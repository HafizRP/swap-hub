<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'Swap Hub') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">

    @include('partials.pwa-head')

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Dark Mode Init: prevent theme flashing -->
    <script>
        (function() {
            var theme = localStorage.getItem('theme');
            var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (theme === 'dark' || (!theme && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
</head>

<body class="antialiased font-sans bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 min-h-screen flex flex-col justify-between selection:bg-indigo-500 selection:text-white"
    x-data="{
        darkMode: localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches),
        toggleTheme() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    }">

    <!-- Floating Theme Switcher & Back to Home -->
    <div class="fixed top-5 right-5 z-50 flex items-center gap-2">
        <a href="/" class="p-2.5 rounded-full bg-white/80 dark:bg-slate-800/80 backdrop-blur border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:border-indigo-300 dark:hover:border-indigo-600 transition-all shadow-sm flex items-center gap-1.5 text-xs font-semibold px-3.5" title="Back to Homepage">
            <i class="bi bi-house text-sm"></i>
            <span class="hidden sm:inline">Home</span>
        </a>
        <button type="button" @click="toggleTheme()" class="p-2.5 rounded-full bg-white/80 dark:bg-slate-800/80 backdrop-blur border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:border-indigo-300 dark:hover:border-indigo-600 transition-all shadow-sm" aria-label="Toggle theme">
            <i class="bi" :class="darkMode ? 'bi-sun-fill text-amber-400' : 'bi-moon-stars-fill text-indigo-500'"></i>
        </button>
    </div>

    <!-- Toast Notifications Container -->
    <div class="fixed top-5 left-1/2 -translate-x-1/2 sm:translate-x-0 sm:left-auto sm:right-5 z-[9999] flex flex-col gap-2.5 w-full max-w-sm px-4 pointer-events-none" id="toastContainer"></div>

    <main class="flex-grow flex flex-col">
        @yield('content')
    </main>

    <script>
        // Toast Notification System (Tailwind Styled)
        class ToastManager {
            constructor() {
                this.container = document.getElementById('toastContainer');
            }

            show(message, type = 'info', title = null, duration = 5000) {
                const toast = document.createElement('div');
                const borderClass = type === 'error' ? 'border-red-200 dark:border-red-800/60' : 
                                   (type === 'success' ? 'border-emerald-200 dark:border-emerald-800/60' : 'border-indigo-200 dark:border-indigo-800/60');
                toast.className = `pointer-events-auto flex items-start gap-3 p-4 rounded-2xl bg-white dark:bg-slate-900 border ${borderClass} shadow-xl shadow-slate-900/10 dark:shadow-black/50 transition-all duration-300 ease-out transform translate-y-2 opacity-0`;

                const icons = {
                    error: '<div class="w-8 h-8 rounded-xl bg-red-100 dark:bg-red-950/60 text-red-600 dark:text-red-400 flex items-center justify-center shrink-0"><i class="bi bi-x-circle-fill text-base"></i></div>',
                    success: '<div class="w-8 h-8 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0"><i class="bi bi-check-circle-fill text-base"></i></div>',
                    warning: '<div class="w-8 h-8 rounded-xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0"><i class="bi bi-exclamation-triangle-fill text-base"></i></div>',
                    info: '<div class="w-8 h-8 rounded-xl bg-indigo-100 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0"><i class="bi bi-info-circle-fill text-base"></i></div>'
                };

                const titles = {
                    error: title || 'Error',
                    success: title || 'Success',
                    warning: title || 'Notice',
                    info: title || 'Info'
                };

                toast.innerHTML = `
                    ${icons[type] || icons.info}
                    <div class="flex-grow min-w-0 pr-1">
                        <div class="text-xs font-bold text-slate-900 dark:text-slate-100 mb-0.5">${titles[type]}</div>
                        <div class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">${message}</div>
                    </div>
                    <button type="button" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1 transition-colors" onclick="this.closest('div.pointer-events-auto').remove()">
                        <i class="bi bi-x-lg text-xs"></i>
                    </button>
                `;

                this.container.appendChild(toast);
                requestAnimationFrame(() => {
                    toast.classList.remove('translate-y-2', 'opacity-0');
                    toast.classList.add('translate-y-0', 'opacity-100');
                });

                if (duration > 0) {
                    setTimeout(() => {
                        toast.classList.add('opacity-0', '-translate-y-2');
                        setTimeout(() => toast.remove(), 300);
                    }, duration);
                }

                return toast;
            }

            error(message, title = null) {
                return this.show(message, 'error', title);
            }

            success(message, title = null) {
                return this.show(message, 'success', title);
            }

            warning(message, title = null) {
                return this.show(message, 'warning', title);
            }
        }

        const toast = new ToastManager();

        // Show Laravel validation errors as toasts
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toast.error('{{ $error }}');
            @endforeach
        @endif

        // Show success messages
        @if (session('status'))
            toast.success('{{ session('status') }}');
        @endif

        @if (session('success'))
            toast.success('{{ session('success') }}');
        @endif

        // Form validation helper
        function validateInput(input) {
            const value = input.value.trim();
            const type = input.type;
            let isValid = true;

            if (input.required && !value) {
                isValid = false;
            }

            if (type === 'email' && value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                isValid = emailRegex.test(value);
            }

            if (type === 'password' && value && input.name === 'password') {
                isValid = value.length >= 8;
            }

            if (input.name === 'password_confirmation') {
                const password = document.getElementById('password');
                isValid = password && value === password.value;
            }

            // Update visual state
            if (value) {
                if (isValid) {
                    input.classList.remove('error');
                    input.classList.add('valid');
                } else {
                    input.classList.add('error');
                    input.classList.remove('valid');
                }
            } else {
                input.classList.remove('error', 'valid');
            }

            return isValid;
        }

        // Auto-validate on blur
        document.addEventListener('DOMContentLoaded', function () {
            const inputs = document.querySelectorAll('.form-input');
            inputs.forEach(input => {
                input.addEventListener('blur', () => validateInput(input));
                input.addEventListener('input', () => {
                    if (input.classList.contains('error')) {
                        validateInput(input);
                    }
                });
            });

            // Form submit with loading state
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.classList.add('loading');
                        submitBtn.disabled = true;
                    }
                });
            });
        });
    </script>

    @include('partials.pwa-install-prompt')
</body>

</html>