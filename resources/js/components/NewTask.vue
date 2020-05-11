<template>
  <div class="taskWrap">
    <div class="d-flex justify-content-between">
      <h3>Новая работа</h3>
      <button class="btn btn-danger" @click="cancel()">Отмена</button>
    </div>
    <div class="row">
      <div class="col-lg-5">
        <div class="row lineData">
          <div class="col-md-6">Исполнитель</div>
          <div class="col-md-6">
            <select class="form-control" v-model="newTask.handyman">
              <option
                v-for="handyman in handymans"
                :key="handyman.id"
                :value="handyman.id"
              >{{handyman.name}}</option>
            </select>
          </div>
        </div>

        <div class="row lineData">
          <div class="col-md-6">Тип работ</div>
          <div class="col-md-6">
            <select class="form-control" v-model="newTask.type">
              <option selected value="1">Кладка</option>
              <option value="2">Штукатурка</option>
            </select>
          </div>
        </div>

        <div class="row lineData">
          <div class="col-md-6">Необходимый объем работ</div>
          <div class="col-md-6">
            <input class="form-control" v-model="newTask.capacity" />
          </div>
        </div>

        <div class="row lineData">
          <div class="col-md-6">Норматив объем работ/чел/сут</div>
          <div class="col-md-6">
            <input class="form-control" v-model="newTask.standartPeople" />
          </div>
        </div>

        <div class="row lineData">
          <div class="col-md-6">Норматив раствор/объем работ</div>
          <div class="col-md-6">
            <input class="form-control" v-model="newTask.standartConsumption" />
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="calendarWrap">
          <div>Срок выполнения работы</div>
          <Calendar v-model="newTask.end" :disabledDates="{to: today}" />
        </div>
      </div>
      <div class="col-lg-3" v-if="hide">
        <div>Выходные дни</div>
        <div class="form-check">
          <input type="checkbox" id="exampleCheck1" v-model="newTask.holiday" value="1" />
          <label for="exampleCheck1">Пн</label>
        </div>
        <div class="form-check">
          <input type="checkbox" id="exampleCheck2" v-model="newTask.holiday" value="2" />
          <label for="exampleCheck2">Вт</label>
        </div>
        <div class="form-check">
          <input type="checkbox" id="exampleCheck3" v-model="newTask.holiday" value="3" />
          <label for="exampleCheck3">Ср</label>
        </div>
        <div class="form-check">
          <input type="checkbox" id="exampleCheck4" v-model="newTask.holiday" value="4" />
          <label for="exampleCheck4">Чт</label>
        </div>
        <div class="form-check">
          <input type="checkbox" id="exampleCheck5" v-model="newTask.holiday" value="5" />
          <label for="exampleCheck5">Пт</label>
        </div>
        <div class="form-check">
          <input type="checkbox" id="exampleCheck6" v-model="newTask.holiday" value="6" />
          <label for="exampleCheck6">Сб</label>
        </div>
        <div class="form-check">
          <input type="checkbox" id="exampleCheck7" v-model="newTask.holiday" value="7" />
          <label for="exampleCheck7">Вс</label>
        </div>
      </div>
    </div>
    <button class="btn btn-success" @click="addTask()">Добавить</button>
  </div>
</template>

<script>
import Calendar from "vuelendar/components/vl-day-selector";

export default {
  components: { Calendar },
  props: ["work"],
  data() {
    return {
      newTask: {
        type: 1,
        capacity: 100,
        standartPeople: 1,
        standartConsumption: 1,
        holiday: [],
        end: null,
      },
      handymans: [],
      today: null,
      hide: false
    };
  },
  computed: {

  },
  watch: {

  },
  methods: {
    getListHandymans() {
      this.$http
        .get(this.$store.state.host + "api/getListHandymans", {
          params: {
            api_token: this.$store.state.userApi,
          }
        })
        .then(function (response) {
          this.handymans = response.data;
        });
    },
    cancel() {
      this.$emit('cancel');
    },
    addTask() {
      if (this.newTask.handyman == null || this.newTask.handymans < 1) {
        alert("Не выбран исполнитель");
        return;
      }
      this.$http
        .post(this.$store.state.host + "api/addTask", {
          api_token: this.$store.state.userApi,
          newTask: this.newTask
        })
        .then(function (response) {
          this.$emit('added');
        })
    }
  },
  created() {
    this.newTask.end = this.$moment().add(1, "day").format("YYYY-MM-DD");
    this.newTask.workId = this.work.id;
    this.today = this.$moment().format("YYYY-MM-DD");
    this.getListHandymans();
  }
};
</script>

<style scoped>
.taskWrap {
  background: rgb(247, 253, 237);
  border: 1px solid rgb(240, 239, 239);
  border-radius: 10px;
  padding: 12px 20px;
  margin-top: 10px;
}
.lineData {
  line-height: 30px;
}
.lineData div {
  margin: auto;
}
.lineData input,
.lineData select {
  margin: 4px;
}
.calendarWrap {
  margin-right: 20px;
  text-align: center;
}
</style>