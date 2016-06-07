<template>
<div class="repo-dashboard">
  <div class="row">
    <div class="repo-name">
      <h3>{{ repo.name }}</h3>
    </div>
    <issue-pack v-for="pack in issuePacks" :pack="pack" type="install" :milestones="milestones"></issue-pack>
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
  import IssuePackService from 'issue-pack';
  import IssuePack from './IssuePack.vue';
  import GithubService from '../services/github';
  import YAML from 'yamljs';
  import Github from 'github';
  import _ from 'underscore';

  export default {
    props: [],
    components: { FileUpload, IssuePack },
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
              parsed.label = 'Official GovReady Pack';
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
      },
      'install-pack': function (pack) {
        this.install(pack);
      }
    },
    methods: {
      install (pack) {
        if(!pack.installed) {
          this.$dispatch('new-alert', {'message': 'Installing ' + pack.name, 'type': 'success'});
        }

        var github_identity = _.findWhere(this.profile.identities, { provider: "github" });

        var issuePack = new IssuePackService({
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
