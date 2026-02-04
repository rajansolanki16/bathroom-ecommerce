import axios from "axios";
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content");
if (token) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token;
}

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: "app-key", 
    wsHost: "127.0.0.1",
    wsPort: 6001,
    forceTLS: false,
    disableStats: true,
    enabledTransports: ["ws", "wss"],
    cluster: "mt1",
});

if (window.Echo) {
    window.Echo.join("presence-online")
        .here((users) => {
            console.log("👥 Online users:", users);
            window.axios.post("/activity/online").catch(err => console.error("Online Error:", err));
        })
        .joining((user) => {
            console.log("➕ Another user joined:", user);
       })
        .leaving((user) => {
            console.log("➖ User left:", user);
        })
        .error((error) => {
            console.error("❌ Echo Auth Error:", error);
        });
}

setInterval(() => {
    window.axios.post("/activity/ping").catch(() => {});
}, 30000);