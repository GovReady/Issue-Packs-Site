<template>
  <a href="#" data-toggle="tooltip" data-placement="top" title="Logout" v-on:click="logout()">
    <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
  </a>
</template>

<script>
import { router } from '../app';

export default {
  methods: {
    logout () {
      console.log('logging out');

      localStorage.removeItem('id_token');
      localStorage.removeItem('profile');

      this.$dispatch('go', '/');
      this.$dispatch('logout');
    }
  }
}
</script>
