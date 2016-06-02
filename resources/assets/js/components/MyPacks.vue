<template>
  <div class="my-packs">
    <issue-pack v-for="pack in myPacks" :pack="pack" type="manage"></issue-pack>
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
  </div>
</template>
<script>
import FileUpload from './FileUpload.vue';
import IssuePack from './IssuePack.vue';

import _ from 'underscore';

export default {
  components: { FileUpload, IssuePack },
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
  },
  events: {
    'create-pack': function (pack) {
      var parsed = YAML.parse(pack);
      this.$http.post('/api/packs', {pack: parsed})
        .then(function (response) {
          this.$dispatch('new-alert', {message: 'Pack uploaded successfully.', type: 'success'});
          this.myPacks.push(response.data);
        }.bind(this), function (error) {
          console.error(error);
        });
    },
    'delete-pack': function (pack) {
      console.log("Deleting id: ", pack.id);
      this.myPacks.splice(_.indexOf(this.myPacks, _.findWhere(this.MyPacks, {id: pack.id})), 1);
    }
  }
}
</script>
