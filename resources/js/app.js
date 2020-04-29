require('./bootstrap');
window.Vue = require('vue');

import HighchartsVue from 'highcharts-vue'
import Highcharts from 'highcharts';
import moment from 'moment'

Vue.config.productionTip = false;
import VueResource from 'vue-resource'
Vue.use(VueResource);
Vue.http.options.emulateJSON = true;
Vue.use(HighchartsVue)
Vue.prototype.$moment = moment

import Admin from './components/Admin';
import Leader from './components/Leader';
import Handyman from './components/Handyman';

import router from './routes/router';
import store from './store/store';

Highcharts.setOptions({
  lang: {
    loading: "Загрузка...",
    weekdays: [
      "Воскресенье",
      "Понедельник",
      "Вторник",
      "Среда",
      "Четверг",
      "Пятница",
      "Суббота"
    ],
    shortMonths: [
      "Янв",
      "Фев",
      "Март",
      "Апр",
      "Май",
      "Июнь",
      "Июль",
      "Авг",
      "Сент",
      "Окт",
      "Нояб",
      "Дек"
    ]
  }
});

const app = new Vue({
  components: {
    Admin, Leader, Handyman
  },
  store,
  router,
  el: '#app',
});
