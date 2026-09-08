<?php

namespace Database\Seeders;

use App\Models\Lapangan;
use Illuminate\Database\Seeder;

class LapanganSeeder extends Seeder
{
    public function run(): void
    {
        Lapangan::create([
            'nama_lapangan' => 'Lapangan Futsal A',
            'jenis_olahraga' => 'futsal',
            'harga_per_jam' => 150000,
            'deskripsi' => 'Lapangan futsal indoor standar FIFA',
            'status' => 'aktif',
        ]);

        Lapangan::create([
            'nama_lapangan' => 'Lapangan Badminton 1',
            'jenis_olahraga' => 'badminton',
            'harga_per_jam' => 60000,
            'deskripsi' => 'Lapangan badminton indoor lantai vinyl',
            'status' => 'aktif',
        ]);
    }
}