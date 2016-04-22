<template>
  <button class="btn btn-primary btn-lg" v-on:click="login()">Log in with Github</button>
</template>

<script>
  export default  {
    methods: {
      login: function () {
        console.log('logging in');
      }
    },
    created () {
      console.log('button created');
    }
  };
</script>

