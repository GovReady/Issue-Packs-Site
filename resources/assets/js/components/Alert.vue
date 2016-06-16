  <template>
  <div v-show="show" class="ui-pnotify Alert">
    <div class="alert ui-pnotify-container alert-dark ui-pnotify-shadow" style="min-height: 16px; overflow: hidden;">
        <div class="ui-pnotify-closer" style="cursor: pointer;" @click="show = false">
          <span class="glyphicon glyphicon-remove" title="Close"></span>
        </div>
        <div class="ui-pnotify-icon"><span class="glyphicon glyphicon-bullhorn"></span></div>
        <h4 class="ui-pnotify-title">Notification</h4>
        <div class="ui-pnotify-text">{{ message }}</div>
    </div>
</div>

</template>

<script>
  export default {
    props: ['type', 'message'],

    ready () {
      //Set timeout to 3 secounds
      if(this._timeout) {
        clearTimeout(this._timeout);
      }
      this._timeout = setTimeout(() => this.show = false, 3600);
    },

    data () {
      return {
        show: true
      };
    },

    computed: {
      alertClasses () {
        var type = this.type;

        return {
          'Alert': true,
          'Alert--Success': type == 'success',
          'Alert--Error': type == 'error'
        };
      }
    }
  };
</script>
