<template>
    <h6>
        <a href="#" @click="currentUser"> {{ shop.name }} </a>
    </h6>
</template>

<script>

    export default {
        props: ['shop'],
        methods: {
            currentUser() {
                 this.$emit("currentUser", this.shop.id)
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