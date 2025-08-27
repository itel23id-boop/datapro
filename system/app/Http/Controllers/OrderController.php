<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Layanan;
use App\Models\Pembayaran;
use App\Models\Voucher;
use App\Models\VoucherList;
use App\Models\Pembelian;
use App\Models\User;
use App\Models\LogUser;
use App\Models\Berita;
use App\Models\Method;
use App\Models\Rating;
use App\Models\Profit;
use App\Models\Paket;
use App\Helpers\Formater;
use App\Models\PaketLayanan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Controllers\MethodController;
use App\Http\Controllers\WhatsappController;
use App\Http\Controllers\ProviderApi\PartaisocmedController;
use App\Http\Controllers\ProviderApi\IrvankedesmmController;
use App\Http\Controllers\ProviderApi\VipMemberController;
use App\Http\Controllers\ProviderApi\IstanaMarketController;
use App\Http\Controllers\ProviderApi\FanstoreController;
use App\Http\Controllers\ProviderApi\RasxmediaController;
use App\Http\Controllers\ProviderApi\ApiCheckController;
use App\Http\Controllers\PaymentGateway\TriPayController;
use App\Http\Controllers\PaymentGateway\iPaymuController;
use App\Http\Controllers\PaymentGateway\duitKuController;
use App\Http\Controllers\PaymentGateway\TokoPayController;
use App\Http\Controllers\PaymentGateway\LinkQuController;
use App\Http\Controllers\PaymentGateway\XenditController;
use App\Http\Controllers\PaymentGateway\PaydisiniController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmailbySaldo;

class OrderController extends Controller
{
    public function create(Kategori $kategori)
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $data = Kategori::where('kode', $kategori->kode)->select('nama', 'sub_nama', 'text_1', 'text_2', 'text_3', 'text_4', 'text_5', 'server_id', 'thumbnail', 'id', 'kode', 'petunjuk','deskripsi_game','deskripsi_field','banner','tipe','deskripsi_popup', 'status_validasi')->first();
        if($data == null) return back();
        
        if(Auth::check()){
            if(Auth::user()->role == "Member"){
                $layanan = Layanan::where('kategori_id', $data->id)->where('status', 'available')->select('id', 'layanan', 'product_logo', 'harga_member AS harga', 'is_flash_sale', 'expired_flash_sale', 'harga_flash_sale')->orderBy('tipe', 'asc')->orderBy('harga', 'asc')->get();
            }else if(Auth::user()->role == "Reseller"){
                $layanan = Layanan::where('kategori_id', $data->id)->where('status', 'available')->select('id', 'layanan', 'product_logo', 'harga_reseller AS harga', 'is_flash_sale', 'expired_flash_sale', 'harga_flash_sale')->orderBy('tipe', 'asc')->orderBy('harga', 'asc')->get();
            }else if(Auth::user()->role == "VIP" || Auth::user()->role == "Admin"){
                $layanan = Layanan::where('kategori_id', $data->id)->where('status', 'available')->select('id', 'layanan', 'product_logo', 'harga_vip AS harga', 'is_flash_sale', 'expired_flash_sale', 'harga_flash_sale')->orderBy('tipe', 'asc')->orderBy('harga', 'asc')->get();
            }
        }else{
            $layanan = Layanan::where('kategori_id', $data->id)->where('status', 'available')->select('id', 'layanan','product_logo', 'harga', 'is_flash_sale', 'expired_flash_sale', 'harga_flash_sale')->orderBy('tipe', 'asc')->orderBy('harga', 'asc')->get();
        }
        
        $formatter = new Formater;
        $pakets = [];
        $userRole = Auth::check() ? Auth::user()->role : null;
        
