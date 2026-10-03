/**
 * Swap Hub PWA Helper & Service Worker Client
 */

let deferredPrompt = null;

// Helper to trigger installation from anywhere (buttons, banners)
window.installPwa = async function () {
    if (!deferredPrompt) {
        console.log('[PWA] No deferred install prompt available.');
        return false;
    }
    deferredPrompt.prompt();
    const { outcome } = await deferredPrompt.userChoice;
    console.log(`[PWA] Install prompt outcome: ${outcome}`);
    deferredPrompt = null;
    window.dispatchEvent(new CustomEvent('pwa-dismissed'));
    return outcome === 'accepted';
};

// Check if running in standalone mode (already installed as PWA)
window.isPwaStandalone = function () {
    return (
        window.matchMedia('(display-mode: standalone)').matches ||
        window.navigator.standalone === true ||
        document.referrer.includes('android-app://')
    );
};

// 1. Service Worker Registration & Update Handling
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker
            .register('/sw.js', { scope: '/' })
            .then((registration) => {
                console.log('[PWA] Service Worker registered with scope:', registration.scope);

                // Check for updates
                registration.addEventListener('updatefound', () => {
                    const newWorker = registration.installing;
                    if (newWorker) {
                        newWorker.addEventListener('statechange', () => {
                            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                console.log('[PWA] New version available.');
                                window.dispatchEvent(
                                    new CustomEvent('pwa-update-available', {
                                        detail: { registration }
                                    })
                                );
                            }
                        });
                    }
                });
            })
            .catch((error) => {
                console.warn('[PWA] Service Worker registration failed:', error);
            });

        // Detect controller change (when new SW activates)
        let refreshing = false;
        navigator.serviceWorker.addEventListener('controllerchange', () => {
            if (!refreshing) {
                refreshing = true;
                window.location.reload();
            }
        });
    });
}

// 2. Install Prompt Interception
window.addEventListener('beforeinstallprompt', (e) => {
    // Prevent default mini-infobar on mobile
    e.preventDefault();
    deferredPrompt = e;
    window.deferredPwaPrompt = e;

    // Notify UI components that app can be installed
    window.dispatchEvent(
        new CustomEvent('pwa-installable', {
            detail: { canInstall: true }
        })
    );
});

window.addEventListener('appinstalled', () => {
    deferredPrompt = null;
    window.deferredPwaPrompt = null;
    console.log('[PWA] Swap Hub installed successfully.');
    window.dispatchEvent(new CustomEvent('pwa-installed'));
});

// 3. Online / Offline Network Status Detection
function showNetworkToast(isOnline) {
    const existing = document.getElementById('pwa-network-toast');
    if (existing) {
        existing.remove();
    }

    const toast = document.createElement('div');
    toast.id = 'pwa-network-toast';
    toast.className = `fixed bottom-4 left-4 z-50 flex items-center gap-3 px-4 py-3 rounded-2xl shadow-xl backdrop-blur-md text-sm font-semibold transition-all transform duration-300 translate-y-0 ${
        isOnline
            ? 'bg-emerald-950/90 text-emerald-200 border border-emerald-500/30'
            : 'bg-rose-950/90 text-rose-200 border border-rose-500/30'
    }`;

    toast.innerHTML = isOnline
        ? '<i class="bi bi-wifi text-emerald-400 text-base"></i><span>Koneksi kembali terhubung</span>'
        : '<i class="bi bi-wifi-off text-rose-400 text-base"></i><span>Koneksi offline. Beberapa fitur mungkin dibatasi.</span>';

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(10px)';
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

window.addEventListener('online', () => {
    window.dispatchEvent(new CustomEvent('connection-changed', { detail: { online: true } }));
    showNetworkToast(true);
});

window.addEventListener('offline', () => {
    window.dispatchEvent(new CustomEvent('connection-changed', { detail: { online: false } }));
    showNetworkToast(false);
});
