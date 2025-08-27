<?php
namespace App\Http\Controllers\PaymentGateway;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use App\Models\Pembayaran;
use App\Models\Pembelian;
use App\Models\Layanan;
use App\Models\Kategori;
use App\Models\Voucher;
use App\Models\User;
use App\Http\Controllers\ProviderApi\PartaisocmedController;
use App\Http\Controllers\ProviderApi\IrvankedesmmController;
use App\Http\Controllers\ProviderApi\VipMemberController;
use App\Http\Controllers\ProviderApi\IstanaMarketController;
use App\Http\Controllers\ProviderApi\FanstoreController;
use App\Http\Controllers\ProviderApi\RasxmediaController;
use App\Http\Controllers\WhatsappController;

class XenditController extends Controller
{
    public function createTransaction($amount, $invweb, $pmethod)
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $apiKey = $api->xendit_authkey; // Ganti dengan API Key yang valid
        $headers = [
            'Authorization: Basic ' . base64_encode($apiKey . ':'), // Menggunakan Basic Auth dengan API Key
            'Content-Type: application/json',
            'Accept: application/json',
        ];

        $body = [
            "external_id" => "$invweb",
            "amount" => $amount,
            "redirect_url" => 'https://skylarkshopdiamond.id/pembelian/invoice/'.$invweb,
            "payment_methods" => [
                "$pmethod"
            ]
        ];
        $ch = curl_init('https://api.xendit.co/v2/invoices');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }
    public function createVA($invweb, $pmethod)
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        
        $apiKey = $api->xendit_authkey; // Ganti dengan API Key yang valid
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.xendit.co/callback_virtual_accounts',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
           "external_id": "'.$invweb.'",
           "bank_code": "'.$pmethod.'",
           "name": "'.$api->judul_web.'"
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($apiKey . ':')
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }
    public function createRetail($amount, $invweb, $pmethod)
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        
        $apiKey = $api->xendit_authkey; // Ganti dengan API Key yang valid
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.xendit.co/fixed_payment_code',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
            "external_id": "'.$invweb.'",
            "retail_outlet_name": "'.$pmethod.'",
            "name": "'.$api->judul_web.'",
            "expected_amount": '.$amount.'
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($apiKey . ':')
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        return json_decode($response, true);
    }
    public function createQR($amount, $invweb, $pmethod)
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        
        $apiKey = $api->xendit_authkey; // Ganti dengan API Key yang valid
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.xendit.co/qr_codes',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
           "reference_id": "'.$invweb.'",
           "type": "DYNAMIC",
           "currency": "IDR",
           "amount": '.$amount.'
        }
        ',
          CURLOPT_HTTPHEADER => array(
            'api-version: 2022-07-31',
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($apiKey . ':')
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        return json_decode($response, true);
    }
    public function handle(Request $request)
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $wa = new WhatsappController;
        $xenditCallbackToken = $api->xendit_tokenkey;

        // Bagian ini untuk mendapatkan Token callback dari permintaan header, 
        // yang kemudian akan dibandingkan dengan token verifikasi callback Xendit
        $xIncomingCallbackTokenHeader = $request->header('x-callback-token');

        // Untuk memastikan permintaan datang dari Xendit
        // Anda harus membandingkan token yang masuk sama dengan token verifikasi callback Anda
        // Ini untuk memastikan permintaan datang dari Xendit dan bukan dari pihak ketiga lainnya.
        if ($xIncomingCallbackTokenHeader === $xenditCallbackToken) {
            
            // Permintaan masuk diverifikasi berasal dari Xendit

            // Baris ini untuk mendapatkan semua input pesan dalam format JSON teks mentah
            $rawRequestInput = $request->getContent();
            // Baris ini melakukan format input mentah menjadi array asosiatif
            $arrRequestInput = json_decode($rawRequestInput, true);

            // Melakukan aksi atau pengolahan data sesuai kebutuhan Anda
            $_id = $arrRequestInput['id'];
            $_externalId = $arrRequestInput['external_id'];
            
            $invoice = Pembayaran::where('reference', $_id)->where('status', 'Menunggu Pembayaran')->first();
            
            if(!$invoice){
                return response()->json([
                    'status' => false,
                    'Message' => 'Invoice has already paid!'
                ]);
            }
            
            
                $order_id = $invoice->order_id;
                
                $dataPembeli = Pembelian::where('order_id', $order_id)->first();

                if ($dataPembeli) {
                    $dataLayanan = Layanan::where('id', $dataPembeli->id_layanan)->first();
                
                    if ($dataLayanan) {
                        $dataKategori = Kategori::where('id', $dataLayanan->kategori_id)->first();
                
                        if ($dataKategori) {
                            $pesanPembeli = "Pesanan anda sedang kami Proses,mohon ditunggu!";
                
                            $zoneSend = $dataPembeli->zone == null ? "" : "($dataPembeli->zone)\n";
                            $nickname = $dataPembeli->nickname == null ? '' : "Nickname : $dataPembeli->nickname\n";
                
                            
                        } else {
                            // Handle jika $dataKategori tidak ditemukan
                        }
                    } else {
                        // Handle jika $dataLayanan tidak ditemukan
                    }
                } else {
                    $dataDeposit = Pembayaran::where('order_id', $order_id)->first();
                }
            
            
            $_userId = $arrRequestInput['user_id'];
            $_status = $arrRequestInput['status'];
            
            switch($_status){
                case 'PAID':
                        if($dataDeposit->type == 'deposit'){
                            $userDeposit = User::where('username', $dataDeposit->username)->first();
                            $userDeposit->update(['balance' => $dataDeposit->harga + $userDeposit->balance]);
                            $wa->send($userDeposit->phone, "Deposit anda telah berhasil dikonfirmasi oleh sistem sejumlah Rp ".number_format($dataDeposit->harga, 0, '.', ',').".");
                            $dataDeposit->update(['status' => 'Lunas']);
                        } else if($dataDeposit->type == 'upgrade'){
                            $user = User::where('username', $dataDeposit->username)->first();
                            $user->update(['role' => $dataDeposit->role]); 
                            $wa->send($user->phone, "Upgrade anda telah berhasil dikonfirmasi oleh sistem dengan role ".$dataDeposit->role." Harga ".number_format($dataDeposit->harga, 0, '.', ',').".");
                            $dataDeposit->update(['status' => 'Lunas']);
                        }else{
                             $wa->send($invoice->no_pembeli, $pesanPembeli);
                            
                           
                            if($dataLayanan->provider == "partaisocmed"){
                                if($api->partaisosmed_status == "Active") {
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
                            } else if($dataLayanan->provider == "irvankedesmm"){
                                if($api->irvankede_status == "Active") {
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
                            } else if($dataLayanan->provider == "vipmember"){
                                if($api->vipmember_status == "Active") {
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
                            } else if($dataLayanan->provider == "istanamarket"){
                                if($api->istanamarket_status == "Active") {
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
                            } else if($dataLayanan->provider == "fanstore"){
                                if($api->fanstore_status == "Active") {
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
                            } else if($dataLayanan->provider == "rasxmedia"){
                                if($api->rasxmedia_status == "Active") {
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
                        
                        if ($order['status']) {
                            
                            if($dataPembeli->tipe_transaksi !== 'joki'){
                            
                            $pesanSukses =
                            "Pesanan Dengan Nomor Invoice *$order_id*  Berhasil Dikirim\n\n".
                            "Silahkan cek akun game kamu kak😊🙏.\n\n".
                            "Terima Kasih Sudah Membeli Item di Skylark Shop Semoga Dilancarkan Terus Rezekinya dan sehat selalu yaa❤\n".
                            "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                            "Catatan: \n\n".
                            "Jika item belum diterima pada akun game kamu, mohon tunggu sekitar 5-15 menit.\n".
                            "Jika masih belum diterima silahkan kontak kami dengan mengirimkan No Transaksi biar kami bantu cek ^^\n".
                            "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                            "Customer Support : $token->nomor_admin\n".
                            "Online 24 Jam";
                        
                            $pesanSuksesAdmin = 
                                "*Pembelian Sukses*\n\n" .
                                "No Invoice: *$order_id*\n" .
                                "Layanan: *$dataPembeli->layanan*\n" .
                                "ID : *$dataPembeli->user_id*\n" .
                                "Server : *$dataPembeli->zone*\n" .
                                "Nickname : *$dataPembeli->nickname*\n" .
                                "Harga: *Rp. " . number_format($invoice->harga, 0, '.', ',') . "*\n" .
                                "Status Pembelian: *Sukses*\n\n" .
                                "*Kontak Pembeli*\n" .
                                "No HP : $invoice->no_pembeli\n" .
                            "*Invoice* : " . env("APP_URL") . "/pembelian/invoice/$order_id\n\n" .
                            "INI ADALAH PESAN OTOMATIS";
                            
                
                            $wa->send($api->nomor_admin, $pesanSuksesAdmin);
                            $wa->send($invoice->no_pembeli, $pesanSukses);
                            
                            $dataPembeli->update([
                                'provider_order_id' => isset($order['transactionId']) ? $order['transactionId'] : 0,
                                'status' => 'Pending',
                                'log' => json_encode($order)
                            ]);
                            }else{
                                
                                $pesanSukses =
                                "Pesanan Dengan Nomor Invoice DIISI DENGAN NOMOR INVOICE  Berhasil Dikirim\n\n".
                                "Silahkan cek akun game kamu kak😊🙏.\n\n".
                                "Terima Kasih Sudah Membeli Item di Skylark Shop Semoga Dilancarkan Terus Rezekinya dan sehat selalu yaa❤\n".
                                "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                                "Catatan: \n\n".
                                "Jika item belum diterima pada akun game kamu, mohon tunggu sekitar 5-15 menit.\n".
                                "Jika masih belum diterima silahkan kontak kami dengan mengirimkan No Transaksi biar kami bantu cek ^^\n".
                                "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                                "Customer Support : $token->nomor_admin\n".
                                "Online 24 Jam";
                                
                                
                                $pesanSuksesAdmin = 
                                    "*Pembelian Sukses*\n\n" .
                                    "No Invoice: *$order_id*\n" .
                                    "Layanan: *$dataPembeli->layanan*\n" .
                                    "ID : *$dataPembeli->user_id*\n" .
                                    "Server : *$dataPembeli->zone*\n" .
                                    "Nickname : *$dataPembeli->nickname*\n" .
                                    "Harga: *Rp. " . number_format($invoice->harga, 0, '.', ',') . "*\n" .
                                    "Status Pembelian: *Sukses*\n\n" .
                                    "*Kontak Pembeli*\n" .
                                    "No HP : $invoice->no_pembeli\n" .
                                    "*Invoice* : " . env("APP_URL") . "/pembelian/invoice/$order_id\n\n" .
                                    "INI ADALAH PESAN OTOMATIS";
                    
                                    $wa->send($token->nomor_admin, $pesanSuksesAdmin);
                                    $wa->send($invoice->no_pembeli, $pesanSukses);
                            }
                        }else{
                            if($dataPembeli->tipe_transaksi !== 'joki'){
                            
                                $dataPembeli->update([
                                    'note' => $note,
                                    'status' => 'Failed',
                                    'log' => json_encode($order)
                                ]);
                            }
                        }
                    }
                
                $invoice->update(['status' => 'Lunas']);
                
                break;
                case 'EXPIRED':
                    
                    $invoice->update(['status' => 'EXPIRED']);    
                
                break;
                case 'FAILED':
                
                    $invoice->update(['status' => 'FAILED']);
                
                break;
                
                default:
                    return Response::json([
                        'success' => false,
                        'message' => 'Unrecognized payment status',
                    ]);
            }
            $_paidAmount = $arrRequestInput['paid_amount'];
            $_paidAt = $arrRequestInput['paid_at'];
            $_paymentChannel = $arrRequestInput['payment_channel'];
            // $_paymentDestination = $arrRequestInput['payment_destination'];

            // Kamu bisa menggunakan array objek di atas sebagai informasi callback yang dapat digunakan untuk melakukan pengecekan atau aktivasi tertentu di aplikasi atau sistem kamu.

            return response()->json(['success' => true]);
        } else {
            // Permintaan bukan dari Xendit, tolak dan buang pesan dengan HTTP status 403
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    }
}
