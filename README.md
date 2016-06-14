# Issue-Packs-Site
**This site is currently in Alpha**

Issue Pack marketplace for [issue-pack](https://www.npmjs.com/package/issue-pack)

## Issue Packs

The issue packs on the site currently pull from [the issue-pack examples directory](https://github.com/GovReady/Issue-Packs/tree/master/examples).

_Users can also upload issue packs of their own, but they are currently only used as a sync tool and are not stored on the site._

## Github Sync

The site uses Auth0's Lock to log in a user and interact with Github.  Currently, the site is front-end only so there is no information stored but the packs can be synced to Github while the user is logged in.

## Colophon

The site is built on Laravel 5.2, which currently serves a VueJS application.  All front-end source files are located in the `/resources/assets` directory and are built using the `gulp` command.  For development purposes, `gulp watch` can also be used to watch the source files and automatically rebuild them when a file is changed.  The theme for the dashboard has been pulled from [Gentelella](https://github.com/puikinsh/gentelella) and included / overridden in the `resources/assets` directory.

* [Laravel](https://laravel.com/docs/5.2)
* [Auth0](https://auth0.com/)
* [VueJS](https://vuejs.org/)
* [Laravel-Elixir](https://laravel.com/docs/5.2/elixir)
  * [Vueify](https://github.com/vuejs/vueify)
  * [BabelJS](https://babeljs.io/)
  * [SASS](http://sass-lang.com/)

