<template>
  <div class="Alerts">
    <alert v-for="alert in alerts" :message="alert.message" :type="alert.type"></alert>
  </div>
</template>

<script>
  import Alert from './Alert.vue';

  export default {
    props: ['alerts'],
    components: { Alert }
  }
</script>
