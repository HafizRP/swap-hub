<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="w-14 h-14 rounded-2xl bg-brand-500/10 text-brand-600 dark:text-brand-400 mx-auto flex items-center justify-center text-2xl mb-3">
            <i class="bi bi-envelope-check-fill"></i>
        </div>
        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Verifikasi Email Anda</h2>
        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
            Terima kasih telah mendaftar! Silakan klik tautan verifikasi yang baru saja kami kirimkan ke email Anda sebelum mulai berkolaborasi.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/40 text-emerald-700 dark:text-emerald-300 text-xs flex items-center gap-2 font-medium">
            <i class="bi bi-check-circle-fill text-base shrink-0"></i>
            <span>Tautan verifikasi baru telah dikirim ke alamat email Anda.</span>
        </div>
    @endif

    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                    class="w-full py-2.5 px-4 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-bold shadow-md shadow-brand-500/20 transition-all flex items-center justify-center gap-2">
                <i class="bi bi-send-fill text-xs"></i>
                <span>Kirim Ulang Email Verifikasi</span>
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf
            <button type="submit" class="text-xs font-semibold text-slate-500 hover:text-rose-600 dark:text-slate-400 dark:hover:text-rose-400 transition-colors">
                Keluar Akun
            </button>
        </form>
    </div>
</x-guest-layout>
