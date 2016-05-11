<template>
  <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
      <h3>Organizations</h3>
      <ul class="nav side-menu">
        <li v-for="org in orgs" v-bind:class="{'active': org.show}">
          <a v-on:click="show(org)">
            <img v-bind:src="org.avatar_url" class="org-avatar">
            <span class="org-name">{{ org.name || org.login }}</span>
            <span class="fa fa-chevron-down"></span>
          </a>
          <ul class="nav child_menu" v-show="org.show" transition="expand">
            <li v-for="repo in org.repos">
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

      }
    },
    props: ['orgs'],
    methods: {
      show (org) {
        console.log(org);
        org.show = !org.show;
      }
    }
  }

</script>
