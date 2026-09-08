@extends('layouts.lapangan')

@section('title', 'Laporan - LapanganKu')

@section('content')
<div class="dashboard-header">
    <h1>Laporan Keuangan & Reservasi</h1>
    <p class="subtitle">Ringkasan pendapatan dan aktivitas reservasi</p>
</div>

<!-- Statistik Cards -->
<div class="grid grid-4">
    <div class="stat-card">
        <div class="stat-label">Total Pendapatan</div>
        <div class="stat-value">Rp {{ number_format($totalPendapatan) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Member Aktif</div>
        <div class="stat-value">{{ $totalMemberAktif }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Reservasi</div>
        <div class="stat-value">{{ \App\Models\Reservasi::count() }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Lapangan Aktif</div>
        <div class="stat-value">{{ \App\Models\Lapangan::where('status', 'aktif')->count() }}</div>
    </div>
</div>

<!-- Grafik Sederhana (Chart.js) -->
<div class="card">
    <h2 class="mt-0">Pendapatan Bulanan (6 Bulan Terakhir)</h2>
    <canvas id="chartPendapatan" style="max-height:300px;"></canvas>
</div>

<div class="card">
    <h2 class="mt-0">Jumlah Reservasi per Bulan</h2>
    <canvas id="chartReservasi" style="max-height:300px;"></canvas>
</div>

<!-- Top Lapangan -->
<div class="card">
    <h2 class="mt-0">🏆 Lapangan Paling Populer</h2>
    @if($topLapangan->count() > 0)
    <table>
        <tr><th>Lapangan</th><th>Total Reservasi</th></tr>
        @foreach($topLapangan as $lap)
        <tr>
            <td>{{ $lap->lapangan->nama_lapangan ?? 'N/A' }}</td>
            <td>{{ $lap->total }}</td>
        </tr>
        @endforeach
    </table>
    @else
    <p class="text-muted">Belum ada data reservasi.</p>
    @endif
</div>

<!-- Tabel Pendapatan Bulanan -->
<div class="card">
    <h2 class="mt-0">Rincian Pendapatan Bulanan</h2>
    @if($pendapatanBulanan->count() > 0)
    <table>
        <tr><th>Bulan</th><th>Tahun</th><th>Pendapatan</th></tr>
        @foreach($pendapatanBulanan as $data)
        <tr>
            <td>{{ date('F', mktime(0,0,0,$data->bulan,1)) }}</td>
            <td>{{ $data->tahun }}</td>
            <td>Rp {{ number_format($data->total) }}</td>
        </tr>
        @endforeach
    </table>
    @else
    <p class="text-muted">Belum ada data pendapatan.</p>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data Pendapatan
    const pendapatanData = @json($pendapatanBulanan->reverse()->values());
    const labelsPendapatan = pendapatanData.map(item => {
        const bulan = new Date(item.tahun, item.bulan - 1).toLocaleString('id-ID', { month: 'long' });
        return `${bulan} ${item.tahun}`;
    });
    const valuesPendapatan = pendapatanData.map(item => item.total);

    const ctx1 = document.getElementById('chartPendapatan').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: labelsPendapatan,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: valuesPendapatan,
                backgroundColor: 'rgba(74, 144, 217, 0.7)',
                borderColor: '#4A90D9',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: value => 'Rp ' + new Intl.NumberFormat('id-ID').format(value) }
                }
            }
        }
    });

    // Data Reservasi
    const reservasiData = @json($reservasiBulanan->reverse()->values());
    const labelsReservasi = reservasiData.map(item => {
        const bulan = new Date(item.tahun, item.bulan - 1).toLocaleString('id-ID', { month: 'long' });
        return `${bulan} ${item.tahun}`;
    });
    const valuesReservasi = reservasiData.map(item => item.jumlah);

    const ctx2 = document.getElementById('chartReservasi').getContext('2d');
    new Chart(ctx2, {
        type: 'line',
        data: {
            labels: labelsReservasi,
            datasets: [{
                label: 'Jumlah Reservasi',
                data: valuesReservasi,
                backgroundColor: 'rgba(46, 204, 113, 0.2)',
                borderColor: '#2ecc71',
                borderWidth: 2,
                pointBackgroundColor: '#2ecc71',
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
</script>
@endpush

@push('styles')
<style>
.grid-4 {
    display: grid;
    grid-template-columns: repeat(4,1fr);
    gap: 16px;
    margin-bottom: 24px;
}
.stat-card {
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.stat-label {
    font-size: 14px;
    color: #888;
}
.stat-value {
    font-size: 24px;
    font-weight: 700;
    color: #333;
}
.card {
    background: #fff;
    border-radius: 10px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    margin-bottom: 20px;
}
.mt-0 { margin-top: 0; }
.text-muted { color: #888; }
table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}
table th {
    text-align: left;
    padding: 10px 8px;
    border-bottom: 2px solid #eee;
    font-weight: 600;
}
table td {
    padding: 10px 8px;
    border-bottom: 1px solid #f0f0f0;
}
@media (max-width: 768px) {
    .grid-4 {
        grid-template-columns: repeat(2,1fr);
    }
}
</style>
@endpush