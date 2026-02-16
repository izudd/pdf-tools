<x-app-layout>
    <x-slot name="header">
        @php
            $successCount = collect($results)->where('status', 'success')->count();
            $errorCount = collect($results)->where('status', 'error')->count();
            $allSuccess = $errorCount === 0;
        @endphp
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 {{ $allSuccess ? 'bg-gradient-to-br from-green-400 to-emerald-500' : 'bg-gradient-to-br from-amber-400 to-orange-500' }} rounded-2xl flex items-center justify-center shadow-lg {{ $allSuccess ? 'shadow-green-500/20' : 'shadow-orange-500/20' }} animate-bounce-in">
                <span class="text-2xl">{{ $allSuccess ? '🎉' : '😰' }}</span>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold text-gray-900">{{ $allSuccess ? 'Yeay! Semua Berhasil!' : 'Hmm, Ada Yang Error...' }}</h2>
                <p class="text-sm text-purple-400 font-medium">{{ $allSuccess ? 'Semua file udah selesai diproses nih~ ✨' : 'Tapi jangan khawatir, yang lain berhasil kok!' }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Step Indicator -->
            <div class="mb-8 flex items-center justify-center gap-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-pink-500 to-purple-500 text-white flex items-center justify-center shadow-md">
                        <span class="text-sm">✅</span>
                    </div>
                    <span class="text-xs font-extrabold text-purple-600">Upload</span>
                </div>
                <div class="w-10 h-1 bg-gradient-to-r from-purple-400 to-orange-400 rounded-full"></div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white flex items-center justify-center shadow-md">
                        <span class="text-sm">✅</span>
                    </div>
                    <span class="text-xs font-extrabold text-orange-500">Review</span>
                </div>
                <div class="w-10 h-1 bg-gradient-to-r from-orange-400 to-green-400 rounded-full"></div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 text-white flex items-center justify-center shadow-md shadow-green-500/30 animate-bounce-in">
                        <span>🎉</span>
                    </div>
                    <span class="text-xs font-extrabold text-green-600">Selesai!</span>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-3xl p-5 border-2 border-purple-100/50 text-center shadow-lg shadow-purple-500/5 card-hover">
                    <span class="text-2xl">📋</span>
                    <p class="text-3xl font-black text-gray-900 mt-1">{{ count($results) }}</p>
                    <p class="text-xs text-purple-400 mt-1 font-bold">Total File</p>
                </div>
                <div class="bg-white rounded-3xl p-5 border-2 border-green-100 text-center shadow-lg shadow-green-500/5 card-hover">
                    <span class="text-2xl">✅</span>
                    <p class="text-3xl font-black text-green-600 mt-1">{{ $successCount }}</p>
                    <p class="text-xs text-green-500 mt-1 font-bold">Berhasil</p>
                </div>
                <div class="bg-white rounded-3xl p-5 border-2 {{ $errorCount > 0 ? 'border-red-100' : 'border-gray-100' }} text-center shadow-lg {{ $errorCount > 0 ? 'shadow-red-500/5' : 'shadow-gray-500/5' }} card-hover">
                    <span class="text-2xl">{{ $errorCount > 0 ? '😭' : '😃' }}</span>
                    <p class="text-3xl font-black {{ $errorCount > 0 ? 'text-red-500' : 'text-gray-300' }} mt-1">{{ $errorCount }}</p>
                    <p class="text-xs {{ $errorCount > 0 ? 'text-red-400' : 'text-gray-400' }} mt-1 font-bold">Gagal</p>
                </div>
            </div>

            <!-- Results List -->
            <div class="bg-white rounded-3xl shadow-lg shadow-purple-500/5 border-2 border-purple-100/50 overflow-hidden">
                <div class="px-8 pt-6 pb-4 border-b border-purple-100/50 bg-gradient-to-r from-purple-50/50 to-pink-50/50">
                    <h3 class="text-sm font-extrabold text-gray-800">📄 Detail per file</h3>
                </div>

                <div class="divide-y divide-purple-50">
                    @foreach($results as $index => $res)
                        <div class="px-8 py-4 flex items-center gap-4 hover:bg-purple-50/30 transition-colors" style="animation: fadeIn 0.4s ease-out {{ $index * 0.08 }}s both">
                            @if($res['status'] == 'success')
                                <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-emerald-500 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-md shadow-green-500/20">
                                    <span class="text-white text-sm">✅</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-extrabold text-gray-800 truncate">{{ $res['file'] }}</p>
                                    <p class="text-xs text-purple-400 mt-0.5 font-medium">
                                        {{ $res['original'] }} &rarr; halaman {{ $res['page_extracted'] }} ✨
                                    </p>
                                </div>
                                <a href="{{ route('pdf-tool.download', ['path' => $res['download_path']]) }}"
                                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-purple-50 to-pink-50 text-purple-700 text-xs font-extrabold rounded-xl border border-purple-200/50 hover:from-purple-100 hover:to-pink-100 hover:scale-105 active:scale-95 transition-all duration-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Download
                                </a>
                            @else
                                <div class="w-10 h-10 bg-gradient-to-br from-red-400 to-pink-500 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-md shadow-red-500/20">
                                    <span class="text-white text-sm">😭</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ $res['file'] }}</p>
                                    <p class="text-xs text-red-400 mt-0.5 font-medium">{{ $res['message'] }}</p>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-500 border border-red-100">Error</span>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Download All ZIP -->
                @if($successCount > 1)
                    <div class="px-8 py-5 border-t-2 border-green-100 bg-gradient-to-r from-green-50/80 to-emerald-50/80">
                        <form action="{{ route('pdf-tool.download-zip') }}" method="POST" class="flex items-center justify-between">
                            @csrf
                            @foreach($results as $res)
                                @if($res['status'] == 'success')
                                    <input type="hidden" name="paths[]" value="{{ $res['download_path'] }}">
                                @endif
                            @endforeach
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-teal-500 rounded-2xl flex items-center justify-center shadow-md shadow-green-500/20">
                                    <span class="text-xl">📦</span>
                                </div>
                                <div>
                                    <p class="text-sm font-extrabold text-gray-800">Download Semua 🎉</p>
                                    <p class="text-xs text-green-500 font-semibold">{{ $successCount }} file dalam satu ZIP</p>
                                </div>
                            </div>
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-sm font-extrabold rounded-2xl shadow-lg shadow-green-500/25 hover:shadow-xl hover:shadow-green-500/30 hover:scale-105 active:scale-95 transition-all duration-200">
                                <span class="text-lg">📦</span>
                                Download ZIP
                            </button>
                        </form>
                    </div>
                @endif

                <!-- Action Bar -->
                <div class="px-8 py-5 bg-gradient-to-r from-purple-50/80 to-pink-50/80 border-t border-purple-100/50 flex items-center justify-between">
                    <a href="{{ route('pdf-tool.index') }}" class="inline-flex items-center gap-2 text-sm text-purple-400 hover:text-purple-600 transition-colors font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali
                    </a>
                    <a href="{{ route('pdf-tool.index') }}"
                       class="inline-flex items-center gap-2 px-7 py-3 gradient-brand text-white text-sm font-extrabold rounded-2xl shadow-lg shadow-pink-500/25 hover:shadow-xl hover:shadow-pink-500/30 hover:scale-105 active:scale-95 transition-all duration-200">
                        <span class="text-lg">🚀</span>
                        Upload Lagi!
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
