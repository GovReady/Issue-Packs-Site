<template>
<div v-bind:class="{'nav-md': !navbarToggle, 'nav-sm': navbarToggle}">
  <alerts :alerts.sync="alerts"></alerts>
  <router-view></router-view>
</div>
</template>

<script>
import Alerts from './Alerts.vue';
import {router} from '../app';

export default {
  created () {
    this.profile = JSON.parse(localStorage.getItem('profile'));
  },

  events: {
    'new-alert': function (alert) {
      this.alerts.push(alert);
    },
    'go': function (path) {
      router.go(path);
    },
    'login': function (profile) {
      this.profile = profile;
      this.authenticated = true;
    },
    'logout': function () {
      localStorage.removeItem('id_token');
      localStorage.removeItem('profile');
      this.authenticated = false;

      this.$dispatch('go', '/');
    },
    'navbar-toggle': function (toggle) {
      this.navbarToggle = toggle;
    }
  },

  data() {
    return {
      alerts: [],
      navbarToggle: false
    }
  },

  // Include custom components
  components: { Alerts },

  methods: {
    getSecretThing () {
      var jwtHeader = { 'Authorization': 'Bearer ' + localStorage.getItem('id_token') };

      this.$http.get('http://localhost:3001/secured/ping',{}, {
        // Send the JWT as a header
        headers: jwtHeader
      }).then(
        //successfull callback
        (response) => {
          // Handle data returned
          console.log(response.data);
        },
        //error callback
        (err) => console.log(err));
    }
  },
  replace: false
}
</script>
