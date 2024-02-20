import './bootstrap';

import { createApp } from 'vue';
import Home from './components/Home.vue'
import ChatMessage from './components/ChatMessage.vue'


const app = createApp({
    components: {
        Home,
        ChatMessage,
    }
});



app.mount("#app");