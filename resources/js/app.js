import './bootstrap';

import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import Home from './components/Home.vue';

const routes = [
    {path: '/home', component: Home}
]


const router = createRouter({
    history: createWebHistory(),
    routes
})

const app = createApp({
    components: {
        Home
    }
});


app.use(router);

app.mount("#app");