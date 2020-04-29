<template>
  <div class="taskWrap">
    <div>
      <h3>{{task.work.name}}</h3>
    </div>
    <div class="row lineData">
      <div class="col-md-3">Исполнитель</div>
      <div class="col-md-3">{{task.user.name}}</div>
      <div class="col-md-3">Тип работ</div>
      <div class="col-md-3">{{typeWork}}</div>
    </div>

    <div class="row lineData">
      <div class="col-md-3">Необходимый объем работ</div>
      <div class="col-md-3 d-flex dataHover">
        <div class="flex-grow-1">{{task.capacity}}</div>
        <div v-if="isAdmin">
          <BtnEdit />
        </div>
      </div>
      <div class="col-md-3">Выполненный объем работ</div>
      <div class="col-md-3">{{completedWork}}</div>
    </div>

    <div class="row lineData">
      <div class="col-md-3">Срок выполнения работ</div>
      <div class="col-md-3 d-flex dataHover">
        <div class="flex-grow-1">{{task.end}}</div>
        <div v-if="isAdmin">
          <BtnEdit />
        </div>
      </div>
      <div class="col-md-3">Расчетная дата окончания работ</div>
      <div class="col-md-3">{{task.capacity}}</div>
    </div>
    <br />
    <div class="d-flex flex-wrap">
      <div class="calendarWrap">
        <Calendar v-model="date" :disabledDates="{to:taskStart, from: today}" />
      </div>
      <div class>
        <div>
          Кол-во человек:
          {{(todayNote != null)?todayNote.people: ''}}
        </div>
        <div v-if="todayNote == null">
          <input class="form-control" v-model="todayPeople" />
        </div>
        <div>
          Кол-во раствора:
          {{(todayNote != null)?todayNote.consumption: ''}}
        </div>
        <div v-if="todayNote == null">
          <input class="form-control" v-model="todayConsumption" />
        </div>
        <button
          v-if="todayNote == null"
          class="btn btn-primary"
          @click="sendNote()"
        >Отправить данные</button>
      </div>
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
      chartOptions: {
        chart: { type: "line", height: 200 },
        credits: {
          enabled: false
        },
        title: { text: "" },
        xAxis: {
          type: "datetime",
          tickInterval: 1000 * 60 * 60 * 24
        },
        yAxis: { title: { text: "" } },
        legend: { enabled: false },
        series: [
          {
            type: "column",
            marker: { enabled: false, symbol: null },
            name: "Раствор",
            data: []
          }
        ]
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
      var result = this.task.notes.reduce(function(sum, elem) {
        return sum + elem.consumption;
      }, 0);
      console.log(result, this.task.standartConsumption);
      return result / this.task.standartConsumption;
    },
    taskStart() {
      var start = this.$moment(this.task.created_at)
        .add(-1, "day")
        .format("YYYY-MM-DD");
      return start;
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
      this.task.notes.forEach(element => {
        if (this.date == element.dt) {
          this.todayNote = element;
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
            task_id: this.task.id
          })
          .then(
            function(response) {
              this.todayNote = {
                people: parseFloat(this.todayPeople),
                consumption: parseFloat(this.todayConsumption)
              };
              this.task.notes.push(this.todayNote);
              this.fillGraph();
            },
            function(response) {
              alert("Неверные данные");
            }
          );
      }
    },
    fillGraph() {
      this.chartOptions.series.data = [];
      this.task.notes.forEach(note => {
        this.chartOptions.series[0].data.push({
          x: this.$moment(note.dt)
            .add(4, "hour")
            .valueOf(),
          y: note.consumption
        });
      });
    }
  },
  created() {
    this.date = this.$moment().format("YYYY-MM-DD");
    this.today = this.$moment()
      .add(1, "day")
      .format("YYYY-MM-DD");
    this.chartOptions.xAxis.min = this.$moment(
      this.$moment(this.task.created_at).format("YYYY-MM-DD")
    ).valueOf();
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
.btn {
  margin-top: 15px;
}
</style>

<style lang="scss">
@import "vuelendar/scss/vuelendar.scss";
</style>