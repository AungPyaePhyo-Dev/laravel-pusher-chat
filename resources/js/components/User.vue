<template>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Users</div>
            <div class="card-body">
                <div class="users">
                    <h6>
                        <a href="#">Aung Aung </a>
                    </h6>
                    <h6>
                        <a href="#">Mg Mg </a>
                    </h6>
                </div>
            </div>
        </div>
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
    .users {
        background-color: #fff;
        border-radius: 3px;
    }

    ul {
        list-style-type: none;
    }
</style>