@extends('layouts.lapangan')

@section('title', 'Lapangan Tersedia')

@section('content')
<!-- HAPUS PESAN ERROR JIKA ADA DARI SESSION -->
@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

<div class="hero">
    <span class="eyebrow">Member Eksklusif · Reservasi Real-Time</span>
    <h1>Reservasi Lapangan Olahraga Jadi Lebih Mudah</h1>
    <p>Pesan jadwal lapangan favoritmu secara online, real-time, tanpa bentrok jadwal.</p>
    <div style="margin-top:22px;">
        @guest
            <a href="{{ route('register') }}" class="btn btn-gold">Daftar Sekarang</a>
            <a href="{{ route('login') }}" class="btn btn-secondary" style="margin-left:10px;">Masuk</a>
        @endguest
    </div>
</div>

<h2>Lapangan Tersedia</h2>
<p class="subtitle">Pilihan lapangan olahraga yang bisa Anda reservasi</p>

@if(isset($lapangans) && count($lapangans) > 0)
<div class="grid grid-3">
@foreach($lapangans as $lapangan)
    <div class="field-card">
        <div class="img-placeholder">{{ strtoupper(substr($lapangan->jenis_olahraga ?? 'N/A', 0, 3)) }}</div>
        <div class="body">
            <h3>{{ $lapangan->nama_lapangan ?? 'N/A' }}</h3>
            <div class="text-muted" style="text-transform:capitalize;">{{ $lapangan->jenis_olahraga ?? 'N/A' }}</div>
            <div class="price">Rp {{ number_format($lapangan->harga_per_jam ?? 0) }} / jam</div>
            <p class="text-muted">{{ $lapangan->deskripsi ?? '' }}</p>
            @auth
                <a href="{{ route('customer.reservasi', ['lapangan_id' => $lapangan->id]) }}" class="btn btn-block">Reservasi</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-block">Reservasi</a>
            @endauth
        </div>
    </div>
@endforeach
</div>
@else
    <p class="text-muted">Belum ada lapangan yang tersedia saat ini.</p>
@endif
@endsection