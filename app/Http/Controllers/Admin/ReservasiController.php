<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use Illuminate\Http\Request;

class ReservasiController extends Controller
{
    public function index()
    {
        $reservasis = Reservasi::with(['user', 'lapangan'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.reservasi', compact('reservasis'));
    }

    public function verifikasi($id)
    {
        $reservasi = Reservasi::findOrFail($id);
        $reservasi->update([
            'status_pembayaran' => 'lunas',
            'status_reservasi' => 'dikonfirmasi',
        ]);
        return back()->with('success', 'Pembayaran diverifikasi dan reservasi dikonfirmasi.');
    }

    public function batalkan($id)
    {
        $reservasi = Reservasi::findOrFail($id);
        $reservasi->update(['status_reservasi' => 'dibatalkan']);
        return back()->with('success', 'Reservasi dibatalkan.');
    }

    public function selesai($id)
    {
        $reservasi = Reservasi::findOrFail($id);
        $reservasi->update(['status_reservasi' => 'selesai']);
        return back()->with('success', 'Reservasi ditandai selesai.');
    }

    public function destroy($id)
    {
        $reservasi = Reservasi::whereIn('status_reservasi', ['dibatalkan', 'selesai'])->findOrFail($id);
        $reservasi->delete();
        return back()->with('success', 'Reservasi berhasil dihapus.');
    }
}