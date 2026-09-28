<section>
    <header class="mb-6">
        <h3 class="text-lg font-black text-slate-900 dark:text-slate-100 flex items-center gap-2">
            <i class="bi bi-shield-lock text-indigo-600 dark:text-indigo-400"></i>
            <span>Keamanan & Kata Sandi</span>
        </h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
            Pastikan akun Anda menggunakan kata sandi yang aman dan tidak digunakan di platform lain.
        </p>
    </header>
 
    <form method="post" action="{{ route('password.update') }}" class="space-y-4" x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
        @csrf
        @method('put')
 
        <!-- Current Password -->
        <div>
            <label for="update_password_current_password" class="block font-bold text-slate-700 dark:text-slate-300 text-xs uppercase tracking-wider mb-2">
                Kata Sandi Saat Ini
            </label>
            <div class="relative">
                <input id="update_password_current_password" name="current_password" :type="showCurrent ? 'text' : 'password'" 
                       class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl px-4 pr-10 py-2.5 text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all" 
                       autocomplete="current-password" placeholder="••••••••">
                <button type="button" @click="showCurrent = !showCurrent" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 border-0 bg-transparent transition-colors">
                    <i class="bi text-xs" :class="showCurrent ? 'bi-eye-slash' : 'bi-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1.5" />
        </div>
 
        <!-- New Password -->
        <div>
            <label for="update_password_password" class="block font-bold text-slate-700 dark:text-slate-300 text-xs uppercase tracking-wider mb-2">
                Kata Sandi Baru
            </label>
            <div class="relative">
                <input id="update_password_password" name="password" :type="showNew ? 'text' : 'password'" 
                       class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl px-4 pr-10 py-2.5 text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all" 
                       autocomplete="new-password" placeholder="Minimal 8 karakter">
                <button type="button" @click="showNew = !showNew" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 border-0 bg-transparent transition-colors">
                    <i class="bi text-xs" :class="showNew ? 'bi-eye-slash' : 'bi-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1.5" />
        </div>
 
        <!-- Confirm New Password -->
        <div>
            <label for="update_password_password_confirmation" class="block font-bold text-slate-700 dark:text-slate-300 text-xs uppercase tracking-wider mb-2">
                Ulangi Kata Sandi Baru
            </label>
            <div class="relative">
                <input id="update_password_password_confirmation" name="password_confirmation" :type="showConfirm ? 'text' : 'password'" 
                       class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl px-4 pr-10 py-2.5 text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all" 
                       autocomplete="new-password" placeholder="Ketik ulang kata sandi">
                <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 border-0 bg-transparent transition-colors">
                    <i class="bi text-xs" :class="showConfirm ? 'bi-eye-slash' : 'bi-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1.5" />
        </div>
 
        <!-- Actions -->
        <div class="flex items-center gap-4 pt-4 border-t border-slate-100 dark:border-slate-700/60">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl text-xs shadow-sm transition-all duration-150 active:scale-[0.98] border-0 cursor-pointer">
                Perbarui Kata Sandi
            </button>
 
            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)"
                    class="text-xs text-emerald-600 dark:text-emerald-400 font-bold mb-0">
                    <i class="bi bi-check-circle-fill mr-1"></i>
                    Kata sandi berhasil diubah.
                </p>
            @endif
        </div>
    </form>
</section>
