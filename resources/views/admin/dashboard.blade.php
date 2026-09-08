@extends('layouts.lapangan')

@section('title', 'Dashboard Admin - LapanganKu')

@section('content')
<div class="dashboard-header">
    <h1>Dashboard Admin</h1>
    <p class="subtitle">Ringkasan aktivitas dan data sistem</p>
</div>

<!-- ============================================================ -->
<!-- STATISTIK CARDS -->
<!-- ============================================================ -->
<div class="grid grid-4">
    <div class="stat-card">
        <div class="stat-icon">📊</div>
        <div class="stat-info">
            <div class="stat-label">Total Reservasi</div>
            <div class="stat-value">{{ $totalReservasi ?? 0 }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">💰</div>
        <div class="stat-info">
            <div class="stat-label">Pendapatan</div>
            <div class="stat-value">Rp {{ number_format($totalPendapatan ?? 0) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div class="stat-info">
            <div class="stat-label">Total Member</div>
            <div class="stat-value">{{ $totalMember ?? 0 }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🏸</div>
        <div class="stat-info">
            <div class="stat-label">Lapangan Aktif</div>
            <div class="stat-value">{{ $totalLapangan ?? 0 }}</div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- GRAFIK PROGRESS -->
<!-- ============================================================ -->
<div class="grid grid-2">
    <!-- Grafik Reservasi per Hari -->
    <div class="card">
        <div class="flex-between">
            <h2 class="mt-0">📈 Reservasi (7 Hari Terakhir)</h2>
        </div>
        <canvas id="reservasiChart" style="max-height:200px;"></canvas>
    </div>

    <!-- Grafik Pendapatan per Bulan -->
    <div class="card">
        <div class="flex-between">
            <h2 class="mt-0">💰 Pendapatan (6 Bulan Terakhir)</h2>
        </div>
        <canvas id="pendapatanChart" style="max-height:200px;"></canvas>
    </div>
</div>

<!-- ============================================================ -->
<!-- STATISTIK RESERVASI PER STATUS -->
<!-- ============================================================ -->
<div class="card">
    <div class="flex-between">
        <h2 class="mt-0">📋 Statistik Reservasi</h2>
    </div>
    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
        <div><span class="badge badge-pending">Pending</span> : {{ $statusPending ?? 0 }}</div>
        <div><span class="badge badge-confirmed">Dikonfirmasi</span> : {{ $statusDikonfirmasi ?? 0 }}</div>
        <div><span class="badge badge-done">Selesai</span> : {{ $statusSelesai ?? 0 }}</div>
        <div><span class="badge badge-cancelled">Dibatalkan</span> : {{ $statusDibatalkan ?? 0 }}</div>
    </div>
</div>

<!-- ============================================================ -->
<!-- RESERVASI TERBARU -->
<!-- ============================================================ -->
<div class="card">
    <div class="flex-between">
        <h2 class="mt-0">Reservasi Terbaru</h2>
        <a href="{{ route('admin.reservasi') }}" class="btn btn-sm">Lihat Semua</a>
    </div>
    @if(isset($reservasiTerbaru) && count($reservasiTerbaru) > 0)
    <table>
        <tr>
            <th>Kode</th>
            <th>Pelanggan</th>
            <th>Lapangan</th>
            <th>Tanggal</th>
            <th>Status</th>
        </tr>
        @foreach($reservasiTerbaru as $r)
        <tr>
            <td>{{ $r->kode_reservasi }}</td>
            <td>{{ optional($r->user)->name ?? 'N/A' }}</td>
            <td>{{ optional($r->lapangan)->nama_lapangan ?? 'N/A' }}</td>
            <td>{{ date('d M Y', strtotime($r->tanggal_main)) }}</td>
            <td>
                @php
                    $statusClass = match($r->status_reservasi) {
                        'dikonfirmasi' => 'confirmed',
                        'pending'      => 'pending',
                        'dibatalkan'   => 'cancelled',
                        'selesai'      => 'done',
                        default        => 'done',
                    };
                @endphp
                <span class="badge badge-{{ $statusClass }}">{{ ucfirst($r->status_reservasi) }}</span>
            </td>
        </tr>
        @endforeach
    </table>
    @else
    <p class="text-muted">Belum ada reservasi.</p>
    @endif
</div>

<!-- ============================================================ -->
<!-- MENU CEPAT -->
<!-- ============================================================ -->
<div class="grid grid-3">
    <a href="{{ route('admin.lapangan') }}" class="quick-menu-card">🏸 Kelola Lapangan</a>
    <a href="{{ route('admin.reservasi') }}" class="quick-menu-card">📋 Kelola Reservasi</a>
    <a href="{{ route('admin.member') }}" class="quick-menu-card">👥 Kelola Membership</a>
</div>

@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Grafik Reservasi per Hari
        const ctx1 = document.getElementById('reservasiChart').getContext('2d');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: @json($labels ?? []),
                datasets: [{
                    label: 'Reservasi',
                    data: @json($reservasiData ?? []),
                    backgroundColor: 'rgba(74, 144, 217, 0.2)',
                    borderColor: 'rgba(74, 144, 217, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Grafik Pendapatan per Bulan
        const ctx2 = document.getElementById('pendapatanChart').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: @json($bulanLabels ?? []),
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: @json($pendapatanData ?? []),
                    backgroundColor: 'rgba(40, 167, 69, 0.6)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush

@push('styles')
<style>
    /* ============================================================ */
    /* STATISTIK CARDS */
    /* ============================================================ */
    .dashboard-header { margin-bottom: 24px; }
    .dashboard-header h1 { margin: 0; font-size: 28px; }
    .subtitle { color: #666; margin-top: 4px; }
    
    .grid-4 { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; margin-bottom: 24px; }
    .grid-3 { display: grid; grid-template-columns: repeat(3,1fr); gap: 16px; margin-top: 20px; }
    .grid-2 { display: grid; grid-template-columns: repeat(2,1fr); gap: 16px; margin-bottom: 24px; }
    
    .stat-card { 
        background: #fff; 
        border-radius: 10px; 
        padding: 20px; 
        box-shadow: 0 2px 8px rgba(0,0,0,0.06); 
        display: flex; 
        align-items: center; 
        gap: 16px; 
    }
    .stat-icon { font-size: 32px; }
    .stat-label { font-size: 14px; color: #888; }
    .stat-value { font-size: 24px; font-weight: 700; color: #333; }

    /* ============================================================ */
    /* CARD */
    /* ============================================================ */
    .card { 
        background: #fff; 
        border-radius: 10px; 
        padding: 24px; 
        box-shadow: 0 2px 8px rgba(0,0,0,0.06); 
        margin-bottom: 20px; 
    }
    .mt-0 { margin-top: 0; }
    .text-muted { color: #888; }
    .flex-between { display: flex; justify-content: space-between; align-items: center; }

    /* ============================================================ */
    /* TABLE */
    /* ============================================================ */
    table { width: 100%; border-collapse: collapse; font-size: 14px; }
    table th { text-align: left; padding: 10px 8px; border-bottom: 2px solid #eee; font-weight: 600; }
    table td { padding: 10px 8px; border-bottom: 1px solid #f0f0f0; }

    /* ============================================================ */
    /* BADGE */
    /* ============================================================ */
    .badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; }
    .badge-confirmed, .badge-paid { background: #d4edda; color: #155724; }
    .badge-pending, .badge-wait { background: #fff3cd; color: #856404; }
    .badge-cancelled, .badge-unpaid { background: #f8d7da; color: #721c24; }
    .badge-done { background: #cce5ff; color: #004085; }

    /* ============================================================ */
    /* BUTTON */
    /* ============================================================ */
    .btn-sm { padding: 6px 14px; font-size: 13px; background: #4A90D9; color: #fff; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
    .btn-sm:hover { background: #357ABD; }

    /* ============================================================ */
    /* QUICK MENU */
    /* ============================================================ */
    .quick-menu-card { 
        background: #f8f9fa; 
        border-radius: 10px; 
        padding: 24px; 
        text-align: center; 
        text-decoration: none; 
        color: #333; 
        transition: 0.2s; 
        border: 1px solid #eaeaea; 
    }
    .quick-menu-card:hover { 
        background: #eef3ff; 
        border-color: #4A90D9; 
        transform: translateY(-2px); 
        box-shadow: 0 4px 12px rgba(74,144,217,0.15); 
    }

    /* ============================================================ */
    /* RESPONSIVE */
    /* ============================================================ */
    @media (max-width: 768px) {
        .grid-4 { grid-template-columns: repeat(2,1fr); }
        .grid-3 { grid-template-columns: 1fr; }
        .grid-2 { grid-template-columns: 1fr; }
    }
</style>
@endpush