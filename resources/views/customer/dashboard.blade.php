@extends('layouts.lapangan')

@section('title', 'Dashboard - LapanganKu')

@section('content')
<!-- ============================================================ -->
<!-- HEADER DASHBOARD -->
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
            <span style="font-size: 32px;">👋</span>
            <div>
                <h1 style="margin: 0; font-size: 28px; font-weight: 700; color: #fff;">
                    Halo, {{ Auth::user()->name ?? 'Customer' }}
                </h1>
                <p style="margin: 4px 0 0 0; opacity: 0.8; font-size: 15px;">
                    Berikut ringkasan aktivitas reservasi Anda
                </p>
            </div>
        </div>
        @if(isset($membership) && $membership)
            <div style="
                margin-top: 12px;
                display: inline-block;
                background: rgba(255,215,0,0.2);
                border: 1px solid rgba(255,215,0,0.3);
                padding: 6px 18px;
                border-radius: 20px;
                font-size: 13px;
                color: #FFD700;
            ">
                ⭐ Member <strong>{{ $membership->tier->nama_tier ?? 'Aktif' }}</strong> 
                (Diskon {{ $membership->tier->diskon_persen ?? 0 }}%)
            </div>
        @endif
    </div>
</div>

<!-- ============================================================ -->
<!-- 1. RINGKASAN (STATISTIK) -->
<!-- ============================================================ -->
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 28px;">
    <div style="
        background: #fff;
        border-radius: 12px;
        padding: 20px 22px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border: 1px solid #eaeaea;
        transition: transform 0.2s;
        position: relative;
        overflow: hidden;
    ">
        <div style="position: absolute; top: -20px; right: -20px; width: 60px; height: 60px; border-radius: 50%; background: rgba(11,58,44,0.04);"></div>
        <div style="font-size: 28px; margin-bottom: 4px;">📊</div>
        <div style="font-size: 14px; color: #888; font-weight: 500;">Total Reservasi</div>
        <div style="font-size: 26px; font-weight: 700; color: #0B3A2C; margin-top: 2px;">
            {{ $totalReservasi ?? 0 }}
        </div>
    </div>

    <div style="
        background: #fff;
        border-radius: 12px;
        padding: 20px 22px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border: 1px solid #eaeaea;
        transition: transform 0.2s;
        position: relative;
        overflow: hidden;
    ">
        <div style="position: absolute; top: -20px; right: -20px; width: 60px; height: 60px; border-radius: 50%; background: rgba(198,162,77,0.08);"></div>
        <div style="font-size: 28px; margin-bottom: 4px;">💰</div>
        <div style="font-size: 14px; color: #888; font-weight: 500;">Total Pembayaran Lunas</div>
        <div style="font-size: 26px; font-weight: 700; color: #0B3A2C; margin-top: 2px;">
            Rp {{ number_format($totalPembayaran ?? 0) }}
        </div>
    </div>

    <div style="
        background: #fff;
        border-radius: 12px;
        padding: 20px 22px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border: 1px solid #eaeaea;
        transition: transform 0.2s;
        position: relative;
        overflow: hidden;
    ">
        <div style="position: absolute; top: -20px; right: -20px; width: 60px; height: 60px; border-radius: 50%; background: rgba(255,215,0,0.08);"></div>
        <div style="font-size: 28px; margin-bottom: 4px;">🏅</div>
        <div style="font-size: 14px; color: #888; font-weight: 500;">Status Membership</div>
        <div style="font-size: 22px; font-weight: 700; margin-top: 2px;">
            @if(isset($membership) && $membership)
                <span style="color: #D4A017;">{{ $membership->tier->nama_tier ?? 'Aktif' }}</span>
            @else
                <span style="color: #888; font-size: 18px;">Reguler</span>
            @endif
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- 2. RESERVASI BERIKUTNYA (MENDATANG) -->
<!-- ============================================================ -->
<div style="
    background: #fff;
    border-radius: 14px;
    padding: 24px 28px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border: 1px solid #e8ecef;
    margin-bottom: 24px;
