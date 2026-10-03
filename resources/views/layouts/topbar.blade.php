<nav class="flex items-center bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 py-2 sticky top-0 px-4 h-[74px] z-35">
    <div class="container-fluid flex items-center justify-between w-full p-0">
        <!-- Mobile Toggle -->
        <button @click="toggleSidebar()" class="p-2 text-slate-500 dark:text-slate-400 mr-3 md:hidden hover:bg-slate-100 dark:hover:bg-slate-700/50 rounded-full">
            <i class="bi bi-list text-2xl"></i>
        </button>
 
        <!-- Search -->
        <form action="{{ route('projects.index') }}" method="GET" class="hidden md:block flex-1 mr-4 max-w-[420px]">
            <div class="flex items-center bg-slate-100/90 dark:bg-slate-700/50 rounded-xl border border-transparent focus-within:border-indigo-500 focus-within:bg-white dark:focus-within:bg-slate-800 focus-within:ring-2 focus-within:ring-indigo-500/20 transition-all overflow-hidden">
                <span class="bg-transparent border-0 text-slate-400 pl-3.5 pr-2 flex items-center justify-center">
                    <i class="bi bi-search text-xs"></i>
                </span>
                <input type="text" name="search" class="bg-transparent border-0 w-full outline-none text-slate-800 dark:text-slate-100 placeholder-slate-400 py-2 px-1 text-xs font-medium"
                    placeholder="Cari proyek, skill, atau anggota..." value="{{ request('search') }}">
                <kbd class="hidden sm:inline-block text-[10px] font-semibold bg-white dark:bg-slate-800 text-slate-400 px-2 py-0.5 rounded-md border border-slate-200 dark:border-slate-700 mr-2.5 shadow-2xs">⌘K</kbd>
            </div>
        </form>
 
        <!-- Right Side Controls -->
        <div class="ml-auto flex items-center gap-2">
            <button @click="toggleTheme()" class="p-2 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50 rounded-full transition-colors duration-200">
                <i class="bi text-lg" :class="darkMode ? 'bi-sun-fill' : 'bi-moon-stars-fill'"></i>
            </button>
 
            <a class="p-2 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50 rounded-full transition-colors duration-200 relative" href="#">
                <i class="bi bi-bell-fill text-lg"></i>
                <span class="absolute top-1.5 right-1.5 p-1 bg-red-500 border border-white rounded-full"></span>
            </a>
 
            <a class="p-2 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50 rounded-full transition-colors duration-200 mr-2" href="{{ route('chat') }}" wire:navigate.hover>
                <i class="bi bi-chat-left-text-fill text-lg"></i>
            </a>

            <div class="border-l border-slate-200 dark:border-slate-700 pl-3 ml-2 flex items-center gap-3">
                <div class="text-right hidden md:block" style="line-height: 1.2;">
                    <span class="block font-bold text-slate-800 dark:text-slate-100 text-sm">{{ auth()->user()->name }}</span>
                    <span class="block text-slate-400 dark:text-slate-500 text-xs">{{ auth()->user()->major ?? 'Computer Science' }}</span>
                </div>

                <!-- Profile Dropdown Menu (Alpine.js) -->
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <a href="#" @click.prevent="open = !open" class="block">
                        <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                            width="40" height="40" class="rounded-circle border-2 border-white dark:border-slate-700 shadow-sm object-cover">
                    </a>
                    
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 rounded-lg shadow-lg py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700" 
                         style="display: none; z-index: 50;">
                        <h6 class="px-4 py-2 text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-700/50 mb-1">Manage Account</h6>
                        <a class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700/50"
                           href="{{ route('profile.show', auth()->id()) }}" wire:navigate.hover>
                            <i class="bi bi-person"></i> Profile
                        </a>
                        <a class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700/50"
                           href="{{ route('profile.edit') }}" wire:navigate.hover>
                            <i class="bi bi-gear"></i> Settings
                        </a>
                        <div class="border-t border-slate-100 dark:border-slate-700/50 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left flex items-center gap-2 px-4 py-2 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-950/20">
                                <i class="bi bi-box-arrow-right"></i> Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>