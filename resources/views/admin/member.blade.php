@extends('layouts.lapangan')

@section('title', 'Kelola Membership - LapanganKu')

@section('content')
<div class="dashboard-header">
    <h1>Kelola Membership</h1>
    <p class="subtitle">Kelola paket membership dan pantau status keanggotaan pelanggan</p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

<!-- Form Tambah/Edit Paket -->
<div class="card">
    <h2 class="mt-0">{{ isset($editTier) ? 'Edit Paket' : 'Tambah Paket Membership' }}</h2>
    <form method="POST" action="{{ isset($editTier) ? route('admin.tier.update', $editTier->id) : route('admin.tier.store') }}" style="max-width:500px;">
        @csrf
        @if(isset($editTier))
            @method('PUT')
            <input type="hidden" name="id" value="{{ $editTier->id }}">
        @endif
        <div class="form-group">
            <label for="nama_tier">Nama Tier</label>
            <input type="text" name="nama_tier" id="nama_tier" value="{{ old('nama_tier', $editTier->nama_tier ?? '') }}" required>
        </div>
        <div class="form-group">
            <label for="harga_paket">Harga Paket (Rp)</label>
            <input type="number" name="harga_paket" id="harga_paket" value="{{ old('harga_paket', $editTier->harga_paket ?? 0) }}" required min="0" step="1000">
        </div>
        <div class="form-group">
            <label for="durasi_hari">Durasi (hari)</label>
            <input type="number" name="durasi_hari" id="durasi_hari" value="{{ old('durasi_hari', $editTier->durasi_hari ?? 30) }}" required min="1">
        </div>
        <div class="form-group">
            <label for="diskon_persen">Diskon (%)</label>
            <input type="number" name="diskon_persen" id="diskon_persen" value="{{ old('diskon_persen', $editTier->diskon_persen ?? 0) }}" required min="0" max="100" step="0.5">
        </div>
        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" rows="2">{{ old('deskripsi', $editTier->deskripsi ?? '') }}</textarea>
        </div>
        <button type="submit" class="btn">{{ isset($editTier) ? 'Simpan Perubahan' : 'Tambah Paket' }}</button>
        @if(isset($editTier))
            <a href="{{ route('admin.member') }}" class="btn btn-secondary">Batal</a>
        @endif
    </form>
</div>

<!-- Daftar Paket Membership -->
<div class="card">
    <h2 class="mt-0">Daftar Paket Membership</h2>
    @if(isset($tiers) && count($tiers) > 0)
    <table>
        <tr>
            <th>Nama</th>
            <th>Harga</th>
            <th>Durasi</th>
            <th>Diskon</th>
            <th>Aksi</th>
        </tr>
        @foreach($tiers as $t)
        <tr>
            <td>{{ $t->nama_tier }}</td>
            <td>{{ $t->harga_paket > 0 ? 'Rp '.number_format($t->harga_paket) : 'Gratis' }}</td>
            <td>{{ $t->durasi_hari }} hari</td>
            <td>{{ $t->diskon_persen }}%</td>
            <td>
                <a href="{{ route('admin.tier.edit', $t->id) }}" class="btn btn-sm">Edit</a>
                <form method="POST" action="{{ route('admin.tier.destroy', $t->id) }}" style="display:inline;" onsubmit="return confirm('Hapus paket {{ $t->nama_tier }}?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
    @else
    <p class="text-muted">Belum ada paket membership.</p>
    @endif
</div>

