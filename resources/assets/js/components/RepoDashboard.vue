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
                  {{ issue.title }}
                </p>
              </li>
            </ul>
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
              }
            ]
          }
        ]
      }

    }
  }
</script>
