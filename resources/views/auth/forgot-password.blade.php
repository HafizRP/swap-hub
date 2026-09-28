<x-guest-layout>
    <div class="mb-4 text-center">
        <div class="w-12 h-12 rounded-xl bg-brand-500/10 text-brand-600 dark:text-brand-400 mx-auto flex items-center justify-center text-xl mb-3">
            <i class="bi bi-key-fill"></i>
        </div>
        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Lupa Kata Sandi?</h2>
        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
            Masukkan email terdaftar Anda. Kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Email Mahasiswa</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   placeholder="nama@university.ac.id"
                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all">
            @error('email')
                <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full py-2.5 px-4 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-bold shadow-md shadow-brand-500/20 transition-all flex items-center justify-center gap-2">
            <span>Kirim Link Reset Sandi</span>
            <i class="bi bi-envelope-fill text-xs"></i>
        </button>

        <div class="text-center pt-2">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-brand-600 dark:text-slate-400 dark:hover:text-brand-400 transition-colors">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke halaman Masuk</span>
            </a>
        </div>
    </form>
</x-guest-layout>
