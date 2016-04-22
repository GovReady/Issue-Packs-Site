import Vue from 'vue'

Vue.component('login', {
  template: '<button class="btn btn-primary btn-lg" v-on:click="login()">Log in with Github',
  methods: {
    login: function () {
      console.log('logging in');
    }
  }
});
