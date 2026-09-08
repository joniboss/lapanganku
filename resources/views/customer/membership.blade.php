@extends('layouts.lapangan')

@section('title', 'Membership - LapanganKu')

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
            <span style="font-size: 32px;">⭐</span>
            <div>
                <h1 style="margin: 0; font-size: 28px; font-weight: 700; color: #fff;">
                    Membership
                </h1>
                <p style="margin: 4px 0 0 0; opacity: 0.8; font-size: 15px;">
                    Berlangganan paket membership untuk mendapatkan diskon reservasi
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
@if(session('error'))
    <div style="
        background: #f8d7da;
        color: #721c24;
        padding: 14px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        border-left: 4px solid #dc3545;
        font-weight: 500;
    ">
        ❌ {{ session('error') }}
    </div>
@endif

<!-- ============================================================ -->
<!-- MEMBERSHIP AKTIF -->
<!-- ============================================================ -->
@if(isset($active) && $active)
    <div style="
        background: linear-gradient(135deg, #f0faf4, #e0f0e8);
        border-radius: 14px;
        padding: 20px 24px;
        margin-bottom: 24px;
        border-left: 6px solid #28a745;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        box-shadow: 0 2px 8px rgba(40,167,69,0.10);
    ">
        <div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 28px;">✅</span>
                <h2 style="margin: 0; font-size: 20px; font-family: 'Fraunces', serif; color: #0B3A2C;">
                    Membership Aktif: <strong style="color: #D4A017;">{{ $active->tier->nama_tier ?? 'Aktif' }}</strong>
                </h2>
            </div>
            <p style="margin: 4px 0 0 0; color: #2C7A4B; font-size: 15px;">
                🎯 Diskon <strong>{{ $active->tier->diskon_persen ?? 0 }}%</strong> berlaku hingga 
                <strong>{{ date('d M Y', strtotime($active->tanggal_berakhir)) }}</strong>
            </p>
        </div>
        <span style="
            display: inline-block;
            padding: 6px 20px;
            background: #28a745;
            color: #fff;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
        ">
            Aktif
        </span>
    </div>
@endif

<!-- ============================================================ -->
<!-- MEMBERSHIP MENUNGGU VERIFIKASI -->
<!-- ============================================================ -->
@if(isset($pending) && $pending)
    <div style="
        background: #fff8e1;
        border-radius: 14px;
        padding: 16px 22px;
        margin-bottom: 24px;
        border-left: 6px solid #ffc107;
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    ">
        <span style="font-size: 28px;">⏳</span>
        <div style="flex: 1;">
            <p style="margin: 0; font-weight: 600; color: #856404;">
                Membership Menunggu Verifikasi
            </p>
            <p style="margin: 2px 0 0 0; font-size: 14px; color: #856404;">
                Paket: <strong>{{ $pending->tier->nama_tier ?? 'N/A' }}</strong> 
                (Upload: {{ date('d M Y H:i', strtotime($pending->tanggal_bayar)) }})
            </p>
        </div>
        <span style="
            display: inline-block;
            padding: 4px 16px;
            background: #ffc107;
            color: #856404;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        ">
            Menunggu Verifikasi
        </span>
    </div>
@endif

<!-- ============================================================ -->
<!-- DAFTAR PAKET MEMBERSHIP -->
<!-- ============================================================ -->
<h2 style="
    font-size: 20px;
    font-family: 'Fraunces', serif;
    color: #0B3A2C;
    margin: 0 0 16px 0;
    display: flex;
    align-items: center;
    gap: 8px;
">
    📦 Paket Membership
</h2>

<div style="
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
">
    @foreach($tiers as $tier)
        <div style="
            background: #fff;
            border-radius: 14px;
            padding: 24px 22px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: 1px solid #e8ecef;
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative;
            overflow: hidden;
        " onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 24px rgba(0,0,0,0.10)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 10px rgba(0,0,0,0.05)';">

            <!-- Aksen -->
            <div style="position: absolute; top: -30px; right: -30px; width: 80px; height: 80px; border-radius: 50%; background: rgba(198,162,77,0.06);"></div>

            @php
                $tierColors = [
                    'Reguler' => ['bg' => '#e9ecef', 'color' => '#495057', 'badge' => '#6c757d'],
                    'Silver' => ['bg' => '#f1f1ee', 'color' => '#4B4B46', 'badge' => '#adb5bd'],
                    'Gold' => ['bg' => '#fbf0da', 'color' => '#7A5C13', 'badge' => '#FFD700'],
                    'Platinum' => ['bg' => '#d4e0f0', 'color' => '#1a3a6a', 'badge' => '#6c8fc0'],
                ];
                $tierColor = $tierColors[$tier->nama_tier] ?? ['bg' => '#e9ecef', 'color' => '#495057', 'badge' => '#6c757d'];
            @endphp

            <div style="
                display: inline-block;
                padding: 4px 14px;
                border-radius: 12px;
                background: {{ $tierColor['bg'] }};
                color: {{ $tierColor['color'] }};
                font-size: 12px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                margin-bottom: 8px;
            ">
                {{ $tier->nama_tier }}
            </div>

            <div style="font-size: 24px; font-weight: 700; color: #0B3A2C; margin-bottom: 4px;">
                {{ $tier->harga_paket > 0 ? 'Rp '.number_format($tier->harga_paket) : 'Gratis' }}
            </div>
            <div style="font-size: 13px; color: #888; margin-bottom: 12px;">
                {{ $tier->durasi_hari > 0 ? $tier->durasi_hari.' hari' : 'Tidak ada durasi' }}
                @if($tier->diskon_persen > 0)
                    <span style="color: #2C7A4B; font-weight: 600;">| Diskon {{ $tier->diskon_persen }}%</span>
                @endif
            </div>

            <p style="margin: 0 0 16px 0; color: #666; font-size: 14px; line-height: 1.5; min-height: 40px;">
                {{ $tier->deskripsi }}
            </p>

            @if($tier->harga_paket > 0)
                @if(isset($active) && $active->tier_id == $tier->id)
                    <button style="
                        width: 100%;
                        padding: 10px;
                        background: #d4edda;
                        color: #155724;
                        border: 1px solid #b7dcc8;
                        border-radius: 8px;
                        font-weight: 600;
                        font-size: 14px;
                        cursor: not-allowed;
                    " disabled>
                        ✅ Paket Aktif
                    </button>
                @elseif(isset($pending) && $pending->tier_id == $tier->id)
                    <button style="
                        width: 100%;
                        padding: 10px;
                        background: #fff3cd;
                        color: #856404;
                        border: 1px solid #e8d6a6;
                        border-radius: 8px;
                        font-weight: 600;
                        font-size: 14px;
                        cursor: not-allowed;
                    " disabled>
                        ⏳ Menunggu Verifikasi
                    </button>
                @else
                    <a href="{{ route('customer.payment.membership', $tier->id) }}" style="
                        display: block;
                        text-align: center;
                        padding: 10px;
                        background: linear-gradient(135deg, #0B3A2C, #1C7253);
                        color: #fff;
                        border-radius: 8px;
                        font-weight: 600;
                        font-size: 14px;
                        text-decoration: none;
                        transition: all 0.2s;
                    " onmouseover="this.style.boxShadow='0 4px 12px rgba(11,58,44,0.3)'" onmouseout="this.style.boxShadow='none'">
                        🚀 Berlangganan
                    </a>
                @endif
            @else
                <button style="
                    width: 100%;
                    padding: 10px;
                    background: #e9ecef;
                    color: #6c757d;
                    border: none;
                    border-radius: 8px;
                    font-weight: 600;
                    font-size: 14px;
                    cursor: not-allowed;
                " disabled>
                    Default
                </button>
            @endif
        </div>
    @endforeach
</div>

<!-- ============================================================ -->
<!-- RIWAYAT MEMBERSHIP -->
<!-- ============================================================ -->
<div style="
    background: #fff;
    border-radius: 14px;
    padding: 24px 28px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border: 1px solid #e8ecef;
">
    <h2 style="
        margin: 0 0 16px 0;
        font-size: 20px;
        font-family: 'Fraunces', serif;
        color: #0B3A2C;
        display: flex;
        align-items: center;
        gap: 8px;
    ">
        📜 Riwayat Membership
    </h2>

    @if(isset($riwayat) && count($riwayat) > 0)
        <div style="overflow-x: auto; border-radius: 10px; border: 1px solid #eaeaea;">
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <thead>
                    <tr style="background: linear-gradient(135deg, #0B3A2C, #1C7253); color: #fff;">
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600;">Paket</th>
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600;">Mulai</th>
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600;">Berakhir</th>
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600;">Status</th>
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600;">Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayat as $index => $r)
                    <tr style="
                        border-bottom: 1px solid #eaeaea;
                        background: @if($index % 2 == 0) #fafcfb @else #fff @endif;
                    ">
                        <td style="padding: 12px 14px; font-weight: 600; color: #0B3A2C;">
                            {{ $r->tier->nama_tier ?? 'N/A' }}
                        </td>
                        <td style="padding: 12px 14px;">{{ date('d M Y', strtotime($r->tanggal_mulai)) }}</td>
                        <td style="padding: 12px 14px;">{{ date('d M Y', strtotime($r->tanggal_berakhir)) }}</td>
                        <td style="padding: 12px 14px;">
                            @php
                                $statusLabel = match($r->status) {
                                    'aktif' => '✅ Aktif',
                                    'pending' => '⏳ Pending',
                                    'dibatalkan' => '❌ Dibatalkan',
                                    default => '📅 Kadaluarsa',
                                };
                                $statusClass = match($r->status) {
                                    'aktif' => '#d4edda',
                                    'pending' => '#fff3cd',
                                    'dibatalkan' => '#f8d7da',
                                    default => '#e9ecef',
                                };
                                $statusColor = match($r->status) {
                                    'aktif' => '#155724',
                                    'pending' => '#856404',
                                    'dibatalkan' => '#721c24',
                                    default => '#6c757d',
                                };
                            @endphp
                            <span style="
                                display: inline-block;
                                padding: 4px 14px;
                                border-radius: 20px;
                                font-size: 12px;
                                font-weight: 600;
                                background: {{ $statusClass }};
                                color: {{ $statusColor }};
                            ">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td style="padding: 12px 14px;">
                            @if($r->status_pembayaran === 'lunas')
                                <span style="display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #d4edda; color: #155724;">💳 Lunas</span>
                            @elseif($r->status_pembayaran === 'menunggu_verifikasi')
                                <span style="display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #fff3cd; color: #856404;">⏳ Menunggu</span>
                            @else
                                <span style="display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #f8d7da; color: #721c24;">⚠️ Belum Bayar</span>
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
            padding: 40px 20px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px dashed #d0d9e0;
        ">
            <div style="font-size: 48px; margin-bottom: 8px;">📜</div>
            <p style="margin: 0; font-size: 16px; font-weight: 500; color: #888;">Belum ada riwayat membership.</p>
        </div>
    @endif
</div>
@endsection