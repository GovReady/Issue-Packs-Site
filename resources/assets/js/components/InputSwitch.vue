<template>
  <label class="pack-public">
    <input type="checkbox" class="input-switch">
    <span class="input-switch-control" v-bind:class="{'selected': selected }" v-on:click="toggle()">
      <small class="input-switch-indicator"></small>
    </span>
    Public Pack
  </label>
</template>
<script>
  export default {
    props: [ 'id', 'selected'],
    data: () => {
      return {

      }
    },
    methods: {
      toggle: function () {
        this.selected = !this.selected;
        this.$dispatch('input-toggled', this.selected);
      }
    }
  }
</script>
