import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import StudentDashboard from '../views/StudentDashboard.vue'
import LecturerDashboard from '../views/LecturerDashboard.vue'
import AdminDashboard from '../views/AdminDashboard.vue'

const routes = [
  { path: '/', name: 'Home', component: HomeView },
  { path: '/student', name: 'StudentDashboard', component: StudentDashboard },
  { path: '/lecturer', name: 'LecturerDashboard', component: LecturerDashboard },
  { path: '/admin', name: 'AdminDashboard', component: AdminDashboard }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router


// const routes = [
//   {
//     path: "/",
//     name: "Home",
//     component: HomeView,
//   },
// ];

// const router = createRouter({
//   history: createWebHistory(),
//   routes,
// });

// export default router;
