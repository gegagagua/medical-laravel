<template>
  <div id="app">
    <router-view />
    <Toast />
  </div>
</template>

<script>
import Toast from './components/ui/Toast.vue';

export default {
  name: 'App',
  components: {
    Toast
  }
};
</script>

