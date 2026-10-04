<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Swap Hub') }} — Workspace Kolaborasi & Pertukaran Skill Mahasiswa</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" type="image/png" href="{{ asset('icon.png') }}">

    @include('partials.pwa-head')

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        .font-mono-code {
            font-family: 'JetBrains Mono', monospace;
        }
        .craft-glass {
            background: rgba(255, 255, 255, 0.78);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
        .craft-card-shadow {
            box-shadow: 0 20px 45px -12px rgba(15, 23, 42, 0.08), 0 1px 3px rgba(15, 23, 42, 0.04);
        }
        .craft-subtle-glow {
            background: radial-gradient(circle at 50% 0%, rgba(20, 184, 166, 0.12), rgba(245, 158, 11, 0.05) 45%, rgba(250, 250, 249, 0) 70%);
        }
    </style>
</head>

<body class="bg-[#fbfbfa] text-stone-900 selection:bg-teal-600 selection:text-white antialiased min-h-screen flex flex-col" x-data="{ mobileMenu: false, previewTab: 'workspace', skillFilter: 'all' }">

    <!-- Ambient background mesh (Craft-like soft glow) -->
    <div class="fixed inset-0 pointer-events-none craft-subtle-glow z-0"></div>

    <!-- ================= FLOATING PILL NAVBAR ================= -->
    <header class="fixed top-4 inset-x-0 mx-auto max-w-5xl z-50 px-4">
        <div class="craft-glass rounded-full border border-stone-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.05)] px-4 py-2 flex items-center justify-between transition-all">
            <!-- Brand -->
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 no-underline group pl-1">
                <x-application-logo class="w-7 h-7 rounded-lg group-hover:scale-105 transition-transform" />
                <span class="text-sm font-extrabold tracking-tight text-stone-900 flex items-center gap-1.5">
                    SwapHub
                    <span class="text-[10px] font-semibold tracking-wide uppercase px-2 py-0.5 rounded-full bg-stone-100 text-stone-600 border border-stone-200">Campus</span>
                </span>
            </a>

            <!-- Center Navigation Links -->
            <nav class="hidden md:flex items-center gap-1 text-xs font-semibold text-stone-600">
                <a href="#canvas" class="px-3.5 py-1.5 rounded-full hover:bg-stone-100 hover:text-stone-900 transition-colors no-underline">Workspace</a>
                <a href="#skill-matrix" class="px-3.5 py-1.5 rounded-full hover:bg-stone-100 hover:text-stone-900 transition-colors no-underline">Tukar Skill</a>
                <a href="#fitur" class="px-3.5 py-1.5 rounded-full hover:bg-stone-100 hover:text-stone-900 transition-colors no-underline">Fitur Modular</a>
                <a href="{{ route('projects.index') }}" class="px-3.5 py-1.5 rounded-full hover:bg-stone-100 hover:text-stone-900 transition-colors no-underline">Eksplor Proyek</a>
                <a href="#testimoni" class="px-3.5 py-1.5 rounded-full hover:bg-stone-100 hover:text-stone-900 transition-colors no-underline">Cerita</a>
            </nav>

            <!-- Actions -->
            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold bg-stone-900 hover:bg-stone-800 text-white transition-all shadow-sm active:scale-95 no-underline">
                        <span>Dashboard</span>
                        <i class="bi bi-arrow-right text-[10px]"></i>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="text-xs font-semibold text-stone-600 hover:text-stone-900 px-3 py-1.5 rounded-full hover:bg-stone-100 transition-colors no-underline">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center gap-1 px-3.5 py-1.5 rounded-full text-xs font-bold bg-stone-900 hover:bg-stone-800 text-white transition-all shadow-sm active:scale-95 no-underline">
                        <span>Daftar</span>
                        <i class="bi bi-chevron-right text-[10px] text-teal-400"></i>
                    </a>
                @endauth

                <!-- Mobile menu toggle -->
                <button type="button" @click="mobileMenu = !mobileMenu" class="md:hidden p-1.5 text-stone-600 hover:text-stone-900 rounded-full hover:bg-stone-100" aria-label="Toggle menu">
                    <i class="bi" :class="mobileMenu ? 'bi-x-lg' : 'bi-list'"></i>
                </button>
            </div>
        </div>

        <!-- Mobile dropdown -->
        <div x-show="mobileMenu" x-cloak @click.away="mobileMenu = false" class="md:hidden mt-2 p-4 craft-glass rounded-lg border border-stone-200 shadow-xl space-y-2 text-sm font-semibold">
            <a href="#canvas" @click="mobileMenu = false" class="block py-1.5 px-3 rounded-lg hover:bg-stone-100 text-stone-700 no-underline">Workspace</a>
            <a href="#skill-matrix" @click="mobileMenu = false" class="block py-1.5 px-3 rounded-lg hover:bg-stone-100 text-stone-700 no-underline">Tukar Skill</a>
            <a href="#fitur" @click="mobileMenu = false" class="block py-1.5 px-3 rounded-lg hover:bg-stone-100 text-stone-700 no-underline">Fitur Modular</a>
            <a href="{{ route('projects.index') }}" @click="mobileMenu = false" class="block py-1.5 px-3 rounded-lg hover:bg-stone-100 text-stone-700 no-underline">Eksplor Proyek</a>
            <a href="#testimoni" @click="mobileMenu = false" class="block py-1.5 px-3 rounded-lg hover:bg-stone-100 text-stone-700 no-underline">Cerita Mahasiswa</a>
        </div>
    </header>

    <main class="flex-grow relative z-10 pt-28">

        <!-- ================= HERO SECTION (CRAFT ELEGANCE + SWAP HUB IDENTITY) ================= -->
        <section class="pt-8 pb-16 md:pt-14 md:pb-24 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto text-center">
            <!-- Pill Badge with Craft Micro Glow -->
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full craft-glass border border-stone-200/90 shadow-sm text-xs font-semibold text-stone-700 mb-6 hover:border-teal-400/60 transition-colors">
                <span class="w-2 h-2 rounded-full bg-teal-500 animate-pulse"></span>
                <span>Generasi Baru Ruang Kolaborasi Mahasiswa</span>
                <span class="text-stone-300">•</span>
                <span class="text-teal-700 font-bold">Swap Hub 2.0</span>
            </div>

            <!-- Big Confident Editorial Headline -->
            <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold text-stone-950 tracking-[-0.035em] leading-[1.08] max-w-4xl mx-auto">
                Tempat ide mahasiswa bertumbuh menjadi <span class="bg-gradient-to-r from-stone-900 via-teal-800 to-teal-600 bg-clip-text text-transparent">karya nyata.</span>
            </h1>

            <p class="mt-6 text-base sm:text-lg text-stone-600 max-w-2xl mx-auto leading-relaxed font-normal">
                Bukan sekadar tugas kelas. Susun tim lintas disiplin, barter keahlian teknis secara seimbang, sinkronisasi milestone ke Google Calendar & GitHub, lalu cetak Live Resume terverifikasi.
            </p>

            <!-- Dual Pill Action Buttons -->
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('register') }}"
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full text-sm font-bold bg-stone-900 hover:bg-stone-800 text-white shadow-md hover:shadow-xl transition-all duration-200 active:scale-95 no-underline w-full sm:w-auto">
                    <span>Mulai Kolaborasi — Gratis</span>
                    <i class="bi bi-arrow-right text-teal-400 text-xs"></i>
                </a>
                <a href="{{ route('projects.index') }}"
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full text-sm font-semibold bg-white hover:bg-stone-100 text-stone-800 border border-stone-200/90 shadow-sm transition-all duration-200 active:scale-95 no-underline w-full sm:w-auto">
                    <i class="bi bi-compass text-stone-400"></i>
                    <span>Jelajahi 380+ Proyek</span>
                </a>
            </div>

            <!-- Social proof chip -->
            <div class="mt-10 flex items-center justify-center gap-3 text-xs text-stone-500 font-medium">
                <div class="flex -space-x-2">
                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=64&h=64&fit=crop&crop=faces&q=80" class="w-7 h-7 rounded-full border-2 border-white object-cover shadow-sm" alt="Student">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=64&h=64&fit=crop&crop=faces&q=80" class="w-7 h-7 rounded-full border-2 border-white object-cover shadow-sm" alt="Student">
                    <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=64&h=64&fit=crop&crop=faces&q=80" class="w-7 h-7 rounded-full border-2 border-white object-cover shadow-sm" alt="Student">
                    <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=64&h=64&fit=crop&crop=faces&q=80" class="w-7 h-7 rounded-full border-2 border-white object-cover shadow-sm" alt="Student">
                </div>
                <span>Dipercaya oleh <strong>2,480+</strong> mahasiswa aktif di 48 kampus seluruh Indonesia</span>
            </div>

            <!-- ================= HERO PRODUCT CANVAS (CRAFT.DO DOCUMENT & CARD METAPHOR) ================= -->
            <div id="canvas" class="mt-12 text-left relative max-w-5xl mx-auto">
                <!-- Floating Micro Badges -->
                <div class="hidden lg:flex items-center gap-2 absolute -top-4 -right-4 z-20 craft-glass border border-stone-200/80 px-3 py-1.5 rounded-lg shadow-lg text-xs font-semibold text-stone-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Google Calendar & GitHub Sync Aktif</span>
                </div>
                <div class="hidden lg:flex items-center gap-2 absolute -bottom-4 -left-4 z-20 craft-glass border border-stone-200/80 px-3 py-1.5 rounded-lg shadow-lg text-xs font-semibold text-stone-700">
                    <i class="bi bi-shield-check text-teal-600"></i>
                    <span>Bebas Free-Rider: Peer Review Terenkripsi</span>
                </div>

                <!-- Main Canvas Window Frame -->
                <div class="bg-white rounded-xl border border-stone-200/80 craft-card-shadow overflow-hidden transition-all">
                    <!-- Window Top Chrome -->
                    <div class="px-5 py-3.5 bg-stone-50/90 border-b border-stone-200/70 flex items-center justify-between text-xs text-stone-500">
                        <!-- macOS style traffic lights -->
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-rose-400 border border-rose-500/20"></span>
                            <span class="w-3 h-3 rounded-full bg-amber-400 border border-amber-500/20"></span>
                            <span class="w-3 h-3 rounded-full bg-emerald-400 border border-emerald-500/20"></span>
                            <span class="ml-3 font-mono-code text-[11px] text-stone-400 hidden sm:inline">swap-hub.workspace / smart-campus-iot</span>
                        </div>

                        <!-- Canvas Breadcrumb & Switcher -->
                        <div class="flex items-center gap-1 bg-white p-1 rounded-xl border border-stone-200/80 shadow-xs">
                            <button type="button" @click="previewTab = 'workspace'" :class="previewTab === 'workspace' ? 'bg-stone-900 text-white font-bold' : 'text-stone-600 hover:text-stone-900'" class="px-2.5 py-1 rounded-lg text-[11px] transition-all">
                                Dokumen Proyek
                            </button>
                            <button type="button" @click="previewTab = 'skillswap'" :class="previewTab === 'skillswap' ? 'bg-stone-900 text-white font-bold' : 'text-stone-600 hover:text-stone-900'" class="px-2.5 py-1 rounded-lg text-[11px] transition-all">
                                Matriks Skill
                            </button>
                            <button type="button" @click="previewTab = 'resume'" :class="previewTab === 'resume' ? 'bg-stone-900 text-white font-bold' : 'text-stone-600 hover:text-stone-900'" class="px-2.5 py-1 rounded-lg text-[11px] transition-all">
                                Live Resume PDF
                            </button>
                        </div>
                    </div>

                    <!-- Canvas Body: Tab 1 (Document Workspace) -->
                    <div x-show="previewTab === 'workspace'" class="p-4 sm:p-4 space-y-4">
                        <!-- Document Cover Banner -->
                        <div class="rounded-lg bg-gradient-to-r from-teal-800 via-teal-900 to-stone-900 p-4 text-white relative overflow-hidden flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="relative z-10 space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded-full bg-teal-400/20 text-teal-300 text-[10px] font-bold uppercase tracking-wider border border-teal-300/30">Proyek Unggulan</span>
                                    <span class="text-xs text-stone-300">Institut Teknologi Bandung</span>
                                </div>
                                <h3 class="text-xl sm:text-2xl font-bold tracking-tight text-white mb-0">
                                    Smart Hydroponic & IoT Telemetry System
                                </h3>
                                <p class="text-xs text-teal-100/80 mb-0 font-normal">
                                    Membangun prototype monitoring nutrisi otomatis berbasis ESP32, backend Laravel, dan Flutter app.
                                </p>
                            </div>
                            <div class="relative z-10 flex sm:flex-col items-center sm:items-end gap-2 shrink-0">
                                <span class="px-3 py-1 rounded-full bg-white/10 backdrop-blur text-white text-xs font-semibold border border-white/20">
                                    3/4 Anggota Terisi
                                </span>
                                <span class="text-[11px] text-teal-200">Batas: 28 Nov 2026</span>
                            </div>
                        </div>

                        <!-- Craft-Style Nested Modular Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Block 1: Squad Allocation -->
                            <div class="p-4 rounded-lg bg-stone-50/80 border border-stone-200/70 hover:border-stone-300 transition-colors flex flex-col justify-between">
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-stone-700 uppercase tracking-wider">Susunan Tim</span>
                                        <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between p-2 rounded-xl bg-white border border-stone-200/60 shadow-xs">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center text-[10px] font-bold">RP</div>
                                                <div class="text-xs">
                                                    <p class="font-bold text-stone-800 mb-0 leading-none">Rafi Pratama</p>
                                                    <span class="text-[10px] text-stone-400">IoT Lead • ITB</span>
                                                </div>
                                            </div>
                                            <span class="text-[10px] font-semibold text-teal-700 bg-teal-50 px-1.5 py-0.5 rounded">Arduino</span>
                                        </div>

                                        <div class="flex items-center justify-between p-2 rounded-xl bg-white border border-stone-200/60 shadow-xs">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center text-[10px] font-bold">NS</div>
                                                <div class="text-xs">
                                                    <p class="font-bold text-stone-800 mb-0 leading-none">Nadia Safitri</p>
                                                    <span class="text-[10px] text-stone-400">UI/UX • UI</span>
                                                </div>
                                            </div>
                                            <span class="text-[10px] font-semibold text-amber-700 bg-amber-50 px-1.5 py-0.5 rounded">Figma</span>
                                        </div>

                                        <!-- Open Match Slot -->
                                        <div class="p-2 rounded-xl border border-dashed border-teal-400/80 bg-teal-50/50 flex items-center justify-between">
                                            <div class="text-xs">
                                                <p class="font-bold text-teal-900 mb-0 leading-none">Dicari: Flutter Dev</p>
                                                <span class="text-[10px] text-teal-700">96% Kecocokan Skill</span>
                                            </div>
                                            <span class="text-[10px] font-bold px-2 py-0.5 bg-teal-600 text-white rounded-md shadow-xs">Gabung</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Block 2: Live Integrations & Milestone -->
                            <div class="p-4 rounded-lg bg-stone-50/80 border border-stone-200/70 hover:border-stone-300 transition-colors flex flex-col justify-between">
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-stone-700 uppercase tracking-wider">Milestone & Calendar</span>
                                        <i class="bi bi-calendar2-check text-teal-600"></i>
                                    </div>

                                    <div class="space-y-2">
                                        <div class="p-2.5 rounded-xl bg-white border border-stone-200/60 shadow-xs">
                                            <div class="flex items-center justify-between text-xs mb-1">
                                                <span class="font-bold text-stone-800">Sprint #2: API MQTT</span>
                                                <span class="text-[10px] font-bold text-teal-700 bg-teal-50 px-1.5 py-0.2 rounded">80%</span>
                                            </div>
                                            <div class="w-full bg-stone-100 rounded-full h-1.5 overflow-hidden">
                                                <div class="bg-teal-600 h-1.5 rounded-full" style="width: 80%"></div>
                                            </div>
                                            <p class="text-[10px] text-stone-400 mt-2 mb-0">Tersinkron ke Google Calendar seluruh anggota</p>
                                        </div>

                                        <div class="p-2.5 rounded-xl bg-stone-900 text-stone-100 font-mono-code text-[11px] space-y-1">
                                            <div class="flex items-center justify-between text-[10px] text-stone-400">
                                                <span>github/webhook</span>
                                                <span class="text-emerald-400">SHA-256 Valid</span>
                                            </div>
                                            <p class="text-stone-300 mb-0 truncate">commit 42dfb1: add telemetry pub/sub</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Block 3: Validated Peer Review Preview -->
                            <div class="p-4 rounded-lg bg-stone-50/80 border border-stone-200/70 hover:border-stone-300 transition-colors flex flex-col justify-between">
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-stone-700 uppercase tracking-wider">Verifikasi Kontribusi</span>
                                        <i class="bi bi-award text-amber-500"></i>
                                    </div>

                                    <div class="p-3 rounded-xl bg-white border border-stone-200/60 shadow-xs space-y-2">
                                        <div class="flex items-center gap-1 text-amber-400 text-xs">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <span class="text-stone-800 font-bold text-xs ml-1">5.0 / 5.0</span>
                                        </div>
                                        <p class="text-xs text-stone-600 italic mb-0 leading-relaxed">
                                            &ldquo;Rafi sangat andal dalam merancang wiring diagram dan tepat waktu dalam integrasi sensor.&rdquo;
                                        </p>
                                        <div class="pt-1 flex items-center justify-between text-[10px] text-stone-400">
                                            <span>Oleh: Cindy (Peer Reviewer)</span>
                                            <span class="text-teal-700 font-bold">Tervalidasi</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 text-[11px] font-semibold text-stone-600">
                                        <i class="bi bi-file-earmark-pdf text-rose-500"></i>
                                        <span>Siap dicetak ke Live Resume PDF</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Canvas Body: Tab 2 (Skill Swap Matrix) -->
                    <div x-show="previewTab === 'skillswap'" x-cloak class="p-4 sm:p-4 space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-stone-200/70">
                            <div>
                                <h4 class="text-lg font-bold text-stone-900 mb-0">Pasar Pertukaran Skill Antarmahasiswa</h4>
                                <p class="text-xs text-stone-500 mb-0">Barter pengetahuan & kontribusi proyek secara setara tanpa transaksi uang.</p>
                            </div>
                            <a href="{{ route('skills.swap.index') }}" class="text-xs font-bold text-teal-700 hover:text-teal-800 no-underline inline-flex items-center gap-1">
                                <span>Lihat Semua Penawaran</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Swap Item 1 -->
                            <div class="p-4 rounded-lg bg-white border border-stone-200/80 shadow-xs flex flex-col justify-between">
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3">
                                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=64&h=64&fit=crop&q=80" class="w-9 h-9 rounded-full object-cover" alt="Student">
                                        <div>
                                            <p class="text-xs font-bold text-stone-900 mb-0">Fathia Azzahra</p>
                                            <p class="text-[10px] text-stone-400 mb-0">Desain Komunikasi Visual • UGM</p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 text-xs">
                                        <div class="p-2 rounded-xl bg-teal-50 border border-teal-100">
                                            <span class="text-[10px] font-bold uppercase text-teal-800 block">Menawarkan</span>
                                            <span class="font-semibold text-teal-950">UI/UX & Branding App</span>
                                        </div>
                                        <div class="p-2 rounded-xl bg-amber-50 border border-amber-100">
                                            <span class="text-[10px] font-bold uppercase text-amber-800 block">Mencari</span>
                                            <span class="font-semibold text-amber-950">Backend REST API</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 pt-3 border-t border-stone-100 flex items-center justify-between text-xs">
                                    <span class="text-[11px] text-stone-500">Kecocokan: <strong>94%</strong></span>
                                    <span class="px-3 py-1 rounded-full bg-stone-900 text-white text-[11px] font-bold">Kirim Permintaan</span>
                                </div>
                            </div>

                            <!-- Swap Item 2 -->
                            <div class="p-4 rounded-lg bg-white border border-stone-200/80 shadow-xs flex flex-col justify-between">
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3">
                                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=64&h=64&fit=crop&q=80" class="w-9 h-9 rounded-full object-cover" alt="Student">
                                        <div>
                                            <p class="text-xs font-bold text-stone-900 mb-0">Bima Wicaksono</p>
                                            <p class="text-[10px] text-stone-400 mb-0">Teknik Informatika • ITS Surabaya</p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 text-xs">
                                        <div class="p-2 rounded-xl bg-teal-50 border border-teal-100">
                                            <span class="text-[10px] font-bold uppercase text-teal-800 block">Menawarkan</span>
                                            <span class="font-semibold text-teal-950">Python ML & Data Scraping</span>
                                        </div>
                                        <div class="p-2 rounded-xl bg-amber-50 border border-amber-100">
                                            <span class="text-[10px] font-bold uppercase text-amber-800 block">Mencari</span>
                                            <span class="font-semibold text-amber-950">Frontend Tailwind / Vue</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 pt-3 border-t border-stone-100 flex items-center justify-between text-xs">
                                    <span class="text-[11px] text-stone-500">Kecocokan: <strong>98%</strong></span>
                                    <span class="px-3 py-1 rounded-full bg-stone-900 text-white text-[11px] font-bold">Kirim Permintaan</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Canvas Body: Tab 3 (Live Resume PDF) -->
                    <div x-show="previewTab === 'resume'" x-cloak class="p-4 sm:p-4 space-y-4">
                        <div class="max-w-2xl mx-auto p-4 rounded-lg bg-white border border-stone-200/90 shadow-sm space-y-4">
                            <div class="flex items-start justify-between border-b border-stone-200 pb-4">
                                <div>
                                    <h4 class="text-xl font-bold text-stone-950 mb-0">Rafi Pratama</h4>
                                    <p class="text-xs text-stone-500 mb-0">Teknik Informatika • Institut Teknologi Bandung • Angkatan 2023</p>
                                </div>
                                <div class="text-right">
                                    <span class="px-2.5 py-1 rounded-full bg-teal-50 text-teal-700 text-xs font-bold border border-teal-200">
                                        Verified Portofolio
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <h5 class="text-xs font-bold uppercase tracking-wider text-stone-400">Riwayat Proyek Selesai & Peer Endorsements</h5>
                                <div class="p-3 rounded-xl bg-stone-50 border border-stone-200/60 text-xs space-y-1">
                                    <div class="flex justify-between font-bold text-stone-800">
                                        <span>Smart Hydroponic & IoT Telemetry</span>
                                        <span class="text-teal-700">Skor Peer: 4.95/5.0</span>
                                    </div>
                                    <p class="text-stone-500 mb-0">Peran: Lead IoT Engineer & Architecture. Berhasil mengintegrasikan 5 modul sensor dengan 0% downtime pengujian.</p>
                                </div>
                            </div>

                            <div class="pt-3 border-t border-stone-100 flex items-center justify-between text-xs">
                                <span class="text-stone-400 font-mono-code text-[11px]">ID Verifikasi: SH-ITB-2026-8819</span>
                                <span class="inline-flex items-center gap-1.5 font-bold text-stone-900">
                                    <i class="bi bi-download"></i> Ekspor PDF Instan
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ================= CAMPUS NETWORK STRIP (DIFFERENTIATOR) ================= -->
        <section class="border-y border-stone-200/70 bg-white/60 py-8">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p class="text-xs font-semibold tracking-wider uppercase text-stone-400 mb-6">
                    Menghubungkan Kolaborator dari 48+ Kampus Terkemuka
                </p>
                <div class="flex flex-wrap items-center justify-center gap-4 sm:gap-4 text-sm font-bold text-stone-500">
                    <span class="hover:text-stone-900 transition-colors">ITB Bandung</span>
                    <span class="text-stone-300">•</span>
                    <span class="hover:text-stone-900 transition-colors">Universitas Indonesia</span>
                    <span class="text-stone-300">•</span>
                    <span class="hover:text-stone-900 transition-colors">UGM Yogyakarta</span>
                    <span class="text-stone-300">•</span>
                    <span class="hover:text-stone-900 transition-colors">ITS Surabaya</span>
                    <span class="text-stone-300">•</span>
                    <span class="hover:text-stone-900 transition-colors">Telkom University</span>
                    <span class="text-stone-300">•</span>
                    <span class="hover:text-stone-900 transition-colors">Binus University</span>
                </div>
            </div>
        </section>

        <!-- ================= CRAFT-INSPIRED BENTO GRID ================= -->
        <section id="fitur" class="py-20 md:py-28 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto text-center mb-16 space-y-3">
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-stone-200/80 text-stone-700 tracking-wide uppercase">
                    Fitur Modular
                </span>
                <h2 class="text-3xl sm:text-5xl font-extrabold text-stone-950 tracking-tight">
                    Semua alat esensial dalam satu kanvas yang rapi.
                </h2>
                <p class="text-stone-600 text-base">
                    Dirancang untuk mengatasi friksi kerja kelompok, deadline berantakan, dan portofolio yang tidak terbukti.
                </p>
            </div>

            <!-- Bento Layout -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Bento 1: Matchmaking (Span 2) -->
                <div class="md:col-span-2 bg-white rounded-xl border border-stone-200/80 p-4 craft-card-shadow flex flex-col justify-between relative overflow-hidden group hover:border-stone-300 transition-all">
                    <div class="space-y-4 max-w-lg mb-8">
                        <div class="w-10 h-10 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center text-lg font-bold">
                            <i class="bi bi-people"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-stone-900 tracking-tight">Matchmaking Cerdas Berbasis Target & Skill</h3>
                        <p class="text-stone-600 text-sm leading-relaxed">
                            Algoritma pencocokan Swap Hub mempertimbangkan tumpukan teknologi, ketersediaan jam kerja per minggu, dan target capaian (lomba, skripsi, atau proyek open source).
                        </p>
                    </div>

                    <div class="bg-stone-50 rounded-lg p-4 border border-stone-200/70 flex flex-wrap items-center gap-2 text-xs">
                        <span class="font-bold text-stone-400 mr-2">Cakupan Keahlian:</span>
                        <span class="px-2.5 py-1 bg-white border border-stone-200 rounded-lg font-medium text-stone-700">UI/UX & Figma</span>
                        <span class="px-2.5 py-1 bg-white border border-stone-200 rounded-lg font-medium text-stone-700">Laravel / PHP 8.4</span>
                        <span class="px-2.5 py-1 bg-white border border-stone-200 rounded-lg font-medium text-stone-700">Python ML / Data</span>
                        <span class="px-2.5 py-1 bg-white border border-stone-200 rounded-lg font-medium text-stone-700">Flutter Mobile</span>
                        <span class="px-2.5 py-1 bg-teal-100 text-teal-800 rounded-lg font-bold">+140 Skill Lainnya</span>
                    </div>
                </div>

                <!-- Bento 2: Google Calendar (Span 1) -->
                <div class="bg-white rounded-xl border border-stone-200/80 p-4 craft-card-shadow flex flex-col justify-between group hover:border-stone-300 transition-all">
                    <div class="space-y-4">
                        <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center text-lg font-bold">
                            <i class="bi bi-calendar2-range"></i>
                        </div>
                        <h3 class="text-xl font-bold text-stone-900 tracking-tight">Google Calendar Sync</h3>
                        <p class="text-stone-600 text-sm leading-relaxed">
                            Setiap task deadline otomatis terjadwal ke Google Calendar pribadi melalui integrasi Spatie Calendar. Tanpa bentrok dengan jadwal kuliah.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-stone-100 flex items-center gap-2 text-xs font-bold text-amber-800">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Sinkronisasi otomatis real-time</span>
                    </div>
                </div>

                <!-- Bento 3: GitHub HMAC SHA-256 (Span 1) -->
                <div class="bg-white rounded-xl border border-stone-200/80 p-4 craft-card-shadow flex flex-col justify-between group hover:border-stone-300 transition-all">
                    <div class="space-y-4">
                        <div class="w-10 h-10 rounded-lg bg-stone-900 text-white flex items-center justify-center text-lg font-bold">
                            <i class="bi bi-github"></i>
                        </div>
                        <h3 class="text-xl font-bold text-stone-900 tracking-tight">GitHub Activity Webhook</h3>
                        <p class="text-stone-600 text-sm leading-relaxed">
                            Hubungkan repositori proyek. Push commits diverifikasi via HMAC SHA-256 dan mendokumentasikan kontribusi kode nyata ke profilmu.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-stone-100 flex items-center gap-2 text-xs font-bold text-stone-900">
                        <i class="bi bi-shield-lock-fill text-emerald-600"></i>
                        <span>Keamanan Signature Terverifikasi</span>
                    </div>
                </div>

                <!-- Bento 4: Live Resume & Peer Validation (Span 2) -->
                <div class="md:col-span-2 bg-white rounded-xl border border-stone-200/80 p-4 craft-card-shadow flex flex-col justify-between group hover:border-stone-300 transition-all">
                    <div class="space-y-4 max-w-lg mb-8">
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-lg font-bold">
                            <i class="bi bi-patch-check"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-stone-900 tracking-tight">Anti "Penumpang Nama": Validasi Kolegial</h3>
                        <p class="text-stone-600 text-sm leading-relaxed">
                            Di akhir sprint, anggota tim saling mengevaluasi kinerja secara transparan. Skor kontribusi dicetak ke dalam Live Resume PDF dengan QR verifikasi untuk recruiter magang.
                        </p>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-stone-100 text-xs font-bold text-stone-700">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-stone-100">
                            <i class="bi bi-file-earmark-pdf text-rose-500"></i> Unduh PDF 1-Klik
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-stone-100">
                            <i class="bi bi-star-fill text-amber-500"></i> Ulasan Rekan Nyata
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= STEP BY STEP WORKFLOW ================= -->
        <section id="alur-kerja" class="py-20 bg-stone-100/60 border-y border-stone-200/70">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl mx-auto text-center mb-16 space-y-3">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-white text-stone-700 border border-stone-200 tracking-wide uppercase">
                        Alur Sederhana
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-stone-950 tracking-tight">
                        Tiga langkah menuju portofolio kompetitif.
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white p-7 rounded-xl border border-stone-200/80 craft-card-shadow space-y-4">
                        <span class="font-mono-code text-3xl font-extrabold text-stone-300">01</span>
                        <h4 class="text-lg font-bold text-stone-900 mb-1">Bangun Profil & Klaim Skill</h4>
                        <p class="text-sm text-stone-600 leading-relaxed">
                            Tentukan keahlian utama, minat kolaborasi, dan tautkan profil GitHub. Dapatkan rekomendasi proyek yang relevan.
                        </p>
                    </div>

                    <div class="bg-white p-7 rounded-xl border border-stone-200/80 craft-card-shadow space-y-4">
                        <span class="font-mono-code text-3xl font-extrabold text-teal-400">02</span>
                        <h4 class="text-lg font-bold text-stone-900 mb-1">Kolaborasi di Workspace</h4>
                        <p class="text-sm text-stone-600 leading-relaxed">
                            Eksekusi proyek dengan papan Kanban, chat real-time, dan repositori sinkron tanpa hambatan komunikasi.
                        </p>
                    </div>

                    <div class="bg-white p-7 rounded-xl border border-stone-200/80 craft-card-shadow space-y-4">
                        <span class="font-mono-code text-3xl font-extrabold text-stone-300">03</span>
                        <h4 class="text-lg font-bold text-stone-900 mb-1">Dapatkan Validasi Nyata</h4>
                        <p class="text-sm text-stone-600 leading-relaxed">
                            Kumpulkan review rekan setim dan unduh Live Resume PDF terverifikasi saat melamar magang atau beasiswa.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= STUDENT TESTIMONIALS ================= -->
        <section id="testimoni" class="py-24 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto text-center mb-16 space-y-3">
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-teal-50 text-teal-800 border border-teal-200 tracking-wide uppercase">
                    Cerita Mahasiswa
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-stone-950 tracking-tight">
                    Pengalaman nyata dari mereka yang telah bertukar skill.
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Testimonial 1 -->
                <div class="bg-white p-4 sm:p-4 rounded-xl border border-stone-200/80 craft-card-shadow flex flex-col justify-between">
                    <p class="text-stone-600 text-sm leading-relaxed mb-6">
                        &ldquo;Biasanya nyari anak desain buat tugas akhir susahnya minta ampun. Di Swap Hub ketemu partner dari DKV UGM yang pas banget visinya, dan aplikasi kami tembus juara di hackathon nasional.&rdquo;
                    </p>
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=80&h=80&fit=crop&q=80" class="w-10 h-10 rounded-full object-cover shadow-xs" alt="Arya">
                        <div>
                            <h5 class="text-sm font-bold text-stone-900 mb-0">Arya Wicaksono</h5>
                            <p class="text-xs text-stone-400 mb-0">Teknik Informatika • UI</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-white p-4 sm:p-4 rounded-xl border border-stone-200/80 craft-card-shadow flex flex-col justify-between">
                    <p class="text-stone-600 text-sm leading-relaxed mb-6">
                        &ldquo;Fitur Google Calendar dan sinkronisasi GitHub bikin kerja tim berasa kerja di startup beneran. Gak ada lagi alasan lupa deadline atau tugas hilang di grup WhatsApp.&rdquo;
                    </p>
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=80&h=80&fit=crop&q=80" class="w-10 h-10 rounded-full object-cover shadow-xs" alt="Cindy">
                        <div>
                            <h5 class="text-sm font-bold text-stone-900 mb-0">Cindy Clarissa</h5>
                            <p class="text-xs text-stone-400 mb-0">Sistem Informasi • ITB</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-white p-4 sm:p-4 rounded-xl border border-stone-200/80 craft-card-shadow flex flex-col justify-between">
                    <p class="text-stone-600 text-sm leading-relaxed mb-6">
                        &ldquo;Resume PDF otomatis yang diexport dari Swap Hub langsung saya lampirkan saat daftar magang. Recruiter sangat mengapresiasi karena ada bukti peer-review objektif.&rdquo;
                    </p>
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=80&h=80&fit=crop&q=80" class="w-10 h-10 rounded-full object-cover shadow-xs" alt="Fauzan">
                        <div>
                            <h5 class="text-sm font-bold text-stone-900 mb-0">Fauzan Rahman</h5>
                            <p class="text-xs text-stone-400 mb-0">Ilmu Komputer • UGM</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= ELEGANT CRAFT-STYLE MINIMAL DARK CTA ================= -->
        <section class="py-20 bg-stone-950 text-white relative overflow-hidden">
            <!-- Subtle Radial Teal Ambient -->
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_0%,rgba(13,148,136,0.3),rgba(20,20,19,0)_70%)] pointer-events-none"></div>

            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 space-y-4">
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-white/10 text-teal-300 border border-white/15 tracking-wide uppercase">
                    Mulai Sekarang
                </span>
                <h2 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-white leading-tight">
                    Siap Memulai Kolaborasi Pertamamu?
                </h2>
                <p class="text-stone-400 text-base max-w-xl mx-auto leading-relaxed">
                    Bergabung bersama ribuan mahasiswa visioner lainnya. Temukan partner dengan keahlian komplementer hari ini.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full text-sm font-bold bg-white hover:bg-stone-100 text-stone-900 shadow-lg transition-all duration-200 active:scale-95 no-underline w-full sm:w-auto">
                        <span>Daftar Akun Gratis</span>
                        <i class="bi bi-arrow-right text-teal-600"></i>
                    </a>
                    <a href="{{ route('projects.index') }}"
                       class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full text-sm font-semibold bg-stone-900 hover:bg-stone-800 text-stone-200 border border-stone-800 transition-all duration-200 active:scale-95 no-underline w-full sm:w-auto">
                        <span>Lihat Semua Proyek</span>
                    </a>
                </div>
            </div>
        </section>

    </main>

    <!-- ================= CRAFT-STYLE MINIMAL FOOTER ================= -->
    <footer class="bg-white border-t border-stone-200/80 py-12 relative z-10 text-xs">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 pb-10">
                <div class="md:col-span-2 space-y-3">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-stone-900 text-white flex items-center justify-center font-bold text-[10px]">
                            <span class="text-teal-400">S</span>H
                        </div>
                        <span class="text-sm font-bold text-stone-900">SwapHub</span>
                    </div>
                    <p class="text-stone-500 max-w-xs leading-relaxed">
                        Platform ekosistem kolaborasi dan pertukaran skill antarmahasiswa untuk mencetak portofolio kerja nyata.
                    </p>
                </div>

                <div>
                    <h6 class="font-bold uppercase tracking-wider text-stone-900 mb-3">Navigasi</h6>
                    <ul class="space-y-2 text-stone-600 list-none p-0 m-0">
                        <li><a href="#canvas" class="hover:text-stone-900 transition-colors no-underline">Workspace</a></li>
                        <li><a href="#fitur" class="hover:text-stone-900 transition-colors no-underline">Fitur Modular</a></li>
                        <li><a href="{{ route('projects.index') }}" class="hover:text-stone-900 transition-colors no-underline">Cari Proyek</a></li>
                        <li><a href="{{ route('skills.swap.index') }}" class="hover:text-stone-900 transition-colors no-underline">Pasar Skill</a></li>
                    </ul>
                </div>

                <div>
                    <h6 class="font-bold uppercase tracking-wider text-stone-900 mb-3">Integrasi</h6>
                    <ul class="space-y-2 text-stone-600 list-none p-0 m-0">
                        <li>Google Calendar (Spatie)</li>
                        <li>GitHub HMAC Webhook</li>
                        <li>Live Resume PDF</li>
                        <li>Realtime Reverb Chat</li>
                    </ul>
                </div>

                <div>
                    <h6 class="font-bold uppercase tracking-wider text-stone-900 mb-3">Komunitas</h6>
                    <ul class="space-y-2 text-stone-600 list-none p-0 m-0">
                        <li><a href="https://github.com" target="_blank" rel="noopener" class="hover:text-stone-900 transition-colors no-underline">GitHub</a></li>
                        <li><a href="https://linkedin.com" target="_blank" rel="noopener" class="hover:text-stone-900 transition-colors no-underline">LinkedIn</a></li>
                        <li><a href="https://discord.com" target="_blank" rel="noopener" class="hover:text-stone-900 transition-colors no-underline">Discord Mahasiswa</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-stone-100 pt-6 flex flex-col sm:flex-row items-center justify-between text-stone-400 gap-3">
                <p class="mb-0">&copy; {{ date('Y') }} Swap Hub. Dibuat untuk mahasiswa Indonesia.</p>
                <div class="flex items-center gap-4">
                    <span>Privacy</span>
                    <span>Terms</span>
                    <span>System Status</span>
                </div>
            </div>
        </div>
    </footer>

    @include('partials.pwa-install-prompt')
</body>
</html>
