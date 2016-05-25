/**
 *  Browserify Entrypoint
 */

/**
 *  Vue Setup
 */
import Vue from 'vue';
import VueAsyncData from 'vue-async-data';
import VueResource from 'vue-resource';
import VueRouter from 'vue-router';

Vue.use(VueAsyncData);
Vue.use(VueResource);
Vue.use(VueRouter);

import App from './components/App.vue';
import HomeView from './components/HomeView.vue';
import DashboardView from './components/DashboardView.vue';
import ReposView from './components/ReposView.vue';
import Alerts from './components/Alerts.vue';

// Configure debug mode
Vue.config.debug = true;

//Register global components
Vue.component('alerts', Alerts);

export var router = new VueRouter({
  history: true,
});

export var lock = new Auth0Lock('pqbUxfLwW1UImYUUIe2qQbwCmByq41za', 'govready.auth0.com');

/**
 *  Set up application routes
 */
router.map({
  '/': {
    component: HomeView
  },
  '/dashboard': {
    component: DashboardView,
    auth: true
  }
});

/**
 *  Return whether a user is logged in
 */
function auth() {
  if(localStorage.getItem('id_token')) {
    return true;
  } else {
    return false;
  }
}

/**
 *  Redirect routes requiring authentication when user not logged in
 */
router.beforeEach(function (transition) {
  if(transition.to.auth && !auth()) {
    transition.redirect('/');
  } else {
    transition.next();
  }
});

/**
 *  Redirect 404s to the dashboard
 */
router.redirect({
  '*': '/dashboard'
});

router.start(App, '#app');
