window.Pusher = Pusher;

const wsHost = import.meta.env.VITE_REVERB_HOST || (typeof window !== 'undefined' ? window.location.hostname : '127.0.0.1');
const wsPort = import.meta.env.VITE_REVERB_PORT 
    ? Number(import.meta.env.VITE_REVERB_PORT) 
    : (typeof window !== 'undefined' && window.location.port ? Number(window.location.port) : (typeof window !== 'undefined' && window.location.protocol === 'https:' ? 443 : 80));
const wsScheme = import.meta.env.VITE_REVERB_SCHEME || (typeof window !== 'undefined' && window.location.protocol === 'https:' ? 'https' : 'http');

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY || 'swaphubreverbkey',
    wsHost: wsHost,
    wsPort: wsPort,
    wssPort: wsPort,
    forceTLS: wsScheme === 'https',
    enabledTransports: ['ws', 'wss'],
});