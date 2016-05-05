<template>
  <div class="repo-content">
    <h2>Repos</h2>
    <ul>
      <repo-list></repo-list>
    </ul>
  </div>
</template>
<script>
  import RepoList from './RepoList.vue';

  export default {
    props: ['repos'],
    components: { RepoList }
  }
</script>
