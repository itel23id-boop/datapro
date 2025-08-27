<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\LogUser;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Formater;
use App\Models\KategoriTipe;
use Illuminate\Support\Str;

class KategoriController extends Controller
{
    public function create()
    {
        return view('components.admin.kategori.index', ['data' => Kategori::orderBy('id', 'ASC')->get(),'dataTab' => KategoriTipe::orderBy('id', 'ASC')->get()]);
    }
    
    public function add()
    {
        return view('components.admin.kategori.add', ['data' => Kategori::orderBy('id', 'ASC')->get(),'dataTab' => KategoriTipe::orderBy('id', 'ASC')->get()]);
    }
    
    public function detail($id)
    {
        $data = Kategori::where('id', $id)->first();
        return view('components.admin.kategori.detail', ['kategori_id' => $data->id,'data' => $data,'dataTab' => KategoriTipe::orderBy('id', 'ASC')->get()]);
    }
    
    public function validasi(Request $request)
    {
        if($request->validasi == 'ya') {
            $dataHtml = "<div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Kode Validasi</label>
                        <div class='col-lg-10'>
                        <input type='text' class='form-control' name='kode_validasi'>
                        <small style='color:red;'>Kode Validasi bisa cek <a href='https://api.mystic-pedia.net/' target='blank_'>disini</a></small>
                        </div>
                        </div>";
        } else {
            $dataHtml = "";
        }
        return response()->json([
            'status' => true,
            'data' => $dataHtml
        ]);
    }

    public function store(Request $request)
    {
        $formatter = new Formater;
        $request->validate([
            'thumbnail' => 'required|image|mimes:jpg,png,webp',
            'banner' => 'required|image|mimes:jpg,png,webp',
            'nama' => 'required',
            'sub_nama' => 'required',
            'brand' => 'required',
            'kode' => 'required|unique:kategoris,kode',
            'serverOption' => 'required',
            'tipe' => 'required',
            'provider' => 'required'
        ]);
        
        $file = $request->file('thumbnail');
        $filename = Str::random('15') . '.' . $file->extension();
        $file->move('assets/thumbnail', $filename);
        
        $file2 = $request->file('banner');
        $filename2 = Str::random('15') . '.' . $file2->extension();
        $file2->move('assets/banner_game', $filename2);
        
        if($request->file('petunjuk') == null) {
            $folder_petunjuk = null;
        } else {
            $petunjuk = $request->file('petunjuk');
            $folderPetunjuk = 'assets/petunjuk';
            $petunjuk->move($folderPetunjuk, $petunjuk->getClientOriginalName());  
            $folder_petunjuk = "/".$folderPetunjuk."/".$petunjuk->getClientOriginalName();
        }
        
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $kategori = new Kategori();
        $kategori->nama = $request->nama;
        $kategori->sub_nama = $request->sub_nama;
        $kategori->brand = $request->brand;
        $kategori->text_1 = $request->text_1;
        $kategori->text_2 = $request->text_2;
        $kategori->text_3 = $request->text_3;
        $kategori->text_4 = $request->text_4;
        $kategori->text_5 = $request->text_5;
        $kategori->kode = str_replace(" ","-",strtolower($request->kode));
        $kategori->server_id = $request->serverOption == 'ya' ? 1 : 0;
        $kategori->popular = $request->popular;
        $kategori->tipe = $request->tipe;
        $kategori->thumbnail = "/assets/thumbnail/".$filename;
        $kategori->banner = "/assets/banner_game/".$filename2;
        $kategori->petunjuk = $folder_petunjuk;
        $kategori->deskripsi_game = $request->deskripsi_game;
        $kategori->deskripsi_field = str_replace("\r\n","<br>",$request->deskripsi_field);
        $kategori->deskripsi_popup = isset($request->deskripsi_popup) ? $request->deskripsi_popup : null;
        $kategori->status_validasi = $request->validasi == 'ya' ? 'Yes' : 'No';
        $kategori->kode_validasi = $request->kode_validasi;
        $kategori->provider = $request->provider;
        $kategori->save();
        
        $log = new LogUser();
        $log->user = Auth::user()->username;
        $log->type = 'system';
        $log->text = 'IP : '.$client_ip.' Melakukan penambahan Kategori '.$request->nama;
        $log->ip = $client_ip;
        $log->loc = $client_iploc;
        $log->ua = $browser;
        $log->save();

        return back()->with('success', 'Berhasil menambahkan Kategori '.$request->nama);
    }

