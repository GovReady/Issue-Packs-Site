# Issue-Packs-Site
**This site is currently in Alpha**

Issue Pack marketplace for [issue-pack](https://www.npmjs.com/package/issue-pack)

## Issue Packs

The Official issue packs on the site currently pull from [the issue-pack examples directory](https://github.com/GovReady/Issue-Packs/tree/master/examples).

## Issue Pack Sync

The site uses Auth0's Lock to log in a user and interact with the application.  Currently, Github and Redmine are supported.

## Colophon

The site is built on Laravel 5.2, which currently serves a VueJS application.  All front-end source files are located in the `/resources/assets` directory and are built using the `gulp` command.  The application entrypoint is `/resources/assets/app.js`, and the SASS build entrypoint is `/resources/assets/sass/app.scss`.  For development purposes, `gulp watch` can also be used to watch the source files and automatically rebuild them when a file is changed.  The theme for the dashboard has been pulled from [Gentelella](https://github.com/puikinsh/gentelella) and included / overridden in the `resources/assets` directory.  The production files that are served can be found in the `public/` directory.

* [Laravel](https://laravel.com/docs/5.2)
* [Auth0](https://auth0.com/)
* [VueJS](https://vuejs.org/)
* [Laravel-Elixir](https://laravel.com/docs/5.2/elixir)
  * [Vueify](https://github.com/vuejs/vueify)
  * [BabelJS](https://babeljs.io/)
  * [SASS](http://sass-lang.com/)

_If you haven't worked with VueJS in a Laravel environment, Laracasts has a [great explainer video](https://laracasts.com/series/learning-vuejs/episodes/9) on working with VueJS and Laravel's Elixir_

## Development

### Local Setup

The site front-end application is bundled into `public/js/app.js` and `public/css/app.css` in this repository.  This means that to set up your own version of the site you simply need to set up the Laravel application by following [Laravel's Installation Guide](https://laravel.com/docs/5.2), but we've included the quick steps below:

1.  Create a MySQL database and user.
1.  Copy `.env.example` to `.env` and set the necessary configuration attributes
1.  Install [Composer](https://getcomposer.org/) and run `composer install` from the root directory to pull in all Laravel dependencies
1.  Set up your webserver ( Apache / Nginx ) vhost file to point to the `/public` directory and restart your webserver

**If you want to actively develop for the site, you will need to run `gulp build` after changing JS / SASS files in the `resources/assets` directory to rebuild the front-end production files**

_You can also serve the site quickly using the `gulp watch` command.  This will set up browserify as well to watch and reload files as they change_

### Vagrant Setup
This repo comes with a [Laravel Homestead](https://laravel.com/docs/5.2/homestead) vagrant setup included.  Follow the instructions in the [Homestead documentation](https://laravel.com/docs/5.2/homestead) to get a development environment up and running.

## Questions

If you have any questions on the site at all, please don't hesitate to [open an issue](https://github.com/GovReady/Issue-Packs-Site/issues/new).  We monitor the issue log carefully and will make sure to respond to any inquiries as quickly as possible!
