<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipTier extends Model
{
    protected $table = 'membership_tiers';

    protected $fillable = [
        'nama_tier',
        'harga_paket',
        'durasi_hari',
        'diskon_persen',
        'deskripsi',
    ];

    public function memberships()
    {
        return $this->hasMany(Membership::class, 'tier_id');
    }
}