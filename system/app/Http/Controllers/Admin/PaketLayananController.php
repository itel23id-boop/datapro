<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\PaketLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\LogUser;
use App\Helpers\Formater;

class PaketLayananController extends Controller
{
    

    public function store(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $validator = Validator::make($request->all(), [
            'paket_id' => 'required',
            'layanan_id' => 'required|unique:paket_layanans',
            'kategori_id' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }
        
        try {
            $formFields = $request->validate([
                'paket_id' => 'required',
                'layanan_id' => 'required|unique:paket_layanans',
                'kategori_id' => 'required'
            ]);
            
            DB::beginTransaction();
            
            $services = $formFields['layanan_id'];
            foreach ($services as $service) {
               $formFields['layanan_id'] = $service;
               PaketLayanan::create($formFields);
            }
            
            DB::commit();
            
            $log = new LogUser();
            $log->user = Auth::user()->username;
            $log->type = 'system';
            $log->text = 'IP : '.$client_ip.' Melakukan penambahan Paket Layanan';
            $log->ip = $client_ip;
            $log->loc = $client_iploc;
            $log->ua = $browser;
            $log->save();
        
            return redirect()->back()->with('success', 'Paket Layanan berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Terjadi kesalahan saat menyimpan data paket layanan: ' . $e->getMessage());

            return redirect()->back()->with('error', $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data paket layanan');
        }
    }

    public function destroy($layanan_id)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        try {
            DB::beginTransaction();
            // Hapus data paket layanan
            $paketLayanan = PaketLayanan::firstWhere('layanan_id',$layanan_id);
            $paketLayanan->delete();

            DB::commit();
            
            $log = new LogUser();
            $log->user = Auth::user()->username;
            $log->type = 'system';
            $log->text = 'IP : '.$client_ip.' Melakukan hapus Paket Layanan ';
            $log->ip = $client_ip;
            $log->loc = $client_iploc;
            $log->ua = $browser;
            $log->save();

            return redirect()->back()->with('success', 'Paket Layanan berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Terjadi kesalahan saat menghapus data paket layanan: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data paket layanan');
        }
    }

    public function get_layanan(Request $request)
    {
        try {
            $layanan = Layanan::join('kategoris', 'layanans.kategori_id', 'kategoris.id')
                ->orderBy('harga', 'asc')
                ->select('layanans.*', 'kategoris.nama AS nama_kategori')
                ->where('layanans.kategori_id', $request->kategori_id)
                ->where('layanans.status', 'available');

            if ($layanan->count() == 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Layanan Tidak Ditemukan !',
                    'data' => null
                ], 404);
            }
            $layanan = $layanan->get();

            return response()->json([
                'status' => true,
                'message' => 'berhasil',
                'data' => $layanan
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => true,
                'message' => 'Terjadi Masalah !',
                'data' => null
            ], 500);
        }
    }
    public function get_harga(Request $request)
    {
        try {
            $layanan = Layanan::orderBy('created_at', 'desc')
                ->select('harga', 'harga_member','harga_reseller','harga_vip')
                ->where('id', $request->layanan_id)
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Berhasil',
                'data' => $layanan
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi Masalah !',
                'data' => null
            ], 500);
        }
    }
}
