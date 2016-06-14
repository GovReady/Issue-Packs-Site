<template>
  <div class="redmine-settings">
    <div class="connection-header">
      <img src="/img/redmine.png">
      <h4>Redmine</h4>
    </div>
    <div class="connection-body">
      <validator name="validation" v-if="connection === undefined">
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
      <div class="connection-installed" v-if="connection !== undefined">
        <div class="connection-settings">
          <h4>Connection Installed:</h4>
          <p>URL: {{ connection.url }}</p>
        </div>
        <button class="btn btn-danger delete-connection" v-on:click="deleteConnection()">Delete Connection</button>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="instructions">
      <h5>
        Setting Up Redmine API
        <a v-on:click="showInstructions = !showInstructions">
          <small>
            Show
            <span v-if="!showInstructions">More</span>
            <span v-if="showInstructions">Less</span>
          </small>
        </a>
      </h3>

      <div class="instructions-body" v-bind:class="{ 'active': showInstructions }">
        <ol>
          <li>Go to your Redmine instance and copy the URL <em>(eg. http://redmine.example.com)</em></li>
          <li>Paste the URL in the form above</li>
          <li>In your Redmine instance, click on 'My Account' in the upper right corner</li>
          <li>On the right-hand side, look for 'API access key' and click 'Show'<br><small class="tooltip_trigger"><i class="fa fa-question-circle-o"></i>Don't see 'API Access Key'
          <div class="tooltip">Your Administrator may not have enabled the REST API.  Please tell your Administrator to go to Adminstration > Settings > Authentication and check 'Enable REST web service' on your Redmine instance.</div></small></li>
          <li>Copy the access key and paste it in the form above</li>
          <li>Click 'Save Connection'</li>
        </ol>
      </div>
    </div>
  </div>
</template>
<script>
  export default {
    props: ['connection'],
    data: () => {
      return {
        url: '',
        token: '',
        showInstructions: false
      };
    },
    methods: {
      saveConnection: function () {
        this.$dispatch('save-connection', {
          provider: 'redmine',
          url: this.url,
          token: this.token
        });
      },
      deleteConnection: function () {
        this.$dispatch('delete-connection', this.connection);
      }
    },
    validators: {
      url: function (val) {
        return /^(http\:\/\/|https\:\/\/)(.{4,})$/.test(val);
      }
    }
  }
</script>
