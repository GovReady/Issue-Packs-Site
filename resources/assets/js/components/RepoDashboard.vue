<template>
<div class="repo-dashboard">
  <div class="row">
    <div class="repo-name">
      <h3>{{ repo.name }}</h3>
    </div>
    <issue-pack v-for="pack in issuePacks | orderBy 'listPriority'" :pack="pack" type="install" :milestones="milestones"></issue-pack>
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
        var loadedPacks = [];

        var github = new GithubService({
          profile: profile
        });

        var userPacksPromise = this.$http.get('/api/my-packs',{}).then(
          function (response) {
            var packs = response.data;
            packs.forEach(function (pack) {
              pack.installed = false;
              pack.installExisting = false;
              pack.installTo = {};
              pack.label = 'User Owned Pack';
              pack.listPriority = 1;
              loadedPacks.push(pack);
            });

            return response.data;
          },
          (err) => console.error(err));

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
          userPacksPromise
          //officialPacksPromise
        ]).then(function (response) {
          return {
            repo: response[0].repo,
            milestones: response[0].milestones,
            issuePacks: loadedPacks
          };
        });
      }
    },
    events: {
      'create-pack': function (pack) {
        var parsed = YAML.parse(pack);

        this.$http.post('/api/packs', {pack: parsed})
          .then(function (response) {
            var newPack = response.data;
            this.$dispatch('new-alert', {message: 'Pack uploaded successfully.', type: 'success'});

            newPack.installed = false;
            newPack.installExisting = false;
            newPack.installTo = {};
            newPack.label = 'User Owned Pack';

            this.issuePacks.push(newPack);
          }.bind(this), function (error) {
            console.error(error);
          });
      },
      'install-pack': function (pack) {
        this.install(pack);
      }
    },
    methods: {
      install: function (pack) {
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
            this.saveSync(pack, pack.installTo.html_url)
              .then(function (response) {
                this.$dispatch('new-alert', {'message': 'Pack installed successfully', 'type': 'success'});
                pack.installedTo = pack.installTo;
                pack.installed = true;
              }.bind(this), function (error) {
                console.error(error);
              });

          }.bind(this));
        } else {
          var ret = issuePack.push(this.repo.full_name);

          ret.then(function (milestone) {
            this.saveSync(pack, milestone.html_url).then(function (response) {
              this.$dispatch('new-alert', {'message': 'Pack installed successfully', 'type': 'success'});
              pack.installedTo = milestone;
              pack.installed = true;
            }.bind(this), function (error) {
              console.error(error);
            });
          }.bind(this));
        }
      },
      saveSync: function (pack, url) {
        if(pack.id) {
          return this.$http.post('/api/packs/' + pack.id + '/sync', {
            application: 'github',
            repo: this.repo.full_name,
            url: url
          });
        } else {
          return new Promise(function (resolve, reject) {
            console.info('Cannot save sync for packs not stored in the database.');
            resolve();
          });

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
