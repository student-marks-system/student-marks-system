import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";


// Course Management Views (Module 3)
import CourseList from "../views/CourseManagementView/CourseList.vue";
import AssignLecturers from "../views/CourseManagementView/AssignLecturers.vue";
import MarksUpdate from "../views/MarksUpdateView/MarksUpdate.vue";
import SystemLogs from "../views/SystemLogsView/SystemLogs.vue";
// Layouts
import LecturerLayout from "../layouts/LecturerLayout.vue";
import AdminLayout from "../layouts/AdminLayout.vue";

const routes = [
  {
    path: "/",
    name: "Home",
    component: HomeView,
  },

    // Course Management Routes (Module 3)
  {
    path: "/courses",
    component: LecturerLayout, 
    children: [
      {
        path: "",
        name: "CourseList",
        component: CourseList,
      },
      {
        path: "assign-lecturers",
        name: "AssignLecturers",
        component: AssignLecturers,
      },
    ],
  },
   {
    path: "/admin",
    component: AdminLayout, 
    children: [
      {
        path: "Marks-Update",
        name: "MarksUpdate",
        component: MarksUpdate,
      },
      {
        path: "System-Logs",
        name: "SystemLogs",
        component: SystemLogs,
      },
    ],
  },

];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
