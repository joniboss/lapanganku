<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use App\Models\Lapangan;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservasiController extends Controller
{
    public function index(Request $request)
    {
        $lapanganId = $request->lapangan_id;
        $lapangan = Lapangan::find($lapanganId);

        if (!$lapangan) {
            return redirect()->route('home')->with('error', 'Lapangan tidak ditemukan.');
        }

        $existingSchedule = Reservasi::where('lapangan_id', $lapanganId)
            ->where('tanggal_main', '>=', date('Y-m-d'))
            ->whereIn('status_reservasi', ['pending', 'dikonfirmasi', 'selesai'])
            ->get();

        $user = Auth::user();
        $membership = null;
        $diskon = 0;
        if ($user) {
            $membership = Membership::with('tier')
                ->where('user_id', $user->id)
                ->where('status', 'aktif')
                ->where('tanggal_berakhir', '>=', date('Y-m-d'))
                ->first();
            $diskon = $membership ? (float) $membership->tier->diskon_persen : 0;
        }

        return view('customer.reservasi', compact('lapangan', 'existingSchedule', 'membership', 'diskon'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lapangan_id' => 'required|exists:lapangans,id',
            'tanggal_main' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'catatan' => 'nullable|string',
        ]);

        $lapangan = Lapangan::find($request->lapangan_id);
        $durasi = (strtotime($request->jam_selesai) - strtotime($request->jam_mulai)) / 3600;
        $subtotal = $lapangan->harga_per_jam * $durasi;

        // ============================================================
        // 🔴 CEK BENTROK JADWAL (LOGIKA PASTI)
        // ============================================================
        $bentrok = Reservasi::where('lapangan_id', $request->lapangan_id)
            ->where('tanggal_main', $request->tanggal_main)
            ->whereIn('status_reservasi', ['pending', 'dikonfirmasi', 'selesai']) // hanya status aktif
            ->where(function ($query) use ($request) {
                $query->where('jam_mulai', '<', $request->jam_selesai)
                      ->where('jam_selesai', '>', $request->jam_mulai);
            })
            ->exists();

        if ($bentrok) {
            return back()
                ->with('error', '⛔ Jadwal yang Anda pilih sudah dipesan. Silahkan pilih jam lain.')
                ->withInput();
        }

        // ============================================================
        // AMBIL DISKON MEMBERSHIP
        // ============================================================
        $user = Auth::user();
        $membership = Membership::with('tier')
            ->where('user_id', $user->id)
            ->where('status', 'aktif')
            ->where('tanggal_berakhir', '>=', date('Y-m-d'))
            ->first();

        $diskon = $membership ? (float) $membership->tier->diskon_persen : 0;
        $total = $subtotal - ($subtotal * $diskon / 100);

        // ============================================================
        // SIMPAN RESERVASI
        // ============================================================
        $reservasi = Reservasi::create([
            'kode_reservasi' => 'RSV' . date('YmdHis') . rand(10, 99),
            'user_id' => $user->id,
            'lapangan_id' => $request->lapangan_id,
            'tanggal_main' => $request->tanggal_main,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'durasi_jam' => $durasi,
            'harga_satuan' => $lapangan->harga_per_jam,
            'diskon_persen' => $diskon,
            'total_harga' => $total,
            'status_reservasi' => 'pending',
            'status_pembayaran' => 'belum_bayar',
            'catatan' => $request->catatan,
        ]);

        return redirect()
            ->route('customer.riwayat')
            ->with('success', "✅ Reservasi berhasil dengan kode {$reservasi->kode_reservasi}");
    }

    public function riwayat()
    {
        $reservasis = Reservasi::with('lapangan')
            ->whereHas('lapangan')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.riwayat', compact('reservasis'));
    }
}