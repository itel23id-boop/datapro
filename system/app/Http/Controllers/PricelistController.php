<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\Layanan;
use App\Models\Berita;
use App\Models\Seting;

class PricelistController extends Controller
{
    public function create(Request $request)
    {
        if($request->id) {
            $datas = Layanan::where('kategoris.id', $request->id)->join('kategoris', 'layanans.kategori_id', 'kategoris.id')->where('kategoris.status', 'active')->where('layanans.status', 'available')->orderBy('harga', 'asc')
                ->select('layanans.*', 'kategoris.nama AS nama_kategori', 'kategoris.id AS id_kategori', 'layanans.status AS status_layanan')->get();
            $kategoris = Kategori::where('id',$request->id)->where('status','active')->orderBy('nama', 'asc')->first();
        } else {
            $datas = Layanan::join('kategoris', 'layanans.kategori_id', 'kategoris.id')->where('kategoris.status', 'active')->where('layanans.status', 'available')->orderBy('harga', 'asc')
                ->select('layanans.*', 'kategoris.nama AS nama_kategori', 'kategoris.id AS id_kategori', 'layanans.status AS status_layanan')->get();
            $kategoris = null;
        }

        return view('components.pricelist', [
        'datas' => $datas, 'kategoris' => Kategori::orderBy('nama', 'asc')->where('status','active')->get(),
        'id' => $kategoris,
        'logoheader' => Berita::where('tipe', 'logoheader')->latest()->first(),
        'logofooter' => Berita::where('tipe', 'logofooter')->latest()->first(),
        'pay_method' => \App\Models\Method::all()
        ]);
    }
}
