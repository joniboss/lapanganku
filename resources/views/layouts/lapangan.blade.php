<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LapanganKu')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS LapanganKu -->
   <link rel="stylesheet" href="/css/style.css">
    
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <a class="brand" href="{{ url('/') }}">
            <span class="brand-mark">L<span class="brand-dot">.</span>K</span>
            <span class="brand-word">Lapangan<em>Ku</em></span>
        </a>
        <nav>
            @auth
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    <a href="{{ route('admin.lapangan') }}">Kelola Lapangan</a>
                    <a href="{{ route('admin.reservasi') }}">Kelola Reservasi</a>
                    <a href="{{ route('admin.member') }}">Kelola Membership</a>
                @else
                    <a href="{{ route('customer.dashboard') }}">Dashboard</a>
                    <a href="{{ route('customer.reservasi') }}">Reservasi</a>
                    <a href="{{ route('customer.riwayat') }}">Riwayat</a>
                    <a href="{{ route('customer.membership') }}">Membership</a>
                @endif
                <span class="user-chip">{{ Auth::user()->name }} · {{ ucfirst(Auth::user()->role) }}</span>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Keluar</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
            @else
                <a href="{{ route('login') }}">Masuk</a>
                <a href="{{ route('register') }}">Daftar</a>
            @endauth
        </nav>
    </div>

    <div class="container">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        
        @yield('content')
    </div>

    <!-- Footer -->
    <footer style="text-align:center; padding:20px; margin-top:40px; border-top:1px solid #eee; color:#888; font-size:14px;">
        © {{ date('Y') }} LapanganKu – Sistem Informasi Reservasi & Manajemen Membership Lapangan Olahraga
    </footer>

    @stack('scripts')
</body>
</html>