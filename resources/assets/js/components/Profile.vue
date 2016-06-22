<template>
  <div class="profile">
    <h3>{{ profile.name }}</h3>
    <small v-if="packs.length == 0">No public packs found.</small>
    <issue-pack v-for="pack in packs" :pack="pack" type="search"></issue-pack>
  </div>
</template>
<script>
import IssuePack from './IssuePack.vue';

  export default {
    data: function () {
      return {
        profile: {},
        packs: []
      };
    },
    components: { IssuePack },
    route: {
      data: function (transition) {
        var profilePromise = this.$http.get('/api/users/' + transition.to.params.id)
          .then(function (response) {
            return {
              profile: response.data,
              packs: response.data.issue_packs
            };
          }, function (error) {
            console.error(error);
          });

        return profilePromise;
      }
    }
  }
</script>