    public function delete($id)
    {
        $formatter = new Formater;
        $data = Kategori::where('id', $id)->select('thumbnail','nama')->first();
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        try{
            unlink(public_path($data->thumbnail));
            Kategori::where('id', $id)->delete();
            
            $log = new LogUser();
            $log->user = Auth::user()->username;
            $log->type = 'system';
            $log->text = 'IP : '.$client_ip.' Melakukan hapus Kategori '.$data->nama;
            $log->ip = $client_ip;
            $log->loc = $client_iploc;
            $log->ua = $browser;
            $log->save();
            
            return back()->with('success', 'Berhasil hapus!');
        }catch(\Exception $e){
            Kategori::where('id', $id)->delete();
            
            $log = new LogUser();
            $log->user = Auth::user()->username;
            $log->type = 'system';
            $log->text = 'IP : '.$client_ip.' Melakukan hapus Kategori '.$data->nama;
            $log->ip = $client_ip;
            $log->loc = $client_iploc;
            $log->ua = $browser;
            $log->save();
            
            return back()->with('success', 'Berhasil hapus!');
        }
    }

    public function update($id, $status)
    {
        $formatter = new Formater;
        $datas = Kategori::where('id', $id)->select('nama')->first();
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $data = Kategori::where('id', $id)->update([
            'status' => $status
        ]);
        
        $log = new LogUser();
        $log->user = Auth::user()->username;
        $log->type = 'system';
        $log->text = 'IP : '.$client_ip.' Melakukan update status Kategori '.$datas->nama;
        $log->ip = $client_ip;
        $log->loc = $client_iploc;
        $log->ua = $browser;
        $log->save();

        return back()->with('success', 'Berhasil update Kategori! '.$datas->nama);
    }
    
    public function update_tab($id, $code)
    {
        $formatter = new Formater;
        $datas = Kategori::where('id', $id)->select('nama')->first();
        $kat_tipe = KategoriTipe::where('code', $code)->first();
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $data = Kategori::where('id', $id)->update([
            'tipe' => $code
        ]);
        
        $log = new LogUser();
        $log->user = Auth::user()->username;
        $log->type = 'system';
        $log->text = 'IP : '.$client_ip.' Melakukan update Tipe Kategori '.$datas->nama.' '.$kat_tipe->text;
        $log->ip = $client_ip;
        $log->loc = $client_iploc;
        $log->ua = $browser;
        $log->save();

        return back()->with('success', 'Berhasil update Tipe Kategori! '.$datas->nama.' '.$kat_tipe->text);
    }

public function edit($id)
    {
        $data = Kategori::where('id', $id)->first();
        $value = $data->server_id == 1 ? 'ya' : 'tidak';
        $text = $data->server_id == 1 ? 'Ya' : 'Tidak';
        $Tab = KategoriTipe::where('code', $data->tipe)->first();
        $send = "
                <form action='".route("kategori.edit.update", [$id])."' method='POST' enctype='multipart/form-data'>
                    <input type='hidden' name='_token' value='".csrf_token()."'>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Tipe</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='".$Tab->text. "' readonly>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Nama</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='".$data->nama. "' name='kategori'>
                        </div>
                    </div> 
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label'>Popular</label>
                        <div class='col-lg-10'>
                            <select class='form-control' name='popular'>
                                <option value='$data->popular'>".ucwords($data->popular)." (Selected)</option>
                                <option value='No'>No</option>
                                <option value='Yes'>Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Url</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='" . $data->kode . "' name='kode'>
                        </div>
                    </div> 
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Sub Nama</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='" . $data->sub_nama . "' name='sub_nama'>
                        </div>
                    </div> 
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Text 1</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='" . $data->text_1 . "' name='text_1'>
                            <small>Contoh : Instant / 1-15 Menit</small>
                        </div>
                    </div> 
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Text 2</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='" . $data->text_2 . "' name='text_2'>
                            <small>Contoh : Indonesia / Global</small>
                        </div>
                    </div> 
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Text 3</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='" . $data->text_3 . "' name='text_3'>
                            <small>Contoh : Pembayaran yang aman</small>
                        </div>
                    </div> 
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Text 4</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='" . $data->text_4 . "' name='text_4'>
                            <small>Contoh : Pengiriman instant</small>
                        </div>
                    </div> 
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Text 5</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='" . $data->text_5 . "' name='text_5'>
                            <small>Contoh : Layanan Pelanggan</small>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Brand</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='" . $data->brand . "' name='brand'>
                        </div>
                    </div> 
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Deskripsi Game</label>
                        <div class='col-lg-10'>
                            <textarea class='form-control' name='deskripsi_game' id='deskripsi_game'>".$data->deskripsi_game."</textarea>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Deskripsi Field User ID & Zone ID</label>
                        <div class='col-lg-10'>
                            <textarea class='form-control' name='deskripsi_field'>".$data->deskripsi_field."</textarea>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Deskripsi PopUp Game</label>
                        <div class='col-lg-10'>
                            <textarea class='form-control' name='deskripsi_popup' id='deskripsi_popup'>".$data->deskripsi_popup."</textarea>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Thumbnail</label>
                        <div class='col-lg-10'>
                            <input type='file' class='form-control' value='" . $data->thumbnail . "' name='thumbnail'>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Banner</label>
                        <div class='col-lg-10'>
                            <input type='file' class='form-control' value='" . $data->banner . "' name='banner'>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Petunjuk</label>
                        <div class='col-lg-10'>
                            <input type='file' class='form-control' value='" . $data->petunjuk . "' name='petunjuk'>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label'>Server ID</label>
                        <div class='col-lg-10'>
                            <select class='form-control' id='customRadio1' name='serverOption'>
                                <option value='$value'>$text (Selected)</option>
                                <option value='tidak'>Tidak</option>
                                <option value='ya'>Ya</option>
                            </select>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Status</label>
                        <div class='col-lg-10'>
                            <select class='form-control' name='status'>
                                <option value='$data->status'>".ucwords($data->status)." (Selected)</option>
                                <option value='active'>Active</option>
                                <option value='unactive'>Unactive</option>
                            </select>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Provider</label>
                        <div class='col-lg-10'>
                            <select class='form-control' name='provider'>
                                <option value='$data->provider'>".ucwords($data->provider)." (Selected)</option>
                                <option value='manual'>Manual</option>
                                <option value='partaisocmed'>PartaiSocmed</option>
                                <option value='irvankedesmm'>IrvanKedeSMM</option>
                                <option value='vipmember'>Vipmember</option>
                                <option value='istanamarket'>Istanamarket</option>
                                <option value='fanstore'>Fanstore</option>
                                <option value='rasxmedia'>Rasxmedia</option>
                            </select>
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-danger' data-dismiss='modal'>Close</button>
                        <button type='submit' class='btn btn-primary'>Simpan</button>
                    </div>
                </form>
                <script>
                    CKEDITOR.replace( 'deskripsi_game' );
                    CKEDITOR.replace( 'deskripsi_popup' );
                </script>
        ";

        return $send;        
    }  
    
