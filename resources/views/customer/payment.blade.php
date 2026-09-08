@extends('layouts.lapangan')

@section('title', 'Pembayaran Reservasi - LapanganKu')

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
            <span style="font-size: 32px;">💳</span>
            <div>
                <h1 style="margin: 0; font-size: 28px; font-weight: 700; color: #fff;">
                    Pembayaran Reservasi
                </h1>
                <p style="margin: 4px 0 0 0; opacity: 0.8; font-size: 15px;">
                    Selesaikan pembayaran untuk reservasi Anda
                </p>
            </div>
        </div>
    </div>
</div>

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
@if($errors->any())
    <div style="
        background: #f8d7da;
        color: #721c24;
        padding: 14px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        border-left: 4px solid #dc3545;
        font-weight: 500;
    ">
        @foreach($errors->all() as $err)
            <p style="margin: 0;">❌ {{ $err }}</p>
        @endforeach
    </div>
@endif

<div style="
    background: #fff;
    border-radius: 14px;
    padding: 28px 32px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border: 1px solid #e8ecef;
    margin-bottom: 24px;
">
    @if(isset($reservasi) && $reservasi)
        <!-- ============================================================ -->
        <!-- DETAIL RESERVASI + RINCIAN -->
        <!-- ============================================================ -->
        <h2 style="
            margin: 0 0 16px 0;
            font-size: 20px;
            font-family: 'Fraunces', serif;
            color: #0B3A2C;
            display: flex;
            align-items: center;
            gap: 8px;
        ">
            📋 Detail Reservasi & Rincian Pembayaran
        </h2>

        <div style="
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 20px;
            border: 1px solid #e8ecef;
        ">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px 24px;">
                <div><span style="font-weight: 600; color: #0B3A2C;">Kode Reservasi</span></div>
                <div style="font-weight: 600; color: #1C7253;">{{ $reservasi->kode_reservasi }}</div>

                <div><span style="font-weight: 600; color: #0B3A2C;">Lapangan</span></div>
                <div>{{ optional($reservasi->lapangan)->nama_lapangan ?? 'N/A' }}</div>

                <div><span style="font-weight: 600; color: #0B3A2C;">Tanggal Main</span></div>
                <div>{{ date('d M Y', strtotime($reservasi->tanggal_main)) }}</div>

                <div><span style="font-weight: 600; color: #0B3A2C;">Jam Main</span></div>
                <div>{{ substr($reservasi->jam_mulai,0,5) }} - {{ substr($reservasi->jam_selesai,0,5) }}</div>
            </div>
        </div>

        <!-- RINCIAN PERHITUNGAN -->
        <div style="
            background: linear-gradient(135deg, #f0f7f4, #e4f0ea);
            border-radius: 12px;
            padding: 20px 24px;
            border-left: 4px solid #1C7253;
            margin-bottom: 24px;
        ">
            <h4 style="margin: 0 0 12px 0; color: #0B3A2C; font-family: 'Fraunces', serif;">💰 Rincian Pembayaran</h4>
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <tr>
                    <td style="padding: 6px 0; color: #555;">Harga per Jam</td>
                    <td style="padding: 6px 0; text-align: right; font-weight: 500;">Rp {{ number_format($reservasi->harga_satuan) }}</td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; color: #555;">Durasi Sewa</td>
                    <td style="padding: 6px 0; text-align: right; font-weight: 500;">{{ $reservasi->durasi_jam }} jam</td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; color: #555;">Subtotal</td>
                    <td style="padding: 6px 0; text-align: right; font-weight: 500;">Rp {{ number_format($reservasi->harga_satuan * $reservasi->durasi_jam) }}</td>
                </tr>
                @if($reservasi->diskon_persen > 0)
                <tr>
                    <td style="padding: 6px 0; color: #2C7A4B; font-weight: 600;">
                        🎯 Diskon {{ $reservasi->diskon_persen }}% (Membership)
                    </td>
                    <td style="padding: 6px 0; text-align: right; color: #2C7A4B; font-weight: 600;">
                        - Rp {{ number_format(($reservasi->harga_satuan * $reservasi->durasi_jam) * ($reservasi->diskon_persen / 100)) }}
                    </td>
                </tr>
                @endif
                <tr style="border-top: 2px solid #1C7253;">
                    <td style="padding: 10px 0 0 0; font-weight: 700; font-size: 16px; color: #0B3A2C;">
                        <strong>Total yang Harus Dibayar</strong>
                    </td>
                    <td style="padding: 10px 0 0 0; text-align: right; font-weight: 700; font-size: 18px; color: #1C7253;">
                        Rp {{ number_format($reservasi->total_harga) }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- ============================================================ -->
        <!-- METODE PEMBAYARAN -->
        <!-- ============================================================ -->
        <h2 style="
            margin: 0 0 16px 0;
            font-size: 20px;
            font-family: 'Fraunces', serif;
            color: #0B3A2C;
            display: flex;
            align-items: center;
            gap: 8px;
        ">
            🏦 Metode Pembayaran
        </h2>
        <p style="color: #666; margin-bottom: 16px;">Silakan transfer ke rekening berikut:</p>

        <div style="
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        ">
            <!-- BCA -->
            <div style="
                background: #f8fafc;
                padding: 16px 20px;
                border-radius: 10px;
                text-align: center;
                border: 1px solid #e8ecef;
                transition: all 0.2s;
            " onmouseover="this.style.borderColor='#1C7253'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.06)'" onmouseout="this.style.borderColor='#e8ecef'; this.style.boxShadow='none'">
                <img src="{{ asset('assets/img/banks/bca.png') }}" alt="BCA" style="height:44px; margin-bottom: 6px;">
                <div style="font-weight: 700; font-size: 15px; color: #0B3A2C;">Bank BCA</div>
                <div style="font-size: 13px; color: #555;">123-456-7890</div>
                <div style="font-size: 12px; color: #888;">a.n. PT. Tepok Tunggal Setia</div>
            </div>

            <!-- Mandiri -->
            <div style="
                background: #f8fafc;
                padding: 16px 20px;
                border-radius: 10px;
                text-align: center;
                border: 1px solid #e8ecef;
                transition: all 0.2s;
            " onmouseover="this.style.borderColor='#1C7253'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.06)'" onmouseout="this.style.borderColor='#e8ecef'; this.style.boxShadow='none'">
                <img src="{{ asset('assets/img/banks/mandiri.png') }}" alt="Mandiri" style="height:44px; margin-bottom: 6px;">
                <div style="font-weight: 700; font-size: 15px; color: #0B3A2C;">Bank Mandiri</div>
                <div style="font-size: 13px; color: #555;">987-654-3210</div>
                <div style="font-size: 12px; color: #888;">a.n. PT. Tepok Tunggal Setia</div>
            </div>
        </div>

        <!-- QRIS -->
        <div style="
            text-align: center;
            padding: 20px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e8ecef;
            margin-bottom: 24px;
        ">
            <p style="font-weight: 600; color: #0B3A2C; margin: 0 0 8px 0;">📱 Scan QRIS berikut untuk pembayaran</p>
            <img src="{{ asset('assets/img/banks/qris.png') }}" 
                 alt="QRIS PT. Tepok Tunggal Setia" 
                 style="border: 1px solid #ddd; border-radius: 10px; max-width: 180px;">
            <p style="font-size: 13px; color: #888; margin-top: 6px;">a.n. PT. Tepok Tunggal Setia</p>
        </div>

        <!-- ============================================================ -->
        <!-- UPLOAD BUKTI -->
        <!-- ============================================================ -->
        <h2 style="
            margin: 0 0 16px 0;
            font-size: 20px;
            font-family: 'Fraunces', serif;
            color: #0B3A2C;
            display: flex;
            align-items: center;
            gap: 8px;
        ">
            📤 Upload Bukti Pembayaran
        </h2>

        <form method="POST" action="{{ route('customer.payment.upload', $reservasi->id) }}" enctype="multipart/form-data">
            @csrf
            <div style="display: grid; gap: 16px; max-width: 500px;">
                <div>
                    <label for="metode_pembayaran" style="display: block; font-weight: 600; font-size: 14px; color: #0B3A2C; margin-bottom: 4px;">Metode Pembayaran</label>
                    <select name="metode_pembayaran" id="metode_pembayaran" required style="
                        width: 100%;
                        padding: 10px 14px;
                        border: 1px solid #d0d9e0;
                        border-radius: 8px;
                        font-size: 14px;
                        background: #fff;
                    ">
                        <option value="">-- Pilih Metode --</option>
                        <option value="BCA">Bank BCA</option>
                        <option value="Mandiri">Bank Mandiri</option>
                        <option value="QRIS">QRIS</option>
                    </select>
                </div>

                <div>
                    <label for="nama_rekening" style="display: block; font-weight: 600; font-size: 14px; color: #0B3A2C; margin-bottom: 4px;">Nama Pemilik Rekening/E-Wallet</label>
                    <input type="text" name="nama_rekening" id="nama_rekening" placeholder="Nama sesuai rekening" required style="
                        width: 100%;
                        padding: 10px 14px;
                        border: 1px solid #d0d9e0;
                        border-radius: 8px;
                        font-size: 14px;
                    ">
                </div>

                <div>
                    <label for="bukti" style="display: block; font-weight: 600; font-size: 14px; color: #0B3A2C; margin-bottom: 4px;">Upload Bukti Transfer (JPG/PNG/PDF, max 2MB)</label>
                    <input type="file" name="bukti" id="bukti" accept=".jpg,.jpeg,.png,.pdf" required style="
                        width: 100%;
                        padding: 8px;
                        border: 1px solid #d0d9e0;
                        border-radius: 8px;
                        font-size: 14px;
                        background: #fff;
                    ">
                </div>

                <button type="submit" style="
                    padding: 12px 24px;
                    background: linear-gradient(135deg, #0B3A2C, #1C7253);
                    color: #fff;
                    border: none;
                    border-radius: 8px;
                    font-size: 16px;
                    font-weight: 700;
                    cursor: pointer;
                    transition: all 0.2s;
                    box-shadow: 0 4px 12px rgba(11,58,44,0.3);
                    margin-top: 4px;
                " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 24px rgba(11,58,44,0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(11,58,44,0.3)'">
                    💳 Kirim Bukti Pembayaran
                </button>
            </div>
        </form>
    @else
        <p style="text-align: center; padding: 40px 0; color: #888;">Reservasi tidak ditemukan.</p>
    @endif
</div>
@endsection