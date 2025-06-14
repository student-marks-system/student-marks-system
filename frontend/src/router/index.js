import { createRouter, createWebHistory } from "vue-router";
// Student Views
import StudentDashboard from "../views/StudentView/StudentDashboard.vue";
import CourseDetailView from "../views/StudentView/CourseDetailView.vue";
import PerformanceComparison from "../views/StudentView/PerformanceComparison.vue";
import PerformanceTrends from "../views/StudentView/PerformanceTrends.vue";
import WhatIfSimulator from "../views/StudentView/WhatIfSimulator.vue";

// Advisor Views
import AdvisorDashboard from "../views/AdvisorView/AdvisorDashboard.vue";
import AdviseeProfile from "../views/AdvisorView/AdviseeProfile.vue";

// Layouts
import StudentLayout from "../layouts/StudentLayout.vue";
import AdvisorLayout from "../layouts/AdvisorLayout.vue";
const routes = [
  // Student Routes
  {
    path: "/Student",
    component: StudentLayout,
    children: [
      { path: "", name: "StudentDashboard", component: StudentDashboard },
      {
        path: "CourseDetailView",
        name: "CourseDetailView",
        component: CourseDetailView,
      },
      {
        path: "PerformanceComparison",
        name: "PerformanceComparison",
        component: PerformanceComparison,
      },
      {
        path: "PerformanceTrends",
        name: "PerformanceTrends",
        component: PerformanceTrends,
      },
      {
        path: "WhatIfSimulator",
        name: "WhatIfSimulator",
        component: WhatIfSimulator,
      },
    ],
  },

  {
    path: "/advisor",
    component: AdvisorLayout,
    children: [
      { path: "", name: "AdvisorDashboard", component: AdvisorDashboard },
      {
        path: "AdviseeProfile",
        name: "AdviseeProfile",
        component: AdviseeProfile,
      },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
