<?php
namespace App\Http\Controllers\PaymentGateway;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Kategori;
use App\Models\Pembelian;
use App\Models\Layanan;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ProviderApi\PartaisocmedController;
use App\Http\Controllers\ProviderApi\IrvankedesmmController;
use App\Http\Controllers\ProviderApi\VipMemberController;
use App\Http\Controllers\ProviderApi\IstanaMarketController;
use App\Http\Controllers\ProviderApi\FanstoreController;
use App\Http\Controllers\ProviderApi\RasxmediaController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\WhatsappController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use App\Mail\SendEmail;

class PaydisiniController extends Controller
{
    public function __construct()
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $this->api_key = $api->paydisini_key;
        $this->merchant = $api->paydisini_merchant;
        $this->wa_admin = $api->nomor_admin;
        $this->status_partaisosmed = $api->partaisosmed_status;
        $this->status_irvankede = $api->irvankede_status;
        $this->status_vipmember = $api->vipmember_status;
        $this->status_istanamarket = $api->istanamarket_status;
        $this->status_fanstore = $api->fanstore_status;
        $this->status_rasxmedia = $api->rasxmedia_status;
    }

    public function requestPayment($harga, $order_id, $nomor, $method, $email, $returnURL)
    {
        $sign = md5($this->api_key . $order_id . $method . $harga . '10800' . 'NewTransaction');
        $body = array(
            'key' => $this->api_key,
            'request' => 'new',
            'unique_code' => $order_id,
            'service' => $method,
            'amount' => $harga,
            'customer_email' => $email,
            'note' => 'Pembayaran '.$order_id,
            'valid_time' => '10800',
            'type_fee' => '1',
            'payment_guide' => TRUE, // Pilih TRUE jika ingin menampilkan panduan pembayaran
            'callback_count' => ENV("APP_URL")."/callback_paydisini",
            'signature' => 'ba8427311e3d002bfa52a48ad46c04d2',
            'return_url' => $returnURL
        );

        $response = $this->connect($body);
        if($response->success == true) {
            if(empty($data->data->virtual_account)){
                if(empty($data->data->checkout_url)){
                    if(empty($data->data->payment_code)){
                        $paymentNumber = $data->data->qr_content;
                    } else {
                        $paymentNumber = $data->data->payment_code;
                    }
                }else{
                    $paymentNumber = $data->data->checkout_url;
                }
            }else{
                $paymentNumber = $data->data->virtual_account;
            }
            return array('success' => true, 'amount' => $response->data->amount, 'no_pembayaran' => $paymentNumber, 'reference' => $response->data->pay_id, 'checkout_url' => $data->data->checkout_url);
        } else {
            return array('success' => false,'msg' => $response->msg);
        }
    }

    public function checkTransaction($transactionId)
    {
        $sign = md5($this->api_key . $transactionId . 'StatusTransaction');
        $body = array(
            'key' => $this->api_key,
            'request' => 'status',
            'unique_code' => $transactionId,
            'signature' => $sign
        );

        return $this->connect($body);
    }
    
    public function channel()
    {
        $sign = md5($this->api_key . 'PaymentChannel');
        $body = array(
            'key' => $this->api_key,
            'request' => 'payment_channel',
            'signature' => $sign
        );

        return $this->connect($body);
    }

    public function connect($body)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://paydisini.co.id/api/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        
        $err = curl_error($ch);
        $ret = curl_exec($ch);
        curl_close($ch);
        if ($err) {
            return $err;
        } else {
            return json_decode($ret);
        }
    }

    public function handle(Request $request)
    {
        $json = $request->getContent();
        $data = json_decode($json);
        
        $trx = $data->unique_code;
        $pembayaran = Pembayaran::where('order_id', $trx)->where('status', 'Menunggu Pembayaran')->first();
        $wa = new WhatsappController;
        try{
            if (!$pembayaran) {
                return Response::json(['error' => 'No invoice found or already paid: ' . $trx])->setStatusCode(400);
            } else {
                if ($data->status == "Success") {
                    $order_id = $pembayaran->order_id;
                    if($pembayaran->type == 'deposit') {
                        
                        $user = User::where('username', $pembayaran->username)->first();
                        $user->update(['balance' => $pembayaran->harga + $user->balance]); 
                        $wa->send($user->phone, "Deposit anda telah berhasil dikonfirmasi oleh sistem sejumlah Rp ".number_format($pembayaran->harga, 0, '.', ',').".");
                        $pembayaran->update(['status' => 'Lunas']);
                        
                    } else if($pembayaran->type == 'upgrade') {
                        
                        $user = User::where('username', $pembayaran->username)->first();
                        $user->update(['role' => $pembayaran->role]); 
                        $wa->send($user->phone, "Upgrade anda telah berhasil dikonfirmasi oleh sistem dengan role ".$pembayaran->role." Harga ".number_format($pembayaran->harga, 0, '.', ',').".");
                        $pembayaran->update(['status' => 'Lunas']);
                        
                    } else {
                        $dataPembeli = Pembelian::where('order_id', $order_id)->first();
                        
                        $dataLayanan = Layanan::where('id', $dataPembeli->id_layanan)->first();
                        
                        if(!$dataLayanan) return Response::json(['success' => false,'error'=> "Layanan tidak tersedia."])->setStatusCode(401);
                        
                        $pembayaran = Pembayaran::where('order_id', $order_id)->first();
                        $dataKategori = Kategori::where('id', $dataLayanan->kategori_id)->first();
                
                        $pesan = 
                                "PEMBAYARAN BERHASIL\n".
                                "❍➤ Informasi pembelian\n".
                                "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                                "❃ ➤ Tujuan : *$dataPembeli->uid*\n".
                                "❃ ➤ Layanan :  *$dataKategori->nama*\n".
                                "❃ ➤ Produk : *$dataLayanan->layanan*\n" .
                                "❃ ➤ Total Harga : *Rp. " . number_format($pembayaran->harga, 0, '.', ',') . "*\n" .
                                "❃ ➤ Pembayaran : *$pembayaran->metode*\n" .
                                "❃ ➤ Status : *Lunas*\n" .
                                "❃ ➤ Nomor Invoice : *$order_id*\n\n".
                                "" . env("APP_URL") . "/pembelian/invoice/$order_id\n\n" .
                                "Ini Adalah Pesan Otomatis, Jika Mengalami Kendala Bisa Menghubungi WA CS Di Bawah:\n".
                                "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                                "Customer Support : ".$this->filter_phone('0',$this->wa_admin)."\n".
                                "Online 24 Jam";
                            $pesanAdmin = 
                                "*PEMBAYARAN BERHASIL #$order_id* TELAH LUNAS\n".
                                "❍➤ Informasi pembelian\n".
                                "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                                "❃ ➤ Tujuan : *$dataPembeli->uid* (*$dataPembeli->zone*)\n".
                                "❃ ➤ Nickname : $dataPembeli->nickname\n".
                                "❃ ➤ Layanan :  *$dataKategori->nama*\n".
                                "❃ ➤ Produk : *$dataLayanan->layanan*\n" .
                                "❃ ➤ Total Harga : *Rp. " . number_format($pembayaran->harga, 0, '.', ',') . "*\n" .
                                "❃ ➤ Pembayaran : *$pembayaran->metode*\n" .
                                "❃ ➤ Status : *Lunas*\n" .
                                "❃ ➤ Nomor Invoice : *$order_id*\n".
                                "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                                "*Kontak Pembeli*\n".
                                "No HP : $pembayaran->no_pembeli";
                        
                        $pembayaran->update(['status' => 'Lunas']);
                        
                        $wa->send($this->wa_admin, $pesanAdmin);
            		    $wa->send($dataPembeli->no_pembeli, $pesan);
                            
                        if($dataLayanan->provider == "partaisocmed"){
                            if($this->status_partaisosmed == "Active") {
                                $partaisocmed = new PartaisocmedController;
                                $order = $partaisocmed->order($dataPembeli->user_id, $dataLayanan->provider_id, $dataPembeli->quantity, $dataPembeli->keywords, $dataPembeli->custom_comments, $dataPembeli->usernames, $dataPembeli->hashtag, $dataPembeli->media, $dataPembeli->answer_number,
                            $dataPembeli->minimal, $dataPembeli->maximal, $dataPembeli->post, $dataPembeli->old_post, $dataPembeli->delay, $dataPembeli->expiry);
                                    
                                if($order['result']){
                                    $order['status'] = true;
                                    $order['transactionId'] = $order['data']['trxid'];
                                }else{
                                    $order['status'] = false;
                                }
                            } else {
                    		    $note = 'Transaksi bermasalah, silahkan hubungi admin!';
                                $order['status'] = false;
                            }
                        }else if($dataLayanan->provider == "irvankedesmm"){
                            if($this->status_irvankede == "Active") {
                                $irvankedesmm = new IrvankedesmmController;
                                $order = $irvankedesmm->order($dataPembeli->user_id, $dataLayanan->provider_id, $dataPembeli->quantity, $dataPembeli->custom_comments, $dataPembeli->usernames, $dataPembeli->hashtag, $dataPembeli->media, $dataPembeli->answer_number);
                                    
                                if($order['result']){
                                    $order['status'] = true;
                                    $order['transactionId'] = $order['data']['trxid'];
                                }else{
                                    $order['status'] = false;
                                }
                            } else {
                    		    $note = 'Transaksi bermasalah, silahkan hubungi admin!';
                                $order['status'] = false;
                            }
                        }else if($dataLayanan->provider == "istanamarket"){
                            if($this->status_istanamarket == "Active") {
                                $istanamarket = new IstanaMarketController;
                                $order = $istanamarket->order($dataPembeli->user_id, $dataLayanan->provider_id, $dataPembeli->quantity, $dataPembeli->custom_comments);
                                    
                                if($order['result']){
                                    $order['status'] = true;
                                    $order['transactionId'] = $order['data']['trxid'];
                                }else{
                                    $order['status'] = false;
                                }
                            } else {
                    		    $note = 'Transaksi bermasalah, silahkan hubungi admin!';
                                $order['status'] = false;
                            }
                        }else if($dataLayanan->provider == "vipmember"){
                            if($this->status_vipmember == "Active") {
                                $vipmember = new VipMemberController;
                                $order = $vipmember->order($dataPembeli->user_id, $dataLayanan->provider_id, $dataPembeli->quantity, $dataPembeli->usernames, $dataPembeli->answer_number, $dataPembeli->custom_comments);
                                    
                                if($order['result']){
                                    $order['status'] = true;
                                    $order['transactionId'] = $order['data']['trxid'];
                                }else{
                                    $order['status'] = false;
                                }
                            } else {
                    		    $note = 'Transaksi bermasalah, silahkan hubungi admin!';
                                $order['status'] = false;
                            }
                        }else if($dataLayanan->provider == "fanstore"){
                            if($this->status_fanstore == "Active") {
                                $fanstore = new FanstoreController;
                                $order = $fanstore->order($dataPembeli->user_id, $dataLayanan->provider_id, $dataPembeli->quantity);
                                    
                                if($order['result']){
                                    $order['status'] = true;
                                    $order['transactionId'] = $order['data']['trxid'];
                                }else{
                                    $order['status'] = false;
                                }
                            } else {
                    		    $note = 'Transaksi bermasalah, silahkan hubungi admin!';
                                $order['status'] = false;
                            }
                        }else if($dataLayanan->provider == "rasxmedia"){
                            if($this->status_rasxmedia == "Active") {
                                $rasxmedia = new RasxmediaController;
                                $order = $rasxmedia->order($dataPembeli->user_id, $dataLayanan->provider_id, $dataPembeli->quantity, $dataPembeli->custom_comments);
                                    
                                if($order['result']){
                                    $order['status'] = true;
                                    $order['transactionId'] = $order['data']['trxid'];
                                }else{
                                    $order['status'] = false;
                                }
                            } else {
                    		    $note = 'Transaksi bermasalah, silahkan hubungi admin!';
                                $order['status'] = false;
                            }
                        }else if($dataLayanan->provider == "manual"){
                            $order['transactionId'] = '';
                            $order['status'] = true;
                        }
                        
                        if ($order['status']) { // Jika pembelian sukses
                            Mail::to($pembayaran->email_pembeli)->send(new SendEmail([
                                'pembelian' => $dataPembeli,
                                'kategori' => $dataKategori,
                                'pembayaran' => $pembayaran
                            ]));
                            $dataPembeli->update([
                                'note' => 'Sedang diproses...',
                                'provider_order_id' => isset($order['transactionId']) ? $order['transactionId'] : null,
                                'status' => 'Success',
                                'log' => json_encode($order)
                            ]);
            
                        } else { //jika pembelian gagal
            
                            $dataPembeli->update([
                                'note' => $note,
                                'status' => 'Failed',
                                'log' => json_encode($order)
                            ]);
            
                        }
                    }
                    return Response()->json(['success' => true])->setStatusCode(200);
                } else {
                    $dataPembeli->update([
                        'status' => 'Failed'
                    ]);
                    $pembayaran->update([
                        'status' => 'Batal'
                    ]);
                    return Response()->json(['success' => true])->setStatusCode(201);
                }
            }
        } catch (\Exception $ex) {
            return Response()->json(['success' => false, 'error' => $ex->getMessage()])->setStatusCode(500);
        }
    }
    public function filter_phone($type,$number)
    {
        $phone = preg_replace("/[^0-9]/", '', $number);
        if($type == '0') {
            if(substr($phone,0,3) == '+62'){ $change = '0'.substr($phone,3); }
            else if(substr($phone, 0, 2) == '62'){ $change = '0'.substr($phone,2); }
            else if(substr($phone, 0, 1) == '0') { $change = $phone; }
        } else {
            if(substr($phone,0,3) == '+62'){ $change = substr($phone,1); }
            else if(substr($phone, 0, 2) == '62'){ $change = $phone; }
            else if(substr($phone, 0, 1) == '0') { $change = '62'.substr($phone,1); }
        }
        return $change;
    }
}
