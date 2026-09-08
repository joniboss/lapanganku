<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar - LapanganKu</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ============================================================ */
        /* RESET & BASE (SAMA DENGAN LOGIN) */
        /* ============================================================ */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Manrope', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f7f3e9;
            background-image:
                radial-gradient(circle at 10% 20%, rgba(11,58,44,0.04) 0%, transparent 50%),
                radial-gradient(circle at 90% 80%, rgba(198,162,77,0.06) 0%, transparent 50%);
            padding: 20px;
        }

        .auth-wrapper {
            display: flex;
            max-width: 1000px;
            width: 100%;
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(11,58,44,0.15), 0 4px 16px rgba(0,0,0,0.04);
            overflow: hidden;
            border: 1px solid rgba(198,162,77,0.15);
        }

        /* ============================================================ */
        /* LEFT SIDE - BRAND */
        /* ============================================================ */
        .auth-brand {
            flex: 1;
            background: linear-gradient(145deg, #0B3A2C 0%, #175E46 50%, #1C7253 100%);
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #fff;
            position: relative;
            overflow: hidden;
            min-height: 400px;
        }
        .auth-brand::before {
            content: "";
            position: absolute;
            top: -80px;
            right: -80px;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: rgba(198,162,77,0.08);
        }
        .auth-brand::after {
            content: "";
            position: absolute;
            bottom: -60px;
            left: -60px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(198,162,77,0.05);
        }
        .auth-brand .brand-mark {
            font-family: 'Fraunces', serif;
            font-size: 56px;
            font-weight: 700;
            color: #FFD700;
            position: relative;
            z-index: 2;
            text-shadow: 0 2px 20px rgba(255,215,0,0.2);
        }
        .auth-brand .brand-mark span { color: #fff; }
        .auth-brand .brand-tagline {
            font-size: 14px;
            opacity: 0.7;
            margin-top: 8px;
            letter-spacing: 0.1em;
            position: relative;
            z-index: 2;
        }
        .auth-brand .brand-desc {
            font-size: 16px;
            opacity: 0.8;
            margin-top: 12px;
            text-align: center;
            line-height: 1.6;
            max-width: 300px;
            position: relative;
            z-index: 2;
            font-weight: 300;
        }
        .auth-brand .brand-icon {
            font-size: 72px;
            margin-bottom: 16px;
            position: relative;
            z-index: 2;
        }

        /* ============================================================ */
        /* RIGHT SIDE - FORM */
        /* ============================================================ */
        .auth-form {
            flex: 1;
            padding: 48px 44px;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 400px;
        }
        .auth-form .form-header h2 {
            font-family: 'Fraunces', serif;
            font-size: 28px;
            color: #0B3A2C;
            margin: 0 0 4px 0;
        }
        .auth-form .form-header p {
            color: #888;
            font-size: 15px;
            margin: 0 0 28px 0;
        }

        .form-group {
            margin-bottom: 16px;
        }
        .form-group label {
            display: block;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #0B3A2C;
            margin-bottom: 6px;
        }
        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e8ecef;
            border-radius: 10px;
            font-size: 15px;
            font-family: 'Manrope', sans-serif;
            transition: all 0.25s ease;
            background: #fafcfb;
            color: #1a1a1a;
        }
        .form-group input:focus {
            outline: none;
            border-color: #1C7253;
            box-shadow: 0 0 0 4px rgba(28,114,83,0.10);
            background: #fff;
        }
        .form-group input::placeholder {
            color: #bbb;
        }

        .text-error {
            color: #dc3545;
            font-size: 13px;
            margin-top: 4px;
            display: block;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px 16px;
            border-radius: 8px;
            border-left: 4px solid #dc3545;
            margin-bottom: 16px;
            font-size: 14px;
        }

        .btn-auth {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #0B3A2C 0%, #1C7253 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            font-family: 'Manrope', sans-serif;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 4px 14px rgba(11,58,44,0.3);
            margin-top: 8px;
            letter-spacing: 0.02em;
        }
        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(11,58,44,0.4);
        }

        .auth-switch {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #888;
        }
        .auth-switch a {
            color: #1C7253;
            font-weight: 700;
            text-decoration: none;
        }
        .auth-switch a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .auth-wrapper { flex-direction: column; border-radius: 16px; max-width: 480px; }
            .auth-brand { padding: 32px 24px; min-height: 200px; }
            .auth-brand .brand-mark { font-size: 40px; }
            .auth-brand .brand-icon { font-size: 48px; }
            .auth-brand .brand-desc { max-width: 100%; font-size: 14px; }
            .auth-form { padding: 32px 24px; min-height: auto; }
            .auth-form .form-header h2 { font-size: 24px; }
        }
    </style>
</head>
<body>

<div class="auth-wrapper">
    <!-- ============================================================ -->
    <!-- LEFT SIDE - BRAND -->
    <!-- ============================================================ -->
    <div class="auth-brand">
        <div class="brand-icon">🏸</div>
        <div class="brand-mark">
            L<span style="color: #FFD700;">.</span>K
        </div>
        <div class="brand-tagline">LapanganKu</div>
        <div class="brand-desc">
            Bergabunglah dengan komunitas<br>
            <strong style="color: #FFD700; font-weight: 600;">Premium · Real-Time · Eksklusif</strong>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- RIGHT SIDE - FORM -->
    <!-- ============================================================ -->
    <div class="auth-form">
        <div class="form-header">
            <h2>✨ Daftar Akun</h2>
            <p>Buat akun untuk reservasi lapangan favoritmu</p>
        </div>

        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" name="name" id="name" placeholder="Budi Pratama" value="{{ old('name') }}" required autofocus>
                @error('name')
                    <span class="text-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="budi@email.com" value="{{ old('email') }}" required>
                @error('email')
                    <span class="text-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <input type="password" name="password" id="password" placeholder="••••••••" required>
                @error('password')
                    <span class="text-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="••••••••" required>
                @error('password_confirmation')
                    <span class="text-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit -->
            <button type="submit" class="btn-auth">
                🚀 Daftar Sekarang
            </button>

            <!-- Login Link -->
            <p class="auth-switch">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
            </p>
        </form>
    </div>
</div>

</body>
</html>