import _ from 'underscore';
import Github from 'github-api';
import async from 'async';
import http from 'axios';

//Get repos for the organization
function getRepos (org, callback) {
  http.get(org.repos_url).then(function (res) {
    callback(res.data);
  })
}

//Get organization information
function getOrg(org, callback) {
  http.get(org.url)
    .then(function (res) {
      var org = res.data;
      org.show = false;

      getRepos(org, function (repos) {
        org.repos = repos;

        callback(null, org);
      });
    });
}

export default class GithubService {
  constructor (options) {
    this.profile = options.profile;

    var github_identity = _.findWhere(this.profile.identities, {provider: "github"});
    this.github_token = github_identity.access_token;
    http.defaults.headers.common['Authorization'] = 'token ' + this.github_token;
  }

  /**
   *  getOrgs()
   *
   *  Returns promise with all organizations (public & private ) with repo list attached
   */
  getOrgs(cb) {
    var orgPromises = [];

    var github = new Github({
      token: this.github_token
    });

    var user = github.getUser(this.profile.nickname);

    return new Promise(function (resolve, reject) {
      http.get('https://api.github.com/user/orgs')
        .then(function (res) {
          var partOrgs = res.data;

          async.map(partOrgs, getOrg, function (err, results) {
            resolve(results);
          });
        });
    });

  }

  getRepos() {
    return Vue.http.get(repos_url + '?token=' + this.id_token)
      .then(function (repos) {
        console.log(repos.data);
        return {
          repos: repos.data
        };
      });
  }
}
