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
import HomeView from './components/HomeView.vue';
import ReposView from './components/ReposView.vue';
import Alerts from './components/Alerts.vue';

// Configure debug mode
Vue.config.debug = true;

//Register global components
Vue.component('alerts', Alerts);

export var router = new VueRouter({
  history: true
});

export var lock = new Auth0Lock('fmbqeYWZ7UU4PRC19cMND5MmghK0pVzA', 'govready.auth0.com');

router.map({
  '/': {
    component: HomeView
  },
  '/repos': {
    component: ReposView
  }
});

router.redirect({
  '*': '/'
});

router.start(App, '#app');
