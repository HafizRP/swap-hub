@section('title', 'Konfirmasi Sandi')
<x-guest-layout>
    <div class="text-center mb-6">
        <div class="w-14 h-14 mx-auto rounded-lg bg-teal-500/10 dark:bg-teal-500/20 text-teal-600 dark:text-teal-400 flex items-center justify-center mb-4">
            <i class="bi bi-shield-lock-fill text-2xl"></i>
        </div>
        <h1 class="text-2xl font-black text-stone-900 dark:text-stone-100 tracking-tight">
            Konfirmasi Sandi
        </h1>
        <p class="text-xs sm:text-sm text-stone-500 dark:text-stone-400 mt-2 leading-relaxed">
            Ini adalah area aman aplikasi. Masukkan kata sandi Anda untuk melanjutkan.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4" x-data="{ show: false }">
        @csrf

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-bold text-stone-700 dark:text-stone-300 uppercase tracking-wider mb-2">
                Kata Sandi
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                    <i class="bi bi-lock"></i>
                </span>
                <input id="password" type="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password" autofocus
                    class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-stone-200 dark:border-stone-800 bg-stone-50 dark:bg-[#2e2c29]/50 text-sm text-stone-900 dark:text-stone-100 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all"
                    placeholder="••••••••">
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-stone-400 hover:text-stone-600 dark:hover:text-stone-200 border-0 bg-transparent transition-colors">
                    <i class="bi text-xs" :class="show ? 'bi-eye-slash' : 'bi-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="pt-2">
            <button type="submit"
                class="w-full py-2.5 px-4 rounded-lg bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium shadow-sm transition-all duration-150 active:scale-[0.98] flex items-center justify-center gap-2 border-0 cursor-pointer">
                <span>Konfirmasi Lanjutkan</span>
                <i class="bi bi-arrow-right text-xs"></i>
            </button>
        </div>
    </form>
</x-guest-layout>