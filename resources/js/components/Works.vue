<template>
  <div>
    <div class="row">
      <div class="col-md-2 text-center vAlign">Список объектов</div>
      <div class="col-md-2">
        <select class="form-control" v-model="selectedWork">
          <option v-for="work in works" :key="work.id" :value="work">{{work.name}}</option>
        </select>
      </div>
      <div class="col-md-8 d-flex" v-if="this.$store.state.userRole == 1">
        <button class="btn btn-primary marginR15" @click="isAddNewWork = true">Добавить объект</button>
        <button
          v-if="taskCompletable"
          class="btn btn-success marginR15"
          @click="completeWork()"
        >Завершить объект</button>
        <button
          v-if="selectedWork != null"
          class="btn btn-danger"
          @click="deleteWork()"
        >Удалить объект</button>
      </div>
      <div v-else class="col-md-8"/>
      <div class="col-md-6"></div>
    </div>
    <div class="newWorkWrap" v-if="isAddNewWork">
      <label>Название объекта:</label>
      <input class="form-control" v-model="newWorkTitle" />
      <button class="btn btn-primary marginT15" @click="addWork()">Добавить</button>
      <button
        class="btn btn-secondary marginT15"
        @click="newWorkTitle=''; isAddNewWork = false"
      >Отмена</button>
    </div>
    <div class="marginT15" v-if="selectedWork != null">
      <Task
        :work="selectedWork"
        v-for="task in tasks"
        :key="task.id"
        :task="task"
        @refresh="refresh()"
      />
      <button v-if="this.$store.state.userRole == 1" class="btn btn-info marginT15" @click="isNewTask = true">Добавить работу</button>
      <NewTask
        v-if="isNewTask"
        :work="selectedWork"
        @cancel="isNewTask = false"
        @added="refresh()"
      />
      <Task
        :work="selectedWork"
        v-for="task in completedTasks"
        :key="task.id"
        :task="task"
        @refresh="refresh()"
      />
    </div>
  </div>
</template>

<script>
import Task from "./Task";
import NewTask from "./NewTask";

export default {
  components: { Task, NewTask },
  data() {
    return {
      isCompleted: false,
      works: [],
      selectedWork: null,
      tasks: [],
      completedTasks: [],
      isNewTask: false,
      isAddNewWork: false,
      newWorkTitle: ""
    };
  },
  computed: {
    taskCompletable() {
      if (this.selectedWork != null) {
        let result = true;
        this.tasks.forEach(function (elem) {
          if (elem.status == 1) {
            result = false;
          }
        })
        return result;
      } else {
        return false;
      }
    }
  },
  watch: {
    selectedWork() {
      if (this.selectedWork != null)
        this.refresh();
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
        .then(function (response) {
          this.works = response.data;
          
          
        });
    },
    refresh() {
      this.isNewTask = false
      this.$http.get(this.$store.state.host + "api/getListTasks", {
        params: {
          api_token: this.$store.state.userApi,
          work_id: this.selectedWork.id
        }
      })
        .then(function (response) {
          this.tasks = [];
          this.completedTasks = [];
          response.data.forEach(task => {
            if (task.status == 1)
              this.tasks.push(task);
            else{
              task.updated_at = task.updated_at.substr(0, 10);
              this.completedTasks.push(task);
            }
          });
          //this.tasks = response.data;
        });
    },
    addWork() {
      this.$http
        .post(this.$store.state.host + "api/addWork", {
          api_token: this.$store.state.userApi,
          title: this.newWorkTitle
        })
        .then(function (response) {
          this.newWorkTitle = "";
          this.isAddNewWork = false;
          this.getListWorks();
        });
    },
    deleteWork() {
      if (confirm("Точно удалить работу?")) {
        this.$http
          .post(this.$store.state.host + "api/deleteWork", {
            api_token: this.$store.state.userApi,
            id: this.selectedWork.id
          })
          .then(function (response) {
            this.selectedWork = null;
            this.getListWorks();
          });
      }
    },
    completeWork() {
      this.$http
        .post(this.$store.state.host + "api/completeWork", {
          api_token: this.$store.state.userApi,
          id: this.selectedWork.id
        })
        .then(function (response) {
          this.selectedWork = null;
          this.getListWorks();
        });
    }
  },
  created() {
    this.getListWorks();
  }
};
</script>

<style scoped>
.newWorkWrap {
  background: rgb(232, 246, 255);
  border: 1px solid rgb(240, 239, 239);
  border-radius: 10px;
  padding: 12px 20px;
  margin-top: 10px;
}
</style>>

