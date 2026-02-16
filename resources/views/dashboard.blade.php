<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="text-3xl float">👋</div>
                <div>
                    <h2 class="text-2xl font-extrabold text-gray-900">Haloo, {{ Auth::user()->name }}!</h2>
                    <p class="mt-0.5 text-sm text-purple-400 font-medium">Selamat datang kembali~ Mau ngapain hari ini? ✨</p>
                </div>
            </div>
            <div class="text-right hidden sm:block">
                <p class="text-xs text-purple-300 font-semibold bg-purple-50 px-3 py-1.5 rounded-full">📅 {{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Quick Action -->
            <div class="mb-8">
                <a href="{{ route('pdf-tool.index') }}" class="group block">
                    <div class="relative overflow-hidden rounded-3xl gradient-brand p-8 shadow-2xl shadow-pink-500/20 card-hover border-2 border-white/20">
                        <!-- Fun decorations -->
                        <div class="absolute top-6 right-10 text-6xl opacity-10 group-hover:opacity-20 transition-opacity float">📄</div>
                        <div class="absolute top-20 right-40 text-4xl opacity-10 float-delay">✨</div>
                        <div class="absolute bottom-4 right-20 text-5xl opacity-10 group-hover:opacity-20 transition-opacity float-delay">✂️</div>

                        <div class="relative flex items-center justify-between">
                            <div>
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                                        <span class="text-3xl">✂️</span>
                                    </div>
                                    <span class="text-white/60 text-sm font-extrabold uppercase tracking-wider">Quick Action ⚡</span>
                                </div>
                                <h3 class="text-2xl font-black text-white mb-2">PDF Batch Extract Tool</h3>
                                <p class="text-white/70 text-sm font-semibold max-w-md">Upload, pilih halaman, dan proses ratusan file PDF sekaligus. Cepat, aman, auto backup! 🚀</p>
                            </div>
                            <div class="hidden sm:flex items-center">
                                <div class="w-16 h-16 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center group-hover:bg-white/20 transition-all duration-300 group-hover:scale-110">
                                    <span class="text-3xl group-hover:scale-125 transition-transform">👉</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Feature Card 1 -->
                <div class="bg-white rounded-3xl p-7 shadow-lg shadow-purple-500/5 border-2 border-purple-100/50 card-hover group">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                        <span class="text-3xl">📤</span>
                    </div>
                    <h4 class="font-extrabold text-gray-800 mb-1 text-lg">Batch Upload</h4>
                    <p class="text-sm text-purple-400 leading-relaxed font-medium">Upload banyak file PDF sekaligus. Drag & drop bisa! Tinggal taruh aja~ 🚀</p>
                </div>

                <!-- Feature Card 2 -->
                <div class="bg-white rounded-3xl p-7 shadow-lg shadow-purple-500/5 border-2 border-purple-100/50 card-hover group">
                    <div class="w-14 h-14 bg-gradient-to-br from-amber-100 to-orange-100 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300">
                        <span class="text-3xl">🛡️</span>
                    </div>
                    <h4 class="font-extrabold text-gray-800 mb-1 text-lg">Backup Otomatis</h4>
                    <p class="text-sm text-purple-400 leading-relaxed font-medium">Tenang aja, file asli selalu di-backup dulu sebelum diproses. Aman! 💪</p>
                </div>

                <!-- Feature Card 3 -->
                <div class="bg-white rounded-3xl p-7 shadow-lg shadow-purple-500/5 border-2 border-purple-100/50 card-hover group">
                    <div class="w-14 h-14 bg-gradient-to-br from-green-100 to-emerald-100 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                        <span class="text-3xl">📊</span>
                    </div>
                    <h4 class="font-extrabold text-gray-800 mb-1 text-lg">Report Detail</h4>
                    <p class="text-sm text-purple-400 leading-relaxed font-medium">Laporan lengkap per file. Kalo ada yang error, file lain tetap jalan kok~ 👌</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
