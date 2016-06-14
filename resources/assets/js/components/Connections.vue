<template>
  <div class="connections">
    <ul>
      <li class="connection">
        <redmine-settings></redmine-settings>
      </li>
    </ul>
  </div>
</template>
<script>
import RedmineSettings from './RedmineSettings.vue';

export default {
  components: { RedmineSettings },
  data: () => {
    return {
      connections: ['redmine']
    }
  }
}
</script>
