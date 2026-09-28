<section x-data="{ showConfirmModal: false }">
    <header class="mb-4">
        <h3 class="text-base font-extrabold text-red-600 dark:text-red-400 flex items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span>Hapus Akun Pengguna</span>
        </h3>
        <p class="text-xs text-red-700/80 dark:text-red-400/80 mt-1 leading-relaxed">
            Setelah akun Anda dihapus, semua data profil, riwayat proyek, dan reputasi keahlian akan dihapus secara permanen.
        </p>
    </header>
 
    <button @click="showConfirmModal = true" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition-all duration-150 active:scale-[0.98] border-0 cursor-pointer shadow-sm">
        Hapus Akun Permanen
    </button>
 
    <!-- Modal (Alpine.js) -->
    <div x-show="showConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-slate-900/60 backdrop-blur-sm p-4" style="display: none;" x-transition>
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl max-w-md w-full shadow-2xl p-6" @click.outside="showConfirmModal = false">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100 dark:border-slate-700/50 mb-4">
                <h4 class="font-extrabold text-slate-900 dark:text-slate-100 text-base mb-0">
                    Konfirmasi Hapus Akun
                </h4>
                <button @click="showConfirmModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 border-0 bg-transparent cursor-pointer">
                    <i class="bi bi-x-lg text-sm"></i>
                </button>
            </div>
            
            <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
                @csrf
                @method('delete')
 
                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed mb-0">
                    Tindakan ini tidak dapat dibatalkan. Masukkan kata sandi akun Anda untuk mengonfirmasi penghapusan identitas profil mahasiswa ini.
                </p>
 
                <div>
                    <label for="delete_password" class="block text-slate-700 dark:text-slate-300 text-xs font-bold uppercase tracking-wider mb-2">Kata Sandi Anda</label>
                    <input id="delete_password" name="password" type="password" 
                           class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-2.5 text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all" 
                           placeholder="Ketik kata sandi untuk konfirmasi...">
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1.5" />
                </div>
 
                <div class="flex justify-end gap-2.5 pt-3 border-t border-slate-100 dark:border-slate-700/50">
                    <button type="button" @click="showConfirmModal = false" class="py-2.5 px-4 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 text-xs font-bold cursor-pointer bg-transparent">
                        Batal
                    </button>
 
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 px-5 rounded-xl text-xs shadow-sm transition-all duration-150 active:scale-[0.98] border-0 cursor-pointer">
                        Ya, Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