<!-- Daftar Member -->
<div class="card">
    <h2 class="mt-0">Daftar Member</h2>
    @if(isset($members) && count($members) > 0)
    <table>
        <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Paket</th>
            <th>Mulai</th>
            <th>Berakhir</th>
            <th>Status</th>
            <th>Pembayaran</th>
            <th>Aksi</th>
        </tr>
        @foreach($members as $m)
        <tr>
            <td>{{ optional($m->user)->name ?? 'N/A' }}</td>
            <td>{{ optional($m->user)->email ?? 'N/A' }}</td>
            <td>{{ optional($m->tier)->nama_tier ?? 'N/A' }} ({{ optional($m->tier)->diskon_persen ?? 0 }}%)</td>
            <td>{{ date('d M Y', strtotime($m->tanggal_mulai)) }}</td>
            <td>{{ date('d M Y', strtotime($m->tanggal_berakhir)) }}</td>
            <td>
                @php
                    $statusLabel = match($m->status) {
                        'aktif' => 'Aktif',
                        'pending' => 'Pending',
                        'dibatalkan' => 'Dibatalkan',
                        default => 'Kadaluarsa',
                    };
                    $statusClass = match($m->status) {
                        'aktif' => 'confirmed',
                        'pending' => 'pending',
                        'dibatalkan' => 'cancelled',
                        default => 'done',
                    };
                @endphp
                <span class="badge badge-{{ $statusClass }}">{{ $statusLabel }}</span>
            </td>
            <td>
                @if($m->status_pembayaran === 'lunas')
                    <span class="badge badge-paid">Lunas</span>
                @elseif($m->status_pembayaran === 'menunggu_verifikasi')
                    <span class="badge badge-wait">Menunggu</span>
                @else
                    <span class="badge badge-unpaid">Belum Bayar</span>
                @endif
            </td>
            <td>
                @if(isset($m->bukti_pembayaran) && $m->bukti_pembayaran)
                    <a href="{{ asset($m->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-info">📎 Lihat Bukti</a>
                @endif

                @if($m->status_pembayaran === 'menunggu_verifikasi')
                    <form method="POST" action="{{ route('admin.member.verifikasi', $m->id) }}" style="display:inline;">
                        @csrf
                        <button class="btn btn-sm btn-success">Verif</button>
                    </form>
                @endif

                <form method="POST" action="{{ route('admin.member.destroy', $m->id) }}" style="display:inline;" onsubmit="return confirm('Hapus riwayat member ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">🗑️</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
    @else
    <p class="text-muted">Belum ada pelanggan yang berlangganan membership.</p>
    @endif
</div>
@endsection

@push('styles')
<style>
.dashboard-header { margin-bottom: 24px; }
.dashboard-header h1 { margin: 0; font-size: 28px; }
.subtitle { color: #666; margin-top: 4px; }
.alert-success { background: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 16px; }
.alert-error { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 6px; margin-bottom: 16px; }
.card { background: #fff; border-radius: 10px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); margin-bottom: 20px; }
.mt-0 { margin-top: 0; }
.form-group { margin-bottom: 16px; }
.form-group label { display: block; font-weight: 600; margin-bottom: 4px; }
.form-group input, .form-group select, .form-group textarea { width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }
.btn { padding: 8px 20px; background: #4A90D9; color: #fff; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; display: inline-block; font-size: 14px; }
.btn:hover { background: #357ABD; }
.btn-secondary { background: #6c757d; }
.btn-secondary:hover { background: #5a6268; }
.btn-sm { padding: 4px 10px; font-size: 12px; margin: 2px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
.btn-success { background: #28a745; color: #fff; }
.btn-success:hover { background: #218838; }
.btn-danger { background: #dc3545; color: #fff; }
.btn-danger:hover { background: #c82333; }
.btn-info { background: #17a2b8; color: #fff; }
.btn-info:hover { background: #138496; }
table { width: 100%; border-collapse: collapse; font-size: 14px; }
table th { text-align: left; padding: 10px 8px; border-bottom: 2px solid #eee; font-weight: 600; }
table td { padding: 10px 8px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
.badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; }
.badge-confirmed, .badge-paid { background: #d4edda; color: #155724; }
.badge-pending, .badge-wait { background: #fff3cd; color: #856404; }
.badge-cancelled, .badge-unpaid { background: #f8d7da; color: #721c24; }
.badge-done { background: #cce5ff; color: #004085; }
.text-muted { color: #888; }
</style>
@endpush