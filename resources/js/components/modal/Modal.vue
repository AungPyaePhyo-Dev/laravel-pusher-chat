<template>
    <div class="vue-modal" v-show="open" v-if="filteredUser">
        <h1>Hello</h1>
    </div>
</template>

<script>

export default {
    props: {
        open: {
            type: Boolean,
            required: true
        },
        image: {
            type: String,
            required: true
        },
        filteredUser: {
            type: Object,
            required: true
        }
    }
}
</script>

<style scoped>

*,
::before,
::after {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.vue-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  /* height: 100%; */
  overflow-x: hidden;
  overflow-y: auto;
  background-color: rgba(0, 0, 0, 0.4);
  z-index: 1;
}

.vue-modal-inner {
  width: 400px;
  float: right;
  margin-right: 2rem;
  margin-top: 2rem;
}

.vue-modal-content {
  position: relative;
  background-color: #fff;
  border: 1px solid rgba(0, 0, 0, 0.3);
  background-clip: padding-box;
  border-radius: 0.3rem;
  padding: 1rem;
}

.image-detail {
    border-radius: 50%;
}

</style>
