<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PertanyaanUmum;

class QuestionController extends Controller
{
    public function create()
    {
        return view('components.question',[
            'datas' => PertanyaanUmum::orderBy('id', 'ASC')->get(),
            'pay_method' => \App\Models\Method::all()
        ]);
    }
}