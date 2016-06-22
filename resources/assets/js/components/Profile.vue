<template>
  <div class="profile">
    <h3>{{ profile.name }}</h3>
  </div>
</template>
<script>
  export default {
    data: function () {
      return {
        profile: {}
      };
    },
    route: {
      data: function (transition) {
        var profilePromise = this.$http.get('/api/users/' + transition.to.params.id)
          .then(function (response) {
            console.log(response);

            return {
              profile: response.data
            };
          }, function (error) {
            console.error(error);
          });

        return profilePromise;
      }
    }
  }
</script>
