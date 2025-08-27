<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paket;
use App\Models\Layanan;
use App\Models\Kategori;
use App\Models\PaketLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\LogUser;
use App\Helpers\Formater;

class PaketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['kategoris'] = Kategori::get();
        $data['pakets'] = Paket::get();
        return view('components.admin.paket.index', $data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * meltih
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        try {
            $request->validate([
                'nama' => 'required',
                'paket_logo' => 'required|image|mimes:jpg,png,webp,jpeg'
            ]);
            
            if($request->file('paket_logo') == null) {
            $folder_petunjuk = null;
            } else {
                $petunjuk = $request->file('paket_logo');
                $folderPetunjuk = 'assets/paket_logo';
                $petunjuk->move($folderPetunjuk, $petunjuk->getClientOriginalName());  
                $folder_petunjuk = "/".$folderPetunjuk."/".$petunjuk->getClientOriginalName();
            }
            
            $paket = new Paket();
            $paket->nama = $request->nama;
            $paket->image = $folder_petunjuk;
            $paket->save();
            
            $log = new LogUser();
            $log->user = Auth::user()->username;
            $log->type = 'system';
            $log->text = 'IP : '.$client_ip.' Melakukan penambahan Paket '.$request->nama;
            $log->ip = $client_ip;
            $log->loc = $client_iploc;
            $log->ua = $browser;
            $log->save();

            return redirect()->back()->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Paket  $paket
     * @return \Illuminate\Http\Response
     */
    public function show(Paket $paket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Paket  $paket
     * @return \Illuminate\Http\Response
     */
    public function edit(Paket $paket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Paket  $paket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Paket $paket)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        try {
            if($request->file('paket_logo')){
                $file = $request->file('paket_logo');
                $folder = 'assets/paket_logo';
                $file->move($folder, $file->getClientOriginalName());      
                $paket->update([
                    'image' => "/".$folder."/".$file->getClientOriginalName()
                ]);
            }
            $unique = '';
            if ($request->nama != $paket->nama) {
                $unique = '|unique:pakets';
            }
            $validator = Validator::make($request->all(), [
                'nama' => 'required|string' . $unique,
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', 'input tidak valid');
            }

            $data = $validator->validated();

            DB::beginTransaction();
            $paket->update($data);

            DB::commit();
            
            $log = new LogUser();
            $log->user = Auth::user()->username;
            $log->type = 'system';
            $log->text = 'IP : '.$client_ip.' Melakukan update Paket '.$request->nama;
            $log->ip = $client_ip;
            $log->loc = $client_iploc;
            $log->ua = $browser;
            $log->save();

            return redirect()->back()->with('success', 'Data berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Terjadi kesalahan saat mengupdate data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupdate data');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Paket  $paket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Paket $paket)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        try {
            DB::beginTransaction();
            if(PaketLayanan::firstWhere('paket_id',$paket->id)){
            PaketLayanan::firstWhere('paket_id',$paket->id)->delete();
            }
            $paket->delete();
            DB::commit();
            
            $log = new LogUser();
            $log->user = Auth::user()->username;
            $log->type = 'system';
            $log->text = 'IP : '.$client_ip.' Melakukan hapus data Paket';
            $log->ip = $client_ip;
            $log->loc = $client_iploc;
            $log->ua = $browser;
            $log->save();
    
            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data');
        }
    }
}
