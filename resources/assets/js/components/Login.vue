<template>
  <button class="btn btn-primary btn-lg" v-on:click="login()">Log in with Github</button>
</template>

<script>
import {lock} from '../app';

export default {
  methods: {
    login () {
      var self = this;

      lock.show((err, profile, token) => {
        if(err) {
          // Handle the error
          console.log(err)
        } else {
          // Set the token and user profile in local storage
          localStorage.setItem('profile', JSON.stringify(profile));
          localStorage.setItem('id_token', token);
          self.authenticated = true;

          console.log(localStorage.getItem('profile'));
          this.$dispatch('new-alert', {message: 'Login Successful.  Profile logged to console.', type: 'success'});
        }
      });
    },
    logout () {
      var self = this;
      localStorage.removeItem('id_token');
      localStorage.removeItem('profile');
      self.authenticated = false;
    }
  }
}
</script>
