<template>
<div class="container body">


  <div class="main_container">

    <div class="col-md-3 left_col">
      <div class="left_col scroll-view">

        <div class="navbar nav_title" style="border: 0;">
          <a href="index.html" class="site_title"><i class="fa fa-briefcase"></i> <span>Issue Packs</span></a>
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
        </div>
        <!-- /menu profile quick info -->
        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
          <div class="menu_section">
            <h3>Organizations</h3>
            <ul class="nav side-menu">
              <li v-for="org in orgs" v-bind:class="{'active': org.show}">
                <a v-on:click="show(org)">
                  <img v-bind:src="org.avatar_url" class="org-avatar">
                  <span class="org-name">{{ org.name }}</span>
                  <span class="fa fa-chevron-down"></span>
                </a>
                <ul class="nav child_menu" v-show="org.show">
                  <li v-for="repo in org.repos">
                    <a v-on:click="loadRepo(repo)">{{ repo.name }}</a>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
        <!-- /sidebar menu -->

        <!-- /menu footer buttons -->
        <div class="sidebar-footer hidden-small">
          <a data-toggle="tooltip" data-placement="top" title="Settings">
            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
          </a>
          <a data-toggle="tooltip" data-placement="top" title="FullScreen">
            <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
          </a>
          <a data-toggle="tooltip" data-placement="top" title="Lock">
            <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
          </a>
          <logout></logout>
        </div>
        <!-- /menu footer buttons -->
      </div>
    </div>

    <!-- top navigation -->
    <div class="top_nav">
      <div class="nav_menu">
        <nav class="" role="navigation">
          <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
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
                  <a href="javascript:;">
                    <span class="badge bg-red pull-right">50%</span>
                    <span>Settings</span>
                  </a>
                </li>
                <li>
                  <a href="javascript:;">Help</a>
                </li>
                <li><a href="login.html"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                </li>
              </ul>
            </li>
            <li role="presentation" class="dropdown">
              <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-envelope-o"></i>
                <span class="badge bg-green">6</span>
              </a>
              <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                <li>
                  <a>
                    <span class="image">
                      <img v-bind:src="gravatar_link" alt="Profile Image" />
                    </span>
                    <span>
                      <span>John Smith</span>
                      <span class="time">3 mins ago</span>
                    </span>
                    <span class="message">Film festivals used to be do-or-die moments for movie makers. They were where...</span>
                  </a>
                </li>
                <li>
                  <a>
                    <span class="image">
                      <img v-bind:src="gravatar_link" alt="Profile Image" />
                    </span>
                    <span>
                      <span>John Smith</span>
                      <span class="time">3 mins ago</span>
                    </span>
                    <span class="message">Film festivals used to be do-or-die moments for movie makers. They were where...</span>
                  </a>
                </li>
                <li>
                  <a>
                    <span class="image">
                      <img v-bind:src="gravatar_link" alt="Profile Image" />
                    </span>
                    <span>
                      <span>John Smith</span>
                      <span class="time">3 mins ago</span>
                    </span>
                    <span class="message">Film festivals used to be do-or-die moments for movie makers. They were where...</span>
                  </a>
                </li>
                <li>
                  <a>
                    <span class="image">
                      <img v-bind:src="gravatar_link" alt="Profile Image" />
                    </span>
                    <span>
                      <span>John Smith</span>
                      <span class="time">3 mins ago</span>
                    </span>
                    <span class="message">Film festivals used to be do-or-die moments for movie makers. They were where...</span>
                  </a>
                </li>
                <li>
                  <div class="text-center">
                    <a href="#">
                      <strong>See All Alerts</strong>
                      <i class="fa fa-angle-right"></i>
                    </a>
                  </div>
                </li>
              </ul>
            </li>
          </ul>
        </nav>
      </div>
    </div>
    <!-- /top navigation -->
    <!-- page content -->
    <div class="right_col" role="main">
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <span v-if="!currentRepo.selected">Choose issue pack on left.</span>
          <repo-dashboard :repo="currentRepo" v-if="currentRepo.selected"></repo-dashboard>
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
import GithubAPI from 'github';

