<template>
<div class="">
  <a class="hiddenanchor" id="toregister"></a>
  <a class="hiddenanchor" id="tologin"></a>
  <div id="wrapper">
    <login></login>
  </div>
  <section class="login_supported">
    <strong>Collaborate with:</strong>
    <br>
    <img src="/img/github.png" alt="GitHub" class="supported_platform">
    <img src="/img/redmine-banner.png" alt="Redmine" class="supported_platform">
    <img src="/img/jira.svg" alt="Jira" class="supported_platform">
  </section>
</div>
</template>

<script>
import Login from './Login.vue';

export default {
  components: { Login }
}

</script>
