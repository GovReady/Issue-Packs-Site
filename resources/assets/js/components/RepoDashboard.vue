<template>
<div class="repo-dashboard">
  <div class="row">
    <div v-for="pack in issuePacks" class="issue-pack">
      <div class="x_panel">
        <div class="x_title">
          <h2>{{ pack.name }}</h2>
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
            <button class="btn btn-primary" v-bind:class="{'disabled': pack.installed}" v-on:click="install(pack)" v-show="!pack.installExisting">
              <span v-if="!pack.installed">Create Milestone &amp; Issues</span>
              <span v-if="pack.installed">Issues Created</span>
            </button>
          </div>
          <div class="pack-install-existing">
            <a v-on:click="showMilestones(pack)" v-show="!pack.installExisting">Or install issues in existing milestone</a>
            <a v-on:click="hideMilestones(pack)" v-show="pack.installExisting">Nevermind, install new milestone</a>
            <div class="existing-milestones" v-show="pack.installExisting">
              <select v-model="pack.installTo">
                <option selected>Select Milestone</option>
                <option v-for="milestone in milestones" v-bind:value="milestone">{{ milestone.title }}</option>
              </select>
              <button class="btn btn-primary install-existing-btn" v-if="pack.installTo != 'Select Milestone'">Install to {{ pack.installTo.title }}</button>
            </div>
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
  import Github from 'github';
  import _ from 'underscore';

  export default {
    props: ['repo'],
    ready () {

    },
    methods: {
      install (pack) {
        if(!pack.installed) {
          this.$dispatch('new-alert', {'message': 'Installing ' + pack.milestone, 'type': 'success'});
        }

        var github_identity = _.findWhere(this.profile.identities, { provider: "github" });

        var issuePack = new IssuePack({
          auth: {
            token: github_identity.access_token
          }
        });

        issuePack.load(pack);
        var ret = issuePack.push(this.repo.full_name);

        pack.installed = true;
      },
      showMilestones(pack) {
        pack.installExisting = true;
      },
      hideMilestones(pack) {
        pack.installExisting = false;
      }
    },
    data () {
      return {
        issuePacks: [],
        milestones: [],
        pack_url: "https://api.github.com/repos/govready/issue-packs/contents/examples",
        profile: JSON.parse(localStorage.getItem('profile'))
      }
    },
    asyncData: function (resolve, reject) {
      var profile = this.profile;

      var github = new GithubService({
        profile: profile
      });

      var milestonesPromise = github.getMilestones(this.repo.full_name)
        .then(function (milestones) {
          return milestones;
        });



      var packsPromise = github.getIssuePacks(this.pack_url)
        .then(function (packs) {
          var packObjects = [];

          packs.forEach(function (pack) {
            var parsed = YAML.parse(pack);
            parsed.installed = false;
            parsed.installExisting = false;
            parsed.installTo = {};
            packObjects.push(parsed);
          });

          return packObjects;
        });

      return Promise.all([
        milestonesPromise,
        packsPromise
      ]).then(([milestones, issuePacks]) => ({milestones, issuePacks}));
    }
  }
</script>
