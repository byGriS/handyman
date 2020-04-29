<template>
  <div>
    <div class="row">
      <div class="col-md-2 text-center vAlign">Список объектов</div>
      <div class="col-md-2">
        <select class="form-control" v-model="selectedWork">
          <option v-for="work in works" :key="work.id" :value="work">{{work.name}}</option>
        </select>
      </div>
      <div class="col-md-2">
        <button class="btn btn-primary">Добавить объект</button>
      </div>
      <div class="col-md-6"></div>
    </div>
    <div v-if="selectedWork != null">
      <Task :work="selectedWork" :isAdmin="true" v-for="task in tasks" :key="task.id" :task="task" />
    </div>
  </div>
</template>

<script>
import Task from "./Task";

export default {
  components: { Task },
  data() {
    return {
      works: [],
      selectedWork: null,
      tasks: []
    };
  },
  watch: {
    selectedWork() {
      this.$http.get(this.$store.state.host + "api/getListTasks", {
        params: {
          api_token: this.$store.state.userApi,
          work_id: this.selectedWork.id
        }
      })
      .then(function(response){
        this.tasks = response.data;
      });
    }
  },
  methods: {
    getListWorks() {
      this.$http
        .get(this.$store.state.host + "api/getListWorks", {
          params: {
            api_token: this.$store.state.userApi
          }
        })
        .then(function(response) {
          this.works = response.data;
        });
    }
  },
  created() {
    this.getListWorks();
  }
};
</script>