<template>
        <div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card scrollable">
                <div class="card-header">
                  <h4>Chats</h4>
                  <h6>{{ currentFilteredUser.username }}</h6>
                </div>
                <div class="card-body" v-if="currentFilteredUser">
                    <div class="chat">
                            <div class="card-body" ref="hasScrolledToBottom">
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
                                <button class="button" :disabled="buttonDisabled" @click="sendMessage">Send</button>
                            </div>
                    </div>
                </div>
                <div v-else>
                    <div class="d-flex justify-content-center my-5">
                        Select a user to chat
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
          <div class="card">
              <div class="card-header">Shops</div>
              <div class="card-body">
                  <div class="users">
                      <div v-for="shop in shops" :key="shop.id">
                          <User :shop="shop" :currentUser="currentUser" />
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

let chat = null;

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
                };
                
            return {
                shops : [],
                currentFilteredUser: '',
                chats: [],
                loggedInUser,
                message: '',
                buttonDisabled: false
            }
        },
  methods: {

    currentUser(id){

        console.log(id)


    //   let currentUser = this.users.find(user => {
    //       return user.id == id;
    //   });
    //   this.currentFilteredUser = currentUser;
    
    //   if(currentUser.chat_participants.length == 0) {
    //         let token = '4|zeOdKyuUHc1cWeJ7RuoRMQNj5fSn1T2IwDIqezpO42cc47b1';
    //         axios.request({
    //             headers: {
    //                 Authorization: `Bearer ${token}`
    //             },
    //             method: "POST",
    //             url: `/api/chat`,
    //             data: {
    //                 user_id: currentUser.id
    //             },
    //             }).then(response => {
    //                 chat = response.data.data.participants.find(participant => {
    //                     return participant.user_id == currentUser.id;
    //                 });
    //         });
    //     } else {
    //         chat = currentUser.chat_participants.find(participant => {
    //                 return participant.user_id == currentUser.id;
    //         })

    //         let token = '4|zeOdKyuUHc1cWeJ7RuoRMQNj5fSn1T2IwDIqezpO42cc47b1';

    //         axios.request({
    //                 headers: {
    //                     Authorization: `Bearer ${token}`
    //                 },
    //                 method: "GET",
    //                 url: `/api/chat-message?chat_id=${chat.chat_id}&page=1`,
    //                 }).then(response => {
    //                     this.chats = response.data.data;
    //             });

    //             window.Echo.private('chat-1').listen('NewMessageSent', (e) => {
    //                 this.chats.push(e.message);
    //                 });
    //     }
    },

    sendMessage() {
        let token = '4|zeOdKyuUHc1cWeJ7RuoRMQNj5fSn1T2IwDIqezpO42cc47b1';

        this.buttonDisabled = true;

        axios.request({
            headers: {
                Authorization: `Bearer ${token}`
            },
            method: "POST",
            url: `/api/chat-message`,
            data: {
                message: this.message,
                chat_id: chat.chat_id
            },
            }).then(response => {
                this.chats.push(response.data.data);
                this.message = ''; 
                setTimeout(() => {
                    this.buttonDisabled = false;
                }, 2000);    
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
              console.log(this.shops)
      });
  },

}
</script>
