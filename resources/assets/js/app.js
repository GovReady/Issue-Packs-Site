/**
 *  Browserify Entrypoint
 */

/**
 *  Vue Setup
 */
import Vue from 'vue';
import VueResource from 'vue-resource';
import VueRouter from 'vue-router';

Vue.use(VueResource);
Vue.use(VueRouter);

import App from './components/App.vue';
//import Alerts from './components/Alerts.vue';
import HomeView from './components/HomeView.vue';

// Configure debug mode
Vue.config.debug = true;

export var router = new VueRouter({
  history: true
});

export var lock = new Auth0Lock('fmbqeYWZ7UU4PRC19cMND5MmghK0pVzA', 'govready.auth0.com');

router.map({
  '/': {
    component: HomeView
  }
});

router.redirect({
  '*': '/'
});

router.start(App, '#app');


