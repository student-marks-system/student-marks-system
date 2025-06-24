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
import AdvisorAdvisees from "../views/AdvisorView/AdvisorAdvisees.vue";

// Layouts
import StudentLayout from "../layouts/StudentLayout.vue";
import AdvisorLayout from "../layouts/AdvisorLayout.vue";
import LecturerLayout from "@/layouts/LecturerLayout.vue";

// Auth service for route guards
import authService from "@/services/auth";

// Import views
import Login from "@/views/LoginPage.vue";
import LecturerDashboard from "@/views/LecturerView/LecturerDashboard.vue";

const routes = [
  {
    path: "/",
    redirect: "/login",
  },
  {
    path: "/login",
    name: "Login",
    component: Login,
    meta: {
      guestOnly: true,
    },
  },
  {
    path: "/student",
    component: StudentLayout,
    meta: {
      requiresAuth: true,
      roles: ["student"],
    },
    children: [
      {
        path: "",
        redirect: "/student/dashboard",
      },
      {
        path: "dashboard",
        name: "StudentDashboard",
        component: StudentDashboard,
        meta: {
          requiresAuth: true,
          roles: ["student"],
        },
      },
      {
        path: "course/:courseId?",
        name: "CourseDetailView",
        component: CourseDetailView,
        meta: {
          requiresAuth: true,
          roles: ["student"],
        },
      },
      {
        path: "performance-comparison",
        name: "PerformanceComparison",
        component: PerformanceComparison,
        meta: {
          requiresAuth: true,
          roles: ["student"],
        },
      },
      {
        path: "performance-trends",
        name: "PerformanceTrends",
        component: PerformanceTrends,
        meta: {
          requiresAuth: true,
          roles: ["student"],
        },
      },
      {
        path: "what-if-simulator",
        name: "WhatIfSimulator",
        component: WhatIfSimulator,
        meta: {
          requiresAuth: true,
          roles: ["student"],
        },
      },
      {
        path: "remark-requests",
        name: "RemarkRequests",
        component: () => import("../views/StudentView/RemarkRequests.vue"),
        meta: {
          requiresAuth: true,
          roles: ["student"],
        },
      },
      {
        path: "notifications",
        name: "StudentNotifications",
        component: () =>
          import("../views/StudentView/StudentNotifications.vue"),
        meta: {
          requiresAuth: true,
          roles: ["student"],
        },
      },
      {
        path: "profile",
        name: "StudentProfile",
        component: () => import("../views/StudentView/StudentProfile.vue"),
        meta: {
          requiresAuth: true,
          roles: ["student"],
        },
      },
      {
        path: "settings",
        name: "StudentSettings",
        component: () => import("../views/StudentView/StudentSettings.vue"),
        meta: {
          requiresAuth: true,
          roles: ["student"],
        },
      },
      {
        path: "help",
        name: "StudentHelp",
        component: () => import("../views/StudentView/StudentHelp.vue"),
        meta: {
          requiresAuth: true,
          roles: ["student"],
        },
      },
    ],
  },
  {
    path: "/lecturer",
    component: LecturerLayout,
    meta: {
      requiresAuth: true,
      roles: ["lecturer"],
    },
    children: [
      {
        path: "",
        redirect: "/lecturer/dashboard",
      },
      {
        path: "dashboard",
        name: "LecturerDashboard",
        component: LecturerDashboard,
        meta: {
          requiresAuth: true,
          roles: ["lecturer"],
        },
      },
      {
        path: "course/:courseId",
        name: "LecturerCourseView",
        component: () => import("../views/LecturerView/LecturerCourseView.vue"),
        meta: {
          requiresAuth: true,
          roles: ["lecturer"],
        },
      },
      {
        path: "course/:courseId/students",
        name: "LecturerStudentsView",
        component: () =>
          import("../views/LecturerView/LecturerStudentsView.vue"),
        meta: {
          requiresAuth: true,
          roles: ["lecturer"],
        },
      },
      {
        path: "course/:courseId/analytics",
        name: "LecturerAnalyticsView",
        component: () =>
          import("../views/LecturerView/LecturerAnalyticsView.vue"),
        meta: {
          requiresAuth: true,
          roles: ["lecturer"],
        },
      },
      {
        path: "components/new",
        name: "CreateComponent",
        component: () => import("../views/LecturerView/CreateComponent.vue"),
        meta: {
          requiresAuth: true,
          roles: ["lecturer"],
        },
      },
      {
        path: "analytics",
        name: "LecturerAnalytics",
        component: () => import("../views/LecturerView/LecturerAnalytics.vue"),
        meta: {
          requiresAuth: true,
          roles: ["lecturer"],
        },
      },
      {
        path: "notifications",
        name: "LecturerNotifications",
        component: () =>
          import("../views/LecturerView/LecturerNotifications.vue"),
        meta: {
          requiresAuth: true,
          roles: ["lecturer"],
        },
      },
      {
        path: "profile",
        name: "LecturerProfile",
        component: () => import("../views/LecturerView/LecturerProfile.vue"),
        meta: {
          requiresAuth: true,
          roles: ["lecturer"],
        },
      },
      {
        path: "settings",
        name: "LecturerSettings",
        component: () => import("../views/LecturerView/LecturerSettings.vue"),
        meta: {
          requiresAuth: true,
          roles: ["lecturer"],
        },
      },
    ],
  },
  {
    path: "/advisor",
    component: AdvisorLayout,
    meta: {
      requiresAuth: true,
      roles: ["advisor"],
    },
    children: [
      {
        path: "",
        redirect: "/advisor/dashboard",
      },
      {
        path: "dashboard",
        name: "AdvisorDashboard",
        component: AdvisorDashboard,
        meta: {
          requiresAuth: true,
          roles: ["advisor"],
        },
      },
      {
        path: "advisees",
        name: "AdvisorAdvisees",
        component: AdvisorAdvisees,
        meta: {
          requiresAuth: true,
          roles: ["advisor"],
        },
      },
      {
        path: "advisee/:studentId?",
        name: "AdviseeProfile",
        component: AdviseeProfile,
        meta: {
          requiresAuth: true,
          roles: ["advisor"],
        },
      },
    ],
  },
  // Placeholder routes for admin
  {
    path: "/admin",
    redirect: "/login",
  },
  {
    path: "/admin/dashboard",
    redirect: "/login",
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Navigation guards
router.beforeEach((to, from, next) => {
  const isAuthenticated = authService.isAuthenticated();
  const userRole = authService.getCurrentUserRole();

  // Check if route requires authentication
  if (to.meta.requiresAuth && !isAuthenticated) {
    next("/login");
    return;
  }

  // Check if route is guest only (like login page)
  if (to.meta.guestOnly && isAuthenticated) {
    // Already authenticated, redirect to appropriate dashboard
    const dashboardRoutes = {
      student: "/student/dashboard",
      advisor: "/advisor/dashboard",
      lecturer: "/lecturer/dashboard",
      admin: "/login", // Redirect to login until admin dashboard is created
    };
    next(dashboardRoutes[userRole] || "/login");
    return;
  }

  // Check role-based access
  if (to.meta.roles && !to.meta.roles.includes(userRole)) {
    next("/login");
    return;
  }

  next();
});

// After navigation hook for page titles
router.afterEach((to) => {
  const pageTitles = {
    LoginPage: "Login - Student Marks System",
    StudentDashboard: "Student Dashboard - Student Marks System",
    AdvisorDashboard: "Advisor Dashboard - Student Marks System",
    AdvisorAdvisees: "My Advisees - Student Marks System",
    CourseDetailView: "Course Details - Student Marks System",
    PerformanceComparison: "Performance Comparison - Student Marks System",
    PerformanceTrends: "Performance Trends - Student Marks System",
    WhatIfSimulator: "What-If Simulator - Student Marks System",
    AdviseeProfile: "Advisee Profile - Student Marks System",
    StudentNotifications: "Notifications - Student Marks System",
    StudentProfile: "My Profile - Student Marks System",
    StudentSettings: "Settings - Student Marks System",
    StudentHelp: "Help & Support - Student Marks System",
  };

  document.title = pageTitles[to.name] || "Student Marks System";
});

export default router;
