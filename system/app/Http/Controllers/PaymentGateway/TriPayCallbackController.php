<?php
namespace App\Http\Controllers\PaymentGateway;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Pembelian;
use App\Models\Layanan;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ProviderApi\PartaisocmedController;
use App\Http\Controllers\ProviderApi\IrvankedesmmController;
use App\Http\Controllers\ProviderApi\IstanaMarketController;
use App\Http\Controllers\ProviderApi\VipMemberController;
use App\Http\Controllers\ProviderApi\FanstoreController;
use App\Http\Controllers\ProviderApi\RasxmediaController;
use App\Http\Controllers\WhatsappController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;

class TriPayCallbackController extends Controller
{
    public function handle(Request $request)
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $callbackSignature = $request->server('HTTP_X_CALLBACK_SIGNATURE');
        $private_key = isset($api->tripay_private_key) ? $api->tripay_private_key : '-';
        $json = $request->getContent();
        $signature = hash_hmac('sha256', $json, $private_key);

        if ($signature !== (string) $callbackSignature) {
            return 'Invalid signature';
        }

        if ('payment_status' !== (string) $request->server('HTTP_X_CALLBACK_EVENT')) {
            return 'Invalid callback event, no action was taken';
        }

        $data = json_decode($json);
        $ref = $data->reference;

        $invoice = Pembayaran::where('reference', $ref)
            ->where('status', 'Menunggu Pembayaran')
            ->first();

        $order_id = $invoice->order_id;
        $dataPembeli = Pembelian::where('order_id', $order_id)->first();
        if (!$invoice) {
            return 'Invoice not found or current status is not UNPAID';
        }

        if (intval($data->total_amount) !== (int) $invoice->harga) {
            return 'Invalid amount';
        }

