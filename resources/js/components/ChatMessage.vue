<template>
    <div class="message-area">
        <Message :currentFilteredUser="currentFilteredUser" :chats="chats"/>
    </div>
</template>

<script>
    import ChatMessages from './ChatMessages.vue';
    import Message from './Message.vue';

    export default {
        props : ['currentFilteredUser', 'chats'],
        
        components : {
            ChatMessages,
            Message
        },
        data() {
            return {
                
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>

<style>
    .message-area {
        height: 400px;
        max-height: 400px;
        overflow-y: scroll;
        padding: 15px;
        border-bottom: 1px solid #eee;
    }
</style>
