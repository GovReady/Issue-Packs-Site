# Issue-Packs-Site
**This site is currently in Alpha**

Issue Pack marketplace for [issue-pack](https://www.npmjs.com/package/issue-pack)

## Issue Packs

The Official issue packs on the site currently pull from [the issue-pack examples directory](https://github.com/GovReady/Issue-Packs/tree/master/examples).

## Issue Pack Sync

The site uses Auth0's Lock to log in a user and interact with the application.  Currently, Github and Redmine are supported.

## Colophon

The site is built on Laravel 5.2, which currently serves a VueJS application.  All front-end source files are located in the `/resources/assets` directory and are built using the `gulp` command.  The application entrypoint is `/resources/assets/app.js`, and the SASS build entrypoint is `/resources/assets/sass/app.scss`.  For development purposes, `gulp watch` can also be used to watch the source files and automatically rebuild them when a file is changed.  The theme for the dashboard has been pulled from [Gentelella](https://github.com/puikinsh/gentelella) and included / overridden in the `resources/assets` directory.

* [Laravel](https://laravel.com/docs/5.2)
* [Auth0](https://auth0.com/)
* [VueJS](https://vuejs.org/)
* [Laravel-Elixir](https://laravel.com/docs/5.2/elixir)
  * [Vueify](https://github.com/vuejs/vueify)
  * [BabelJS](https://babeljs.io/)
  * [SASS](http://sass-lang.com/)

## Development

This repo comes with a [Laravel Homestead](https://laravel.com/docs/5.2/homestead) vagrant setup included.  Follow the instructions in the [Homestead documentation](https://laravel.com/docs/5.2/homestead) to get a development environment up and running.
