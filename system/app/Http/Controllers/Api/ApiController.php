<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    public function produk(Request $request)
    {
        return ['Status' => true, 'Data' => 'Testing Produk API', 'Message' => 'Dalam Pengembangan'];
    }
    public function orders(Request $request)
    {
        return ['Status' => true, 'Data' => 'Testing Order API', 'Message' => 'Dalam Pengembangan'];
    }
    public function status(Request $request)
    {
        return ['Status' => true, 'Data' => 'Testing Status API', 'Message' => 'Dalam Pengembangan'];
    }
    public function cekid(Request $request)
    {
        return ['Status' => true, 'Data' => 'Testing CEKID API', 'Message' => 'Dalam Pengembangan'];
    }
}
