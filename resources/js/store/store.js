import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state:{
    userRole: 0,
    userApi: "",
    //host: "http://handyman.loc/",
    host: "https://handyman63.ru/",
  }
})