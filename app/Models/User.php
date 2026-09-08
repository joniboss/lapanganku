<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi ke reservasi
    public function reservasis()
    {
        return $this->hasMany(Reservasi::class);
    }

    // Relasi ke membership
    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    // Relasi membership aktif (untuk diskon)
    public function membershipAktif()
    {
        return $this->hasOne(Membership::class)
            ->where('status', 'aktif')
            ->where('tanggal_berakhir', '>=', date('Y-m-d'))
            ->with('tier');
    }
}