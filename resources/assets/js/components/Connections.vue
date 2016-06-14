<template>
  <div class="connections">
    <ul>
      <li class="connection">
        <redmine-settings :connection="redmineConnection"></redmine-settings>
      </li>
    </ul>
  </div>
</template>
<script>
import RedmineSettings from './RedmineSettings.vue';
import _ from 'underscore';

export default {
  components: { RedmineSettings },
  route: {
    data: function (transition) {
      var connectionPromise = this.$http.get('/api/connections').then(function (response) {
        var connections = response.data;

        connections.forEach(function (connection) {
          this.connections.push(connection);
        }.bind(this));
      }.bind(this), function (error) {
        console.error(error);
      });

      return connectionPromise;
    }
  },
  data: () => {
    return {
      connections: []
    }
  },
  computed: {
    redmineConnection: function () {
      return _.findWhere(this.connections, {provider: 'redmine'});
    }
  },
  events: {
    'save-connection': function (connection) {
      this.$http.post('/api/connections', {
        provider: connection.provider,
        url: connection.url,
        access_token: connection.token
      }).then(function (result) {
        console.log(result.data);
        this.$dispatch('new-alert', {
          message: 'Connection Saved',
          type: 'success'
        });
      }, function (error) {
        console.error(error);
      });
      console.log('saving');
      console.log(connection);
    }
  }
}
</script>
