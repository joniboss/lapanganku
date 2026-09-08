<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\Reservasi;
use Illuminate\Http\Request;

class LapanganController extends Controller
{
    public function index()
    {
        $lapangans = Lapangan::orderBy('status', 'desc')->orderBy('nama_lapangan')->get();
        return view('admin.lapangan', compact('lapangans'));
    }

    public function create()
    {
        return view('admin.lapangan-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lapangan' => 'required|string|max:255|unique:lapangans',
            'jenis_olahraga' => 'required|in:futsal,badminton,basket,padel',
            'harga_per_jam' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $lapangan = Lapangan::create($validated);
        return redirect()->route('admin.lapangan')->with('success', "Lapangan '{$lapangan->nama_lapangan}' berhasil ditambahkan.");
    }

    public function edit($id)
    {
        $lapangan = Lapangan::findOrFail($id);
        return view('admin.lapangan-edit', compact('lapangan'));
    }

    public function update(Request $request, $id)
    {
        $lapangan = Lapangan::findOrFail($id);

        $validated = $request->validate([
            'nama_lapangan' => 'required|string|max:255|unique:lapangans,nama_lapangan,' . $id,
            'jenis_olahraga' => 'required|in:futsal,badminton,basket,padel',
            'harga_per_jam' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $lapangan->update($validated);
        return redirect()->route('admin.lapangan')->with('success', "Lapangan '{$lapangan->nama_lapangan}' berhasil diperbarui.");
    }

    public function toggleStatus($id)
    {
        $lapangan = Lapangan::findOrFail($id);
        $newStatus = $lapangan->status === 'aktif' ? 'nonaktif' : 'aktif';
        $lapangan->update(['status' => $newStatus]);

        $message = $newStatus === 'aktif'
            ? "Lapangan '{$lapangan->nama_lapangan}' berhasil diaktifkan."
            : "Lapangan '{$lapangan->nama_lapangan}' berhasil dinonaktifkan.";

        return back()->with('success', $message);
    }

    public function destroy($id)
    {
        $lapangan = Lapangan::findOrFail($id);

        // Cek reservasi aktif
        $hasActiveReservasi = Reservasi::where('lapangan_id', $id)
            ->whereIn('status_reservasi', ['pending', 'dikonfirmasi'])
            ->exists();

        if ($hasActiveReservasi) {
            return back()->with('error', "Lapangan '{$lapangan->nama_lapangan}' tidak bisa dihapus karena masih ada reservasi aktif.");
        }

        $lapangan->delete();
        return back()->with('success', "Lapangan '{$lapangan->nama_lapangan}' berhasil dihapus permanen.");
    }
}