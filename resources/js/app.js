require('./bootstrap');
import Echo from 'laravel-echo';

if (process.env.MIX_APP_ENV == 'local') {
    // url = "//" + window.location.hostname + ':6003';
}

window.io = require('socket.io-client');

console.log("tsets", window.io)
window.Echo = new Echo({
    broadcaster: 'socket.io',
    // host: url,
    host: window.location.hostname + ':6003',
    encrypted: false,
    forceTLS: false,
    transports: ['websocket', 'polling'],
});
console.log(window.Echo);
