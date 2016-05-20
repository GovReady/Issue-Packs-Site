<template>
  <div class="dropzone">
    <div v-if="!uploadedPack">
      <div class="dropzone-area" drag-over="handleDragOver" @dragenter="hovering = true" @dragleave="hovering = false" :class"{'hovered': hovering}">
        <span class="dropzone-text">Drop issue pack here or click to select</span>
        <input type="file" @change="onFileChange">
      </div>
    </div>
    <div class="uploadedPack" v-if="uploadedPack">

    </div>
  </div>
</template>
<script>
  export default {
    data() {
      return {
        file: '',
        hovering: false
      }
    },
    methods: {
      onFileChange(e) {
        var files = e.target.files || e.dataTransfer.files;
        if(!files.length) {
          return;
        }

        this.createPack(files[0])
      },
      createPack(file) {
        var reader = new FileReader();
        var contents = reader.readAsText(file);
        reader.onload = function (e) {
          this.$dispatch('create-pack', e.target.result);
        }.bind(this);
      }
    }
  }
</script>
