<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Swap Hub') }} | Platform Kolaborasi Mahasiswa</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

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

<body class="antialiased font-sans text-slate-800 dark:text-slate-100 bg-slate-50 dark:bg-slate-950 selection:bg-brand-500 selection:text-white transition-colors duration-200"
      x-data="{
          mobileMenu: false,
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

    <!-- Top Navigation Bar -->
    <header class="fixed top-0 inset-x-0 z-50 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- Brand -->
            <a href="/" class="flex items-center gap-2.5 group">
                <img src="{{ asset('icon.png') }}" alt="Swap Hub" class="w-8 h-8 rounded-lg shadow-sm group-hover:scale-105 transition-transform">
                <span class="text-xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                    Swap<span class="text-brand-500">Hub</span>
                </span>
            </a>

            <!-- Desktop Navigation Links -->
            <nav class="hidden md:flex items-center gap-8">
                <a href="#fitur" class="text-sm font-medium text-slate-600 hover:text-brand-600 dark:text-slate-300 dark:hover:text-brand-400 transition-colors">Fitur Utama</a>
                <a href="#cara-kerja" class="text-sm font-medium text-slate-600 hover:text-brand-600 dark:text-slate-300 dark:hover:text-brand-400 transition-colors">Cara Kerja</a>
                <a href="#keunggulan" class="text-sm font-medium text-slate-600 hover:text-brand-600 dark:text-slate-300 dark:hover:text-brand-400 transition-colors">Eksplorasi</a>
            </nav>

            <!-- Actions -->
            <div class="flex items-center gap-3">
                <!-- Theme Toggle Button -->
                <button @click="toggleTheme()"
                        class="p-2 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                        title="Toggle Dark Mode">
                    <i class="bi text-lg" :class="darkMode ? 'bi-sun-fill text-amber-400' : 'bi-moon-stars-fill'"></i>
                </button>

                @auth
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-xl shadow-sm hover:shadow transition-all">
                        <span>Dashboard</span>
                        <i class="bi bi-arrow-right text-xs"></i>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="hidden sm:inline-flex px-4 py-2 text-sm font-semibold text-slate-700 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-xl shadow-sm hover:shadow transition-all">
                        <span>Daftar Sekarang</span>
                        <i class="bi bi-arrow-right text-xs"></i>
                    </a>
                @endauth

                <!-- Mobile Menu Button -->
                <button @click="mobileMenu = !mobileMenu"
                        class="md:hidden p-2 text-slate-600 dark:text-slate-300 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
                    <i class="bi text-xl" :class="mobileMenu ? 'bi-x-lg' : 'bi-list'"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenu"
             x-cloak
             x-transition
             class="md:hidden border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 pt-2 pb-4 space-y-2">
            <a href="#fitur" @click="mobileMenu = false" class="block px-3 py-2 rounded-lg text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800">Fitur Utama</a>
            <a href="#cara-kerja" @click="mobileMenu = false" class="block px-3 py-2 rounded-lg text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800">Cara Kerja</a>
            <a href="#keunggulan" @click="mobileMenu = false" class="block px-3 py-2 rounded-lg text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800">Eksplorasi</a>
            @guest
                <div class="pt-2 border-t border-slate-200 dark:border-slate-800">
                    <a href="{{ route('login') }}" class="block w-full text-center py-2 text-sm font-semibold text-slate-700 dark:text-slate-200">Masuk Akun</a>
                </div>
            @endguest
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 md:pt-40 md:pb-28 overflow-hidden">
        <!-- Background Ambient Glows -->
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[350px] bg-gradient-to-tr from-brand-500/20 to-emerald-500/10 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">

                <!-- Left Column: Copy & CTAs -->
                <div class="lg:col-span-7 space-y-6 text-left">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-brand-500/10 border border-brand-500/20 text-brand-600 dark:text-brand-400 text-xs font-semibold">
                        <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></span>
                        Platform Kolaborasi Mahasiswa No. 1
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 dark:text-white leading-[1.15]">
                        Temukan partner proyek. <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-500 to-indigo-600 dark:from-brand-400 dark:to-indigo-300">
                            Tukar skill & bangun portofolio
                        </span> nyata.
                    </h1>

                    <p class="text-base sm:text-lg text-slate-600 dark:text-slate-300 max-w-2xl leading-relaxed">
                        Hubungkan keahlianmu dengan rekan tim lintas universitas. Kelola tugas secara realtime dengan Kanban, integrasi commit GitHub otomatis, dan dapatkan poin reputasi terverifikasi.
                    </p>

                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center justify-center gap-2 px-6 py-3.5 text-base font-semibold text-white bg-brand-600 hover:bg-brand-700 active:scale-[0.98] rounded-xl shadow-lg shadow-brand-500/25 transition-all">
                            <span>Mulai Kolaborasi Gratis</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center justify-center gap-2 px-5 py-3.5 text-base font-semibold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800 rounded-xl transition-all">
                            <i class="bi bi-compass"></i>
                            <span>Jelajahi Proyek</span>
                        </a>
                    </div>

                    <!-- Social Proof Micro Strip -->
                    <div class="pt-4 flex items-center gap-4">
                        <div class="flex -space-x-2.5 overflow-hidden">
                            <img class="inline-block h-9 w-9 rounded-full ring-2 ring-white dark:ring-slate-900 object-cover" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80" alt="Student">
                            <img class="inline-block h-9 w-9 rounded-full ring-2 ring-white dark:ring-slate-900 object-cover" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&auto=format&fit=crop&q=80" alt="Student">
                            <img class="inline-block h-9 w-9 rounded-full ring-2 ring-white dark:ring-slate-900 object-cover" src="https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?w=100&auto=format&fit=crop&q=80" alt="Student">
                            <div class="h-9 w-9 rounded-full ring-2 ring-white dark:ring-slate-900 bg-slate-200 dark:bg-slate-800 flex items-center justify-center text-[11px] font-bold text-slate-700 dark:text-slate-300">
                                +2k
                            </div>
                        </div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">
                            <strong class="font-semibold text-slate-800 dark:text-slate-200">2,000+ Mahasiswa</strong> aktif berkolaborasi dan membangun portofolio bersama.
                        </div>
                    </div>
                </div>

                <!-- Right Column: Interactive UI Showcase Preview -->
                <div class="lg:col-span-5 relative">
                    <div class="bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-2xl p-5 sm:p-6 relative hover-card">

                        <!-- Window Header -->
                        <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100 dark:border-slate-800">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                                <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                                <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                                <span class="text-xs font-semibold text-slate-400 ml-2">Swap Hub Workspace</span>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Live Sync
                            </span>
                        </div>

                        <!-- Active Project Card Mockup -->
                        <div class="space-y-4">
                            <div class="bg-slate-50 dark:bg-slate-800/60 p-4 rounded-xl border border-slate-200/60 dark:border-slate-700/50">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-brand-600 dark:text-brand-400">Web Development</span>
                                        <h4 class="text-base font-bold text-slate-900 dark:text-white mt-0.5">EduMatch - AI Study Partner</h4>
                                    </div>
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                                        Active Squad
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 line-clamp-2">
                                    Membangun platform rekomendasi belajar berbasis AI dengan stack Laravel, Tailwind, dan OpenAI.
                                </p>
                                <div class="flex flex-wrap gap-1.5 mt-3">
                                    <span class="px-2 py-0.5 text-[10px] font-semibold bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded border border-slate-200 dark:border-slate-600">Laravel</span>
                                    <span class="px-2 py-0.5 text-[10px] font-semibold bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded border border-slate-200 dark:border-slate-600">Tailwind</span>
                                    <span class="px-2 py-0.5 text-[10px] font-semibold bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded border border-slate-200 dark:border-slate-600">UI/UX</span>
                                </div>
                            </div>

                            <!-- Interactive Mini Feed -->
                            <div class="space-y-2.5">
                                <div class="flex items-center gap-3 p-2.5 rounded-lg bg-emerald-50/70 dark:bg-emerald-950/20 border border-emerald-200/40 dark:border-emerald-800/40">
                                    <i class="bi bi-github text-emerald-600 dark:text-emerald-400 text-base shrink-0"></i>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 truncate mb-0">Commit #d726d87 merged to main</p>
                                        <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-medium">+10 Reputation XP diberikan</span>
                                    </div>
                                    <span class="text-[10px] text-slate-400">2m lalu</span>
                                </div>

                                <div class="flex items-center gap-3 p-2.5 rounded-lg bg-indigo-50/70 dark:bg-indigo-950/20 border border-indigo-200/40 dark:border-indigo-800/40">
                                    <i class="bi bi-check2-circle text-indigo-600 dark:text-indigo-400 text-base shrink-0"></i>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 truncate mb-0">Task Kanban diselesaikan</p>
                                        <span class="text-[10px] text-indigo-600 dark:text-indigo-400 font-medium">Design System Refactor selesai</span>
                                    </div>
                                    <span class="text-[10px] text-slate-400">Baru saja</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Bento Features Grid Section -->
    <section id="fitur" class="py-20 bg-slate-100/60 dark:bg-slate-900/50 border-y border-slate-200/70 dark:border-slate-800 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs font-extrabold uppercase tracking-wider text-brand-600 dark:text-brand-400">Fitur Dirancang Untuk Mahasiswa</span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white mt-2 mb-4">
                    Semua yang Kamu Butuhkan untuk Kolaborasi
                </h2>
                <p class="text-base text-slate-600 dark:text-slate-400">
                    Bukan sekadar group chat biasa. Swap Hub menyediakan ekosistem terpadu untuk mengeksekusi proyek nyata dari nol hingga rilis.
                </p>
            </div>

            <!-- Bento 5-Cell Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Bento 1: Matchmaking (Large 2 Cols) -->
                <div class="md:col-span-2 bg-white dark:bg-slate-800/90 rounded-2xl p-7 border border-slate-200/80 dark:border-slate-700/80 shadow-sm hover-card flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center text-2xl mb-5">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-2">Skill Matchmaking & Squad Finder</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed max-w-xl">
                            Cari tim berdasarkan keahlian yang spesifik (Backend, Frontend, Mobile, UI/UX, Data Science). Ajukan diri untuk bergabung ke proyek impian dengan satu klik.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2 mt-6 pt-4 border-t border-slate-100 dark:border-slate-700/50">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-brand-50 text-brand-700 dark:bg-brand-950/40 dark:text-brand-300">Smart Skill Matching</span>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300">Approval Workflow</span>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300">Multi-University</span>
                    </div>
                </div>

                <!-- Bento 2: Reputation System -->
                <div class="bg-white dark:bg-slate-800/90 rounded-2xl p-7 border border-slate-200/80 dark:border-slate-700/80 shadow-sm hover-card flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center text-2xl mb-5">
                            <i class="bi bi-trophy-fill"></i>
                        </div>
                        <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-2">Reputasi & Peer Review</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            Validasi keahlianmu lewat review rekan tim saat proyek selesai. Kumpulkan poin reputasi untuk kredibilitas profil.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-700/50">
                        <div class="flex items-center justify-between text-xs font-semibold">
                            <span class="text-slate-500 dark:text-slate-400">Status Anggota</span>
                            <span class="text-amber-600 dark:text-amber-400 font-bold">Elite Verified</span>
                        </div>
                    </div>
                </div>

                <!-- Bento 3: GitHub Integration -->
                <div class="bg-white dark:bg-slate-800/90 rounded-2xl p-7 border border-slate-200/80 dark:border-slate-700/80 shadow-sm hover-card flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-slate-900 text-white dark:bg-slate-700 flex items-center justify-center text-2xl mb-5">
                            <i class="bi bi-github"></i>
                        </div>
                        <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-2">GitHub Webhooks Otomatis</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            Sambungkan repository GitHub ke proyek. Setiap commit dan pull request tercatat langsung di feed aktivitas tim.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-700/50">
                        <span class="text-xs font-mono text-slate-500 dark:text-slate-400">HMAC SHA256 Verified</span>
                    </div>
                </div>

                <!-- Bento 4: Interactive Kanban (Large 2 Cols) -->
                <div class="md:col-span-2 bg-white dark:bg-slate-800/90 rounded-2xl p-7 border border-slate-200/80 dark:border-slate-700/80 shadow-sm hover-card flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-2xl mb-5">
                            <i class="bi bi-kanban-fill"></i>
                        </div>
                        <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-2">Live Kanban Board & Google Calendar Sync</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed max-w-xl">
                            Pantau progres tugas dari To-Do, In Progress, hingga Done. Tenggat waktu tugas otomatis tersinkronisasi dengan Google Calendar masing-masing anggota.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2 mt-6 pt-4 border-t border-slate-100 dark:border-slate-700/50">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300">Prioritas Tugas</span>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300">Due Date Tracking</span>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300">Auto Calendar Event</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="cara-kerja" class="py-20 bg-white dark:bg-slate-950 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs font-extrabold uppercase tracking-wider text-brand-600 dark:text-brand-400">Langkah Mudah</span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white mt-2 mb-4">
                    Mulai Kolaborasi dalam 3 Menit
                </h2>
                <p class="text-base text-slate-600 dark:text-slate-400">
                    Alur praktis untuk mahasiswa yang ingin menciptakan dampak nyata.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                <!-- Step 1 -->
                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200/70 dark:border-slate-800 text-left">
                    <div class="w-10 h-10 rounded-xl bg-brand-600 text-white font-bold flex items-center justify-center mb-4 text-base">
                        1
                    </div>
                    <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Buat Profil & Daftarkan Skill</h4>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                        Lengkapi profil dengan jurusan, universitas, portofolio GitHub, dan skill yang kamu kuasai.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200/70 dark:border-slate-800 text-left">
                    <div class="w-10 h-10 rounded-xl bg-brand-600 text-white font-bold flex items-center justify-center mb-4 text-base">
                        2
                    </div>
                    <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Bikin Proyek atau Gabung Squad</h4>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                        Mulai ide proyek baru dan rekrut teman, atau cari proyek terbuka yang membutuhkan skill keahlianmu.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200/70 dark:border-slate-800 text-left">
                    <div class="w-10 h-10 rounded-xl bg-brand-600 text-white font-bold flex items-center justify-center mb-4 text-base">
                        3
                    </div>
                    <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Eksekusi & Export Resume PDF</h4>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                        Kerjakan tugas bersama di Kanban dan chat. Saat selesai, unduh portofolio resume PDF resmi dari Swap Hub.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Banner -->
    <section class="py-16 bg-gradient-to-br from-brand-600 to-indigo-800 text-white relative overflow-hidden">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 space-y-6">
            <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                Siap Memulai Proyek Kolaborasi Pertamamu?
            </h2>
            <p class="text-base sm:text-lg text-brand-100 max-w-2xl mx-auto">
                Bergabunglah dengan ribuan mahasiswa lainnya dan buktikan kemampuanmu dengan portofolio yang teruji.
            </p>
            <div class="pt-2">
                <a href="{{ route('register') }}"
                   class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-bold text-brand-900 bg-white hover:bg-slate-100 rounded-xl shadow-xl active:scale-[0.98] transition-all">
                    <span>Gabung Swap Hub Sekarang</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white dark:bg-slate-950 border-t border-slate-200 dark:border-slate-800 py-12 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('icon.png') }}" alt="Swap Hub" class="w-7 h-7 rounded">
                <span class="text-base font-bold text-slate-900 dark:text-white">Swap<span class="text-brand-500">Hub</span></span>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-0">
                &copy; {{ date('Y') }} Swap Hub. Platform Kolaborasi Mahasiswa. All rights reserved.
            </p>
            <div class="flex items-center gap-4 text-slate-400">
                <a href="#" class="hover:text-brand-500 transition-colors"><i class="bi bi-github text-lg"></i></a>
                <a href="#" class="hover:text-brand-500 transition-colors"><i class="bi bi-discord text-lg"></i></a>
            </div>
        </div>
    </footer>

</body>
</html>
