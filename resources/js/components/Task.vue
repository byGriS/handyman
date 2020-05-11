<template>
  <div class="taskWrap">
    <div class="d-flex justify-content-between">
      <h3>{{task.work.name}}</h3>
      <div>
        <button
          v-if="isAdmin && isFull"
          class="btn btn-success"
          @click="completeTask()"
        >Завершить работу</button>
        <button v-if="isAdmin" class="btn btn-danger" @click="deleteTask()">Удалить работу</button>
      </div>
    </div>
    <div class="row lineData">
      <div class="col-md-3">Исполнитель</div>
      <div class="col-md-3">{{task.user.name}}</div>
      <div class="col-md-3">Тип работ</div>
      <div class="col-md-3">{{typeWork}}</div>
    </div>

    <div class="row lineData">
      <div class="col-md-3">Необходимый объем работ</div>
      <div v-if="isEditCapacity" class="col-md-3 d-flex dataHover">
        <input class="form-control" v-model="task.capacity" />
        <button class="btn btn-primary" @click="editCapacity()">Ок</button>
      </div>
      <div v-else class="col-md-3 d-flex dataHover">
        <div class="flex-grow-1">{{task.capacity}}</div>
        <div v-if="isAdmin" @click="editCapacity()">
          <BtnEdit />
        </div>
      </div>
      <div class="col-md-3">Выполненный объем работ</div>
      <div class="col-md-3">{{completedWork}}</div>
    </div>

    <div class="row lineData">
      <div class="col-md-3">Срок выполнения работ</div>
      <div v-if="isEditEnd" class="col-md-3 d-flex dataHover">
        <input class="form-control" v-model="task.end" />
        <button class="btn btn-primary" @click="editEnd()">Ок</button>
      </div>
      <div v-else class="col-md-3 d-flex dataHover">
        <div class="flex-grow-1">{{task.end}}</div>
        <div v-if="isAdmin" @click="editEnd()">
          <BtnEdit />
        </div>
      </div>
      <div class="col-md-3">Расчетная дата окончания работ</div>
      <div class="col-md-3">{{completedWorkDT}}</div>
    </div>

    <div v-if="isAdmin" class="row lineData">
      <div class="col-md-3">Норматив объем работ/чел/сут</div>
      <div v-if="isEditStandartPeople" class="col-md-3 d-flex dataHover">
        <input class="form-control" v-model="task.standartPeople" />
        <button class="btn btn-primary" @click="editStandartPeople()">Ок</button>
      </div>
      <div v-else class="col-md-3 d-flex dataHover">
        <div class="flex-grow-1">{{task.standartPeople}}</div>
        <div v-if="isAdmin" @click="editStandartPeople()">
          <BtnEdit />
        </div>
      </div>
      <div class="col-md-6" />
    </div>
    <div v-if="isAdmin" class="row lineData">
      <div class="col-md-3">Норматив раствор/объем работ</div>
      <div v-if="isEditStandartConsumption" class="col-md-3 d-flex dataHover">
        <input class="form-control" v-model="task.standartConsumption" />
        <button class="btn btn-primary" @click="editStandartConsumption()">Ок</button>
      </div>
      <div v-else class="col-md-3 d-flex dataHover">
        <div class="flex-grow-1">{{task.standartConsumption}}</div>
        <div v-if="isAdmin" @click="editStandartConsumption()">
          <BtnEdit />
        </div>
      </div>
      <div class="col-md-6" />
    </div>

    <br />

    <div class="row">
      <div class="calendarWrap col-md-3">
        <Calendar v-model="date" :disabledDates="{to:taskStart, from: today}" />
      </div>
      <div class="lineData col-md-3">
        <div v-if="todayNote == null">
          <label>Кол-во человек:</label>
          <input class="form-control" v-model="todayPeople" />
        </div>
        <div v-else class="d-flex">
          <div class="flex-grow-1 d-flex">
            <label class="marginR15">Кол-во человек:</label>
            <div v-if="!isEditTodayPeople">
              <label class="marginR15">{{(todayNote != null) ? todayNote.people : ''}}</label>
            </div>
            <div v-else>
              <input class="width50 form-control" v-model="todayNote.people" />
            </div>
          </div>
          <div v-if="isAdmin" @click="editTodayPeople()">
            <div v-if="!isEditTodayPeople">
              <BtnEdit />
            </div>
            <div v-else>
              <button class="btn btn-primary">Ок</button>
            </div>
          </div>
        </div>

        <div v-if="todayNote == null">
          <label>Кол-во раствора:</label>
          <input class="form-control" v-model="todayConsumption" />
        </div>
        <div v-else class="d-flex">
          <div class="flex-grow-1 d-flex">
            <label class="marginR15">Кол-во раствора:</label>
            <div v-if="!isEditTodayConsumption">
              <label class="marginR15">{{(todayNote != null) ? todayNote.consumption : ''}}</label>
            </div>
            <div v-else>
              <input class="width50 form-control" v-model="todayConsumption" />
            </div>
          </div>
          <div v-if="isAdmin" @click="editTodayConsumption()">
            <div v-if="!isEditTodayConsumption">
              <BtnEdit />
            </div>
            <div v-else>
              <button class="btn btn-primary">Ок</button>
            </div>
          </div>
        </div>

        <button
          v-if="todayNote == null"
          class="btn btn-primary marginT15"
          @click="sendNote()"
        >Отправить данные</button>
      </div>
      <div class="col-md-6"></div>
    </div>
    <div>
      <highcharts :options="chartOptions"></highcharts>
    </div>
  </div>
