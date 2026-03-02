import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    // key: "3oLtjog7IJ5j4tMZJbf6naFP8Aq4KBjl0y97C2BqHnA=",
    key: process.env.MIX_PUSHER_APP_KEY,
    // key: "3375bca1f75eb88b354a",
    // wsHost: "127.0.0.1",
    wsHost: process.env.MIX_PUSHER_APP_HOST ?? window.location.hostname,
    wsPort: process.env.MIX_PUSHER_APP_PORT,
    scheme: "http",
    // forceTLS: true,
    forceTLS: false,
    disableStats: true,
    enabledTransports: ["ws"],
    // enabledTransports: ["ws", "wss"],
    cluster: "mt1",
});
