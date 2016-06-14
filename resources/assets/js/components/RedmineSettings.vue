<template>
  <div class="redmine-settings">
    <div class="connection-header">
      <img src="/img/redmine.png">
      <h4>Redmine</h4>
    </div>
    <div class="connection-body">
      <form class="form-horizontal form-label-left" v-on:submit.prevent>
        <div class="form-group">
          <label for="url" class="control-label connection-label">URL:</label>
          <div class="connection-input">
            <input type="url" class="form-control" v-model="url" placeholder="Redmine URL" name="url">
          </div>
        </div>
        <div class="form-group">
          <label for="token" class="control-label connection-label">Token:</label>
          <div class="connection-input">
            <input type="text" class="form-control" v-model="token" placeholder="Redmine API Token" name="token">
          </div>
        </div>
        <button class="btn btn-primary pull-right" v-on:click="saveConnection()">Save Connection</button>
      </form>
    </div>
  </div>
</template>
<script>
  export default {
    data: () => {
      return {
        url: '',
        token: ''
      };
    },
    methods: {
      saveConnection: function () {
        console.log('saving');
      }
    }
  }
</script>
