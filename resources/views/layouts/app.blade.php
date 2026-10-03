<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('title') @yield('title') - @endif{{ config('app.name', 'Swap Hub') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">

    @include('partials.pwa-head')

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .hover-lift {
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -10px rgba(15, 23, 42, 0.09), 0 4px 6px -2px rgba(15, 23, 42, 0.04) !important;
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

<body class="antialiased font-sans text-slate-800 dark:text-slate-100 bg-slate-50 dark:bg-slate-900"
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
    <!-- Rendered on top of everything, only visible on mobile when open -->
    <div x-show="sidebarOpenMobile"
         x-cloak
         class="fixed inset-0 z-[9999] md:hidden flex">

        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"
             @click="sidebarOpenMobile = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
        </div>

        <!-- Drawer -->
        <div class="relative w-[280px] h-full bg-white dark:bg-slate-800 border-r border-slate-200 dark:border-slate-700 flex flex-col overflow-y-auto overflow-x-hidden shadow-xl"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full">

            <!-- Logo & Close -->
            <div class="flex items-center justify-between p-4 mb-2">
                <a href="{{ route('dashboard') }}" @click="sidebarOpenMobile = false" wire:navigate.hover class="flex items-center gap-2 no-underline text-slate-800 dark:text-slate-100">
                    <img src="{{ asset('icon.png') }}" alt="Swap Hub" class="w-8 h-8 rounded shrink-0">
                    <span class="text-lg font-bold">Swap Hub</span>
                </a>
                <button @click="sidebarOpenMobile = false"
                        class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 border-0 bg-transparent rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>

            <!-- User Info -->
            <div class="px-3 mb-4">
                <div class="flex items-center gap-3 p-2.5 bg-slate-100 dark:bg-slate-700/50 rounded-xl">
                    <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=random' }}"
                         class="rounded-full shrink-0 w-10 h-10 object-cover">
                    <div class="min-w-0">
                        <p class="font-bold mb-0 text-slate-800 dark:text-slate-100 text-sm truncate">{{ auth()->user()->name }}</p>
                        <p class="text-slate-500 dark:text-slate-400 text-[11px] truncate mb-0">{{ auth()->user()->university ?? 'Student' }}</p>
                    </div>
                </div>
            </div>

            <!-- Nav Items -->
            <ul class="flex flex-col flex-1 px-3 gap-1 mb-4">
                @foreach([
                    ['route' => 'dashboard', 'icon' => 'bi-grid-fill', 'label' => 'Dashboard'],
                    ['route' => 'projects.index', 'icon' => 'bi-search', 'label' => 'Cari Proyek'],
                    ['route' => 'profile.show', 'icon' => 'bi-person-fill', 'label' => 'Profil Saya', 'params' => auth()->id()],
                    ['route' => 'chat', 'icon' => 'bi-chat-dots-fill', 'label' => 'Workspace'],
                ] as $mItem)
                    @php
                        $mActive = request()->routeIs($mItem['route'] . '*');
                        $mUrl = route($mItem['route'], $mItem['params'] ?? []);
                    @endphp
                    <li>
                        <a href="{{ $mUrl }}" @click="sidebarOpenMobile = false" wire:navigate.hover
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors no-underline text-sm font-medium {{ $mActive ? 'bg-indigo-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50' }}">
                            <i class="bi {{ $mItem['icon'] }} text-base shrink-0"></i>
                            <span>{{ $mItem['label'] }}</span>
                        </a>
                    </li>
                @endforeach

                <li><div class="border-t border-slate-200 dark:border-slate-700 my-2"></div></li>

                <li>
                    <a href="{{ route('profile.edit') }}" @click="sidebarOpenMobile = false" wire:navigate.hover
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors no-underline text-sm font-medium {{ request()->routeIs('profile.edit') ? 'bg-indigo-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50' }}">
                        <i class="bi bi-gear-fill text-base shrink-0"></i>
                        <span>Pengaturan</span>
                    </a>
                </li>

                @if(auth()->user()->isAdmin())
                    <li><div class="border-t border-slate-200 dark:border-slate-700 my-2"></div></li>
                    @foreach([
                        ['route' => 'admin.dashboard', 'icon' => 'bi-speedometer2', 'label' => 'Admin Dashboard'],
                        ['route' => 'admin.users.index', 'icon' => 'bi-people-fill', 'label' => 'User Management'],
                        ['route' => 'admin.projects.index', 'icon' => 'bi-folder-fill', 'label' => 'Project Management'],
                        ['route' => 'admin.health.index', 'icon' => 'bi-heart-pulse-fill', 'label' => 'System Health'],
                    ] as $aItem)
                        <li>
                            <a href="{{ route($aItem['route']) }}" @click="sidebarOpenMobile = false" wire:navigate.hover
                               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors no-underline text-sm font-medium {{ request()->routeIs($aItem['route'] . '*') ? 'bg-indigo-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50' }}">
                                <i class="bi {{ $aItem['icon'] }} text-base shrink-0"></i>
                                <span>{{ $aItem['label'] }}</span>
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>

            <div class="px-4 py-3 text-center border-t border-slate-200 dark:border-slate-700">
                <small class="text-slate-400 dark:text-slate-500 text-[10px]">&copy; 2024 Swap Hub</small>
            </div>
        </div>
    </div>

    <!-- ===================== MAIN APP SHELL ===================== -->
    <div class="flex h-screen overflow-hidden">

        <!-- Desktop Sidebar — sticky, part of flex flow, hidden on mobile -->
        @include('layouts.sidebar')

        <!-- Right Column: Topbar + Page Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            @include('layouts.topbar')

            <main class="flex-1 overflow-y-auto overflow-x-hidden p-4 md:p-6 custom-scrollbar bg-slate-50 dark:bg-slate-900">
                <div class="mx-auto max-w-[1400px]">
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