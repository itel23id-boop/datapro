<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artikel;
use App\Models\Kategori;
use App\Models\Layanan;
use App\Models\Berita;
use App\Models\KategoriTipe;
class IndexController extends Controller
{
    public function create()
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        return view('layout.home.'.$api->change_theme, [
            'Tab' => KategoriTipe::where('status', 'ON')->orderBy('id', 'asc')->get(),
            'kategori' => Kategori::where('status', 'active')->get(),
            'jumlah_kat' => Kategori::where('tipe', 'game')->get(),
            'jumlah_user' => \App\Models\User::all(),
            'jumlah_pembayaran' => \App\Models\Pembayaran::all(),
            'jumlah_pembelian' => \App\Models\Pembelian::all(),
            'banner' => Berita::where('tipe', 'banner')->get(),
            'logoheader' => Berita::where('tipe', 'logoheader')->latest()->first(),
            'logofooter' => Berita::where('tipe', 'logofooter')->latest()->first(),
            'popup' => Berita::where('tipe', 'popup')->latest()->first(),
            'flashsale' => \App\Models\Promo::get(),
            'artikel' => Artikel::orderBy('updated_at', 'desc')->get(),
            'pay_method' => \App\Models\Method::all()
        ]);
    }
    public function get_notif()
    {
        $pesanan = \App\Models\Pembelian::inRandomOrder()->orderBy('id','DESC')->get();
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $results = [];
        foreach($pesanan as $data) {
            $layanan = Layanan::where('id', $data->id_layanan)->select('kategori_id','layanan')->first();
            $kat = Kategori::where('id', $layanan->kategori_id)->select('thumbnail','nama','kode')->first();
            $results[] = [
                'order' => '
                <a href="'.e(env('APP_URL').'/order/'.$kat->kode).'" target="blank_">
                    <img src="'.e($kat->thumbnail).'" class="altumcode-conversions-image" alt="'.e($kat->nama).'" loading="lazy">
                </a>
                <div style="width: 100%!important;">
                    <div class="altumcode-conversions-header">
                        <p class="altumcode-conversions-title" style="color: #fff">'.e(substr($data->order_id, 0, -18)).'***INV</p>
                    </div>
                    <p class="altumcode-conversions-description" style="color: #fff">Membeli '.e($kat->nama).' '.e($data->layanan).'</p>
                    <div class="altumcode-conversions-time">sekarang</div>
                    <a href="'.e(env("APP_URL")).'" class="altumcode-site italic" style="color:white;">'.e($api->judul_web).'</a>
                </div>
                ',
            ];
        }
        return $results;
    }
    public function cariIndex(Request $request)
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $validated = $request->validate([
            'data' => 'nullable|string|max:255',
            'to_search' => 'nullable|string|max:255',
        ]);

        if($api->change_theme == "1") {
            $search = $validated['data'] ?? null;
            if($search !== null){
                $data = Kategori::where('nama','LIKE','%'.$search.'%')->where('status','active')->limit(6)->get();
            } else {
                $data = Kategori::where('status','active')->get();
            }
            $res = '';
            foreach($data as $d){
                $res .= '
                <a class="group featured-game-card relative transform overflow-hidden rounded-2xl bg-murky-700 duration-300 ease-in-out hover:shadow-2xl hover:ring-2 hover:ring-primary-500 hover:ring-offset-2 hover:ring-offset-murky-80 text-decoration-none" href="'.e(url('/order/'.$d->kode)).'" style="outline: none;">
                    <div class="blur-sharp ">
                    <img alt="'.e($d->nama).'" sizes="100vw" src="'.e($d->thumbnail).'" width="202" height="288" decoding="async" data-nimg="1" class="aspect-[4/6] object-cover object-center" style="color: transparent;max-width: 100%;height: auto;" />
                    </div>
                    <article class="absolute inset-x-0 -bottom-10 z-10 flex transform flex-col px-3 transition-all duration-300 ease-in-out group-hover:bottom-3 sm:px-4 group-hover:sm:bottom-4">
                        <h2 class="truncate text-sm font-semibold text-murky-200 sm:text-base">'.e($d->nama).'</h2>
                        <p class="truncate text-xxs text-murky-400 sm:text-xs mt-1">'.e($d->sub_nama).'</p>
                    </article>
                    <div class="absolute inset-0 transform bg-gradient-to-t from-transparent transition-all duration-300 group-hover:from-murky-900"></div>
                </a>';
            }
            return $res;
        } else if($api->change_theme == "2") {
            $search = $validated['to_search'] ?? null;
            if($search !== null){
                $data = Kategori::where('nama','LIKE','%'.$search.'%')->where('status','active')->limit(6)->get();
            } else {
                $data = Kategori::where('status','active')->get();
            }
            $out = [];
            foreach($data as $d) {
                $out[] = ['kode' => $d->kode, 'nama' => $d->nama, 'thumbnail' => $d->thumbnail, 'sub_nama' => $d->sub_nama];
            }
            return response()->json(['data' => $out]);
        }
    }
    public function notFound()
    {
        return view('errors.404');
    }
    public function error()
    {
        return view('errors.500');
    }
}
