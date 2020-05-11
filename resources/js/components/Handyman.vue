<template>
  <div class="handymanWrap">
    <div v-if="notActive">Активных работ нет</div>
    <div v-else class="row">
      <div class="col-md-2 text-center vAlign">Список объектов</div>
      <div class="col-md-2">
        <select class="form-control" v-model="selectedTask">
          <option v-for="task in tasks" :key="task.id" :value="task">{{task.work.name}}</option>
        </select>
      </div>
      <div class="col-md-8"></div>
    </div>
    <Task
      v-if="selectedTask != null"
      :work="selectedTask.work"
      :task="selectedTask"
      :isAdmin="false"
    />
  </div>
</template>

<script>
import Task from "./Task";

export default {
  components: { Task },
  data() {
    return {
      tasks: [],
      selectedTask: null,
      notActive: false
    };
  },
  methods: {
    getListTasksByUser() {
      this.$http
        .get(this.$store.state.host + "api/getListTasksByUser", {
          params: {
            api_token: this.$store.state.userApi
          }
        })
        .then(function (response) {
          this.tasks = response.data;
          if (this.tasks.length > 0) this.selectedTask = this.tasks[0];
          else
            this.notActive = true;
        });
    }
  },
  mounted() {
    this.$store.state.userRole = 3;
    this.$store.state.userApi = $('meta[name="api-token"]').attr("content");
    this.getListTasksByUser();
  }
};
</script>

<style scoped>
.handymanWrap {
  margin-top: 15px;
}
</style>