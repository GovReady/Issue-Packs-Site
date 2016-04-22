<template>
  <button class="btn btn-primary btn-lg" v-on:click="login()">Log in with Github</button>
</template>

<script>
  export default  {
    methods: {
      login: function () {
        var message = { message: 'Some message', type: 'success' };

        this.$dispatch('new-alert', message);
      }
    }
  };
</script>

