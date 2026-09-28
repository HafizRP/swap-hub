<section>
    <header class="mb-6">
        <h3 class="text-lg font-black text-slate-900 dark:text-white flex items-center gap-2">
            <i class="bi bi-shield-lock-fill text-brand-600"></i>
            <span>Ubah Kata Sandi</span>
        </h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
            Pastikan akun Anda menggunakan kata sandi yang kuat untuk menjaga keamanan.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                Kata Sandi Saat Ini <span class="text-rose-500">*</span>
            </label>
            <input id="update_password_current_password" name="current_password" type="password" 
                   class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-brand-500 outline-none" 
                   autocomplete="current-password">
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="update_password_password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                    Kata Sandi Baru <span class="text-rose-500">*</span>
                </label>
                <input id="update_password_password" name="password" type="password" 
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-brand-500 outline-none" 
                       autocomplete="new-password">
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
            </div>

            <div>
                <label for="update_password_password_confirmation" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                    Konfirmasi Kata Sandi Baru <span class="text-rose-500">*</span>
                </label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-brand-500 outline-none" 
                       autocomplete="new-password">
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
            </div>
        </div>

        <div class="flex items-center gap-3 pt-4">
            <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-500/20 transition-all">
                Simpan Kata Sandi
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)"
                   class="text-xs text-emerald-600 dark:text-emerald-400 font-bold">
                    Kata sandi berhasil diubah!
                </p>
            @endif
        </div>
    </form>
</section>
