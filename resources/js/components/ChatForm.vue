<template>
    <div>
        <textarea 
            cols="25"
            rows="5"
            class="form-input" v-model="message">
        </textarea>
        <button class="button" @click="sendMessage">Send</button>
    </div>
</template>

<script>
    export default {

        data() {
            return {
            message: ''
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
                        
                });
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>

<style>
    .form {
        padding: 8px;
    }
    .form-input {
        width: 100%;
        border: 1px solid #d3e0e9;
        padding: 5px 10px;
        outline: none;
    }
    .notice {
        color: #aaa
    }

    .button {
        float:right;
        margin-top: 10px;
        padding: 5px 20px;
        background: blue;
        color: white;
        border: 2px solid blue;
        border-radius: 10px;
    }
    
</style>