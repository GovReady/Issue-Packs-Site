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
import VueValidator from 'vue-validator';

Vue.use(VueAsyncData);
Vue.use(VueResource);
Vue.use(VueRouter);
Vue.use(VueValidator);

import App from './components/App.vue';
import Connections from './components/Connections.vue';
import HomeView from './components/HomeView.vue';
import DashboardView from './components/DashboardView.vue';
import ReposView from './components/ReposView.vue';
import RepoDashboard from './components/RepoDashboard.vue';
import MyPacks from './components/MyPacks.vue';
import PackSearch from './components/PackSearch.vue';
import ProjectDashboard from './components/ProjectDashboard.vue';
import Alerts from './components/Alerts.vue';

import moment from 'moment';

// Configure debug mode
Vue.config.debug = true;

//Register global components
Vue.component('alerts', Alerts);

export var router = new VueRouter({
  history: true,
});

export var lock = new Auth0Lock('pqbUxfLwW1UImYUUIe2qQbwCmByq41za', 'govready.auth0.com');

Vue.http.interceptors.push({
  request: function (request) {
    request.headers['Authorization'] = 'Bearer ' + localStorage.getItem('id_token');
    request.headers['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');

    return request;
  }
});

Vue.filter('timeago', function (value) {
  var date = moment.utc(value);

  return date.fromNow();
});

/**
 *  Set up application routes
 */
router.map({
  '/': {
    component: HomeView
  },
  '/dashboard': {
    component: DashboardView,
    auth: true,
    title: 'Install Issue Packs',
    subRoutes: {
      '/connections': {
        name: 'connections',
        component: Connections,
        title: 'My Connections'
      },
      '/search-packs': {
        name: 'search-packs',
        component: PackSearch,
        title: 'Search Packs'
      },
      '/my-packs': {
        name: 'my-packs',
        component: MyPacks,
        title: 'My Issue Packs'
      },
      '/repos/:org/:repo': {
        name: 'repo-dashboard',
        component: RepoDashboard,
      },
      '/projects/:id': {
        name: 'project-dashboard',
        component: ProjectDashboard
      }
    }
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
