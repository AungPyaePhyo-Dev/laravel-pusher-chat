<template>
        <div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                  <h4>Chats</h4>
                  <h6>{{ currentFilteredUser.username }}</h6>
                </div>
                <div class="card-body">
                   <ChatMessage :currentFilteredUser="currentFilteredUser" />
                   <ChatForm />
                </div>
            </div>
        </div>
        <div class="col-md-4">
          <div class="card">
              <div class="card-header">Users</div>
              <div class="card-body">
                  <div class="users">
                      <div v-for="user in users" :key="user.id">
                          <User :user="user" @currentUser="currentUser" />
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

export default {

  components: {
    ChatMessage,
    ChatForm,
    User
  },
  data() {
            return {
                users : [],
                currentFilteredUser: '',
            }
        },
  methods: {
    currentUser(id){

      let currentUser = this.users.find(user => {
          return user.id == id;
      });
      this.currentFilteredUser = currentUser;

    }
  },
  mounted() {
      let token = '31|mv7fX7lVTK8hVkQOIEl9evCamgA9abAawRdNvm3ef1db626f';

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
