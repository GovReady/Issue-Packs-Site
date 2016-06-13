<template>
  <div class="search-packs">
    <small v-if="searchPacks.length == 0">No public packs found.</small>
    <issue-pack v-for="pack in searchPacks | orderBy 'listPriority'" :pack="pack" type="search"></issue-pack>
  </div>
</template>
<script>
import IssuePack from './IssuePack.vue';
import GithubService from '../services/github';

export default {
  components: { IssuePack },
  route: {
    data: function (transition) {
      var profile = JSON.parse(localStorage.getItem('profile'));

      var github = new GithubService({
        profile: profile
      });

      var officialPacksPromise = github.getIssuePacks(this.pack_url)
          .then(function (packs) {

            packs.forEach(function (pack) {
              var parsed = YAML.parse(pack);

              parsed.listPriority = 0;
              parsed.label = "Official GovReady Issue Pack";
              parsed.copyable = true;
              this.searchPacks.push(parsed);
            }.bind(this));

            return packs;
          }.bind(this));

      var packPromise = this.$http.get('/api/packs/search').then(
          (response) => {
            var packs = response.data;
            packs.forEach(function (pack) {
              pack.label = "Owned by " + pack.user.name;
              pack.listPriority = 1;
              this.searchPacks.push(pack);
            }.bind(this));

            return response.data;
          },
          (err) => console.error(err));

      return packPromise;
    }
  },
  data: function () {
    return {
      searchPacks: [],
      pack_url: "https://api.github.com/repos/govready/issue-packs/contents/examples"
    };
  },
  events: {
    'copy-pack': function (pack) {
      var copyPromise = this.$http.post('/api/packs/' + pack.id + '/copy')
        .then(function (response) {
          // var pack = response.data;
          // pack.label = "Pack Copied";
          // this.searchPacks.push(pack);

          this.$dispatch('new-alert', {message: 'Pack copied successfully.', type: 'success'});
        }.bind(this), function (error) {
          console.error(error);
        });
    }
  }
}
</script>
