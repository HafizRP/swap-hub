<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('title') @yield('title') - @endif{{ config('app.name', 'Swap Hub') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" type="image/png" href="{{ asset('icon.png') }}">

    @include('partials.pwa-head')

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
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

<body class="antialiased font-sans bg-[#fafaf9] dark:bg-[#141210] text-stone-900 dark:text-stone-100 min-h-screen flex flex-col justify-center items-center p-4 relative selection:bg-teal-600 selection:text-white"
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
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-teal-500/10 dark:bg-teal-600/15 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-emerald-500/10 dark:bg-emerald-600/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Floating Theme Switcher & Home Link -->
    <div class="fixed top-5 right-5 z-50 flex items-center gap-2">
        <a href="/" class="p-2.5 rounded-full bg-white/90 dark:bg-[#2e2c29]/80 backdrop-blur border border-stone-200 dark:border-stone-800 text-stone-600 dark:text-stone-300 hover:text-teal-600 dark:hover:text-teal-400 hover:border-teal-300 dark:hover:border-teal-600 transition-all shadow-sm flex items-center gap-1.5 text-xs font-semibold px-3.5 no-underline" title="Back to Home">
            <i class="bi bi-house"></i>
            <span class="hidden sm:inline">Home</span>
        </a>
        <button type="button" @click="toggleTheme()" class="p-2.5 rounded-full bg-white/90 dark:bg-[#2e2c29]/80 backdrop-blur border border-stone-200 dark:border-stone-800 text-stone-600 dark:text-stone-300 hover:text-teal-600 dark:hover:text-teal-400 hover:border-teal-300 dark:hover:border-teal-600 transition-all shadow-sm" aria-label="Toggle theme">
            <i class="bi" :class="darkMode ? 'bi-sun-fill text-amber-400' : 'bi-moon-stars-fill text-teal-600'"></i>
        </button>
    </div>

    <div class="w-full max-w-md my-auto">
        <!-- Brand Header -->
        <div class="text-center mb-6">
            <a href="/" class="inline-flex items-center gap-2.5 no-underline group">
                <x-application-logo class="w-10 h-10 rounded-lg shadow-lg shadow-teal-500/20 group-hover:scale-105 transition-transform" />
                <span class="text-2xl font-black text-stone-900 dark:text-stone-100 tracking-tight">Swap Hub</span>
            </a>
        </div>

        <!-- Card Container -->
        <div class="bg-white dark:bg-[#141414] border border-stone-200 dark:border-stone-800 rounded-xl p-4 sm:p-4 shadow-xl">
            @if(isset($slot) && !empty((string)$slot))
                {{ $slot }}
            @else
                @yield('content')
            @endif
        </div>
    </div>
    @include('partials.pwa-install-prompt')
    @livewireScripts
</body>

</html>