<template>
  <div class="search-packs">
    <issue-pack v-for="pack in searchPacks" :pack="pack" type="search"></issue-pack>
  </div>
</template>
<script>
import IssuePack from './IssuePack.vue';

export default {
  components: { IssuePack },
  route: {
    data: function (transition) {
      var packPromise = this.$http.get('/api/packs/search').then(
          (response) => {
            var packs = response.data;
            packs.forEach(function (pack) {
              pack.label = "Owned by " + pack.user.name;
            });

            this.searchPacks = packs;
            return response.data;
          },
          (err) => console.error(err));

      return packPromise;
    }
  },
  data: function () {
    return {
      searchPacks: []
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
