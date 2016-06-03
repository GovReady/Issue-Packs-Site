<template>
  <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu-header">
      <h3>Organizations</h3>
    </div>
    <div class="menu-search">
      <input type="text" v-model="orgFilter" class="form-control" placeholder="Filter Organizations">
    </div>
    <div class="menu_section">
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
</template>
<script>
import GithubService from '../services/github';
import _ from 'underscore';

  export default {
    ready () {

    },
    data () {
      return {
        orgs: [],
        orgFilter: '',
        profile: JSON.parse(localStorage.getItem('profile'))
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
      if(github_identity !== undefined) {
        var githubService = new GithubService({
          profile: this.profile
        });

        return githubService.getOrgs()
          .then(function (orgs) {

            _.each(orgs, function (org) {
              org.repoFilter = ''
            });
            return {orgs: orgs};
          });
      }
    }
  }

</script>
