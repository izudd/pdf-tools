<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PDF Tool') }} - Document Processing</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Nunito', sans-serif; }
            .gradient-brand { background: linear-gradient(135deg, #f472b6 0%, #a78bfa 50%, #60a5fa 100%); }
            .gradient-fun { background: linear-gradient(135deg, #fbbf24 0%, #f97316 50%, #ef4444 100%); }
            .gradient-cool { background: linear-gradient(135deg, #34d399 0%, #22d3ee 50%, #818cf8 100%); }
            .gradient-text { background: linear-gradient(135deg, #ec4899, #8b5cf6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
            .glass-card { background: rgba(255,255,255,0.85); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
            .card-hover { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
            .card-hover:hover { transform: translateY(-4px) rotate(-0.5deg); box-shadow: 0 20px 40px -12px rgba(168, 85, 247, 0.2); }
            .animate-fade-in { animation: fadeIn 0.5s ease-out; }
            .animate-bounce-in { animation: bounceIn 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55); }
            .animate-wiggle { animation: wiggle 0.5s ease-in-out; }
            @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
            @keyframes bounceIn { from { opacity: 0; transform: scale(0.3); } to { opacity: 1; transform: scale(1); } }
            @keyframes wiggle { 0%, 100% { transform: rotate(0deg); } 25% { transform: rotate(-3deg); } 75% { transform: rotate(3deg); } }
            @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-8px); } }
            .float { animation: float 3s ease-in-out infinite; }
            .float-delay { animation: float 3s ease-in-out infinite 1.5s; }

            /* Pet Floor */
            #pet-floor {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                height: 60px;
                z-index: 9998;
                pointer-events: none;
                overflow: hidden;
            }
            .floor-pet {
                position: absolute;
                bottom: 2px;
                user-select: none;
                pointer-events: auto;
                cursor: pointer;
            }
            .floor-pet canvas {
                image-rendering: pixelated;
                image-rendering: crisp-edges;
            }
            .floor-pet .pet-label {
                position: absolute;
                bottom: calc(100% + 2px);
                left: 50%;
                transform: translateX(-50%);
                background: rgba(139, 92, 246, 0.9);
                color: white;
                font-size: 9px;
                font-weight: 800;
                padding: 2px 8px;
                border-radius: 8px;
                white-space: nowrap;
                opacity: 0;
                transition: opacity 0.2s;
                pointer-events: none;
                font-family: 'Nunito', sans-serif;
            }
            .floor-pet:hover .pet-label { opacity: 1; }
            .floor-pet .pet-bubble {
                position: absolute;
                bottom: calc(100% + 16px);
                left: 50%;
                transform: translateX(-50%);
                font-size: 12px;
                font-weight: 700;
                opacity: 0;
                pointer-events: none;
                white-space: nowrap;
                background: white;
                padding: 3px 10px;
                border-radius: 10px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.12);
                border: 1.5px solid #e9d5ff;
                font-family: 'Nunito', sans-serif;
                color: #6b21a8;
            }
            .floor-pet .pet-bubble.show {
                opacity: 1;
                animation: bubblePop 2.5s ease-out forwards;
            }
            @keyframes bubblePop {
                0% { opacity: 0; transform: translateX(-50%) translateY(5px); }
                10% { opacity: 1; transform: translateX(-50%) translateY(0); }
                80% { opacity: 1; }
                100% { opacity: 0; transform: translateX(-50%) translateY(-8px); }
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-pink-50 via-purple-50 to-blue-50">
            @include('layouts.navigation')

            @if (isset($header))
                <header class="bg-white/60 backdrop-blur-xl border-b border-purple-100/50">
                    <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main class="animate-fade-in">
                {{ $slot }}
            </main>

            <footer class="mt-auto py-6 pb-16 text-center">
                <p class="text-xs text-purple-300 font-medium">Made with <span class="text-pink-400">&hearts;</span> by Admin Team &mdash; PDF Tool v1.0</p>
            </footer>
        </div>

        <!-- Pet Floor -->
        <div id="pet-floor"></div>

        <script>
        (function() {
            // =============================================
            // PIXEL ART PET SYSTEM (like VS Code Pets!)
            // =============================================
            const SCALE = 3;     // pixel scale
            const PX = 16;       // sprite size in pixels
            const SIZE = PX * SCALE; // rendered size

            // Pixel art data - each frame is a 16x16 grid
            // Colors: 0=transparent, 1=outline, 2=body, 3=accent, 4=eyes, 5=nose/mouth, 6=inner ear
            const PALETTES = {
                orange_cat: { 1: '#4a3728', 2: '#f4a24c', 3: '#fcc580', 4: '#2d1b0e', 5: '#e8607c', 6: '#f8c4d0' },
                gray_cat:   { 1: '#2d2d3d', 2: '#8b8ba0', 3: '#b8b8cc', 4: '#1a1a2e', 5: '#e8607c', 6: '#d4a0b0' },
                black_cat:  { 1: '#1a1a2e', 2: '#3d3d54', 3: '#5a5a70', 4: '#ccff00', 5: '#e8607c', 6: '#6a4a5a' },
            };

            // Sprite frames as 16x16 pixel arrays
            // Cat walking frame 1
            const CAT_WALK_1 = [
                '0000000000000000',
                '0000011110000000',
                '0001162611000000',
                '0001122211000000',
                '0001144110000000',
                '0000155100000000',
                '0001111110000000',
                '0012222221100000',
                '0122222222100000',
                '0122233222100000',
                '0122222222100000',
                '0012222221100000',
                '0001122110000000',
                '0001100110000000',
                '0001100011000000',
                '0000000000000000',
            ];
            // Cat walking frame 2
            const CAT_WALK_2 = [
                '0000000000000000',
                '0000011110000000',
                '0001162611000000',
                '0001122211000000',
                '0001144110000000',
                '0000155100000000',
                '0001111110000000',
                '0012222221100000',
                '0122222222100000',
                '0122233222100000',
                '0122222222100000',
                '0012222221100000',
                '0001122110000000',
                '0000110011000000',
                '0001100110000000',
                '0000000000000000',
            ];
            // Cat idle (sitting)
            const CAT_IDLE = [
                '0000000000000000',
                '0000011110000000',
                '0001162611000000',
                '0001122211000000',
                '0001144110000000',
                '0000155100000000',
                '0001111110000000',
                '0012222221100000',
                '0122222222100000',
                '0122233222100000',
                '0122222222100000',
                '0122222222100000',
                '0122222222100000',
                '0011222211000000',
                '0001111110000000',
                '0000000000000000',
            ];
            // Cat sleeping
            const CAT_SLEEP_1 = [
                '0000000000000000',
                '0000000000000000',
                '0000000000000000',
                '0000000000000000',
                '0000000000000000',
                '0000000000000000',
                '0000011110000000',
                '0001162611000000',
                '0001111211000000',
                '0001111110000000',
                '0112222222110000',
                '1222233222211000',
                '1222222222221100',
                '1222222222233100',
                '0111111111111000',
                '0000000000000000',
            ];
            const CAT_SLEEP_2 = [
                '0000000000000000',
                '0000000000000000',
                '0000000000000000',
                '0000000000000000',
                '0000000000000000',
                '0000000000000000',
                '0000011110000000',
                '0001162611000000',
                '0001111211000000',
                '0001111110000000',
                '0112222222110000',
                '1222233222211000',
                '1222222222221100',
                '1222222222233100',
                '0111111111111000',
                '0000000000000000',
            ];
            // Cat walk frame 3 (different leg position)
            const CAT_WALK_3 = [
                '0000000000000000',
                '0000011110000000',
                '0001162611000000',
                '0001122211000000',
                '0001144110000000',
                '0000155100000000',
                '0001111110000000',
                '0012222221100000',
                '0122222222100000',
                '0122233222100000',
                '0122222222100000',
                '0012222221100000',
                '0001122110000000',
                '0011000011000000',
                '0010000010000000',
                '0000000000000000',
            ];
            // Cat walk frame 4
            const CAT_WALK_4 = [
                '0000000000000000',
                '0000011110000000',
                '0001162611000000',
                '0001122211000000',
                '0001144110000000',
                '0000155100000000',
                '0001111110000000',
                '0012222221100000',
                '0122222222100000',
                '0122233222100000',
                '0122222222100000',
                '0012222221100000',
                '0001122110000000',
                '0001001010000000',
                '0001001010000000',
                '0000000000000000',
            ];

            const ANIMATIONS = {
                walk: [CAT_WALK_1, CAT_WALK_2, CAT_WALK_3, CAT_WALK_4],
                idle: [CAT_IDLE],
                sleep: [CAT_SLEEP_1, CAT_SLEEP_2],
            };

            function drawSprite(ctx, frame, palette, flipped) {
                ctx.clearRect(0, 0, SIZE, SIZE);
                for (let y = 0; y < PX; y++) {
                    for (let x = 0; x < PX; x++) {
                        const c = frame[y][x];
                        if (c === '0') continue;
                        const color = palette[parseInt(c)];
                        if (!color) continue;
                        ctx.fillStyle = color;
                        const drawX = flipped ? (PX - 1 - x) * SCALE : x * SCALE;
                        ctx.fillRect(drawX, y * SCALE, SCALE, SCALE);
                    }
                }
            }

            // Pet config
            const petConfigs = [
                { name: 'Mochi', palette: PALETTES.orange_cat, speed: 0.8, startX: 120, bubbles: ['Meow~', 'Nyaa! ♡', 'Purrr~', '*stretch*', 'Mew!', '*yawn*', 'Mrow~'] },
                { name: 'Kuro', palette: PALETTES.black_cat, speed: 1.1, startX: 400, bubbles: ['...', 'Hmph.', '*stare*', 'Nya.', '*lick paw*', 'Meow.', '...zzz'] },
                { name: 'Sakura', palette: PALETTES.gray_cat, speed: 0.6, startX: 700, bubbles: ['Nyaa~ ♡', 'Purr...', '*roll*', 'Mrow!', '*blink*', 'Mew~ ♡', '*nuzzle*'] },
            ];

            const floorEl = document.getElementById('pet-floor');

            const pets = petConfigs.map((config, i) => {
                const wrapper = document.createElement('div');
                wrapper.className = 'floor-pet';
                wrapper.style.left = config.startX + 'px';

                const canvas = document.createElement('canvas');
                canvas.width = SIZE;
                canvas.height = SIZE;
                canvas.style.width = SIZE + 'px';
                canvas.style.height = SIZE + 'px';

                const label = document.createElement('div');
                label.className = 'pet-label';
                label.textContent = config.name;

                const bubble = document.createElement('div');
                bubble.className = 'pet-bubble';

                wrapper.appendChild(canvas);
                wrapper.appendChild(label);
                wrapper.appendChild(bubble);
                floorEl.appendChild(wrapper);

                // Click handler
                wrapper.addEventListener('click', () => {
                    const msg = config.bubbles[Math.floor(Math.random() * config.bubbles.length)];
                    bubble.textContent = msg;
                    bubble.classList.remove('show');
                    void bubble.offsetWidth;
                    bubble.classList.add('show');
                    setTimeout(() => bubble.classList.remove('show'), 2500);
                });

                return {
                    el: wrapper,
                    canvas: canvas,
                    ctx: canvas.getContext('2d'),
                    bubbleEl: bubble,
                    config: config,
                    x: config.startX,
                    dir: Math.random() > 0.5 ? 1 : -1,
                    state: 'walk',
                    frame: 0,
                    frameTimer: 0,
                    stateTimer: 0,
                    nextStateTime: 3000 + Math.random() * 4000,
                    bubbleTimer: 8000 + Math.random() * 15000,
                };
            });

            let lastT = performance.now();

            function loop(now) {
                const dt = now - lastT;
                lastT = now;
                const sw = window.innerWidth;

                pets.forEach(pet => {
                    pet.stateTimer += dt;
                    pet.frameTimer += dt;
                    pet.bubbleTimer -= dt;

                    // Auto speech bubble
                    if (pet.bubbleTimer <= 0) {
                        pet.bubbleTimer = 12000 + Math.random() * 18000;
                        const msg = pet.config.bubbles[Math.floor(Math.random() * pet.config.bubbles.length)];
                        pet.bubbleEl.textContent = msg;
                        pet.bubbleEl.classList.remove('show');
                        void pet.bubbleEl.offsetWidth;
                        pet.bubbleEl.classList.add('show');
                        setTimeout(() => pet.bubbleEl.classList.remove('show'), 2500);
                    }

                    // State machine
                    if (pet.stateTimer >= pet.nextStateTime) {
                        pet.stateTimer = 0;
                        pet.frame = 0;
                        pet.frameTimer = 0;
                        const r = Math.random();

                        if (pet.state === 'walk') {
                            if (r < 0.30) {
                                pet.state = 'idle';
                                pet.nextStateTime = 2000 + Math.random() * 4000;
                            } else if (r < 0.50) {
                                pet.state = 'sleep';
                                pet.nextStateTime = 5000 + Math.random() * 8000;
                            } else {
                                pet.dir *= -1;
                                pet.nextStateTime = 3000 + Math.random() * 5000;
                            }
                        } else if (pet.state === 'idle') {
                            if (r < 0.65) {
                                pet.state = 'walk';
                                pet.nextStateTime = 4000 + Math.random() * 5000;
                            } else {
                                pet.state = 'sleep';
                                pet.nextStateTime = 5000 + Math.random() * 6000;
                            }
                        } else {
                            pet.state = 'idle';
                            pet.nextStateTime = 2000 + Math.random() * 3000;
                            // Show wake up
                            pet.bubbleEl.textContent = '*yawn*';
                            pet.bubbleEl.classList.remove('show');
                            void pet.bubbleEl.offsetWidth;
                            pet.bubbleEl.classList.add('show');
                            setTimeout(() => pet.bubbleEl.classList.remove('show'), 2000);
                        }
                    }

                    // Animation frame rate
                    const anim = ANIMATIONS[pet.state];
                    const frameInterval = pet.state === 'walk' ? 180 : (pet.state === 'sleep' ? 600 : 400);
                    if (pet.frameTimer >= frameInterval) {
                        pet.frameTimer = 0;
                        pet.frame = (pet.frame + 1) % anim.length;
                    }

                    // Movement
                    if (pet.state === 'walk') {
                        pet.x += pet.config.speed * pet.dir * (dt / 16);
                        if (pet.x > sw - SIZE - 10) { pet.x = sw - SIZE - 10; pet.dir = -1; }
                        if (pet.x < 10) { pet.x = 10; pet.dir = 1; }
                    }

                    // Sleep zzz
                    if (pet.state === 'sleep' && Math.random() < 0.003) {
                        pet.bubbleEl.textContent = '💤';
                        pet.bubbleEl.classList.remove('show');
                        void pet.bubbleEl.offsetWidth;
                        pet.bubbleEl.classList.add('show');
                        setTimeout(() => pet.bubbleEl.classList.remove('show'), 2000);
                    }

                    // Draw sprite
                    const flipped = pet.dir === -1;
                    drawSprite(pet.ctx, anim[pet.frame], pet.config.palette, flipped);

                    pet.el.style.left = pet.x + 'px';
                });

                requestAnimationFrame(loop);
            }

            requestAnimationFrame(loop);
        })();
        </script>
    </body>
</html>
