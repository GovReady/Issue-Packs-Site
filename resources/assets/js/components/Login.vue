<template>
<div class="animate form">
  <section class="login_content">
    <div class="login_intro">
      <h1>Get Started!</h1>
    </div>
    <div class="login_features">
      <ul>
        <li>Use your project management software</li>
        <li>Bake compliance into your workflow</li>
        <li>Share compliance workflows with other agencies</li>
      </ul>
    </div>
    <div class="login_button">
      <button class="btn btn-success" v-on:click="login()">Log In</button>
    </div>
    <!-- form -->
  </section>
  <!-- content -->
  <div class="clearfix"></div>
</div>
</template>

<script>
import {lock} from '../app';

export default {
  methods: {
    login () {
      var self = this;

      lock.show(function (err, profile, token) {
        if(err) {
          // Handle the error
          console.log(err)
        } else {
          var profileObject = profile;

          this.$http.post('/api/login', {profile: profile})
            .then(
              function (response) {
                profileObject.id = response.data.id;

                // Set the token and user profile in local storage
                var profile = JSON.stringify(profileObject);
                localStorage.setItem('profile', profile);
                localStorage.setItem('id_token', token);

                this.$dispatch('go', '/dashboard');
                this.$dispatch('login', profile, { id: response.data.id });
              }.bind(this),
              function (error) {
                console.error(error);
                this.$dispatch('logout');
              }.bind(this));


        }
      }.bind(this));
    }
  }
}
</script>
