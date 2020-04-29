import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

import Admin from '../components/Admin'
import Users from '../components/Users'
import Works from '../components/Works'

export default new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'works',
      component: Works,
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