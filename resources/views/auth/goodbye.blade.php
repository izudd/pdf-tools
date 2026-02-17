<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sampai Jumpa! - PDF Tools</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700,800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            overflow: hidden;
            background: #0f0a1a;
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

        /* Stars falling */
        .stars {
            position: fixed;
            inset: 0;
            pointer-events: none;
        }

        .star {
            position: absolute;
            font-size: 1.2rem;
            animation: star-fall linear infinite;
            opacity: 0;
        }

        .star:nth-child(1) { left: 5%; animation-duration: 4s; animation-delay: 0s; }
        .star:nth-child(2) { left: 15%; animation-duration: 5s; animation-delay: 0.8s; font-size: 0.8rem; }
        .star:nth-child(3) { left: 30%; animation-duration: 4.5s; animation-delay: 1.5s; }
        .star:nth-child(4) { left: 45%; animation-duration: 3.8s; animation-delay: 0.3s; font-size: 0.9rem; }
        .star:nth-child(5) { left: 55%; animation-duration: 5.2s; animation-delay: 2s; }
        .star:nth-child(6) { left: 70%; animation-duration: 4.2s; animation-delay: 1s; font-size: 1rem; }
        .star:nth-child(7) { left: 82%; animation-duration: 4.8s; animation-delay: 0.5s; }
        .star:nth-child(8) { left: 93%; animation-duration: 3.5s; animation-delay: 1.8s; font-size: 0.7rem; }

        @keyframes star-fall {
            0% { transform: translateY(-20px) rotate(0deg); opacity: 0; }
            10% { opacity: 0.8; }
            90% { opacity: 0.8; }
            100% { transform: translateY(100vh) rotate(360deg); opacity: 0; }
        }

        .container {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem;
        }

        /* Speech bubble */
        .speech-bubble {
            position: relative;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 1.2rem 2rem;
            margin-bottom: 2rem;
            max-width: 420px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(120, 80, 255, 0.2);
            animation: bubble-in 0.8s ease-out 0.5s both;
        }

        .speech-bubble::after {
            content: '';
            position: absolute;
            bottom: -12px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 14px solid transparent;
            border-right: 14px solid transparent;
            border-top: 14px solid rgba(255, 255, 255, 0.95);
        }

        @keyframes bubble-in {
            0% { opacity: 0; transform: scale(0.8) translateY(10px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }

        .speech-text {
            color: #1a1030;
            font-size: 1.1rem;
            font-weight: 600;
            line-height: 1.6;
        }

        .speech-text .highlight {
            background: linear-gradient(135deg, #7c3aed, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
        }

        /* Anime character - sad/waving goodbye */
        .anime-char {
            position: relative;
            width: 200px;
            height: 260px;
            margin-bottom: 1.5rem;
            animation: char-sway 4s ease-in-out infinite;
        }

        @keyframes char-sway {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            25% { transform: translateY(-5px) rotate(-2deg); }
            75% { transform: translateY(-5px) rotate(2deg); }
        }

        .anime-char svg {
            width: 100%;
            height: 100%;
            filter: drop-shadow(0 10px 30px rgba(120, 80, 255, 0.3));
        }

        /* Title */
        .title {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #c084fc, #60a5fa, #f472b6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
            text-align: center;
            animation: title-in 0.6s ease-out 0.3s both;
        }

        @keyframes title-in {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .subtitle {
            color: rgba(200, 180, 255, 0.5);
            font-size: 1rem;
            margin-bottom: 2.5rem;
            text-align: center;
            animation: title-in 0.6s ease-out 0.5s both;
        }

        /* Buttons */
        .btn-group {
            display: flex;
            gap: 1rem;
            animation: title-in 0.6s ease-out 0.8s both;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 32px;
            font-family: 'Outfit', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: white;
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            border: none;
            border-radius: 60px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(124, 58, 237, 0.4);
            position: relative;
        }

        .btn-back:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 8px 30px rgba(124, 58, 237, 0.5);
        }

        .btn-back::before {
            content: '';
            position: absolute;
            inset: -3px;
            border-radius: 60px;
            background: linear-gradient(135deg, #7c3aed, #ec4899, #60a5fa, #7c3aed);
            background-size: 300% 300%;
            animation: glow-spin 3s linear infinite;
            z-index: -1;
            opacity: 0.5;
            filter: blur(8px);
        }

        @keyframes glow-spin {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 32px;
            font-family: 'Outfit', sans-serif;
            font-size: 1.05rem;
            font-weight: 600;
            color: rgba(200, 180, 255, 0.7);
            background: rgba(124, 58, 237, 0.15);
            border: 1.5px solid rgba(124, 58, 237, 0.3);
            border-radius: 60px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-home:hover {
            background: rgba(124, 58, 237, 0.25);
            color: #c084fc;
            transform: translateY(-2px);
        }

        /* Sparkles */
        .sparkle {
            position: absolute;
            width: 8px;
            height: 8px;
            animation: sparkle 2s ease-in-out infinite;
        }

        .sparkle::before, .sparkle::after {
            content: '';
            position: absolute;
            background: #f0d0ff;
        }

        .sparkle::before {
            width: 100%;
            height: 2px;
            top: 50%;
            transform: translateY(-50%);
        }

        .sparkle::after {
            width: 2px;
            height: 100%;
            left: 50%;
            transform: translateX(-50%);
        }

        .sparkle:nth-child(1) { top: 15%; left: 5%; animation-delay: 0s; }
        .sparkle:nth-child(2) { top: 25%; right: 8%; animation-delay: 0.7s; }
        .sparkle:nth-child(3) { top: 55%; left: -5%; animation-delay: 1.2s; }

        @keyframes sparkle {
            0%, 100% { opacity: 0; transform: scale(0) rotate(0deg); }
            50% { opacity: 1; transform: scale(1) rotate(45deg); }
        }

        .footer {
            position: fixed;
            bottom: 1.5rem;
            left: 0;
            right: 0;
            text-align: center;
            color: rgba(200, 180, 255, 0.3);
            font-size: 0.8rem;
            animation: title-in 0.6s ease-out 1s both;
        }

        @media (max-width: 640px) {
            .title { font-size: 1.8rem; }
            .anime-char { width: 160px; height: 210px; }
            .btn-group { flex-direction: column; }
            .speech-bubble { max-width: 320px; padding: 1rem 1.5rem; }
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

    <div class="stars">
        <div class="star">&#10024;</div>
        <div class="star">&#10024;</div>
        <div class="star">&#10024;</div>
        <div class="star">&#10024;</div>
        <div class="star">&#10024;</div>
        <div class="star">&#10024;</div>
        <div class="star">&#10024;</div>
        <div class="star">&#10024;</div>
    </div>

    <div class="container">
        <!-- Speech Bubble -->
        <div class="speech-bubble">
            <p class="speech-text">
                Yaaah sudah mau pergi.. <span class="highlight">Sampai jumpa ya Admin~!</span><br>
                Jangan lupa balik lagi nanti!
            </p>
        </div>

        <!-- Anime Character - waving goodbye with sad expression -->
        <div class="anime-char">
            <div class="sparkle"></div>
            <div class="sparkle"></div>
            <div class="sparkle"></div>
            <svg viewBox="0 0 200 260" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Hair back -->
                <ellipse cx="100" cy="95" rx="72" ry="75" fill="#2d1b69"/>
                <path d="M35 100 Q25 160 40 190" stroke="#3d2680" stroke-width="8" fill="none" stroke-linecap="round"/>
                <path d="M165 100 Q175 160 160 190" stroke="#3d2680" stroke-width="8" fill="none" stroke-linecap="round"/>

                <!-- Body / Dress -->
                <path d="M60 155 Q55 175 45 220 Q50 235 100 240 Q150 235 155 220 Q145 175 140 155" fill="#7c3aed"/>
                <path d="M60 155 Q55 175 45 220 Q50 235 100 240 Q150 235 155 220 Q145 175 140 155" fill="url(#dress-gradient)"/>

                <!-- Collar -->
                <path d="M72 155 L100 175 L128 155" stroke="#fbbf24" stroke-width="3" fill="none" stroke-linecap="round"/>
                <circle cx="100" cy="175" r="4" fill="#fbbf24"/>

                <!-- Neck -->
                <rect x="90" y="145" width="20" height="15" rx="5" fill="#fce4c8"/>

                <!-- Face -->
                <ellipse cx="100" cy="100" rx="55" ry="55" fill="#fce4c8"/>

                <!-- Blush -->
                <ellipse cx="65" cy="118" rx="12" ry="7" fill="#ffb3c6" opacity="0.5"/>
                <ellipse cx="135" cy="118" rx="12" ry="7" fill="#ffb3c6" opacity="0.5"/>

                <!-- Eyes - sad/teary -->
                <!-- Left eye -->
                <ellipse cx="75" cy="102" rx="14" ry="16" fill="white"/>
                <ellipse cx="77" cy="106" rx="10" ry="12" fill="#5b21b6"/>
                <ellipse cx="79" cy="105" rx="5" ry="6" fill="#1e0a4a"/>
                <ellipse cx="82" cy="101" rx="3" ry="3.5" fill="white"/>
                <ellipse cx="74" cy="108" rx="2" ry="2" fill="white" opacity="0.7"/>
                <!-- Tear drop left -->
                <ellipse cx="68" cy="122" rx="3" ry="5" fill="#93c5fd" opacity="0.6" class="tear"/>

                <!-- Right eye -->
                <ellipse cx="125" cy="102" rx="14" ry="16" fill="white"/>
                <ellipse cx="127" cy="106" rx="10" ry="12" fill="#5b21b6"/>
                <ellipse cx="129" cy="105" rx="5" ry="6" fill="#1e0a4a"/>
                <ellipse cx="132" cy="101" rx="3" ry="3.5" fill="white"/>
                <ellipse cx="124" cy="108" rx="2" ry="2" fill="white" opacity="0.7"/>
                <!-- Tear drop right -->
                <ellipse cx="132" cy="122" rx="3" ry="5" fill="#93c5fd" opacity="0.6" class="tear"/>

                <!-- Eyebrows - worried -->
                <path d="M60 86 Q72 82 88 88" stroke="#2d1b69" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                <path d="M112 88 Q128 82 140 86" stroke="#2d1b69" stroke-width="2.5" fill="none" stroke-linecap="round"/>

                <!-- Mouth - wobbly sad -->
                <path d="M88 128 Q94 124 100 126 Q106 124 112 128" stroke="#e05080" stroke-width="2.5" fill="none" stroke-linecap="round"/>

                <!-- Nose -->
                <ellipse cx="100" cy="117" rx="2" ry="1.5" fill="#e8c4a8"/>

                <!-- Hair front -->
                <path d="M30 90 Q35 40 60 28 Q80 18 100 20 Q120 18 140 28 Q165 40 170 90 Q165 75 150 68 Q140 72 130 65 Q120 55 100 55 Q80 55 70 65 Q60 72 50 68 Q35 75 30 90Z" fill="#3d2680"/>

                <!-- Hair highlight -->
                <path d="M55 50 Q70 35 85 42" stroke="#5b38a0" stroke-width="3" fill="none" stroke-linecap="round" opacity="0.6"/>

                <!-- Ahoge - droopy -->
                <path d="M100 20 Q98 8 95 2 Q90 -4 88 0" fill="#3d2680"/>

                <!-- Left arm - holding handkerchief -->
                <path d="M60 165 Q42 175 35 195" stroke="#fce4c8" stroke-width="14" fill="none" stroke-linecap="round"/>
                <!-- Handkerchief -->
                <path d="M25 188 Q30 182 38 185 Q42 192 35 198 Q28 195 25 188Z" fill="white" opacity="0.9"/>

                <!-- Right arm - waving goodbye -->
                <path d="M140 165 Q168 148 178 120" stroke="#fce4c8" stroke-width="14" fill="none" stroke-linecap="round" class="wave-bye"/>
                <circle cx="180" cy="115" r="8" fill="#fce4c8" class="wave-bye"/>

                <!-- Legs / Shoes -->
                <ellipse cx="80" cy="242" rx="18" ry="8" fill="#5b21b6"/>
                <ellipse cx="120" cy="242" rx="18" ry="8" fill="#5b21b6"/>

                <defs>
                    <linearGradient id="dress-gradient" x1="100" y1="155" x2="100" y2="240" gradientUnits="userSpaceOnUse">
                        <stop offset="0%" stop-color="rgba(124, 58, 237, 0)"/>
                        <stop offset="100%" stop-color="rgba(88, 28, 195, 0.6)"/>
                    </linearGradient>
                </defs>
            </svg>

            <style>
                .wave-bye {
                    animation: wave-goodbye 0.8s ease-in-out infinite;
                    transform-origin: 140px 165px;
                }
                @keyframes wave-goodbye {
                    0%, 100% { transform: rotate(0deg); }
                    25% { transform: rotate(-12deg); }
                    75% { transform: rotate(12deg); }
                }
                .tear {
                    animation: tear-drop 2s ease-in-out infinite;
                }
                @keyframes tear-drop {
                    0%, 100% { opacity: 0; transform: translateY(0); }
                    30% { opacity: 0.6; }
                    70% { opacity: 0.6; }
                    100% { opacity: 0; transform: translateY(8px); }
                }
            </style>
        </div>

        <!-- Title -->
        <h1 class="title">Sampai Jumpa~!</h1>
        <p class="subtitle">Kamu berhasil logout. Terima kasih sudah menggunakan PDF Tools!</p>

        <!-- Buttons -->
        <div class="btn-group">
            <a href="{{ route('login') }}" class="btn-back">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M15 12H3"/>
                </svg>
                Login Lagi
            </a>
            <a href="{{ url('/') }}" class="btn-home">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                Beranda
            </a>
        </div>
    </div>

    <div class="footer">
        PDF Tools &copy; {{ date('Y') }} &mdash; Built with Laravel
    </div>
</body>
</html>
