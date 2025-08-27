<?php
namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Berita;
use App\Models\Pembelian;
use App\Models\Seting;
use App\Models\Method;
use App\Models\User;
use App\Models\LogUser;
use App\Models\Method_Fee;
use App\Http\Controllers\PaymentGateway\duitKuController;
use App\Http\Controllers\PaymentGateway\TriPayController;
use App\Http\Controllers\PaymentGateway\iPaymuController;
use App\Http\Controllers\PaymentGateway\TokoPayController;
use App\Http\Controllers\PaymentGateway\LinkQuController;
use App\Http\Controllers\PaymentGateway\XenditController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Helpers\Formater;
use App\Http\Controllers\WhatsappController;

class DepositController extends Controller
{
    public function create()
    {
        return view('components.user.topupuser', [
        'data' => Pembayaran::where('username', Auth::user()->username)->where('type', 'deposit')->orderBy('created_at', 'desc')->get(),
        'pay_method' => \App\Models\Method::all()
        ]);
    }
    
    public function price(Request $request)
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $dataMethod = \App\Models\Method::get();
        foreach ($dataMethod as $dataM) {
            if($dataM->status == 'ON') {
                if($dataM->percent == '%') {
                    $fee = $request->quantity * ($dataM->biaya_admin / 100);
                } else if($dataM->percent == "+") {
                    $fee = $dataM->biaya_admin;
                }
            } else {
                $fee = 0;
            }
            if(in_array($dataM->tipe, ['virtual-account','convenience-store','transfer-bank'])) {
                $status = $request->quantity >= 10000 ? 'Av' : 'Dis';
            } else if(in_array($dataM->tipe,['e-walet','qris'])) { 
                $status = 'Av';
            } else if($dataM->tipe == 'pulsa') { 
                $status = $request->quantity >= 1000 ? 'Av' : 'Dis';
            }
            $out[] = [
                'id' => $dataM->id,
                'status' => $status,
                'price' => "Rp. ".number_format($request->quantity + $fee)
            ];
        }
        return response()->json($out);
    }

    public function store(Request $request) {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $tripay = new TriPayController();  
        $ipaymu = new iPaymuController(); 
        $duitku = new duitKuController();
        $tokopay = new TokoPayController();
        $linkqu = new LinkQuController();
        $xendit = new XenditController();
        
        $request->validate([
            'quantity' => 'required|numeric',
            'method' => 'required'
        ]);
        $rand = rand(1,1000);
        $unik = date('Hs');
        $kode_unik = substr(str_shuffle(1234567890),0,3);
        $order_id = 'DP'.$unik.$kode_unik;
        $dataMethod = Method::where('id', $request->method)->select('name','provider','tipe','code','keterangan')->first();
        $user = User::where('username', Auth::user()->username)->orderBy('created_at', 'desc')->first();
        if(count(Pembayaran::where('username', Auth::user()->username)->where('type', 'deposit')->where('status', 'Menunggu Pembayaran')->orderBy('created_at', 'desc')->get()) != 0) return back()->with('error', "Masih ada invoice deposit sebelumnya mohon untuk menunggu 1x24jam agar invoice gagal.");
        $no_pembayaran = '';
        $amount = '';
        $reference = '';
        $phone = $formatter->filter_phone('62',$user->no_wa);
        if($dataMethod->provider == "duitku") {
            $listchannel = [];
            foreach($duitku->channel($request->quantity)->paymentFee as $channel){
                array_push($listchannel,$channel->paymentMethod);
            }
            unset($listchannel['OV']);
            if(!in_array($dataMethod->code,$listchannel)){
                return back()->with('error', "Tipe pembayaran tidak sah, silahkan hubungi admin! (1)");
            }
            $duitkuress = $duitku->requestPayment($order_id, $request->quantity, $dataMethod->code, $order_id.'@email.com', $phone, env("APP_URL").'/user/invoice/'.$order_id);
            if($duitkuress['success'] != true) return back()->with('error', $duitkuress['msg'].' (1)');
            
            $no_pembayaran = $duitkuress['no_pembayaran'] == null ? null : $duitkuress['no_pembayaran'];
            $reference = $duitkuress['reference'] == null ? "" : $duitkuress['reference'];
            $amount = $duitkuress['amount'] == null ? $request->quantity + $fee : $duitkuress['amount'];
            $checkouturl = $duitkuress['checkout_url'] == null ? "" : $duitkuress['checkout_url'];
            
        } else if($dataMethod->provider == "ipaymu") {
            $ipayres = $ipaymu->requestPayment($request->quantity, $order_id, $phone, $order_id.'@email.com',$dataMethod->code);
            if($ipayres['Status'] != 200) return back()->with('error', 'Metode pembayaran ini sedang tidak dapat digunakan (2)');
            
            $no_pembayaran = $ipayres['Data']['QrString'] == null ? $ipayres['Data']['PaymentNo'] : $ipayres['Data']['QrString'];
            $reference = $ipayres['Data']['TransactionId'];
            $amount = $ipayres['Data']['Total'];
            $checkouturl = null;
        } else if($dataMethod->provider == "tokopay") {
            $tokopayres = $tokopay->requestPay($request->quantity, $order_id, $phone, $dataMethod->code, $order_id.'@email.com', env("APP_URL").'/topup/invoice/'.$order_id, $kode_unik);
            if($tokopayres['success'] != true) return response()->json(['status' => false, 'data' => $tokopayres['msg']]);

            $no_pembayaran = $tokopayres['no_pembayaran'];
            $reference = $tokopayres['reference'];
            $amount = $tokopayres['amount'];
            $checkouturl = $tokopayres['checkout_url'];
            
        } else if($dataMethod->provider == "tripay") {
            $listchannel = [];
            foreach($tripay->channel()->data as $channel){
                array_push($listchannel,$channel->code);
            }
            unset($listchannel['OVO']);
            if(!in_array($dataMethod['code'],$listchannel)){
                return back()->with('error', "Tipe pembayaran tidak sah, silahkan hubungi admin! (4)");
            }
            $tripayres = $tripay->request($order_id, $request->quantity, $dataMethod->code, $order_id.'@email.com', $phone);
            if($tripayres['success'] != true) return back()->with('error', $tripayres['msg'].' (4)');
            
            $no_pembayaran = $tripayres['no_pembayaran'];
            $reference = $tripayres['reference'];
            $amount = $tripayres['amount'];
            $checkouturl = $tripayres['checkout_url'];
            
        } else if($dataMethod->provider == "linkqu") {
            if($dataMethod->tipe == "e-walet") {
                $listchannel = [];
                foreach($linkqu->channel_ewallet()->data->dataproduk as $channel){
                    array_push($listchannel,$channel->kodebank);
                }
                unset($listchannel['OVO_MANUAL']);
                if(!in_array($dataMethod['code'],$listchannel)){
                    return back()->with('error','Tipe pembayaran tidak sah, silahkan hubungi admin! (5)');
                }
            } else if($dataMethod->tipe == "virtual-account") {
                $listchannel = [];
                foreach($linkqu->channel_va()->data as $channel){
                    array_push($listchannel,$channel->kodeBank);
                }
                unset($listchannel['OVO_MANUAL']);
                if(!in_array($dataMethod['code'],$listchannel)){
                    return back()->with('error','Tipe pembayaran tidak sah, silahkan hubungi admin! (5)');
                }
            }
            $linkqures = $linkqu->requestPayment($dataMethod->tipe, $request->quantity, $order_id, $kode_unik, $phone, $order_id.'@email.com', $dataMethod['code']);
            if($linkqures['success'] != true) return back()->with('error', $linkqures['msg']);

            $no_pembayaran = $linkqures['no_pembayaran'];
            $reference = $linkqures['reference'];
            $amount = $linkqures['amount'];
            $checkouturl = $linkqures['checkout_url'];
            
        } else if($dataMethod->provider == "xendit") {
            $xenditres = $xendit->createTransaction($request->quantity, $order_id, $dataMethod['code']);
            if($xenditres['status'] != "PENDING") return back()->with('error', 'Pembayaran Error, Silahkan hubungi admin!');

            if($dataMethod->tipe == 'virtual-account') {
                $no_pembayaran = $xendit->createVA($order_id,$dataMethod['code'])['account_number'];
                $checkouturl = null;
            } else if($dataMethod->tipe == 'convenience-store') {
                $no_pembayaran = $xendit->createRetail($request->quantity,$order_id,$dataMethod['code'])['payment_code'];
                $checkouturl = null;
            } else if($dataMethod->tipe == 'qris') {
                $no_pembayaran = $xendit->createQR($request->quantity,$order_id,$dataMethod['code'])['qr_string'];
                $checkouturl = null;
            } else {
                $no_pembayaran = null;
                $checkouturl = $xenditres['invoice_url'];
            }
            $reference = $xenditres['id'];
            $amount = $xenditres['amount'];
            
        } else if($dataMethod->provider == "manual") {
            $amount = $request->quantity + $rand;
            $reference = null;   
            $checkouturl = null;   
            if($dataMethod->code == "OVO_MANUAL" || $dataMethod->provider == "manual"){
                $no_pembayaran = $dataMethod->keterangan;
                if($amount < 10000){
                    return response()->json(['status' => false, 'data' => 'Minimum jumlah pembayaran untuk metode pembayaran ini adalah Rp 10.000']);
                }
            } else if($dataMethod->code == "GOPAY_MANUAL" || $dataMethod->provider == "manual"){
                $no_pembayaran = $dataMethod->keterangan;
                if($amount < 10000){
                    return response()->json(['status' => false, 'data' => 'Minimum jumlah pembayaran untuk metode pembayaran ini adalah Rp 10.000']);
                }
            }else{
                $no_pembayaran = $dataMethod->keterangan;
                if($amount < 10000){
                    return response()->json(['status' => false, 'data' => 'Minimum jumlah pembayaran untuk metode pembayaran ini adalah Rp 10.000']);
                }
            }

        } else {
            return back()->with('error', 'Metode tidak valid');
        }
        
        $pembayaran = new Pembayaran();
        $pembayaran->order_id = $order_id;
        $pembayaran->username = Auth::user()->username;
        $pembayaran->harga = $request->quantity;
        $pembayaran->total_harga = $amount;
        $pembayaran->no_pembayaran = $no_pembayaran;
        $pembayaran->no_pembeli = $phone;
        $pembayaran->checkout_url = $checkouturl;
        $pembayaran->provider = $dataMethod->provider;
        $pembayaran->status = 'Menunggu Pembayaran';
        $pembayaran->metode = $dataMethod->name;
        $pembayaran->metode_code = $dataMethod->code;
        $pembayaran->metode_tipe = $dataMethod->tipe;
        $pembayaran->reference = $reference;
        $pembayaran->type = 'deposit';
        $pembayaran->save();
        
        $log = new LogUser();
        $log->order_id = $order_id;
        $log->user = Auth::user()->username;
        $log->type = 'topup';
        $log->text = 'IP : '.$client_ip.' Melakukan Top Up Sebesar Rp.'.number_format($request->quantity).' ';
        $log->ip = $client_ip;
        $log->loc = $client_iploc;
        $log->ua = $browser;
        $log->save();
        
        return redirect()->to(env("APP_URL").'/topup/invoice/'.$order_id);
    }
    public function invoice_topup($order)
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $data = Pembayaran::where('username', Auth::user()->username)->where('order_id', $order)->where('type', 'deposit')->orderBy('created_at', 'desc')->first();
        $expired = date('Y-m-d H:i:s', strtotime('+'.$api->expired_invoice_hours.' Hours +'.$api->expired_invoice_minutes.' minutes', strtotime($data->created_at)));
        return view('components.user.invoicetopup',[
            'data' => $data,
            'expired' => $expired,
            'pay_method' => \App\Models\Method::all()
        ]);
    }
}
