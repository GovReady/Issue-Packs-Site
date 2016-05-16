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
            <span class="fa fa-chevron-down"></span>
          </a>
          <ul class="nav child_menu" v-show="org.show" transition="expand">
            <li v-for="repo in org.repos | orderBy 'name'">
              <a v-on:click="loadRepo(repo)">{{ repo.name }}</a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</template>
<script>
  export default {
    ready () {

    },
    data () {
      return {
        orgFilter: ''
      }
    },
    props: ['orgs'],
    methods: {
      show (org) {
        console.log(org);
        org.show = !org.show;
      },
      loadRepo (repo) {
        this.$dispatch('repo-selected', repo);
      }
    }
  }

</script>
