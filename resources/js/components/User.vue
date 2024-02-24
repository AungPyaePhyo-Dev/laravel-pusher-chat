<template>
    <h6 class="text-center">
        <a href="#" class="text-decoration-none" @click="currentUser"> 
            <img style="border-radius: 50%; width:50px;" :src="image" alt="">
            <div>
                {{ user.username.split(" ")[0] }}
            </div>
        </a>

    </h6>
</template>

<script>

    export default {
        props: ['user'],
        data() {
            let image = 'https://randomuser.me/api/portraits/med/men/' + this.user.id + '.jpg';

            return {
                image
            }
        },
        methods: {
            currentUser() {
                 this.$emit("currentUser", this.user.id)
            }
        }
    }
</script>

<style>
    .users {
        background-color: #fff;
        border-radius: 3px;
    }

    ul {
        list-style-type: none;
    }
</style>