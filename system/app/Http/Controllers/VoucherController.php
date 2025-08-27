<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\VoucherList;
use App\Models\Layanan;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;
use App\Models\LogUser;
use App\Helpers\Formater;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class VoucherController extends Controller
{
    public function create()
    {
        return view('components.admin.voucher.index', ['vouchers' => Voucher::orderBy('created_at', 'desc')->get(),'voucherlist' => VoucherList::orderBy('created_at', 'desc')->get()]);
    }
    
    public function globals(Request $request)
    {
        if($request->globals == "tidak") {
            $data = Kategori::get();
            if ($data == "[]") return response()->json(['status' => false, 'data' => '<option value="0">Kategori tidak ditemukan!</option>']);
            $dataHtml = '<option value="0">- Select Kategori -</option>';
            foreach ($data as $kategori) {
                $dataHtml .= "<option value='$kategori->id'>$kategori->nama</option>";
            }
    
            return response()->json([
                'status' => true,
                'data' => $dataHtml
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data' => null
            ]);
        }
    }
    
    public function store(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $request->validate([
            'globals' => 'required',
            'kode' => 'required|unique:vouchers,kode',
            'promo' => 'required|numeric|min:0|max:100',
            'stock' => 'required|numeric|min:0',
            'max_potongan' => 'required|numeric|min:0',
            'versi' => 'required',
            'expired' => 'required',
        ]);
        $formatTanggal = Carbon::parse($request->expired)->format('Y-m-d H:i:s');
        $voucher = new Voucher();
        $voucher->globals = $request->globals == 'ya' ? 1 : 0;
        $voucher->kategori_id = $request->kategori_id;
        $voucher->kode = $request->kode;
        $voucher->promo = $request->promo;
        $voucher->stock = $request->stock;
        $voucher->max_potongan = $request->max_potongan;
        $voucher->min_transaksi = $request->min_transaksi;
        $voucher->versi = $request->versi;
        $voucher->limit_voucher_login = $request->limit_voucher_login;
        $voucher->role = $request->role;
        $voucher->expired = $formatTanggal;
        $voucher->save();
        
        $log = new LogUser();
        $log->user = Auth::user()->username;
        $log->type = 'system';
        $log->text = 'IP : '.$client_ip.' Melakukan penambahan voucher '.$request->kode;
        $log->ip = $client_ip;
        $log->loc = $client_iploc;
        $log->ua = $browser;
        $log->save();
        
        return back()->with('success', 'Berhasil menambahkan voucher');
    }
    
    public function destroy($id)
    {
        Voucher::where('id', $id)->delete();
        
        return back()->with('success', 'Berhasil menghapus voucher');
    }
    
    public function destroy_list($id)
    {
        VoucherList::where('id', $id)->delete();
        
        return back()->with('success', 'Berhasil menghapus voucher list');
    }
    
    public function confirm(Request $request)
    {
        
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        if(!$request->voucher) return response()->json(['status' => false, 'code' => 400, 'message' => 'Voucher harus di isi.']);
        if(!$request->target || $request->target == ' ' || $request->target == '' || $request->target == ',') return response()->json(['status' => false,'code' => 402, 'message' => 'Silahkan isi data pelanggan.']);
        if(!$request->service) return response()->json(['status' => false,'code' => 401, 'message' => 'Silahkan pilih nominal terlebih dahulu.']);
        
        $voucher = Voucher::where('kode', $request->voucher)->first();
        
        if(isset($voucher->kategori_id)) {
            $kategori = Kategori::where('id',$voucher->kategori_id)->first();
            
            if($voucher->globals == 0 AND $voucher->kategori_id == $kategori->id AND $voucher->versi == "login") {
                if(Auth::check()){
                    if($voucher->role != Auth::user()->role){
                        return response()->json(['status' => false,'code' => 400, 'message' => 'Voucher khusus role '.$voucher->role]);
                    }
                } else {
                    return response()->json(['status' => false,'code' => 400, 'message' => 'Voucher ini khusus login dengan role '.$voucher->role]);
                }
            } else if($voucher->globals == 1 AND $voucher->kategori_id == null AND $voucher->versi == "login") {
                if(Auth::check()){
                    if($voucher->role != Auth::user()->role){
                        return response()->json(['status' => false,'code' => 400, 'message' => 'Voucher khusus role '.$voucher->role]);
                    }
                } else {
                    return response()->json(['status' => false,'code' => 400, 'message' => 'Voucher ini khusus login dengan role '.$voucher->role]);
                }
            }
        }
        
        if(!$voucher) return response()->json(['status' => false,'code' => 400, 'message' => 'Voucher tidak ditemukan.']);
        if($voucher->stock == 0) return response()->json(['status' => false,'code' => 400, 'message' => 'Voucher sudah tidak valid.']);
        
        if(isset($request->service)){
            if($voucher->globals == 0) {
                if(Auth::check()){
                    if(Auth::user()->role == "Member"){
                        $service = Layanan::where('id', $request->service)->where('kategori_id', $voucher->kategori_id)->select('harga_member AS harga', 'is_flash_sale')->first();
                    }else if(Auth::user()->role == "Reseller"){
                        $service = Layanan::where('id', $request->service)->where('kategori_id', $voucher->kategori_id)->select('harga_reseller AS harga', 'is_flash_sale')->first();
                    }else if(Auth::user()->role == "VIP" || Auth::user()->role == "Admin"){
                        $service = Layanan::where('id', $request->service)->where('kategori_id', $voucher->kategori_id)->select('harga_vip AS harga', 'is_flash_sale')->first();
                    }
                }else{
                    $service = Layanan::where('id', $request->service)->where('kategori_id', $voucher->kategori_id)->select('harga AS harga', 'is_flash_sale')->first();
                }
            } else if($voucher->globals != 0) {
                if(Auth::check()){
                    if(Auth::user()->role == "Member"){
                        $service = Layanan::where('id', $request->service)->select('harga_member AS harga', 'is_flash_sale')->first();
                    }else if(Auth::user()->role == "Reseller"){
                        $service = Layanan::where('id', $request->service)->select('harga_reseller AS harga', 'is_flash_sale')->first();
                    }else if(Auth::user()->role == "VIP" || Auth::user()->role == "Admin"){
                        $service = Layanan::where('id', $request->service)->select('harga_vip AS harga', 'is_flash_sale')->first();
                    }
                }else{
                    $service = Layanan::where('id', $request->service)->select('harga AS harga', 'is_flash_sale')->first();
                }
            }
            
            if(!$service) return response()->json(['status' => false,'code' => 400, 'message' => 'Voucher ini khusus '.$kategori->nama]);
            if(date('Y-m-d H:i:s') > $voucher->expired) return response()->json(['status' => false,'code' => 400, 'message' => 'Voucher telah kadaluarsa.']);
            if($service->is_flash_sale == 'Yes') return response()->json(['status' => false,'code' => 400, 'message' => 'Promo tidak dapat digunakan pada produk FLASHSALE.']);
            if($voucher->min_transaksi >= $service->harga) return response()->json(['status' => false,'code' => 400, 'message' => 'Minimal transaksi Rp. '.number_format($voucher->min_transaksi).'']);
            if(Auth::check()){
                $voucherlist = VoucherList::where('user_id', Auth::user()->id)->where('kode', $request->voucher)->first();
                if($voucherlist) {
                    if($voucher->versi != "public") {
                        if($voucherlist->limit >= $voucher->limit_voucher_login) return response()->json(['status' => false,'code' => 400, 'message' => 'Voucher telah mencapai limit maximum '.$voucher->limit_voucher_login.'']);
                    }
                }
            }
            $potongan = $service->harga * ($voucher->promo / 100);
            
            if($potongan < $voucher->max_potongan){
                $potongan = $voucher->max_potongan;
            } else if($potongan > $voucher->max_potongan){
                $potongan = $voucher->max_potongan;
            }
            
            $service->harga = $service->harga - $potongan;
            
            $dataMethod = \App\Models\Method::get();
            foreach ($dataMethod as $dataM) {
                if($dataM->status == 'ON') {
                    if($dataM->percent == '%') {
                        $biaya_admin = $service->harga * ($dataM->biaya_admin / 100);
                    } else if($dataM->percent == "+") {
                        $biaya_admin = $dataM->biaya_admin;
                    }
                } else {
                    $biaya_admin = 0;
                }
                if(in_array($dataM->tipe, ['virtual-account','convenience-store','transfer-bank'])) {
                    $status = $service->harga >= 10000 ? 'Av' : 'Dis';
                } else if(in_array($dataM->tipe,['e-walet','qris'])) { 
                    $status = 'Av';
                } else if($dataM->tipe == 'pulsa') { 
                    $status = $service->harga >= 1000 ? 'Av' : 'Dis';
                }
                $out[] = [
                    'id' => $dataM->id,
                    'status' => $status,
                    'text' => "Diskon: Rp. ".number_format($voucher->max_potongan, 0, '.', ','),
                    'kode' => $request->voucher,
                    'price' => "Rp. ".number_format($service->harga + $biaya_admin, 0, '.', ',')
                ];
            }
            $out[] = [
                'id' => 1,
                'status' => 'Av',
                'text' => "Diskon: Rp. ".number_format($voucher->max_potongan, 0, '.', ','),
                'kode' => $request->voucher,
                'price' => "Rp. ".number_format($service->harga, 0, '.', ',')
            ];
            if(Auth::check()){
                $log = new LogUser();
                $log->user = Auth::user()->username;
                $log->type = 'system';
                $log->text = 'IP : '.$client_ip.' menggunakan kode voucher '.$request->voucher;
                $log->ip = $client_ip;
                $log->loc = $client_iploc;
                $log->ua = $browser;
                $log->save();
            } else {
                $log = new LogUser();
                $log->type = 'system';
                $log->text = 'IP : '.$client_ip.' menggunakan kode voucher '.$request->voucher;
                $log->ip = $client_ip;
                $log->loc = $client_iploc;
                $log->ua = $browser;
                $log->save();
            }
            return response()->json($out);
        }
    }
    
    public function show($id)
    {
        $data = Voucher::where('id', $id)->first();

        return view('components.admin.voucher.edit', compact('data'));
    }
    
    public function show_list($id)
    {
        $data = VoucherList::where('id', $id)->first();
        $user = DB::table('users')->where('id',$data->user_id)->first();

        return view('components.admin.voucher.edit-list', compact('data','user'));
    }
    
    public function patch_list(Request $request, $id)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $data = VoucherList::where('id', $id)->first();
        $user = DB::table('users')->where('id',$data->user_id)->first();
        
        $request->validate([
            'kode'  => 'required',
            'limit' => 'required|numeric'
        ]);
        
        VoucherList::where('id', $id)->update([
            'kode' => $request->kode,
            'limit' => $request->limit
        ]);
        
        $log = new LogUser();
        $log->user = Auth::user()->username;
        $log->type = 'system';
        $log->text = 'IP : '.$client_ip.' Melakukan update Vocuher List '.$user->username;
        $log->ip = $client_ip;
        $log->loc = $client_iploc;
        $log->ua = $browser;
        $log->save();
        
        return back()->with('success', 'Berhasil update Vocuher List '.$user->username);
    }
    
    public function patch(Request $request, $id)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $request->validate([
            'kode'  => 'required',
            'promo' => 'required|numeric|min:0|max:100',
            'stock' => 'required|numeric|min:0',
            'max_potongan' => 'required|numeric|min:0'
        ]);
        $formatTanggal = Carbon::parse($request->expired)->format('Y-m-d H:i:s');
        
        Voucher::where('id', $id)->update([
            'globals' => $request->globals == 'ya' ? 1 : 0,
            'kategori_id' => $request->kategori_id,
            'min_transaksi' => $request->min_transaksi,
            'versi' => $request->versi,
            'limit_voucher_login' => $request->versi == "login" ? $request->limit_voucher_login : null,
            'role' => $request->role,
            'expired' => $formatTanggal,
            'kode' => $request->kode,
            'promo' => $request->promo,
            'stock' => $request->stock,
            'max_potongan' => $request->max_potongan
        ]);
        
        $log = new LogUser();
        $log->user = Auth::user()->username;
        $log->type = 'system';
        $log->text = 'IP : '.$client_ip.' Melakukan update kode promo '.$request->kode;
        $log->ip = $client_ip;
        $log->loc = $client_iploc;
        $log->ua = $browser;
        $log->save();
        
        return back()->with('success', 'Berhasil update kode promo '.$request->kode);
    }
}
