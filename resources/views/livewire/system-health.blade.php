<div wire:poll.5s>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
        <div>
            <p class="text-slate-500 dark:text-slate-400 text-sm mb-0">Real-time health check of application services</p>
        </div>
        <div class="flex items-center gap-2">
            <span wire:loading class="text-indigo-600 dark:text-indigo-400 text-xs font-medium flex items-center gap-1">
                <i class="bi bi-arrow-clockwise animate-spin"></i>Updating...
            </span>
            <button wire:click="$refresh" class="border border-indigo-600 dark:border-indigo-400 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/20 font-bold py-1.5 px-4 rounded-full text-xs transition-colors">
                <i class="bi bi-arrow-clockwise" wire:loading.class="animate-spin"></i> Refresh
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Web Application -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="text-center p-6">
                <div class="mb-4">
                    @if($webStatus == 'OK')
                        <div class="bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 rounded-full inline-flex items-center justify-center w-20 h-20 mx-auto">
                            <i class="bi bi-check-circle-fill text-3xl"></i>
                        </div>
                    @else
                        <div class="bg-red-500/15 text-red-600 dark:text-red-400 rounded-full inline-flex items-center justify-center w-20 h-20 mx-auto">
                            <i class="bi bi-x-circle-fill text-3xl"></i>
                        </div>
                    @endif
                </div>
                <h5 class="font-bold text-slate-800 dark:text-slate-100 mb-1 text-base">Web Application</h5>
                <p class="text-slate-400 dark:text-slate-500 text-xs mb-4">Core Application Framework</p>

                <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl p-4 text-left space-y-2.5">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500 dark:text-slate-400 text-xs">Status:</span>
                        <span class="font-bold text-xs {{ $webStatus == 'OK' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $webStatus }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500 dark:text-slate-400 text-xs">URL:</span>
                        <span class="font-bold text-slate-700 dark:text-slate-200 text-xs truncate max-w-[150px]">
                            {{ $appUrl }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Database -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="text-center p-6">
                <div class="mb-4">
                    @if($dbStatus == 'OK')
                        <div class="bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 rounded-full inline-flex items-center justify-center w-20 h-20 mx-auto">
                            <i class="bi bi-database-fill-check text-3xl"></i>
                        </div>
                    @else
                        <div class="bg-red-500/15 text-red-600 dark:text-red-400 rounded-full inline-flex items-center justify-center w-20 h-20 mx-auto">
                            <i class="bi bi-database-fill-x text-3xl"></i>
                        </div>
                    @endif
                </div>
                <h5 class="font-bold text-slate-800 dark:text-slate-100 mb-1 text-base">Database</h5>
                <p class="text-slate-400 dark:text-slate-500 text-xs mb-4">Primary Data Storage</p>

                <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl p-4 text-left space-y-2.5">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500 dark:text-slate-400 text-xs">Connection:</span>
                        <span class="font-bold text-xs {{ $dbStatus == 'OK' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $dbStatus == 'OK' ? 'Connected' : 'Failed' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500 dark:text-slate-400 text-xs">Latency:</span>
                        <span class="font-bold text-slate-700 dark:text-slate-200 text-xs">{{ $dbLatency }} ms</span>
                    </div>
                    @if($dbStatus != 'OK')
                        <div class="mt-2 p-2.5 bg-red-500/10 rounded-lg text-red-600 dark:text-red-400 text-xs break-words">
                            {{ $dbStatus }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Real-time Service -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="text-center p-6">
                <div class="mb-4">
                    @if($pusherStatus == 'OK')
                        <div class="bg-sky-500/15 text-sky-600 dark:text-sky-400 rounded-full inline-flex items-center justify-center w-20 h-20 mx-auto">
                            <i class="bi bi-broadcast text-3xl"></i>
                        </div>
                    @else
                        <div class="bg-amber-500/15 text-amber-600 dark:text-amber-400 rounded-full inline-flex items-center justify-center w-20 h-20 mx-auto">
                            <i class="bi bi-exclamation-triangle-fill text-3xl"></i>
                        </div>
                    @endif
                </div>
                <h5 class="font-bold text-slate-800 dark:text-slate-100 mb-1 text-base">Real-time Service</h5>
                <p class="text-slate-400 dark:text-slate-500 text-xs mb-4">Reverb / Pusher WebSocket</p>

                <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl p-4 text-left space-y-2.5">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500 dark:text-slate-400 text-xs">Service:</span>
                        <span class="font-bold text-xs {{ $pusherStatus == 'OK' ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                            {{ $pusherStatus == 'OK' ? 'Reachable' : 'Unreachable' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500 dark:text-slate-400 text-xs">Host:</span>
                        <span class="font-bold text-slate-700 dark:text-slate-200 text-xs">{{ $host }}:{{ $port }}</span>
                    </div>
                    @if($pusherError)
                        <div class="mt-2 p-2.5 bg-amber-500/10 rounded-lg text-amber-600 dark:text-amber-400 text-xs break-words">
                            {{ $pusherError }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 text-center">
        <small class="text-slate-400 dark:text-slate-500 text-xs">
            <i class="bi bi-clock mr-1"></i>
            Last checked: {{ now()->format('M d, Y H:i:s') }}
        </small>
    </div>
</div>