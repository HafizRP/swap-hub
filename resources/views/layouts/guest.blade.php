<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('title') @yield('title') - @endif{{ config('app.name', 'Swap Hub') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">

    <!-- Google Fonts -->
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

<body class="antialiased font-sans bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 min-h-screen flex flex-col justify-center items-center p-4 relative selection:bg-indigo-500 selection:text-white"
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

    <!-- Ambient background glows -->
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-500/10 dark:bg-indigo-600/15 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-emerald-500/10 dark:bg-emerald-600/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Floating Theme Switcher & Home Link -->
    <div class="fixed top-5 right-5 z-50 flex items-center gap-2">
        <a href="/" class="p-2.5 rounded-full bg-white/80 dark:bg-slate-800/80 backdrop-blur border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all shadow-sm flex items-center gap-1.5 text-xs font-semibold px-3.5 no-underline" title="Back to Home">
            <i class="bi bi-house"></i>
            <span class="hidden sm:inline">Home</span>
        </a>
        <button type="button" @click="toggleTheme()" class="p-2.5 rounded-full bg-white/80 dark:bg-slate-800/80 backdrop-blur border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all shadow-sm" aria-label="Toggle theme">
            <i class="bi" :class="darkMode ? 'bi-sun-fill text-amber-400' : 'bi-moon-stars-fill text-indigo-500'"></i>
        </button>
    </div>

    <div class="w-full max-w-md my-auto">
        <!-- Brand Header -->
        <div class="text-center mb-6">
            <a href="/" class="inline-flex items-center gap-2.5 no-underline group">
                <div class="w-11 h-11 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/25 group-hover:scale-105 transition-transform">
                    <img src="{{ asset('icon.png') }}" alt="Swap Hub" class="w-7 h-7 object-contain">
                </div>
                <span class="text-2xl font-black text-slate-900 dark:text-slate-100 tracking-tight">Swap Hub</span>
            </a>
        </div>

        <!-- Card Container -->
        <div class="bg-white dark:bg-slate-900/90 backdrop-blur-xl border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xl shadow-slate-200/50 dark:shadow-black/40">
            @if(isset($slot) && !empty((string)$slot))
                {{ $slot }}
            @else
                @yield('content')
            @endif
        </div>
    </div>
</body>

</html>