<template>
<div class="repo-dashboard">
  <div class="row">
    <div class="repo-name">
      <h3>{{ repo.name }}</h3>
    </div>
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
          <div class="pack-install" v-show="!pack.installed">
            <button class="btn btn-primary"v-on:click="install(pack)" v-show="!pack.installExisting">Create Milestone &amp; Issues</button>
          </div>
          <div class="pack-install-existing" v-show="!pack.installed">
            <a v-on:click="showMilestones(pack)" v-show="!pack.installExisting">Or install issues in existing milestone</a>
            <a v-on:click="hideMilestones(pack)" v-show="pack.installExisting">Nevermind, install new milestone</a>
            <div class="existing-milestones" v-show="pack.installExisting">
              <select v-model="pack.installTo">
                <option selected>Select Milestone</option>
                <option v-for="milestone in milestones" v-bind:value="milestone">{{ milestone.title }}</option>
              </select>
              <button v-on:click="install(pack)"class="btn btn-primary install-existing-btn" v-if="pack.installTo != 'Select Milestone'">Install to {{ pack.installTo.title }}</button>
            </div>
          </div>
          <div class="pack-installed-messages" v-if="pack.installed">
            <span>
              Pack installed successfully to
              <br>
              <a href="{{ pack.installedTo.html_url }}" target="_blank">{{ pack.installedTo.html_url }}</a>
              </span>
          </div>
        </div>
      </div>
    </div>
    <div class="issue-pack-upload">
      <div class="x_panel">
        <div class="x_title">
          <h2>Upload a Pack</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <file-upload></file-upload>
        </div>
      </div>
    </div>
  </div>
</div>
</template>
<script>
  import FileUpload from './FileUpload.vue';
  import IssuePack from 'issue-pack';
  import GithubService from '../services/github';
  import YAML from 'yamljs';
  import Github from 'github';
  import _ from 'underscore';

  export default {
    props: [],
    components: { FileUpload },
    route: {
      data: function (transition) {
        var profile = JSON.parse(localStorage.getItem('profile'));

        var github = new GithubService({
          profile: profile
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

        var repoPromise = new Promise(function (resolve, reject) {
          github.getRepo(transition.to.params.org + '/' + transition.to.params.repo)
            .then(function (repo) {
              github.getMilestones(repo.full_name)
                .then(function (milestones) {
                  resolve({
                    repo: repo,
                    milestones: milestones
                  });
                });
            });
        });

        return Promise.all([
          repoPromise,
          packsPromise
        ]).then(function (response) {
          return {
            repo: response[0].repo,
            milestones: response[0].milestones,
            issuePacks: response[1]
          };
        });
      }
    },
    events: {
      'create-pack': function (pack) {
        var parsed = YAML.parse(pack);
        parsed.installed = false;
        parsed.installExisting = false;
        parsed.installTo = {};

        this.issuePacks.push(parsed);
      }
    },
    methods: {
      install (pack) {
        if(!pack.installed) {
          this.$dispatch('new-alert', {'message': 'Installing ' + pack.name, 'type': 'success'});
        }

        var github_identity = _.findWhere(this.profile.identities, { provider: "github" });

        var issuePack = new IssuePack({
          auth: {
            token: github_identity.access_token
          }
        });

        issuePack.load(pack);

        if(pack.installExisting) {
          var ret = issuePack.push(this.repo.full_name, pack.installTo.number);

          ret.then(function (milestone) {
            this.$dispatch('new-alert', {'message': 'Pack installed successfully', 'type': 'success'});
            pack.installedTo = pack.installTo;
            pack.installed = true;
          }.bind(this));
        } else {
          var ret = issuePack.push(this.repo.full_name);

          ret.then(function (milestone) {
            this.$dispatch('new-alert', {'message': 'Pack installed successfully', 'type': 'success'});
            pack.installedTo = milestone;
            pack.installed = true;
          }.bind(this));
        }
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
        profile: JSON.parse(localStorage.getItem('profile')),
        repo: {}
      }
    }
  }
</script>
