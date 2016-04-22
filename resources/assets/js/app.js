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
  components: { Login, Alerts }
});
