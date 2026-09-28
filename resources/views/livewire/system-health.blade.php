<div wire:poll.10s class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h3 class="text-sm font-black uppercase tracking-wider text-slate-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-activity text-brand-600"></i>
                <span>Status Kesehatan Sistem (Real-Time)</span>
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400">Pemeriksaan otomatis kondisi service setiap 10 detik</p>
        </div>
        <div class="flex items-center gap-2">
            <span wire:loading class="text-xs font-bold text-brand-600 dark:text-brand-400 flex items-center gap-1">
                <i class="bi bi-arrow-repeat animate-spin"></i> Memeriksa...
            </span>
            <button wire:click="$refresh" type="button"
                    class="px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-300 transition-colors">
                <i class="bi bi-arrow-clockwise"></i> Perbarui
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Web App -->
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700/80 shadow-sm space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500">Web Service</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ $webStatus === 'OK' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-rose-500/10 text-rose-600' }}">
                    {{ $webStatus === 'OK' ? 'Healthy' : 'Down' }}
                </span>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-lg">
                    <i class="bi bi-hdd-network-fill"></i>
                </div>
                <div>
                    <h4 class="font-bold text-xs text-slate-900 dark:text-white">Laravel Core</h4>
                    <p class="text-[11px] text-slate-400 truncate max-w-[160px]">{{ $appUrl }}</p>
                </div>
            </div>
        </div>

        <!-- Database -->
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700/80 shadow-sm space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500">Database</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ $dbStatus === 'OK' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-rose-500/10 text-rose-600' }}">
                    {{ $dbStatus === 'OK' ? 'Connected (' . $dbLatency . 'ms)' : 'Failed' }}
                </span>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-600 flex items-center justify-center text-lg">
                    <i class="bi bi-database-check"></i>
                </div>
                <div>
                    <h4 class="font-bold text-xs text-slate-900 dark:text-white">MariaDB / MySQL</h4>
                    <p class="text-[11px] text-slate-400">Latensi: {{ $dbLatency }} ms</p>
                </div>
            </div>
        </div>

        <!-- Real-Time WebSocket -->
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700/80 shadow-sm space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500">Broadcasting</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ $pusherStatus === 'OK' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-amber-500/10 text-amber-600' }}">
                    {{ $pusherStatus === 'OK' ? 'Online' : 'Warning' }}
                </span>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-600 flex items-center justify-center text-lg">
                    <i class="bi bi-broadcast"></i>
                </div>
                <div>
                    <h4 class="font-bold text-xs text-slate-900 dark:text-white">Laravel Reverb</h4>
                    <p class="text-[11px] text-slate-400">{{ $host }}:{{ $port }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
