<template>
    <div class="message-area">
        <Message />
    </div>
</template>

<script>
    import ChatMessages from './ChatMessages.vue';
    import Message from './Message.vue';

    export default {
        components : {
            ChatMessages,
            Message
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
