<template>
<div class="repo-dashboard">
  <h2>{{ repo.name }}</h2>
  <span>{{ repo.description }}</span>
</div>
</template>
<script>
  export default {
    props: ['repo'],
    ready () {

    }
  }
</script>
