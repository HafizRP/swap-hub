/**
 * Swap Hub Service Worker
 * Comprehensive PWA caching, offline fallback, and background sync helper.
 */

const CACHE_VERSION = 'swaphub-v1.0.0';
const CORE_CACHE = `core-${CACHE_VERSION}`;
const RUNTIME_CACHE = `runtime-${CACHE_VERSION}`;

// Essential static assets precached on installation
const PRECACHE_ASSETS = [
    '/offline',
    '/offline.html',
    '/manifest.json',
    '/favicon.ico',
    '/icon.png',
    '/icons/icon-72x72.png',
    '/icons/icon-96x96.png',
    '/icons/icon-128x128.png',
    '/icons/icon-144x144.png',
    '/icons/icon-152x152.png',
    '/icons/icon-192x192.png',
    '/icons/icon-384x384.png',
    '/icons/icon-512x512.png',
    '/icons/icon-512x512-maskable.png',
    '/icons/apple-touch-icon.png',
    'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css'
];

// Domains/Paths to bypass caching (Real-time WebSockets, auth, Livewire updates)
const BYPASS_PATTERNS = [
    /\/app\//,
    /\/apps\//,
    /\/livewire\//,
    /\/broadcasting\//,
    /\/webhooks\//,
    /\/auth\//,
    /\/api\//
];

// 1. Install Event: Precache core assets
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CORE_CACHE)
            .then((cache) => {
                return cache.addAll(PRECACHE_ASSETS).catch((err) => {
                    console.warn('[PWA SW] Precache warning:', err);
                });
            })
            .then(() => self.skipWaiting())
    );
});

// 2. Activate Event: Clean up legacy caches
self.addEventListener('activate', (event) => {
    const currentCaches = [CORE_CACHE, RUNTIME_CACHE];
    event.waitUntil(
        caches.keys()
            .then((keys) => {
                return Promise.all(
                    keys.map((key) => {
                        if (!currentCaches.includes(key)) {
                            console.log('[PWA SW] Deleting stale cache:', key);
                            return caches.delete(key);
                        }
                    })
                );
            })
            .then(() => self.clients.claim())
    );
});

// 3. Fetch Event
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Only process GET requests from http/https
    if (request.method !== 'GET' || !url.protocol.startsWith('http')) {
        return;
    }

    // Bypass real-time, auth, and stateful endpoints
    if (BYPASS_PATTERNS.some((pattern) => pattern.test(url.pathname))) {
        return;
    }

    // A. Navigation requests (HTML documents)
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request)
                .then((networkResponse) => {
                    if (networkResponse && networkResponse.status === 200) {
                        const copy = networkResponse.clone();
                        caches.open(RUNTIME_CACHE).then((cache) => cache.put(request, copy));
                    }
                    return networkResponse;
                })
                .catch(async () => {
                    // Try runtime cache first
                    const cachedResponse = await caches.match(request);
                    if (cachedResponse) {
                        return cachedResponse;
                    }
                    // Fallback to offline blade route or static offline.html
                    const offlineBlade = await caches.match('/offline');
                    if (offlineBlade) {
                        return offlineBlade;
                    }
                    return caches.match('/offline.html');
                })
        );
        return;
    }

    // B. Static Assets (Vite build assets, images, icons, fonts)
    const isStaticAsset =
        url.pathname.startsWith('/build/') ||
        url.pathname.startsWith('/icons/') ||
        /\.(css|js|woff2?|ttf|eot|svg|png|jpg|jpeg|gif|webp|ico)$/i.test(url.pathname);

    if (isStaticAsset) {
        event.respondWith(
            caches.match(request).then((cachedResponse) => {
                if (cachedResponse) {
                    // Stale-While-Revalidate: serve cached version, update in background
                    fetch(request)
                        .then((networkResponse) => {
                            if (networkResponse && networkResponse.status === 200) {
                                caches.open(RUNTIME_CACHE).then((cache) => cache.put(request, networkResponse));
                            }
                        })
                        .catch(() => {});
                    return cachedResponse;
                }

                // If not cached, fetch from network and store in runtime cache
                return fetch(request)
                    .then((networkResponse) => {
                        if (networkResponse && networkResponse.status === 200) {
                            const copy = networkResponse.clone();
                            caches.open(RUNTIME_CACHE).then((cache) => cache.put(request, copy));
                        }
                        return networkResponse;
                    })
                    .catch(() => {
                        // Return empty or fallback if needed
                    });
            })
        );
        return;
    }

    // C. Default: Network first with cache fallback
    event.respondWith(
        fetch(request)
            .then((networkResponse) => {
                if (networkResponse && networkResponse.status === 200) {
                    const copy = networkResponse.clone();
                    caches.open(RUNTIME_CACHE).then((cache) => cache.put(request, copy));
                }
                return networkResponse;
            })
            .catch(() => caches.match(request))
    );
});

// 4. Message Event: Allow web clients to trigger skipWaiting
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});
