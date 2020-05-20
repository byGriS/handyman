import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

import Users from '../components/Users'
import Works from '../components/Works'
import CompletedWorks from '../components/completedWorks'

export default new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'works',
      component: Works,
    },   
    {
      path: '/complete',
      name: 'completedWorks',
      component: CompletedWorks,
    },
    {
      path: '/users',
      name: 'users',
      component: Users,
    },
    {
      path: '*',
      redirect: '/'
    }
  ]
})