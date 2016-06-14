<template>
  <div class="official-packs accordion">
    <div class="official-pack panel" v-for="pack in packs">
      <a class="panel-heading" v-on:click="expand(pack)">
        <h4>{{ pack.name }}</h4>
      </a>
      <div class="panel-collapse" v-bind:class="{ 'collapse-in': pack.expanded, 'collapse': !pack.expanded}" >
        <div class="official-pack-body panel-body">
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
          <button class="btn btn-primary" v-on:click="copyPack(pack)">Copy To My Account</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import GithubService from '../services/github';
import _ from 'underscore';

export default {
  data: function () {
    return {
      packs: [],
      pack_url: "https://api.github.com/repos/govready/issue-packs/contents/examples"
    }
  },
  methods: {
    expand: function (pack) {
      _.each(this.packs, function (otherPack) {
        //Don't change clicked org's status
        if(!_.isEqual(otherPack, pack)) {
          otherPack.expanded = false;
        }
      });

      pack.expanded = !pack.expanded;
    },
    copyPack: function (pack) {
      this.$dispatch('copy-pack', pack);
    }
  },
  asyncData: function () {
    var profile = JSON.parse(localStorage.getItem('profile'));

    var github = new GithubService({
      profile: profile
    });

    var officialPacksPromise = github.getIssuePacks(this.pack_url)
        .then(function (packs) {
          var loadedPacks = [];

          packs.forEach(function (pack) {
            var parsed = YAML.parse(pack);
            parsed.expanded = false;

            loadedPacks.push(parsed);
          }.bind(this));

          return {packs: loadedPacks};
        }.bind(this));

    return officialPacksPromise;
  }
}
</script>
