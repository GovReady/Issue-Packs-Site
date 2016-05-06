<template>
<div class="">
  <a class="hiddenanchor" id="toregister"></a>
  <a class="hiddenanchor" id="tologin"></a>

  <div id="wrapper">
    <login></login>
  </div>
</div>
</template>

<script>
import Login from './Login.vue';

export default {
  components: { Login }
}

</script>
