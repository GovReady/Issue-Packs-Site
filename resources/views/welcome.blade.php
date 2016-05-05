<!DOCTYPE html>
<html>
    <head>
      <title>Issue Packs</title>
      <link rel="stylesheet" href="/css/app.css">
      <script src="//cdn.auth0.com/js/lock-9.0.min.js"></script>
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    </head>
    <body class="bg-faded">
      <div id="app" class="main">
        <alerts :alerts.sync="alerts"></alerts>
        <div class="main-content">
          <div class="inner">
            <h2>Issue Packs</h2>
            <button class="btn btn-primary btn-lg" v-on:click="login()">Log in with Github</button>
          </div>
        </div>
      </div>
      <script src="/js/app.js"></script>
    </body>
</html>
