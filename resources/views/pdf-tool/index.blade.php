<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 gradient-brand rounded-2xl flex items-center justify-center shadow-lg shadow-pink-500/20 float">
                <span class="text-2xl">🚀</span>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold text-gray-900">Upload PDF Yuk!</h2>
                <p class="text-sm text-purple-400 font-medium">Pilih file PDF yang mau diproses 🌟</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-2 border-red-200 rounded-2xl animate-wiggle">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xl">😭</span>
                        <span class="font-bold text-red-800 text-sm">Waduh, upload gagal!</span>
                    </div>
                    @foreach($errors->all() as $error)
                        <p class="text-red-600 text-sm ml-8">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Fun tip card -->
            <div class="mb-6 bg-gradient-to-r from-amber-50 to-yellow-50 rounded-2xl p-4 border-2 border-amber-200/60 flex items-start gap-3">
                <span class="text-2xl float">💡</span>
                <div>
                    <p class="text-sm font-bold text-amber-800">Tips!</p>
                    <p class="text-xs text-amber-600 mt-0.5">Kamu bisa upload banyak file sekaligus lho! Drag aja langsung ke kotak di bawah, atau klik untuk browse. Max 20MB per file ya~</p>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-lg shadow-purple-500/5 border-2 border-purple-100/50 overflow-hidden">
                <form action="{{ route('pdf-tool.upload') }}" method="POST" enctype="multipart/form-data"
                      x-data="{ files: [], dragging: false }"
                      @dragover.prevent="dragging = true"
                      @dragleave.prevent="dragging = false"
                      @drop.prevent="dragging = false; files = Array.from($event.dataTransfer.files); $refs.fileInput.files = $event.dataTransfer.files">
                    @csrf

                    <!-- Upload Zone -->
                    <div class="p-8">
                        <div class="border-3 border-dashed rounded-3xl p-12 text-center transition-all duration-300 cursor-pointer relative overflow-hidden"
                             :class="dragging ? 'border-purple-400 bg-purple-50/50 scale-[1.02]' : 'border-purple-200 hover:border-purple-400 hover:bg-purple-50/30'"
                             @click="$refs.fileInput.click()">

                            <!-- Floating decorations -->
                            <div class="absolute top-4 left-8 text-3xl opacity-20 float">📄</div>
                            <div class="absolute top-8 right-12 text-2xl opacity-15 float-delay">✨</div>
                            <div class="absolute bottom-6 left-16 text-2xl opacity-15 float-delay">🌈</div>
                            <div class="absolute bottom-4 right-8 text-3xl opacity-20 float">📋</div>

                            <div class="relative">
                                <div class="w-20 h-20 mx-auto mb-5 rounded-3xl flex items-center justify-center transition-all duration-300"
                                     :class="dragging ? 'bg-purple-100 scale-110' : 'bg-gradient-to-br from-pink-100 to-purple-100'">
                                    <span class="text-4xl" :class="dragging ? 'animate-bounce' : ''">
                                        <span x-show="!dragging">📦</span>
                                        <span x-show="dragging" x-cloak>🎉</span>
                                    </span>
                                </div>

                                <p class="text-gray-700 font-extrabold text-lg mb-1">
                                    <span x-show="files.length === 0">Taruh file PDF di sini!</span>
                                    <span x-show="files.length > 0" class="text-purple-600">
                                        🎉 <span x-text="files.length"></span> file siap di-upload!
                                    </span>
                                </p>
                                <p class="text-sm text-purple-400 font-medium mb-5">atau klik di area ini untuk pilih file 👇</p>

                                <input type="file" name="files[]" multiple accept=".pdf" required
                                       x-ref="fileInput"
                                       @change="files = Array.from($event.target.files)"
                                       class="hidden">

                                <span class="inline-flex items-center gap-2 px-5 py-2 bg-purple-50 text-purple-500 text-xs font-bold rounded-full border border-purple-100">
                                    📎 PDF only &bull; Max 20MB per file
                                </span>
                            </div>
                        </div>

                        <!-- File List Preview -->
                        <div x-show="files && files.length > 0" x-transition class="mt-5 space-y-2">
                            <p class="text-xs font-bold text-purple-400 uppercase tracking-wider mb-2">📋 File yang dipilih:</p>
                            <template x-for="(file, index) in files" :key="index">
                                <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-pink-50 to-purple-50 rounded-2xl border border-purple-100/50" style="animation: fadeIn 0.3s ease-out both" :style="'animation-delay:' + (index * 0.05) + 's'">
                                    <div class="w-9 h-9 bg-gradient-to-br from-red-400 to-pink-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md shadow-red-500/20">
                                        <span class="text-white text-sm">📄</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-700 truncate" x-text="file.name"></p>
                                        <p class="text-xs text-purple-400 font-medium" x-text="(file.size / 1024 / 1024).toFixed(2) + ' MB'"></p>
                                    </div>
                                    <span class="text-green-500 text-lg">✅</span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Submit Bar -->
                    <div class="px-8 py-5 bg-gradient-to-r from-purple-50/80 to-pink-50/80 border-t border-purple-100/50 flex items-center justify-between">
                        <p class="text-xs text-purple-400 font-semibold hidden sm:block">🚀 Siap untuk proses? Gas!</p>
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-7 py-3 gradient-brand text-white text-sm font-extrabold rounded-2xl shadow-lg shadow-pink-500/25 hover:shadow-xl hover:shadow-pink-500/30 hover:scale-105 active:scale-95 transition-all duration-200">
                            <span class="text-lg">⚡</span>
                            Upload & Lanjut!
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
