<nav x-data="{ open: false }" class="bg-gradient-to-r from-violet-600 via-purple-600 to-indigo-600 shadow-xl shadow-purple-500/20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <div class="shrink-0 flex items-center gap-3">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                            <span class="text-xl">📄</span>
                        </div>
                        <span class="text-white font-extrabold text-lg tracking-tight hidden sm:block">
                            PDF Tool <span class="text-yellow-300 text-sm">⭐</span>
                        </span>
                    </a>
                </div>

                <div class="hidden sm:flex sm:items-center sm:ml-8 space-x-1">
                    <a href="{{ route('dashboard') }}"
                       class="px-4 py-2 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-white/25 text-white shadow-inner' : 'text-white/70 hover:bg-white/15 hover:text-white' }}">
                        <span class="flex items-center gap-2">
                            <span>🏠</span>
                            Dashboard
                        </span>
                    </a>
                    <a href="{{ route('pdf-tool.index') }}"
                       class="px-4 py-2 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('pdf-tool.*') ? 'bg-white text-purple-700 shadow-lg shadow-white/25' : 'text-white/70 hover:bg-white/15 hover:text-white' }}">
                        <span class="flex items-center gap-2">
                            <span>✂️</span>
                            PDF Tool
                        </span>
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-bold text-white/80 hover:bg-white/15 hover:text-white transition-all duration-200">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center text-white text-xs font-black shadow-lg shadow-orange-500/30 ring-2 ring-white/30">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-xs text-gray-400">Halo! 👋</p>
                            <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2 font-semibold">
                            <span>👤</span>
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="flex items-center gap-2 text-red-500 hover:text-red-700 hover:bg-red-50 font-semibold">
                                <span>🚪</span>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="p-2 rounded-xl text-white/70 hover:text-white hover:bg-white/15 transition-colors">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-purple-700/50 backdrop-blur-lg border-t border-white/10">
        <div class="pt-2 pb-3 px-3 space-y-1">
            <a href="{{ route('dashboard') }}"
               class="block px-4 py-2.5 rounded-xl text-sm font-bold {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/15 hover:text-white' }}">
                🏠 Dashboard
            </a>
            <a href="{{ route('pdf-tool.index') }}"
               class="block px-4 py-2.5 rounded-xl text-sm font-bold {{ request()->routeIs('pdf-tool.*') ? 'bg-white text-purple-700' : 'text-white/70 hover:bg-white/15 hover:text-white' }}">
                ✂️ PDF Tool
            </a>
        </div>

        <div class="pt-3 pb-3 px-3 border-t border-white/10">
            <div class="flex items-center gap-3 px-4 py-2">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center text-white font-black shadow-lg">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="text-sm font-bold text-white">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-white/60">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-2 space-y-1">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 rounded-xl text-sm text-white/70 hover:bg-white/15 hover:text-white font-semibold">
                    👤 Profile
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2.5 rounded-xl text-sm text-red-300 hover:bg-white/10 hover:text-red-200 font-semibold">
                        🚪 Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
