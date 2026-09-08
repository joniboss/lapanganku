<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    use HasFactory;

    // 🔧 Tentukan nama tabel yang benar (sesuai dengan migration)
    protected $table = 'reservasis';

    protected $fillable = [
        'kode_reservasi',
        'user_id',
        'lapangan_id',
        'tanggal_main',
        'jam_mulai',
        'jam_selesai',
        'durasi_jam',
        'harga_satuan',
        'diskon_persen',
        'total_harga',
        'status_reservasi',
        'status_pembayaran',
        'metode_pembayaran',
        'bukti_pembayaran',
        'tanggal_bayar',
        'catatan',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Lapangan
    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }
}