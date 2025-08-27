<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artikel;
use App\Models\Kategori;
use App\Models\Layanan;
use App\Models\Berita;
use App\Models\Seting;
use App\Models\KategoriTipe;
class indexController extends Controller
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
        foreach($pesanan as $data) {
            $layanan = Layanan::where('id', $data->id_layanan)->select('kategori_id','layanan')->first();
            $kat = Kategori::where('id', $layanan->kategori_id)->select('thumbnail','nama','kode')->first();
            $result = array(
                'order' => '
                <a href="'.env('APP_URL').'/order/'.$kat->kode.'" target="blank_">
                    <img src="'.$kat->thumbnail.'" class="altumcode-conversions-image" alt="'.$kat->nama.'" loading="lazy">
                </a>
                <div style="width: 100%!important;">
                    <div class="altumcode-conversions-header">
                        <p class="altumcode-conversions-title" style="color: #fff">'.substr($data->order_id, 0, -18).'***INV</p>
                    </div>
                    <p class="altumcode-conversions-description" style="color: #fff">Membeli '.$kat->nama.' '.$data->layanan.'</p>
                    <div class="altumcode-conversions-time">sekarang</div>
                    <a href="'.env("APP_URL").'" class="altumcode-site italic" style="color:white;">'.$api->judul_web.'</a>
                </div>
                '
			);
            return $result;
        }
    }
    public function cariIndex(Request $request)
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        if($api->change_theme == "1") {
            if(isset($request->data)){
                $data = Kategori::where('nama','LIKE','%'.$request->data.'%')->where('status','active')->limit(6)->get();
                $res = '';
                foreach($data as $d){
                    $res .= '
        							<a
                                        class="group featured-game-card relative transform overflow-hidden rounded-2xl bg-murky-700 duration-300 ease-in-out hover:shadow-2xl hover:ring-2 hover:ring-primary-500 hover:ring-offset-2 hover:ring-offset-murky-80 text-decoration-none"
                                        href="'.url('/order').'/'.$d->kode.'"
                                        style="outline: none;"
                                    >
                                        <div class="blur-sharp ">
                                        <img
                                            alt="'.$d->nama.'"
                                            sizes="100vw"
                                            src="'.$d->thumbnail.'"
                                            width="202"
                                            height="288"
                                            decoding="async"
                                            data-nimg="1"
                                            class="aspect-[4/6] object-cover object-center"
                                            style="color: transparent;max-width: 100%;height: auto;"
                                        />
                                        </div>
                                        <article class="absolute inset-x-0 -bottom-10 z-10 flex transform flex-col px-3 transition-all duration-300 ease-in-out group-hover:bottom-3 sm:px-4 group-hover:sm:bottom-4">
                                            <h2 class="truncate text-sm font-semibold text-murky-200 sm:text-base">'.$d->nama.'</h2>
                                            <p class="truncate text-xxs text-murky-400 sm:text-xs mt-1">'.$d->sub_nama.'</p>
                                        </article>
                                        <div class="absolute inset-0 transform bg-gradient-to-t from-transparent transition-all duration-300 group-hover:from-murky-900"></div>
                                    </a>
                    ';
                }
                return $res;
            } else {
                $data = Kategori::where('status','active')->where('status','active')->get();
                $res = '';
                foreach($data as $d){
                    $res .= '
        							<a
                                        class="group featured-game-card relative transform overflow-hidden rounded-2xl bg-murky-700 duration-300 ease-in-out hover:shadow-2xl hover:ring-2 hover:ring-primary-500 hover:ring-offset-2 hover:ring-offset-murky-80 text-decoration-none"
                                        href="'.url('/order').'/'.$d->kode.'"
                                        style="outline: none;"
                                    >
                                        <div class="blur-sharp ">
                                        <img
                                            alt="'.$d->nama.'"
                                            sizes="100vw"
                                            src="'.$d->thumbnail.'"
                                            width="202"
                                            height="288"
                                            decoding="async"
                                            data-nimg="1"
                                            class="aspect-[4/6] object-cover object-center"
                                            style="color: transparent;max-width: 100%;height: auto;"
                                        />
                                        </div>
                                        <article class="absolute inset-x-0 -bottom-10 z-10 flex transform flex-col px-3 transition-all duration-300 ease-in-out group-hover:bottom-3 sm:px-4 group-hover:sm:bottom-4">
                                            <h2 class="truncate text-sm font-semibold text-murky-200 sm:text-base">'.$d->nama.'</h2>
                                            <p class="truncate text-xxs text-murky-400 sm:text-xs mt-1">'.$d->sub_nama.'</p>
                                        </article>
                                        <div class="absolute inset-0 transform bg-gradient-to-t from-transparent transition-all duration-300 group-hover:from-murky-900"></div>
                                    </a>
                    ';
                }
                return $res;
            }
        } else if($api->change_theme == "2") {
            if(isset($request->to_search)){
                $data = Kategori::where('nama','LIKE','%'.$request->to_search.'%')->where('status','active')->limit(6)->get();
                foreach($data as $d) {
                    $out[] = ['kode' => $d->kode, 'nama' => $d->nama, 'thumbnail' => $d->thumbnail, 'sub_nama' => $d->sub_nama];
                }
                return response()->json(['data' => $out]);
            } else {
                $data = Kategori::where('status','active')->where('status','active')->get();
                foreach($data as $d) {
                    $out[] = ['kode' => $d->kode, 'nama' => $d->nama, 'thumbnail' => $d->thumbnail, 'sub_nama' => $d->sub_nama];
                }
                return response()->json(['data' => $out]);
            }
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
