@extends('layouts.auth')

@section('title', 'Atur Ulang Sandi')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4 sm:p-4 lg:p-4">
    <div class="w-full max-w-md bg-white dark:bg-[#141414] rounded-xl border border-stone-200 dark:border-stone-800 shadow-xl overflow-hidden p-4 sm:p-4">
        
        <!-- Header -->
        <div class="text-center mb-6">
            <div class="w-14 h-14 mx-auto rounded-lg bg-teal-500/10 dark:bg-teal-500/20 text-teal-600 dark:text-teal-400 flex items-center justify-center mb-4">
                <i class="bi bi-shield-lock-fill text-2xl"></i>
            </div>
            <h1 class="text-2xl font-black text-stone-900 dark:text-stone-100 tracking-tight">
                Atur Kata Sandi Baru
            </h1>
            <p class="text-xs sm:text-sm text-stone-500 dark:text-stone-400 mt-2 leading-relaxed">
                Buat kata sandi baru yang kuat dan aman untuk akun Swap Hub Anda.
            </p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('password.store') }}" class="space-y-4" x-data="{ showPass: false, showConfirm: false }">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-bold text-stone-700 dark:text-stone-300 uppercase tracking-wider mb-2">
                    Alamat Email
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-stone-200 dark:border-stone-800 bg-stone-50 dark:bg-[#2e2c29]/50 text-sm text-stone-900 dark:text-stone-100 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all"
                        placeholder="nama@kampus.ac.id">
                </div>
                @error('email')
                    <p class="text-xs text-red-500 font-semibold mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- New Password -->
            <div>
                <label for="password" class="block text-xs font-bold text-stone-700 dark:text-stone-300 uppercase tracking-wider mb-2">
                    Kata Sandi Baru
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input id="password" type="password" :type="showPass ? 'text' : 'password'" name="password" required autocomplete="new-password"
                        class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-stone-200 dark:border-stone-800 bg-stone-50 dark:bg-[#2e2c29]/50 text-sm text-stone-900 dark:text-stone-100 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all"
                        placeholder="Minimal 8 karakter">
                    <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-stone-400 hover:text-stone-600 dark:hover:text-stone-200 border-0 bg-transparent transition-colors">
                        <i class="bi text-xs" :class="showPass ? 'bi-eye-slash' : 'bi-eye'"></i>
                    </button>
                </div>
                @error('password')
                    <p class="text-xs text-red-500 font-semibold mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-stone-700 dark:text-stone-300 uppercase tracking-wider mb-2">
                    Konfirmasi Kata Sandi Baru
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                        <i class="bi bi-lock-fill"></i>
                    </span>
                    <input id="password_confirmation" type="password" :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password"
                        class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-stone-200 dark:border-stone-800 bg-stone-50 dark:bg-[#2e2c29]/50 text-sm text-stone-900 dark:text-stone-100 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all"
                        placeholder="Ulangi kata sandi baru">
                    <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-stone-400 hover:text-stone-600 dark:hover:text-stone-200 border-0 bg-transparent transition-colors">
                        <i class="bi text-xs" :class="showConfirm ? 'bi-eye-slash' : 'bi-eye'"></i>
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit" 
                    class="w-full py-2.5 px-4 rounded-lg bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium shadow-sm transition-all duration-150 active:scale-[0.98] flex items-center justify-center gap-2 border-0 cursor-pointer">
                    <span>Simpan Kata Sandi Baru</span>
                    <i class="bi bi-arrow-right text-xs"></i>
                </button>
            </div>
        </form>

        <!-- Back to Login -->
        <div class="text-center pt-6 mt-6 border-t border-stone-100 dark:border-stone-800">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-xs font-bold text-stone-500 dark:text-stone-400 hover:text-teal-600 dark:hover:text-teal-400 no-underline transition-colors">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Halaman Masuk</span>
            </a>
        </div>

    </div>
</div>
@endsection