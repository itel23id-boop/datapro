<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriInput extends Model
{
    use HasFactory;
    protected $table = 'kategori_input';
    protected $guarded = ['id'];
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
    public function dropdown()
    {
        return $this->belongsTo(Dropdown::class, 'id_input');
    }
}
