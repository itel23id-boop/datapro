<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LogoProduct;
use App\Models\LogUser;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Formater;
use Str;

class LogoProductController extends Controller
{
    public function create()
    {
        
        return view('components.admin.logoproduct', ['data' => LogoProduct::orderBy('id', 'desc')->paginate(100), 'kategoris' => Kategori::get()]);
    }

    public function store(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $request->validate([
            'category_id' => 'required',
            'name' => 'required'
        ]);
        
        $img = $request->file('product_logo');
        $filename = Str::random('15') . '.' . $img->extension();
        $img->move('assets/product_logo', $filename);
        
        $LogoProduct = new LogoProduct();
        $LogoProduct->category_id = $request->category_id;
        $LogoProduct->name = $request->name;
        $LogoProduct->path = "/assets/product_logo/".$filename;
        $LogoProduct->save();
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan penambahan Logo Produk '.$request->category;
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();

        return back()->with('success', 'Berhasil menambahkan Logo Produk '.$request->category);
    }

    public function delete($id)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        $data = LogoProduct::where('id', $id)->select('category')->first();

        LogoProduct::where('id', $id)->delete();
            
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan hapus Logo Produk '.$data->category;
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
            
        return back()->with('success', 'Berhasil hapus Logo Produk! '.$data->category);
    }

    public function detail($id) {
        $data = LogoProduct::where('id', $id)->first();
        $cat = Kategori::where('id', $data->category_id)->first();
        $send = "
                <form action='".route("logo-product.detail.update", [$id])."' method='POST' enctype='multipart/form-data'>
                    <input type='hidden' name='_token' value='".csrf_token()."'>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Kategori</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='".$cat->nama. "' readonly>
                        </div>
                    </div>    
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Nama</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='" . $data->name . "' name='name'>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Produk Logo</label>
                        <div class='col-lg-10'>
                            <input type='file' class='form-control' name='product_logo' value='".$data->path. "'>
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-danger' data-dismiss='modal'>Close</button>
                        <button type='submit' class='btn btn-primary'>Simpan</button>
                    </div>
                </form>
        ";

        return $send;        
    }  
    
    public function patch(Request $request, $id)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        if($request->file('product_logo')){
            $img = $request->file('product_logo');
            $filename = Str::random('15') . '.' . $img->extension();
            $img->move('assets/product_logo', $filename);
            LogoProduct::where('id', $id)->update([
                'path' => "/assets/product_logo/".$filename
            ]);
        }
        
        LogoProduct::where('id', $id)->update([
            'name' => $request->name
        ]);
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan update Logo Produk '.$request->category;
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
           
        return back()->with('success', 'Berhasil update Logo Produk '.$request->category);        
    }        
}
