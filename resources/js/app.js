import './bootstrap';

import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import Home from './components/Home.vue';
import ChatMessage from './components/ChatMessage.vue';
import Login from './components/Login.vue';

const routes = [
    {path: '/', component: Home},
    {path: '/login', component: Login}
]


const router = createRouter({
    history: createWebHistory(),
    routes
})

const app = createApp({
    components: {
        Home,
        ChatMessage,
        Login
    }
});


app.use(router);

app.mount("#app");