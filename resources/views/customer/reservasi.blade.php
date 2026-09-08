@extends('layouts.lapangan')

@section('title', 'Reservasi Lapangan - LapanganKu')

@section('content')
<div class="dashboard-header">
    <h1>Reservasi Lapangan</h1>
    <p class="subtitle">Pilih tanggal dan jam untuk lapangan pilihan Anda</p>
</div>

@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

@auth
    @if(isset($membership) && $membership)
        <div class="alert alert-info" style="border-left:4px solid #FFD700; margin-bottom:20px;">
            🎉 Anda mendapatkan diskon <strong>{{ $membership->tier->diskon_persen }}%</strong> 
            dari paket <strong>{{ $membership->tier->nama_tier }}</strong>.
            <br><small style="color:#666;">Diskon otomatis diterapkan saat checkout.</small>
        </div>
    @endif
@endauth

<div class="card">
    @if(isset($lapangan) && $lapangan)
        <!-- ============================================================ -->
        <!-- HEADER LAPANGAN (PREMIUM) -->
        <!-- ============================================================ -->
        <div class="lapangan-header" style="
            background: linear-gradient(135deg, #0B3A2C 0%, #175E46 50%, #1C7253 100%);
            border-radius: 14px;
            padding: 24px 28px;
            margin-bottom: 24px;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(11,58,44,0.35);
        ">
            <!-- Aksen dekoratif -->
            <div style="position: absolute; top: -40px; right: -40px; width: 140px; height: 140px; border-radius: 50%; background: rgba(198,162,77,0.15);"></div>
            <div style="position: absolute; bottom: -60px; left: -30px; width: 100px; height: 100px; border-radius: 50%; background: rgba(198,162,77,0.08);"></div>

            <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap; position: relative; z-index: 2;">
                <!-- Ikon -->
                <div style="
                    width: 56px; height: 56px;
                    background: rgba(255,255,255,0.12);
                    border-radius: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 28px;
                    border: 1px solid rgba(198,162,77,0.3);
                ">
                    🏸
                </div>
                <div style="flex: 1;">
                    <h2 style="margin: 0; font-size: 24px; font-weight: 700; color: #fff; letter-spacing: 0.01em;">
                        {{ $lapangan->nama_lapangan }}
                    </h2>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap; margin-top: 4px;">
                        <span style="background: rgba(198,162,77,0.25); color: #F3E9CC; padding: 2px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                            {{ ucfirst($lapangan->jenis_olahraga) }}
                        </span>
                        @if(isset($diskon) && $diskon > 0)
                            <span style="background: rgba(255,215,0,0.2); color: #FFD700; padding: 2px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                🔥 Diskon {{ $diskon }}%
                            </span>
                        @endif
                    </div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 14px; opacity: 0.8;">Harga / jam</div>
                    <div style="font-size: 22px; font-weight: 700; color: #FFD700;">
                        Rp {{ number_format($lapangan->harga_per_jam) }}
                        @if(isset($diskon) && $diskon > 0)
                            <span style="font-size: 14px; color: #A8D5BA; font-weight: 400; text-decoration: line-through; opacity: 0.7; margin-left: 8px;">
                                Rp {{ number_format($lapangan->harga_per_jam) }}
                            </span>
                        @endif
                    </div>
                    @if(isset($diskon) && $diskon > 0)
                        <div style="font-size: 13px; color: #A8D5BA;">
                            Setelah diskon: <strong style="color: #fff;">Rp {{ number_format($lapangan->harga_per_jam * (1 - $diskon/100)) }}</strong>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Deskripsi -->
            <p style="margin: 12px 0 0 0; opacity: 0.8; font-size: 14px; position: relative; z-index: 2; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 12px;">
                {{ $lapangan->deskripsi }}
            </p>
        </div>

        <!-- ============================================================ -->
        <!-- PILIH TANGKAL (RAPI) -->
        <!-- ============================================================ -->
        <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap; margin-bottom: 20px; background: #f8f9fa; padding: 12px 18px; border-radius: 10px; border: 1px solid #eaeaea;">
            <form method="GET" action="{{ route('customer.reservasi') }}" style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap; flex: 1;">
                <input type="hidden" name="lapangan_id" value="{{ $lapangan->id }}">
                <label for="tanggal" style="font-weight: 600; margin: 0; font-size: 14px; color: #333;">📅 Pilih Tanggal:</label>
                <input type="date" name="tanggal" id="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" onchange="this.form.submit()" style="padding: 8px 14px; border: 1px solid #ddd; border-radius: 6px; background: #fff; font-size: 14px; cursor: pointer;">
                <button type="submit" style="padding: 8px 16px; background: #4A90D9; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600;">Cek</button>
            </form>
            <div style="background: #e8f0fe; padding: 8px 16px; border-radius: 20px; border: 1px solid #d0e0f0;">
                <span style="font-size: 14px; font-weight: 600; color: #1a3a5c;">
                    📆 {{ date('l, d M Y', strtotime(request('tanggal', date('Y-m-d')))) }}
                </span>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- FORM RESERVASI (PREMIUM) -->
        <!-- ============================================================ -->
        <div style="
            background: linear-gradient(145deg, #f8fafc, #eef2f7);
            border-radius: 14px;
            padding: 24px 28px;
            border: 1px solid #e2e8f0;
            box-shadow: inset 0 1px 4px rgba(0,0,0,0.02);
            margin-top: 8px;
        ">
            <h3 style="
                margin: 0 0 20px 0;
                font-family: 'Fraunces', serif;
                font-size: 20px;
                color: #0B3A2C;
                display: flex;
                align-items: center;
                gap: 10px;
                border-bottom: 2px solid #dce5ec;
                padding-bottom: 12px;
            ">
                <span style="font-size: 24px;">📝</span> Form Reservasi
            </h3>

            <form method="POST" action="{{ route('customer.reservasi.store') }}">
                @csrf
                <input type="hidden" name="lapangan_id" value="{{ $lapangan->id }}">

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px 24px;">
                    <!-- Tanggal Main -->
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="tanggal_main" style="display: flex; align-items: center; gap: 6px; font-weight: 600; font-size: 13px; color: #1a3a2c;">
                            <span>📅</span> Tanggal Main
                        </label>
                        <input type="date" name="tanggal_main" id="tanggal_main" value="{{ request('tanggal', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required style="
                            width: 100%;
                            padding: 10px 14px;
                            border: 1px solid #d0d9e0;
                            border-radius: 10px;
                            background: #fff;
                            font-size: 15px;
                            transition: all 0.2s;
                            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
                        ">
                    </div>

                    <!-- Jam Mulai -->
                    <div class="form-group">
                        <label for="jam_mulai" style="display: flex; align-items: center; gap: 6px; font-weight: 600; font-size: 13px; color: #1a3a2c;">
                            <span>🕒</span> Jam Mulai
                        </label>
                        <input type="time" name="jam_mulai" id="jam_mulai" required style="
                            width: 100%;
                            padding: 10px 14px;
                            border: 1px solid #d0d9e0;
                            border-radius: 10px;
                            background: #fff;
                            font-size: 15px;
                            transition: all 0.2s;
                            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
                        ">
                    </div>

                    <!-- Jam Selesai -->
                    <div class="form-group">
                        <label for="jam_selesai" style="display: flex; align-items: center; gap: 6px; font-weight: 600; font-size: 13px; color: #1a3a2c;">
                            <span>🕒</span> Jam Selesai
                        </label>
                        <input type="time" name="jam_selesai" id="jam_selesai" required style="
                            width: 100%;
                            padding: 10px 14px;
                            border: 1px solid #d0d9e0;
                            border-radius: 10px;
                            background: #fff;
                            font-size: 15px;
                            transition: all 0.2s;
                            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
                        ">
                    </div>
                </div>

                <!-- Catatan -->
                <div class="form-group" style="margin-top: 16px;">
                    <label for="catatan" style="display: flex; align-items: center; gap: 6px; font-weight: 600; font-size: 13px; color: #1a3a2c;">
                        <span>📝</span> Catatan (opsional)
                    </label>
                    <textarea name="catatan" id="catatan" rows="2" placeholder="Misal: sewa bola tambahan, lokasi parkir, dll." style="
                        width: 100%;
                        padding: 10px 14px;
                        border: 1px solid #d0d9e0;
                        border-radius: 10px;
                        background: #fff;
                        font-size: 14px;
                        transition: all 0.2s;
                        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
                        resize: vertical;
                        min-height: 60px;
                    "></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" style="
                    width: 100%;
                    padding: 14px;
                    background: linear-gradient(135deg, #0B3A2C 0%, #1C7253 100%);
                    color: #fff;
                    border: none;
                    border-radius: 12px;
                    font-size: 17px;
                    font-weight: 700;
                    cursor: pointer;
                    transition: all 0.25s ease;
                    box-shadow: 0 4px 14px rgba(11,58,44,0.35);
                    margin-top: 16px;
                    letter-spacing: 0.02em;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 10px;
                "
                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 24px rgba(11,58,44,0.45)';"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 14px rgba(11,58,44,0.35)';">
                    <span>✅</span> Buat Reservasi
                </button>
            </form>
        </div>

    @else
        <p class="text-muted">Lapangan tidak ditemukan. Silakan pilih lapangan dari halaman utama.</p>
        <a href="{{ route('home') }}" class="btn">Kembali ke Daftar Lapangan</a>
    @endif
</div>
@endsection

@push('styles')
<style>
    /* Gaya tambahan agar input lebih hidup */
    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #1C7253;
        box-shadow: 0 0 0 4px rgba(28, 114, 83, 0.12);
        transition: all 0.2s;
    }

    /* Reset gaya default alert dan card */
    .dashboard-header { margin-bottom: 24px; }
    .dashboard-header h1 { margin: 0; font-size: 28px; }
    .subtitle { color: #666; margin-top: 4px; }
    .alert-error { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 6px; margin-bottom: 16px; }
    .alert-info { background: #eef3ff; color: #004085; padding: 12px; border-radius: 6px; margin-bottom: 16px; border-left: 4px solid #4A90D9; }
    .card { background: #fff; border-radius: 10px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); margin-bottom: 20px; }
    .text-muted { color: #888; }
    hr { margin: 16px 0; border: 0; border-top: 1px solid #eee; }
</style>
@endpush