        if ($data->status == "PAID") {
            if($invoice->type == 'deposit') {
                try{
                    $wa = new WhatsappController;
                    $user = User::where('username', $invoice->username)->first();
                    $user->update(['balance' => $invoice->harga + $user->balance]); 
                    $wa->send($user->phone, "Deposit anda telah berhasil dikonfirmasi oleh sistem sejumlah Rp ".number_format($invoice->harga, 0, '.', ',').".");
                    $invoice->update(['status' => 'Lunas']);
                }catch (\Exception $e){
                }
            } else if($invoice->type == 'upgrade') {
                try{
                    $wa = new WhatsappController;
                    $user = User::where('username', $invoice->username)->first();
                    $user->update(['role' => $invoice->role]); 
                    $wa->send($user->phone, "Upgrade anda telah berhasil dikonfirmasi oleh sistem dengan role ".$invoice->role." Harga ".number_format($invoice->harga, 0, '.', ',').".");
                    $invoice->update(['status' => 'Lunas']);
                }catch (\Exception $e){
                }
            } else {
        $dataLayanan = Layanan::where('id', $dataPembeli->id_layanan)->first();
        $dataKategori = Kategori::where('id', $dataLayanan->kategori_id)->first();

        $pesan = 
                "PEMBAYARAN BERHASIL\n".
                "❍➤ Informasi pembelian\n".
                "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                "❃ ➤ Tujuan : *$dataPembeli->uid*\n".
                "❃ ➤ Layanan :  *$dataKategori->nama*\n".
                "❃ ➤ Produk : *$dataLayanan->layanan*\n" .
                "❃ ➤ Total Harga : *Rp. " . number_format($invoice->harga, 0, '.', ',') . "*\n" .
                "❃ ➤ Pembayaran : *$invoice->metode*\n" .
                "❃ ➤ Status : *Lunas*\n" .
                "❃ ➤ Nomor Invoice : *$order_id*\n\n".
                "" . env("APP_URL") . "/pembelian/invoice/$order_id\n\n" .
                "Ini Adalah Pesan Otomatis, Jika Mengalami Kendala Bisa Menghubungi WA CS Di Bawah:\n".
                "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                "Customer Support : ".$this->filter_phone('0',$api->nomor_admin)."\n".
                "Online 24 Jam";
        $pesanAdmin = 
            "*PEMBAYARAN BERHASIL #$order_id* TELAH LUNAS\n".
            "❍➤ Informasi pembelian\n".
            "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
            "❃ ➤ Tujuan : *$dataPembeli->uid* (*$dataPembeli->zone*)\n".
            "❃ ➤ Nickname : $dataPembeli->nickname\n".
            "❃ ➤ Layanan :  *$dataKategori->nama*\n".
            "❃ ➤ Produk : *$dataLayanan->layanan*\n" .
            "❃ ➤ Total Harga : *Rp. " . number_format($invoice->harga, 0, '.', ',') . "*\n" .
            "❃ ➤ Pembayaran : *$invoice->metode*\n" .
            "❃ ➤ Status : *Lunas*\n" .
            "❃ ➤ Nomor Invoice : *$order_id*\n".
            "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
            "*Kontak Pembeli*\n".
            "No HP : $invoice->no_pembeli";

        $uid = $dataPembeli->user_id;
        $zone = $dataPembeli->zone;
        $provider_id = $dataLayanan->provider_id;
                try{
                    $wa = new WhatsappController;
    		        $requestPesan = $wa->send($api->wa_admin, $pesanAdmin);
    		        $pesanMember = $wa->send($dataPembeli->no_pembeli, $pesan);
                }catch (\Exception $e){
    		
    	        }

                    if($dataLayanan->provider == "partaisocmed"){
                        if($api->partaisosmed_status == "Active") {
                            $partaisocmed = new PartaisocmedController;
                            $order = $partaisocmed->order($uid, $provider_id, $dataPembeli->quantity, $dataPembeli->keywords, $dataPembeli->custom_comments, $dataPembeli->usernames, $dataPembeli->hashtag, $dataPembeli->media, $dataPembeli->answer_number,
                            $dataPembeli->minimal, $dataPembeli->maximal, $dataPembeli->post, $dataPembeli->old_post, $dataPembeli->delay, $dataPembeli->expiry);
                            
                            if($order['result']){
                                $order['status'] = $order['result'];
                                $order['transactionId'] = $order['data']['trxid'];
                            }else{
                                $order['status'] = false;
                            }
                        } else {
                            $note = 'Transaksi bermasalah, silahkan hubungi admin!';
                            $order['status'] = false;
                        }
                    }else if($dataLayanan->provider == "irvankedesmm"){
                        if($api->irvankede_status == "Active") {
                            $irvankedesmm = new IrvankedesmmController;
                            $order = $irvankedesmm->order($uid, $provider_id, $dataPembeli->quantity, $dataPembeli->custom_comments, $dataPembeli->usernames, $dataPembeli->hashtag, $dataPembeli->media, $dataPembeli->answer_number);
                            
                            if($order['result']){
                                $order['status'] = $order['result'];
                                $order['transactionId'] = $order['data']['trxid'];
                            }else{
                                $order['status'] = false;
                            }
                        } else {
                            $note = 'Transaksi bermasalah, silahkan hubungi admin!';
                            $order['status'] = false;
                        }
                    }else if($dataLayanan->provider == "vipmember"){
                        if($api->vipmember_status == "Active") {
                            $vipmember = new VipMemberController;
                            $order = $vipmember->order($uid, $provider_id, $dataPembeli->quantity, $dataPembeli->usernames, $dataPembeli->answer_number, $dataPembeli->custom_comments);
                            
                            if($order['result']){
                                $order['status'] = $order['result'];
                                $order['transactionId'] = $order['data']['trxid'];
                            }else{
                                $order['status'] = false;
                            }
                        } else {
                            $note = 'Transaksi bermasalah, silahkan hubungi admin!';
                            $order['status'] = false;
                        }
                    }else if($dataLayanan->provider == "istanamarket"){
                        if($api->istanamarket_status == "Active") {
                            $istanamarket = new IstanaMarketController;
                            $order = $istanamarket->order($uid, $provider_id, $dataPembeli->quantity, $dataPembeli->custom_comments);
                            
                            if($order['result']){
                                $order['status'] = $order['result'];
                                $order['transactionId'] = $order['data']['trxid'];
                            }else{
                                $order['status'] = false;
                            }
                        } else {
                            $note = 'Transaksi bermasalah, silahkan hubungi admin!';
                            $order['status'] = false;
                        }
                    }else if($dataLayanan->provider == "fanstore"){
                        if($api->fanstore_status == "Active") {
                            $fanstore = new FanstoreController;
                            $order = $fanstore->order($uid, $provider_id, $dataPembeli->quantity);
                            
                            if($order['result']){
                                $order['status'] = $order['result'];
                                $order['transactionId'] = $order['data']['trxid'];
                            }else{
                                $order['status'] = false;
                            }
                        } else {
                            $note = 'Transaksi bermasalah, silahkan hubungi admin!';
                            $order['status'] = false;
                        }
                    }else if($dataLayanan->provider == "rasxmedia"){
                        if($api->rasxmedia_status == "Active") {
                            $rasxmedia = new RasxmediaController;
                            $order = $rasxmedia->order($uid, $provider_id, $dataPembeli->quantity, $dataPembeli->custom_comments);
                            
                            if($order['result']){
                                $order['status'] = $order['result'];
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
                        
                        Mail::to($invoice->email_pembeli)->send(new SendEmail([
                            'pembelian' => $dataPembeli,
                            'kategori' => $dataKategori,
                            'pembayaran' => $invoice
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
                $invoice->update(['status' => 'Lunas']);
            }
            return response()->json(['success' => true]);
        } else if ($data->status == "EXPIRED" || $data->status == "FAILED") {
            $dataPembeli->update(['status' => 'Failed', 'note' => 'Pembayaran telah kadaluarsa!']);
            $invoice->update(['status' => 'Expired']);
            return response()->json(['success' => true]);

        } else {

            return response()->json(['error' => 'Unrecognized payment status']);

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