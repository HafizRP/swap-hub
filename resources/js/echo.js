import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '4ac83a58a7171f24d51b',
    cluster: 'ap1',
    forceTLS: true,
    encrypted: true,
});
