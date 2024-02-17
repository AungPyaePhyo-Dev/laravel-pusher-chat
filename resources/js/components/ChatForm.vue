<template>
    <div>
        <form class="form">
            <textarea 
                cols="25"
                rows="5"
                class="form-input">
            </textarea>
            <button class="button">Send</button>
        </form>
    </div>
</template>

<script>
    export default {
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