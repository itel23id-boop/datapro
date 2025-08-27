<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Berita;
use App\Models\Pembelian;
use App\Models\User;
class TopRankingController extends Controller
{
   public function create() {
        $totalOrders = Pembelian::select(DB::raw('username, COUNT(*) as total_order, status'))
            ->groupBy('username','status')
            ->orderByDesc('total_order','status')
            ->get();
            
        $toplayanans = Pembelian::where('status', 'Success')->select(DB::raw('layanan, COUNT(*) as top_layanan, id_layanan'))->groupBy('layanan','id_layanan')->orderByDesc('top_layanan','id_layanan')->limit(10)->get();
        
        return view('components.admin.top-ranking', [
            'totalOrders' => $totalOrders,
            'toplayanans' => $toplayanans,
            'banner' => Berita::where('tipe', 'banner')->get(),
            'logoheader' => Berita::where('tipe', 'logoheader')->latest()->first(),
            'logofooter' => Berita::where('tipe', 'logofooter')->latest()->first(),
            'popup' => Berita::where('tipe', 'popup')->latest()->first(),
            'pay_method' => \App\Models\Method::all()
        ]);
    }
    
    
}