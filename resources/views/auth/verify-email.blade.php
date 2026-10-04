@extends('layouts.auth')

@section('title', 'Verifikasi Email')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4 sm:p-4 lg:p-4">
    <div class="w-full max-w-md bg-white dark:bg-[#141414] rounded-xl border border-stone-200 dark:border-stone-800 shadow-xl overflow-hidden p-4 sm:p-4">
        
        <!-- Header -->
        <div class="text-center mb-6">
            <div class="w-16 h-16 mx-auto rounded-lg bg-teal-500/10 dark:bg-teal-500/20 text-teal-600 dark:text-teal-400 flex items-center justify-center mb-4">
                <i class="bi bi-envelope-check-fill text-3xl"></i>
            </div>
            <h1 class="text-2xl font-black text-stone-900 dark:text-stone-100 tracking-tight">
                Cek Email Anda
            </h1>
            <p class="text-xs sm:text-sm text-stone-500 dark:text-stone-400 mt-2 leading-relaxed">
                Terima kasih telah bergabung! Silakan klik tautan verifikasi yang baru saja kami kirimkan ke email Anda untuk mengaktifkan akun.
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-xs font-semibold text-emerald-700 dark:text-emerald-300 flex items-center gap-2">
                <i class="bi bi-check-circle-fill shrink-0"></i>
                <span>Tautan verifikasi baru telah berhasil dikirim ke alamat email Anda.</span>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="space-y-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" 
                    class="w-full py-2.5 px-4 rounded-lg bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium shadow-sm transition-all duration-150 active:scale-[0.98] flex items-center justify-center gap-2 border-0 cursor-pointer">
                    <i class="bi bi-send-fill text-xs"></i>
                    <span>Kirim Ulang Email Verifikasi</span>
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="text-center">
                @csrf
                <button type="submit" class="w-full py-2.5 px-4 rounded-lg border border-stone-200 dark:border-stone-800 hover:bg-stone-50 dark:hover:bg-stone-800/60 text-stone-600 dark:text-stone-400 text-xs font-semibold transition-colors cursor-pointer bg-transparent">
                    <i class="bi bi-box-arrow-right mr-1.5"></i>
                    Keluar / Ganti Akun
                </button>
            </form>
        </div>

        <!-- Help Info -->
        <div class="mt-6 pt-5 border-t border-stone-100 dark:border-stone-800 text-center">
            <p class="text-xs text-stone-400 dark:text-stone-500 mb-0">
                Tidak menemukan email? Cek folder spam atau promosi di inbox Anda.
            </p>
        </div>

    </div>
</div>
@endsection