/**
 *  Browserify Entrypoint
 */

/**
 *  Vue Setup
 */
import Vue from 'vue'
Vue.use(require('vue-resource'));

import Alert from './components/Alert.vue';
import Login from './components/Login.vue';

// Configure debug mode
Vue.config.debug = true;

/**
 *  Instantiate our app
 */
new Vue({
  el: '#app',

  data: {
    'title': 'Issue Packs',
    'user': {}
  },

  // Include custom components
  components: { Login, Alert },
  ready () {
    console.log('ready to go');
  }
});
