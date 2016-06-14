<template>
  <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="sidebar-github-orgs" v-show="orgs.length > 0">
      <div class="menu-header">
        <a v-on:click="showGithub = !showGithub">
          <h3>Github Organizations</h3>
          <i class="fa" v-bind:class="{'fa-chevron-up': showGithub, 'fa-chevron-down': !showGithub }"></i>
        </a>
      </div>
      <div class="menu_section" v-bind:class="{ 'active': showGithub }">
        <div class="menu-search">
          <input type="text" v-model="orgFilter" class="form-control" placeholder="Filter Organizations">
        </div>
        <ul class="nav side-menu">
          <li v-for="org in orgs | filterBy orgFilter in 'name' 'login' | orderBy 'login'" v-bind:class="{'active': org.show}">
            <a v-on:click="show(org)">
              <img v-bind:src="org.avatar_url" class="org-avatar">
              <span class="org-name">{{ org.name || org.login }}</span>
              <span class="fa" v-bind:class="{'fa-chevron-down': !org.show, 'fa-chevron-up': org.show}"></span>
            </a>
            <ul class="nav child_menu" transition="expand">
              <div class="repo-filter">
                <input v-model="org.repoFilter" type="text" class="form-control" placeholder="Filter Repos">
              </div>
              <li v-for="repo in org.repos | filterBy org.repoFilter in 'name' | orderBy 'name'">
                <a v-on:click="loadRepo(repo)">{{ repo.name }}</a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
    <div class="sidebar-redmine-projects" v-show="projects.length > 0">
      <div class="menu-header">
        <a v-on:click="showRedmine = !showRedmine">
          <h3>Redmine Projects</h3>
          <i class="fa" v-bind:class="{'fa-chevron-up': showRedmine, 'fa-chevron-down': !showRedmine}"></i>
        </a>
      </div>
      <div class="menu_section" v-bind:class="{ 'active': showRedmine }">
        <ul class="nav side-menu">
          <li v-for="project in projects">
            <a>{{ project.name }}</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
<script>
import GithubService from '../services/github';
import _ from 'underscore';

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
        showRedmine: false
      }
    },
    props: [],
    methods: {
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
          return {
            orgs: response[0].orgs,
            projects: response[1]
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
