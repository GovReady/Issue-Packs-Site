<template>
  <div class="issue-pack">
    <div class="x_panel">
      <div class="x_title">
        <h2>{{ pack.name }}
          <small v-if="pack.label" v-bind:class="{'official-pack-label': pack.listPriority == 0 }">
            <i class="fa fa-check-circle" v-if="pack.listPriority == 0"></i>
            {{ pack.label }}
          </small>
        </h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <div class="issue-pack-body panel-body" v-bind:class="{ 'scroll-shadow': overflowing }">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Issue Title</th>
                <th>Issue Body</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="issue in pack.issues">
                <td>{{ issue.title }}</td>
                <td>{{ issue.body }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="issue-pack-manage" v-if="type == 'manage'">
          <div class="pack-delete">
            <button class="btn btn-danger" v-on:click="deletePack(pack)">Delete Pack</button>
            <input-switch :id="pack.id" :selected="pack.public"></input-switch>
          </div>
          <div class="pack-download">
            <a v-bind:download="filename" v-on:click="download($event)">Download Pack <i class="fa fa-download"></i></a>
          </div>
          <div class="pack-sync-log">
            <a v-on:click="toggleSyncLog()">Show Installation Log ({{ pack.syncs.length }}) <i class="fa" v-bind:class="{'fa-caret-down': !showSyncLog, 'fa-caret-up': showSyncLog}"></i></a>
            <div class="pack-sync-list" v-show="showSyncLog">
              <ul>
                <li v-for="sync in pack.syncs">
                  <span>{{ sync.user.name }} synced this pack to <a href="{{sync.url}}" target="_blank">{{sync.repo}}</a> {{ sync.created_at | timeago }}</span>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="issue-pack-install" v-if="type == 'install'">
          <div class="pack-install" v-show="!pack.installed" v-if="milestones !== undefined">
            <button class="btn btn-primary" v-on:click="install(pack)" v-show="!pack.installExisting">Create Milestone &amp; Issues</button>
          </div>
          <div class="pack-install-existing" v-show="!pack.installed" v-if="milestones !== undefined">
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
          <div class="pack-install-project" v-if="project !== undefined">
            <button v-on:click="install(pack)" class="btn btn-primary">Install Pack</button>
          </div>
          <div class="pack-installed-messages" v-if="pack.installed">
            <span>
              Pack installed successfully to
              <br>
              <a href="{{ pack.installedTo.html_url }}" target="_blank">{{ pack.installedTo.html_url }}</a>
              </span>
          </div>
        </div>
        <div class="issue-pack-search" v-if="type == 'search'">
          <button v-on:click="copyPack(pack)" v-if="copyable" class="btn btn-primary copy-pack-btn">Copy To My Account</button>
          <small v-if="!copyable">Cannot copy your own packs.</small>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import InputSwitch from './InputSwitch.vue';
import $ from 'jquery';
import YAML from 'yamljs';

  export default {
    ready () {
      var el = $(this.$el).find('.issue-pack-body')[0];
      var isOverflowing = el.clientHeight < el.scrollHeight;

      this.overflowing = isOverflowing;
    },
    components: { InputSwitch },
    props: ['pack', 'type', 'milestones', 'label', 'project'],
    data: function () {
      return {
        showSyncLog: false,
        overflowing: false
      };
    },
    computed: {
      copyable: function () {
        if(this.pack.listPriority === 0) {
          return true;
        }

        var profile = JSON.parse(localStorage.getItem('profile'));

        if(this.pack.user) {
          return ((this.type == 'search') && (this.pack.user.email !== profile.email));
        } else {
          return false;
        }
      },
      filename: function () {
        return this.pack.name + '.yaml';
      }
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
      download(event) {
        var packObject = {
          name: this.pack.name,
          issues: [],
        };

        this.pack.issues.forEach(function (issue) {
          var packIssue = {
            title: issue.title,
            body: issue.body,
            labels: issue.labels
          };

          if(packIssue.labels.length === 0) {
            delete packIssue.labels;
          }

          packObject.issues.push(packIssue);
        });

        var packString = YAML.stringify(packObject, 4, 2);

        event.target.href="data:text/plain," + encodeURIComponent(packString);
      },
      install (pack) {
        this.$dispatch('install-pack', pack);
      },
      showMilestones(pack) {
        pack.installExisting = true;
      },
      hideMilestones(pack) {
        pack.installExisting = false;
      },
      copyPack(pack) {
        this.$dispatch('copy-pack', pack);
      },
      toggleSyncLog() {
        this.showSyncLog = !this.showSyncLog;
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
