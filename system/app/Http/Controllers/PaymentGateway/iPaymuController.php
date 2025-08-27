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
use App\Http\Controllers\ProviderApi\IrvankedesmmController;
use App\Http\Controllers\ProviderApi\PartaisocmedController;
use App\Http\Controllers\ProviderApi\VipMemberController;
use App\Http\Controllers\ProviderApi\IstanaMarketController;
use App\Http\Controllers\ProviderApi\FanstoreController;
use App\Http\Controllers\ProviderApi\RasxmediaController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\WhatsappController;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;

class iPaymuController extends Controller
{
    protected $url = 'https://my.ipaymu.com';
    
    public function __construct()
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $this->virtual_account = $api->ipaymu_va;
        $this->api_key = $api->ipaymu_key;
        $this->wa_admin = $api->nomor_admin;
        $this->status_partaisosmed = $api->partaisosmed_status;
        $this->status_irvankede = $api->irvankede_status;
        $this->status_vipmember = $api->vipmember_status;
        $this->status_istanamarket = $api->istanamarket_status;
        $this->status_fanstore = $api->fanstore_status;
        $this->status_rasxmedia = $api->rasxmedia_status;
    }

    public function requestPayment($harga, $order_id, $nomor, $method, $email,$paymentChannel)
    {
        $body['amount']      = round($harga);
        $body['notifyUrl'] = ENV("APP_URL").'/callback_ipaymu';
        $body['referenceId'] = $order_id;
        $body['name'] = ENV("APP_NAME");
        $body['phone'] = $nomor;
        $body['email'] = $email;
        $body['paymentMethod'] = $method;
        $body['paymentChannel'] = $paymentChannel;
        $body['expired'] = 24;
        $body['expired_type'] = "hours";

        $jsonBody     = json_encode($body, JSON_UNESCAPED_SLASHES);
        $requestBody  = strtolower(hash('sha256', $jsonBody));
        $stringToSign = strtoupper("post") . ':' . $this->virtual_account . ':' . $requestBody . ':' . $this->api_key;
        $signature    = hash_hmac('sha256', $stringToSign, $this->api_key);
        $timestamp    = Date('YmdHis');

        $response = $this->connect('/api/v2/payment/direct', $jsonBody, $signature, $timestamp);
        if($response->Status == 200) {
            if(isset($response->Data->QrString)){
                $paymentNumber = $response->Data->QrString;
            }else{
                $paymentNumber = $response->Data->PaymentNo;
            }
            return array('success' => true, 'amount' => $response->Data->Total, 'no_pembayaran' => $paymentNumber, 'reference' => $response->Data->TransactionId);
        } else {
            return array('success' => false,'msg' => $response->Message);
        }
    }

    public function checkTransaction($transactionId)
    {
        $body['transactionId'] = $transactionId;

        $jsonBody     = json_encode($body, JSON_UNESCAPED_SLASHES);
        $requestBody  = strtolower(hash('sha256', $jsonBody));
        $stringToSign = strtoupper("post") . ':' . $this->virtual_account . ':' . $requestBody . ':' . $this->api_key;
        $signature    = hash_hmac('sha256', $stringToSign, $this->api_key);
        $timestamp    = Date('YmdHis');

        return $this->connect('/api/v2/transaction', $jsonBody, $signature, $timestamp);
    }
    
    public function channel()
    {
        $body['account'] = $this->virtual_account;

        $jsonBody     = json_encode($body, JSON_UNESCAPED_SLASHES);
        $requestBody  = strtolower(hash('sha256', $jsonBody));
        $stringToSign = strtoupper("post") . ':' . $this->virtual_account . ':' . $requestBody . ':' . $this->api_key;
        $signature    = hash_hmac('sha256', $stringToSign, $this->api_key);
        $timestamp    = Date('YmdHis');

        return $this->connect('/api/v2/payment-method-list', $jsonBody, $signature, $timestamp);
    }

    public function connect($endPoint, $body, $signature, $timestamp)
    {
        $ch = curl_init($this->url . $endPoint);

        $headers = array(
            'Content-Type: application/json',
            'va: ' . $this->virtual_account,
            'signature: ' . $signature,
            'timestamp: ' . $timestamp
        );

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $ret = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        if ($err) {
            Log::error('iPaymu API error: ' . $err);
            return $err;
        } else {
            return json_decode($ret);
        }
    }

    public function handle(Request $request)
    {
        $trx = $request->reference_id;
        $pembayaran = Pembayaran::where('order_id', $trx)->where('status', 'Menunggu Pembayaran')->first();
        
        if ($request->status == "berhasil" || $request->status == "pending") {
            $order_id = $pembayaran->order_id;
            if($pembayaran->type == 'deposit') {
                try{
                    $wa = new WhatsappController;
                    $user = User::where('username', $pembayaran->username)->first();
                    $user->update(['balance' => $pembayaran->harga + $user->balance]); 
                    $wa->send($user->phone, "Deposit anda telah berhasil dikonfirmasi oleh sistem sejumlah Rp ".number_format($pembayaran->harga, 0, '.', ',').".");
                    $pembayaran->update(['status' => 'Lunas']);
                }catch (\Exception $e){
                }
            } else if($pembayaran->type == 'upgrade') {
                try{
                    $wa = new WhatsappController;
                    $user = User::where('username', $pembayaran->username)->first();
                    $user->update(['role' => $pembayaran->role]); 
                    $wa->send($user->phone, "Upgrade anda telah berhasil dikonfirmasi oleh sistem dengan role ".$pembayaran->role." Harga ".number_format($pembayaran->harga, 0, '.', ',').".");
                    $pembayaran->update(['status' => 'Lunas']);
                }catch (\Exception $e){
                }
            } else {
                $dataPembeli = Pembelian::where('order_id', $order_id)->first();
                
                $dataLayanan = Layanan::where('id', $dataPembeli->id_layanan)->first();
                
                if(!$dataLayanan) return "Sukses";
                
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
                
                $updatePembayaran = $pembayaran->update(['status' => 'Lunas']);
                
                try{
    		        $wa = new WhatsappController;
                    $wa->send($this->wa_admin, $pesanAdmin);
    		        $wa->send($dataPembeli->no_pembeli, $pesan);
                }catch (\Exception $e){
    		
    	        }
                    
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
                }else if($dataLayanan->provider == "vipmember"){
                    if($this->status_vipmember == "Active") {
                        $vipmember = new vipmember;
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
                        $rasxmedia= new RasxmediaController;
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
                        'status' => 'Pending',
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
            return "Sukses";
        } else if ($request->status == "gagal") {
            $dataPembeli->update([
                'status' => 'Failed'
            ]);
            $pembayaran->update([
                'status' => 'Batal'
            ]);
            return "Sukses";
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
