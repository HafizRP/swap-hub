<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('title') @yield('title') — @endif{{ config('app.name', 'Swap Hub') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" type="image/png" href="{{ asset('icon.png') }}">

    @include('partials.pwa-head')

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        .font-mono-code {
            font-family: 'JetBrains Mono', monospace;
        }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #d6d3d1; border-radius: 9999px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #292524; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #a8a29e; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #44403c; }

        .craft-surface {
            background-color: #fafafa;
        }
        .dark .craft-surface {
            background-color: #0a0a0a;
        }
        .craft-sidebar {
            background-color: rgba(250, 250, 250, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
        .dark .craft-sidebar {
            background-color: rgba(15, 15, 15, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
        .craft-topbar {
            background-color: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
        .dark .craft-topbar {
            background-color: rgba(12, 12, 12, 0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
    </style>

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

<body class="h-full antialiased text-stone-900 dark:text-stone-100 craft-surface selection:bg-teal-500 selection:text-white"
    x-data="{
        sidebarHovered: false,
        sidebarOpenMobile: false,
        commandPaletteOpen: false,
        darkMode: localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches),
        toggleTheme() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        },
        openCommandPalette(e) {
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                this.commandPaletteOpen = true;
            }
        }
    }"
    @keydown.window="openCommandPalette($event)"
    @resize.window="if(window.innerWidth >= 768) sidebarOpenMobile = false">

    <!-- ===================== MOBILE SIDEBAR DRAWER ===================== -->
    <div x-show="sidebarOpenMobile"
         x-cloak
         class="fixed inset-0 z-[9999] md:hidden flex">

        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
             @click="sidebarOpenMobile = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
        </div>

        <!-- Drawer (Linear / Craft minimalist drawer) -->
        <!-- Mobile Drawer Sidebar -->
        <div class="relative w-[280px] h-full bg-white dark:bg-[#0f0f0f] border-r border-stone-200/80 dark:border-stone-800/80 flex flex-col overflow-y-auto shadow-2xl"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full">

            <!-- Logo & Close -->
            <div class="h-12 px-4 flex items-center justify-between border-b border-stone-200/60 dark:border-stone-800/60">
                <a href="{{ route('dashboard') }}" @click="sidebarOpenMobile = false" wire:navigate.hover class="flex items-center gap-2.5 no-underline">
                    <x-application-logo class="w-7 h-7 rounded-lg shrink-0" />
                    <span class="text-sm font-bold tracking-tight text-stone-900 dark:text-stone-100">SwapHub</span>
                </a>
                <button @click="sidebarOpenMobile = false"
                        class="p-1.5 text-stone-400 hover:text-stone-700 dark:hover:text-stone-200 rounded-lg hover:bg-stone-100 dark:hover:bg-stone-800 transition-colors">
                    <i class="bi bi-x-lg text-sm"></i>
                </button>
            </div>

            <!-- Nav Items -->
            <nav class="flex-1 p-3 space-y-6">
                <div>
                    <span class="px-2 text-[10px] font-bold uppercase tracking-wider text-stone-400 dark:text-stone-500 block mb-2">Workspace</span>
                    <ul class="space-y-1 list-none p-0 m-0">
                        @foreach([
                            ['route' => 'dashboard', 'icon' => 'bi-columns-gap', 'label' => 'Dashboard'],
                            ['route' => 'projects.index', 'icon' => 'bi-compass', 'label' => 'Cari Proyek'],
                            ['route' => 'skills.swap.index', 'icon' => 'bi-arrow-left-right', 'label' => 'Skill Swap'],
                            ['route' => 'chat', 'icon' => 'bi-chat-dots', 'label' => 'Chat'],
                        ] as $mItem)
                            @php
                                $mActive = request()->routeIs($mItem['route'] . '*');
                                $mUrl = route($mItem['route'], $mItem['params'] ?? []);
                            @endphp
                            <li>
                                <a href="{{ $mUrl }}" @click="sidebarOpenMobile = false" wire:navigate.hover
                                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium no-underline transition-all {{ $mActive ? 'bg-teal-600 text-white' : 'text-stone-600 dark:text-stone-400 hover:bg-stone-100 dark:hover:bg-stone-800' }}">
                                    <i class="bi {{ $mItem['icon'] }} text-base"></i>
                                    <span>{{ $mItem['label'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <span class="px-2 text-[10px] font-bold uppercase tracking-wider text-stone-400 dark:text-stone-500 block mb-2">Campus</span>
                    <ul class="space-y-1 list-none p-0 m-0">
                        @foreach([
                            ['route' => 'campus.study-desk', 'icon' => 'bi-camera-video', 'label' => 'Study Desk'],
                            ['route' => 'campus.code-reviews', 'icon' => 'bi-code-slash', 'label' => 'Code Review'],
                            ['route' => 'campus.credits', 'icon' => 'bi-coin', 'label' => 'Kredit Time-Bank'],
                            ['route' => 'campus.courses', 'icon' => 'bi-mortarboard', 'label' => 'Mata Kuliah'],
                        ] as $cmItem)
                            @php
                                $cmActive = request()->routeIs($cmItem['route'] . '*');
                                $cmUrl = route($cmItem['route']);
                            @endphp
                            <li>
                                <a href="{{ $cmUrl }}" @click="sidebarOpenMobile = false"
                                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium no-underline transition-all {{ $cmActive ? 'bg-teal-600 text-white' : 'text-stone-600 dark:text-stone-400 hover:bg-stone-100 dark:hover:bg-stone-800' }}">
                                    <i class="bi {{ $cmItem['icon'] }} text-base"></i>
                                    <span>{{ $cmItem['label'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <span class="px-2 text-[10px] font-bold uppercase tracking-wider text-stone-400 dark:text-stone-500 block mb-2">Akun</span>
                    <ul class="space-y-1 list-none p-0 m-0">
                        <li>
                            <a href="{{ route('profile.show', auth()->id()) }}" @click="sidebarOpenMobile = false" wire:navigate.hover
                               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium no-underline transition-all {{ request()->routeIs('profile.show') ? 'bg-teal-600 text-white' : 'text-stone-600 dark:text-stone-400 hover:bg-stone-100 dark:hover:bg-stone-800' }}">
                                <i class="bi bi-person text-base"></i>
                                <span>Profil Saya</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('profile.edit') }}" @click="sidebarOpenMobile = false" wire:navigate.hover
                               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium no-underline transition-all {{ request()->routeIs('profile.edit') ? 'bg-teal-600 text-white' : 'text-stone-600 dark:text-stone-400 hover:bg-stone-100 dark:hover:bg-stone-800' }}">
                                <i class="bi bi-gear text-base"></i>
                                <span>Pengaturan</span>
                            </a>
                        </li>
                    </ul>
                </div>

                @if(auth()->user()->isAdmin())
                    <div>
                        <span class="px-2 text-[10px] font-bold uppercase tracking-wider text-stone-400 dark:text-stone-500 block mb-2">Admin</span>
                        <ul class="space-y-1 list-none p-0 m-0">
                            @foreach([
                                ['route' => 'admin.dashboard', 'icon' => 'bi-speedometer2', 'label' => 'Overview'],
                                ['route' => 'admin.users.index', 'icon' => 'bi-people', 'label' => 'Users'],
                                ['route' => 'admin.projects.index', 'icon' => 'bi-folder', 'label' => 'Projects'],
                                ['route' => 'admin.credits.index', 'icon' => 'bi-coin', 'label' => 'Credits'],
                                ['route' => 'admin.code-reviews.index', 'icon' => 'bi-code-slash', 'label' => 'Bounties'],
                                ['route' => 'admin.swaps.index', 'icon' => 'bi-arrow-left-right', 'label' => 'Swaps'],
                                ['route' => 'admin.skills.index', 'icon' => 'bi-tools', 'label' => 'Skills'],
                                ['route' => 'admin.courses.index', 'icon' => 'bi-mortarboard', 'label' => 'Courses'],
                                ['route' => 'admin.badges.index', 'icon' => 'bi-award', 'label' => 'Badges'],
                                ['route' => 'admin.audit-logs.index', 'icon' => 'bi-shield-check', 'label' => 'Audit Logs'],
                                ['route' => 'admin.health.index', 'icon' => 'bi-heart-pulse', 'label' => 'Health'],
                            ] as $aItem)
                                <li>
                                    <a href="{{ route($aItem['route']) }}" @click="sidebarOpenMobile = false"
                                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium no-underline transition-all {{ request()->routeIs($aItem['route'] . '*') ? 'bg-teal-600 text-white' : 'text-stone-600 dark:text-stone-400 hover:bg-stone-100 dark:hover:bg-stone-800' }}">
                                        <i class="bi {{ $aItem['icon'] }} text-base"></i>
                                        <span>{{ $aItem['label'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </nav>

            <!-- Mobile Footer -->
            <div class="p-3 border-t border-stone-200/60 dark:border-stone-800/60">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/20 transition-colors">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- ===================== MAIN APP SHELL (CRAFT / LINEAR WORKSPACE) ===================== -->
    <div class="flex h-screen overflow-hidden">

        <!-- Linear Collapsible Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Workspace Stage -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Slim Topbar / Command Header -->
            @include('layouts.topbar')

            <!-- Main Scrollable Canvas -->
            <main class="flex-1 overflow-y-auto overflow-x-hidden p-4 sm:p-6 lg:p-8 custom-scrollbar">
                <div class="mx-auto max-w-[1400px]">
                    @if(isset($slot) && !empty((string)$slot))
                        {{ $slot }}
                    @else
                        @yield('content')
                    @endif
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

    <!-- Auth Notification Script -->
    @auth
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if ('Notification' in window && Notification.permission === 'default') {
                    Notification.requestPermission();
                }

                const userId = {{ auth()->id() }};
                window.currentConversationId = null;

                const initEcho = () => {
                    if (window.Echo) {
                        window.Echo.private(`App.Models.User.${userId}`)
                            .listen('.message.sent', (e) => {
                                if (window.Livewire) window.Livewire.dispatch('refresh-conversation-list');
                                if (e.user_id == userId) return;
                                if (window.currentConversationId && window.currentConversationId == e.conversation_id) return;

                                if ('Notification' in window && Notification.permission === 'granted') {
                                    const title = (e.conversation_type === 'project' && e.conversation_name)
                                        ? e.conversation_name : (e.user_name || 'New Message');

                                    let bodyText = e.content || '';
                                    bodyText = bodyText.replace(/(\*\*|__)(.*?)\1/g, '$2');
                                    bodyText = bodyText.replace(/(`)(.*?)\1/g, '$2');
                                    bodyText = bodyText.replace(/^\s*-\s/gm, '• ');

                                    const body = bodyText.substring(0, 100) + (bodyText.length > 100 ? '...' : '');

                                    try {
                                        const n = new Notification(title, { body, icon: e.user_avatar });
                                        n.onclick = function () { window.focus(); window.location.href = `/chat/${e.conversation_id}`; }
                                    } catch (err) {}
                                }
                            });
                    } else {
                        setTimeout(initEcho, 1000);
                    }
                };
                initEcho();
            });
        </script>
    @endauth

    @include('partials.pwa-install-prompt')
</body>

</html>
