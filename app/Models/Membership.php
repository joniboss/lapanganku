<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $table = 'memberships';

    protected $fillable = [
        'user_id',
        'tier_id',
        'tanggal_mulai',
        'tanggal_berakhir',
        'status',
        'status_pembayaran',
        'metode_pembayaran',
        'bukti_pembayaran',
        'tanggal_bayar',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tier()
    {
        return $this->belongsTo(MembershipTier::class, 'tier_id');
    }
}