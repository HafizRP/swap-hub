<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Swap Hub') }} — Platform Kolaborasi Proyek & Skill Mahasiswa</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .hero-glow {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.12) 0%, rgba(99, 102, 241, 0) 70%);
            top: -150px;
            right: -100px;
            pointer-events: none;
            z-index: 0;
        }

        .hero-glow-left {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(79, 70, 229, 0.08) 0%, rgba(79, 70, 229, 0) 70%);
            bottom: -100px;
            left: -150px;
            pointer-events: none;
            z-index: 0;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 selection:bg-indigo-500 selection:text-white antialiased min-h-screen flex flex-col">

    <!-- Ambient Background Effects -->
    <div class="fixed inset-0 bg-subtle-grid pointer-events-none z-0"></div>

    <!-- Navigation Header -->
    <header class="sticky top-0 z-50 glass-surface border-b border-slate-200/80 transition-all duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand -->
            <a href="{{ url('/') }}" class="flex items-center gap-3 no-underline group">
                <div class="w-10 h-10 rounded-xl bg-indigo-600/10 flex items-center justify-center border border-indigo-500/20 group-hover:scale-105 transition-transform duration-200">
                    <img src="{{ asset('icon.png') }}" alt="Swap Hub" class="w-6 h-6 object-contain">
                </div>
                <span class="text-xl font-extrabold tracking-tight text-slate-900">
                    Swap<span class="text-indigo-600">Hub</span>
                </span>
            </a>

            <!-- Center Navigation Links -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
                <a href="#fitur" class="hover:text-indigo-600 transition-colors no-underline">Fitur</a>
                <a href="#cara-kerja" class="hover:text-indigo-600 transition-colors no-underline">Cara Kerja</a>
                <a href="{{ route('projects.index') }}" class="hover:text-indigo-600 transition-colors no-underline">Cari Proyek</a>
                <a href="#testimoni" class="hover:text-indigo-600 transition-colors no-underline">Testimoni</a>
            </nav>

            <!-- Actions -->
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-bold bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm transition-all duration-150 active:scale-[0.98] no-underline">
                        <span>Buka Dashboard</span>
                        <i class="bi bi-arrow-right text-xs"></i>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="text-sm font-bold text-slate-700 hover:text-indigo-600 px-3 py-2 transition-colors no-underline">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-full text-sm font-bold bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm transition-all duration-150 active:scale-[0.98] no-underline">
                        <span>Daftar Gratis</span>
                        <i class="bi bi-chevron-right text-xs"></i>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main class="flex-grow relative z-10">

        <!-- ================= HERO SECTION ================= -->
        <section class="relative pt-12 pb-20 md:pt-20 md:pb-32 overflow-hidden">
            <div class="hero-glow"></div>
            <div class="hero-glow-left"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                    
                    <!-- Left Hero Content -->
                    <div class="lg:col-span-7 space-y-8">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-50 border border-indigo-200/80 text-indigo-700 text-xs font-bold tracking-wide">
                            <i class="bi bi-stars text-indigo-500"></i>
                            <span>Kolaborasi Proyek Mahasiswa Seluruh Indonesia</span>
                        </div>

                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-[1.12]">
                            Bangun proyek impian.<br>
                            <span class="text-indigo-600">Buktikan skill nyata</span><br>
                            sejak masa kuliah.
                        </h1>

                        <p class="text-lg text-slate-600 max-w-xl leading-relaxed text-pretty font-normal">
                            Bukan sekadar tugas kelas. Temukan co-creator dengan keahlian komplementer, sinkronisasi deadline ke Google Calendar, dan kumpulkan validasi peer-review untuk portofolio siap kerja.
                        </p>

                        <!-- CTA Row -->
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
                            <a href="{{ route('register') }}"
                               class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl text-base font-bold bg-indigo-600 hover:bg-indigo-700 text-white shadow-lg shadow-indigo-600/20 transition-all duration-150 active:scale-[0.98] no-underline">
                                <span>Mulai Kolaborasi — Gratis</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                            <a href="{{ route('projects.index') }}"
                               class="inline-flex items-center justify-center gap-2 px-7 py-4 rounded-xl text-base font-semibold bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 shadow-sm transition-all duration-150 active:scale-[0.98] no-underline">
                                <i class="bi bi-compass text-slate-400"></i>
                                <span>Jelajahi Proyek Aktif</span>
                            </a>
                        </div>

                        <!-- Real Organic Social Proof -->
                        <div class="flex items-center gap-4 pt-4 border-t border-slate-200/60">
                            <div class="flex -space-x-2.5">
                                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&h=100&fit=crop&crop=faces&q=80"
                                     class="w-10 h-10 rounded-full border-2 border-white object-cover ring-1 ring-slate-100" alt="Student">
                                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&crop=faces&q=80"
                                     class="w-10 h-10 rounded-full border-2 border-white object-cover ring-1 ring-slate-100" alt="Student">
                                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop&crop=faces&q=80"
                                     class="w-10 h-10 rounded-full border-2 border-white object-cover ring-1 ring-slate-100" alt="Student">
                                <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&h=100&fit=crop&crop=faces&q=80"
                                     class="w-10 h-10 rounded-full border-2 border-white object-cover ring-1 ring-slate-100" alt="Student">
                            </div>
                            <div>
                                <div class="flex items-center gap-1 text-amber-500 text-xs">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <span class="text-slate-800 font-bold ml-1">4.9/5</span>
                                </div>
                                <p class="text-xs text-slate-500 font-medium mb-0">
                                    Dipercaya <span class="font-bold text-slate-800 tabular-nums">2,480+</span> mahasiswa di 45+ universitas
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Hero Visual Comp: Interactive Squad Match Preview -->
                    <div class="lg:col-span-5">
                        <div class="relative mx-auto max-w-md lg:max-w-none">
                            <!-- Background Accent Frame -->
                            <div class="absolute -inset-1.5 bg-gradient-to-tr from-indigo-500 to-indigo-300 rounded-3xl blur-lg opacity-30"></div>

                            <!-- Main Glass Card -->
                            <div class="relative bg-white rounded-2xl border border-slate-200 shadow-xl p-6 space-y-5">
                                <!-- Card Header -->
                                <div class="flex items-start justify-between pb-4 border-b border-slate-100">
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Sedang Merekrut
                                            </span>
                                            <span class="text-[11px] text-slate-400 font-medium">Batas: 14 Nov</span>
                                        </div>
                                        <h3 class="text-base font-bold text-slate-900 mb-0">E-Commerce Edukasi Maritim</h3>
                                        <p class="text-xs text-slate-500 mb-0">Teknologi Web • IoT • Desain UX</p>
                                    </div>
                                    <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">
                                        SH
                                    </div>
                                </div>

                                <!-- Squad Members Allocation -->
                                <div class="space-y-3">
                                    <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Susunan Tim (3/4 Anggota)</div>

                                    <!-- Member 1 -->
                                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                        <div class="flex items-center gap-3">
                                            <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=80&h=80&fit=crop&q=80"
                                                 class="w-8 h-8 rounded-full object-cover" alt="Rafi">
                                            <div>
                                                <p class="text-xs font-bold text-slate-900 mb-0">Rafi Pratama</p>
                                                <p class="text-[11px] text-slate-500 mb-0">Project Lead • ITB</p>
                                            </div>
                                        </div>
                                        <span class="text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">Fullstack</span>
                                    </div>

                                    <!-- Member 2 -->
                                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                        <div class="flex items-center gap-3">
                                            <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=80&h=80&fit=crop&q=80"
                                                 class="w-8 h-8 rounded-full object-cover" alt="Nadia">
                                            <div>
                                                <p class="text-xs font-bold text-slate-900 mb-0">Nadia Safitri</p>
                                                <p class="text-[11px] text-slate-500 mb-0">UI/UX Designer • UI</p>
                                            </div>
                                        </div>
                                        <span class="text-[11px] font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">Figma</span>
                                    </div>

                                    <!-- Open Slot Match Indicator -->
                                    <div class="p-3 rounded-xl border border-dashed border-indigo-300 bg-indigo-50/50 flex items-center justify-between">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold">
                                                <i class="bi bi-person-plus-fill"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold text-indigo-950 mb-0">Dicari: Backend Developer</p>
                                                <p class="text-[11px] text-indigo-600 mb-0">Match Score: 96% kecocokan</p>
                                            </div>
                                        </div>
                                        <span class="px-2.5 py-1 text-xs font-bold bg-indigo-600 text-white rounded-lg shadow-sm">
                                            Gabung
                                        </span>
                                    </div>
                                </div>

                                <!-- Live Integration Badge Toast -->
                                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                                    <div class="flex items-center gap-2">
                                        <i class="bi bi-calendar-check text-indigo-600"></i>
                                        <span>Google Calendar Synced</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 font-bold text-slate-700">
                                        <i class="bi bi-github text-slate-900"></i>
                                        <span>4 commits hari ini</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ================= STATS HIGHLIGHT ================= -->
        <section class="border-y border-slate-200/80 bg-white py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-slate-100">
                    <div class="pt-4 md:pt-0">
                        <div class="text-3xl lg:text-4xl font-black text-slate-900 tabular-nums">2,480+</div>
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">Mahasiswa Aktif</div>
                    </div>
                    <div class="pt-4 md:pt-0">
                        <div class="text-3xl lg:text-4xl font-black text-indigo-600 tabular-nums">380+</div>
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">Proyek Terselesaikan</div>
                    </div>
                    <div class="pt-4 md:pt-0">
                        <div class="text-3xl lg:text-4xl font-black text-slate-900 tabular-nums">94.2%</div>
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">Kecocokan Tim</div>
                    </div>
                    <div class="pt-4 md:pt-0">
                        <div class="text-3xl lg:text-4xl font-black text-slate-900 tabular-nums">48 Kampus</div>
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">Di Seluruh Indonesia</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= BENTO GRID FEATURES ================= -->
        <section id="fitur" class="py-24 bg-slate-50 relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl mx-auto text-center mb-16 space-y-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700 tracking-wide uppercase">
                        Keunggulan Platform
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                        Dirancang khusus untuk ritme kolaborasi mahasiswa modern.
                    </h2>
                    <p class="text-slate-600 text-base">
                        Semua alat esensial untuk menemukan partner, mengelola tugas, dan memvalidasi kontribusimu di satu tempat.
                    </p>
                </div>

                <!-- Bento Grid Layout -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <!-- Bento Card 1 (Span 2) -->
                    <div class="md:col-span-2 bg-white rounded-2xl border border-slate-200/80 p-8 shadow-sm hover-lift flex flex-col justify-between overflow-hidden relative">
                        <div class="space-y-4 max-w-lg mb-8">
                            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-900">Project Matchmaking Cerdas</h3>
                            <p class="text-slate-600 text-sm leading-relaxed">
                                Tidak perlu lagi bingung mencari rekan setim di grup chat kampus. Algoritma kami mencocokkan proyek berdasarkan keahlian teknis (React, Laravel, Flutter), preferensi jam kerja, dan target luaran.
                            </p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-200/60 flex flex-wrap gap-2 items-center">
                            <span class="text-xs font-bold text-slate-400 mr-2">Skill Populer:</span>
                            <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-700">UI/UX Design</span>
                            <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-700">Laravel / PHP</span>
                            <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-700">Python AI/ML</span>
                            <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-700">Mobile Flutter</span>
                            <span class="px-2.5 py-1 bg-indigo-50 border border-indigo-200 rounded-lg text-xs font-bold text-indigo-700">+120 Lainnya</span>
                        </div>
                    </div>

                    <!-- Bento Card 2 (Span 1) -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-8 shadow-sm hover-lift flex flex-col justify-between">
                        <div class="space-y-4">
                            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                                <i class="bi bi-calendar2-week-fill"></i>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900">Google Calendar Sync</h3>
                            <p class="text-slate-600 text-sm leading-relaxed">
                                Setiap tugas yang dibuat di papan Kanban proyek otomatis tersinkronisasi ke Google Calendar pribadi melalui integrasi Spatie Calendar. Bebas terlewat deadline.
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center gap-2 text-xs font-bold text-amber-700">
                            <i class="bi bi-check2-circle text-base"></i>
                            <span>Sinkronisasi otomatis real-time</span>
                        </div>
                    </div>

                    <!-- Bento Card 3 (Span 1) -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-8 shadow-sm hover-lift flex flex-col justify-between">
                        <div class="space-y-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-900 text-white flex items-center justify-center text-xl">
                                <i class="bi bi-github"></i>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900">GitHub Webhook & Reputasi</h3>
                            <p class="text-slate-600 text-sm leading-relaxed">
                                Hubungkan repository tugas akhir atau kompetisimu. Aktivitas push commit secara aman diverifikasi via HMAC SHA-256 dan menambah poin reputasi akunmu.
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center gap-2 text-xs font-bold text-slate-800">
                            <i class="bi bi-shield-check text-emerald-600"></i>
                            <span>HMAC SHA-256 Verified</span>
                        </div>
                    </div>

                    <!-- Bento Card 4 (Span 2) -->
                    <div class="md:col-span-2 bg-white rounded-2xl border border-slate-200/80 p-8 shadow-sm hover-lift flex flex-col justify-between">
                        <div class="space-y-4 max-w-lg mb-8">
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                                <i class="bi bi-patch-check-fill"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-900">Live Resume & Peer Review Terverifikasi</h3>
                            <p class="text-slate-600 text-sm leading-relaxed">
                                Buktikan kamu bukan sekadar "penumpang nama". Di akhir proyek, setiap anggota saling memberikan rating dan feedback objektif yang dicetak langsung ke Live Resume PDF siap pakai untuk melamar magang.
                            </p>
                        </div>
                        <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-800">
                                <i class="bi bi-file-earmark-pdf text-red-500"></i> Export PDF Instan
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-800">
                                <i class="bi bi-star-fill text-yellow-500"></i> Rating & Testimonial
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ================= WORKFLOW / CARA KERJA ================= -->
        <section id="cara-kerja" class="py-24 bg-white border-t border-slate-200/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl mx-auto text-center mb-16 space-y-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 tracking-wide uppercase">
                        Alur Sederhana
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                        Tiga langkah menuju portofolio kompetitif.
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                    <!-- Step 1 -->
                    <div class="relative bg-slate-50 rounded-2xl p-8 border border-slate-200/80 hover-lift">
                        <div class="text-5xl font-black text-slate-200 mb-6 tabular-nums">01</div>
                        <h4 class="text-lg font-bold text-slate-900 mb-2">Buat Profil & Klaim Keahlian</h4>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            Daftar dengan akun Google atau GitHub kampusmu. Tentukan keahlian, minat proyek, dan portofolio awal.
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative bg-slate-50 rounded-2xl p-8 border border-slate-200/80 hover-lift">
                        <div class="text-5xl font-black text-indigo-200 mb-6 tabular-nums">02</div>
                        <h4 class="text-lg font-bold text-slate-900 mb-2">Bentuk Tim & Kerjakan di Workspace</h4>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            Gunakan papan Kanban, chat real-time, dan repositori terhubung untuk mengeksekusi ide secara profesional.
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative bg-slate-50 rounded-2xl p-8 border border-slate-200/80 hover-lift">
                        <div class="text-5xl font-black text-slate-200 mb-6 tabular-nums">03</div>
                        <h4 class="text-lg font-bold text-slate-900 mb-2">Dapatkan Validasi & Reputasi</h4>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            Selesaikan proyek, kumpulkan ulasan rekan tim, dan unduh Live Resume PDF untuk memukau recruiter magang.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= TESTIMONIALS ================= -->
        <section id="testimoni" class="py-24 bg-slate-50 border-t border-slate-200/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl mx-auto text-center mb-16 space-y-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700 tracking-wide uppercase">
                        Cerita Mahasiswa
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                        Pengalaman nyata dari mereka yang telah berkolaborasi.
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Quote 1 -->
                    <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm hover-lift flex flex-col justify-between">
                        <p class="text-slate-600 text-sm leading-relaxed mb-6">
                            &ldquo;Susah banget cari UI designer yang komit untuk proyek lomba hackathon. Di Swap Hub, ketemu partner dari kampus lain yang pas banget visinya, dan kami juara 2!&rdquo;
                        </p>
                        <div class="flex items-center gap-3">
                            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=80&h=80&fit=crop&q=80"
                                 class="w-10 h-10 rounded-full object-cover" alt="Arya">
                            <div>
                                <h5 class="text-sm font-bold text-slate-900 mb-0">Arya Wicaksono</h5>
                                <p class="text-xs text-slate-400 mb-0">Teknik Informatika • Univ. Indonesia</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quote 2 -->
                    <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm hover-lift flex flex-col justify-between">
                        <p class="text-slate-600 text-sm leading-relaxed mb-6">
                            &ldquo;Fitur Google Calendar dan sinkronisasi GitHub bikin kerja kelompok berasa kerja di startup tech beneran. Semua terorganisir rapi tanpa drama.&rdquo;
                        </p>
                        <div class="flex items-center gap-3">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=80&h=80&fit=crop&q=80"
                                 class="w-10 h-10 rounded-full object-cover" alt="Cindy">
                            <div>
                                <h5 class="text-sm font-bold text-slate-900 mb-0">Cindy Clarissa</h5>
                                <p class="text-xs text-slate-400 mb-0">Sistem Informasi • ITB</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quote 3 -->
                    <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm hover-lift flex flex-col justify-between">
                        <p class="text-slate-600 text-sm leading-relaxed mb-6">
                            &ldquo;Resume PDF otomatis yang diexport dari Swap Hub langsung saya lampirkan saat apply magang di e-commerce unicorn, dan langsung lolos tahap portofolio.&rdquo;
                        </p>
                        <div class="flex items-center gap-3">
                            <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=80&h=80&fit=crop&q=80"
                                 class="w-10 h-10 rounded-full object-cover" alt="Fauzan">
                            <div>
                                <h5 class="text-sm font-bold text-slate-900 mb-0">Fauzan Rahman</h5>
                                <p class="text-xs text-slate-400 mb-0">Ilmu Komputer • UGM</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= CTA BANNER ================= -->
        <section class="py-20 bg-slate-900 text-white relative overflow-hidden">
            <!-- Subtle Radial Gradient -->
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_80%_at_50%_-20%,rgba(99,102,241,0.25),rgba(255,255,255,0))] pointer-events-none"></div>

            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 space-y-8">
                <h2 class="text-3xl sm:text-5xl font-black tracking-tight text-white">
                    Siap Memulai Kolaborasi Pertamamu?
                </h2>
                <p class="text-slate-400 text-base sm:text-lg max-w-2xl mx-auto leading-relaxed text-pretty">
                    Bergabung bersama ribuan mahasiswa inovatif lainnya. Kembangkan portofolio nyata dan temukan partner terbaikmu hari ini.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-2">
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl text-base font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg transition-all duration-150 active:scale-[0.98] no-underline w-full sm:w-auto">
                        <span>Daftar Akun Gratis</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="{{ route('projects.index') }}"
                       class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl text-base font-semibold bg-slate-800 hover:bg-slate-750 text-slate-200 border border-slate-700 transition-all duration-150 active:scale-[0.98] no-underline w-full sm:w-auto">
                        <span>Lihat Semua Proyek</span>
                    </a>
                </div>
            </div>
        </section>

    </main>

    <!-- ================= FOOTER ================= -->
    <footer class="bg-white border-t border-slate-200 py-12 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-8 pb-12">
                <!-- Brand Info -->
                <div class="md:col-span-2 space-y-4">
                    <a href="{{ url('/') }}" class="flex items-center gap-2.5 no-underline">
                        <img src="{{ asset('icon.png') }}" alt="Swap Hub" class="w-7 h-7 object-contain">
                        <span class="text-lg font-bold tracking-tight text-slate-900">
                            Swap<span class="text-indigo-600">Hub</span>
                        </span>
                    </a>
                    <p class="text-slate-500 text-sm max-w-sm leading-relaxed">
                        Platform ekosistem kolaborasi dan pertukaran skill antarmahasiswa untuk mempersiapkan generasi profesional masa depan.
                    </p>
                    <div class="flex items-center gap-3 text-slate-400">
                        <a href="https://github.com" target="_blank" rel="noopener" class="hover:text-slate-700 transition-colors text-lg"><i class="bi bi-github"></i></a>
                        <a href="https://linkedin.com" target="_blank" rel="noopener" class="hover:text-slate-700 transition-colors text-lg"><i class="bi bi-linkedin"></i></a>
                        <a href="https://twitter.com" target="_blank" rel="noopener" class="hover:text-slate-700 transition-colors text-lg"><i class="bi bi-twitter-x"></i></a>
                    </div>
                </div>

                <!-- Navigation Columns -->
                <div>
                    <h5 class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-4">Platform</h5>
                    <ul class="space-y-2.5 text-sm text-slate-600 list-none p-0 m-0">
                        <li><a href="#fitur" class="hover:text-indigo-600 transition-colors no-underline">Fitur Utama</a></li>
                        <li><a href="{{ route('projects.index') }}" class="hover:text-indigo-600 transition-colors no-underline">Cari Proyek</a></li>
                        <li><a href="#cara-kerja" class="hover:text-indigo-600 transition-colors no-underline">Alur Kerja</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-4">Fitur Terintegrasi</h5>
                    <ul class="space-y-2.5 text-sm text-slate-600 list-none p-0 m-0">
                        <li><span class="text-slate-600">Spatie Calendar</span></li>
                        <li><span class="text-slate-600">GitHub Webhooks</span></li>
                        <li><span class="text-slate-600">Live Resume PDF</span></li>
                        <li><span class="text-slate-600">Pusher Reverb Chat</span></li>
                    </ul>
                </div>

                <div>
                    <h5 class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-4">Kebijakan & Privasi</h5>
                    <ul class="space-y-2.5 text-sm text-slate-600 list-none p-0 m-0">
                        <li><a href="#" class="hover:text-indigo-600 transition-colors no-underline">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition-colors no-underline">Ketentuan Layanan</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition-colors no-underline">Panduan Komunitas</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-200/80 pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                <p class="mb-0">&copy; {{ date('Y') }} Swap Hub. Seluruh hak cipta dilindungi.</p>
                <div class="flex items-center gap-4">
                    <span class="text-slate-400">Dibuat untuk mahasiswa Indonesia</span>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>