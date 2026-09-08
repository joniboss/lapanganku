<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use App\Models\Lapangan;
use App\Models\User;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        // Total pendapatan per bulan (6 bulan terakhir)
        $pendapatanBulanan = Reservasi::select(
                DB::raw('YEAR(tanggal_main) as tahun'),
                DB::raw('MONTH(tanggal_main) as bulan'),
                DB::raw('SUM(total_harga) as total')
            )
            ->where('status_pembayaran', 'lunas')
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->limit(6)
            ->get();

        // Jumlah reservasi per bulan (6 bulan terakhir)
        $reservasiBulanan = Reservasi::select(
                DB::raw('YEAR(tanggal_main) as tahun'),
                DB::raw('MONTH(tanggal_main) as bulan'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->limit(6)
            ->get();

        // Top 3 lapangan paling sering dipesan
        $topLapangan = Reservasi::select('lapangan_id', DB::raw('COUNT(*) as total'))
            ->groupBy('lapangan_id')
            ->with('lapangan')
            ->orderBy('total', 'desc')
            ->limit(3)
            ->get();

        // Total member aktif
        $totalMemberAktif = Membership::where('status', 'aktif')
            ->where('tanggal_berakhir', '>=', now())
            ->count();

        // Total pendapatan keseluruhan
        $totalPendapatan = Reservasi::where('status_pembayaran', 'lunas')->sum('total_harga');

        return view('admin.laporan', compact(
            'pendapatanBulanan',
            'reservasiBulanan',
            'topLapangan',
            'totalMemberAktif',
            'totalPendapatan'
        ));
    }
}