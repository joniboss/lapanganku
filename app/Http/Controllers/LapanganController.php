<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use Illuminate\Http\Request;

class LapanganController extends Controller
{
    public function index()
    {
        // Hapus session error yang mungkin tertinggal dari proses sebelumnya
        session()->forget('error');
        
        // Ambil semua lapangan dengan status aktif
        $lapangans = Lapangan::where('status', 'aktif')->get();

        return view('lapangan.index', compact('lapangans'));
    }

    public function show($id)
    {
        $lapangan = Lapangan::findOrFail($id);
        return view('lapangan.show', compact('lapangan'));
    }
}