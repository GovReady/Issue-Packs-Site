import _ from 'underscore';
import Github from 'github-api';
import async from 'async';
import axios from 'axios';

var http;

//Get repos for the organization
function getRepos (org, callback) {
  http.get(org.repos_url + '?per_page=100').then(function (res) {
    callback(res.data);
  });
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

//Get an issue pack contents
function getPack(pack, callback) {
  http.get(pack.download_url, { dropAuthHeader: true })
    .then(function (res) {
      callback(null, res.data);
    });
}

export default class GithubService {
  constructor (options) {
    this.profile = options.profile;

    var github_identity = _.findWhere(this.profile.identities, {provider: "github"});
    this.github_token = github_identity.access_token;

    //Set default Authorization header for auth'ed requests
    axios.defaults.headers.common['Authorization'] = 'token ' + this.github_token;

    //Create an axios instance
    http = axios.create();

    //Set up interceptor to remove Authorization header on file content requests
    http.interceptors.request.use(function (config) {
      if (config.dropAuthHeader) {
        delete config.headers.Authorization;
      }

      return config;
    }, function (error) {
      console.error(error);
    });
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
      http.get('https://api.github.com/user/orgs?per_page=100')
        .then(function (res) {
          var partOrgs = res.data;

          async.map(partOrgs, getOrg, function (err, results) {
            resolve(results);
          });
        });
    });

  }

  getIssuePacks(url) {
    return new Promise(function (resolve, reject) {
      http.get(url)
      .then(function (res) {
        var packs = res.data;

        async.map(packs, getPack, function (err, results) {
          resolve(results);
        });
      });
    });
  }

  getMilestones (repo, cb) {
    return http.get('https://api.github.com/repos/' + repo + '/milestones')
      .then(function (res) {
        cb(res.data);
      });
  }
}
