import { createRouter, createWebHistory } from "vue-router";

// Views
import HomeView from "../views/HomeView.vue";
import CourseList from "../views/CourseManagementView/CourseList.vue";
import AssignLecturers from "../views/CourseManagementView/AssignLecturers.vue";
import MarksUpdate from "../views/MarksUpdateView/MarksUpdate.vue";
import SystemLogs from "../views/SystemLogsView/SystemLogs.vue";
import SubmitRemarkRequest from "../views/RemarkRequestView/SubmitRemarkRequest.vue";
import ReviewRemarkRequest from "../views/RemarkRequestView/ReviewRemarkRequest.vue";

// Layouts
import LecturerLayout from "../layouts/LecturerLayout.vue"; 
import AdminLayout from "../layouts/AdminLayout.vue"; 

const routes = [
  {
    path: "/",
    name: "Home",
    component: HomeView,
  },
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
        path: "submit-remark",
        name: "SubmitRemarkRequest",
        component: SubmitRemarkRequest,
      },
      {
        path: "review-remarks",
        name: "ReviewRemarkRequest",
        component: ReviewRemarkRequest,
      },
    ],
  },
  {
    path: "/admin",
    component: AdminLayout,
    children: [
      {
        path: "assign-lecturers", 
        name: "AssignLecturers",
        component: AssignLecturers,
      },
      {
        path: "mark-updates", 
        name: "MarksUpdate",
        component: MarksUpdate,
        meta: { requiresAdmin: true },
      },
      {
        path: "system-logs",
        name: "SystemLogs",
        component: SystemLogs,
        meta: { requiresAdmin: true },
      },
    ],
  },
  {
    path: "/:pathMatch(.*)*",
    redirect: "/",
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});


export default router;