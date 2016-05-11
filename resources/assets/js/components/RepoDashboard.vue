<template>
<div class="repo-dashboard">
  <div class="row">
    <div v-for="pack in issuePacks" class="issue-pack">
      <div class="x_panel">
        <div class="x_title">
          <h2>{{ pack.milestone }}</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <div>
            <ul class="to_do">
              <li v-for="issue in pack.issues">
                <p>
                  <span>{{ issue.title }}</span> - <span>{{ issue.body }}</span>
                  <span v-for="label in issue.labels" class="issue-role">{{ label }}</span>
                </p>
              </li>
            </ul>
          </div>
          <div class="pack-install">
            <button class="btn btn-primary" v-bind:class="{'disabled': pack.installed}" v-on:click="install(pack)">
              <span v-if="!pack.installed">Install</span>
              <span v-if="pack.installed">Pack Installed</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>
<script>
  import IssuePack from 'issue-pack';
  import GithubService from '../services/github';
  import YAML from 'yamljs';

  export default {
    props: ['repo'],
    ready () {

    },
    methods: {
      install (pack) {
        if(!pack.installed) {
          this.$dispatch('new-alert', {'message': 'Installing ' + pack.milestone, 'type': 'success'});
        }

        pack.installed = true;
      }
    },
    data () {
      return {
        issuePacks: []
      }
    },
    asyncData: function (resolve, reject) {
      var profile = JSON.parse(localStorage.getItem('profile'));

      var github = new GithubService({
        profile: profile
      });

      return github.getIssuePacks()
        .then(function (packs) {
          var packObjects = [];

          packs.forEach(function (pack) {
            packObjects.push(YAML.parse(pack));
          });

          return { issuePacks: packObjects }
        });
    }
  }
</script>
