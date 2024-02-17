
<template>
    <div class="chat__message">
        <div v-for="message in messages" 
            :key="message.id">
            <div class="message self my-3">
                    <strong class="user">You</strong>
                    <p class="body">
                        {{ message.message }}
                    </p>
                </div>
            <!-- <div v-for="single_message in message.messages" :key="single_message.id">
                <div class="message self my-3">
                    <strong class="user">You</strong>
                    <p class="body">
                        {{ single_message.message }}
                    </p>
                </div>
            </div> -->
        </div>
       
        <div class="message income my-4">
            <strong class="user">Aung Aung</strong>
            <p class="body">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam cumque quaerat rem quia veniam exercitationem, commodi numquam omnis! Non placeat perspiciatis nulla illum cumque ad natus asperiores fuga. Facere, dignissimos.</p>
        </div>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                messages : []
            }
        },
        mounted() {
            let token = '4|WgDtjOCG7iiYaNO70bxNI9qhwWZCCAJTFxgrKH6Fb4f1d39d';

            axios.request({
                headers: {
                    Authorization: `Bearer ${token}`
                },
                method: "GET",
                url: `/api/chat/1`
                }).then(response => {
                    this.messages = response.data.data;
            });

            window.Echo.private('chat-1').listen('NewMessageSent', (e) => {
                this.messages.push(e.message);
               });
        }
    }
</script>

<style>
    .user {
        font-weight: 800;
    }
    .body {
        margin-bottom: 0;
        white-space: pre-wrap;
    }
    .message {
        border-bottom: 1px solid #000000
    }
    .self {
        background-color: #f0f0f0;
        padding: 10px;
    }

    .income {
        background-color: blue;
        padding: 10px;
        color: white;
    }
</style>