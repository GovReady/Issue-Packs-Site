<template>
<div id="login" class="animate form">
  <section class="login_content">
    <form>
      <h1>Issue Packs</h1>
      <div>
        <a class="btn btn-default" v-on:click="login()"><i class="fa fa-github"></i> Log In With Github</a>
      </div>
      <!-- <div class="clearfix"></div> -->
      <div class="separator">
        <div class="clearfix"></div>
      </div>
    </form>
    <!-- form -->
  </section>
  <!-- content -->
</div>
  <!-- <button class="btn btn-primary btn-lg" v-on:click="login()">Log in with Github</button> -->
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
          var profile = JSON.stringify(profile);

          // Set the token and user profile in local storage
          localStorage.setItem('profile', profile);
          localStorage.setItem('id_token', token);
          self.authenticated = true;

          console.log(localStorage.getItem('profile'));
          this.$dispatch('new-alert', {
            message: 'Login Successful.  Profile logged to console.',
            type: 'success'
          });

          this.$dispatch('go', '/dashboard');
          this.$dispatch('login', profile);
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
