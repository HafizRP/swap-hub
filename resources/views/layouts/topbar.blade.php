<nav class="sticky top-0 z-20 flex items-center bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800 px-4 sm:px-6 h-16 transition-colors">
    <div class="flex items-center justify-between w-full gap-4">
        <!-- Left: Mobile Toggle & Page Search -->
        <div class="flex items-center gap-3 flex-1 max-w-lg">
            <!-- Mobile Toggle -->
            <button @click="toggleSidebar()"
                    class="p-2 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 md:hidden rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <i class="bi bi-list text-2xl"></i>
            </button>

            <!-- Search Form -->
            <form action="{{ route('projects.index') }}" method="GET" class="w-full hidden sm:block">
                <div class="relative flex items-center">
                    <i class="bi bi-search absolute left-3.5 text-slate-400 text-xs"></i>
                    <input type="text" name="search"
                           class="w-full bg-slate-100 dark:bg-slate-800/80 border border-transparent focus:border-brand-500 focus:bg-white dark:focus:bg-slate-800 rounded-xl pl-9 pr-4 py-2 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all"
                           placeholder="Cari judul proyek, topik, atau skill..."
                           value="{{ request('search') }}">
                </div>
            </form>
        </div>

        <!-- Right: Actions & User Menu -->
        <div class="flex items-center gap-2 sm:gap-3">
            <!-- Theme Toggle -->
            <button @click="toggleTheme()"
                    class="p-2 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                    title="Toggle Theme">
                <i class="bi text-base" :class="darkMode ? 'bi-sun-fill text-amber-400' : 'bi-moon-stars-fill'"></i>
            </button>

            <!-- Workspace Chat Shortcut -->
            <a href="{{ route('chat') }}"
               class="p-2 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors relative"
               title="Workspace Chat">
                <i class="bi bi-chat-dots text-base"></i>
            </a>

            <!-- Divider -->
            <div class="h-6 w-px bg-slate-200 dark:bg-slate-800 mx-1"></div>

            <!-- Profile Dropdown -->
            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = !open"
                        class="flex items-center gap-2.5 p-1 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=6366f1&color=fff' }}"
                         class="w-8 h-8 rounded-lg object-cover ring-2 ring-brand-500/20 shadow-sm"
                         alt="{{ auth()->user()->name }}">
                    <div class="text-left hidden lg:block pr-1">
                        <p class="text-xs font-bold text-slate-900 dark:text-white leading-tight mb-0 truncate max-w-[120px]">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mb-0 truncate max-w-[120px]">{{ auth()->user()->major ?? 'Mahasiswa' }}</p>
                    </div>
                    <i class="bi bi-chevron-down text-[10px] text-slate-400 hidden lg:block"></i>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open"
                     x-cloak
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-52 rounded-2xl shadow-xl py-1.5 bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 z-50">
                    
                    <div class="px-3.5 py-2 border-b border-slate-100 dark:border-slate-800">
                        <p class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-slate-400 truncate">{{ auth()->user()->email }}</p>
                    </div>

                    <div class="py-1">
                        <a href="{{ route('profile.show', auth()->id()) }}"
                           class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/80 transition-colors">
                            <i class="bi bi-person-badge text-slate-400 text-sm"></i>
                            <span>Profil & Portofolio</span>
                        </a>
                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/80 transition-colors">
                            <i class="bi bi-gear text-slate-400 text-sm"></i>
                            <span>Pengaturan Akun</span>
                        </a>
                    </div>

                    <div class="border-t border-slate-100 dark:border-slate-800 my-1"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-2.5 px-3.5 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition-colors">
                            <i class="bi bi-box-arrow-right text-sm"></i>
                            <span>Keluar Akun</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
