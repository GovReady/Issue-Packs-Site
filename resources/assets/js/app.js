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

Vue.transition('expand', {
  enter (el) {
    el.style.height = 'auto';
    var endHeight = getComputedStyle(el).height;
    //el.style.height = '0px';
    el.offsetHeight;
    el.style.height = endHeight;
  },
  afterEnter (el) {
    //el.style.height = 'auto';
  },
  beforeLeave (el) {
    //el.style.height = getComputedStyle(el).height;
    //el.offsetHeight;
    //el.style.height = '0px';
  }
});

export var router = new VueRouter({
  history: true,
});

export var lock = new Auth0Lock('pqbUxfLwW1UImYUUIe2qQbwCmByq41za', 'govready.auth0.com');

router.map({
  '/': {
    component: HomeView
  },
  '/dashboard': {
    component: DashboardView,
    data (transition) {
      //return
    }
  }
});

router.redirect({
  '*': '/'
});

router.start(App, '#app');
