<template>
<div id="login" class="animate form">
  <section class="login_content">
    <form>
      <h1>Issue Packs</h1>
      <div>
        <a class="btn btn-default" v-on:click="login()">Log In</a>
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

          this.$http.post('/api/login', {profile: profile})
            .then(
              function (response) {
                console.log(response);
                this.$dispatch('go', '/dashboard');
                this.$dispatch('login', profile);
              }.bind(this),
              function (error) {
                console.log(error);
                this.$dispatch('logout');
              }.bind(this));


        }
      });
    }
  }
}
</script>
