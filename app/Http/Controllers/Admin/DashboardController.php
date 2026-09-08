<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use App\Models\Lapangan;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalReservasi = Reservasi::count();
        $totalPendapatan = Reservasi::where('status_pembayaran', 'lunas')->sum('total_harga');
        $totalMember = User::where('role', 'customer')->count();
        $totalLapangan = Lapangan::where('status', 'aktif')->count();

        $reservasiTerbaru = Reservasi::with(['user', 'lapangan'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Data grafik
        $labels = [];
        $reservasiData = [];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = date('Y-m-d', strtotime("-$i days"));
            $labels[] = date('d M', strtotime($tanggal));
            $reservasiData[] = Reservasi::whereDate('created_at', $tanggal)->count();
        }

        $bulanLabels = [];
        $pendapatanData = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = date('Y-m', strtotime("-$i months"));
            $bulanLabels[] = date('M Y', strtotime($bulan . '-01'));
            $pendapatanData[] = Reservasi::where('status_pembayaran', 'lunas')
                ->whereYear('created_at', date('Y', strtotime($bulan)))
                ->whereMonth('created_at', date('m', strtotime($bulan)))
                ->sum('total_harga');
        }

        $statusPending = Reservasi::where('status_reservasi', 'pending')->count();
        $statusDikonfirmasi = Reservasi::where('status_reservasi', 'dikonfirmasi')->count();
        $statusSelesai = Reservasi::where('status_reservasi', 'selesai')->count();
        $statusDibatalkan = Reservasi::where('status_reservasi', 'dibatalkan')->count();

        return view('admin.dashboard', compact(
            'totalReservasi',
            'totalPendapatan',
            'totalMember',
            'totalLapangan',
            'reservasiTerbaru',
            'labels',
            'reservasiData',
            'bulanLabels',
            'pendapatanData',
            'statusPending',
            'statusDikonfirmasi',
            'statusSelesai',
            'statusDibatalkan'
        ));
    }
}