">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">
        <h2 style="margin: 0; font-size: 20px; font-family: 'Fraunces', serif; color: #0B3A2C; display: flex; align-items: center; gap: 8px;">
            <span style="font-size: 26px;">📋</span> Reservasi Mendatang
            @if(isset($upcoming) && count($upcoming) > 0)
                <span style="
                    background: #0B3A2C;
                    color: #fff;
                    font-size: 12px;
                    padding: 2px 10px;
                    border-radius: 12px;
                    font-weight: 600;
                ">{{ count($upcoming) }}</span>
            @endif
        </h2>
        <a href="{{ route('customer.reservasi') }}" style="
            padding: 8px 20px;
            background: linear-gradient(135deg, #0B3A2C, #1C7253);
            color: #fff;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 6px rgba(11,58,44,0.2);
        ">
            ➕ Reservasi Baru
        </a>
    </div>

    @if(isset($upcoming) && count($upcoming) > 0)
        <div style="overflow-x: auto; border-radius: 10px; border: 1px solid #eaeaea;">
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <thead>
                    <tr style="background: linear-gradient(135deg, #0B3A2C, #1C7253); color: #fff;">
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600;">Kode</th>
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600;">Lapangan</th>
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600;">Tanggal</th>
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600;">Jam</th>
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600;">Total</th>
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600;">Status</th>
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600;">Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($upcoming as $index => $r)
                    <tr style="border-bottom:1px solid #eaeaea; background:@if($index%2==0) #fafcfb @else #fff @endif;">
                        <td style="padding:12px 14px; font-weight:600; color:#0B3A2C;">{{ $r->kode_reservasi }}</td>
                        <td style="padding:12px 14px;">{{ optional($r->lapangan)->nama_lapangan ?? 'N/A' }}</td>
                        <td style="padding:12px 14px;">{{ date('d M Y', strtotime($r->tanggal_main)) }}</td>
                        <td style="padding:12px 14px;">{{ substr($r->jam_mulai,0,5) }} - {{ substr($r->jam_selesai,0,5) }}</td>
                        <td style="padding:12px 14px;">Rp {{ number_format($r->total_harga) }}</td>
                        <td style="padding:12px 14px;">
                            @php
                                $statusClass = match($r->status_reservasi) {
                                    'dikonfirmasi' => 'confirmed',
                                    'pending'      => 'pending',
                                    'selesai'      => 'done',
                                    default        => 'cancelled',
                                };
                                $statusLabel = match($r->status_reservasi) {
                                    'dikonfirmasi' => '✅ Dikonfirmasi',
                                    'pending'      => '⏳ Pending',
                                    'selesai'      => '✔️ Selesai',
                                    default        => '❌ Dibatalkan',
                                };
                                $bgColor = match($statusClass) {
                                    'confirmed' => '#d4edda',
                                    'pending'   => '#fff3cd',
                                    'done'      => '#cce5ff',
                                    default     => '#f8d7da',
                                };
                                $textColor = match($statusClass) {
                                    'confirmed' => '#155724',
                                    'pending'   => '#856404',
                                    'done'      => '#004085',
                                    default     => '#721c24',
                                };
                            @endphp
                            <span style="display:inline-block;padding:4px 14px;border-radius:20px;font-size:12px;font-weight:600;background:{{ $bgColor }};color:{{ $textColor }};">{{ $statusLabel }}</span>
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
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="text-align:center;padding:40px 20px;background:#f8fafc;border-radius:12px;border:1px dashed #d0d9e0;">
            <div style="font-size:56px;margin-bottom:12px;">🏸</div>
            <h3 style="margin:0 0 4px 0; color:#0B3A2C; font-family:'Fraunces', serif;">Belum Ada Reservasi Mendatang</h3>
            <p style="margin:0;color:#888;font-size:14px;">Yuk, segera reservasi lapangan favoritmu!</p>
            <a href="{{ route('customer.reservasi') }}" style="display:inline-block;margin-top:16px;padding:10px 28px;background:#0B3A2C;color:#fff;border-radius:8px;font-weight:600;text-decoration:none;">Reservasi Sekarang</a>
        </div>
    @endif
</div>

<!-- ============================================================ -->
<!-- 3. & 4. MEMBERSHIP INFO & SEARCH LAPANGAN -->
<!-- ============================================================ -->
<div style="
    background: #fff;
    border-radius: 14px;
    padding: 24px 28px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border: 1px solid #e8ecef;
    margin-bottom: 24px;
">
    <h2 style="margin: 0 0 16px 0; font-size: 20px; font-family: 'Fraunces', serif; color: #0B3A2C; display: flex; align-items: center; gap: 8px;">
        🔍 Cari & Reservasi Lapangan
    </h2>

    <!-- Form Pencarian -->
    <form method="GET" action="{{ route('customer.dashboard') }}" style="display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; align-items: flex-end;">
        <div style="flex: 1; min-width: 150px;">
            <label style="display:block;font-weight:600;font-size:13px;color:#0B3A2C;margin-bottom:4px;">Tanggal</label>
            <input type="date" name="tanggal" value="{{ $tanggal ?? date('Y-m-d') }}" style="width:100%;padding:8px 12px;border:2px solid #e8ecef;border-radius:8px;font-size:14px;">
        </div>
        <div style="flex: 1; min-width: 150px;">
            <label style="display:block;font-weight:600;font-size:13px;color:#0B3A2C;margin-bottom:4px;">Jenis Olahraga</label>
            <select name="jenis" style="width:100%;padding:8px 12px;border:2px solid #e8ecef;border-radius:8px;font-size:14px;background:#fff;">
                <option value="">Semua</option>
                <option value="futsal" {{ request('jenis') == 'futsal' ? 'selected' : '' }}>Futsal</option>
                <option value="badminton" {{ request('jenis') == 'badminton' ? 'selected' : '' }}>Badminton</option>
                <option value="basket" {{ request('jenis') == 'basket' ? 'selected' : '' }}>Basket</option>
                <option value="tenis" {{ request('jenis') == 'tenis' ? 'selected' : '' }}>Tenis</option>
            </select>
        </div>
        <div style="flex: 0 0 auto;">
            <button type="submit" style="padding:10px 24px;background:linear-gradient(135deg,#0B3A2C,#1C7253);color:#fff;border:none;border-radius:8px;font-weight:600;font-size:14px;cursor:pointer;transition:0.2s;box-shadow:0 2px 8px rgba(11,58,44,0.2);">
                🔍 Cari
            </button>
        </div>
    </form>

    <!-- 5. Daftar Lapangan -->
    @if(isset($lapangans) && count($lapangans) > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 16px;">
            @foreach($lapangans as $lap)
                <div style="
                    background: #f8fafc;
                    border-radius: 12px;
                    padding: 16px 18px;
                    border: 1px solid #e8ecef;
                    transition: 0.2s;
                " onmouseover="this.style.borderColor='#1C7253';this.style.boxShadow='0 4px 12px rgba(0,0,0,0.06)'" onmouseout="this.style.borderColor='#e8ecef';this.style.boxShadow='none'">
                    <div style="font-weight:700;font-size:16px;color:#0B3A2C;">{{ $lap->nama_lapangan }}</div>
                    <div style="font-size:13px;color:#888;text-transform:capitalize;">{{ $lap->jenis_olahraga }}</div>
                    <div style="font-size:14px;font-weight:600;color:#1C7253;margin:4px 0;">Rp {{ number_format($lap->harga_per_jam) }}/jam</div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:8px;">
                        <span style="font-size:12px;font-weight:600;color:{{ $lap->status == 'aktif' ? '#28a745' : '#dc3545' }};">
                            {{ $lap->status == 'aktif' ? '✅ Tersedia' : '⛔ Tidak Tersedia' }}
                        </span>
                        <a href="{{ route('customer.reservasi', ['lapangan_id' => $lap->id]) }}" style="
                            padding: 6px 16px;
                            background: #0B3A2C;
                            color: #fff;
                            border-radius: 6px;
                            font-size: 12px;
                            font-weight: 600;
                            text-decoration: none;
                            transition: 0.2s;
                        " onmouseover="this.style.background='#1C7253'" onmouseout="this.style.background='#0B3A2C'">
                            Reservasi
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div style="text-align:center;padding:32px 20px;color:#888;">
            <p style="margin:0;">Tidak ada lapangan yang tersedia saat ini.</p>
        </div>
    @endif
</div>

<!-- ============================================================ -->
<!-- PROMO MEMBERSHIP (jika belum member) -->
<!-- ============================================================ -->
@if(!isset($membership) || !$membership)
    <div style="
        background: linear-gradient(135deg, #FFF9E6 0%, #FFF3D6 100%);
        border-radius: 12px;
        padding: 20px 28px;
        border-left: 6px solid #FFD700;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    ">
        <div>
            <h3 style="margin:0;font-size:18px;color:#5a4a1a;">🌟 Jadi Member Yuk!</h3>
            <p style="margin:4px 0 0 0;color:#7a6a3a;font-size:14px;">
                Dapatkan diskon spesial di setiap reservasi dengan berlangganan paket membership.
            </p>
        </div>
        <a href="{{ route('customer.membership') }}" style="
            padding:10px 28px;
            background:linear-gradient(135deg,#FFD700,#F0C800);
            color:#3a2a0a;
            border-radius:10px;
            font-weight:700;
            font-size:14px;
            text-decoration:none;
            transition:0.2s;
            box-shadow:0 2px 8px rgba(255,215,0,0.4);
            display:inline-block;
        ">
            Lihat Paket Membership
        </a>
    </div>
@endif
@endsection