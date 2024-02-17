<template>
  <div id="app">
    <post-list></post-list>
  </div>
</template><script>
import PostList from './components/PostList.vue';export default {
  components: {
    'post-list': PostList,
  },
};
</script>