
<template>
    <div class="chat__message">
        {{ currentFilteredUser }}
        
        <div v-for="participant in chat.participants" 
            :key="participant.id" class="d-flex justify-content-end">
            <div class="message self my-3 col-md-4">
                {{ participant.chat }}
            </div>
        </div>


       
        <div class="d-flex justify-content-start">
            <div class="message income my-4 col-md-4">
            <strong class="user">Aung Aung</strong>
            <p class="body">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
        </div>
        </div>
    </div>
</template>

<script>


import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

        window.Pusher = Pusher;

        window.Echo = new Echo({
                        broadcaster: 'pusher',
                        key: import.meta.env.VITE_PUSHER_APP_KEY,
                        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
                        authEndpoint: 'http://127.0.0.1:8000/api/broadcasting/auth',
                        auth: {
                            headers: {
                                Authorization: 'Bearer 31|mv7fX7lVTK8hVkQOIEl9evCamgA9abAawRdNvm3ef1db626f'
                            }
                        },
                        forceTLS: false,
                        encrypted: true,
                        disableStats: true,
                        enabledTransports: ['ws', 'wss'],
                        csrfToken: document.head.querySelector('meta[name="csrf-token"]').content
                    });
                    

    export default {
         props: ['currentFilteredUser'],

        data() {
            return {
                chat : [],
            }
        },
        mounted() {
            let token = '31|mv7fX7lVTK8hVkQOIEl9evCamgA9abAawRdNvm3ef1db626f';

            axios.request({
                headers: {
                    Authorization: `Bearer ${token}`
                },
                method: "GET",
                url: `/api/chat/1`
                }).then(response => {
                    this.chat = response.data.data;
            });

            window.Echo.private('chat-1').listen('NewMessageSent', (e) => {
                this.messages.push(e.message);
               });
        },

        computed() {

        }
    }
</script>

<style>
    .user {
        font-weight: 800;
    }
    .body {
        margin-bottom: 0;
        white-space: pre-wrap;
    }
    .message {
        border-bottom: 1px solid #000000
    }
    .self {
        background-color: #f0f0f0;
        padding: 10px;
    }

    .income {
        background-color: blue;
        padding: 10px;
        color: white;
    }
</style>