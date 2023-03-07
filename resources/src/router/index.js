import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import list from '../components/list.vue'
import admin from '../components/admin.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'admin',
      component: admin
    },
    {
      path: '/list',
      name: 'list',
      component: list
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue')
    }
  ]
})

export default router
