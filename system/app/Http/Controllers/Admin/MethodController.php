<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Method;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\LogUser;
use App\Helpers\Formater;

class MethodController extends Controller
{
    public function create()
    {
        
        return view('components.admin.method', ['data' => method::orderBy('id', 'desc')->paginate(100)]);
    }
    
    public function get_code(Request $request)
    {
        $dataHtml = "";
        $dataHtml2 = "";
        if($request->provider == 'manual') {
            $dataHtml = "<label>Kode Pembayaran</label>
                        <input type='text' class='form-control @error('code') is-invalid @enderror' name='code'>
                        <small style='color:red;'>Untuk kode pembayaran ewallet tambahkan manual contoh : OVO_MANUAL / GOPAY_MANUAL, agar terdeteksi sistem proses manual / mutasi</small>
                        ";
            $dataHtml2 = "<label>No. Rekening</label>
                        <input type='text' class='form-control @error('keterangan') is-invalid @enderror' name='keterangan' placeholder='123456789 a/n nama rekeningmu'>
                        ";
        } else if($request->provider == 'tokopay') {
            $dataHtml = "<label>Kode Pembayaran</label>
                        <input type='text' class='form-control @error('code') is-invalid @enderror' name='code'>
                        <small style='color:red;'>Untuk kode pembayaran / channel pembayaran bisa cek <a href='https://docs.tokopay.id/persiapan-awal/metode-pembayaran' target='blank_'>Disini</a>
                        ";
        } else if($request->provider == 'tripay') {
            $dataHtml = "<label>Kode Pembayaran</label>
                        <input type='text' class='form-control @error('code') is-invalid @enderror' name='code'>
                        <small style='color:red;'>Untuk kode pembayaran / channel pembayaran bisa cek <a href='https://tripay.co.id/developer?tab=channels' target='blank_'>Disini</a>
                        ";
        } else if($request->provider == 'duitku') {
            $dataHtml = "<label>Kode Pembayaran</label>
                        <input type='text' class='form-control @error('code') is-invalid @enderror' name='code'>
                        <small style='color:red;'>Untuk kode pembayaran / channel pembayaran bisa cek <a href='https://docs.duitku.com/api/id/#metode-pembayaran' target='blank_'>Disini</a>
                        ";
        } else if($request->provider == 'ipaymu') {
            $dataHtml = "<label>Kode Pembayaran</label>
                        <input type='text' class='form-control @error('code') is-invalid @enderror' name='code'>
                        <small style='color:red;'>Untuk kode pembayaran / channel pembayaran bisa cek <a href='https://documenter.getpostman.com/view/7508947/SWLfanD1?version=latest#79e948f6-66b0-4d45-be63-6320f020c834' target='blank_'>Disini</a>
                        ";
        } else if($request->provider == 'linkqu') {
            $dataHtml = "<label>Kode Pembayaran</label>
                        <input type='text' class='form-control @error('code') is-invalid @enderror' name='code'>
                        <small style='color:red;'>Untuk kode pembayaran / channel pembayaran bisa cek <a href='https://documenter.getpostman.com/view/3085963/SzKN22op#dc5c7e46-05ae-4e20-bcc1-fa0037610968' target='blank_'>Disini</a>
                        ";
        } else if($request->provider == 'paydisini') {
            $dataHtml = "<label>Kode Pembayaran</label>
                        <input type='text' class='form-control @error('code') is-invalid @enderror' name='code'>
                        <small style='color:red;'>Untuk kode pembayaran / channel pembayaran bisa cek <a href='https://payment.paydisini.co.id/docs/' target='blank_'>Disini</a>
                        ";
        } else {
            $dataHtml = "<h1>Error.</h1>";
            $dataHtml2 = "<h1>Error.</h1>";
        }
        return response()->json([
            'status' => true,
            'data' => $dataHtml,
            'data2' => $dataHtml2
        ]);
    }

    public function store(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $request->validate([
            'name' => 'required',
            'images' => 'required|file|mimes:jpg,png,webp',
            'code' => 'required',
            'tipe' => 'required',
            'provider' => 'required',
            'percent' => 'required',
            'biaya_admin' => 'required',
            'status' => 'required'
        ]);
        
        $img = $request->file('images');
        $filename = Str::random('15') . '.' . $img->extension();
        $img->move('assets/thumbnail', $filename);
        
        $method = new method();
        $method->name = $request->name;
        $method->code = $request->code;
        $method->keterangan = $request->keterangan;
        $method->tipe = $request->tipe;
        $method->provider = $request->provider;
        $method->images = "/assets/thumbnail/".$filename;
        $method->percent = $request->percent;
        $method->biaya_admin = $request->biaya_admin;
        $method->status = $request->status;
        $method->save();
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan penambahan Payment '.$request->name;
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();

        return back()->with('success', 'Berhasil menambahkan payment '.$request->name);
    }

