import './bootstrap';

import { createApp } from 'vue';
import Home from './components/Home.vue'
import ChatMessage from './components/ChatMessage.vue'


import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
                broadcaster: 'pusher',
                key: import.meta.env.VITE_PUSHER_APP_KEY,
                cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
                authEndpoint: 'http://127.0.0.1:8000/api/broadcasting/auth',
                // bearerToken: 'Bearer 4|WgDtjOCG7iiYaNO70bxNI9qhwWZCCAJTFxgrKH6Fb4f1d39d',
                auth: {
                    headers: {
                        Authorization: 'Bearer 4|WgDtjOCG7iiYaNO70bxNI9qhwWZCCAJTFxgrKH6Fb4f1d39d'
                    }
                },
                forceTLS: false,
                encrypted: true,
                disableStats: true,
                enabledTransports: ['ws', 'wss'],
                csrfToken: document.head.querySelector('meta[name="csrf-token"]').content
            });


const app = createApp({
    components: {
        Home,
        ChatMessage,
    }
});



app.mount("#app");