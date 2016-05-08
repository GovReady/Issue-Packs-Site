import Github from 'github';

export default class GithubService {
  constructor () {
    console.log('constructing');

    console.log(Github);
    var github = new Github({
      version: "3.0.0"
    });

    this.github = github;
  }

  getOrgs(user) {
    console.log(user);
    console.log(this);

    return new Promise(function (resolve, reject) {
      this.github.user.getOrgs({
        user: user
      }, function (err, res) {
        console.log(err, res);
        resolve(res);
      });
    });
  }
}