    public function patch(Request $request, $id)
    {
        $formatter = new Formater;
        $datas = Kategori::where('id', $id)->select('nama')->first();
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        if($request->file('thumbnail')){
            $file = $request->file('thumbnail');
            $folder = 'assets/thumbnail';
            $file->move($folder, $file->getClientOriginalName());      
            Kategori::where('id', $id)->update([
                'thumbnail' => "/".$folder."/".$file->getClientOriginalName()
            ]);
        }
        
        if($request->file('banner')){
            $file2 = $request->file('banner');
            $folder2 = 'assets/banner_game';
            $file2->move($folder2, $file2->getClientOriginalName());      
            Kategori::where('id', $id)->update([
                'banner' => "/".$folder2."/".$file2->getClientOriginalName()
            ]);
        }
        
        if($request->file('petunjuk')){
            $file = $request->file('petunjuk');
            $folder = 'assets/petunjuk';
            $file->move($folder, $file->getClientOriginalName());      
            Kategori::where('id', $id)->update([
                'petunjuk' => "/".$folder."/".$file->getClientOriginalName()
            ]);
        }
        Kategori::where('id', $id)->update([
            'nama' => $request->kategori,
            'sub_nama' => $request->sub_nama,
            'kode' => str_replace(" ","-",strtolower($request->kode)),
            'brand' => $request->brand,
            'text_1' => $request->text_1,
            'text_2' => $request->text_2,
            'text_3' => $request->text_3,
            'text_4' => $request->text_4,
            'text_5' => $request->text_5,
            'popular' => $request->popular,
            'status' => $request->status,
            'server_id' => $request->serverOption == 'ya' ? 1 : 0,
            'deskripsi_game' => $request->deskripsi_game,
            'provider' => $request->provider,
            'deskripsi_field' => str_replace("\r\n","<br>",$request->deskripsi_field),
            'deskripsi_popup' => isset($request->deskripsi_popup) ? $request->deskripsi_popup : null
        ]);
        
        $log = new LogUser();
        $log->user = Auth::user()->username;
        $log->type = 'system';
        $log->text = 'IP : '.$client_ip.' Melakukan update Kategori '.$datas->nama;
        $log->ip = $client_ip;
        $log->loc = $client_iploc;
        $log->ua = $browser;
        $log->save();
           
        return back()->with('success', 'Berhasil update Kategori');        
    }        
}
