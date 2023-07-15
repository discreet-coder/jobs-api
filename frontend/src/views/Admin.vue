<template>
  <div class="container">
    <header class="jumbotron">
      <h3><pre>{{content}}</pre></h3>
    </header>
  </div>
</template>

<script>

import JobApplicationService from '../services/job-application.service';

export default {
  name: 'Admin',
  data() {
    return {
      content: ''
    };
  },
  mounted() {
    JobApplicationService.allApplications().then(
      response => {
        this.content = response.data.result;
      },
      error => {
        this.content =
          (error.response && error.response.data && error.response.data.message) ||
          error.message ||
          error.toString();
      }
    );
  }
};

</script>
