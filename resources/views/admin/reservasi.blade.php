@extends('layouts.lapangan')

@section('title', 'Kelola Reservasi - LapanganKu')

@section('content')
<!-- ============================================================ -->
<!-- HEADER -->
<!-- ============================================================ -->
<div style="
    background: linear-gradient(135deg, #0B3A2C 0%, #175E46 50%, #1C7253 100%);
    border-radius: 16px;
    padding: 28px 32px;
    margin-bottom: 28px;
    color: #fff;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(11,58,44,0.3);
">
    <div style="position: absolute; top: -60px; right: -60px; width: 200px; height: 200px; border-radius: 50%; background: rgba(198,162,77,0.10);"></div>
    <div style="position: absolute; bottom: -40px; left: -20px; width: 120px; height: 120px; border-radius: 50%; background: rgba(198,162,77,0.06);"></div>

    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <span style="font-size: 32px;">📋</span>
            <div>
                <h1 style="margin: 0; font-size: 28px; font-weight: 700; color: #fff;">
                    Kelola Reservasi
                </h1>
                <p style="margin: 4px 0 0 0; opacity: 0.8; font-size: 15px;">
                    Konfirmasi jadwal dan verifikasi pembayaran pelanggan
                </p>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div style="background:#d4edda;color:#155724;padding:14px 20px;border-radius:10px;margin-bottom:20px;border-left:4px solid #28a745;font-weight:500;">
        ✅ {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div style="background:#f8d7da;color:#721c24;padding:14px 20px;border-radius:10px;margin-bottom:20px;border-left:4px solid #dc3545;font-weight:500;">
        ❌ {{ session('error') }}
    </div>
@endif

<!-- TAB FILTER -->
<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px;">
    <a href="{{ route('admin.reservasi', ['status' => 'semua']) }}" style="padding:8px 18px;border-radius:20px;background:{{ request('status') == 'semua' || !request('status') ? '#0B3A2C' : '#fff' }};color:{{ request('status') == 'semua' || !request('status') ? '#fff' : '#0B3A2C' }};border:1px solid #e8ecef;font-size:13px;font-weight:600;text-decoration:none;">Semua</a>
    <a href="{{ route('admin.reservasi', ['status' => 'pending']) }}" style="padding:8px 18px;border-radius:20px;background:{{ request('status') == 'pending' ? '#0B3A2C' : '#fff' }};color:{{ request('status') == 'pending' ? '#fff' : '#0B3A2C' }};border:1px solid #e8ecef;font-size:13px;font-weight:600;text-decoration:none;">Pending</a>
    <a href="{{ route('admin.reservasi', ['status' => 'dikonfirmasi']) }}" style="padding:8px 18px;border-radius:20px;background:{{ request('status') == 'dikonfirmasi' ? '#0B3A2C' : '#fff' }};color:{{ request('status') == 'dikonfirmasi' ? '#fff' : '#0B3A2C' }};border:1px solid #e8ecef;font-size:13px;font-weight:600;text-decoration:none;">Dikonfirmasi</a>
    <a href="{{ route('admin.reservasi', ['status' => 'selesai']) }}" style="padding:8px 18px;border-radius:20px;background:{{ request('status') == 'selesai' ? '#0B3A2C' : '#fff' }};color:{{ request('status') == 'selesai' ? '#fff' : '#0B3A2C' }};border:1px solid #e8ecef;font-size:13px;font-weight:600;text-decoration:none;">Selesai</a>
    <a href="{{ route('admin.reservasi', ['status' => 'dibatalkan']) }}" style="padding:8px 18px;border-radius:20px;background:{{ request('status') == 'dibatalkan' ? '#0B3A2C' : '#fff' }};color:{{ request('status') == 'dibatalkan' ? '#fff' : '#0B3A2C' }};border:1px solid #e8ecef;font-size:13px;font-weight:600;text-decoration:none;">Dibatalkan</a>
</div>

<!-- TABEL -->
<div style="
    background: #fff;
    border-radius: 14px;
    padding: 24px 28px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border: 1px solid #e8ecef;
">
    @if(isset($reservasis) && count($reservasis) > 0)
        <div style="overflow-x: auto; border-radius: 10px; border: 1px solid #eaeaea;">
            <table style="width: 100%; border-collapse: collapse; font-size: 13.5px;">
                <thead>
                    <tr style="background: linear-gradient(135deg, #0B3A2C, #1C7253); color: #fff;">
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600;">Kode</th>
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600;">Pelanggan</th>
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600;">Lapangan</th>
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600;">Tanggal/Jam</th>
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600;">Total</th>
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600;">Status</th>
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600;">Bayar</th>
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600; min-width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservasis as $index => $r)
                    <tr style="border-bottom:1px solid #eaeaea; background:@if($index%2==0) #fafcfb @else #fff @endif;">
                        <td style="padding:12px 14px; font-weight:600; color:#0B3A2C;">{{ $r->kode_reservasi }}</td>
                        <td style="padding:12px 14px;">
                            {{ optional($r->user)->name ?? 'N/A' }}
                            <br><span style="color:#888;font-size:12px;">{{ optional($r->user)->no_hp ?? '' }}</span>
                        </td>
                        <td style="padding:12px 14px;">{{ optional($r->lapangan)->nama_lapangan ?? 'N/A' }}</td>
                        <td style="padding:12px 14px;">
                            {{ date('d M Y', strtotime($r->tanggal_main)) }}
                            <br><span style="font-size:12px;color:#888;">{{ substr($r->jam_mulai,0,5) }} - {{ substr($r->jam_selesai,0,5) }}</span>
                        </td>
                        <td style="padding:12px 14px; font-weight:600; color:#0B3A2C;">Rp {{ number_format($r->total_harga) }}</td>
                        <td style="padding:12px 14px;">
                            @php
                                $statusClass = match($r->status_reservasi) {
                                    'dikonfirmasi' => 'confirmed',
                                    'pending'      => 'pending',
                                    'selesai'      => 'done',
                                    'dibatalkan'   => 'cancelled',
                                    default        => 'done',
                                };
                                $statusLabel = match($r->status_reservasi) {
                                    'dikonfirmasi' => '✅ Dikonfirmasi',
                                    'pending'      => '⏳ Pending',
                                    'selesai'      => '✔️ Selesai',
                                    'dibatalkan'   => '❌ Dibatalkan',
                                    default        => 'Selesai',
                                };
                                $bgColor = match($statusClass) {
                                    'confirmed' => '#d4edda',
                                    'pending'   => '#fff3cd',
                                    'done'      => '#cce5ff',
                                    'cancelled' => '#f8d7da',
                                    default     => '#e9ecef',
                                };
                                $textColor = match($statusClass) {
                                    'confirmed' => '#155724',
                                    'pending'   => '#856404',
                                    'done'      => '#004085',
                                    'cancelled' => '#721c24',
                                    default     => '#6c757d',
                                };
                            @endphp
                            <span style="display:inline-block;padding:4px 14px;border-radius:20px;font-size:12px;font-weight:600;background:{{ $bgColor }};color:{{ $textColor }};">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td style="padding:12px 14px;">
                            @if($r->status_pembayaran === 'lunas')
                                <span style="display:inline-block;padding:4px 14px;border-radius:20px;font-size:12px;font-weight:600;background:#d4edda;color:#155724;">💳 Lunas</span>
                            @elseif($r->status_pembayaran === 'menunggu_verifikasi')
                                <span style="display:inline-block;padding:4px 14px;border-radius:20px;font-size:12px;font-weight:600;background:#fff3cd;color:#856404;">⏳ Menunggu</span>
                            @else
                                <span style="display:inline-block;padding:4px 14px;border-radius:20px;font-size:12px;font-weight:600;background:#f8d7da;color:#721c24;">⚠️ Belum Bayar</span>
                            @endif
                        </td>
                        <td style="padding:12px 14px;">

                            <!-- ============================================================ -->
                            <!-- TOMBOL LIHAT BUKTI (DITAMBAHKAN) -->
                            <!-- ============================================================ -->
                            @if(isset($r->bukti_pembayaran) && $r->bukti_pembayaran)
                                <a href="{{ asset($r->bukti_pembayaran) }}" target="_blank" style="
                                    display: inline-block;
                                    padding: 6px 14px;
                                    background: #17a2b8;
                                    color: #fff;
                                    border-radius: 6px;
                                    font-size: 12px;
                                    font-weight: 600;
                                    text-decoration: none;
                                    margin-right: 4px;
                                    margin-bottom: 4px;
                                " onmouseover="this.style.background='#138496'" onmouseout="this.style.background='#17a2b8'">
                                    📎 Lihat Bukti
                                </a>
                            @endif

                            <!-- ============================================================ -->
                            <!-- AKSI LAINNYA -->
                            <!-- ============================================================ -->
                            @if($r->status_pembayaran === 'menunggu_verifikasi')
                                <form method="POST" action="{{ route('admin.reservasi.verifikasi', $r->id) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" style="padding:6px 14px;background:#28a745;color:#fff;border:none;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;margin-bottom:4px;">✅ Verif</button>
                                </form>
                                <form method="POST" action="{{ route('admin.reservasi.batalkan', $r->id) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" style="padding:6px 14px;background:#dc3545;color:#fff;border:none;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;margin-bottom:4px;">❌ Tolak</button>
                                </form>
                            @endif

                            @if($r->status_reservasi === 'pending')
                                <form method="POST" action="{{ route('admin.reservasi.verifikasi', $r->id) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" style="padding:6px 14px;background:#0B3A2C;color:#fff;border:none;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;margin-bottom:4px;">Konfirmasi</button>
                                </form>
                                <form method="POST" action="{{ route('admin.reservasi.batalkan', $r->id) }}" style="display:inline;" onsubmit="return confirm('Batalkan reservasi ini?')">
                                    @csrf
                                    <button type="submit" style="padding:6px 14px;background:#dc3545;color:#fff;border:none;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;margin-bottom:4px;">Batalkan</button>
                                </form>
                            @endif

                            @if($r->status_reservasi === 'dikonfirmasi')
                                <form method="POST" action="{{ route('admin.reservasi.selesai', $r->id) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" style="padding:6px 14px;background:#28a745;color:#fff;border:none;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;margin-bottom:4px;">✔️ Selesai</button>
                                </form>
                                <form method="POST" action="{{ route('admin.reservasi.batalkan', $r->id) }}" style="display:inline;" onsubmit="return confirm('Batalkan reservasi ini?')">
                                    @csrf
                                    <button type="submit" style="padding:6px 14px;background:#dc3545;color:#fff;border:none;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;margin-bottom:4px;">Batalkan</button>
                                </form>
                            @endif

                            @if(in_array($r->status_reservasi, ['dibatalkan', 'selesai']))
                                <a href="#" onclick="event.preventDefault(); if(confirm('Hapus permanen reservasi ini?')) document.getElementById('delete-form-{{ $r->id }}').submit();" style="display:inline-block;padding:6px 14px;background:#dc3545;color:#fff;border-radius:6px;font-size:12px;font-weight:600;text-decoration:none;margin-bottom:4px;">🗑️ Hapus</a>
                                <form id="delete-form-{{ $r->id }}" action="{{ route('admin.reservasi.destroy', $r->id) }}" method="POST" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="text-align:center;padding:40px 20px;background:#f8fafc;border-radius:12px;border:1px dashed #d0d9e0;">
            <div style="font-size:48px;margin-bottom:8px;">📭</div>
            <p style="margin:0;font-size:16px;font-weight:500;color:#888;">Tidak ada data reservasi.</p>
        </div>
    @endif
</div>
@endsection