<template>
  <div class="student-dashboard">
    <!-- Dashboard Header -->
    <DashboardHeader
      :student-name="studentInfo.name"
      :matric-number="studentInfo.matricNumber"
      :current-semester="studentInfo.currentSemester"
    />

    <!-- Stats Grid -->
    <StatsGrid
      :overall-g-p-a="academicStats.overallGPA"
      :gpa-change="academicStats.gpaChange"
      :enrolled-courses-count="academicStats.enrolledCoursesCount"
      :class-rank="academicStats.classRank"
      :total-students="academicStats.totalStudents"
      :percentile="academicStats.percentile"
    />

    <!-- Course Performance Section -->
    <CoursePerformanceSection
      :courses="courses"
      @view-all-courses="handleViewAllCourses"
      @course-details="handleCourseDetails"
    />

    <!-- Quick Actions Section -->
    <QuickActionsSection @action-clicked="handleActionClick" />

    <!-- Recent Activity Section -->
    <RecentActivitySection
      :activities="recentActivities"
      @activity-action="handleActivityAction"
    />
  </div>
</template>

<script>
// Import dashboard components
import DashboardHeader from "@/components/StudentComponents/DashboardComponents/DashboardHeader.vue";
import StatsGrid from "@/components/StudentComponents/DashboardComponents/StatsGrid.vue";
import CoursePerformanceSection from "@/components/StudentComponents/DashboardComponents/CoursePerformanceSection.vue";
import QuickActionsSection from "@/components/StudentComponents/DashboardComponents/QuickActionsSection.vue";
import RecentActivitySection from "@/components/StudentComponents/DashboardComponents/RecentActivitySection.vue";

export default {
  name: "StudentDashboard",
  components: {
    DashboardHeader,
    StatsGrid,
    CoursePerformanceSection,
    QuickActionsSection,
    RecentActivitySection,
  },
  data() {
    return {
      studentInfo: {
        name: "John Smith",
        matricNumber: "A20CS1001",
        currentSemester: "2024/2025 Semester 1",
      },
      academicStats: {
        overallGPA: "3.67",
        gpaChange: "+0.15",
        enrolledCoursesCount: 3,
        classRank: 12,
        totalStudents: 85,
        percentile: 86,
      },
      courses: [
        {
          id: 1,
          code: "SECJ3483",
          name: "Web Technology",
          credits: 3,
          currentMarks: 248,
          totalMarks: 270,
          progressPercentage: 92,
          currentGrade: "A-",
          classAverage: "75",
          completedAssessments: 3,
          pendingAssessments: 3,
        },
        {
          id: 2,
          code: "SECJ2203",
          name: "Database Systems",
          credits: 3,
          currentMarks: 165,
          totalMarks: 200,
          progressPercentage: 83,
          currentGrade: "B+",
          classAverage: "68",
          completedAssessments: 2,
          pendingAssessments: 4,
        },
        {
          id: 3,
          code: "SECJ3103",
          name: "Software Engineering",
          credits: 3,
          currentMarks: 140,
          totalMarks: 170,
          progressPercentage: 82,
          currentGrade: "B+",
          classAverage: "72",
          completedAssessments: 2,
          pendingAssessments: 4,
        },
      ],
      recentActivities: [
        {
          id: 1,
          type: "grade_update",
          title: "New marks posted",
          description: "SECJ3483 - Assignment 2 marks are now available",
          timestamp: new Date(Date.now() - 2 * 60 * 60 * 1000), // 2 hours ago
          actionRequired: true,
          actionText: "View Details",
        },
        {
          id: 2,
          type: "reminder",
          title: "Upcoming deadline",
          description: "SECJ2203 - Lab Exercise 3 due in 2 days",
          timestamp: new Date(Date.now() - 6 * 60 * 60 * 1000), // 6 hours ago
          actionRequired: false,
        },
        {
          id: 3,
          type: "remark_update",
          title: "Remark request approved",
          description: "Your remark request for Quiz 2 has been approved",
          timestamp: new Date(Date.now() - 24 * 60 * 60 * 1000), // 1 day ago
          actionRequired: true,
          actionText: "View Updated Marks",
        },
        {
          id: 4,
          type: "announcement",
          title: "Course announcement",
          description: "New study materials uploaded for Software Engineering",
          timestamp: new Date(Date.now() - 48 * 60 * 60 * 1000), // 2 days ago
          actionRequired: false,
        },
      ],
    };
  },
  async mounted() {
    await this.loadDashboardData();
  },
  methods: {
    async loadDashboardData() {
      try {
        // Load student information
        await this.loadStudentInfo();

        // Load academic statistics
        await this.loadAcademicStats();

        // Load course data
        await this.loadCourses();

        // Load recent activities
        await this.loadRecentActivities();
      } catch (error) {
        console.error("Error loading dashboard data:", error);
        this.showError("Failed to load dashboard data");
      }
    },

    async loadStudentInfo() {
      // TODO: Replace with actual API call
      // const response = await this.$http.get('/api/student/profile');
      // this.studentInfo = response.data;
    },

    async loadAcademicStats() {
      // TODO: Replace with actual API call
      // const response = await this.$http.get('/api/student/academic-stats');
      // this.academicStats = response.data;
    },

    async loadCourses() {
      // TODO: Replace with actual API call
      // const response = await this.$http.get('/api/student/courses');
      // this.courses = response.data;
    },

    async loadRecentActivities() {
      // TODO: Replace with actual API call
      // const response = await this.$http.get('/api/student/recent-activities');
      // this.recentActivities = response.data;
    },

    // Event handlers
    handleViewAllCourses() {
      this.$router.push("/courses");
    },

    handleCourseDetails(courseId) {
      this.$router.push(`/course/${courseId}/details`);
    },

    handleActionClick(action) {
      if (action.route) {
        this.$router.push(action.route);
      }

      // Handle specific actions
      switch (action.id) {
        case "what-if":
          this.$router.push("/what-if-simulator");
          break;
        case "compare":
          this.$router.push("/performance-comparison");
          break;
        case "remark":
          this.$router.push("/remark-request");
          break;
        case "trends":
          this.$router.push("/performance-trends");
          break;
      }
    },

    handleActivityAction(activity) {
      // Handle activity-specific actions
      switch (activity.type) {
        case "grade_update":
          this.$router.push(`/course/${activity.courseId || 1}/marks`);
          break;
        case "remark_update":
          this.$router.push("/remark-requests");
          break;
        default:
          console.log("Activity action:", activity);
      }
    },

    showError(message) {
      // TODO: Implement proper error handling/notification
      alert(message);
    },
  },
};
</script>

<style scoped>
.student-dashboard {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
  background: #f8fafc;
  min-height: 100vh;
}

/* Global responsive adjustments */
@media (max-width: 768px) {
  .student-dashboard {
    padding: 10px;
  }
}
</style>
