<template>
  <ul>
    <li v-for="repo in repos">
      {{ repo }}
    </li>
  </ul>
</template>
<script>
  import Repo from './Repo.vue'

  export default {
    props: ['repos'],
    components: { Repo }
  }
</script>
