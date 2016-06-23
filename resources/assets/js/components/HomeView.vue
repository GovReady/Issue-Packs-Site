<template>
<div class="">
  <a class="hiddenanchor" id="toregister"></a>
  <a class="hiddenanchor" id="tologin"></a>
  <div class="login_header">

    <h1><i class="fa fa-folder-open header_icon"></i>Issue Packs</h1>
    <div class="header_right">
      <em>Brought To You By</em>
      <h2>GovReady</h2>
    </div>
    <div class="clearfix"></div>
  </div>
  <div id="wrapper">
    <login></login>
    <div class="separator">
        <div class="clearfix"></div>
      </div>
  </div>
  <section class="login_supported">
    <strong>Collaborate with:</strong>
    <br>
    <div class="supported_platform">
      <img src="/img/github.png" alt="GitHub" class="img-responsive">
    </div>
    <div class="supported_platform">
      <img src="/img/redmine-banner.png" alt="Redmine" class="img-responsive">
    </div>
    <div class="supported_platform">
      <span class="coming-soon-overlay">Coming Soon!</span>
      <img src="/img/jira.svg" alt="Jira" class="img-responsive">
    </div>

  </section>
</div>
</template>

<script>
import Login from './Login.vue';

export default {
  components: { Login }
}

</script>
