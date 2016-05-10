export default class GithubService {
  constructor (options) {
    console.log('constructing');
    this.profile = options.profile;
    this.id_token = options.id_token;
  }

  getSecretThing () {
      var jwtHeader = { 'Authorization': 'Bearer ' + localStorage.getItem('id_token') };

      this.$http.get('http://localhost:3001/secured/ping',{}, {
        // Send the JWT as a header
        headers: jwtHeader
      }).then(
        //successfull callback
        (response) => {
          // Handle data returned
          console.log(response.data);
        },
        //error callback
        (err) => console.log(err));
    }
  }

  getRepos() {
    return this.$http.get(repos_url + '?token=' + this.id_token)
      .then(function (repos) {
        console.log(repos.data);
        return {
          repos: repos.data
        };
      });
  }
}
