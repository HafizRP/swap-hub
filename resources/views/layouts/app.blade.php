<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('title') @yield('title') - @endif{{ config('app.name', 'Swap Hub') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        [data-bs-theme="light"] {
            --bs-font-sans-serif: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            --bs-body-bg: #f8fafc;
            --bs-primary: #4f46e5;
            --bs-primary-rgb: 79, 70, 229;
            --bs-sidebar-bg: #ffffff;
            --bs-border-color: #e2e8f0;
        }

        [data-bs-theme="dark"] {
            --bs-font-sans-serif: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            --bs-body-bg: #0b1120;
            --bs-body-color: #e2e8f0;
            --bs-primary: #6366f1;
            --bs-primary-rgb: 99, 102, 241;
            --bs-sidebar-bg: #1e293b;
            --bs-border-color: rgba(255, 255, 255, 0.08);
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color, #1e293b);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Dark Mode Utility Overrides */
        [data-bs-theme="dark"] .bg-white {
            background-color: #1e293b !important;
            color: #e2e8f0;
        }

        [data-bs-theme="dark"] .bg-light {
            background-color: #0f172a !important;
        }

        [data-bs-theme="dark"] .text-dark {
            color: #f1f5f9 !important;
        }

        [data-bs-theme="dark"] .text-secondary {
            color: #94a3b8 !important;
        }

        [data-bs-theme="dark"] .border,
        [data-bs-theme="dark"] .border-top,
        [data-bs-theme="dark"] .border-bottom,
        [data-bs-theme="dark"] .border-end,
        [data-bs-theme="dark"] .border-start {
            border-color: var(--bs-border-color) !important;
        }

        [data-bs-theme="dark"] .btn-light {
            background-color: rgba(255, 255, 255, 0.05);
            border-color: transparent;
            color: #e2e8f0;
        }

        [data-bs-theme="dark"] .btn-light:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        [data-bs-theme="dark"] .nav-link:hover:not(.active) {
            background-color: rgba(255, 255, 255, 0.05);
            color: #f1f5f9 !important;
        }

        [data-bs-theme="dark"] .dropdown-menu {
            background-color: #1e293b;
            border-color: var(--bs-border-color);
        }

        [data-bs-theme="dark"] .dropdown-item {
            color: #e2e8f0;
        }

        [data-bs-theme="dark"] .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        [data-bs-theme="dark"] .input-group-text,
        [data-bs-theme="dark"] input::placeholder {
            color: #94a3b8 !important;
        }

        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        .btn-primary:hover {
            background-color: #4338ca;
            border-color: #4338ca;
        }

        [data-bs-theme="dark"] .btn-primary:hover {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }

        .text-primary {
            color: var(--bs-primary) !important;
        }

        .bg-primary {
            background-color: var(--bs-primary) !important;
        }

        /* Sidebar Link Active State */
        .nav-link.active {
            background-color: rgba(79, 70, 229, 0.1);
            color: var(--bs-primary) !important;
            font-weight: 600;
        }

        .nav-link {
            transition: all 0.2s;
        }

        .nav-link:hover:not(.active) {
            background-color: #f1f5f9;
            color: #1e293b !important;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        [data-bs-theme="dark"] .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #475569;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Card Hover Effect */
        .hover-lift {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .05) !important;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    @livewireStyles
    <!-- Theme Init -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.setAttribute('data-bs-theme', 'dark');
        } else {
            document.documentElement.setAttribute('data-bs-theme', 'light');
        }
    </script>
</head>

<body class="antialiased" x-data="{ 
    sidebarExpanded: localStorage.getItem('sidebarExpanded') === null ? true : localStorage.getItem('sidebarExpanded') === 'true',
    sidebarOpenMobile: false,
    darkMode: localStorage.getItem('theme') === 'dark',
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
        document.documentElement.setAttribute('data-bs-theme', this.darkMode ? 'dark' : 'light');
    },
    init() {
        this.$watch('darkMode', val => {
            document.documentElement.setAttribute('data-bs-theme', val ? 'dark' : 'light');
        });
    }
}" @resize.window="if(window.innerWidth >= 768) sidebarOpenMobile = false">
    <div class="d-flex min-vh-100">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Mobile Overlay -->
        <div x-show="sidebarOpenMobile" class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-md-none"
            style="z-index: 1045;" @click="sidebarOpenMobile = false" x-transition.opacity></div>

        <!-- Main Content Wrapper -->
        <div class="flex-grow-1 d-flex flex-column" style="min-width: 0;">
            <!-- Topbar -->
            @include('layouts.topbar')

            <!-- Main Page Content -->
            <main class="flex-grow-1 overflow-auto p-4 custom-scrollbar bg-light">
                <div class="container-fluid" style="max-width: 1400px;">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @livewireScripts
    @stack('scripts')

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
                                    const body = e.content.substring(0, 100);

                                    try {
                                        const n = new Notification(title, { body: body, icon: e.user_avatar });
                                        n.onclick = function () { window.focus(); window.location.href = `/chat/${e.conversation_id}`; }
                                    } catch (err) { }
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
</body>

</html>