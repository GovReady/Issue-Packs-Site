<template>
  <div class="project-dashboard">
    <div class="row">
      <div class="project-name">
        <h3>{{ project.name }}</h3>
      </div>
      <issue-pack v-for="pack in loadedPacks | orderBy 'listPriority'" :pack="pack" type="install" :project="project"></issue-pack>
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
      <div class="issue-pack-officials">
        <div class="x_panel">
          <div class="x_title">
            <h2>
              ...Or Copy An Official Pack
            </h2>
            <span class="fa fa-question-circle-o tooltip_trigger official_pack_help">
              <p class="tooltip">These are official issue packs from GovReady.  Copy them to your account to use them for your own projects.</p>
            </span>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <official-packs></official-packs>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import FileUpload from './FileUpload.vue';
import IssuePack from './IssuePack.vue';
import OfficialPacks from './OfficialPacks.vue';
import {store} from '../app';

  export default {
    data: function () {
      return {
        project: {},
        loadedPacks: []
      }
    },
    events: {
      'install-pack': function (pack) {
        this.$http.post('/api/redmine/' + this.project.id + '/install', {
          pack_id: pack.id
        }).then(function (response) {
          this.saveSync(pack);
          this.$dispatch('new-alert', {
            message: 'Issues installed successfully',
            type: 'success'
          });
        }, function (error) {
          console.error(error);
        });
      },
      'copy-pack': function (pack) {
        this.$http.post('/api/packs', {pack: pack})
          .then(function (response) {
            var newPack = response.data;
            this.$dispatch('new-alert', {message: 'Pack uploaded successfully.', type: 'success'});

            newPack.label = 'User Owned Pack';

            this.loadedPacks.push(newPack);
          }.bind(this), function (error) {
            console.error(error);
          });
      },
      'create-pack': function (pack) {
        var parsed = YAML.parse(pack);

        this.$http.post('/api/packs', {pack: parsed})
          .then(function (response) {
            var newPack = response.data;
            this.$dispatch('new-alert', {message: 'Pack uploaded successfully.', type: 'success'});

            newPack.label = 'User Owned Pack';

            this.loadedPacks.push(newPack);
          }.bind(this), function (error) {
            console.error(error);
          });
      }
    },
    components: { FileUpload, IssuePack, OfficialPacks },
    methods: {
      saveSync: function (pack) {
        if(pack.id) {
          return this.$http.post('/api/packs/' + pack.id + '/sync', {
            application: 'redmine',
            repo: this.project.identifier
          });
        } else {
          return new Promise(function (resolve, reject) {
            console.info('Cannot save sync for packs not stored in the database.');
            resolve();
          });
        }
      }
    },
    route: {
      data: function (transition) {
        var id = transition.to.params.id;

        var redminePromise = this.$http.get('/api/redmine/' + id)
          .then(function (response) {
            var project = response.data.project;

            return { project: project };
          }, function (error) {
            console.error(error);
          })

        var userPacksPromise = this.$http.get('/api/my-packs',{}).then(
          function (response) {
            var packs = response.data;
            packs.forEach(function (pack) {
              pack.installed = false;
              pack.installExisting = false;
              pack.installTo = {};
              pack.label = 'User Owned Pack';
              pack.listPriority = 1;
              this.loadedPacks.push(pack);
            }.bind(this));

            return response.data;
          }.bind(this),
          (err) => console.error(err));

        return Promise.all([
          redminePromise,
          userPacksPromise
        ]).then(function (response) {
          return {
            project: response[0].project
          }
        });
      }
    }
  }
</script>