    public function delete($id)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        $data = method::where('id', $id)->select('images','name')->first();
        
        try{
            unlink(public_path($data->images));
            method::where('id', $id)->delete();
            
            $LogUser = new LogUser();
            $LogUser->user = Auth::user()->username;
            $LogUser->type = 'system';
            $LogUser->text = 'IP : '.$client_ip.' Melakukan hapus Payment '.$data->name;
            $LogUser->ip = $client_ip;
            $LogUser->loc = $client_iploc;
            $LogUser->ua = $browser;
            $LogUser->save();
            
            return back()->with('success', 'Berhasil hapus! '.$data->name);
        }catch(\Exception $e){
            method::where('id', $id)->delete();
            
            $LogUser = new LogUser();
            $LogUser->user = Auth::user()->username;
            $LogUser->type = 'system';
            $LogUser->text = 'IP : '.$client_ip.' Melakukan hapus Payment '.$data->name;
            $LogUser->ip = $client_ip;
            $LogUser->loc = $client_iploc;
            $LogUser->ua = $browser;
            $LogUser->save();
            
            return back()->with('success', 'Berhasil hapus! '.$data->name);
        }
    }

public function detail($id)
    {
        $data = method::where('id', $id)->first();
        
        $send = "
                <form action='".route("method.detail.update", [$id])."' method='POST' enctype='multipart/form-data'>
                    <input type='hidden' name='_token' value='".csrf_token()."'>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Nama</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='".$data->name. "' name='name'>
                        </div>
                    </div>    
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Kode</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='" . $data->code . "' name='code'>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Keterangan</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='" . $data->keterangan . "' name='keterangan'>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label'>Tipe</label>
                        <div class='col-lg-10'>
                            <select class='form-control' name='tipe'>
                                <option value='$data->tipe'>".ucwords($data->tipe)." (Selected)</option>
                                <option value='bank-transfer'>Bank Transfer</option>
                                <option value='qris'>QRIS</option>
                                <option value='e-walet'>E-Wallet</option>
                                <option value='virtual-account'>Virtual Account</option>
                                <option value='convenience-store'>Convenience Store</option>
                                <option value='pulsa'>Pulsa</option>
                            </select>
                        </div>
                    </div>    
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label'>Provider</label>
                        <div class='col-lg-10'>
                            <select class='form-control' name='provider'>
                                <option value='$data->provider'>".ucwords($data->provider)." (Selected)</option>
                                <option value='manual'>Manual</option>
                                <option value='tripay'>Tripay</option>
                                <option value='ipaymu'>Ipaymu</option>
                                <option value='duitku'>Duitku</option>
                                <option value='linkqu'>LinkQu</option>
                                <option value='tokopay'>TokoPay</option>
                                <option value='xendit'>Xendit</option>
                                <option value='paydisini'>Paydisini</option>
                            </select>
                        </div>
                    </div> 
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Thumbnail</label>
                        <div class='col-lg-10'>
                            <input type='file' class='form-control' value='" . $data->images . "' name='images'>
                        </div>
                    </div> 
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Status Biaya Admin</label>
                        <div class='col-lg-10'>
                            <select class='form-control' name='status'>
                                <option value='$data->status'>".ucwords($data->status)." (Selected)</option>
                                <option value='ON'>ON</option>
                                <option value='OFF'>OFF</option>
                            </select>
                        </div>
                    </div>   
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Biaya Admin</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='" . $data->biaya_admin . "' name='biaya_admin'>
                        </div>
                    </div>   
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Percent</label>
                        <div class='col-lg-10'>
                            <select class='form-control' name='percent'>
                                <option value='$data->percent'>".ucwords($data->percent)." (Selected)</option>
                                <option value='%'>%</option>
                                <option value='+'>+</option>
                            </select>
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
        
        if($request->file('images')){
            $file = $request->file('images');
            $folder = 'assets/thumbnail';
            $file->move($folder, $file->getClientOriginalName());      
            method::where('id', $id)->update([
                'images' => "/".$folder."/".$file->getClientOriginalName()
            ]);
        }
        
        $method = method::where('id', $id)->update([
            'name' => $request->name,
            'code' => $request->code,
            'keterangan' => $request->keterangan,
            'tipe' => $request->tipe,
            'provider' => $request->provider,
            'percent' => $request->percent,
            'biaya_admin' => $request->biaya_admin,
            'status' => $request->status,
        ]);
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan hapus Payment '.$request->name;
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
           
        return back()->with('success', 'Berhasil update payment '.$request->name);        
    }        
}
