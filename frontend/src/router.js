import Vue from 'vue';
import Router from 'vue-router';
import Login from './views/Login.vue';

Vue.use(Router);

export const router = new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'home',
      component: Login
    },
    {
      path: '/home',
      component: Login
    },
    {
      path: '/login',
      component: Login
    },
    {
      path: '/admin',
      name: 'admin',
      // lazy-loaded
      component: () => import('./views/Admin.vue')
    },
    {
      path: '/job-application',
      name: 'job-application',
      // lazy-loaded
      component: () => import('./views/JobApplication.vue')
    }
  ]
});
