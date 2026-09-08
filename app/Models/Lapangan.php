<?php

namespace App\Models;  

use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model  
{
    protected $fillable = [
        'nama_lapangan',
        'jenis_olahraga',
        'harga_per_jam',
        'deskripsi',
        'status',
    ];
}