</template>

<script>
import Calendar from "vuelendar/components/vl-day-selector";
import BtnEdit from "./BtnEdit";

export default {
  components: { Calendar, BtnEdit },
  props: ["task", "isAdmin"],
  data() {
    return {
      todayNote: null,
      todayPeople: 0,
      todayConsumption: 0,
      date: null,
      today: null,
      isEditCapacity: false,
      isEditEnd: false,
      isEditStandartPeople: false,
      isEditStandartConsumption: false,
      isEditTodayPeople: false,
      isEditTodayConsumption: false,
      chartOptions: {
        chart: { type: "line", height: 200 },
        credits: {
          enabled: false
        },
        title: { text: "" },
        xAxis: {
          type: "datetime",
        },
        yAxis: {
          title: { text: "" },
          max: this.task.capacity
        },
        legend: { enabled: false },
        series: []
      }
    };
  },
  computed: {
    typeWork() {
      switch (this.task.typeWork) {
        case 1:
          return "Кладка";
          break;
        case 2:
          return "Штукатурка";
          break;
      }
    },
    completedWork() {
      var result = this.task.notes.reduce(function (sum, elem) {
        return sum + elem.consumption;
      }, 0);
      return this.calcConsumptionToWork(result).toFixed(2);
    },
    completedWorkDT() {
      if (this.task.notes.length == 0)
        return "----";
      let needDays = Math.ceil((this.task.capacity - this.completedWork) / this.calcConsumptionToWork(this.task.notes[this.task.notes.length - 1].consumption));
      return this.$moment().add(needDays, "days").format("YYYY-MM-DD");
    },
    taskStart() {
      var start = this.$moment(this.task.created_at).add(-1, "day").format("YYYY-MM-DD");
      return start;
    },
    isFull() {
      return (parseFloat(this.completedWork) >= parseFloat(this.task.capacity));
    }
  },
  watch: {
    date() {
      this.checkTodayNote();
    }
  },
  methods: {
    checkTodayNote() {
      this.todayNote = null;
      this.todayConsumption = 0;
      this.task.notes.forEach(element => {
        if (this.date == element.dt) {
          this.todayNote = element;
          this.todayConsumption = element.consumption;
        }
      });
    },
    sendNote() {
      if (confirm("Отправить данные?")) {
        this.$http
          .post(this.$store.state.host + "api/addNote", {
            api_token: this.$store.state.userApi,
            people: this.todayPeople,
            consumption: this.todayConsumption,
            dt: this.date,
            task_id: this.task.id
          })
          .then(
            function (response) {
              this.todayNote = {
                people: parseFloat(this.todayPeople),
                consumption: parseFloat(this.todayConsumption),
                dt: this.date,
                id: response.data
              };
              this.task.notes.push(this.todayNote);
              this.fillGraph();
              this.todayConsumption = this.todayConsumption;
              this.todayPeople = 0;
            },
            function (response) {
              alert("Неверные данные");
            }
          );
      }
    },
    compareNotesByDate(a, b) {
      if (a.dt > b.dt) return 1;
      if (b.dt > a.dt) return -1;
      return 0;
    },
    fillGraph() {
      this.chartOptions.yAxis.max = this.task.capacity;
      this.chartOptions.xAxis.max = this.$moment(this.task.end).valueOf();
      this.chartOptions.series = [];
      this.chartOptions.series.push({
        type: "column",
        name: "Раствор",
        data: []
      });
      this.chartOptions.series.data = [];
      this.task.notes.sort(this.compareNotesByDate);
      this.task.notes.reduce((sum, note) => {
        let color = "#7CB5EC";
        if (this.isAdmin && this.calcPeopleToWork(note.people) - this.calcConsumptionToWork(note.consumption) > 1)
          color = "#FF0000";
        if (this.isAdmin && this.calcPeopleToWork(note.people) - this.calcConsumptionToWork(note.consumption) < -1)
          color = "#FFFF00";
        let y = sum + this.calcConsumptionToWork(note.consumption);
        this.chartOptions.series[0].data.push({
          x: this.$moment(note.dt).add(4, "hour").valueOf(),
          y: y,
          color: color
        });
        return y;
      }, 0);
    },
    calcConsumptionToWork(consumption) {
      return consumption / this.task.standartConsumption;
    },
    calcPeopleToWork(people) {
      return people * this.task.standartPeople;
    },
    editCapacity() {
      if (!this.isEditCapacity) {
        this.isEditCapacity = true;
      } else {
        this.$http
          .post(this.$store.state.host + "api/changeTaskCapacity", {
            api_token: this.$store.state.userApi,
            task_id: this.task.id,
            capacity: this.task.capacity
          })
          .then(function (response) {
            this.isEditCapacity = false;
            this.fillGraph();
          });
      }
    },
    editEnd() {
      if (!this.isEditEnd) {
        this.isEditEnd = true;
      } else {
        this.$http
          .post(this.$store.state.host + "api/changeTaskEnd", {
            api_token: this.$store.state.userApi,
            task_id: this.task.id,
            end: this.task.end
          })
          .then(
            function (response) {
              this.isEditEnd = false;
              this.fillGraph();
            },
            function (response) {
              alert("Неверные данные");
            }
          );
      }
    },
    editStandartPeople() {
      if (!this.isEditStandartPeople) {
        this.isEditStandartPeople = true;
      } else {
        this.$http
          .post(this.$store.state.host + "api/changeTaskStandartPeople", {
            api_token: this.$store.state.userApi,
            task_id: this.task.id,
            standartPeople: this.task.standartPeople
          })
          .then(function (response) {
            this.isEditStandartPeople = false;
            this.fillGraph();
          });
      }
    },
    editStandartConsumption() {
      if (!this.isEditStandartConsumption) {
        this.isEditStandartConsumption = true;
      } else {
        this.task.standartConsumption = parseFloat(this.task.standartConsumption.toString().replace(',', '.'));
        this.$http
          .post(this.$store.state.host + "api/changeTaskStandartConsumption", {
            api_token: this.$store.state.userApi,
            task_id: this.task.id,
            standartConsumption: this.task.standartConsumption
          })
          .then(function (response) {
            this.isEditStandartConsumption = false;
            this.fillGraph();
          });
      }
    },
    editTodayPeople() {
      if (!this.isEditTodayPeople) {
        this.isEditTodayPeople = true;
      } else {
        this.$http
          .post(this.$store.state.host + "api/changeNotePeople", {
            api_token: this.$store.state.userApi,
            id: this.todayNote.id,
            people: this.todayNote.people
          })
          .then(function (response) {
            this.isEditTodayPeople = false;
            this.fillGraph();
          });
      }
    },
    editTodayConsumption() {
      if (!this.isEditTodayConsumption) {
        this.isEditTodayConsumption = true;
      } else {
        this.$http
          .post(this.$store.state.host + "api/changeNoteConsumption", {
            api_token: this.$store.state.userApi,
            id: this.todayNote.id,
            consumption: this.todayConsumption
          })
          .then(function (response) {
            this.isEditTodayConsumption = false;
            this.todayNote.consumption = parseFloat(this.todayConsumption);
            this.fillGraph();
          });
      }
    },
    deleteTask() {
      if (confirm("Точно удалить работу?")) {
        this.$http
          .post(this.$store.state.host + "api/deleteTask", {
            api_token: this.$store.state.userApi,
            id: this.task.id
          })
          .then(function (response) {
            this.$emit("refresh");
          });
      }
    },
    completeTask() {
      this.$http
        .post(this.$store.state.host + "api/completeTask", {
          api_token: this.$store.state.userApi,
          id: this.task.id
        })
        .then(function (response) {
          this.$emit("refresh");
        });
    }
  },
  created() {
    this.date = this.$moment().format("YYYY-MM-DD");
    this.today = this.$moment().add(1, "day").format("YYYY-MM-DD");
    this.chartOptions.xAxis.min = this.$moment(this.$moment(this.task.created_at).format("YYYY-MM-DD")).valueOf();
    this.chartOptions.xAxis.max = this.$moment(this.task.end).valueOf();
    this.checkTodayNote();
    this.fillGraph();
  }
};
</script>

<style scoped>
.taskWrap {
  background: rgb(255, 255, 255);
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
.lineData svg {
  cursor: pointer;
  padding: 5px;
}
.lineData svg:hover {
  border: 1px solid rgb(202, 202, 202);
  border-radius: 4px;
}
.dataHover:hover {
  background: rgb(251, 255, 238);
  border-radius: 8px;
}
.calendarWrap {
  margin-right: 20px;
}
</style>

<style lang="scss">
@import "vuelendar/scss/vuelendar.scss";
</style>