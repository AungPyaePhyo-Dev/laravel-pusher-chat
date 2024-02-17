<template>
        <div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Chats</div>
                <div class="card-body">
                   <ChatMessage />
                   <ChatForm />
                </div>
            </div>
        </div>
        <User />
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
  }
}
</script>
