<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Kategori;
use App\Models\LogUser;
use App\Models\KategoriTipe;
use App\Models\KategoriInput;
use App\Helpers\Formater;

class KategoriInputController extends Controller
{
    public function create($id)
    {
        $send = "
                <form action='".route("input.store")."' method='POST'>
                    <input type='hidden' name='_token' value='".csrf_token()."'>
                    <input type='hidden' name='kategori_id' value='".$id."'>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Nama Input</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' name='name'>
                        </div>
                    </div> 
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label'>Sistem Target</label>
                        <div class='col-lg-10'>
                            <select class='form-control' name='server_id'>
                                <option value='user_id'>Default (Target + Jumlah/Quantity)</option>
                                <option value='package'>Package (Target Only)</option>
                                <option value='seo'>SEO (Keywords + Jumlah/Quantity)</option>
                                <option value='custom_comments'>Custom Comments (Target + Komen/Comments)</option>
                                <option value='mentions_custom_list'>Mentions Custom List (Target + Usernames)</option>
                                <option value='mentions_hashtag'>Mentions Hashtag (Target + Jumlah/Quantity + Hashtag)</option>
                                <option value='mentions_user_followers'>Mentions User Followers (Target + Jumlah/Quantity + Username)</option>
                                <option value='mentions_media_likers'>Mentions Media Likers (Target + Jumlah/Quantity + Media)</option>
                                <option value='poll'>Poll (Target + Jumlah/Quantity + Answer Number)</option>
                                <option value='comment_replies'>Comment Replies (Target + Username + Comments)</option>
                                <option value='subscription'>Subscriptions (Username + Minimal + Maximal + Post + Old Post + Delay + Expired)</option>
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
    public function create_edit($id)
    {
        $data = KategoriInput::where('id', $id)->first();
        $send = "
                <form action='".route("input.edit", [$id])."' method='POST'>
                    <input type='hidden' name='_token' value='".csrf_token()."'>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Nama Input</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='".$data->name."' name='name'>
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
    public function create_pilihan($id)
    {
        $data = KategoriInput::where('id', $id)->first();
        $send = "
                <form action='".route("input.update", [$id])."' method='POST'>
                    <input type='hidden' name='_token' value='".csrf_token()."'>
                    <div class='mb-3 row'>
                        <label class='col-lg-5 col-form-label' for='example-fileinput'>Pilihan (Masukkan Dengan Format cth: server,mythic,master gunakan koma</label>
                        <div class='col-lg-5'>
                            <textarea type='text' name='dropdown' class='form-control'></textarea>
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
    
    public function store(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ], [
            'name.required' => 'Input harus diisi.',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return back()->with('success', $errors[0]);  
        }

        $kI = new KategoriInput();
        $kI->name = $request->name;
        $kI->value = $request->server_id;
        $kI->dropdown = null;
        $kI->kategori_id = $request->kategori_id;
        $kI->server_id = $request->server_id;
        $kI->save();
        
        $log = new LogUser();
        $log->user = Auth::user()->username;
        $log->type = 'system';
        $log->text = 'IP : '.$client_ip.' Melakukan tambah Input Kategori '.$request->nama;
        $log->ip = $client_ip;
        $log->loc = $client_iploc;
        $log->ua = $browser;
        $log->save();

        return back()->with('success', 'Input berhasil ditambahkan '.$request->name);  
    }
    public function edit(Request $request,$id)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $datas = KategoriInput::where('id', $id)->first();
        $browser = $formatter->devices();
        
        $data = KategoriInput::find($id);

        if ($data) {
            $data->name = $request->name;
            $data->save();
            
            $log = new LogUser();
            $log->user = Auth::user()->username;
            $log->type = 'system';
            $log->text = 'IP : '.$client_ip.' Melakukan edit input Kategori '.$request->name;
            $log->ip = $client_ip;
            $log->loc = $client_iploc;
            $log->ua = $browser;
            $log->save();
            
            return back()->with('success', 'Berhasil edit Input '.$request->name);  
        } else {
            return back()->with('error', 'Gagal edit Input.'); 
        }
    }
    public function update(Request $request, $id)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $kI = KategoriInput::find($id);

        if ($kI) {
            if ($request->dropdown) {
                $optionsArray = explode(',', $request->dropdown);
                $optionsArray = array_map('trim', $optionsArray);
                $optionsArray = array_filter($optionsArray);
                $jsonOptions = json_encode($optionsArray);
                $kI->dropdown = $jsonOptions;
            }
            $kI->save();
            
            $log = new LogUser();
            $log->user = Auth::user()->username;
            $log->type = 'system';
            $log->text = 'IP : '.$client_ip.' Melakukan tambah input pilihan Kategori.';
            $log->ip = $client_ip;
            $log->loc = $client_iploc;
            $log->ua = $browser;
            $log->save();

            return back()->with('success', 'Input pilihan berhasil diubah.');
        } else {
            return back()->with('error', 'Input pilihan Gagal Diubah.'); 
        }
    }
    public function destroy($id)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $data = KategoriInput::where('id', $id)->first();
        $browser = $formatter->devices();
        
        $kI = KategoriInput::find($id);

        if ($kI) {
            $kI->delete();
            
            $log = new LogUser();
            $log->user = Auth::user()->username;
            $log->type = 'system';
            $log->text = 'IP : '.$client_ip.' Melakukan hapus input Kategori '.$data->name;
            $log->ip = $client_ip;
            $log->loc = $client_iploc;
            $log->ua = $browser;
            $log->save();

            return back()->with('success', 'Input berhasil dihapus.');
        } else {
            return back()->with('error', 'Input tidak ditemukan.');
        }
    }
}
