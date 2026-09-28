<section x-data="{ showConfirmModal: false }">
    <header class="mb-6">
        <h3 class="text-lg font-black text-rose-600 dark:text-rose-400 flex items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span>Hapus Akun Permanen</span>
        </h3>
        <p class="text-xs text-rose-700/80 dark:text-rose-300/80 mt-1">
            Setelah akun Anda dihapus, semua data profil, proyek, dan riwayat kolaborasi akan dihapus secara permanen.
        </p>
    </header>

    <button @click="showConfirmModal = true"
            type="button"
            class="px-5 py-2.5 rounded-xl border border-rose-300 dark:border-rose-800 hover:bg-rose-600 hover:text-white text-rose-600 dark:text-rose-400 text-xs font-bold transition-all shadow-sm">
        Hapus Akun Saya
    </button>

    <!-- Modal (Alpine.js) -->
    <div x-show="showConfirmModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4"
         x-transition>
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl max-w-md w-full shadow-2xl p-6 space-y-4"
             @click.outside="showConfirmModal = false">
            
            <div class="flex justify-between items-center pb-3 border-b border-slate-100 dark:border-slate-800">
                <h4 class="font-black text-slate-900 dark:text-white text-base flex items-center gap-2 text-rose-600">
                    <i class="bi bi-shield-x"></i>
                    Konfirmasi Hapus Akun
                </h4>
                <button @click="showConfirmModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
                @csrf
                @method('delete')

                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                    Tindakan ini tidak dapat dibatalkan. Masukkan kata sandi akun Anda untuk mengonfirmasi penghapusan akun.
                </p>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Kata Sandi Konfirmasi
                    </label>
                    <input id="password" name="password" type="password"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-rose-500 outline-none"
                           placeholder="Masukkan kata sandi...">
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1" />
                </div>

                <div class="flex justify-end gap-2.5 pt-3 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showConfirmModal = false"
                            class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-md shadow-rose-500/20 transition-all">
                        Ya, Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
