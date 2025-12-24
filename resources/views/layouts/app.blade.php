<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('title') @yield('title') - @endif{{ config('app.name', 'Swap Hub') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #0b1120;
            color: #e2e8f0;
        }

        .navbar {
            background-color: rgba(11, 17, 32, 0.8) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .card {
            background-color: #1e293b;
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 1.5rem;
        }

        .btn-primary {
            background-color: #4f46e5;
            border: none;
            border-radius: 0.75rem;
            padding: 0.6rem 1.5rem;
            font-weight: 700;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #4338ca;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4);
        }

        /* Chat Hover Styles */
        .hover-bg-light:hover {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }

        /* Dark Mode - Ensure labels are visible */
        [data-bs-theme="dark"] .text-uppercase,
        [data-bs-theme="dark"] .tracking-widest,
        [data-bs-theme="dark"] .tracking-wide {
            color: rgba(255, 255, 255, 0.7) !important;
        }

        [data-bs-theme="dark"] .text-secondary {
            color: #94a3b8 !important;
        }

        /* Smooth Theme Transition */
        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        /* Light Mode Overrides */
        [data-bs-theme="light"] body {
            background-color: #f8fafc;
            color: #1e293b;
        }

        [data-bs-theme="light"] .navbar {
            background-color: rgba(255, 255, 255, 0.9) !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        [data-bs-theme="light"] .card {
            background-color: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        [data-bs-theme="light"] .text-white {
            color: #1e293b !important;
        }

        [data-bs-theme="light"] .text-secondary {
            color: #64748b !important;
        }

        [data-bs-theme="light"] .bg-dark {
            background-color: #f1f5f9 !important;
        }

        /* Light Mode - Navbar Specific Overrides */
        [data-bs-theme="light"] .navbar {
            color: #1e293b !important;
        }

        [data-bs-theme="light"] .navbar-brand,
        [data-bs-theme="light"] .nav-link {
            color: #1e293b !important;
        }

        [data-bs-theme="light"] .nav-link:hover,
        [data-bs-theme="light"] .nav-link.active {
            color: #4f46e5 !important;
        }

        [data-bs-theme="light"] .navbar .dropdown-toggle {
            color: #1e293b !important;
        }

        [data-bs-theme="light"] .dropdown-menu {
            background-color: #ffffff !important;
            border: 1px solid rgba(0, 0, 0, 0.1) !important;
        }

        [data-bs-theme="light"] .dropdown-item {
            color: #1e293b !important;
        }

        [data-bs-theme="light"] .dropdown-item:hover {
            background-color: #f1f5f9 !important;
        }

        /* Light Mode - General Text Overrides */
        [data-bs-theme="light"] h1,
        [data-bs-theme="light"] h2,
        [data-bs-theme="light"] h3,
        [data-bs-theme="light"] h4,
        [data-bs-theme="light"] h5,
        [data-bs-theme="light"] h6 {
            color: #1e293b !important;
        }

        [data-bs-theme="light"] .fw-bold,
        [data-bs-theme="light"] .fw-black {
            color: #1e293b !important;
        }

        [data-bs-theme="light"] .small {
            color: #64748b !important;
        }

        /* Light Mode - More Text Overrides */
        [data-bs-theme="light"] p,
        [data-bs-theme="light"] span,
        [data-bs-theme="light"] div {
            color: #1e293b;
        }

        [data-bs-theme="light"] a {
            color: #4f46e5;
        }

        [data-bs-theme="light"] a:hover {
            color: #4338ca;
        }

        /* Light Mode - Badge Overrides */
        [data-bs-theme="light"] .badge {
            color: #1e293b !important;
        }

        [data-bs-theme="light"] .badge.bg-primary {
            background-color: #4f46e5 !important;
            color: white !important;
        }

        [data-bs-theme="light"] .badge.bg-success {
            background-color: #10b981 !important;
            color: white !important;
        }

        [data-bs-theme="light"] .badge.bg-warning {
            background-color: #f59e0b !important;
            color: white !important;
        }

        [data-bs-theme="light"] .badge.bg-danger {
            background-color: #ef4444 !important;
            color: white !important;
        }

        /* Light Mode - Opacity Classes */
        [data-bs-theme="light"] .opacity-75 {
            opacity: 0.75 !important;
        }

        [data-bs-theme="light"] .opacity-50 {
            opacity: 0.5 !important;
        }

        /* Light Mode - Button Text */
        [data-bs-theme="light"] .btn-primary {
            background-color: #4f46e5 !important;
            color: white !important;
            border-color: #4f46e5 !important;
        }

        [data-bs-theme="light"] .btn-outline-primary {
            color: #4f46e5 !important;
            border-color: #4f46e5 !important;
        }

        [data-bs-theme="light"] .btn-outline-primary:hover {
            background-color: #4f46e5 !important;
            color: white !important;
        }

        /* Light Mode - Special Classes */
        [data-bs-theme="light"] .text-muted {
            color: #64748b !important;
        }

        [data-bs-theme="light"] .text-primary {
            color: #4f46e5 !important;
        }

        [data-bs-theme="light"] .text-success {
            color: #10b981 !important;
        }

        [data-bs-theme="light"] .text-warning {
            color: #f59e0b !important;
        }

        [data-bs-theme="light"] .text-danger {
            color: #ef4444 !important;
        }

        /* Light Mode - Label and Uppercase Text */
        [data-bs-theme="light"] .text-uppercase {
            color: #64748b !important;
        }

        [data-bs-theme="light"] .tracking-widest,
        [data-bs-theme="light"] .tracking-wide {
            color: #64748b !important;
        }

        /* Light Mode - Any remaining white/light text */
        [data-bs-theme="light"] .text-light {
            color: #1e293b !important;
        }

        [data-bs-theme="light"] .text-white-50 {
            color: #64748b !important;
        }

        /* Light Mode - Ensure all text in cards is dark */
        [data-bs-theme="light"] .card * {
            color: #1e293b;
        }

        [data-bs-theme="light"] .card .text-secondary {
            color: #64748b !important;
        }

        [data-bs-theme="light"] .card .small {
            color: #64748b !important;
        }

        /* Light Mode - Aggressive Catch-All Overrides */
        /* This ensures NO white text remains in light mode */

        /* Override ALL possible white text variations */
        [data-bs-theme="light"] .text-white,
        [data-bs-theme="light"] .text-light,
        [data-bs-theme="light"] .text-white-50,
        [data-bs-theme="light"] [class*="text-white"] {
            color: #1e293b !important;
        }

        /* Override opacity variations that might hide text */
        [data-bs-theme="light"] .opacity-25 {
            opacity: 0.4 !important;
        }

        /* Ensure all headings are dark */
        [data-bs-theme="light"] header *,
        [data-bs-theme="light"] .header * {
            color: #1e293b !important;
        }

        /* Ensure all main content is dark */
        [data-bs-theme="light"] main *:not(.btn):not(.badge):not(.bg-primary *):not(.bg-success *):not(.bg-warning *):not(.bg-danger *) {
            color: #1e293b;
        }

        /* Special override for any remaining edge cases */
        [data-bs-theme="light"] .container *:not(.btn):not(.badge):not(.bg-primary *) {
            color: #1e293b;
        }

        /* Ensure labels and form labels are visible */
        [data-bs-theme="light"] label,
        [data-bs-theme="light"] .form-label {
            color: #1e293b !important;
        }

        /* Ensure table text is dark */
        [data-bs-theme="light"] table,
        [data-bs-theme="light"] table * {
            color: #1e293b !important;
        }

        [data-bs-theme="light"] thead {
            color: #1e293b !important;
        }

        /* Ensure list text is dark */
        [data-bs-theme="light"] ul,
        [data-bs-theme="light"] ol,
        [data-bs-theme="light"] li {
            color: #1e293b;
        }

        /* Override any inline styles that might set white text */
        [data-bs-theme="light"] [style*="color: white"],
        [data-bs-theme="light"] [style*="color: #fff"],
        [data-bs-theme="light"] [style*="color: #ffffff"],
        [data-bs-theme="light"] [style*="color:white"],
        [data-bs-theme="light"] [style*="color:#fff"] {
            color: #1e293b !important;
        }

        /* Ensure SVG text is visible */
        [data-bs-theme="light"] svg text {
            fill: #1e293b !important;
        }

        /* Exception: Keep white text in colored backgrounds */
        [data-bs-theme="light"] .bg-primary,
        [data-bs-theme="light"] .bg-primary *,
        [data-bs-theme="light"] .btn-primary,
        [data-bs-theme="light"] .btn-primary *,
        [data-bs-theme="light"] .badge.bg-primary,
        [data-bs-theme="light"] .badge.bg-primary * {
            color: white !important;
        }

        [data-bs-theme="light"] .bg-success,
        [data-bs-theme="light"] .bg-success *,
        [data-bs-theme="light"] .badge.bg-success,
        [data-bs-theme="light"] .badge.bg-success * {
            color: white !important;
        }

        [data-bs-theme="light"] .bg-warning,
        [data-bs-theme="light"] .bg-warning *,
        [data-bs-theme="light"] .badge.bg-warning,
        [data-bs-theme="light"] .badge.bg-warning * {
            color: white !important;
        }

        [data-bs-theme="light"] .bg-danger,
        [data-bs-theme="light"] .bg-danger *,
        [data-bs-theme="light"] .badge.bg-danger,
        [data-bs-theme="light"] .badge.bg-danger * {
            color: white !important;
        }

        /* Ensure placeholder text is visible */
        [data-bs-theme="light"] ::placeholder {
            color: #94a3b8 !important;
        }

        [data-bs-theme="light"] ::-webkit-input-placeholder {
            color: #94a3b8 !important;
        }

        [data-bs-theme="light"] ::-moz-placeholder {
            color: #94a3b8 !important;
        }
    </style>

    <!-- Theme Detection Script (Before any render) -->
    <script>
        // Set theme immediately to prevent flash
        (function () {
            const darkMode = localStorage.getItem('darkMode');
            const theme = (darkMode === 'false') ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', theme);
        })();
    </script>

    <!-- Scripts -->
    @vite(['resources/js/app.js'])

    <!-- Livewire Styles -->
    @livewireStyles
</head>

<body class="antialiased">
    <div class="min-vh-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="py-4 border-bottom border-white-5">
                <div class="container">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="py-5">
            <div class="container">
                {{ $slot }}
            </div>
        </main>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- Stack Scripts -->
    @stack('scripts')

    <!-- Global Chat Notification Script -->
    @auth
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Request permission silently if not granted
                if ('Notification' in window && Notification.permission === 'default') {
                    Notification.requestPermission();
                }

                const userId = {{ auth()->id() }};
                window.currentConversationId = null;

                const initEcho = () => {
                    if (window.Echo) {
                        window.Echo.private(`App.Models.User.${userId}`)
                            .listen('.message.sent', (e) => {
                                // 1. Refresh Livewire Components (Sidebar)
                                if (window.Livewire) {
                                    window.Livewire.dispatch('refresh-conversation-list');
                                }

                                // 2. Ignore own messages
                                if (e.user_id == userId) return;

                                // 3. Ignore if viewing context
                                if (window.currentConversationId && window.currentConversationId == e.conversation_id) {
                                    return;
                                }

                                // 4. Show Notification
                                if ('Notification' in window && Notification.permission === 'granted') {
                                    const title = (e.conversation_type === 'project' && e.conversation_name)
                                        ? e.conversation_name
                                        : (e.user_name || 'New Message');

                                    const body = (e.conversation_type === 'project' && e.conversation_name)
                                        ? `${e.user_name}: ${e.content.substring(0, 100)}`
                                        : e.content.substring(0, 100);

                                    try {
                                        const n = new Notification(title, {
                                            body: body,
                                            icon: e.user_avatar
                                        });
                                        n.onclick = function () {
                                            window.focus();
                                            window.location.href = `/chat/${e.conversation_id}`;
                                        }
                                    } catch (err) {
                                        // Silent fail
                                    }
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
```