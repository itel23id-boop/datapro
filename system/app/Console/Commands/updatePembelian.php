<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Namdevel\GojekPay;
use Namdevel\Ovo;
use App\Models\Pembayaran;
use App\Models\Pembelian;
use App\Models\Layanan;
use App\Models\LayananPpob;
use App\Models\Kategori;
use App\Models\Gojek;
use App\Models\Ovo as OvoModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ProviderApi\PartaisocmedController;
use App\Http\Controllers\ProviderApi\IrvankedesmmController;
use App\Http\Controllers\ProviderApi\VipMemberController;
use App\Http\Controllers\ProviderApi\IstanaMarketController;
use App\Http\Controllers\ProviderApi\FanstoreController;
use App\Http\Controllers\ProviderApi\RasxmediaController;
use App\Http\Controllers\PaymentGateway\iPaymuController;
use App\Http\Controllers\PaymentGateway\LinkQuController;
use App\Http\Controllers\PaymentGateway\CekMutasiController;
use App\Http\Controllers\WhatsappController;
use Illuminate\Support\Facades\Http;
use App\Helpers\Formater;

class updatePembelian extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:pembelian';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = now();
        $parsingDate = Carbon::parse($date);
        $datas = Pembayaran::where('status', 'Menunggu Pembayaran')
            ->whereMonth('created_at', $parsingDate->month)
            ->whereYear('created_at', $parsingDate->year)
            ->get();
        $wa = new WhatsappController;
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $formatter = new Formater;
        if (count($datas) != 0) {

            foreach ($datas as $data) {
    
                $tomorrow = Carbon::create($data->created_at)->addDay();
                $pembelian = Pembelian::where('order_id', $data->order_id)->select('user_id', 'zone', 'layanan', 'tipe_transaksi', 'nickname')->first();
                $pembayaran = Pembayaran::where('order_id', $data->order_id)->select('harga','order_id','no_pembeli', 'metode')->first();
                
                try{
                    $layanan = Layanan::where('id', $pembelian->id_layanan)->select('provider_id', 'kategori_id', 'provider', 'tipe', 'kode')->first();
                    
                    $kategori = Kategori::where('id', $layanan->kategori_id)->first();

                }catch(\Exception $e){
                    continue;
                }

                $pesan = 
                        "PEMBAYARAN BERHASIL\n".
                        "❍➤ Informasi pembelian\n".
                        "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                        "❃ ➤ Tujuan : *$pembelian->user_id*\n".
                        "❃ ➤ Layanan :  *$kategori->nama*\n".
                        "❃ ➤ Produk : *$layanan->layanan*\n" .
                        "❃ ➤ Total Harga : *Rp. " . number_format($pembayaran->harga, 0, '.', ',') . "*\n" .
                        "❃ ➤ Pembayaran : *$pembayaran->metode*\n" .
                        "❃ ➤ Status : *Lunas*\n" .
                        "❃ ➤ Nomor Invoice : *$data->order_id*\n\n".
                        "" . env("APP_URL") . "/pembelian/invoice/$data->order_id\n\n" .
                        "Ini Adalah Pesan Otomatis, Jika Mengalami Kendala Bisa Menghubungi WA CS Di Bawah:\n".
                        "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                        "Customer Support : ".$formatter->filter_phone('0',$api->nomor_admin)."\n".
                        "Online 24 Jam";
                $pesanAdmin = 
                    "*PEMBAYARAN BERHASIL #$data->order_id* TELAH LUNAS\n".
                    "❍➤ Informasi pembelian\n".
                    "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                    "❃ ➤ Tujuan : *$pembelian->user_id* (*$pembelian->zone*)\n".
                    "❃ ➤ Nickname : $pembelian->nickname\n".
                    "❃ ➤ Layanan :  *$kategori->nama*\n".
                    "❃ ➤ Produk : *$layanan->layanan*\n" .
                    "❃ ➤ Total Harga : *Rp. " . number_format($pembayaran->harga, 0, '.', ',') . "*\n" .
                    "❃ ➤ Pembayaran : *$pembayaran->metode*\n" .
                    "❃ ➤ Status : *Lunas*\n" .
                    "❃ ➤ Nomor Invoice : *$data->order_id*\n".
                    "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                    "*Kontak Pembeli*\n".
                    "No HP : $pembayaran->no_pembeli";

                $irvankedesmm= new IrvankedesmmController;
                $partaisocmed= new PartaisocmedController;
                $vipmember= new VipMemberController;
                $istanamarket= new IstanaMarketController;
                $fanstore= new FanstoreController;
                $rasxmedia= new RasxmediaController;
                $linkqu = new LinkQuController;
                if ($date > $tomorrow && $date > $data->created_at) {

                    Pembayaran::where('order_id', $data->order_id)->update(['status' => 'Expired']);
                    Pembelian::where('order_id', $data->order_id)->update(['status' => 'Failed', 'note' => 'Pembayaran anda kadaluarsa.']);
                    
                } else if($data->provider == "linkqu"){
                    
                    $list_transaksi = $linkqu->checkTransaction($data->order_id);
                    
                    foreach ($list_transaksi as $data_linkqu) {
                        if ($data_linkqu->rc == "00" && $data_linkqu->data->status_trx == "success") {
                            try {
                                $requestPesan = $wa->send($api->nomor_admin,$pesanAdmin);
                                $pesanMember = $wa->send($data->no_pembeli, $pesan);
                                
                                Pembayaran::where('order_id', $data->order_id)->update(['status' => 'Lunas']);

                                if($layanan->provider == "partaisocmed"){
                                    $order = $partaisocmed->order($pembelian->user_id, $layanan->provider_id, $pembelian->quantity, $pembelian->keywords, $pembelian->custom_comments, $pembelian->usernames, $pembelian->hashtag, $pembelian->media, $pembelian->answer_number,
                            $pembelian->minimal, $pembelian->maximal, $pembelian->post, $pembelian->old_post, $pembelian->delay, $pembelian->expiry);
                                        
                                    if($order['result']){
                                        $order['status'] = true;
                                        $order['transactionId'] = $order['data']['trxid'];
                                    }else{
                                        $order['status'] = false;
                                    }
                                }else if($layanan->provider == "irvankedesmm"){
                                    $order = $irvankedesmm->order($pembelian->user_id, $layanan->provider_id, $pembelian->quantity, $pembelian->custom_comments, $pembelian->usernames, $pembelian->hashtag, $pembelian->media, $pembelian->answer_number);
                                        
                                    if($order['result']){
                                        $order['status'] = true;
                                        $order['transactionId'] = $order['data']['trxid'];
                                    }else{
                                        $order['status'] = false;
                                    }
                                }else if($layanan->provider == "vipmember"){
                                    $order = $vipmember->order($pembelian->user_id, $layanan->provider_id, $pembelian->quantity, $pembelian->usernames, $pembelian->answer_number, $pembelian->custom_comments);
    
                                    if($order['result']){
                                        $order['status'] = true;
                                        $order['transactionId'] = $order['data']['trxid'];
                                    }else{
                                        $order['status'] = false;
                                    }
                                }else if($layanan->provider == "istanamarket"){
                                    $order = $istanamarket->order($pembelian->user_id, $layanan->provider_id, $pembelian->quantity, $pembelian->custom_comments);
    
                                    if($order['result']){
                                        $order['status'] = true;
                                        $order['transactionId'] = $order['data']['trxid'];
                                    }else{
                                        $order['status'] = false;
                                    }
                                }else if($layanan->provider == "fanstore"){
                                    $order = $fanstore->order($pembelian->user_id, $layanan->provider_id, $pembelian->quantity);
    
                                    if($order['result']){
                                        $order['status'] = true;
                                        $order['transactionId'] = $order['data']['trxid'];
                                    }else{
                                        $order['status'] = false;
                                    }
                                }else if($layanan->provider == "rasxmedia"){
                                    $order = $rasxmedia->order($pembelian->user_id, $layanan->provider_id, $pembelian->quantity, $pembelian->custom_comments);
    
                                    if($order['result']){
                                        $order['status'] = true;
                                        $order['transactionId'] = $order['data']['trxid'];
                                    }else{
                                        $order['status'] = false;
                                    }
                                }

                                if($order['status']){
                                    $updatePembelian = Pembelian::where('order_id', $data->order_id)->update([
                                        'note' => 'Pesanan DiProses',
                                        'provider_order_id' => $order['transactionId'] ? $order['transactionId'] : "",
                                        'status' => 'Pending',
                                        'log' => json_encode($order)
                                    ]);
                                    Log::info('Order ID : ' . $data->order_id . ' Sukses');
                                }else{
                                    Pembelian::where('order_id', $data->order_id)->update([
                                        'note' => 'Pesanan Gagal!',
                                        'log' => json_encode($order),
                                        'status' => 'Failed'
                                    ]);
                                }
                            } catch (\Exception $e) {
                                // throw $e;
                                continue;
                            }

                        }

                    } 
                } else if($data->provider == "manual" && $data->metode_code == "GOPAY_MANUAL"){

                    $authToken = Gojek::select('auth_token')->latest()->first();
                    
                    if(!$authToken) continue;
                    
                    $app = new GojekPay($authToken->auth_token);
                    $getData = json_decode($app->getTransactionHistory(), true);
                    $list_transaksi = $getData['data']['success'];
                    
                    foreach ($list_transaksi as $transfer) {
                        if ($transfer['type'] == "credit" && $transfer['amount']['value'] == $pembayaran->harga) {
                            
                            try {
                                $requestPesan = $wa->send($api->nomor_admin,$pesanAdmin);
                                $pesanMember = $wa->send($data->no_pembeli, $pesan);
                                
                                    Pembayaran::where('order_id', $data->order_id)->update(['status' => 'Lunas']);

                                    if($layanan->provider == "partaisocmed"){
                                        $order = $partaisocmed->order($pembelian->user_id, $layanan->provider_id, $pembelian->quantity, $pembelian->keywords, $pembelian->custom_comments, $pembelian->usernames, $pembelian->hashtag, $pembelian->media, $pembelian->answer_number,
                                $pembelian->minimal, $pembelian->maximal, $pembelian->post, $pembelian->old_post, $pembelian->delay, $pembelian->expiry);
                                            
                                        if($order['result']){
                                            $order['status'] = true;
                                            $order['transactionId'] = $order['data']['trxid'];
                                        }else{
                                            $order['status'] = false;
                                        }
                                    }else if($layanan->provider == "irvankedesmm"){
                                        $order = $irvankedesmm->order($pembelian->user_id, $layanan->provider_id, $pembelian->quantity, $pembelian->custom_comments, $pembelian->usernames, $pembelian->hashtag, $pembelian->media, $pembelian->answer_number);
                                            
                                        if($order['result']){
                                            $order['status'] = true;
                                            $order['transactionId'] = $order['data']['trxid'];
                                        }else{
                                            $order['status'] = false;
                                        }
                                    }else if($layanan->provider == "vipmember"){
                                        $order = $vipmember->order($pembelian->user_id, $layanan->provider_id, $pembelian->quantity, $pembelian->usernames, $pembelian->answer_number, $pembelian->custom_comments);
        
                                        if($order['result']){
                                            $order['status'] = true;
                                            $order['transactionId'] = $order['data']['trxid'];
                                        }else{
                                            $order['status'] = false;
                                        }
                                    }else if($layanan->provider == "istanamarket"){
                                        $order = $istanamarket->order($pembelian->user_id, $layanan->provider_id, $pembelian->quantity, $pembelian->custom_comments);
        
                                        if($order['result']){
                                            $order['status'] = true;
                                            $order['transactionId'] = $order['data']['trxid'];
                                        }else{
                                            $order['status'] = false;
                                        }
                                    }else if($layanan->provider == "fanstore"){
                                        $order = $fanstore->order($pembelian->user_id, $layanan->provider_id, $pembelian->quantity);
        
                                        if($order['result']){
                                            $order['status'] = true;
                                            $order['transactionId'] = $order['data']['trxid'];
                                        }else{
                                            $order['status'] = false;
                                        }
                                    }else if($layanan->provider == "rasxmedia"){
                                        $order = $rasxmedia->order($pembelian->user_id, $layanan->provider_id, $pembelian->quantity, $pembelian->custom_comments);
        
                                        if($order['result']){
                                            $order['status'] = true;
                                            $order['transactionId'] = $order['data']['trxid'];
                                        }else{
                                            $order['status'] = false;
                                        }
                                    }
                                

                                if($pembelian->tipe_transaksi !== 'joki'){
                                    
                                    if($order['status']){

                                        Pembelian::where('order_id', $data->order_id)->update([
                                            'provider_order_id' => $order['transactionId'] ? $order['transactionId'] : "",
                                            'status' => 'Pending',
                                            'log' => json_encode($order)
                                        ]);
                                        Log::info('Order ID : ' . $data->order_id . ' Sukses');
                                    }else{
                                        Pembelian::where('order_id', $data->order_id)->update([
                                            'log' => json_encode($order),
                                            'status' => 'Failed'
                                        ]);
                                    }
                                    
                                }
                            } catch (\Exception $e) {
                                // throw $e;
                                continue;
                            }

                        }

                    }              
                }else if($data->provider == "manual" && $data->metode_code == "OVO_MANUAL"){

                    $authToken = OvoModel::select('AuthToken')->latest()->first();
                    
                    if(!$authToken) continue;
                    
                    $init = new Ovo($authToken->AuthToken);
                    
                    foreach ($init->transactionHistory() as $data_trans) {
                        try {
                            foreach ($data_trans->orders[0]->complete as $transaction => $key) {
                                $dataArray = [];
                                $data_gojek = json_decode(json_encode($key), true);
                                $dataMasuk = $data_gojek['transaction_amount'];
                                $incomingTransfer = $data_gojek['transaction_type'];

                                if ($incomingTransfer == "INCOMING TRANSFER" && $dataMasuk == $pembayaran->harga) { //cek apakah ada status incoming transfer jika ada push ke array
                                    try {
                                        $requestPesan = $wa->send($api->nomor_admin,$pesanAdmin);
                                        $pesanMember = $wa->send($data->no_pembeli, $pesan);

                                        if($layanan->provider == "partaisocmed"){
                                            $order = $partaisocmed->order($pembelian->user_id, $layanan->provider_id, $pembelian->quantity, $pembelian->keywords, $pembelian->custom_comments, $pembelian->usernames, $pembelian->hashtag, $pembelian->media, $pembelian->answer_number,
                                    $pembelian->minimal, $pembelian->maximal, $pembelian->post, $pembelian->old_post, $pembelian->delay, $pembelian->expiry);
                                                
                                            if($order['result']){
                                                $order['status'] = true;
                                                $order['transactionId'] = $order['data']['trxid'];
                                            }else{
                                                $order['status'] = false;
                                            }
                                        }else if($layanan->provider == "irvankedesmm"){
                                            $order = $irvankedesmm->order($pembelian->user_id, $layanan->provider_id, $pembelian->quantity, $pembelian->custom_comments, $pembelian->usernames, $pembelian->hashtag, $pembelian->media, $pembelian->answer_number);
                                                
                                            if($order['result']){
                                                $order['status'] = true;
                                                $order['transactionId'] = $order['data']['trxid'];
                                            }else{
                                                $order['status'] = false;
                                            }
                                        }else if($layanan->provider == "vipmember"){
                                            $order = $vipmember->order($pembelian->user_id, $layanan->provider_id, $pembelian->quantity, $pembelian->usernames, $pembelian->answer_number, $pembelian->custom_comments);
            
                                            if($order['result']){
                                                $order['status'] = true;
                                                $order['transactionId'] = $order['data']['trxid'];
                                            }else{
                                                $order['status'] = false;
                                            }
                                        }else if($layanan->provider == "istanamarket"){
                                            $order = $istanamarket->order($pembelian->user_id, $layanan->provider_id, $pembelian->quantity, $pembelian->custom_comments);
            
                                            if($order['result']){
                                                $order['status'] = true;
                                                $order['transactionId'] = $order['data']['trxid'];
                                            }else{
                                                $order['status'] = false;
                                            }
                                        }else if($layanan->provider == "fanstore"){
                                            $order = $fanstore->order($pembelian->user_id, $layanan->provider_id, $pembelian->quantity);
            
                                            if($order['result']){
                                                $order['status'] = true;
                                                $order['transactionId'] = $order['data']['trxid'];
                                            }else{
                                                $order['status'] = false;
                                            }
                                        }else if($layanan->provider == "rasxmedia"){
                                            $order = $rasxmedia->order($pembelian->user_id, $layanan->provider_id, $pembelian->quantity, $pembelian->custom_comments);
            
                                            if($order['result']){
                                                $order['status'] = true;
                                                $order['transactionId'] = $order['data']['trxid'];
                                            }else{
                                                $order['status'] = false;
                                            }
                                        }
                                        
                                        Pembayaran::where('order_id', $data->order_id)->update(['status' => 'Lunas']);

                                        if ($order['status']) {

                                            $updatePembelian = Pembelian::where('order_id', $data->order_id)->update([
                                                'provider_order_id' => $order['transactionId'] ? $order['transactionId'] : "",
                                                'status' => 'Pending',
                                                'log' => json_encode($order)
                                            ]);
                                        } else {
                                            Pembelian::where('order_id', $data->order_id)->update([
                                                'log' => json_encode($order),
                                                'status' => 'Failed'
                                            ]);
                                        }
                                    } catch (\Exception $e) {
                                        // throw $e;
                                        continue;
                                    }                                    
                                }
                            }
                        } catch (\Exception $e) {
                            continue;
                        }
                    }                                        
                
                }else if($data->provider == "manual" && $data->metode_code == "BCA_MANUAL"){
                    $cekmutasi = new CekMutasiController();
                    $mutasi = $cekmutasi->check($pembayaran->harga);
                    
                    if($mutasi['success']){
                        foreach($mutasi['response'] as $transaksi){
                            if($transaksi['type'] == "credit" && $transaksi['amount'] == $pembayaran->harga){
                                try {
                                    $requestPesan = $wa->send($api->nomor_admin,$pesanAdmin);
                                    $pesanMember = $wa->send($data->no_pembeli, $pesan);

                                        if($layanan->provider == "partaisocmed"){
                                            $order = $partaisocmed->order($pembelian->user_id, $layanan->provider_id, $pembelian->quantity, $pembelian->keywords, $pembelian->custom_comments, $pembelian->usernames, $pembelian->hashtag, $pembelian->media, $pembelian->answer_number,
                                    $pembelian->minimal, $pembelian->maximal, $pembelian->post, $pembelian->old_post, $pembelian->delay, $pembelian->expiry);
                                                
                                            if($order['result']){
                                                $order['status'] = true;
                                                $order['transactionId'] = $order['data']['trxid'];
                                            }else{
                                                $order['status'] = false;
                                            }
                                        }else if($layanan->provider == "irvankedesmm"){
                                            $order = $irvankedesmm->order($pembelian->user_id, $layanan->provider_id, $pembelian->quantity, $pembelian->custom_comments, $pembelian->usernames, $pembelian->hashtag, $pembelian->media, $pembelian->answer_number);
                                                
                                            if($order['result']){
                                                $order['status'] = true;
                                                $order['transactionId'] = $order['data']['trxid'];
                                            }else{
                                                $order['status'] = false;
                                            }
                                        }else if($layanan->provider == "vipmember"){
                                            $order = $vipmember->order($pembelian->user_id, $layanan->provider_id, $pembelian->quantity, $pembelian->usernames, $pembelian->answer_number, $pembelian->custom_comments);
            
                                            if($order['result']){
                                                $order['status'] = true;
                                                $order['transactionId'] = $order['data']['trxid'];
                                            }else{
                                                $order['status'] = false;
                                            }
                                        }else if($layanan->provider == "istanamarket"){
                                            $order = $istanamarket->order($pembelian->user_id, $layanan->provider_id, $pembelian->quantity, $pembelian->custom_comments);
            
                                            if($order['result']){
                                                $order['status'] = true;
                                                $order['transactionId'] = $order['data']['trxid'];
                                            }else{
                                                $order['status'] = false;
                                            }
                                        }else if($layanan->provider == "fanstore"){
                                            $order = $fanstore->order($pembelian->user_id, $layanan->provider_id, $pembelian->quantity);
            
                                            if($order['result']){
                                                $order['status'] = true;
                                                $order['transactionId'] = $order['data']['trxid'];
                                            }else{
                                                $order['status'] = false;
                                            }
                                        }else if($layanan->provider == "rasxmedia"){
                                            $order = $rasxmedia->order($pembelian->user_id, $layanan->provider_id, $pembelian->quantity, $pembelian->custom_comments);
            
                                            if($order['result']){
                                                $order['status'] = true;
                                                $order['transactionId'] = $order['data']['trxid'];
                                            }else{
                                                $order['status'] = false;
                                            }
                                        }                            
                                    
                                    Pembayaran::where('order_id', $data->order_id)->update(['status' => 'Lunas']);

                                    if ($order['status']) {

                                        Pembelian::where('order_id', $data->order_id)->update([
                                            'provider_order_id' => $order['transactionId'] ? $order['transactionId'] : "",
                                            'status' => 'Pending',
                                            'log' => json_encode($order)
                                        ]);
                                    } else {
                                        Pembelian::where('order_id', $data->order_id)->update([
                                            'log' => json_encode($order),
                                            'status' => 'Failed'
                                        ]);
                                    }
                                } catch (\Exception $e) {
                                    // throw $e;
                                    continue;
                                }                                
                            }
                        }
                    }
                }
            }
        }
        echo 'Sukses';
    }
     
}
