<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Formater;

class LaporanController extends Controller
{
    public function create(Request $request)
    {
        $formatter = new Formater;
        $cnt = Pembelian::where('status','Success')->get();
        $tot = Pembelian::where('status','Success')->sum('harga');
        $prf = Pembelian::where('status','Success')->sum('profit');
        $date = date('Y-m-d');
        if(isset($request->start_date) && isset($request->end_date)) {
            $filter_date1 = $request->start_date;
            $filter_date2 = $request->end_date;
            
        	if($formatter->validate_date($filter_date1) == false || $formatter->validate_date($filter_date2) == false) {
        		return back()->with('error', 'Input does not match.');
        	} else if(strtotime($filter_date2) < strtotime($filter_date1)) {
                return back()->with('error', 'The period starts beyond the end period.');
        	} else {
        	    $cnt = Pembelian::where('status','Success')->whereBetween('created_at', [$filter_date1, $filter_date2])->get();
        	    $tot = Pembelian::where('status','Success')->whereBetween('created_at', [$filter_date1, $filter_date2])->sum('harga');
        	    $prf = Pembelian::where('status','Success')->whereBetween('created_at', [$filter_date1, $filter_date2])->sum('profit');
        	}
        } else {
            $filter_date1 = date('Y-m-d', strtotime("-6 days", strtotime($date)));
            $filter_date2 = $date;
        }
        
        return view('components.admin.laporan', [
            'cnt' => $cnt,
            'tot' => $tot,
            'prf' => $prf,
            'filter_date1' => $filter_date1,
            'filter_date2' => $filter_date2,
            'formater' => $formatter
        ]);
    }
}
