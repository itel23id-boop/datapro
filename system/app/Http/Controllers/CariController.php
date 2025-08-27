<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\Berita;
use App\Models\Seting;

class CariController extends Controller
{
    
    public function create()
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        return view('layout.lacak-pesan.'.$api->change_theme, [
            'logoheader' => Berita::where('tipe', 'logoheader')->latest()->first(),
            'logofooter' => Berita::where('tipe', 'logofooter')->latest()->first(),
            'data' => \App\Models\Pembelian::orderBy('id','DESC')->limit(10)->get(),
            'pay_method' => \App\Models\Method::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $pembelian = Pembelian::where('order_id', $request->id)->first();
        if($pembelian){
            return redirect(route('pembelian', ['order' => $request->id]));
        }

        return back()->with('error', 'Nomor invoice tidak ditemukan!');
    }
    
    public function gethistory()
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $pembelian = Pembelian::orderBy('id', 'DESC')->limit(10)->get();
        $res = '';
        foreach($pembelian as $d){
            $label_pesanan = '';
            $ymdhis = explode(' ',$d->updated_at);
            $month = [
                1 => 'Januari','Februari','Maret','April','Mei','Juni',
                'Juli','Agustus','September','Oktober','November','Desember'
            ];
            $explode = explode('-', $ymdhis[0]);
            $formatted = $explode[2].' '.$month[(int)$explode[1]].' '.$explode[0];
            if($api->change_theme == "1") {
                if($d->status == "Pending"){
                    $label_pesanan = 'warning';
                }else if($d->status == "Processing"){
                    $label_pesanan = 'info';
                }else if($d->status == "Success"){
                    $label_pesanan = 'success';
                }else if($d->status == "Refund"){
                    $label_pesanan = 'refund';
                }else{
                    $label_pesanan = 'danger';
                }
                $res .= '
                    <tr>
    					<td nowrap>'.$formatted.' '.substr($ymdhis[1],0,5).'</td>
    					<td>FT'.substr($d->order_id,0,-30).'*******'.substr($d->order_id, -3).'</td>
    					<td>'.$d->quantity.'</td>
    					<td>'.$d->count.'</td>
    					<td>'.$d->remain.'</td>
    					<td nowrap>Rp. '.number_format($d->harga,0,'.','.').'</td>
    					<td><span class="badge bg-'.$label_pesanan.'">'.$d->status.'</span></td>
    				</tr>
                ';
            } else {
                if($d->status == "Pending"){
                    $label_pesanan = 'yellow-300';
                }else if($d->status == "Processing"){
                    $label_pesanan = 'sky-600';
                }else if($d->status == "Success"){
                    $label_pesanan = 'emerald-200';
                }else if($d->status == "Refund"){
                    $label_pesanan = 'rose-300';
                }else{
                    $label_pesanan = 'rose-300';
                }
                $res .= '
                    <tr>
                        <td class="table-cell px-3 py-3.5 text-left text-xs font-medium text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell">
                            <div class="whitespace-nowrap">'.$formatted.' '.substr($ymdhis[1],0,5).' WIB</div>
                        </td>
                        <td class="table-cell px-3 py-3.5 text-left text-xs font-medium text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell">
                            <div class="whitespace-nowrap">FT'. substr($d->order_id,0,-30). '*******' . substr($d->order_id, -3).'</div>
                        </td>
                        <td class="table-cell px-3 py-3.5 text-left text-xs font-medium text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell">
                            <div class="whitespace-nowrap">'.$d->quantity.'</div>
                        </td> 
                        <td class="table-cell px-3 py-3.5 text-left text-xs font-medium text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell">
                            <div class="whitespace-nowrap">'.$d->count.'</div>
                        </td> 
                        <td class="table-cell px-3 py-3.5 text-left text-xs font-medium text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell">
                            <div class="whitespace-nowrap">'.$d->remain.'</div>
                        </td> 
                        <td class="table-cell px-3 py-3.5 text-left text-xs font-medium text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell">
                            <div class="whitespace-nowrap"><span>Rp. '. number_format($d->harga, 0, ',', '.') .'</span></div>
                        </td>
                        <td class="table-cell px-3 py-3.5 text-left text-xs font-medium text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell">
                            <div class="whitespace-nowrap"><span class="inline-flex rounded-sm px-2 text-xs font-semibold leading-5 print:p-0 bg-'. $label_pesanan .' text-emerald-900">'. $d->status .'</span></div>
                        </td>
                    </tr>
                ';
            }
        }
        return $res;
    }
}
