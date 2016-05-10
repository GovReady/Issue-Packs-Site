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
                  {{ issue.title }} - {{ issue.body }}
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
        issuePacks: [
          {
            milestone: "Milestone 1",
            issues: [
              {
                title: "Issue 1",
                body: "This is the first issue",
                labels: ["Role 1", "Role 2"]
              },
              {
                title: "Issue 2",
                body: "This is the second issue",
                labels: ["Role 3"]
              },
              {
                title: "Issue 3",
                body: "This is the third issue",
              }
            ],
            installed: false
          },
          {
            milestone: "Milestone 2",
            issues: [
              {
                title: "Issue 1",
                body: "This is the first issue",
                labels: ["Role 1", "Role 2"]
              },
              {
                title: "Issue 2",
                body: "This is the second issue",
                labels: ["Role 3"]
              },
              {
                title: "Issue 3",
                body: "This is the third issue",
              }
            ],
            installed: false
          }
        ]
      }
    }
  }
</script>
