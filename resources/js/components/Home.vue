<template>
    <div class="container-fluid">
        <div class="row justify-content-center ps-5">
            <div class="col-lg-2 p-0 vh-100 py-3" style="background-color: #d9dcdf;">
                <div class="mx-3">
                    <h5 class="">Chats</h5>
                    <input type="text" placeholder="Search users" v-model="input" class="w-100 search-input p-2 my-3">
                    <div class="d-flex overflow-auto position-relative">
                        <div v-for="user in filteredUsers" :key="user.id" class="mx-2">
                            <User :user="user" @currentUser="currentUser" />
                        </div>
                    </div>
                    <div class="my-4 scrollable">
                        <h6 class="my-4">Recent</h6>
                        <div class="" v-for="recent_chat in sortedData" :key="recent_chat.id">
                            <div class="d-flex align-items-center my-3" :class="{ active : currentFilteredUser.id == getParticipantUser(recent_chat.participants).id }"  @click="currentUser(getParticipantUser(recent_chat.participants).id)" style="cursor:pointer;">
                                    <div>
                                    <img style="border-radius: 50%; width:50px;" :src="getImage(getParticipantUser(recent_chat.participants).id)" alt="">
                                </div>
                                <div class="w-100 ms-2">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <span :class="recent_chat.isNewMessage ? 'fw-bold' : '' ">{{ getParticipantUser(recent_chat.participants).username }}</span>
                                            <p :class="recent_chat.isNewMessage ? 'fw-bold' : '' ">{{ getLastMessage(recent_chat) }}</p>
                                        </div>
                                        <div>
                                            <span :class="recent_chat.isNewMessage ? 'fw-bold' : '' ">{{  getLastMessageTime(recent_chat.last_message.created_at) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-10 px-0" style="position:relative;">
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

                <div class="scrollable mx-3" id="scrollableDiv">
                    
                    <div class="" v-if="currentFilteredUser">
                        <div class="chat">
                                <div class="card-body" ref="hasScrolledToBottom" v-for="(chatGroup, date) in groupedChats" :key="date">
                                            <div class="text-center">
                                               {{ getDay(date) }}
                                            </div>
                                    <template v-for="chat in chatGroup" :key="chat.id" >
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
                        <div style="overflow:hidden; height: calc(100vh - 40vh);" class="align-items-center d-flex justify-content-center my-5">
                            <h5>Select a user to chat</h5>
                        </div>
                    </div>
                </div>
                <div class="d-flex mx-3 my-3" v-if="currentFilteredUser">
                    <input type="text" class="form-control me-3" v-model="message" v-on:keyup.enter="onEnter">
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
let image = '';
let input = '';
let recent_chats = [];
let yesterday = 'yesterday';
let day = "";
let isNewMessage = false;

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
            isNewMessage,
            recent_chats,
            image,
            input,
            yesterday,
            day
        }
  },
  methods: {
    onEnter() {
        this.sendMessage();
    },

    scrollBottom() {
        var scrollableDiv = document.getElementById("scrollableDiv");
        scrollableDiv.scrollTop = scrollableDiv.scrollHeight;
    },
    
    getDay(time) {
        const date = new Date(time);
        const currentDate = new Date();

        let yesterday = new Date(currentDate);
        yesterday.setDate(yesterday.getDate() - 1);

        const formattedDate = date.toISOString().slice(0, 10);
        const formattedCurrentDate = currentDate.toISOString().slice(0, 10);
        const formattedYesterdayDate = yesterday.toISOString().slice(0, 10);  

        if(formattedDate == formattedCurrentDate) {
            return "Today";
        }else if(formattedDate == formattedYesterdayDate) {
            return "yesterday";
        } else {
            return time;
        }
    },

    getLastMessage(recent_chat) {
        if(recent_chat.last_message.message.length > 16) {
            return recent_chat.last_message.message.substring(0, 16) + '...';    
        }
        return recent_chat.last_message.message.substring(0, 17);
    },

    getLastMessageTime(dateString)
    {
        const date = new Date(dateString);
        const currentDate = new Date();

        let yesterday = new Date(currentDate);
        yesterday.setDate(yesterday.getDate() - 1);

        const formattedDate = date.toISOString().slice(0, 10);
        const formattedCurrentDate = currentDate.toISOString().slice(0, 10);
        const formattedYesterdayDate = yesterday.toISOString().slice(0, 10);  

        if (formattedDate === formattedCurrentDate) {
            const hour = date.getHours();
            const minutes = date.getMinutes();
            const amPM = hour >= 12 ? 'PM' : 'AM';
            const formattedHour = hour % 12 || 12;
            return `${formattedHour}:${minutes < 10 ? '0' : ''}${minutes} ${amPM}`; // Padding with zero if minutes < 10
        }else if(formattedDate === formattedYesterdayDate) {
            return this.yesterday;
        }else {
            return formattedDate.slice(5, 10);
        }
    },

    getHourAndMinutes(dateString) {
        const date = new Date(dateString);
        const currentDate = new Date();
        
        const hour = date.getHours();
        const minutes = date.getMinutes();
        const amPM = hour >= 12 ? 'PM' : 'AM';
        const formattedHour = hour % 12 || 12;
        return `${formattedHour}:${minutes < 10 ? '0' : ''}${minutes} ${amPM}`; // Padding with zero if minutes < 10
    },

    getParticipantUser(participants) {
        let logged_user_id = this.loggedInUser.id; 
        let participant = participants.find(function(participant) {
           return participant.user.id != logged_user_id;
        });
        return participant.user;
    },

    getImage(id) {
        return "https://randomuser.me/api/portraits/med/men/" + id + '.jpg';
    },

    currentUser(id){

      if(chat !== null) {
            window.Echo.leave(`chat-${chat.id}`);
      }

      this.scrollBottom();

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

                    const chatIndex = this.recent_chats.findIndex(recent_chat => recent_chat.id === chat.id);

                    if (chatIndex !== -1) {
                        this.recent_chats[chatIndex].isNewMessage = false;
                    }

                        
                    axios.request({
                            headers: {
                                Authorization: `Bearer ${this.token}`
                            },
                            method: "GET",
                            url: `/api/chat-message?chat_id=${chat.id}&page=1&participant_id=${this.currentFilteredUser.id}`,
                            }).then(response => {
                                if(this.currentFilteredUser.id == id) {
                                    this.chats = response.data.data;
                                }
                        });

                        window.Echo.private(`chat-${chat.id}`).listen('NewMessageSent', (e) => {

                            if(currentUser.id) {
                                this.chats.push(e.message);
                            }

                            if(this.recent_chats.length !== 0) {
                                this.recent_chats.filter(recent_chat => {
                                    if(recent_chat.id === chat.id) {
                                        recent_chat.last_message = e.message;
                                    }
                                });
                            }

                        });         
                }else {
                    axios.request({
                        headers: {
                            Authorization: `Bearer ${this.token}`
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

    // send message when button click (send) and button disabled till 2 seconds
    sendMessage() {
        this.buttonDisabled = true;

        axios.request({
            headers: {
                Authorization: `Bearer ${this.token}`
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
                    this.scrollBottom();
                }, 2000);    
        });
    },

    getDateFromTimestamp(timestamp) {
      const dateObj = new Date(timestamp);
      const year = dateObj.getFullYear();
      const month = String(dateObj.getMonth() + 1).padStart(2, '0');
      const day = String(dateObj.getDate()).padStart(2, '0');
      return `${year}-${month}-${day}`;
    },

  },

  computed: {
    // to filter user when typed a letter in search box
    filteredUsers() {
        if(this.input) {
            return this.users.filter(user => {
                return user.username.toLocaleLowerCase().includes(this.input.toLocaleLowerCase());
            });
        } else {
            return this.users;
        }
    },

    // order chats with last message ( created_at )
    sortedData() {
      return this.recent_chats.slice().sort((first, second) => {
        const firstDate = new Date(first.last_message.created_at);
        const secondDate = new Date(second.last_message.created_at);
        return secondDate - firstDate;
      });
    },

    // chat group by day eg. today, yesterday and date(2024-03-03)
    groupedChats() {
      const grouped = {};
      this.chats.forEach(chat => {
        const date = this.getDateFromTimestamp(chat.created_at);
        if (!grouped[date]) {
          grouped[date] = [];
        }
        grouped[date].push(chat);
      });
      return grouped;
    }

  },

  mounted() {

    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        authEndpoint: 'http://127.0.0.1:8000/api/broadcasting/auth',
        auth: {
            headers: {
                Authorization: `Bearer ${this.token}`
            }
        },
        forceTLS: false,
        encrypted: true,    
        disableStats: true,
        enabledTransports: ['ws', 'wss'],
        csrfToken: document.head.querySelector('meta[name="csrf-token"]').content
    });

    // get all users
      axios.request({
          headers: {
              Authorization: `Bearer ${this.token}`
          },
          method: "GET",
          url: `/api/user`
          }).then(response => {
              this.users = response.data.data;
      });

    // get all chat to show in left sidemenu 
      axios.request({
          headers: {
              Authorization: `Bearer ${this.token}`
          },
          method: "GET",
          url: `/api/chat`
          }).then(response => {
              this.recent_chats = response.data.data;   
              window.Echo = new Echo({
                broadcaster: 'pusher',
                key: import.meta.env.VITE_PUSHER_APP_KEY,
                cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
                authEndpoint: 'http://127.0.0.1:8000/api/broadcasting/auth',
                auth: {
                    headers: {
                        Authorization: `Bearer ${this.token}`
                    }
                },
                forceTLS: false,
                encrypted: true,
                disableStats: true,
                enabledTransports: ['ws', 'wss'],
                csrfToken: document.head.querySelector('meta[name="csrf-token"]').content
            });

            axios.request({
            headers: {
                Authorization: `Bearer ${this.token}`
            },
            method: "GET",
            url: `/api/chats`
            }).then(response => {
                response.data.forEach(chatId => {
                    window.Echo.private(`chat-${chatId}`)
                        .listen('NewMessageSent', (e) => {
                            const chatIndex = this.recent_chats.findIndex(chat => chat.id === chatId);
                            if (chatIndex !== -1) {
                                this.recent_chats[chatIndex].last_message = e.message;
                                this.recent_chats[chatIndex].isNewMessage = true; 
                            }
                        });
                });
            }); 
      });
  }

}
</script>

<style scoped>
    .active {
        background-color: #bbb;
        padding:0px 10px;
        border-radius: 10px;
        transition: 0.5s;
    }
</style>
