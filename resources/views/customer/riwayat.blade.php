@extends('layouts.lapangan')

@section('title', 'Riwayat Reservasi - LapanganKu')

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
            <span style="font-size: 32px;">📜</span>
            <div>
                <h1 style="margin: 0; font-size: 28px; font-weight: 700; color: #fff;">
                    Riwayat Reservasi
                </h1>
                <p style="margin: 4px 0 0 0; opacity: 0.8; font-size: 15px;">
                    Semua reservasi yang pernah Anda buat
                </p>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div style="
        background: #d4edda;
        color: #155724;
        padding: 14px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        border-left: 4px solid #28a745;
        font-weight: 500;
    ">
        ✅ {{ session('success') }}
    </div>
@endif

<!-- ============================================================ -->
<!-- TABEL RIWAYAT -->
<!-- ============================================================ -->
<div style="
    background: #fff;
    border-radius: 14px;
    padding: 24px 28px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border: 1px solid #e8ecef;
">
    @if(isset($reservasis) && count($reservasis) > 0)
        <div style="overflow-x: auto; border-radius: 10px; border: 1px solid #eaeaea;">
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <thead>
                    <tr style="background: linear-gradient(135deg, #0B3A2C, #1C7253); color: #fff;">
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600; letter-spacing: 0.03em;">Kode</th>
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600; letter-spacing: 0.03em;">Lapangan</th>
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600; letter-spacing: 0.03em;">Tanggal</th>
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600; letter-spacing: 0.03em;">Jam</th>
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600; letter-spacing: 0.03em;">Total</th>
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600; letter-spacing: 0.03em;">Status</th>
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600; letter-spacing: 0.03em;">Pembayaran</th>
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600; letter-spacing: 0.03em;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservasis as $index => $r)
                    <tr style="
                        border-bottom: 1px solid #eaeaea;
                        background: @if($index % 2 == 0) #fafcfb @else #fff @endif;
                        transition: background 0.15s;
                    " onmouseover="this.style.background='#f0f7f4'" onmouseout="this.style.background='@if($index % 2 == 0) #fafcfb @else #fff @endif'">
                        <td style="padding: 12px 14px; font-weight: 600; color: #0B3A2C;">{{ $r->kode_reservasi }}</td>
                        <td style="padding: 12px 14px;">
                            <span style="display: flex; align-items: center; gap: 6px;">
                                <span style="font-size: 16px;">🏸</span>
                                {{ optional($r->lapangan)->nama_lapangan ?? 'N/A' }}
                            </span>
                        </td>
                        <td style="padding: 12px 14px;">{{ date('d M Y', strtotime($r->tanggal_main)) }}</td>
                        <td style="padding: 12px 14px;">{{ substr($r->jam_mulai,0,5) }} - {{ substr($r->jam_selesai,0,5) }}</td>
                        <td style="padding: 12px 14px; font-weight: 600; color: #0B3A2C;">
                            Rp {{ number_format($r->total_harga) }}
                        </td>
                        <td style="padding: 12px 14px;">
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
                            <span style="
                                display: inline-block;
                                padding: 4px 14px;
                                border-radius: 20px;
                                font-size: 12px;
                                font-weight: 600;
                                background: {{ $bgColor }};
                                color: {{ $textColor }};
                                border: 1px solid {{ $textColor }}22;
                            ">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td style="padding: 12px 14px;">
                            @if($r->status_pembayaran === 'lunas')
                                <span style="display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #d4edda; color: #155724; border: 1px solid #b7dcc8;">💳 Lunas</span>
                            @elseif($r->status_pembayaran === 'menunggu_verifikasi')
                                <span style="display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #fff3cd; color: #856404; border: 1px solid #e8d6a6;">⏳ Menunggu</span>
                            @else
                                <span style="display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">⚠️ Belum Bayar</span>
                            @endif
                        </td>
                        <td style="padding: 12px 14px;">
                            @if($r->status_pembayaran === 'belum_bayar')
                                <a href="{{ route('customer.payment', $r->id) }}" style="
                                    display: inline-block;
                                    padding: 6px 16px;
                                    background: linear-gradient(135deg, #0B3A2C, #1C7253);
                                    color: #fff;
                                    border-radius: 6px;
                                    font-size: 12px;
                                    font-weight: 600;
                                    text-decoration: none;
                                    transition: all 0.2s;
                                " onmouseover="this.style.boxShadow='0 4px 12px rgba(11,58,44,0.3)'" onmouseout="this.style.boxShadow='none'">
                                    💳 Bayar
                                </a>
                            @elseif($r->status_pembayaran === 'menunggu_verifikasi')
                                <span style="color: #888; font-size: 13px;">⏳ Menunggu</span>
                            @else
                                <span style="color: #bbb; font-size: 13px;">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="
            text-align: center;
            padding: 60px 20px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px dashed #d0d9e0;
        ">
            <div style="font-size: 64px; margin-bottom: 12px;">🏸</div>
            <h3 style="margin: 0 0 4px 0; color: #0B3A2C; font-family: 'Fraunces', serif;">Belum Ada Reservasi</h3>
            <p style="margin: 0; color: #888; font-size: 14px;">Yuk, segera reservasi lapangan favoritmu!</p>
            <a href="{{ route('customer.reservasi') }}" style="
                display: inline-block;
                margin-top: 16px;
                padding: 10px 28px;
                background: #0B3A2C;
                color: #fff;
                border-radius: 8px;
                font-weight: 600;
                text-decoration: none;
            ">
                Reservasi Sekarang
            </a>
        </div>
    @endif
</div>
@endsection