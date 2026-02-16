<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PDF Tools - Selamat Datang!</title>
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

        /* Animated gradient background */
        .bg-anime {
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(120, 50, 255, 0.15) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(255, 50, 120, 0.1) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 80%, rgba(50, 120, 255, 0.1) 0%, transparent 50%),
                #0f0a1a;
        }

        /* Floating particles */
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
        .particle:nth-child(2) { left: 20%; animation-duration: 12s; animation-delay: 1s; width: 3px; height: 3px; }
        .particle:nth-child(3) { left: 35%; animation-duration: 9s; animation-delay: 2s; background: rgba(255, 130, 180, 0.5); }
        .particle:nth-child(4) { left: 50%; animation-duration: 11s; animation-delay: 0.5s; width: 5px; height: 5px; }
        .particle:nth-child(5) { left: 65%; animation-duration: 10s; animation-delay: 3s; }
        .particle:nth-child(6) { left: 75%; animation-duration: 7s; animation-delay: 1.5s; background: rgba(100, 180, 255, 0.5); }
        .particle:nth-child(7) { left: 85%; animation-duration: 13s; animation-delay: 2.5s; width: 3px; height: 3px; }
        .particle:nth-child(8) { left: 92%; animation-duration: 9s; animation-delay: 0.8s; }

        @keyframes float-up {
            0% { transform: translateY(100vh) scale(0); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-10vh) scale(1); opacity: 0; }
        }

        /* Main container */
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

        /* Anime character container */
        .anime-char {
            position: relative;
            width: 200px;
            height: 260px;
            margin-bottom: 1rem;
            animation: char-bounce 3s ease-in-out infinite;
        }

        @keyframes char-bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }

        /* Character SVG styles */
        .anime-char svg {
            width: 100%;
            height: 100%;
            filter: drop-shadow(0 10px 30px rgba(120, 80, 255, 0.3));
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

        /* Typing animation */
        .typing-dots {
            display: inline-flex;
            gap: 4px;
            margin-left: 4px;
            vertical-align: middle;
        }

        .typing-dots span {
            width: 6px;
            height: 6px;
            background: #7c3aed;
            border-radius: 50%;
            animation: typing-bounce 1.4s ease-in-out infinite;
        }

        .typing-dots span:nth-child(2) { animation-delay: 0.2s; }
        .typing-dots span:nth-child(3) { animation-delay: 0.4s; }

        @keyframes typing-bounce {
            0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
            30% { transform: translateY(-6px); opacity: 1; }
        }

        /* Title */
        .title {
            font-size: 2.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #c084fc, #f472b6, #60a5fa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
            text-align: center;
            animation: title-in 0.6s ease-out 0.3s both;
            letter-spacing: -0.02em;
        }

        @keyframes title-in {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .subtitle {
            color: rgba(200, 180, 255, 0.6);
            font-size: 1rem;
            font-weight: 400;
            margin-bottom: 2.5rem;
            text-align: center;
            animation: title-in 0.6s ease-out 0.5s both;
        }

        /* CTA Button */
        .cta-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 40px;
            font-family: 'Outfit', sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: white;
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            border: none;
            border-radius: 60px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            animation: btn-in 0.6s ease-out 0.8s both;
            box-shadow:
                0 4px 20px rgba(124, 58, 237, 0.4),
                0 0 0 0 rgba(124, 58, 237, 0.4);
        }

        .cta-btn:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow:
                0 8px 30px rgba(124, 58, 237, 0.5),
                0 0 0 6px rgba(124, 58, 237, 0.15);
        }

        .cta-btn:active {
            transform: translateY(0) scale(0.98);
        }

        .cta-btn .arrow {
            transition: transform 0.3s ease;
        }

        .cta-btn:hover .arrow {
            transform: translateX(4px);
        }

        @keyframes btn-in {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* Glow ring pulse */
        .cta-btn::before {
            content: '';
            position: absolute;
            inset: -3px;
            border-radius: 60px;
            background: linear-gradient(135deg, #7c3aed, #ec4899, #60a5fa, #7c3aed);
            background-size: 300% 300%;
            animation: glow-spin 3s linear infinite;
            z-index: -1;
            opacity: 0.6;
            filter: blur(8px);
        }

        @keyframes glow-spin {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Sparkle effect on character */
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

        .sparkle:nth-child(1) { top: 10%; left: 5%; animation-delay: 0s; }
        .sparkle:nth-child(2) { top: 20%; right: 10%; animation-delay: 0.7s; }
        .sparkle:nth-child(3) { top: 60%; left: -5%; animation-delay: 1.2s; }

        @keyframes sparkle {
            0%, 100% { opacity: 0; transform: scale(0) rotate(0deg); }
            50% { opacity: 1; transform: scale(1) rotate(45deg); }
        }

        /* Footer */
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

        /* Responsive */
        @media (max-width: 640px) {
            .title { font-size: 2rem; }
            .anime-char { width: 160px; height: 210px; }
            .speech-bubble { padding: 1rem 1.5rem; max-width: 320px; }
            .speech-text { font-size: 0.95rem; }
            .cta-btn { padding: 14px 32px; font-size: 1rem; }
        }
    </style>
</head>
<body>
    <div class="bg-anime"></div>

    <!-- Floating particles -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="container">
        <!-- Speech Bubble -->
        <div class="speech-bubble">
            <p class="speech-text">
                Hai Admin! <span class="highlight">Selamat datang~</span> <br>
                Aku siap bantu kamu kelola PDF hari ini!
                <span class="typing-dots">
                    <span></span><span></span><span></span>
                </span>
            </p>
        </div>

        <!-- Anime Character (cute chibi with PDF) -->
        <div class="anime-char">
            <div class="sparkle"></div>
            <div class="sparkle"></div>
            <div class="sparkle"></div>
            <svg viewBox="0 0 200 260" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Hair back -->
                <ellipse cx="100" cy="95" rx="72" ry="75" fill="#2d1b69"/>
                <!-- Hair strands back -->
                <path d="M35 100 Q25 160 40 190" stroke="#3d2680" stroke-width="8" fill="none" stroke-linecap="round"/>
                <path d="M165 100 Q175 160 160 190" stroke="#3d2680" stroke-width="8" fill="none" stroke-linecap="round"/>

                <!-- Body / Dress -->
                <path d="M60 155 Q55 175 45 220 Q50 235 100 240 Q150 235 155 220 Q145 175 140 155" fill="#7c3aed" />
                <path d="M60 155 Q55 175 45 220 Q50 235 100 240 Q150 235 155 220 Q145 175 140 155" fill="url(#dress-gradient)" />

                <!-- Collar -->
                <path d="M72 155 L100 175 L128 155" stroke="#fbbf24" stroke-width="3" fill="none" stroke-linecap="round"/>
                <circle cx="100" cy="175" r="4" fill="#fbbf24"/>

                <!-- Neck -->
                <rect x="90" y="145" width="20" height="15" rx="5" fill="#fce4c8"/>

                <!-- Face -->
                <ellipse cx="100" cy="100" rx="55" ry="55" fill="#fce4c8"/>

                <!-- Blush -->
                <ellipse cx="65" cy="115" rx="12" ry="7" fill="#ffb3c6" opacity="0.5"/>
                <ellipse cx="135" cy="115" rx="12" ry="7" fill="#ffb3c6" opacity="0.5"/>

                <!-- Eyes -->
                <!-- Left eye -->
                <ellipse cx="75" cy="102" rx="14" ry="16" fill="white"/>
                <ellipse cx="77" cy="104" rx="10" ry="12" fill="#5b21b6"/>
                <ellipse cx="79" cy="102" rx="5" ry="6" fill="#1e0a4a"/>
                <ellipse cx="82" cy="98" rx="3" ry="3.5" fill="white"/>
                <ellipse cx="74" cy="106" rx="2" ry="2" fill="white" opacity="0.7"/>

                <!-- Right eye -->
                <ellipse cx="125" cy="102" rx="14" ry="16" fill="white"/>
                <ellipse cx="127" cy="104" rx="10" ry="12" fill="#5b21b6"/>
                <ellipse cx="129" cy="102" rx="5" ry="6" fill="#1e0a4a"/>
                <ellipse cx="132" cy="98" rx="3" ry="3.5" fill="white"/>
                <ellipse cx="124" cy="106" rx="2" ry="2" fill="white" opacity="0.7"/>

                <!-- Eyebrows -->
                <path d="M60 84 Q75 78 88 84" stroke="#2d1b69" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                <path d="M112 84 Q125 78 140 84" stroke="#2d1b69" stroke-width="2.5" fill="none" stroke-linecap="round"/>

                <!-- Mouth (happy smile) -->
                <path d="M90 125 Q100 135 110 125" stroke="#e05080" stroke-width="2.5" fill="none" stroke-linecap="round"/>

                <!-- Nose -->
                <ellipse cx="100" cy="117" rx="2" ry="1.5" fill="#e8c4a8"/>

                <!-- Hair front -->
                <path d="M30 90 Q35 40 60 28 Q80 18 100 20 Q120 18 140 28 Q165 40 170 90 Q165 75 150 68 Q140 72 130 65 Q120 55 100 55 Q80 55 70 65 Q60 72 50 68 Q35 75 30 90Z" fill="#3d2680"/>

                <!-- Hair highlight -->
                <path d="M55 50 Q70 35 85 42" stroke="#5b38a0" stroke-width="3" fill="none" stroke-linecap="round" opacity="0.6"/>

                <!-- Ahoge (antenna hair) -->
                <path d="M100 20 Q95 5 110 0 Q105 10 108 18" fill="#3d2680"/>

                <!-- Arms -->
                <!-- Left arm holding PDF -->
                <path d="M60 165 Q40 180 35 200" stroke="#fce4c8" stroke-width="14" fill="none" stroke-linecap="round"/>
                <!-- Right arm waving -->
                <path d="M140 165 Q165 155 172 135" stroke="#fce4c8" stroke-width="14" fill="none" stroke-linecap="round" class="wave-arm"/>

                <!-- Hand wave -->
                <circle cx="174" cy="130" r="8" fill="#fce4c8" class="wave-arm"/>

                <!-- PDF document in left hand -->
                <g transform="translate(15, 185) rotate(-10)">
                    <rect x="0" y="0" width="35" height="45" rx="3" fill="white" stroke="#e5e7eb" stroke-width="1"/>
                    <rect x="0" y="0" width="35" height="12" rx="3" fill="#ef4444"/>
                    <text x="17.5" y="9" font-size="7" font-weight="bold" fill="white" text-anchor="middle" font-family="Outfit, sans-serif">PDF</text>
                    <rect x="5" y="17" width="25" height="2" rx="1" fill="#e5e7eb"/>
                    <rect x="5" y="22" width="20" height="2" rx="1" fill="#e5e7eb"/>
                    <rect x="5" y="27" width="22" height="2" rx="1" fill="#e5e7eb"/>
                    <rect x="5" y="32" width="15" height="2" rx="1" fill="#e5e7eb"/>
                </g>

                <!-- Legs / Shoes -->
                <ellipse cx="80" cy="242" rx="18" ry="8" fill="#5b21b6"/>
                <ellipse cx="120" cy="242" rx="18" ry="8" fill="#5b21b6"/>

                <!-- Gradients -->
                <defs>
                    <linearGradient id="dress-gradient" x1="100" y1="155" x2="100" y2="240" gradientUnits="userSpaceOnUse">
                        <stop offset="0%" stop-color="rgba(124, 58, 237, 0)" />
                        <stop offset="100%" stop-color="rgba(88, 28, 195, 0.6)" />
                    </linearGradient>
                </defs>
            </svg>

            <style>
                .wave-arm {
                    animation: wave 1.5s ease-in-out infinite;
                    transform-origin: 140px 165px;
                }
                @keyframes wave {
                    0%, 100% { transform: rotate(0deg); }
                    25% { transform: rotate(-10deg); }
                    75% { transform: rotate(10deg); }
                }
            </style>
        </div>

        <!-- Title -->
        <h1 class="title">PDF Tools</h1>
        <p class="subtitle">Kelola file PDF kamu dengan mudah & cepat</p>

        <!-- CTA Button -->
        <a href="{{ route('login') }}" class="cta-btn">
            Masuk Sekarang
            <svg class="arrow" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14M12 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    <div class="footer">
        PDF Tools &copy; {{ date('Y') }} &mdash; Built with Laravel
    </div>
</body>
</html>
