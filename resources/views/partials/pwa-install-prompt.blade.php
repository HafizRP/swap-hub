<div x-data="{
    canInstall: false,
    updateAvailable: false,
    waitingWorker: null,
    dismissed: localStorage.getItem('pwa_prompt_dismissed') === 'true',
    init() {
        if (window.deferredPwaPrompt) {
            this.canInstall = true;
        }
        window.addEventListener('pwa-installable', () => {
            if (!this.dismissed && !window.isPwaStandalone()) {
                this.canInstall = true;
            }
        });
        window.addEventListener('pwa-installed', () => {
            this.canInstall = false;
        });
        window.addEventListener('pwa-dismissed', () => {
            this.canInstall = false;
        });
        window.addEventListener('pwa-update-available', (e) => {
            this.updateAvailable = true;
            this.waitingWorker = e.detail?.registration?.waiting;
        });
    },
    install() {
        window.installPwa().then(() => {
            this.canInstall = false;
        });
    },
    dismiss() {
        this.canInstall = false;
        this.dismissed = true;
        localStorage.setItem('pwa_prompt_dismissed', 'true');
    },
    refresh() {
        if (this.waitingWorker) {
            this.waitingWorker.postMessage({ type: 'SKIP_WAITING' });
        } else {
            window.location.reload();
        }
    }
}" x-cloak>
    <!-- Update Notification Banner -->
    <div x-show="updateAvailable" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="fixed bottom-5 right-5 z-50 max-w-sm w-full bg-slate-900 dark:bg-slate-950 text-white p-4 rounded-2xl border border-indigo-500/40 shadow-2xl flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-indigo-600/30 text-indigo-400 flex items-center justify-center shrink-0">
            <i class="bi bi-arrow-repeat text-xl animate-spin"></i>
        </div>
        <div class="flex-1 min-w-0">
            <h4 class="text-xs font-bold uppercase tracking-wider text-indigo-400">Pembaruan Tersedia</h4>
            <p class="text-xs text-slate-300 truncate">Versi terbaru Swap Hub siap digunakan.</p>
        </div>
        <button @click="refresh()" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-lg transition-all shrink-0 cursor-pointer">
            Segarkan
        </button>
    </div>

    <!-- PWA Install Banner -->
    <div x-show="canInstall && !dismissed" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="fixed bottom-5 right-5 z-50 max-w-md w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 rounded-2xl shadow-2xl flex items-center gap-4">
        <img src="{{ asset('icons/icon-96x96.png') }}" alt="Swap Hub Icon" class="w-12 h-12 rounded-xl object-contain shadow-md shrink-0">
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-1.5">
                <h4 class="text-sm font-bold text-slate-900 dark:text-white truncate">Pasang Swap Hub</h4>
                <span class="px-1.5 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wide bg-indigo-100 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400">PWA</span>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Akses lebih cepat & tanpa browser frame.</p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <button @click="dismiss()" class="text-xs font-semibold text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 px-2 py-1.5 transition-colors cursor-pointer">
                Nanti
            </button>
            <button @click="install()" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 active:scale-95 text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-600/20 transition-all cursor-pointer">
                Pasang
            </button>
        </div>
    </div>
</div>
