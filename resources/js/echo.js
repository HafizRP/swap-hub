import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// Debug config logging
console.log('Initializing Echo with config:', {
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    wsHost: import.meta.env.VITE_PUSHER_HOST,
    forceTLS: (import.meta.env.VITE_PUSHER_SCHEME === 'https')
});

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    wsHost: import.meta.env.VITE_PUSHER_HOST,
    wsPort: import.meta.env.VITE_PUSHER_PORT,
    wssPort: import.meta.env.VITE_PUSHER_PORT,
    forceTLS: (import.meta.env.VITE_PUSHER_SCHEME === 'https'),
    encrypted: true,
    enabledTransports: ['ws', 'wss'],
    disableStats: true,
});

// Connection debugging
window.Echo.connector.pusher.connection.bind('connected', () => {
    console.log('✅ Echo connected to Pusher!');
});
window.Echo.connector.pusher.connection.bind('error', (err) => {
    console.error('❌ Echo connection error:', err);
});
window.Echo.connector.pusher.connection.bind('disconnected', () => {
    console.warn('⚠️ Echo disconnected');
});