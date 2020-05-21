<template>
  <div>
    <div class="row">
      <div class="col-md-5 vAlign text-center">Название объекта</div>
      <div class="col-md-2 vAlign text-center">Дата завершения</div>
      <div class="col-md-2 vAlign text-center">Кол-во задач</div>
      <div class="col-md-3 text-center"></div>
    </div>
    <div class="completedWork" v-for="work in works" :key="work.id">
      <div class="row">
        <div class="col-md-5 vAlign">{{work.name}}</div>
        <div class="col-md-2 vAlign text-center">{{work.updated_at}}</div>
        <div class="col-md-2 vAlign text-center">{{work.tasks.length}}</div>
        <div class="col-md-3 text-center">
          <button v-if="isAdmin" class="btn btn-primary" @click="restoreWork(work.id)">Восстановить объект</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      works: []
    }
  },
  computed: {
    isAdmin() {
      return this.$store.state.userRole == 1;
    }
  },
  methods: {
    getListWorks() {
      this.$http
        .get(this.$store.state.host + "api/getCompletedWorks", {
          params: {
            api_token: this.$store.state.userApi,
          }
        })
        .then(function (response) {
          this.works = response.data;
          this.works.forEach(element => {
            element.updated_at = element.updated_at.substr(0, 10);
          });
        })
    },
    restoreWork(workId) {
      this.$http
        .post(this.$store.state.host + "api/restoreWork", {
          api_token: this.$store.state.userApi,
          id: workId
        })
        .then(() => {
          this.getListWorks();
        })
    }
  },
  created() {
    this.getListWorks();
  }
}
</script>

<style scoped>
.completedWork {
  margin: 5px 0;
  padding: 5px 15px;
  background: rgb(246, 245, 245);
  border: 1px solid rgb(233, 233, 233);
  border-radius: 6px;
}
</style>