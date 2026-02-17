<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - PDF Tools</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700,800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            background: #0f0a1a;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bg-anime {
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(120, 50, 255, 0.15) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(255, 50, 120, 0.1) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 80%, rgba(50, 120, 255, 0.1) 0%, transparent 50%),
                #0f0a1a;
        }

        .particles {
            position: fixed;
            inset: 0;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(180, 140, 255, 0.6);
            border-radius: 50%;
            animation: float-up linear infinite;
        }

        .particle:nth-child(1) { left: 10%; animation-duration: 8s; animation-delay: 0s; }
        .particle:nth-child(2) { left: 25%; animation-duration: 12s; animation-delay: 1s; width: 3px; height: 3px; }
        .particle:nth-child(3) { left: 40%; animation-duration: 9s; animation-delay: 2s; background: rgba(255, 130, 180, 0.5); }
        .particle:nth-child(4) { left: 60%; animation-duration: 11s; animation-delay: 0.5s; width: 5px; height: 5px; }
        .particle:nth-child(5) { left: 75%; animation-duration: 10s; animation-delay: 3s; }
        .particle:nth-child(6) { left: 90%; animation-duration: 7s; animation-delay: 1.5s; background: rgba(100, 180, 255, 0.5); }

        @keyframes float-up {
            0% { transform: translateY(100vh) scale(0); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-10vh) scale(1); opacity: 0; }
        }

        .login-wrapper {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem;
            width: 100%;
            max-width: 440px;
        }

        /* Anime character - peeking from behind the card */
        .char-peek {
            position: relative;
            width: 140px;
            height: 120px;
            margin-bottom: -30px;
            z-index: 20;
            animation: peek-bounce 3s ease-in-out infinite;
        }

        @keyframes peek-bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        .char-peek svg {
            width: 100%;
            height: 100%;
            filter: drop-shadow(0 5px 20px rgba(120, 80, 255, 0.3));
        }

        /* Speech bubble */
        .speech-mini {
            position: absolute;
            top: -45px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.95);
            border-radius: 14px;
            padding: 8px 16px;
            white-space: nowrap;
            font-size: 0.85rem;
            font-weight: 600;
            color: #1a1030;
            box-shadow: 0 4px 20px rgba(120, 80, 255, 0.15);
            animation: bubble-in 0.6s ease-out 0.3s both;
        }

        .speech-mini::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 10px solid transparent;
            border-right: 10px solid transparent;
            border-top: 10px solid rgba(255, 255, 255, 0.95);
        }

        .speech-mini .highlight {
            background: linear-gradient(135deg, #7c3aed, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
        }

        @keyframes bubble-in {
            0% { opacity: 0; transform: translateX(-50%) scale(0.8); }
            100% { opacity: 1; transform: translateX(-50%) scale(1); }
        }

        /* Login card */
        .login-card {
            width: 100%;
            background: rgba(20, 15, 40, 0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(124, 58, 237, 0.2);
            border-radius: 24px;
            padding: 2.5rem 2rem 2rem;
            box-shadow:
                0 20px 60px rgba(0, 0, 0, 0.4),
                0 0 40px rgba(124, 58, 237, 0.1);
            animation: card-in 0.5s ease-out 0.1s both;
        }

        @keyframes card-in {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .login-title {
            text-align: center;
            font-size: 1.6rem;
            font-weight: 800;
            background: linear-gradient(135deg, #c084fc, #f472b6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.3rem;
        }

        .login-subtitle {
            text-align: center;
            color: rgba(200, 180, 255, 0.5);
            font-size: 0.85rem;
            margin-bottom: 1.8rem;
        }

        /* Form styles */
        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-label {
            display: block;
            color: rgba(200, 180, 255, 0.7);
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 0.4rem;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            background: rgba(30, 20, 60, 0.8);
            border: 1.5px solid rgba(124, 58, 237, 0.25);
            border-radius: 12px;
            color: #e0d0ff;
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-input::placeholder {
            color: rgba(160, 140, 200, 0.4);
        }

        .form-input:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.15);
            background: rgba(40, 25, 80, 0.8);
        }

        .form-error {
            color: #f472b6;
            font-size: 0.8rem;
            margin-top: 0.3rem;
        }

        /* Remember me */
        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 1.5rem;
        }

        .remember-row input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #7c3aed;
            cursor: pointer;
        }

        .remember-row label {
            color: rgba(200, 180, 255, 0.5);
            font-size: 0.85rem;
            cursor: pointer;
        }

        /* Submit button */
        .btn-login {
            width: 100%;
            padding: 14px;
            font-family: 'Outfit', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: white;
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            border: none;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(124, 58, 237, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login::before {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: 14px;
            background: linear-gradient(135deg, #7c3aed, #ec4899, #60a5fa, #7c3aed);
            background-size: 300% 300%;
            animation: glow-spin 3s linear infinite;
            z-index: -1;
            opacity: 0;
            filter: blur(8px);
            transition: opacity 0.3s ease;
        }

        .btn-login:hover::before {
            opacity: 0.6;
        }

        @keyframes glow-spin {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Back link */
        .back-link {
            display: block;
            text-align: center;
            margin-top: 1.2rem;
            color: rgba(200, 180, 255, 0.4);
            font-size: 0.85rem;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #c084fc;
        }

        /* Session status */
        .session-status {
            background: rgba(52, 211, 153, 0.15);
            border: 1px solid rgba(52, 211, 153, 0.3);
            color: #6ee7b7;
            padding: 10px 16px;
            border-radius: 12px;
            font-size: 0.85rem;
            margin-bottom: 1rem;
            text-align: center;
        }

        @media (max-width: 480px) {
            .login-wrapper { padding: 1rem; }
            .login-card { padding: 2rem 1.5rem 1.5rem; }
            .login-title { font-size: 1.4rem; }
        }
    </style>
</head>
<body>
    <div class="bg-anime"></div>

    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="login-wrapper">
        <!-- Anime character peeking -->
        <div class="char-peek">
            <div class="speech-mini">Login sini yaa, <span class="highlight">Admin~!</span></div>
            <svg viewBox="0 0 200 170" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Hair back -->
                <ellipse cx="100" cy="70" rx="65" ry="68" fill="#2d1b69"/>
                <path d="M40 75 Q30 120 42 145" stroke="#3d2680" stroke-width="7" fill="none" stroke-linecap="round"/>
                <path d="M160 75 Q170 120 158 145" stroke="#3d2680" stroke-width="7" fill="none" stroke-linecap="round"/>

                <!-- Body -->
                <path d="M62 128 Q58 145 52 165 L148 165 Q142 145 138 128" fill="#7c3aed"/>

                <!-- Collar -->
                <path d="M75 128 L100 145 L125 128" stroke="#fbbf24" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                <circle cx="100" cy="145" r="3" fill="#fbbf24"/>

                <!-- Neck -->
                <rect x="90" y="118" width="20" height="14" rx="5" fill="#fce4c8"/>

                <!-- Face -->
                <ellipse cx="100" cy="75" rx="52" ry="52" fill="#fce4c8"/>

                <!-- Blush -->
                <ellipse cx="62" cy="90" rx="11" ry="6" fill="#ffb3c6" opacity="0.5"/>
                <ellipse cx="138" cy="90" rx="11" ry="6" fill="#ffb3c6" opacity="0.5"/>

                <!-- Eyes - happy closed (^_^) -->
                <path d="M62 80 Q72 70 82 80" stroke="#5b21b6" stroke-width="3" fill="none" stroke-linecap="round"/>
                <path d="M118 80 Q128 70 138 80" stroke="#5b21b6" stroke-width="3" fill="none" stroke-linecap="round"/>

                <!-- Mouth -->
                <path d="M88 100 Q100 112 112 100" stroke="#e05080" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                <!-- Tongue -->
                <ellipse cx="100" cy="105" rx="5" ry="3" fill="#ff8fab"/>

                <!-- Nose -->
                <ellipse cx="100" cy="92" rx="2" ry="1.5" fill="#e8c4a8"/>

                <!-- Hair front -->
                <path d="M35 65 Q40 20 65 8 Q82 0 100 2 Q118 0 135 8 Q160 20 165 65 Q160 52 148 46 Q138 50 128 43 Q118 34 100 34 Q82 34 72 43 Q62 50 52 46 Q40 52 35 65Z" fill="#3d2680"/>

                <!-- Hair highlight -->
                <path d="M58 28 Q72 16 85 22" stroke="#5b38a0" stroke-width="2.5" fill="none" stroke-linecap="round" opacity="0.6"/>

                <!-- Ahoge -->
                <path d="M100 2 Q95 -12 112 -16 Q107 -6 109 0" fill="#3d2680"/>

                <!-- Arms waving -->
                <path d="M55 140 Q30 130 18 115" stroke="#fce4c8" stroke-width="12" fill="none" stroke-linecap="round" class="wave-left"/>
                <path d="M145 140 Q170 130 182 115" stroke="#fce4c8" stroke-width="12" fill="none" stroke-linecap="round" class="wave-right"/>

                <!-- Hands -->
                <circle cx="15" cy="112" r="7" fill="#fce4c8" class="wave-left"/>
                <circle cx="185" cy="112" r="7" fill="#fce4c8" class="wave-right"/>
            </svg>

            <style>
                .wave-left {
                    animation: wave-l 1.2s ease-in-out infinite;
                    transform-origin: 55px 140px;
                }
                .wave-right {
                    animation: wave-r 1.2s ease-in-out infinite;
                    transform-origin: 145px 140px;
                }
                @keyframes wave-l {
                    0%, 100% { transform: rotate(0deg); }
                    50% { transform: rotate(8deg); }
                }
                @keyframes wave-r {
                    0%, 100% { transform: rotate(0deg); }
                    50% { transform: rotate(-8deg); }
                }
            </style>
        </div>

        <!-- Login Card -->
        <div class="login-card">
            <h1 class="login-title">Masuk ke PDF Tools</h1>
            <p class="login-subtitle">Masukkan kredensial kamu untuk melanjutkan</p>

            @if (session('status'))
                <div class="session-status">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-input" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="admin@email.com">
                    @error('email')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input class="form-input" id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password...">
                    @error('password')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="remember-row">
                    <input id="remember_me" type="checkbox" name="remember">
                    <label for="remember_me">Ingat saya</label>
                </div>

                <button type="submit" class="btn-login">Masuk Sekarang</button>
            </form>

            <a href="{{ url('/') }}" class="back-link">&larr; Kembali ke beranda</a>
        </div>
    </div>
</body>
</html>
