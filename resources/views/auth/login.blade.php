@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8">
    <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-12 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-2xl shadow-slate-200/50 dark:shadow-black/50 overflow-hidden">
        
        <!-- Left Column: Modern Hero / Brand Showcase (Desktop) -->
        <div class="hidden lg:flex lg:col-span-5 bg-gradient-to-br from-indigo-600 via-indigo-700 to-slate-900 p-10 flex-col justify-between relative overflow-hidden text-white">
            <!-- Decorative background elements -->
            <div class="absolute -top-24 -right-24 w-72 h-72 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-emerald-500/20 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute inset-0 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:20px_20px] opacity-10 pointer-events-none"></div>

            <div class="relative z-10">
                <!-- Logo -->
                <a href="/" class="inline-flex items-center gap-3 no-underline text-white group mb-12">
                    <div class="w-11 h-11 rounded-2xl bg-white/15 backdrop-blur-md border border-white/20 flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform">
                        <img src="{{ asset('icon.png') }}" alt="Swap Hub" class="w-7 h-7 object-contain">
                    </div>
                    <div>
                        <span class="text-xl font-black tracking-tight block">Swap Hub</span>
                        <span class="text-[10px] uppercase font-bold tracking-widest text-indigo-200 block">Student Collaboration</span>
                    </div>
                </a>

                <!-- Hero Content -->
                <div class="space-y-4">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur border border-white/15 text-xs font-semibold text-indigo-100">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Platform Kolaborasi Mahasiswa #1
                    </span>
                    <h2 class="text-3xl font-black tracking-tight text-white leading-tight">
                        Wujudkan proyek impian bersama rekan terbaik.
                    </h2>
                    <p class="text-sm text-indigo-100/90 leading-relaxed font-normal">
                        Masuk dan lanjutkan sinergi tim, kelola papan Kanban tugas, dan validasi reputasi skill secara transparan.
                    </p>
                </div>
            </div>

            <!-- Value Highlights -->
            <div class="relative z-10 pt-8 border-t border-white/15 space-y-3">
                <div class="flex items-center gap-3 text-xs text-indigo-100">
                    <div class="w-7 h-7 rounded-lg bg-white/10 flex items-center justify-center shrink-0">
                        <i class="bi bi-kanban-fill text-indigo-200"></i>
                    </div>
                    <span>Interactive Kanban & Task Calendar Sync</span>
                </div>
                <div class="flex items-center gap-3 text-xs text-indigo-100">
                    <div class="w-7 h-7 rounded-lg bg-white/10 flex items-center justify-center shrink-0">
                        <i class="bi bi-shield-check text-emerald-300"></i>
                    </div>
                    <span>Peer-to-Peer Verified Skill Portfolio</span>
                </div>
                <div class="flex items-center gap-3 text-xs text-indigo-100">
                    <div class="w-7 h-7 rounded-lg bg-white/10 flex items-center justify-center shrink-0">
                        <i class="bi bi-github text-indigo-200"></i>
                    </div>
                    <span>Automated GitHub Activity Reputations</span>
                </div>
            </div>
        </div>

        <!-- Right Column: Login Form Container -->
        <div class="col-span-12 lg:col-span-7 p-6 sm:p-10 lg:p-12 flex flex-col justify-center">
            <div class="max-w-md mx-auto w-full">
                
                <!-- Mobile Logo Header -->
                <div class="lg:hidden flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-md">
                        <img src="{{ asset('icon.png') }}" alt="Swap Hub" class="w-6 h-6 object-contain">
                    </div>
                    <span class="text-xl font-black text-slate-900 dark:text-slate-100 tracking-tight">Swap Hub</span>
                </div>

                <!-- Form Header -->
                <div class="mb-8">
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-slate-100 tracking-tight">
                        Selamat Datang Kembali
                    </h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Akses akun Swap Hub Anda untuk melanjutkan kolaborasi.
                    </p>
                </div>

                <!-- OAuth Buttons -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
                    <a href="{{ route('auth.google') }}" 
                       class="flex items-center justify-center gap-2.5 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/80 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition-all hover:border-slate-300 dark:hover:border-slate-600 shadow-sm no-underline active:scale-[0.98]">
                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                        </svg>
                        <span>Google</span>
                    </a>

                    <a href="{{ route('auth.github') }}" 
                       class="flex items-center justify-center gap-2.5 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/80 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition-all hover:border-slate-300 dark:hover:border-slate-600 shadow-sm no-underline active:scale-[0.98]">
                        <svg class="w-4 h-4 shrink-0 fill-current" viewBox="0 0 24 24">
                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                        </svg>
                        <span>GitHub</span>
                    </a>
                </div>

                <!-- Divider -->
                <div class="relative my-6 text-center">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-200 dark:border-slate-800"></div>
                    </div>
                    <span class="relative bg-white dark:bg-slate-900 px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                        atau email akun
                    </span>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            Alamat Email
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all"
                                placeholder="nama@kampus.ac.id">
                        </div>
                        @error('email')
                            <p class="text-xs text-red-500 font-semibold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div x-data="{ show: false }">
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                Kata Sandi
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 no-underline transition-colors">
                                    Lupa sandi?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password"
                                class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all"
                                placeholder="••••••••">
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 border-0 bg-transparent transition-colors">
                                <i class="bi" :class="show ? 'bi-eye-slash' : 'bi-eye'"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs text-red-500 font-semibold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center pt-1">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" id="remember_me" name="remember" 
                                class="rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500 dark:bg-slate-800">
                            <span class="text-xs text-slate-600 dark:text-slate-400">Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" 
                            class="w-full py-3 px-5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold shadow-lg shadow-indigo-500/25 transition-all duration-150 active:scale-[0.98] flex items-center justify-center gap-2 border-0 cursor-pointer">
                            <span>Masuk ke Workspace</span>
                            <i class="bi bi-arrow-right text-xs"></i>
                        </button>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center pt-4">
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-0">
                            Belum memiliki akun? 
                            <a href="{{ route('register') }}" class="font-bold text-indigo-600 dark:text-indigo-400 hover:underline no-underline">
                                Daftar Sekarang
                            </a>
                        </p>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>
@endsection