<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promo;
use App\Models\Kategori;
use App\Models\Layanan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\LogUser;
use App\Helpers\Formater;
use Illuminate\Support\Carbon;

class PromoController extends Controller
{
    public function create()
    {
        $data['kategoris'] = Kategori::get();
        $data['data'] = Promo::orderBy('created_at', 'asc')->get();
        return view('components.admin.promo', $data);
    }

    public function store(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $request->validate([
            'thumbnail' => 'required|image|mimes:jpg,png,webp,jpeg',
            'nama' => 'required',
            'kategori_id' => 'required',
            'layanan_id' => 'required',
            'harga_promo' => 'required'
        ]);
        
        $img = $request->file('thumbnail');
        $filename = Str::random('15') . '.' . $img->extension();
        $img->move('assets/banner_flashsale', $filename);
        
        
        $kode = Kategori::where('id', $request->kategori_id)->select('kode')->first();
        $formatTanggal = Carbon::parse($request->expired_flash_sale)->format('Y-m-d H:i:s');
        
        Layanan::where('id', $request->layanan_id)->update([
            'harga_flash_sale' => $request->harga_promo,
            'is_flash_sale' => 'Yes',
            'expired_flash_sale' => $formatTanggal
        ]);
        
        $promo = new Promo();
        $promo->nama = $request->nama;
        $promo->url = $kode->kode;
        $promo->id_kategori = $request->kategori_id;
        $promo->id_layanan = $request->layanan_id;
        $promo->harga_promo = $request->harga_promo;
        $promo->thumbnail = "/assets/banner_flashsale/".$filename;
        $promo->expired_flash_sale = $formatTanggal;
        $promo->save();
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan penambahan Flashsale '.$request->nama;
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();

        return back()->with('success', 'Berhasil menambahkan Flashsale '.$request->nama);
    }

    public function delete($id)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        $data = Promo::where('id', $id)->select('thumbnail','id_layanan','nama')->first();
        try{
            
            unlink(public_path($data->thumbnail));
            Layanan::where('id', $data->id_layanan)->update([
                'harga_flash_sale' => null,
                'is_flash_sale' => 'No',
                'expired_flash_sale' => null
            ]);
            Promo::where('id', $id)->delete();
            
            $LogUser = new LogUser();
            $LogUser->user = Auth::user()->username;
            $LogUser->type = 'system';
            $LogUser->text = 'IP : '.$client_ip.' Melakukan hapus Flashsale '.$data->nama;
            $LogUser->ip = $client_ip;
            $LogUser->loc = $client_iploc;
            $LogUser->ua = $browser;
            $LogUser->save();
            
            return back()->with('success', 'Berhasil hapus Flashsale! '.$data->nama);
        }catch(\Exception $e){
            
            Promo::where('id', $id)->delete();
            Layanan::where('id', $data->id_layanan)->update([
                'harga_flash_sale' => null,
                'is_flash_sale' => 'No',
                'expired_flash_sale' => null
            ]);
            return back()->with('success', 'Berhasil hapus Flashsale! '.$data->nama);
        }
    }

public function detail($id)
    {
        $data = Promo::where('id', $id)->first();
        
        $send = "
                <form action='".route("flashsale.detail.update", [$id])."' method='POST' enctype='multipart/form-data'>
                    <input type='hidden' name='_token' value='".csrf_token()."'>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Nama Flashsale</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='".$data->nama. "' name='nama'>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Harga Flashsale</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='" . $data->harga_promo. "' name='harga_promo'>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Expired Flashsale</label>
                        <div class='col-lg-10'>
                            <input type='date' class='form-control' value='" . $data->expired_flash_sale. "' name='expired_flash_sale'>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Thumbnail</label>
                        <div class='col-lg-10'>
                            <input type='file' class='form-control' value='" . $data->thumbnail. "' name='thumbnail'>
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
        $datas = Promo::where('id', $id)->first();
        
        if($request->file('thumbnail')){
            $file = $request->file('thumbnail');
            $folder = 'assets/banner_flashsale';
            $file->move($folder, $file->getClientOriginalName());      
            Promo::where('id', $id)->update([
                'thumbnail' => "/".$folder."/".$file->getClientOriginalName()
            ]);
        }
        
        $formatTanggal = Carbon::parse($request->expired_flash_sale)->format('Y-m-d H:i:s');
        
        if(isset($request->expired_flash_sale)){
            Layanan::where('id', $datas->id_layanan)->update([
                'harga_flash_sale' => $request->harga_promo,
                'is_flash_sale' => 'Yes',
                'expired_flash_sale' => $request->expired_flash_sale
            ]);
            Promo::where('id', $id)->update([
                'nama' => $request->nama,
                'harga_promo' => $request->harga_promo,
                'expired_flash_sale' => $request->expired_flash_sale
            ]);
        } else {
            Promo::where('id', $id)->update([
                'nama' => $request->nama,
                'harga_promo' => $request->harga_promo
            ]);
            Layanan::where('id', $datas->id_layanan)->update([
                'harga_flash_sale' => $request->harga_promo,
                'is_flash_sale' => 'Yes'
            ]); 
        }
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan update Flashsale '.$request->nama;
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
        
           
        return back()->with('success', 'Berhasil update Flashsale '.$request->nama);        
    }        
}
