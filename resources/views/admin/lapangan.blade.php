@extends('layouts.lapangan')

@section('title', 'Kelola Lapangan - LapanganKu')

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
            <span style="font-size: 32px;">🏸</span>
            <div>
                <h1 style="margin: 0; font-size: 28px; font-weight: 700; color: #fff;">
                    Kelola Lapangan
                </h1>
                <p style="margin: 4px 0 0 0; opacity: 0.8; font-size: 15px;">
                    Tambah, ubah, atau nonaktifkan data lapangan olahraga
                </p>
            </div>
        </div>
        <a href="{{ route('admin.lapangan.create') }}" style="
            display: inline-block;
            margin-top: 12px;
            padding: 10px 24px;
            background: rgba(255,215,0,0.2);
            color: #FFD700;
            border: 1px solid rgba(255,215,0,0.3);
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.2s;
        " onmouseover="this.style.background='rgba(255,215,0,0.3)'" onmouseout="this.style.background='rgba(255,215,0,0.2)'">
            ➕ Tambah Lapangan
        </a>
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

<!-- Daftar Lapangan -->
<div style="
    background: #fff;
    border-radius: 14px;
    padding: 24px 28px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border: 1px solid #e8ecef;
">
    <h2 style="margin: 0 0 16px 0; font-size: 20px; font-family: 'Fraunces', serif; color: #0B3A2C; display: flex; align-items: center; gap: 8px;">
        📋 Daftar Lapangan
    </h2>

    @if(isset($lapangans) && count($lapangans) > 0)
        <div style="overflow-x: auto; border-radius: 10px; border: 1px solid #eaeaea;">
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <thead>
                    <tr style="background: linear-gradient(135deg, #0B3A2C, #1C7253); color: #fff;">
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600;">Nama</th>
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600;">Jenis</th>
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600;">Harga/Jam</th>
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600;">Status</th>
                        <th style="padding: 12px 14px; text-align: left; font-weight: 600;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lapangans as $index => $l)
                    <tr style="border-bottom:1px solid #eaeaea; background:@if($index%2==0) #fafcfb @else #fff @endif;">
                        <td style="padding:12px 14px; font-weight:600; color:#0B3A2C;">{{ $l->nama_lapangan }}</td>
                        <td style="padding:12px 14px; text-transform:capitalize;">{{ $l->jenis_olahraga }}</td>
                        <td style="padding:12px 14px;">Rp {{ number_format($l->harga_per_jam) }}</td>
                        <td style="padding:12px 14px;">
                            @if($l->status === 'aktif')
                                <span style="display:inline-block;padding:4px 14px;border-radius:20px;font-size:12px;font-weight:600;background:#d4edda;color:#155724;">✅ Aktif</span>
                            @else
                                <span style="display:inline-block;padding:4px 14px;border-radius:20px;font-size:12px;font-weight:600;background:#f8d7da;color:#721c24;">⛔ Nonaktif</span>
                            @endif
                        </td>
                        <td style="padding:12px 14px;">
                            <a href="{{ route('admin.lapangan.edit', $l->id) }}" style="display:inline-block;padding:6px 14px;background:#0B3A2C;color:#fff;border-radius:6px;font-size:12px;font-weight:600;text-decoration:none;margin-right:4px;">✏️ Edit</a>
                            @if($l->status === 'aktif')
                                <a href="#" onclick="event.preventDefault(); if(confirm('Nonaktifkan lapangan {{ $l->nama_lapangan }}?')) document.getElementById('toggle-form-{{ $l->id }}').submit();" style="display:inline-block;padding:6px 14px;background:#dc3545;color:#fff;border-radius:6px;font-size:12px;font-weight:600;text-decoration:none;">⛔ Nonaktifkan</a>
                                <form id="toggle-form-{{ $l->id }}" action="{{ route('admin.lapangan.toggle', $l->id) }}" method="POST" style="display:none;">@csrf</form>
                            @else
                                <a href="#" onclick="event.preventDefault(); if(confirm('Aktifkan lapangan {{ $l->nama_lapangan }}?')) document.getElementById('toggle-form-{{ $l->id }}').submit();" style="display:inline-block;padding:6px 14px;background:#28a745;color:#fff;border-radius:6px;font-size:12px;font-weight:600;text-decoration:none;">✅ Aktifkan</a>
                                <form id="toggle-form-{{ $l->id }}" action="{{ route('admin.lapangan.toggle', $l->id) }}" method="POST" style="display:none;">@csrf</form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="text-align:center;padding:40px 20px;background:#f8fafc;border-radius:12px;border:1px dashed #d0d9e0;">
            <div style="font-size:48px;margin-bottom:8px;">🏸</div>
            <p style="margin:0;font-size:16px;font-weight:500;color:#888;">Belum ada lapangan.</p>
            <a href="{{ route('admin.lapangan.create') }}" style="display:inline-block;margin-top:12px;padding:10px 24px;background:#0B3A2C;color:#fff;border-radius:8px;font-weight:600;text-decoration:none;">Tambah Lapangan</a>
        </div>
    @endif
</div>
@endsection