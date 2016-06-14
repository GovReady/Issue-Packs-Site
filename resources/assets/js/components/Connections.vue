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
        var connection = result.data;
        this.connections.push(connection);

        this.$dispatch('new-alert', {
          message: 'Connection Saved',
          type: 'success'
        });

        this.$dispatch('redmine-connected');
      }, function (error) {
        console.error(error);
      });
    },
    'delete-connection': function (connection) {
      this.$http.delete('/api/connections/' + connection.id)
        .then(function (result) {
          this.connections.splice(_.indexOf(this.connections, _.findWhere(this.connections, {id: connection.id})), 1);

          this.$dispatch('new-alert', {
            message: 'Connection Deleted',
            type: 'success'
          });

          this.$dispatch('redmine-deleted');
        }, function (error) {
          console.error(error);
        });
    }
  }
}
</script>
