<template>
  <div class="redmine-settings">
    <div class="connection-header">
      <img src="/img/redmine.png">
      <h4>Redmine</h4>
    </div>
    <div class="connection-body">
      <validator name="validation">
        <form class="form-horizontal form-label-left" v-on:submit.prevent novalidate>
          <div class="form-group">
            <label for="url" class="control-label connection-label">URL:</label>
            <div class="connection-input">
              <input type="url" class="form-control" v-model="url" placeholder="Redmine URL" name="url" v-validate:url="['required', 'url']">
              <p v-if="$validation.url.required"><small>Required*</small></p>
              <p v-if="url && $validation.url.url"><small>Invalid Site URL</small></p>

            </div>
          </div>
          <div class="form-group">
            <label for="token" class="control-label connection-label">Token:</label>
            <div class="connection-input">
              <input type="text" class="form-control" v-model="token" v-validate:token="['required']" placeholder="Redmine API Token" name="token">
              <p v-if="$validation.token.required"><small>Required*</small></p>
            </div>
          </div>
          <button class="btn btn-primary pull-right" v-on:click="saveConnection()" v-if="$validation.valid">Save Connection</button>
        </form>
      </validator>
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
    },
    validators: {
      url: function (val) {
        return /^(http\:\/\/|https\:\/\/)(.{4,})$/.test(val);
      }
    }
  }
</script>
