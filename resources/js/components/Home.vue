<template>
    <div class="container-fluid">
        <div class="row justify-content-center ps-5">
            <div class="col-md-2 p-0 vh-100 py-3" style="background-color: #d9dcdf;">
                <div class="mx-3">
                    <h5 class="">Chats</h5>
                    <input type="text" placeholder="Search users"  class="w-100 search-input p-2 my-3">
                    <div class="d-flex overflow-auto position-relative">
                        <div v-for="user in users" :key="user.id" class="mx-2">
                            <User :user="user" @currentUser="currentUser" />
                        </div>
                    </div>
                    <div class="my-4">
                        <h6 class="my-4">Recent</h6>
                        <div class="d-flex align-items-center my-3">
                            <div>
                                <img style="border-radius: 50%; width:50px;" :src="image" alt="">
                            </div>
                            <div class="w-100 ms-2">
                                <div class="d-flex justify-content-between">
                                <div>
                                    <span class="fw-bold">Myo Htet Oo</span>
                                    <p>hello</p>
                                </div>
                                <div>
                                    <span>05 min</span>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center p-1" style="cursor:pointer; background-color: #bbb; border-radius:10px;">
                            <div>
                                <img style="border-radius: 50%; width:50px;" :src="image" alt="">
                            </div>
                            <div class="w-100 ms-2">
                                <div class="d-flex justify-content-between">
                                <div>
                                    <span class="fw-bold">Tun Saw Thant</span>
                                    <p>hello</p>
                                </div>
                                <div>
                                    <span>05 min</span>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-10 px-0" style="position:relative;">
                    <div class="py-3 mx-3 d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                            <img style="border-radius: 50%; width:50px;" :src="image" alt="">
                            <h6 class="mx-3">{{ currentFilteredUser.username }}</h6>
                        </div>
                        <div class="me-4 my-auto">
                            <h5 style="cursor:pointer;"><span>...</span></h5>
                        </div>
                    </div>
                    <hr>
                <div class="scrollable mx-3">
                    
                    <div class="" v-if="currentFilteredUser">
                        <div class="chat">
                                <div class="card-body" ref="hasScrolledToBottom">
                                    <template v-for="chat in chats" :key="chat.id">
                                        <div class="message message-receive" v-if="chat.user.id != loggedInUser.id">
                                            <p>
                                                <strong class="primary-font">
                                                {{ chat.user.username }} :
                                                </strong>
                                                {{ chat.message }} <br>
                                                <span>{{ getHourAndMinutes(chat.created_at) }}</span>
                                            </p>
                                        </div>
                                        <div class="message message-send" v-if="chat.user.id == loggedInUser.id">
                                            <div>
                                                <p>
                                                <strong class="primary-font">
                                                You ( {{ chat.user.username }} ):
                                                </strong>
                                                {{ chat.message }}
                                                <br>
                                                <span>{{ getHourAndMinutes(chat.created_at) }}</span>
                                            </p> 
                                            
                                            </div>
                                        </div>
                                    </template>
                                </div>
                        </div>
                    </div>
                    <div v-else>
                        <div class="d-flex justify-content-center my-5">
                            Select a user to chat
                        </div>
                    </div>
                </div>
                <div class="d-flex mx-3 my-3">
                    <input type="text" class="form-control me-3" v-model="message">
                    <!-- <textarea 
                        cols="25"
                        rows="1"
                        class="form-input" v-model="message">
                    </textarea> -->
                    <button class="button" :disabled="buttonDisabled" @click="sendMessage">Send</button>
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


let chat = null;
let currentChatId = null;
let image = '';

export default {
  props: ['user', 'token'],

  components: {
    ChatMessage,
    ChatForm,
    User    
  },

  data() {
    let loggedInUser = this.user;
    let token = this.token;
            
        return {
            users : [],
            currentFilteredUser: '',
            chats: [],
            loggedInUser,
            message: '',
            buttonDisabled: false,
            token
        }
  },
  methods: {

    getHourAndMinutes(dateString) {
      const date = new Date(dateString);
      const hour = date.getHours();
      const minutes = date.getMinutes();
      const amPM = hour >= 12 ? 'PM' : 'AM';
      const formattedHour = hour % 12 || 12;
      return `${formattedHour}:${minutes < 10 ? '0' : ''}${minutes} ${amPM}`; // Padding with zero if minutes < 10
    },

    currentUser(id){
      if(chat !== null) {
            window.Echo.leave(`chat-${chat.id}`);
      }

      let currentUser = this.users.find(user => {
          return user.id == id;
      });

      this.currentFilteredUser = currentUser;
       if(this.currentFilteredUser != undefined) {
            this.image = "https://randomuser.me/api/portraits/med/men/" + this.currentFilteredUser.id + '.jpg';
        }

      // related-chat
        axios.request({
            headers: {
                Authorization: `Bearer ${this.token}`
            },
            method: "GET",
            url: `/api/related-chat?current_user_id=${this.currentFilteredUser.id}&loggedin_user_id=${this.loggedInUser.id}`,
            }).then(response => {
                chat = response.data;
                if(chat.id) {

                    let token = this.token;

                        axios.request({
                                headers: {
                                    Authorization: `Bearer ${token}`
                                },
                                method: "GET",
                                url: `/api/chat-message?chat_id=${chat.id}&page=1&participant_id=${this.currentFilteredUser.id}`,
                                }).then(response => {
                                    if(this.currentFilteredUser.id == id) {
                                        this.chats = response.data.data;
                                    }
                            });

                            window.Echo = new Echo({
                                broadcaster: 'pusher',
                                key: import.meta.env.VITE_PUSHER_APP_KEY,
                                cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
                                authEndpoint: 'http://127.0.0.1:8000/api/broadcasting/auth',
                                auth: {
                                    headers: {
                                        Authorization: `Bearer ${token}`
                                    }
                                },
                                forceTLS: false,
                                encrypted: true,
                                disableStats: true,
                                enabledTransports: ['ws', 'wss'],
                                csrfToken: document.head.querySelector('meta[name="csrf-token"]').content
                            });

                            console.log(window.Echo)

                            window.Echo.private(`chat-${chat.id}`).listen('NewMessageSent', (e) => {
                                console.log(e);
                                if(currentUser.id) {
                                    this.chats.push(e.message);
                                }
                            });
                            
                }else {
                        let token = this.token;
                        axios.request({
                            headers: {
                                Authorization: `Bearer ${token}`
                            },
                            method: "POST",
                            url: `/api/chat`,
                            data: {
                                user_id: currentUser.id,
                                logged_user_id: this.loggedInUser.id
                            },
                            }).then(response => {
                                chat = response.data.data.participants.find(participant => {
                                    return participant.user_id == currentUser.id;
                                });
                        });
                }
        });
    },

    sendMessage() {
        let token = this.token;

        this.buttonDisabled = true;

        axios.request({
            headers: {
                Authorization: `Bearer ${token}`
            },
            method: "POST",
            url: `/api/chat-message`,
            data: {
                message: this.message,
                chat_id: chat.id
            },
            }).then(response => {
                this.message = ''; 
                setTimeout(() => {
                    this.buttonDisabled = false;
                }, 2000);    
        });

    },

  },
  mounted() {
      let token = this.token;

      axios.request({
          headers: {
              Authorization: `Bearer ${token}`
          },
          method: "GET",
          url: `/api/user`
          }).then(response => {
              this.users = response.data.data;
      });
  }

}
</script>
