<template>
  <div class="my-packs">
    <issue-pack v-for="pack in myPacks" :pack="pack"></issue-pack>
  </div>
</template>
<script>
import IssuePack from './IssuePack.vue';

export default {
  components: { IssuePack },
  route: {
    data: function (transition) {
      var jwtHeader = { 'Authorization': 'Bearer ' + localStorage.getItem('id_token') };
      var packPromise = this.$http.get('/api/my-packs',{}, {
          headers: jwtHeader
        }).then(
          (response) => {
            this.myPacks = response.data;
            return response.data;
          },
          (err) => console.error(err));

      return packPromise;
    }
  },
  data: function () {
    return {
      myPacks: []
    };
  }
}
</script>
