<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;
use App\Models\LogUser;
use App\Helpers\Formater;

class OrderController extends Controller
{
    public function create()
    {
        $data = Pembelian::orderBy('pembelians.id', 'ASC')->join('pembayarans', 'pembelians.order_id', 'pembayarans.order_id')
                ->select('pembelians.*', 'pembayarans.status AS status_pembayaran', 'pembayarans.no_pembeli AS no_pembeli_pembayaran', 'pembayarans.email_pembeli AS email_pembayaran', 'metode')->get();

        return view('components.admin.transaction', ['data' => $data]);
    }

    public function update($order_id, $status)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        Pembelian::where('order_id', $order_id)->update([
            'status' => $status,
            'updated_at' => now()
        ]);
        
        $log = new LogUser();
        $log->user = Auth::user()->username;
        $log->type = 'system';
        $log->text = 'IP : '.$client_ip.' Melakukan update transaksi otomatis #'.$order_id;
        $log->ip = $client_ip;
        $log->loc = $client_iploc;
        $log->ua = $browser;
        $log->save();
        
        return back()->with('success', 'Berhasil memperbarui status ID #' . $order_id);        
    }
}
