<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use App\Models\Membership;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $uid = $user->id;

        // 1. Ringkasan
        $totalReservasi = Reservasi::where('user_id', $uid)->count();
        $totalPembayaran = Reservasi::where('user_id', $uid)
            ->where('status_pembayaran', 'lunas')
            ->sum('total_harga');

        $membership = Membership::with('tier')
            ->where('user_id', $uid)
            ->where('status', 'aktif')
            ->where('tanggal_berakhir', '>=', date('Y-m-d'))
            ->first();

        // 2. Reservasi mendatang
        $upcoming = Reservasi::with('lapangan')
            ->where('user_id', $uid)
            ->where('tanggal_main', '>=', date('Y-m-d'))
            ->where('status_reservasi', '!=', 'dibatalkan')
            ->orderBy('tanggal_main', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->limit(5)
            ->get();

        // 4. & 5. Search dan daftar lapangan
        $search = $request->input('search');
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $jenis = $request->input('jenis');

        $lapangans = Lapangan::where('status', 'aktif')
            ->when($search, function ($query, $search) {
                return $query->where('nama_lapangan', 'LIKE', "%{$search}%")
                             ->orWhere('jenis_olahraga', 'LIKE', "%{$search}%");
            })
            ->when($jenis, function ($query, $jenis) {
                return $query->where('jenis_olahraga', $jenis);
            })
            ->orderBy('nama_lapangan')
            ->get();

        return view('customer.dashboard', compact(
            'totalReservasi',
            'totalPembayaran',
            'membership',
            'upcoming',
            'lapangans',
            'search',
            'tanggal',
            'jenis'
        ));
    }
}