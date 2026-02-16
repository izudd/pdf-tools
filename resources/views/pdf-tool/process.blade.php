<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg shadow-orange-500/20 float">
                <span class="text-2xl">🔍</span>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold text-gray-900">Review & Pilih Halaman</h2>
                <p class="text-sm text-purple-400 font-medium">Preview dulu, baru pilih yang mau diambil 👀</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6" x-data="{
        activeFile: 0,
        selections: {!! json_encode(array_fill(0, count($uploadedFiles), null)) !!},
        get completedCount() { return this.selections.filter(s => s !== null).length; },
        get allSelected() { return this.completedCount === {{ count($uploadedFiles) }}; }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Step Indicator -->
            <div class="mb-6 flex items-center justify-center gap-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-pink-500 to-purple-500 text-white flex items-center justify-center shadow-md">
                        <span class="text-sm">✅</span>
                    </div>
                    <span class="text-xs font-extrabold text-purple-600">Upload</span>
                </div>
                <div class="w-10 h-1 bg-gradient-to-r from-purple-400 to-orange-400 rounded-full"></div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white flex items-center justify-center text-xs font-black shadow-md shadow-orange-500/30 animate-bounce-in">
                        <span>👀</span>
                    </div>
                    <span class="text-xs font-extrabold text-orange-500">Review</span>
                </div>
                <div class="w-10 h-1 bg-gray-200 rounded-full"></div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center text-xs font-black">3</div>
                    <span class="text-xs text-gray-400 font-bold">Selesai</span>
                </div>
            </div>

            <form action="{{ route('pdf-tool.process') }}" method="POST">
                @csrf

                <div class="flex flex-col lg:flex-row gap-5">

                    <!-- LEFT: File List Sidebar -->
                    <div class="lg:w-64 flex-shrink-0">
                        <div class="bg-white rounded-3xl shadow-lg shadow-purple-500/5 border-2 border-purple-100/50 overflow-hidden lg:sticky lg:top-4">
                            <div class="px-4 py-3 border-b border-purple-100/50 bg-gradient-to-r from-purple-50/80 to-pink-50/80">
                                <div class="flex items-center justify-between">
                                    <p class="text-xs font-extrabold text-purple-600 uppercase tracking-wider">📋 File</p>
                                    <span class="text-xs font-extrabold px-2 py-0.5 rounded-full"
                                          :class="completedCount === {{ count($uploadedFiles) }} ? 'bg-green-100 text-green-700' : 'bg-purple-100 text-purple-700'"
                                          x-text="completedCount + '/{{ count($uploadedFiles) }}'"></span>
                                </div>
                                <!-- Progress bar -->
                                <div class="mt-2 h-2 bg-purple-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500 rounded-full transition-all duration-500 ease-out"
                                         :style="'width:' + (completedCount / {{ count($uploadedFiles) }} * 100) + '%'"></div>
                                </div>
                            </div>

                            <div class="divide-y divide-purple-50 max-h-[60vh] overflow-y-auto">
                                @foreach($uploadedFiles as $index => $file)
                                    <button type="button"
                                            @click="activeFile = {{ $index }}"
                                            :class="activeFile === {{ $index }} ? 'bg-purple-50 border-l-4 border-purple-500' : 'hover:bg-pink-50/50 border-l-4 border-transparent'"
                                            class="w-full text-left px-4 py-3 transition-all duration-150">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-black transition-all"
                                                 :class="selections[{{ $index }}] !== null ? 'bg-gradient-to-br from-green-400 to-emerald-500 text-white shadow-md shadow-green-500/30 scale-110' : (activeFile === {{ $index }} ? 'bg-purple-100 text-purple-600' : 'bg-gray-100 text-gray-400')">
                                                <template x-if="selections[{{ $index }}] !== null">
                                                    <span>✅</span>
                                                </template>
                                                <template x-if="selections[{{ $index }}] === null">
                                                    <span>{{ $index + 1 }}</span>
                                                </template>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-xs font-bold text-gray-800 truncate">{{ $file['original_name'] }}</p>
                                                <p class="text-[10px] font-semibold">
                                                    <span x-show="selections[{{ $index }}] !== null" class="text-green-500" x-text="'Hal. ' + selections[{{ $index }}] + ' ✨'"></span>
                                                    <span x-show="selections[{{ $index }}] === null" class="text-purple-300">{{ $file['total_pages'] }} hal</span>
                                                </p>
                                            </div>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT: Preview + Page Selector -->
                    <div class="flex-1 min-w-0">
                        @foreach($uploadedFiles as $index => $file)
                            <div x-show="activeFile === {{ $index }}"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-data="{
                                    currentPage: 1,
                                    totalPages: {{ $file['total_pages'] }},
                                    previewUrl: '{{ route('pdf-tool.preview') }}?path={{ urlencode($file['path']) }}&page=1',
                                    loadPreview(page) {
                                        this.currentPage = page;
                                        this.previewUrl = '{{ route('pdf-tool.preview') }}?path={{ urlencode($file['path']) }}&page=' + page;
                                    },
                                    selectPage(page) {
                                        this.loadPreview(page);
                                        selections[{{ $index }}] = page;
                                    }
                                 }">

                                <div class="bg-white rounded-3xl shadow-lg shadow-purple-500/5 border-2 border-purple-100/50 overflow-hidden">
                                    <!-- File Header -->
                                    <div class="px-5 py-3 border-b border-purple-100/50 bg-gradient-to-r from-orange-50/50 to-amber-50/50 flex items-center justify-between">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-9 h-9 bg-gradient-to-br from-red-400 to-pink-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md shadow-red-500/20">
                                                <span class="text-white text-sm">📄</span>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-extrabold text-gray-800 truncate">{{ $file['original_name'] }}</p>
                                                <p class="text-xs text-purple-400 font-semibold">{{ $file['total_pages'] }} halaman 📖</p>
                                            </div>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-purple-100 text-purple-700">
                                            {{ $index + 1 }}/{{ count($uploadedFiles) }}
                                        </span>
                                    </div>

                                    <div class="flex flex-col lg:flex-row">
                                        <!-- Preview Area -->
                                        <div class="flex-1 p-4">
                                            <div class="bg-gray-900 rounded-2xl overflow-hidden relative shadow-xl" style="height: 420px;">
                                                <iframe :src="previewUrl"
                                                        class="w-full h-full border-0"
                                                        x-show="previewUrl">
                                                </iframe>
                                            </div>

                                            <!-- Nav Controls -->
                                            <div class="flex items-center justify-center gap-3 mt-3">
                                                <button type="button"
                                                        @click="if(currentPage > 1) loadPreview(currentPage - 1)"
                                                        :disabled="currentPage <= 1"
                                                        :class="currentPage <= 1 ? 'opacity-30' : 'hover:bg-purple-100 hover:scale-110'"
                                                        class="w-9 h-9 rounded-xl bg-purple-50 flex items-center justify-center transition-all duration-200">
                                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                                                </button>
                                                <span class="text-xs font-bold text-purple-400 bg-purple-50 px-3 py-1.5 rounded-lg">
                                                    <span x-text="currentPage" class="font-extrabold text-purple-700"></span>
                                                    <span class="text-purple-300">/ {{ $file['total_pages'] }}</span>
                                                </span>
                                                <button type="button"
                                                        @click="if(currentPage < totalPages) loadPreview(currentPage + 1)"
                                                        :disabled="currentPage >= totalPages"
                                                        :class="currentPage >= totalPages ? 'opacity-30' : 'hover:bg-purple-100 hover:scale-110'"
                                                        class="w-9 h-9 rounded-xl bg-purple-50 flex items-center justify-center transition-all duration-200">
                                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Page Selector Panel -->
                                        <div class="lg:w-52 p-4 border-t lg:border-t-0 lg:border-l border-purple-100/50 flex flex-col">
                                            <p class="text-[10px] font-extrabold text-purple-400 uppercase tracking-widest mb-3">🎯 Pilih Halaman</p>

                                            <div class="grid grid-cols-5 gap-1.5 mb-4 max-h-[280px] overflow-y-auto">
                                                @for($p = 1; $p <= $file['total_pages']; $p++)
                                                    <button type="button"
                                                            @click="selectPage({{ $p }})"
                                                            :class="{
                                                                'bg-gradient-to-br from-green-400 to-emerald-500 text-white ring-2 ring-green-300 ring-offset-1 shadow-md shadow-green-500/30 scale-110': selections[{{ $index }}] === {{ $p }},
                                                                'bg-purple-100 text-purple-700 font-extrabold ring-1 ring-purple-300': currentPage === {{ $p }} && selections[{{ $index }}] !== {{ $p }},
                                                                'bg-gray-50 text-gray-500 hover:bg-purple-50 hover:text-purple-600 hover:scale-105': currentPage !== {{ $p }} && selections[{{ $index }}] !== {{ $p }}
                                                            }"
                                                            class="w-full aspect-square rounded-xl text-xs font-bold transition-all duration-150 flex items-center justify-center">
                                                        {{ $p }}
                                                    </button>
                                                @endfor
                                            </div>

                                            <div class="mt-auto">
                                                <div class="p-3 rounded-2xl border-2 transition-all duration-200"
                                                     :class="selections[{{ $index }}] !== null ? 'bg-green-50 border-green-200' : 'bg-purple-50/50 border-dashed border-purple-200'">
                                                    <template x-if="selections[{{ $index }}] !== null">
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-lg">🎉</span>
                                                            <p class="text-xs font-extrabold text-green-700">Halaman <span x-text="selections[{{ $index }}]"></span> dipilih!</p>
                                                        </div>
                                                    </template>
                                                    <template x-if="selections[{{ $index }}] === null">
                                                        <p class="text-[11px] text-purple-400 text-center font-semibold">👈 Klik nomor halaman</p>
                                                    </template>
                                                </div>

                                                @if($index < count($uploadedFiles) - 1)
                                                    <button type="button"
                                                            x-show="selections[{{ $index }}] !== null"
                                                            x-transition
                                                            @click="activeFile = {{ $index + 1 }}"
                                                            class="w-full mt-2 flex items-center justify-center gap-1.5 px-3 py-2.5 bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 text-xs font-extrabold rounded-xl hover:from-purple-200 hover:to-pink-200 hover:scale-[1.02] active:scale-95 transition-all duration-200">
                                                        File selanjutnya 👉
                                                    </button>
                                                @endif
                                            </div>

                                            <input type="hidden"
                                                   name="files[{{ $index }}][page]"
                                                   :value="selections[{{ $index }}]"
                                                   required>
                                            <input type="hidden" name="files[{{ $index }}][path]" value="{{ $file['path'] }}">
                                            <input type="hidden" name="files[{{ $index }}][original_name]" value="{{ $file['original_name'] }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Bottom Action Bar -->
                <div class="mt-6 bg-white rounded-3xl shadow-lg shadow-purple-500/5 border-2 border-purple-100/50 px-6 py-4 flex items-center justify-between">
                    <a href="{{ route('pdf-tool.index') }}" class="inline-flex items-center gap-2 text-sm text-purple-400 hover:text-purple-600 transition-colors font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Kembali
                    </a>

                    <div class="flex items-center gap-4">
                        <span class="text-xs font-bold" x-show="!allSelected"
                              :class="completedCount > 0 ? 'text-purple-400' : 'text-gray-400'">
                            <span x-text="completedCount"></span>/{{ count($uploadedFiles) }} file dipilih
                        </span>
                        <span class="text-xs text-green-500 font-extrabold flex items-center gap-1" x-show="allSelected">
                            🎉 Semua file siap!
                        </span>

                        <button type="submit"
                                :disabled="!allSelected"
                                :class="allSelected ? 'gradient-fun shadow-lg shadow-orange-500/25 hover:shadow-xl hover:scale-105' : 'bg-gray-200 cursor-not-allowed text-gray-400'"
                                class="inline-flex items-center gap-2 px-7 py-3 text-white text-sm font-extrabold rounded-2xl active:scale-95 transition-all duration-200">
                            <span class="text-lg">⚡</span>
                            Proses {{ count($uploadedFiles) }} File!
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
