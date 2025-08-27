<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PertanyaanUmum;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\LogUser;
use App\Helpers\Formater;

class PertanyaanUmumController extends Controller
{
    public function create()
    {
        $data['data'] = PertanyaanUmum::orderBy('created_at', 'asc')->get();
        return view('components.admin.pertanyaan-umum', $data);
    }

    public function store(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $request->validate([
            'judul' => 'required',
            'pesan' => 'required',
        ]);
        
        $pu = new PertanyaanUmum();
        $pu->judul = $request->judul;
        $pu->pesan = $request->pesan;
        $pu->save();
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan penambahan Pertanyaan Umum '.$request->judul;
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();

        return back()->with('success', 'Berhasil menambahkan Pertanyaan Umum '.$request->judul);
    }

    public function delete($id)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        $data = PertanyaanUmum::where('id', $id)->select('thumbnail','id_layanan','nama')->first();
        
        PertanyaanUmum::where('id', $id)->delete();
            
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan hapus Pertanyaan Umum '.$data->judul;
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
            
        return back()->with('success', 'Berhasil hapus Pertanyaan Umum! '.$data->judul);
    }

public function detail($id)
    {
        $data = PertanyaanUmum::where('id', $id)->first();
        
        $send = "
                <form action='".route("pertanyaan-umum.detail.update", [$id])."' method='POST' enctype='multipart/form-data'>
                    <input type='hidden' name='_token' value='".csrf_token()."'>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Judul</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='".$data->judul. "' name='judul'>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Pesan</label>
                        <div class='col-lg-10'>
                            <textarea class='form-control' name='pesan' id='pertanyaan_umum'>" . $data->pesan. "</textarea>
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-danger' data-dismiss='modal'>Close</button>
                        <button type='submit' class='btn btn-primary'>Simpan</button>
                    </div>
                </form>
                <script>
                    CKEDITOR.replace( 'pertanyaan_umum' );
                </script>
        ";

        return $send;        
    }  
    
    public function patch(Request $request, $id)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        $datas = PertanyaanUmum::where('id', $id)->first();
        
        PertanyaanUmum::where('id', $id)->update([
            'judul' => $request->judul,
            'pesan' => $request->pesan
        ]);
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan update Pertanyaan Umum '.$datas->judul.' => '.$request->judul;
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
        
           
        return back()->with('success', 'Berhasil update Pertanyaan Umum '.$datas->judul.' => '.$request->judul);        
    }        
}
