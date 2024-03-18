

<template>
	<div class="chat">
        {{ chats }}
            <div class="card-body" ref="hasScrolledToBottom" v-if="chat != null">
                <template v-for="chat in chats" :key="chat.id">
                    <div class="message message-receive" v-if="chat.user.id != loggedInUser.id">
                        <p>
                            <strong class="primary-font">
                               {{ chat.user.username }} :
                            </strong>
                            {{ chat.message }}
                        </p>
                    </div>
                    <div class="message message-send" v-else>
                        <p>
                            <strong class="primary-font">
                               You ( {{ chat.user.username }} ):
                            </strong>
                            {{ chat.message }}
                        </p>
                    </div>
                </template>
            </div>

            <div>
                <textarea 
                    cols="25"
                    rows="5"
                    class="form-input" v-model="message">
                </textarea>
                <button class="button" @click="sendMessage">Send</button>
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
                        Authorization: 'Bearer 4|zeOdKyuUHc1cWeJ7RuoRMQNj5fSn1T2IwDIqezpO42cc47b1'
                    }
                },
                forceTLS: false,
                encrypted: true,
                disableStats: true,
                enabledTransports: ['ws', 'wss'],
                csrfToken: document.head.querySelector('meta[name="csrf-token"]').content
            });

                    

    export default {
         props: ['currentFilteredUser', 'chats'],
        data() {
            let chat_id = null;

            if(this.chat) {
                chat_id = this.chat.chat_id;
            }

            let loggedInUser = {
                "id": 2,
                "username": "user2",
                "email": "user2@email.com",
                "created_at": "2024-02-20T04:06:12.000000Z",
                "updated_at": "2024-02-20T04:06:12.000000Z"
            };

            return {
                chats : [],
                loggedInUser,
            }
        },
        methods: {
            sendMessage() {
                let token = '4|zeOdKyuUHc1cWeJ7RuoRMQNj5fSn1T2IwDIqezpO42cc47b1';
                

                axios.request({
                    headers: {
                        Authorization: `Bearer ${token}`
                    },
                    method: "POST",
                    url: `/api/chat-message`,
                    data: {
                        message: this.message,
                        chat_id: 1
                    },
                    }).then(response => {
                        this.chats.push(response.data.data);
                        this.message = '';
                });
            }
        },
        mounted() {
            let token = '4|zeOdKyuUHc1cWeJ7RuoRMQNj5fSn1T2IwDIqezpO42cc47b1';

            axios.request({
                headers: {
                    Authorization: `Bearer ${token}`
                },
                method: "GET",
                url: `/api/chat-message?${this.chat.chat_id}=1&page=1`,
                }).then(response => {
                    this.chats = response.data.data;
            });

            window.Echo.private(`chat-${this.chat.chat_id}`).listen('NewMessageSent', (e) => {
                this.chats.push(e.message);
               });
        }
    }

</script>