import Logout from './Logout.vue';
import RepoDashboard from './RepoDashboard.vue';

export default {
  ready () {

  },
  data () {
    var profile = JSON.parse(localStorage.getItem('profile'));

    var orgs = [
      {
        name: 'GovReady',
        url: 'https://github.com/govready',
        avatar_url: 'https://avatars1.githubusercontent.com/u/6815262?v=3&s=84',
        show: false,
        repos: [
          {
            name: 'Issue-Packs-Site',
            description: 'Front End for Issue Packs',
            html_url: 'https://github.com/GovReady/Issue-Packs-Site',
            full_name: 'GovReady/Issue-Packs-Site'
          },
          {
            name: 'GovReady-WordPress-Agent',
            description: 'Plugin for WordPress to assist with compliance',
            html_url: 'https://github.com/GovReady/GovReady-WordPress-Agent',
            full_name: 'GovReady/GovReady-WordPress-Agent'
          },
          {
            name: 'GovReady-Drupal-Agent',
            description: 'Plugin for Drupal to assist with compliance',
            html_url: 'https://github.com/GovReady/GovReady-Drupal-Agent',
            full_name: 'GovReady/GovReady-Drupal-Agent'
          }
        ]
      },
      {
        name: '@unitedstates',
        url: 'https://github.com/unitedstates',
        avatar_url: 'https://avatars.githubusercontent.com/u/2200203?v=3',
        show: false,
        repos: [
          {
            name: 'Test-Repo',
            description: 'Test Repo Description',
            html_url: 'https://github.com/govready/Issue-Packs-Site',
            full_name: 'GovReady/Test-Repo'
          }
        ]
      },
      {
        name: 'statedecoded',
        url: 'https://github.com/statedecoded',
        avatar_url: 'https://avatars.githubusercontent.com/u/3639174?v=3',
        show: false,
        repos: [
          {
            name: 'Test-Repo',
            description: 'Test Repo Description',
            html_url: 'https://github.com/govready/Issue-Packs-Site',
            full_name: 'GovReady/Test-Repo'
          }
        ]
      },
      {
        name: 'OpenGov Foundation',
        url: 'https://github.com/opengovfoundation',
        avatar_url: 'https://avatars.githubusercontent.com/u/2090211?v=3',
        show: false,
        repos: [
          {
            name: 'Test-Repo',
            description: 'Test Repo Description',
            html_url: 'https://github.com/govready/Issue-Packs-Site',
            full_name: 'GovReady/Test-Repo'
          }
        ]
      },
      {
        name: 'World Wide Web Consortium',
        url: 'https://github.com/w3c',
        avatar_url: 'https://avatars.githubusercontent.com/u/379216?v=3',
        show: false,
        repos: [
          {
            name: 'Test-Repo',
            description: 'Test Repo Description',
            html_url: 'https://github.com/govready/Issue-Packs-Site',
            full_name: 'GovReady/Test-Repo'
          }
        ]
      },
      {
        name: 'DCLegalHackers',
        url: 'https://github.com/dclegalhackers',
        avatar_url: 'https://avatars.githubusercontent.com/u/5272226?v=3',
        show: false,
        repos: [
          {
            name: 'Test-Repo',
            description: 'Test Repo Description',
            html_url: 'https://github.com/govready/Issue-Packs-Site',
            full_name: 'GovReady/Test-Repo'
          }
        ]
      },
    ];

    return {
      profile: profile,
      orgs: orgs,
      currentRepo: {selected: false}
    };
  },
  components: { Logout, RepoDashboard },
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
      this.currentRepo = repo;
      this.currentRepo.selected = true;

    }
  },
  asyncData: function (resolve, reject) {
    var self = this;
    var id_token = localStorage.getItem('id_token');

    // var github = new Github({
    //   token: id_token
    // });

    // var cmbirk = github.getUser('cmbirk');
    // var orgs = cmbirk.getOrgs(function () {
    //   console.log(arguments);
    // });
    // console.log(cmbirk);

  }
}
</script>
