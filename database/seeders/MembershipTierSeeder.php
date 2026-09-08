<?php

namespace Database\Seeders;

use App\Models\MembershipTier;
use Illuminate\Database\Seeder;

class MembershipTierSeeder extends Seeder
{
    public function run(): void
    {
        MembershipTier::create([
            'nama_tier' => 'Reguler',
            'harga_paket' => 0,
            'durasi_hari' => 0,
            'diskon_persen' => 0,
            'deskripsi' => 'Paket reguler gratis tanpa diskon',
        ]);

        MembershipTier::create([
            'nama_tier' => 'Silver',
            'harga_paket' => 50000,
            'durasi_hari' => 30,
            'diskon_persen' => 5,
            'deskripsi' => 'Paket Silver: diskon 5% untuk setiap reservasi',
        ]);

        MembershipTier::create([
            'nama_tier' => 'Gold',
            'harga_paket' => 100000,
            'durasi_hari' => 30,
            'diskon_persen' => 10,
            'deskripsi' => 'Paket Gold: diskon 10% untuk setiap reservasi',
        ]);
    }
}