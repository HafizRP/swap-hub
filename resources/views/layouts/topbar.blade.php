<header class="h-12 craft-topbar border-b border-stone-200/80 dark:border-stone-800/80 sticky top-0 px-4 z-20 flex items-center justify-between select-none">
    <!-- Left: Mobile Toggle + Quick Nav -->
    <div class="flex items-center gap-2">
        <!-- Mobile Menu Toggle -->
        <button @click="sidebarOpenMobile = !sidebarOpenMobile"
                class="md:hidden p-2 text-stone-500 hover:text-stone-900 dark:hover:text-stone-100 rounded-lg hover:bg-stone-100 dark:hover:bg-stone-800 transition-colors"
                aria-label="Menu">
            <i class="bi bi-list text-lg"></i>
        </button>
    </div>

    <!-- Center: Command Palette Search -->
    <div class="flex-1 max-w-xl mx-auto">
        <button @click="commandPaletteOpen = true"
                class="w-full flex items-center gap-2.5 px-3 py-1.5 bg-stone-100/80 dark:bg-stone-800/60 hover:bg-stone-100 dark:hover:bg-stone-800 border border-stone-200/60 dark:border-stone-700/60 rounded-lg text-sm text-stone-500 dark:text-stone-400 transition-all group">
            <i class="bi bi-search text-xs"></i>
            <span class="flex-1 text-left text-xs">Cari proyek, teknologi, atau anggota...</span>
            <kbd class="hidden sm:inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded bg-white dark:bg-stone-700 text-[10px] font-mono-code font-semibold text-stone-400 dark:text-stone-300 border border-stone-200 dark:border-stone-600">
                ⌘K
            </kbd>
        </button>
    </div>

    <!-- Right: Utilities -->
    <div class="flex items-center gap-1">
        <!-- Theme Toggle -->
        <button @click="toggleTheme()"
                class="w-8 h-8 rounded-lg flex items-center justify-center text-stone-500 hover:text-stone-900 dark:hover:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800 transition-colors"
                title="Ganti Tema">
            <i class="bi text-sm" :class="darkMode ? 'bi-sun' : 'bi-moon'"></i>
        </button>

        <!-- Notifications -->
        <button class="w-8 h-8 rounded-lg flex items-center justify-center text-stone-500 hover:text-stone-900 dark:hover:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800 transition-colors relative"
                title="Notifikasi">
            <i class="bi bi-bell text-sm"></i>
            <span class="absolute top-1 right-1 w-1.5 h-1.5 bg-teal-500 rounded-full"></span>
        </button>

        <!-- User Menu -->
        <div class="relative ml-1" x-data="{ userMenuOpen: false }" @click.outside="userMenuOpen = false">
            <button @click="userMenuOpen = !userMenuOpen"
                    class="flex items-center gap-1.5 p-1 pr-2 rounded-lg hover:bg-stone-100 dark:hover:bg-stone-800 transition-colors">
                <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=0d9488&color=fff' }}"
                     class="w-6 h-6 rounded-full object-cover ring-1 ring-stone-200 dark:ring-stone-700">
                <i class="bi bi-chevron-down text-[9px] text-stone-400 hidden sm:inline"></i>
            </button>
            <!-- Dropdown -->
            <div x-show="userMenuOpen"
                 x-cloak
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-56 rounded-xl bg-white dark:bg-[#0f0f0f] border border-stone-200/90 dark:border-stone-800/90 shadow-xl py-1 z-50">
                
                <!-- User Info -->
                <div class="px-3 py-2 border-b border-stone-100 dark:border-stone-800">
                    <p class="font-bold text-xs text-stone-900 dark:text-stone-100 truncate mb-0">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-stone-400 truncate mb-0">{{ auth()->user()->email }}</p>
                </div>

                <!-- Links -->
                <div class="p-1">
                    <a href="{{ route('profile.show', auth()->id()) }}" wire:navigate.hover
                       class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-xs text-stone-700 dark:text-stone-300 hover:bg-stone-100 dark:hover:bg-stone-800 transition-colors no-underline">
                        <i class="bi bi-person text-stone-400"></i>
                        <span>Profil Saya</span>
                    </a>
                    <a href="{{ route('profile.edit') }}" wire:navigate.hover
                       class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-xs text-stone-700 dark:text-stone-300 hover:bg-stone-100 dark:hover:bg-stone-800 transition-colors no-underline">
                        <i class="bi bi-gear text-stone-400"></i>
                        <span>Pengaturan</span>
                    </a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" wire:navigate.hover
                           class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-xs text-stone-700 dark:text-stone-300 hover:bg-stone-100 dark:hover:bg-stone-800 transition-colors no-underline">
                            <i class="bi bi-shield text-stone-400"></i>
                            <span>Admin Panel</span>
                        </a>
                    @endif
                </div>

                <!-- Logout -->
                <div class="border-t border-stone-100 dark:border-stone-800 p-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full text-left flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-xs text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/20 transition-colors font-medium">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Command Palette Modal -->
<div x-show="commandPaletteOpen"
     x-cloak
     @keydown.escape.window="commandPaletteOpen = false"
     class="fixed inset-0 z-[9999] flex items-start justify-center pt-[20vh] px-4"
     style="display: none;">
    
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
         @click="commandPaletteOpen = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>

    <!-- Command Palette -->
    <div class="relative w-full max-w-2xl bg-white dark:bg-[#0f0f0f] rounded-xl shadow-2xl border border-stone-200 dark:border-stone-800 overflow-hidden"
         x-data="{
             liveQuery: '',
             loading: false,
             results: { projects: [], users: [], skills: [] },
             hasResults() {
                 return (this.results.projects && this.results.projects.length > 0) ||
                        (this.results.users && this.results.users.length > 0) ||
                        (this.results.skills && this.results.skills.length > 0);
             },
             async performSearch() {
                 if (this.liveQuery.trim().length < 2) {
                     this.results = { projects: [], users: [], skills: [] };
                     return;
                 }
                 this.loading = true;
                 try {
                     const res = await fetch('{{ route('search.live') }}?q=' + encodeURIComponent(this.liveQuery.trim()));
                     this.results = await res.json();
                 } catch (e) {
                     console.error(e);
                 } finally {
                     this.loading = false;
                 }
             }
         }"
         x-init="$watch('commandPaletteOpen', value => { if (value) { setTimeout(() => $refs.commandInput.focus(), 100); } else { liveQuery = ''; results = { projects: [], users: [], skills: [] }; } })"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">
        
        <!-- Search Input -->
        <form action="{{ route('projects.index') }}" method="GET">
            <div class="flex items-center gap-3 px-4 py-3 border-b border-stone-200 dark:border-stone-800">
                <i class="bi bi-search text-stone-400" :class="loading ? 'animate-spin bi-arrow-repeat text-teal-500' : 'bi-search'"></i>
                <input type="text"
                       name="search"
                       x-model="liveQuery"
                       @input.debounce.250ms="performSearch()"
                       placeholder="Cari proyek, teknologi, atau anggota..."
                       class="flex-1 bg-transparent border-0 outline-none text-sm text-stone-900 dark:text-stone-100 placeholder-stone-400"
                       autofocus
                       x-ref="commandInput">
                <button type="submit" class="text-[11px] font-bold px-2 py-0.5 rounded bg-stone-100 dark:bg-stone-800 text-stone-600 dark:text-stone-300 hover:text-teal-600 border border-stone-200 dark:border-stone-700">
                    Enter ↵
                </button>
            </div>
        </form>

        <!-- Dynamic Live Results or Quick Actions -->
        <div class="p-2 max-h-[420px] overflow-y-auto custom-scrollbar">
            <!-- Live Results View -->
            <template x-if="liveQuery.trim().length >= 2">
                <div>
                    <!-- Projects Found -->
                    <template x-if="results.projects && results.projects.length > 0">
                        <div class="mb-3">
                            <p class="px-2 text-[10px] font-bold uppercase tracking-wider text-teal-600 dark:text-teal-400 mb-1 flex items-center gap-1.5">
                                <i class="bi bi-folder-fill text-xs"></i> Proyek Kolaborasi
                            </p>
                            <template x-for="p in results.projects" :key="'p-' + p.id">
                                <a :href="p.url" @click="commandPaletteOpen = false"
                                   class="flex items-center justify-between px-3 py-2 rounded-lg text-sm text-stone-800 dark:text-stone-200 hover:bg-stone-100 dark:hover:bg-stone-800 transition-colors no-underline">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <i class="bi bi-arrow-right-short text-stone-400"></i>
                                        <span class="font-semibold text-xs truncate" x-text="p.title"></span>
                                    </div>
                                    <span class="text-[10px] uppercase font-bold text-stone-400 bg-stone-100 dark:bg-stone-800 px-2 py-0.5 rounded shrink-0" x-text="p.category"></span>
                                </a>
                            </template>
                        </div>
                    </template>

                    <!-- Users Found -->
                    <template x-if="results.users && results.users.length > 0">
                        <div class="mb-3">
                            <p class="px-2 text-[10px] font-bold uppercase tracking-wider text-sky-600 dark:text-sky-400 mb-1 flex items-center gap-1.5">
                                <i class="bi bi-people-fill text-xs"></i> Mahasiswa & Anggota
                            </p>
                            <template x-for="u in results.users" :key="'u-' + u.id">
                                <a :href="u.url" @click="commandPaletteOpen = false"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-stone-800 dark:text-stone-200 hover:bg-stone-100 dark:hover:bg-stone-800 transition-colors no-underline">
                                    <img :src="u.avatar" class="w-6 h-6 rounded-full object-cover">
                                    <div class="min-w-0">
                                        <span class="font-semibold text-xs block truncate" x-text="u.name"></span>
                                        <span class="text-[10px] text-stone-400 block truncate" x-text="u.major"></span>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </template>

                    <!-- Skills Found -->
                    <template x-if="results.skills && results.skills.length > 0">
                        <div class="mb-3">
                            <p class="px-2 text-[10px] font-bold uppercase tracking-wider text-purple-600 dark:text-purple-400 mb-1 flex items-center gap-1.5">
                                <i class="bi bi-tools text-xs"></i> Keahlian (Skills)
                            </p>
                            <div class="flex flex-wrap gap-1.5 px-2">
                                <template x-for="s in results.skills" :key="'s-' + s.id">
                                    <a :href="s.url" @click="commandPaletteOpen = false"
                                       class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-stone-100 dark:bg-stone-800 hover:bg-teal-50 dark:hover:bg-teal-950/40 text-stone-700 dark:text-stone-300 hover:text-teal-600 border border-stone-200 dark:border-stone-700 transition-colors no-underline">
                                        <i class="bi bi-tag text-[10px] text-stone-400"></i>
                                        <span x-text="s.name"></span>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Empty State for Live Search -->
                    <template x-if="!loading && !hasResults()">
                        <div class="p-6 text-center text-xs text-stone-400">
                            <i class="bi bi-search text-2xl mb-2 block opacity-40"></i>
                            <p class="font-semibold mb-1">Tidak ada hasil instan untuk "<span x-text="liveQuery"></span>"</p>
                            <small class="text-stone-500">Tekan Enter untuk mencari lebih luas di katalog proyek.</small>
                        </div>
                    </template>
                </div>
            </template>

            <!-- Default View when liveQuery is empty -->
            <template x-if="liveQuery.trim().length < 2">
                <div>
                    <div class="mb-3">
                        <p class="px-2 text-[10px] font-bold uppercase tracking-wider text-stone-400 mb-1">Aksi Cepat</p>
                        <a href="{{ route('projects.create') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-stone-700 dark:text-stone-300 hover:bg-stone-100 dark:hover:bg-stone-800 transition-colors no-underline">
                            <i class="bi bi-plus-circle text-teal-600"></i>
                            <span>Buat Proyek Baru</span>
                        </a>
                        <a href="{{ route('projects.index') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-stone-700 dark:text-stone-300 hover:bg-stone-100 dark:hover:bg-stone-800 transition-colors no-underline">
                            <i class="bi bi-compass text-blue-600"></i>
                            <span>Jelajahi Proyek</span>
                        </a>
                        <a href="{{ route('chat') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-stone-700 dark:text-stone-300 hover:bg-stone-100 dark:hover:bg-stone-800 transition-colors no-underline">
                            <i class="bi bi-chat-dots text-purple-600"></i>
                            <span>Buka Chat</span>
                        </a>
                    </div>

                    <div>
                        <p class="px-2 text-[10px] font-bold uppercase tracking-wider text-stone-400 mb-1">Navigasi</p>
                        <a href="{{ route('dashboard') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-stone-700 dark:text-stone-300 hover:bg-stone-100 dark:hover:bg-stone-800 transition-colors no-underline">
                            <i class="bi bi-columns-gap"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('profile.show', auth()->id()) }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-stone-700 dark:text-stone-300 hover:bg-stone-100 dark:hover:bg-stone-800 transition-colors no-underline">
                            <i class="bi bi-person"></i>
                            <span>Profil Saya</span>
                        </a>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
