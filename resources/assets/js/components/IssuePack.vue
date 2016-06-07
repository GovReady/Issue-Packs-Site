<template>
  <div class="issue-pack">
    <div class="x_panel">
      <div class="x_title">
        <h2>{{ pack.name }}
          <small v-if="pack.label">
            <i class="fa fa-check-circle" v-if="pack.label == 'Official GovReady Pack'"></i>
            {{ pack.label }}
          </small>
        </h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <div class="issue-pack-body">
          <ul class="to_do">
            <li v-for="issue in pack.issues">
              <p>
                <span>{{ issue.title }}</span> - <span>{{ issue.body }}</span>
                <span v-for="label in issue.labels" class="issue-role">{{ label.name }}</span>
              </p>
            </li>
          </ul>
        </div>
        <div class="issue-pack-manage" v-if="type == 'manage'">
          <div class="pack-delete">
            <button class="btn btn-danger" v-on:click="deletePack(pack)">Delete Pack</button>
            <input-switch :id="pack.id" :selected="pack.public"></input-switch>
          </div>
        </div>
        <div class="issue-pack-install" v-if="type == 'install'">
          <div class="pack-install" v-show="!pack.installed">
            <button class="btn btn-primary" v-on:click="install(pack)" v-show="!pack.installExisting">Create Milestone &amp; Issues</button>
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
  </div>
</template>
<script>
import InputSwitch from './InputSwitch.vue';

  export default {
    components: { InputSwitch },
    props: ['pack', 'type', 'milestones', 'label'],
    data: () => {
      return {
        jwtHeader: { 'Authorization': 'Bearer ' + localStorage.getItem('id_token') },
        csrfHeader: {'X-CSRF-TOKEN' : document.querySelector('#token').getAttribute('value') }
      };
    },
    methods: {
      deletePack (pack) {

        var packPromise = this.$http.delete('/api/packs/' + pack.id).then(
          function (response) {
            this.$dispatch('delete-pack', pack);
          }.bind(this),
          (err) => console.error(err));

        return packPromise;
      },
      install (pack) {
        this.$dispatch('install-pack', pack);
      },
      showMilestones(pack) {
        pack.installExisting = true;
      },
      hideMilestones(pack) {
        pack.installExisting = false;
      }
    },
    events: {
      'input-toggled': function (selected) {
        this.$http.post('/api/packs/' + this.pack.id + '/publish', { public: selected })
          .then(function (result) {
            console.log(result);
          }, function (error) {
            console.error(error);
          });
      }
    }
  }
</script>
