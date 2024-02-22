<template>
        <div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card scrollable">
                <div class="card-header">
                  <h4>Chats</h4>
                  <h6>{{ currentFilteredUser.username }}</h6>
                </div>
                <div class="card-body">
                    <div class="chat">
                            <div class="card-body" ref="hasScrolledToBottom">
                                <template v-for="chat in messages" :key="chat.id">
                                    <div class="message message-receive" v-if="chat.shop_id != null" >
                                        <p>
                                            <strong class="primary-font">
                                            {{ chat.shop.name }} :
                                            </strong>
                                            {{ chat.message }}
                                        </p>
                                    </div>
                                    <div class="message message-send" v-else>
                                        <p>
                                            <strong class="primary-font">
                                            You ( {{ chat.admin.name }} ):
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
                                <button class="button" :disabled="buttonDisabled" @click="sendMessage">Send</button>
                            </div>
                    </div>
                </div>
                <!-- <div>
                    <div class="d-flex justify-content-center my-5">
                        Select a user to chat
                    </div>
                </div> -->
            </div>
        </div>
        <div class="col-md-4">
          <div class="card">
              <div class="card-header">Shops</div>
              <div class="card-body">
                  <div class="users">
                      <div v-for="shop in shops" :key="shop.id">
                          <User :shop="shop" @currentUser="currentUser" />
                      </div>
                  </div>
              </div>
          </div>
      </div>
        
    </div>
</div>
</template>

<script>

import ChatMessage from './ChatMessage.vue';
import ChatForm from './ChatForm.vue';
import User from './User.vue'



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
let messages = [];


export default {

  components: {
    ChatMessage,
    ChatForm,
    User
  },
  data() {

        let loggedInUser = {
                    "id": 2,
                    "username": "user2",
                    "email": "user2@email.com",
                    "created_at": "2024-02-20T04:06:12.000000Z",
                    "updated_at": "2024-02-20T04:06:12.000000Z"
                }
        let chat_messages = [];
                
            return {
                shops : [],
                currentFilteredUser: '',
                chat: '',
                loggedInUser,
                buttonDisabled: false,
                messages
            }
        },
  methods: {

    currentUser(shopId){

                 axios.request({
                    method: "GET",
                    url: `/api/chat-data?admin_id=1&shop_id=${shopId}`,
                    }).then(response => {
                        this.chat_messages = response.data.chat_messages;
                        this.messages = response.data.chat_messages;
                        this.chat = response.data;
                });
    },

    sendMessage() {
        axios.request({
            method: "POST",
            url: `/api/chat-message-create`,
            data: {
                message: this.message,
                chat_id: this.chat.id,
                admin_id: 1
            },
            }).then(response => {   
                this.messages.push(response.data);
                console.log(window.Echo.private('chat-message-1'));
                //  window.Echo.private('chat-message-1').listen('PrivateTest', (e) => {
                //         console.log(e.message);
                //         this.messages.push(e.message);
                //     });
        });

    },

  },
  mounted() {
      let token = '4|zeOdKyuUHc1cWeJ7RuoRMQNj5fSn1T2IwDIqezpO42cc47b1';

      axios.request({
          headers: {
              Authorization: `Bearer ${token}`
          },
          method: "GET",
          url: `/api/shops`
          }).then(response => {
              this.shops = response.data.data;
      });
  },

}
</script>
