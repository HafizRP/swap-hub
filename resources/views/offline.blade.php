<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Koneksi Terputus - {{ config('app.name', 'Swap Hub') }}</title>
    @include('partials.pwa-head')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-4 antialiased font-sans selection:bg-indigo-500 selection:text-white">
    <div class="max-w-md w-full bg-slate-800/80 border border-slate-700/60 backdrop-blur-xl rounded-3xl p-8 text-center shadow-2xl">
        <div class="w-20 h-20 mx-auto mb-6 bg-indigo-500/10 border border-indigo-500/30 rounded-2xl flex items-center justify-center text-indigo-400 text-3xl">
            <i class="bi bi-wifi-off"></i>
        </div>
        <h1 class="text-2xl font-black tracking-tight mb-2 text-white">Anda Sedang Offline</h1>
        <p class="text-sm text-slate-400 leading-relaxed mb-8">
            Koneksi internet Anda terputus. Swap Hub menyimpan beberapa halaman dalam cache, tetapi pembaruan data dan obrolan membutuhkan jaringan aktif.
        </p>
        <button onclick="window.location.reload()" class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center justify-center gap-2 cursor-pointer">
            <i class="bi bi-arrow-clockwise text-base"></i>
            Coba Sambungkan Ulang
        </button>
        <div class="mt-6 inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold text-rose-400 bg-rose-500/10 border border-rose-500/20">
            <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
            Mode Offline Aktif
        </div>
    </div>
    <script>
        window.addEventListener('online', () => window.location.reload());
    </script>
</body>
</html>
