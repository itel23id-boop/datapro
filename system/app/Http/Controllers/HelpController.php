<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berita;
use App\Http\Controllers\WhatsappController;

class HelpController extends Controller
{
    public function create()
    {
        
        return view('components.help',[
            'pay_method' => \App\Models\Method::all()
        ]);
    }
    public function pesan(Request $request)
    {
        $wa = new WhatsappController;
        $api = \DB::table('setting_webs')->where('id',1)->first();
        if(!$request->name || !$request->wa || !$request->message) {
            return back()->with('error', 'Mohon untuk mengisi semua form pertanyaan!');
        } else {
            $pesan = 
                "*Help Center!*\n".
                "❍➤ Informasi\n".
                "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                "❃ ➤ Nama Lengkap : *$request->name*\n".
                "❃ ➤ No. Whatsapp : *$request->wa*\n".
                "❃ ➤ Pesan : *$request->message*\n".
                "▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ ▱ ▰ \n".
                "Ini Adalah Pesan Pertanyaan Member/Client\n";
            $wa->send($api->nomor_admin, $pesan);
            return back()->with('success', 'Sukses Mengirim Pesan!');
        }
    }
}