/**
 *  Browserify Entrypoint
 */

/**
 *  Vue Setup
 */
import Vue from 'vue'
Vue.use(require('vue-resource'));

import Alerts from './components/Alerts.vue';
import Login from './components/Login.vue';

// Configure debug mode
Vue.config.debug = true;

/**
 *  Instantiate our app
 */
new Vue({
  el: '#app',

  events: {
    'new-alert': function (alert) {
      this.alerts.push(alert);
    }
  },

  data: {
    title: 'Issue Packs',
    user: {},
    alerts: []
  },

  // Include custom components
  components: { Login, Alerts },

  methods: {
    login () {
      var self = this;
      var lock = new Auth0Lock('fmbqeYWZ7UU4PRC19cMND5MmghK0pVzA', 'govready.auth0.com');

      lock.show((err, profile, token) => {
        if(err) {
          // Handle the error
          console.log(err)
        } else {
          // Set the token and user profile in local storage
          localStorage.setItem('profile', JSON.stringify(profile));
          localStorage.setItem('id_token', token);
          self.authenticated = true;
        }
      });
    },
    logout () {
      var self = this;
      localStorage.removeItem('id_token');
      localStorage.removeItem('profile');
      self.authenticated = false;
    },
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
  }
});
