<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\WhatsappController;

class AdminWhatsappController extends Controller
{
    public function create()
    {
    $wa = new WhatsappController;
    $data = $wa->qr();
    if($data['status'] != false) {
        $qr = 'CONNECTED';
        $status = false;
    } else {
        $status = true;
        $qr = $data;
    }
        return view('components.admin.whatsapp', ['qr' => $qr, 'status' => $status]);
    }    
}
