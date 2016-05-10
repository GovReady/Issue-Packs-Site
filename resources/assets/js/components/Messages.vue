<template>
  <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
    <i class="fa fa-envelope-o"></i>
    <span class="badge bg-green">{{ messages.length }}</span>
  </a>
  <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
    <li v-for="message in messages">
      <a>
        <span class="image">
          <img v-bind:src="gravatar" alt="Profile Image" />
        </span>
        <span>
          <span>{{ message.from }}</span>
          <span class="time">3 mins ago</span>
        </span>
        <span class="message">{{ message.body }}</span>
      </a>
    </li>
    <li>
      <div class="text-center">
        <a href="#">
          <strong>See All Alerts</strong>
          <i class="fa fa-angle-right"></i>
        </a>
      </div>
    </li>
  </ul>
</template>
<script>
  export default {
    props: ['gravatar'],
    data () {
      return {
        messages: [
          {
            from: "Chris Birk",
            body: "Hey, did you see this? ..."
          },
          {
            from: "Chris Birk",
            body: "Hey, did you see this? ..."
          },
          {
            from: "Chris Birk",
            body: "Hey, did you see this? ..."
          },
          {
            from: "Chris Birk",
            body: "Hey, did you see this? ..."
          },
          {
            from: "Chris Birk",
            body: "Hey, did you see this? ..."
          }
        ]
      };
    }
  }
</script>
