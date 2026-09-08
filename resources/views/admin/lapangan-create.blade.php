@extends('layouts.lapangan')

@section('title', 'Tambah Lapangan - LapanganKu')

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
            <span style="font-size: 32px;">➕</span>
            <div>
                <h1 style="margin: 0; font-size: 28px; font-weight: 700; color: #fff;">
                    Tambah Lapangan
                </h1>
                <p style="margin: 4px 0 0 0; opacity: 0.8; font-size: 15px;">
                    Tambahkan data lapangan olahraga baru
                </p>
            </div>
        </div>
    </div>
</div>

@if(session('error'))
    <div style="background:#f8d7da;color:#721c24;padding:14px 20px;border-radius:10px;margin-bottom:20px;border-left:4px solid #dc3545;font-weight:500;">
        ❌ {{ session('error') }}
    </div>
@endif

<!-- Form -->
<div style="
    background: #fff;
    border-radius: 14px;
    padding: 28px 32px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border: 1px solid #e8ecef;
">
    <form method="POST" action="{{ route('admin.lapangan.store') }}" style="max-width: 500px;">
        @csrf

        <div class="form-group">
            <label for="nama_lapangan" style="display:block;font-weight:600;font-size:14px;color:#0B3A2C;margin-bottom:4px;">Nama Lapangan</label>
            <input type="text" name="nama_lapangan" id="nama_lapangan" value="{{ old('nama_lapangan') }}" required style="width:100%;padding:10px 14px;border:2px solid #e8ecef;border-radius:8px;font-size:14px;transition:0.2s;" onfocus="this.style.borderColor='#1C7253';this.style.boxShadow='0 0 0 4px rgba(28,114,83,0.10)'" onblur="this.style.borderColor='#e8ecef';this.style.boxShadow='none'">
        </div>

        <div class="form-group">
            <label for="jenis_olahraga" style="display:block;font-weight:600;font-size:14px;color:#0B3A2C;margin-bottom:4px;">Jenis Olahraga</label>
            <select name="jenis_olahraga" id="jenis_olahraga" required style="width:100%;padding:10px 14px;border:2px solid #e8ecef;border-radius:8px;font-size:14px;background:#fff;">
                <option value="futsal" {{ old('jenis_olahraga') == 'futsal' ? 'selected' : '' }}>Futsal</option>
                <option value="badminton" {{ old('jenis_olahraga') == 'badminton' ? 'selected' : '' }}>Badminton</option>
                <option value="basket" {{ old('jenis_olahraga') == 'basket' ? 'selected' : '' }}>Basket</option>
                <option value="padel" {{ old('jenis_olahraga') == 'padel' ? 'selected' : '' }}>Padel</option>
            </select>
        </div>

        <div class="form-group">
            <label for="harga_per_jam" style="display:block;font-weight:600;font-size:14px;color:#0B3A2C;margin-bottom:4px;">Harga per Jam (Rp)</label>
            <input type="number" name="harga_per_jam" id="harga_per_jam" value="{{ old('harga_per_jam') }}" required min="0" step="1000" style="width:100%;padding:10px 14px;border:2px solid #e8ecef;border-radius:8px;font-size:14px;transition:0.2s;" onfocus="this.style.borderColor='#1C7253';this.style.boxShadow='0 0 0 4px rgba(28,114,83,0.10)'" onblur="this.style.borderColor='#e8ecef';this.style.boxShadow='none'">
        </div>

        <div class="form-group">
            <label for="deskripsi" style="display:block;font-weight:600;font-size:14px;color:#0B3A2C;margin-bottom:4px;">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" rows="3" style="width:100%;padding:10px 14px;border:2px solid #e8ecef;border-radius:8px;font-size:14px;transition:0.2s;resize:vertical;" onfocus="this.style.borderColor='#1C7253';this.style.boxShadow='0 0 0 4px rgba(28,114,83,0.10)'" onblur="this.style.borderColor='#e8ecef';this.style.boxShadow='none'">{{ old('deskripsi') }}</textarea>
        </div>

        <div style="display:flex;gap:12px;margin-top:8px;">
            <button type="submit" style="padding:12px 28px;background:linear-gradient(135deg,#0B3A2C,#1C7253);color:#fff;border:none;border-radius:8px;font-size:15px;font-weight:700;cursor:pointer;transition:0.2s;box-shadow:0 4px 12px rgba(11,58,44,0.3);" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(11,58,44,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 12px rgba(11,58,44,0.3)'">
                💾 Simpan
            </button>
            <a href="{{ route('admin.lapangan') }}" style="padding:12px 24px;background:#e9ecef;color:#495057;border-radius:8px;font-weight:600;text-decoration:none;transition:0.2s;" onmouseover="this.style.background='#dee2e6'" onmouseout="this.style.background='#e9ecef'">Batal</a>
        </div>
    </form>
</div>
@endsection