        foreach (Paket::all() as $paket) {
                $lyns = $paket->layanan;
                $l = [];
            
                foreach ($lyns as $lyn) {
                if ($lyn->kategori_id == $data->id && (!$lyn->status || $lyn->status == 'available')) {
                    if ($userRole == 'Member') {
                        $harga = $lyn->harga_member;
                    } elseif ($userRole == 'Reseller') {
                        $harga = $lyn->harga_reseller;
                    } elseif ($userRole == 'VIP' || $userRole == 'Admin') {
                        $harga = $lyn->harga_vip;
                    } else {
                        $harga = $lyn->harga;
                    }
            
                    if (is_object($lyn)) {
                        $lynData = [
                            'id' => $lyn->id,
                            'layanan' => $lyn->layanan,
                            'product_logo' => $lyn->product_logo,
                            'harga' => $harga,
                            'is_flash_sale' => $lyn->is_flash_sale,
                            'expired_flash_sale' => $lyn->expired_flash_sale,
                            'harga_flash_sale' => $lyn->harga_flash_sale,
                            'updated_at' => $lyn->updated_at,
                        ];
            
                        array_push($l, $lynData);
                    }
                }
            }
        
            if (!empty($l)) {
                array_push($pakets, [
                    'nama' => $paket->nama,
                    'layanan' => $l,
                    'image'=>$paket->image,
                    'id'=>$paket->id,
                ]);
            }
        }
        $logs = LogUser::where('ip', $formatter->client_ip())->where('id_kategori', $data->id)->first();
        return view('layout.order.'.$api->change_theme, [
            'title' => $data->nama,
            'kategori' => $data,
            'pakets' => $pakets,
            'nominal' => $layanan, 
            'paket_layanans' => PaketLayanan::where('kategori_id', $data->id)->get(),
            'formatter' => $formatter,
            'logs' => $logs,
            'phone_country' => \DB::table('phone_countrys')->get(),
            'voucher' => \App\Models\Voucher::all(),
            'rating' => Rating::where('kategori_id', $data->id)->select('kategori_id', 'layanan', 'bintang','comment','no_pembeli','id','created_at')->limit('5')->orderBy('id', 'desc')->get(),
            'flashsale' => \App\Models\Layanan::join('kategoris', 'kategoris.id','layanans.kategori_id')->select('kategoris.thumbnail AS gmr_thumb','kategoris.kode AS kode_game','layanans.*')->where('layanans.is_flash_sale', 'Yes')->where('layanans.expired_flash_sale', '>=', date('Y-m-d H:i:s'))->get(),
            'pay_method' => \App\Models\Method::all()
        ]);
    }
    
    public function description($id)
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $data = Layanan::where('id', $id)->first();
        if($data->product_logo == null || $data->product_logo == 0) {
            $product_logo = $api->logo_favicon;
        } else {
            $product_logo = $data->product_logo;
        }
        $send = "
                <div class='relative w-full transform overflow-hidden rounded-lg bg-murky-900 text-left shadow-xl transition-all sm:my-8 sm:max-w-2xl !rounded-2xl opacity-100 translate-y-0 sm:scale-100'>
                    <h2 class='text-center text-sm font-semibold leading-6 text-xl mt-1'>".$data->layanan."</h2>
                    <div class='flex flex-col gap-4 p-4'>
                        <div class='flex justify-center'>
                            <img alt='".$data->layanan."' loading='lazy' width='100' height='100' decoding='async' data-nimg='1' src='".$product_logo."' style='color: transparent;'>
                        </div>
                        <div class='prose prose-sm flex w-full flex-col rounded-xl border border-dashed border-murky-600 px-4 py-3 text-xs text-white prose-p:my-0'>
                            <div>
                                Minimal Order: ".$data->min."<br>
                                Maximal Order: ".$data->max."<br>
                            </div>
                        </div>
                        <div class='prose prose-sm flex w-full flex-col rounded-xl border border-dashed border-murky-600 px-4 py-3 text-xs text-white prose-p:my-0'>
                            <div>
                                ".$data->catatan."
                            </div>
                        </div>
                    </div>
                </div>
                <div class='mt-3 text-end'>
                    <button type='button' class='btn btn-primary w-100' data-bs-dismiss='modal'>OK, Saya Mengerti</button>
                </div>
        ";

        return $send;
    }
    public function price($id,$qty)
    {
        if(Auth::check()){
            if(Auth::user()->role == "Member"){
                $data = Layanan::where('id', $id)->select('harga_member AS harga', 'is_flash_sale','harga_flash_sale','expired_flash_sale','kategori_id','min','max')->first();    
            }else if(Auth::user()->role == "Reseller"){
                $data = Layanan::where('id', $id)->select('harga_reseller AS harga', 'is_flash_sale','harga_flash_sale','expired_flash_sale','kategori_id','min','max')->first();
            }else if(Auth::user()->role == "VIP" || Auth::user()->role == "Admin"){
                $data = Layanan::where('id', $id)->select('harga_vip AS harga', 'is_flash_sale','harga_flash_sale','expired_flash_sale','kategori_id','min','max')->first();
            }
        }else{
            $data = Layanan::where('id', $id)->select('harga AS harga', 'is_flash_sale','harga_flash_sale','expired_flash_sale','kategori_id','min','max')->first();
        }  
        $data->harga = ($qty*$data->harga) / 1000;
        $dataKategori = Kategori::where('id', $data->kategori_id)->first();

        if($data->is_flash_sale == 'Yes' && $data->expired_flash_sale >= date('Y-m-d')) {
            $data->harga = $data->harga_flash_sale;
        }
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $dataMethod = \App\Models\Method::get();
        $biaya_admin = 0;
        foreach ($dataMethod as $dataM) {
            if($dataM->status == 'ON') {
                if($dataM->percent == '%') {
                    $biaya_admin = $data->harga * ($dataM->biaya_admin / 100);
                } else if($dataM->percent == "+") {
                    $biaya_admin = $dataM->biaya_admin;
                }
            } else {
                $biaya_admin = 0;
            }
            if(in_array($dataM->tipe, ['virtual-account','convenience-store','transfer-bank'])) {
                $status = $data->harga >= 10000 ? 'Av' : 'Dis';
            } else if(in_array($dataM->tipe,['e-walet','qris'])) { 
                $status = 'Av';
            } else if($dataM->tipe == 'pulsa') { 
                $status = $data->harga >= 1000 ? 'Av' : 'Dis';
            }
            if($data->min == null && $data->max == null) {
                $min = 'Min: 0';
                $max = 'Max: 0';
            } else {
                $min = 'Min: '.$data->min;
                $max = 'Max: '.$data->max;
            }
            $out[] = [
                'id' => $dataM->id,
                'status' => $status,
                'price' => "Rp. ".number_format($data->harga + $biaya_admin, 0, '.', ','),
                'min' => $min,
                'max' => $max
            ];
        }
        $out[] = [
            'id' => 1,
            'status' => 'Av',
            'price' => "Rp. ".number_format($data->harga , 0, '.', ','),
            'min' => $min,
            'max' => $max
        ];
        return response()->json($out);
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'uid' => 'required',
            'service' => 'required|numeric',
            'payment_method' => 'required',
            'qty' => 'required|numeric',
        ]);
        
            /*$creds = array(
                'secret' => env('CAPTCHA_SECRET'),
                'response' => $request->grecaptcha
            );
       
            $verify = curl_init();
            curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
            curl_setopt($verify, CURLOPT_POST, true);
            curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($creds));
            curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($verify);
         
            $status = json_decode($response, true);
            
            if(!$status['success']){ 
                
                return response()->json([
                    'status' => false,
                ],422);
            
            }*/
            
            if(Auth::check()){
                if(Auth::user()->role == "Member"){
                    $dataLayanan = Layanan::where('id', $request->service)->select('harga_member AS harga', 'layanan', 'kategori_id', 'is_flash_sale','harga_flash_sale','expired_flash_sale')->first();
                }else if(Auth::user()->role == "Reseller"){
                    $dataLayanan = Layanan::where('id', $request->service)->select('harga_reseller AS harga', 'layanan', 'kategori_id', 'is_flash_sale','harga_flash_sale','expired_flash_sale')->first();
                }else if(Auth::user()->role == "VIP" || Auth::user()->role == "Admin"){
                    $dataLayanan = Layanan::where('id', $request->service)->select('harga_vip AS harga', 'layanan', 'kategori_id', 'is_flash_sale','harga_flash_sale','expired_flash_sale')->first();
                }
            }else{
                $dataLayanan = Layanan::where('id', $request->service)->select('harga AS harga', 'layanan', 'kategori_id', 'is_flash_sale','harga_flash_sale','expired_flash_sale')->first();
            }
            
            if($dataLayanan->is_flash_sale == 'Yes' && $dataLayanan->expired_flash_sale >= date('Y-m-d')) {
                $dataLayanan->harga = $dataLayanan->harga_flash_sale;
            }

            /*$dataKategori = Kategori::where('id', $dataLayanan->kategori_id)->select('kode','status_validasi','kode_validasi','tipe')->first();
            
            if($dataKategori->status_validasi == 'Yes'){
                if ($request->zone != null AND $dataKategori->tipe == 'game') {
                    $data = $apicheck->check($request->uid, $request->zone, $dataKategori->kode_validasi, 'game');
                } else if ($request->zone == null AND $dataKategori->tipe == 'game') {
                    $data = $apicheck->check($request->uid, null, $dataKategori->kode_validasi, 'game');
                } else if ($dataKategori->tipe == 'e-money') {
                    $data = $apicheck->check_bank($request->uid, $dataKategori->kode_validasi);
                } else if ($dataKategori->tipe == 'pln') {
                    $data = $apipln->check($request->uid);
                }
                return response()->json([
                    'ok' => $data['status'] == true ? true : false,
                    'msg' => $data['status'] == true ? $data['data']['message'] : $data['message'],
                    'name' => $data['status'] == true ? urldecode($data['data']['userNameGame']) : $request->uid.$request->zone
                ]);
            }else{
                if($request->ktg_tipe !== 'joki'){
                    return response()->json([
                        'ok' => true,
                        'name' => $request->uid.$request->zone
                    ]);
                } else {
                    return response()->json([
                        'ok' => true,
                        'name' => $request->nickname_joki,
                        'requestjoki' => $request->request_joki,
                        'catatanjoki' => $request->catatan_joki,
                        'jumlahjoki' => $request->jumlah_joki,
                    ]);
                }
            }*/
            return response()->json(['ok' => true,'name' => $request->uid.$request->zone]);
    }

    public function store(Request $request)
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();

        $request->validate([
            'uid' => 'required',
            'service' => 'required|numeric',
            'payment_method' => 'required',
            'email' => 'required',
            'qty' => 'required|numeric',
        ]);
        
        // Extracting input data
        if (RateLimiter::tooManyAttempts($this->orderThrottleKey($request), $api->limit_order)) { 
            return response()->json(['status' => false, 'data' => $api->text_limit_order]);
        }
        if (empty($request->service) || empty($request->payment_method)) {
            return response()->json(['status' => false, 'data' => 'Silahkan isi kolom Pembayaran dan Denominasi Produk.']);
        }
        
        if(isset($request->voucher)){
            
            $voucher = Voucher::where('kode', $request->voucher)->first();
            
            if(date('Y-m-d H:i:s') > $voucher->expired) return response()->json(['status' => false, 'data' => 'Voucher telah kadaluarsa.']);
            
            if($voucher->globals == 0) {
                if(Auth::check()){
                    if(Auth::user()->role == "Member"){
                        $dataLayanan = Layanan::where('id', $request->service)->where('kategori_id', $voucher->kategori_id)->select('layanan','harga_member AS harga','kategori_id', 'provider_id', 'provider', 'id', 'is_flash_sale','harga_flash_sale','expired_flash_sale')->first();
                    }else if(Auth::user()->role == "Reseller"){
                        $dataLayanan = Layanan::where('id', $request->service)->where('kategori_id', $voucher->kategori_id)->select('layanan','harga_reseller AS harga','kategori_id', 'provider_id', 'provider', 'id', 'is_flash_sale','harga_flash_sale','expired_flash_sale')->first();
                    }else if(Auth::user()->role == "VIP" || Auth::user()->role == "Admin"){
                        $dataLayanan = Layanan::where('id', $request->service)->where('kategori_id', $voucher->kategori_id)->select('layanan','harga_vip AS harga','kategori_id', 'provider_id', 'provider', 'id', 'is_flash_sale','harga_flash_sale','expired_flash_sale')->first();
                    }            
                }else{
                    $dataLayanan = Layanan::where('id', $request->service)->where('kategori_id', $voucher->kategori_id)->select('layanan', 'harga AS harga', 'kategori_id', 'provider_id', 'provider', 'id', 'is_flash_sale','harga_flash_sale','expired_flash_sale')->first();
                }
            } else {
                if(Auth::check()){
                    if(Auth::user()->role == "Member"){
                        $dataLayanan = Layanan::where('id', $request->service)->select('layanan','harga_member AS harga','kategori_id', 'provider_id', 'provider', 'id', 'is_flash_sale','harga_flash_sale','expired_flash_sale')->first();
                    }else if(Auth::user()->role == "Reseller"){
                        $dataLayanan = Layanan::where('id', $request->service)->select('layanan','harga_reseller AS harga','kategori_id', 'provider_id', 'provider', 'id', 'is_flash_sale','harga_flash_sale','expired_flash_sale')->first();
                    }else if(Auth::user()->role == "VIP" || Auth::user()->role == "Admin"){
                        $dataLayanan = Layanan::where('id', $request->service)->select('layanan','harga_vip AS harga','kategori_id', 'provider_id', 'provider', 'id', 'is_flash_sale','harga_flash_sale','expired_flash_sale')->first();
                    }            
                }else{
                    $dataLayanan = Layanan::where('id', $request->service)->select('layanan', 'harga AS harga', 'kategori_id', 'provider_id', 'provider', 'id', 'is_flash_sale','harga_flash_sale','expired_flash_sale')->first();
                }
            }
            
            if($dataLayanan->is_flash_sale == 'Yes') return response()->json(['status' => false, 'message' => 'Promo tidak dapat digunakan pada produk FLASHSALE'], 404);
            if($voucher->min_transaksi >= $dataLayanan->harga) return response()->json(['status' => false, 'data' => 'Minimal transaksi Rp. '.number_format($voucher->min_transaksi).'']);
            
            if(!$voucher){
                $dataLayanan->harga = $dataLayanan->harga;
            }else{
                if($voucher->stock == 0){
                    $dataLayanan->harga = $dataLayanan->harga;
                }else{
                    if(Auth::check()){
                        $voucherlist = VoucherList::where('user_id', Auth::user()->id)->where('kode', $request->voucher)->first();
                        if(!$voucherlist) {
                            $vl = new VoucherList();
                            $vl->user_id = Auth::user()->id;
                            $vl->kode = $request->voucher;
                            $vl->limit = 1;
                            $vl->save();
                        } else {
                            if($voucher->versi != "public") {
                                if($voucherlist->limit > $voucher->limit_voucher_login) return response()->json(['status' => false, 'data' => 'Voucher telah mencapai limit maximum '.$voucher->limit_voucher_login.'']);
                            }
                            $voucherlist->update([
                                'limit' => $voucherlist->limit + 1
                            ]);
                        }
                    }
                    $potongan = $dataLayanan->harga * ($voucher->promo / 100);
                    if($potongan < $voucher->max_potongan){
                        $potongan = $voucher->max_potongan;
                    } else if($potongan > $voucher->max_potongan){
                        $potongan = $voucher->max_potongan;
                    }
                    
                    $dataLayanan->harga = round($dataLayanan->harga - $potongan);
                    $voucher->decrement('stock');
                }
            }
        } else {
            if(Auth::check()){
                if(Auth::user()->role == "Member"){
                    $dataLayanan = Layanan::where('id', $request->service)->select('layanan','harga_member AS harga','kategori_id', 'provider_id', 'provider', 'id', 'is_flash_sale','harga_flash_sale','expired_flash_sale')->first();
                }else if(Auth::user()->role == "Reseller"){
                    $dataLayanan = Layanan::where('id', $request->service)->select('layanan','harga_reseller AS harga','kategori_id', 'provider_id', 'provider', 'id', 'is_flash_sale','harga_flash_sale','expired_flash_sale')->first();
                }else if(Auth::user()->role == "VIP" || Auth::user()->role == "Admin"){
                    $dataLayanan = Layanan::where('id', $request->service)->select('layanan','harga_vip AS harga','kategori_id', 'provider_id', 'provider', 'id', 'is_flash_sale','harga_flash_sale','expired_flash_sale')->first();
                }            
            }else{
                $dataLayanan = Layanan::where('id', $request->service)->select('layanan', 'harga AS harga', 'kategori_id', 'provider_id', 'provider', 'id', 'is_flash_sale','harga_flash_sale','expired_flash_sale')->first();
            }
        }
        
        if(Auth::check()){
            if(Auth::user()->role == "Member"){
                $cekprofit = Profit::where('name', $dataLayanan->provider)->select('profit_member AS profit','percent')->first();
            }else if(Auth::user()->role == "Reseller"){
                $cekprofit = Profit::where('name', $dataLayanan->provider)->select('profit_reseller AS profit','percent')->first();
            }else if(Auth::user()->role == "VIP" || Auth::user()->role == "Admin"){
                $cekprofit = Profit::where('name', $dataLayanan->provider)->select('profit_vip AS profit','percent')->first();
            }
        } else {
            $cekprofit = Profit::where('name', $dataLayanan->provider)->select('profit AS profit','percent')->first();
        }
        
        $kategori = Kategori::where('id', $dataLayanan->kategori_id)->first();
        
        $dataLayanan->harga = ($request->qty * $dataLayanan->harga) / 1000;
        
        if($dataLayanan->is_flash_sale == 'Yes' && $dataLayanan->expired_flash_sale >= date('Y-m-d')) {
            $dataLayanan->harga = $dataLayanan->harga_flash_sale;
        }
        
        $unik = date('Hs');
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'; // Tambahan huruf kapital A-Z
        $code = '';
        
        for ($i = 0; $i < 20; $i++) { // Panjang kode 12 karakter
            $randomIndex = rand(0, strlen($characters) - 1);
            $code .= $characters[$randomIndex];
        }
        
        $kode_unik = $code;
        $order_id = 'FT'.$unik.$kode_unik;
        
        $tripay = new TriPayController();  
        $ipaymu = new iPaymuController(); 
        $duitku = new duitKuController();
        $tokopay = new TokoPayController();
        $linkqu = new LinkQuController();
        $xendit = new XenditController();
        $paydisini = new PaydisiniController();
        $wa = new WhatsappController;
        $formatter = new Formater;
        
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $rand = rand(1,1000);
        $no_pembayaran = '';
        $amount = '';
        $reference = '';
        
        $phone = preg_replace("/[^0-9]/", '', $request->nomor);
        $req_email = $request->email == null ? $order_id.'@email.com' : $request->email;
        
        if($cekprofit->percent == "%") {
            $profit = $dataLayanan->harga * $cekprofit->profit / 100;
        } else if($cekprofit->percent == "+") {
            $profit = $cekprofit->profit;
        }
        
        if($request->payment_method == "1"){
            $provider_payment = "Saldo";
            $payment_name = "Saldo";
            $payment_code = "Saldo";
            $payment_tipe = "Saldo";
            $biaya_admin = 0;
        } else {
            $dataMethod = Method::where('id', $request->payment_method)->select('name','provider','tipe','code','percent','biaya_admin','status','keterangan')->first();
            $payment_code = $dataMethod->code;
            $payment_name = $dataMethod->name;
            $provider_payment = $dataMethod->provider;
            $payment_tipe = $dataMethod->tipe;
            
            if($dataMethod->status == 'ON') {
                if($dataMethod->percent == '%') {
                    $biaya_admin = $dataLayanan->harga * ($dataMethod->biaya_admin / 100);
                } else if($dataMethod->percent == "+") {
                    $biaya_admin = $dataMethod->biaya_admin;
                }
            } else {
                $biaya_admin = 0;
            }
        }
        
        $dataLayanan->harga = round($dataLayanan->harga + $biaya_admin);
        
        if($payment_name == "Saldo"){
            $amount = $dataLayanan->harga;
        }else if($dataMethod->provider == "manual"){
            $amount = $dataLayanan->harga + ($rand);
            $reference = null;            
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
            if($dataMethod->provider == "duitku") {
                if($api->duitku_status == "Active") {
                    $listchannel = [];
                    foreach($duitku->channel(str_replace('/[^0-9]/', '', number_format($dataLayanan->harga)))->paymentFee as $channel){
                        array_push($listchannel,$channel->paymentMethod);
                    }
                    unset($listchannel['OVO_MANUAL']);
                    if(!in_array($dataMethod['code'],$listchannel)){
                        return response()->json([
                            'status'     => false,
                            'data'    => "Tipe pembayaran tidak sah, silahkan hubungi admin!"
                        ]);
                    }
                    $duitkuress = $duitku->requestPayment($order_id, $dataLayanan->harga, $dataMethod['code'], $req_email, $phone, env("APP_URL").'/pembelian/invoice/'.$order_id);
        
                    if($duitkuress['success'] != true) return response()->json(['status' => false, 'data' => $duitkuress['msg']]);
                    $no_pembayaran = $duitkuress['no_pembayaran'] == null ? null : $duitkuress['no_pembayaran'];
                    $reference = $duitkuress['reference'] == null ? "" : $duitkuress['reference'];
                    $amount = $duitkuress['amount'] == null ? $dataLayanan->harga + $biaya_admin : $duitkuress['amount'];
                    $checkouturl = $duitkuress['checkout_url'] == null ? "" : $duitkuress['checkout_url'];
                } else {
                    return response()->json(['status' => false, 'data' => 'Metode pembayaran ini tidak aktif, silahkan hubungi admin!']);
                }
            } else if($dataMethod->provider == "linkqu") {
                if($api->linkqu_status == "Active") {
                    if($dataMethod->tipe == "e-walet") {
                        $listchannel = [];
                        foreach($linkqu->channel_ewallet()->data->dataproduk as $channel){
                         array_push($listchannel,$channel->kodebank);
                        }
                        unset($listchannel['OVO_MANUAL']);
                        if(!in_array($dataMethod['code'],$listchannel)){
                            
                            
                            return response()->json([
                                'status'     => false,
                                'data'    => "Tipe pembayaran tidak sah, silahkan hubungi admin!"
                            ]);
                            
                        }
                    } else if($dataMethod->tipe == "virtual-account") {
                        $listchannel = [];
                        foreach($linkqu->channel_va()->data as $channel){
                         array_push($listchannel,$channel->kodeBank);
                        }
                        unset($listchannel['OVO_MANUAL']);
                        if(!in_array($dataMethod['code'],$listchannel)){
                            
                            
                            return response()->json([
                                'status'     => false,
                                'data'    => "Tipe pembayaran tidak sah, silahkan hubungi admin!"
                            ]);
                            
                        }
                    }
                    $linkqures = $linkqu->requestPayment($dataMethod->tipe, $dataLayanan->harga, $order_id, $kode_unik, $request->nomor, $req_email, $dataMethod['code']);
                        
                    if($linkqures['success'] != true) return response()->json(['status' => false, 'data' => $linkqures['msg']]);
                        
                    $no_pembayaran = $linkqures['no_pembayaran'];
                    $reference = $linkqures['reference'];
                    $amount = $linkqures['amount'];
                    $checkouturl = $linkqures['checkout_url'];
                } else {
                    return response()->json(['status' => false, 'data' => 'Metode pembayaran ini tidak aktif, silahkan hubungi admin!']);
                }
            } else if($dataMethod->provider == "ipaymu") {
                if($api->ipaymu_status == "Active") {
                    $listchannel = [];
                    foreach($ipaymu->channel()->Data->Channels as $channel){
                        array_push($listchannel,$channel->Code);
                    }
                    unset($listchannel['OVO_MANUAL']);
                    if(!in_array($dataMethod['code'],$listchannel)){
                        return response()->json([
                            'status'     => false,
                            'data'    => "Tipe pembayaran tidak sah, silahkan hubungi admin!"
                        ]);
                    }
                    $ipayres = $ipaymu->requestPayment($dataLayanan->harga, $order_id, $phone, $req_email,$dataMethod['code']);
                    if($ipayres['Status'] != 200) return response()->json(['status' => false, 'data' => 'Metode pembayaran ini sedang tidak dapat digunakan']);
                    
                    $no_pembayaran = $ipayres['Data']['QrString'] == null ? $ipayres['Data']['PaymentNo'] : $ipayres['Data']['QrString'];
                    $reference = $ipayres['Data']['TransactionId'];
                    $amount = $ipayres['Data']['Total'];
                    $checkouturl = null;
                } else {
                    return response()->json(['status' => false, 'data' => 'Metode pembayaran ini tidak aktif, silahkan hubungi admin!']);
                }
            } else if($dataMethod->provider == "paydisini") {
                if($api->paydisini_status == "Active") {
                    $listchannel = [];
                    foreach($paydisini->channel()->data as $channel){
                        array_push($listchannel,$channel->id);
                    }
                    unset($listchannel['OVO_MANUAL']);
                    if(!in_array($dataMethod['code'],$listchannel)){
                        return response()->json([
                            'status'     => false,
                            'data'    => "Tipe pembayaran tidak sah, silahkan hubungi admin!"
                        ]);
                    }
                    $paydisinires = $paydisini->requestPayment($dataLayanan->harga, $order_id, $phone, $req_email, $dataMethod['code'], env("APP_URL").'/pembelian/invoice/'.$order_id);
                    if($paydisinires['success'] != true) return response()->json(['status' => false, 'data' => $paydisinires['msg']]);
                    
                    $no_pembayaran = $paydisinires['no_pembayaran'];
                    $reference = $paydisinires['reference'];
                    $amount = $paydisinires['amount'];
                    $checkouturl = $paydisinires['checkout_url'];
                } else {
                    return response()->json(['status' => false, 'data' => 'Metode pembayaran ini tidak aktif, silahkan hubungi admin!']);
                }
            } else if($dataMethod->provider == "tripay") {
                if($api->tripay_status == "Active") {
                    $listchannel = [];
                    foreach($tripay->channel()->data as $channel){
                     array_push($listchannel,$channel->code);
                    }
                    unset($listchannel['OVO_MANUAL']);
                    if(!in_array($dataMethod['code'],$listchannel)){
                        
                        
                        return response()->json([
                            'status'     => false,
                            'data'    => "Tipe pembayaran tidak sah, silahkan hubungi admin!"
                        ]);
                        
                    }
                    $tripayres = $tripay->request($order_id, $dataLayanan->harga, $dataMethod['code'], $req_email, $phone);
        
                    
                    if($tripayres['success'] != true) return response()->json(['status' => false, 'data' => $tripayres['msg']]);
                    $no_pembayaran = $tripayres['no_pembayaran'];
                    $reference = $tripayres['reference'];
                    $amount = $tripayres['amount'];
                    $checkouturl = $tripayres['checkout_url'] ?? $no_pembayaran;
                } else {
                    return response()->json(['status' => false, 'data' => 'Metode pembayaran ini tidak aktif, silahkan hubungi admin!']);
                }
                
            } else if($dataMethod->provider == "tokopay") {
                if($api->tokopay_status == "Active") {
                    $tokopayres = $tokopay->requestPay($dataLayanan->harga, $order_id, $phone, $dataMethod['code'], $req_email, env("APP_URL").'/pembelian/invoice/'.$order_id, $dataLayanan->provider_id);
                    
                    if($tokopayres['success'] != true) return response()->json(['status' => false, 'data' => $tokopayres['msg']]);
    
                    $no_pembayaran = $tokopayres['no_pembayaran'];
                    $reference = $tokopayres['reference'];
                    $amount = $tokopayres['amount'];
                    $checkouturl = $tokopayres['checkout_url'];
                } else {
                    return response()->json(['status' => false, 'data' => 'Metode pembayaran ini tidak aktif, silahkan hubungi admin!']);
                }
            } else if($dataMethod->provider == "xendit") {
                if($api->xendit_status == "Active") {
                    $xenditres = $xendit->createTransaction($dataLayanan->harga, $order_id, $dataMethod['code']);
                    
                    if($xenditres['status'] != "PENDING") return response()->json(['status' => false, 'data' => 'Pembayaran Error, Silahkan hubungi admin!']);
                    if($dataMethod->tipe == 'virtual-account') {
                        $no_pembayaran = $xendit->createVA($order_id,$dataMethod['code'])['account_number'];
                        $checkouturl = null;
                    } else if($dataMethod->tipe == 'convenience-store') {
                        $no_pembayaran = $xendit->createRetail($dataLayanan->harga,$order_id,$dataMethod['code'])['payment_code'];
                        $checkouturl = null;
                    } else if($dataMethod->tipe == 'qris') {
                        $no_pembayaran = $xendit->createQR($dataLayanan->harga,$order_id,$dataMethod['code'])['qr_string'];
                        $checkouturl = null;
                    } else {
                        $no_pembayaran = null;
                        $checkouturl = $xenditres['invoice_url'];
                    }
                    $reference = $xenditres['id'];
                    $amount = $xenditres['amount'];
                } else {
                    return response()->json(['status' => false, 'data' => 'Metode pembayaran ini tidak aktif, silahkan hubungi admin!']);
                }
            } else {
                return response()->json([
                    'status'     => false,
                    'data'    => "Metode pembayaran tidak ada, silahkan hubungi admin!"
                ]);
            }
        }
        
        if($payment_name != "Saldo"){
            
            $pesan = 
                "PESANAN KAMU BERHASIL DIBUAT\n".
                "❍➤ Informasi pembelian\n".
                "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                "❃ ➤ Tujuan : *$request->uid*\n".
                "❃ ➤ Layanan :  *$kategori->nama*\n".
                "❃ ➤ Produk : *$dataLayanan->layanan*\n" .
                "❃ ➤ Total Harga : *Rp. " . number_format($dataLayanan->harga, 0, '.', ',') . "*\n" .
                "❃ ➤ Pembayaran : *Saldo*\n".
                "❃ ➤ Nomor Invoice : *$order_id*\n\n".
                "" . env("APP_URL") . "/pembelian/invoice/$order_id\n\n" .
                "Ini Adalah Pesan Otomatis, Jika Mengalami Kendala Bisa Menghubungi WA CS Di Bawah:\n".
                "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                "Customer Support : ".$formatter->filter_phone('0',$api->nomor_admin)."\n".
                "Online 24 Jam";
            $requestPesan = $wa->send($phone,$pesan);
            
            if(Auth::check()){
                $username = Auth::user()->username;
            } else {
                $username = null;
            }
            $pembelian = new Pembelian();
            $pembelian->order_id = $order_id;
            $pembelian->username = $username;
            $pembelian->nomor = $phone;
            $pembelian->user_id = $request->uid;
            $pembelian->custom_comments = $request->custom_comments !== null ? $request->custom_comments : null;
            $pembelian->usernames = $request->usernames !== null ? $request->usernames : null;
            $pembelian->hashtag = $request->hashtag !== null ? $request->hashtag : null;
            $pembelian->expiry = $request->expiry !== null ? $request->expiry : null;
            $pembelian->delay = $request->delay !== null ? $request->delay : null;
            $pembelian->old_post = $request->old_post !== null ? $request->old_post : null;
            $pembelian->maximal = $request->maximal !== null ? $request->maximal : null;
            $pembelian->minimal = $request->minimal !== null ? $request->minimal : null;
            $pembelian->post = $request->post !== null ? $request->post : null;
            $pembelian->media = $request->media !== null ? $request->media : null;
            $pembelian->answer_number = $request->answer_number !== null ? $request->answer_number : null;
            $pembelian->keywords = $request->keywords !== null ? $request->keywords : null;
            $pembelian->nickname = $request->nickname;
            $pembelian->quantity = $request->qty;
            $pembelian->layanan = $dataLayanan->layanan;
            $pembelian->id_layanan = $dataLayanan->id;
            $pembelian->harga = $dataLayanan->harga;
            $pembelian->profit = $profit;
            $pembelian->note = null;
            $pembelian->status = 'Pending';
            $pembelian->tipe_transaksi = $request->ktg_tipe;
            $pembelian->ip = $client_ip;
            $pembelian->notify = 0;
            $pembelian->refund = 0;
            $pembelian->save();
    
            $pembayaran = new Pembayaran();
            $pembayaran->username = $username;
            $pembayaran->order_id = $order_id;
            $pembayaran->harga = $dataLayanan->harga;
            $pembayaran->total_harga = $amount;
            $pembayaran->no_pembayaran = $no_pembayaran;
            $pembayaran->no_pembeli = $phone;
            $pembayaran->email_pembeli = $req_email;
            $pembayaran->checkout_url = isset($checkouturl) ? $checkouturl : null;
            $pembayaran->provider = $provider_payment;
            $pembayaran->status = 'Menunggu Pembayaran';
            $pembayaran->metode = $payment_name;
            $pembayaran->metode_code = $payment_code;
            $pembayaran->metode_tipe = $payment_tipe;
            $pembayaran->reference = $reference;
            $pembayaran->voucher_code = $request->voucher;
            $pembayaran->save();
            
            $log = new LogUser();
            $log->order_id = $order_id;
            $log->user = $username;
            $log->user_id = $request->uid;
            $log->zone = $request->zone;
            $log->no_pembeli = $phone;
            $log->email_pembeli = $req_email;
            $log->id_kategori = $dataLayanan->kategori_id;
            $log->id_layanan = $dataLayanan->id;
            $log->type = 'order';
            $log->text = 'IP : '.$client_ip.' melakukan order #'.$order_id;
            $log->ip = $client_ip;
            $log->loc = $client_iploc;
            $log->ua = $browser;
            $log->save();
            
            RateLimiter::hit($this->orderThrottleKey($request));
        }else if($payment_name == "Saldo"){
            if(Auth::check()) {
                $user = User::where('username', Auth::user()->username)->first();
                
                if ($dataLayanan->harga > $user->balance) return response()->json(['status' => false, 'data' => 'Saldo anda tidak cukup']);
    
                    if($dataLayanan->provider == "partaisocmed"){
                        if($api->partaisosmed_status == "Active") {
                            $partaisocmed = new PartaisocmedController;
                            $order = $partaisocmed->order($request->uid, $dataLayanan->provider_id, $request->qty, $request->keywords, $request->custom_comments, $request->usernames, $request->hashtag, $request->media, $request->answer_number,
                            $request->minimal, $request->maximal, $request->post, $request->old_post, $request->delay, $request->expiry);
                            
                            if($order['result']){
                                $order['status'] = true;
                                $order['transactionId'] = $order['data']['trxid'];
                                $msg =  $order['message'];
                            }else{
                                $msg =  $order['message'];
                                $order['status'] = false;
                            }
                        } else {
                            return response()->json(['status' => false, 'data' => 'Transaksi bermasalah, silahkan hubungi admin!']);
                        }
                    }else if($dataLayanan->provider == "irvankedesmm"){
                        if($api->irvankede_status == "Active") {
                            $irvankedesmm = new IrvankedesmmController;
                            $order = $irvankedesmm->order($request->uid, $dataLayanan->provider_id, $request->qty, $request->custom_comments, $request->usernames, $request->hashtag, $request->media, $request->answer_number);
                            
                            if($order['result']){
                                $order['status'] = true;
                                $order['transactionId'] = $order['data']['trxid'];
                                $msg =  $order['message'];
                            }else{
                                $msg =  $order['message'];
                                $order['status'] = false;
                            }
                        } else {
                            return response()->json(['status' => false, 'data' => 'Transaksi bermasalah, silahkan hubungi admin!']);
                        }
                    }else if($dataLayanan->provider == "vipmember"){
                        if($api->vipmember_status == "Active") {
                            $vipmember = new VipMemberController;
                            $order = $vipmember->order($request->uid, $dataLayanan->provider_id, $request->qty, $request->usernames, $request->answer_number, $request->custom_comments);
                            
                            if($order['result']){
                                $order['status'] = true;
                                $order['transactionId'] = $order['data']['trxid'];
                                $msg =  $order['message'];
                            }else{
                                $msg =  $order['message'];
                                $order['status'] = false;
                            }
                        } else {
                            return response()->json(['status' => false, 'data' => 'Transaksi bermasalah, silahkan hubungi admin!']);
                        }
                    }else if($dataLayanan->provider == "istanamarket"){
                        if($api->istanamarket_status == "Active") {
                            $istanamarket = new IstanaMarketController;
                            $order = $istanamarket->order($request->uid, $dataLayanan->provider_id, $request->qty, $request->custom_comments);
                            
                            if($order['result']){
                                $order['status'] = true;
                                $order['transactionId'] = $order['data']['trxid'];
                                $msg =  $order['message'];
                            }else{
                                $msg =  $order['message'];
                                $order['status'] = false;
                            }
                        } else {
                            return response()->json(['status' => false, 'data' => 'Transaksi bermasalah, silahkan hubungi admin!']);
                        }
                    }else if($dataLayanan->provider == "fanstore"){
                        if($api->fanstore_status == "Active") {
                            $fanstore = new FanstoreController;
                            $order = $fanstore->order($request->uid, $dataLayanan->provider_id, $request->qty);
                            
                            if($order['result']){
                                $order['status'] = true;
                                $order['transactionId'] = $order['data']['trxid'];
                                $msg =  $order['message'];
                            }else{
                                $msg =  $order['message'];
                                $order['status'] = false;
                            }
                        } else {
                            return response()->json(['status' => false, 'data' => 'Transaksi bermasalah, silahkan hubungi admin!']);
                        }
                    }else if($dataLayanan->provider == "rasxmedia"){
                        if($api->rasxmedia_status == "Active") {
                            $rasxmedia = new RasxmediaController;
                            $order = $rasxmedia->order($request->uid, $dataLayanan->provider_id, $request->qty, $request->custom_comments);
                            
                            if($order['result']){
                                $order['status'] = true;
                                $order['transactionId'] = $order['data']['trxid'];
                                $msg =  $order['message'];
                            }else{
                                $msg =  $order['message'];
                                $order['status'] = false;
                            }
                        } else {
                            return response()->json(['status' => false, 'data' => 'Transaksi bermasalah, silahkan hubungi admin!']);
                        }
                    }else if($dataLayanan->provider == "manual"){
                        $order['transactionId'] = '';
                        $order['status'] = true;
                    }
                
                
                if($order['status']){
                    $pesanSukses = 
                                "PESANAN KAMU BERHASIL DIBUAT\n".
                                "❍➤ Informasi pembelian\n".
                                "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                                "❃ ➤ Tujuan : *$request->uid*\n".
                                "❃ ➤ Layanan :  *$kategori->nama*\n".
                                "❃ ➤ Produk : *$dataLayanan->layanan*\n" .
                                "❃ ➤ Total Harga : *Rp. " . number_format($dataLayanan->harga, 0, '.', ',') . "*\n" .
                                "❃ ➤ Pembayaran : *Saldo*\n".
                                "❃ ➤ Status : *Lunas*\n".
                                "❃ ➤ Nomor Invoice : *$order_id*\n\n".
                                "Ini Adalah Pesan Otomatis, Jika Mengalami Kendala Bisa Menghubungi WA CS Di Bawah:\n".
                                "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                                "Customer Support : ".$formatter->filter_phone('0',$api->nomor_admin)."\n".
                                "Online 24 Jam";
        
                            $pesanSuksesAdmin = 
                                "PESANAN KAMU BERHASIL DIBUAT\n".
                                "❍➤ Informasi pembelian\n".
                                "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                                "❃ ➤ Tujuan : $request->uid*\n".
                                "❃ ➤ Layanan :  *$kategori->nama*\n".
                                "❃ ➤ Produk : *$dataLayanan->layanan*\n" .
                                "❃ ➤ Total Harga : *Rp. " . number_format($dataLayanan->harga, 0, '.', ',') . "*\n" .
                                "❃ ➤ Pembayaran : *Saldo*\n".
                                "❃ ➤ Status : *Lunas*\n".
                                "❃ ➤ Nomor Invoice : *$order_id*\n".
                                "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                                "*Kontak Pembeli*\n".
                                "No HP : $phone";
                       
                        $wa->send($phone, $pesanSukses);
                        $wa->send($formatter->filter_phone('62',$api->nomor_admin), $pesanSuksesAdmin);
                    
                    $user->update([
                        'balance' => $user->balance - $dataLayanan->harga
                    ]);
                    if($request->email != null || $kategori->tipe == "sosmed") {
                        Mail::to($request->email)->send(new SendEmailbySaldo([
                            'datalayanan' => $dataLayanan,
                            'kategori' => $kategori,
                            'target' => $request->uid,
                            'target2' => $request->zone,
                            'orderid' => $order_id,
                            'created_at' => now(),
                            'pay' => 'Saldo',
                            'qty' => $request->qty,
                            'status' => 'Pending',
                            'total' => $dataLayanan->harga
                        ]));
                    }
                    $pembelian = new Pembelian();
                    $pembelian->username = Auth::user()->username;
                    $pembelian->nomor = $phone;
                    $pembelian->order_id = $order_id;
                    $pembelian->user_id = $request->uid;
                    $pembelian->custom_comments = $request->custom_comments !== null ? $request->custom_comments : null;
                    $pembelian->usernames = $request->usernames !== null ? $request->usernames : null;
                    $pembelian->hashtag = $request->hashtag !== null ? $request->hashtag : null;
                    $pembelian->expiry = $request->expiry !== null ? $request->expiry : null;
                    $pembelian->delay = $request->delay !== null ? $request->delay : null;
                    $pembelian->old_post = $request->old_post !== null ? $request->old_post : null;
                    $pembelian->maximal = $request->maximal !== null ? $request->maximal : null;
                    $pembelian->minimal = $request->minimal !== null ? $request->minimal : null;
                    $pembelian->post = $request->post !== null ? $request->post : null;
                    $pembelian->media = $request->media !== null ? $request->media : null;
                    $pembelian->answer_number = $request->answer_number !== null ? $request->answer_number : null;
                    $pembelian->keywords = $request->keywords !== null ? $request->keywords : null;
                    $pembelian->nickname = $request->nickname;
                    $pembelian->quantity = $request->qty;
                    $pembelian->layanan = $dataLayanan->layanan;
                    $pembelian->id_layanan = $dataLayanan->id;
                    $pembelian->harga = $dataLayanan->harga;
                    $pembelian->profit = $profit;
                    $pembelian->status = 'Pending';
                    $pembelian->note = null;
                    $pembelian->provider_order_id = $order['transactionId'] ? $order['transactionId'] : null;
                    $pembelian->log = json_encode($order);
                    $pembelian->tipe_transaksi = $request->ktg_tipe;
                    $pembelian->ip = $client_ip;
                    $pembelian->notify = 0;
                    $pembelian->refund = 0;
                    $pembelian->save();
    
                    $pembayaran = new Pembayaran();
                    $pembayaran->username = Auth::user()->username;
                    $pembayaran->order_id = $order_id;
                    $pembayaran->harga = $dataLayanan->harga;
                    $pembayaran->total_harga = $dataLayanan->harga;
                    $pembayaran->no_pembayaran = "Saldo";
                    $pembayaran->no_pembeli = $phone;
                    $pembayaran->email_pembeli = $req_email;
                    $pembayaran->provider = 'saldo';
                    $pembayaran->status = 'Lunas';
                    $pembayaran->metode = 'Saldo';
                    $pembayaran->metode_code = 'Saldo';
                    $pembayaran->metode_tipe = 'manual';
                    $pembayaran->reference = null;
                    $pembayaran->voucher_code = $request->voucher;
                    $pembayaran->save();
                    
                    $log = new LogUser();
                    $log->user = Auth::user()->username;
                    $log->order_id = $order_id;
                    $log->user_id = $request->uid;
                    $log->zone = $request->zone;
                    $log->no_pembeli = $phone;
                    $log->email_pembeli = $req_email;
                    $log->id_kategori = $dataLayanan->kategori_id;
                    $log->id_layanan = $dataLayanan->id;
                    $log->type = 'order';
                    $log->text = 'IP : '.$client_ip.' melakukan order #'.$order_id;
                    $log->ip = $client_ip;
                    $log->loc = $client_iploc;
                    $log->ua = $browser;
                    $log->save();
                    RateLimiter::hit($this->orderThrottleKey($request));
                }else{
                    return response()->json([
                        'status' => false,
                        'data' => isset($msg) ? 'Disebabkan '.$msg : 'Server Error'
                    ]);
                }
            } else {
                return response()->json([
                        'status' => false,
                        'data' => 'Silahkan login terlebih dahulu!'
                    ]);
            }
        }

        return response()->json([
            'status' => true,
            'order_id' => $order_id
        ]);
    }
    protected function orderThrottleKey(Request $request) {
        // Use the user's IP address for the throttle key
        return $request->ip();
    }
}
