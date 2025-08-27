<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\User;
use App\Http\Controllers\WhatsappController;

class HistoryPembayaranController extends Controller
{
    public function create()
    {
        return view('components.admin.history-pembayaran', ['data' => Pembayaran::orderBy('created_at', 'desc')->get()]);
    }

    public function patch($id, $status)
    {
        $wa = new WhatsappController;
        $deposit = Pembayaran::where('id', $id)->where('type', 'deposit')->first();

        $user = User::where('username', $deposit->username)->first();

        $deposit->update([
            'status' => $status
        ]);
        
        if($status == "Lunas") {
            $user->update([
                'balance' => $deposit->harga + $user->balance
            ]); 
            $requestPesan = $wa->send($user->phone, "Deposit anda telah berhasil dikonfirmasi oleh admin sejumlah Rp ".number_format($deposit->harga, 0, '.', ',').".");
        }

        return back()->with('success', 'Berhasil konfirmasi deposit');
    }
}
