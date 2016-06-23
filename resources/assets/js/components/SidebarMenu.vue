<template>
  <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section" v-show="orgs.length > 0">
      <ul class="nav side-menu">
        <pulse-loader :loading="loading"></pulse-loader>
        <li v-bind:class="{ 'active': showGithub }" v-if="orgs.length > 0" v-on:blur="closeAccount('Github')" tabindex="1">
          <a v-on:click="toggleAccount('Github', $event)">
            GitHub
            <span class="fa" v-bind:class="{'fa-chevron-up': showGithub, 'fa-chevron-down': !showGithub }"></span>
          </a>
          <ul class="nav child_menu">
            <li v-for="org in orgs | filterBy orgFilter in 'name' 'login' | orderBy 'login'" v-bind:class="{'active': org.show}">
              <a v-on:click="show(org)">
                <img v-bind:src="org.avatar_url" class="org-avatar">
                <span class="org-name">{{ org.name || org.login }}</span>
                <span class="fa" v-bind:class="{'fa-chevron-down': !org.show, 'fa-chevron-up': org.show}"></span>
              </a>
              <ul class="nav child_menu" transition="expand">
                <!-- <div class="repo-filter">
                  <input v-model="org.repoFilter" type="text" class="form-control" placeholder="Filter Repos">
                </div> -->
                <li v-for="repo in org.repos | filterBy org.repoFilter in 'name' | orderBy 'name'">
                  <a v-on:click="loadRepo(repo)">{{ repo.name }}</a>
                </li>
              </ul>
            </li>
          </ul>
        </li>
        <li v-bind:class="{ 'active': showRedmine }" v-if="projects.length > 0" tabindex="2" v-on:blur="closeAccount('Redmine')">
          <a v-on:click="toggleAccount('Redmine', $event)">
            Redmine
            <span class="fa" v-bind:class="{'fa-chevron-up': showRedmine, 'fa-chevron-down': !showRedmine }"></span>
          </a>
          <ul class="nav child_menu">
            <li v-for="project in projects">
              <a v-link="{name: 'project-dashboard', params: { id: project.id }}">{{ project.name }}</a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</template>
<script>
import GithubService from '../services/github';
import _ from 'underscore';

import {store} from '../app';

  export default {
    ready () {

    },
    data () {
      return {
        connections: [],
        orgs: [],
        orgFilter: '',
        profile: JSON.parse(localStorage.getItem('profile')),
        projects: [],
        showGithub: true,
        showRedmine: false,
        state: store.state,
        loading: true
      }
    },
    props: [],
    methods: {
      closeAccount (account) {
        switch(account) {
          case 'Github':
            this.showGithub = false;
            break;
          case 'Redmine':
            this.showRedmine = false;
            break;
        }
      },
      show (org) {
        _.each(this.orgs, function (otherOrg) {
          //Don't change clicked org's status
          if(!_.isEqual(otherOrg, org)) {
            otherOrg.show = false;
          }
        });
        //Toggle clicked org
        org.show = !org.show;
      },
      loadRepo (repo) {
        this.$dispatch('repo-selected', repo);
      },
      toggleAccount(account, event) {
        switch (account) {
          case 'Github':
            this.showGithub = !this.showGithub;
            this.showRedmine = false;
            break;
          case 'Redmine':
            this.showRedmine = !this.showRedmine;
            this.showGithub = false;
            break;
        }
      }
    },
    events: {
      'redmine-connected': function () {
        this.$http.get('/api/redmine')
          .then(function (response) {
            var projects = response.data.projects;

            if(projects !== undefined) {
              this.showGithub = false;
            }

            this.projects = projects;

            return projects
          });
      },
      'redmine-deleted': function () {
        this.projects = [];
      }
    },
    asyncData: function () {
      //Check if it's a github login first
      var github_identity = _.findWhere(this.profile.identities, {provider: "github"});

      var promises = [];

      if(github_identity !== undefined) {
        var githubService = new GithubService({
          profile: this.profile
        });

        var githubPromise = githubService.getOrgs()
          .then(function (orgs) {

            _.each(orgs, function (org) {
              org.repoFilter = ''
            });
            return {orgs: orgs};
          });

        promises.push(githubPromise);
      }

      var redminePromise = this.$http.get('/api/redmine')
        .then(function (response) {
          var projects = response.data.projects;

          if(projects !== undefined) {
            this.showGithub = false;
          }

          return projects
        });

      promises.push(redminePromise);

      return Promise.all(promises).then(function (response) {
        if(response[1] !== undefined) {
          store.setOrgs(response[0].orgs);
          store.setProjects(response[1]);

          return {
            orgs: response[0].orgs,
            projects: response[1],
            loading: false
          };
        } else {
          return {
            orgs: response[0].orgs,
            projects: []
          }
        }
      });
    }
  }

</script>
