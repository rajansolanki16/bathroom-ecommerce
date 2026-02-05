import axios from "axios";
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;
window.axios = axios;

// 1. Setup Echo
window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY, 
    wsHost: import.meta.env.VITE_PUSHER_HOST,
    wsPort: import.meta.env.VITE_PUSHER_PORT ?? 6001,
    wssPort: import.meta.env.VITE_PUSHER_PORT ?? 6001,
    forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
    disableStats: true,
    enabledTransports: ["ws", "wss"],
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
});

// 2. Activity Tracking Logic
const trackActivity = () => {
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            window.axios.post("/activity/online");
        }, 500);
    });

    // Real-time Presence
    window.Echo.join("presence-online")
        // .here((users) => console.log("Current online:", users))
        // .joining((user) => console.log("Joined:", user))
        // .leaving((user) => console.log("Left:", user));

    setInterval(() => {
        axios.post("/activity/ping").catch(() => {});
    }, 30000);

    window.addEventListener('beforeunload', () => {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content");
        const data = new FormData();
        data.append('reason', 'tab_closed');
        if (token) data.append('_token', token);

        navigator.sendBeacon('/activity/offline', data);
    });
};

trackActivity();