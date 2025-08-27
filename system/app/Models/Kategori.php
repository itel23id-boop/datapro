<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $with = ['kategori_input'];

    public function layanans(){
        return $this->hasMany(Layanan::class,'kategori_id');
    }
    public function kategori_input()
    {
        return $this->hasMany(KategoriInput::class, 'kategori_id');
    }
}
