<template>
    <h6>
        <a href="#" @click="currentUser"> {{ user.username }} </a>
    </h6>
</template>

<script>

    export default {
        props: ['user'],
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