import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";


const routes = [
    {
    path: "/",
    name: "Home",
    component: HomeView,
  },

  {
    path: '/admin',
    name: 'AdminPanel',
    component: () => import('../views/AdminView/AdminPanel.vue')
  },
  {
    path: '/lecturer',
    name: 'LecturerPanel',
    component: () => import('../views/LecturerView/LecturerPanel.vue')
  },
  {
    path: '/enrollment',
    name: 'EnrollmentPanel',
   component: () => import('../views/EnrollmentView/EnrollmentPanel.vue')
  },
  
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
