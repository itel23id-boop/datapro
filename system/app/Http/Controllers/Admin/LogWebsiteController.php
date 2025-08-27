<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LogUser;

class LogWebsiteController extends Controller
{
    public function create()
    {
        return view('components.admin.log-website', ['data' => LogUser::orderBy('created_at', 'desc')->get()]);
    }
}
