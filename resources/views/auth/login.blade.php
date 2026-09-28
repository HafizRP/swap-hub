@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
<div class="min-h-screen flex flex-col lg:flex-row">
    <!-- Left Hero Banner (Desktop) -->
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-brand-900 via-indigo-950 to-slate-950 p-12 flex-col justify-between relative overflow-hidden text-white">
        <!-- Ambient Background Pattern -->
        <div class="absolute inset-0 bg-[radial-gradient(#6366f1_1px,transparent_1px)] [background-size:24px_24px] opacity-20"></div>
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-brand-500/20 rounded-full blur-[100px] pointer-events-none"></div>

        <!-- Brand -->
        <div class="relative z-10">
            <a href="/" class="inline-flex items-center gap-2.5">
                <img src="{{ asset('icon.png') }}" alt="Swap Hub" class="w-9 h-9 rounded-lg">
                <span class="text-2xl font-extrabold tracking-tight">Swap<span class="text-brand-400">Hub</span></span>
            </a>
        </div>

        <!-- Value Prop -->
        <div class="relative z-10 max-w-lg space-y-6">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md text-xs font-semibold text-brand-200">
                <i class="bi bi-stars"></i> Kolaborasi Mahasiswa
            </div>
            <h1 class="text-4xl font-extrabold leading-tight tracking-tight">
                Selamat Datang Kembali di Ekosistem Swap Hub.
            </h1>
            <p class="text-slate-300 text-base leading-relaxed">
                Lanjutkan pengelolaan proyek, kolaborasi tugas di Kanban, dan kembangkan portofolio nyata bersama rekan tim.
            </p>
        </div>

        <!-- Social Proof -->
        <div class="relative z-10 pt-6 border-t border-white/10 flex items-center justify-between text-xs text-slate-400">
            <span>&copy; {{ date('Y') }} Swap Hub Platform</span>
            <span class="font-medium text-slate-300">2,000+ Mahasiswa Aktif</span>
        </div>
    </div>

    <!-- Right Form Area -->
    <div class="flex-1 flex items-center justify-center p-6 sm:p-12 lg:p-16 bg-white dark:bg-slate-900 transition-colors">
        <div class="w-full max-w-md space-y-8" x-data="{ showPass: false }">
            <!-- Mobile Brand Header -->
            <div class="lg:hidden text-center">
                <a href="/" class="inline-flex items-center gap-2 mb-4">
                    <img src="{{ asset('icon.png') }}" alt="Swap Hub" class="w-8 h-8 rounded-lg">
                    <span class="text-xl font-extrabold text-slate-900 dark:text-white">Swap<span class="text-brand-500">Hub</span></span>
                </a>
            </div>

            <div class="space-y-2">
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">Masuk ke Akun</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">Masukkan kredensial untuk mengakses workspace Anda.</p>
            </div>

            <!-- Social OAuth Buttons -->
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('auth.google') }}"
                   class="inline-flex items-center justify-center gap-2.5 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-750 text-xs font-bold transition-all shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    <span>Google</span>
                </a>

                <a href="{{ route('auth.github') }}"
                   class="inline-flex items-center justify-center gap-2.5 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-750 text-xs font-bold transition-all shadow-sm">
                    <i class="bi bi-github text-base"></i>
                    <span>GitHub</span>
                </a>
            </div>

            <!-- Divider -->
            <div class="relative flex items-center justify-center">
                <div class="border-t border-slate-200 dark:border-slate-800 w-full"></div>
                <span class="bg-white dark:bg-slate-900 px-3 text-xs text-slate-400 font-medium absolute">atau email</span>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Email Mahasiswa</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                           placeholder="nama@university.ac.id"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all">
                    @error('email')
                        <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-300">Kata Sandi</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-500 dark:text-brand-400">
                                Lupa sandi?
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <input id="password" :type="showPass ? 'text' : 'password'" name="password" required autocomplete="current-password"
                               placeholder="••••••••"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all pr-10">
                        <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                            <i class="bi text-base" :class="showPass ? 'bi-eye-slash-fill' : 'bi-eye-fill'"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 text-brand-600 focus:ring-brand-500 bg-white dark:bg-slate-800">
                    <label for="remember_me" class="ml-2 block text-xs font-medium text-slate-600 dark:text-slate-400">Ingat saya di perangkat ini</label>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full py-3 px-4 rounded-xl bg-brand-600 hover:bg-brand-700 active:scale-[0.99] text-white text-sm font-bold shadow-md shadow-brand-500/20 transition-all flex items-center justify-center gap-2">
                    <span>Masuk ke Dashboard</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <div class="text-center text-xs text-slate-500 dark:text-slate-400">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-bold text-brand-600 hover:text-brand-500 dark:text-brand-400 ml-1">
                    Daftar Akun Baru
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
