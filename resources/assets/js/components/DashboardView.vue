<template>
<div class="container body">
  <div class="main_container">
    <div class="col-md-3 left_col">
      <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
          <a v-link="'/dashboard'" class="site_title"><i class="fa fa-briefcase"></i> <span>Issue Packs</span></a>
        </div>
        <div class="clearfix"></div>
        <!-- menu prile quick info -->
        <div class="profile">
          <div class="profile_pic">
            <img v-bind:src="gravatar_link" alt="..." class="img-circle profile_img">
          </div>
          <div class="profile_info">
            <span>Welcome,</span>
            <h2>{{ profile.name }}</h2>
          </div>
          <div class="clearfix"></div>
        </div>
        <!-- /menu profile quick info -->
        <!-- sidebar menu -->
        <sidebar-menu wait-for="async-data" :toggled="sidebarToggle"></sidebar-menu>
        <!-- /sidebar menu -->

        <!-- /menu footer buttons -->
        <div class="sidebar-footer hidden-small">
          <a href="#" data-toggle="tooltip" data-placement="top" title="Logout" v-on:click="logout()">
            <span class="fa fa-sign-out" aria-hidden="true"></span>
          </a>
        </div>
        <!-- /menu footer buttons -->
      </div>
    </div>

    <!-- top navigation -->
    <div class="top_nav">
      <div class="nav_menu">
        <nav class="" role="navigation">
          <div class="nav toggle">
            <a id="menu_toggle" v-on:click="toggleSidebar()"><i class="fa fa-bars"></i></a>
          </div>
          <ul class="nav navbar-nav navbar-right">
            <li class="">
              <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <img v-bind:src="gravatar_link" alt="">{{ profile.name }}
                <span class=" fa fa-angle-down"></span>
              </a>
              <ul class="dropdown-menu dropdown-usermenu pull-right">
                <li>
                  <a href="javascript:;">  Profile</a>
                </li>
                <li>
                  <a v-link="{name: 'my-packs'}">
                    <span>My Packs</span>
                  </a>
                </li>
                <li><a v-on:click="logout()"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                </li>
              </ul>
            </li>
            <!-- <li role="presentation" class="dropdown">
              <messages :gravatar="gravatar_link"></messages>
            </li> -->
          </ul>
        </nav>
      </div>
    </div>
    <!-- /top navigation -->
    <!-- page content -->
    <div class="right_col" role="main">
      <!-- <div class="page-title">
        <div class="title_left">
          <h3>{{ $route.title }}</h3>
        </div>
      </div> -->
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>
                {{ $route.title }}
              </h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <router-view></router-view>
            </div>
          </div>

        </div>
      </div>
      <br />
    </div>
    <!-- /page content -->
    <!-- footer content -->
    <footer>
      <div class="pull-right">
        Issue Packs Dashboard by <a href="https://govready.com" target="_blank">Govready</a>
      </div>
      <div class="clearfix"></div>
    </footer>
    <!-- /footer content -->
  </div>
</div>
</template>
<script>
import Crypto from 'crypto';
import Github from 'github-api';

import _ from 'underscore';
import {router} from '../app.js';

import Messages from './Messages.vue';
import RepoDashboard from './RepoDashboard.vue';
import SidebarMenu from './SidebarMenu.vue';

export default {
  ready () {

  },
  data () {
    var profile = JSON.parse(localStorage.getItem('profile'));

    return {
      profile: profile,
      orgs: [],
      currentRepo: { selected: false },
      pack_url: "https://api.github.com/repos/govready/issue-packs/contents/examples",
      sidebarToggle: false
    };
  },
  components: { Messages, RepoDashboard, SidebarMenu },
  computed: {
    gravatar_link: function () {
      var email = this.profile.email;
      var base_url = "//www.gravatar.com/avatar/";

      email = email.toLowerCase().trim();
      var hash = Crypto.createHash('md5');
      hash.update(email);

      var url = base_url + hash.digest('hex');

      return url;
    }
  },
  methods: {
    show (org) {
      org.show = !org.show;
    },
    loadRepo (repo) {
      router.go({
        name: 'repo-dashboard',
        params: {
          org: repo.owner.login,
          repo: repo.name
        }
      });
    },
    logout () {
      this.$dispatch('logout');
    },
    toggleSidebar () {
      this.sidebarToggle = !this.sidebarToggle;
      this.$dispatch('navbar-toggle', this.sidebarToggle);
    }
  },
  events: {
    'repo-selected': function (repo) {
      this.loadRepo(repo);
    }
  }
}
</script>
