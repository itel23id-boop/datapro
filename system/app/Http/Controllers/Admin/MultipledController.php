<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\Layanan;
use App\Models\Profit;
use App\Models\LogUser;
use App\Models\LogoProduct;
use Illuminate\Support\Str;
use App\Helpers\Formater;
use Illuminate\Support\Facades\Auth;

class MultipledController extends Controller
{
    
    public function multiple(Request $request) {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        if($request->product_logo != 0) {
            $formFields = $request->validate([
                'id' => 'required'
            ]);
            $services = $formFields['id'];
            foreach ($services as $service) {
                $logo_p = LogoProduct::where('id', $request->product_logo)->first();
                $formFields['id'] = $service;
                $l = Layanan::where('id', $formFields)->select('kategori_id')->first();
                $data = Kategori::where('id', $l->kategori_id)->select('nama')->first();
                Layanan::whereIn('id',$formFields)->update(['product_logo' => $logo_p->path]);
            }
            $log = new LogUser();
            $log->user = Auth::user()->username;
            $log->type = 'system';
            $log->text = 'IP : '.$client_ip.' Melakukan update Layanan '.$data->nama;
            $log->ip = $client_ip;
            $log->loc = $client_iploc;
            $log->ua = $browser;
            $log->save();
            
            return response()->json([
                'status' => true,
                'msg' => 'Berhasil update Multiple Produk Logo Layanan '.$data->nama
            ]);
        } else {
            return response()->json([
                'status' => false,
                'msg' => 'Empty logo product! '
            ]);
        }
    }
}
