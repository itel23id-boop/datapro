<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriTipe;
use App\Models\LogUser;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Formater;

class KategoriTipeController extends Controller
{
    public function create()
    {
        
        return view('components.admin.kategori-tipe', ['data' => KategoriTipe::orderBy('id', 'desc')->paginate(100)]);
    }

    public function store(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $request->validate([
            'text' => 'required'
        ]);
        
        $tab = new KategoriTipe();
        $tab->text = $request->text;
        $tab->code = str_replace(" ","-",strtolower($request->text));
        $tab->status = $request->status == 'ON' ? 'ON' : 'OFF';
        $tab->save();
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan penambahan Kategori '.$request->text;
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();

        return back()->with('success', 'Berhasil menambahkan Kategori '.$request->text);
    }

    public function delete($id)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        $data = KategoriTipe::where('id', $id)->select('text')->first();

        KategoriTipe::where('id', $id)->delete();
            
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan hapus Kategori '.$data->text;
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
            
        return back()->with('success', 'Berhasil hapus! '.$data->text);
    }

    public function detail($id) {
        $data = KategoriTipe::where('id', $id)->first();
        
        $send = "
                <form action='".route("kategori-tipe.detail.update", [$id])."' method='POST' enctype='multipart/form-data'>
                    <input type='hidden' name='_token' value='".csrf_token()."'>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Nama</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='".$data->text. "' name='text'>
                        </div>
                    </div>    
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label'>Status</label>
                        <div class='col-lg-10'>
                            <select class='form-control' name='status'>
                                <option value='" . $data->status . "'>" . $data->status . " (Selected)</option>
                                <option value='ON'>ON</option>
                                <option value='OFF'>OFF</option>
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
        
        KategoriTipe::where('id', $id)->update([
            'text' => $request->text,
            'status' => $request->status
        ]);
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan update kategori '.$request->text;
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
           
        return back()->with('success', 'Berhasil update kategori '.$request->text);        
    }        
}
