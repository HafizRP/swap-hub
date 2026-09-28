<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('title') @yield('title') - @endif{{ config('app.name', 'Swap Hub') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @livewireStyles

    <!-- Theme: apply before paint to avoid flash -->
    <script>
        (function() {
            var theme = localStorage.getItem('theme');
            var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (theme === 'dark' || (!theme && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>

<body class="h-full antialiased font-sans text-slate-800 dark:text-slate-100 bg-slate-50 dark:bg-slate-950 selection:bg-brand-500 selection:text-white transition-colors duration-200"
    x-data="{
        sidebarExpanded: localStorage.getItem('sidebarExpanded') === null ? true : localStorage.getItem('sidebarExpanded') === 'true',
        sidebarOpenMobile: false,
        darkMode: localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches),
        toggleSidebar() {
            if (window.innerWidth >= 768) {
                this.sidebarExpanded = !this.sidebarExpanded;
                localStorage.setItem('sidebarExpanded', this.sidebarExpanded);
            } else {
                this.sidebarOpenMobile = !this.sidebarOpenMobile;
            }
        },
        toggleTheme() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        },
        init() {
            this.$watch('darkMode', val => {
                if (val) document.documentElement.classList.add('dark');
                else document.documentElement.classList.remove('dark');
            });
        }
    }"
    @resize.window="if(window.innerWidth >= 768) sidebarOpenMobile = false">

    <!-- ===================== MOBILE SIDEBAR DRAWER ===================== -->
    <div x-show="sidebarOpenMobile"
         x-cloak
         class="fixed inset-0 z-50 md:hidden flex">

        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm"
             @click="sidebarOpenMobile = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
        </div>

        <!-- Drawer -->
        <div class="relative w-[280px] h-full bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 flex flex-col overflow-y-auto overflow-x-hidden shadow-2xl z-10"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full">

            <!-- Logo & Close -->
            <div class="flex items-center justify-between p-4 border-b border-slate-100 dark:border-slate-800">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('icon.png') }}" alt="Swap Hub" class="w-8 h-8 rounded-lg">
                    <span class="text-base font-extrabold tracking-tight text-slate-900 dark:text-white">Swap<span class="text-brand-500">Hub</span></span>
                </a>
                <button @click="sidebarOpenMobile = false"
                        class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
                    <i class="bi bi-x-lg text-base"></i>
                </button>
            </div>

            <!-- User Info -->
            <div class="p-3">
                <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl border border-slate-100 dark:border-slate-800">
                    <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=6366f1&color=fff' }}"
                         class="rounded-lg shrink-0 w-9 h-9 object-cover">
                    <div class="min-w-0">
                        <p class="font-bold mb-0 text-slate-900 dark:text-white text-xs truncate">{{ auth()->user()->name }}</p>
                        <p class="text-slate-500 dark:text-slate-400 text-[11px] truncate mb-0">{{ auth()->user()->university ?? 'Student' }}</p>
                    </div>
                </div>
            </div>

            <!-- Nav Items -->
            <ul class="flex flex-col flex-1 px-3 gap-1 mb-4">
                @foreach([
                    ['route' => 'dashboard',      'icon' => 'bi-grid-fill',       'label' => 'Dashboard'],
                    ['route' => 'projects.index', 'icon' => 'bi-compass-fill',    'label' => 'Cari Proyek'],
                    ['route' => 'profile.show',   'icon' => 'bi-person-badge-fill','label' => 'Profil & Portofolio', 'params' => auth()->id()],
                    ['route' => 'chat',           'icon' => 'bi-chat-dots-fill',   'label' => 'Workspace & Chat'],
                ] as $mItem)
                    @php
                        $mActive = request()->routeIs($mItem['route'] . '*');
                        $mUrl = route($mItem['route'], $mItem['params'] ?? []);
                    @endphp
                    <li>
                        <a href="{{ $mUrl }}" @click="sidebarOpenMobile = false"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all text-xs font-semibold {{ $mActive ? 'bg-brand-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                            <i class="bi {{ $mItem['icon'] }} text-base shrink-0"></i>
                            <span>{{ $mItem['label'] }}</span>
                        </a>
                    </li>
                @endforeach

                <li><div class="border-t border-slate-100 dark:border-slate-800 my-2"></div></li>

                <li>
                    <a href="{{ route('profile.edit') }}" @click="sidebarOpenMobile = false"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all text-xs font-semibold {{ request()->routeIs('profile.edit') ? 'bg-brand-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                        <i class="bi bi-gear-fill text-base shrink-0"></i>
                        <span>Pengaturan Akun</span>
                    </a>
                </li>

                @if(auth()->user()->isAdmin())
                    <li><div class="border-t border-slate-100 dark:border-slate-800 my-2"></div></li>
                    @foreach([
                        ['route' => 'admin.dashboard',      'icon' => 'bi-speedometer2',    'label' => 'Admin Overview'],
                        ['route' => 'admin.users.index',    'icon' => 'bi-people-fill',     'label' => 'Kelola User'],
                        ['route' => 'admin.projects.index', 'icon' => 'bi-folder-fill',     'label' => 'Kelola Proyek'],
                        ['route' => 'admin.health.index',   'icon' => 'bi-heart-pulse-fill', 'label' => 'System Health'],
                    ] as $aItem)
                        <li>
                            <a href="{{ route($aItem['route']) }}" @click="sidebarOpenMobile = false"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all text-xs font-semibold {{ request()->routeIs($aItem['route'] . '*') ? 'bg-brand-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                                <i class="bi {{ $aItem['icon'] }} text-base shrink-0"></i>
                                <span>{{ $aItem['label'] }}</span>
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>

            <div class="p-4 text-center border-t border-slate-100 dark:border-slate-800">
                <span class="text-slate-400 dark:text-slate-500 text-[11px]">&copy; {{ date('Y') }} Swap Hub</span>
            </div>
        </div>
    </div>

    <!-- ===================== MAIN APP SHELL ===================== -->
    <div class="flex h-screen overflow-hidden">
        <!-- Desktop Sidebar -->
        @include('layouts.sidebar')

        <!-- Right Column: Topbar + Page Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            @include('layouts.topbar')

            <main class="flex-1 overflow-y-auto overflow-x-hidden p-4 sm:p-6 lg:p-8 custom-scrollbar bg-slate-50 dark:bg-slate-950 transition-colors">
                <div class="mx-auto max-w-7xl">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')

    <!-- Re-apply dark mode after Livewire SPA navigation -->
    <script>
        document.addEventListener('livewire:navigated', () => {
            var theme = localStorage.getItem('theme');
            var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (theme === 'dark' || (!theme && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        });
    </script>
</body>
</html>
