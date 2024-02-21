<template>
    <div class="vh-100 d-flex align-items-center justify-content-center">
        <form action="" name="login_form" class="login-form" method="post">
        <h4>Login to Your Account</h4>
        <div class="form-group mt-5 mb-3">
            <input type="text" class="form-control" placeholder="email" aria-label="email" name="email" v-model="email" required autofocus>
        </div>
        <div class="form-group my-3 ">
            <input type="password" class="form-control" placeholder="Password" aria-label="Password" name="password" v-model="password" required>
        </div>
        <div class="text-center">
            <button @click="goToHome">Login</button>
        </div>
        </form>
    </div>
</template>


<script>
import { useRouter } from 'vue-router';

export default {
    data() {
        return {
            email: '',
            password: '',
        }
    },
    methods: {
        goToHome() {
        axios.request({
            method: "POST",
            url: 'http://127.0.0.1:8000/login', // Update this URL to the correct login endpoint
            headers: {
                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content // Use headers for CSRF token
            },
            data: {
                email: this.email,
                password: this.password,
                type: 'web'
            },
        }).then(response => {
            console.log(response);
        }).catch(error => {
            console.error(error);
        });
    }

    }
}
</script>
