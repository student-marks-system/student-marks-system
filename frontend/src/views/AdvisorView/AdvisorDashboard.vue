<template>
  <div class="advisor-dashboard">
    <!-- Header Component -->
    <AdvisorDashboardHeader
      :advisor-name="advisorName"
      :advisor-id="advisorId"
      :department="department"
      :total-advisees="totalAdvisees"
      :at-risk-students="atRiskStudents"
      :recent-meetings="recentMeetings"
    />

    <!-- Alert Section Component -->
    <AlertsSection
      :at-risk-students="atRiskStudentsList"
      @view-student="viewStudentProfile"
      @view-all-at-risk="viewAllAtRiskStudents"
    />

    <!-- Overview Cards Component -->
    <OverviewCards
      :performance-stats="performanceStats"
      :recent-activities="recentActivities"
      :upcoming-meetings="upcomingMeetings"
      @view-all-activity="viewAllActivity"
      @view-meeting="viewMeetingDetails"
      @schedule-meeting="scheduleMeeting"
    />

    <!-- Advisees Section Component -->
    <AdviseesSection
      :advisees="advisees"
      :search-query="searchQuery"
      :selected-filter="selectedFilter"
      :view-mode="viewMode"
      :filtered-advisees="filteredAdvisees"
      @update-search="updateSearch"
      @update-filter="updateFilter"
      @update-view-mode="updateViewMode"
      @view-student="viewStudentProfile"
      @schedule-student-meeting="scheduleStudentMeeting"
      @add-student-note="addStudentNote"
    />

    <!-- Quick Actions Section Component -->
    <QuickActionsSection
      @generate-progress-reports="generateProgressReports"
      @schedule-group-meeting="scheduleGroupMeeting"
      @export-student-data="exportStudentData"
      @view-analytics="viewAnalytics"
      @manage-notifications="manageNotifications"
      @access-resources="accessResources"
    />
  </div>
</template>

<script>
// Import all the components
import AdvisorDashboardHeader from "@/components/AdvisorComponents/DashboardComponents/AdvisorDashboardHeader.vue";
import AlertsSection from "@/components/AdvisorComponents/DashboardComponents/AlertsSection.vue";
import OverviewCards from "@/components/AdvisorComponents/DashboardComponents/OverviewCards.vue";
import AdviseesSection from "@/components/AdvisorComponents/DashboardComponents/AdviseesSection.vue";
import QuickActionsSection from "@/components/AdvisorComponents/DashboardComponents/QuickActionsSection.vue";

export default {
  name: "AdvisorDashboard",
  components: {
    AdvisorDashboardHeader,
    AlertsSection,
    OverviewCards,
    AdviseesSection,
    QuickActionsSection,
  },
  data() {
    return {
      advisorName: "Dr. Amanda Wilson",
      advisorId: "ADV001",
      department: "Computer Science",
      searchQuery: "",
      selectedFilter: "all",
      viewMode: "grid",
      advisees: [
        {
          id: 1,
          name: "John Smith",
          matricNumber: "A20CS1001",
          program: "Computer Science",
          currentGPA: 3.25,
          currentSemester: "2024/2025-1",
          enrolledCourses: 5,
          lastMeeting: "2024-10-15",
          hasRecentIssues: false,
          improvingTrend: true,
          hasUpcomingMeeting: false,
        },
        {
          id: 2,
          name: "Emily Davis",
          matricNumber: "A20CS1002",
          program: "Computer Science",
          currentGPA: 3.75,
          currentSemester: "2024/2025-1",
          enrolledCourses: 4,
          lastMeeting: "2024-10-20",
          hasRecentIssues: false,
          improvingTrend: false,
          hasUpcomingMeeting: true,
        },
        {
          id: 3,
          name: "Marcus Johnson",
          matricNumber: "A20CS1003",
          program: "Computer Science",
          currentGPA: 2.35,
          currentSemester: "2024/2025-1",
          enrolledCourses: 3,
          lastMeeting: "2024-10-12",
          hasRecentIssues: true,
          improvingTrend: false,
          hasUpcomingMeeting: false,
        },
        {
          id: 4,
          name: "Sarah Williams",
          matricNumber: "A20CS1004",
          program: "Computer Science",
          currentGPA: 3.85,
          currentSemester: "2024/2025-1",
          enrolledCourses: 5,
          lastMeeting: "2024-10-18",
          hasRecentIssues: false,
          improvingTrend: true,
          hasUpcomingMeeting: false,
        },
        {
          id: 5,
          name: "Michael Brown",
          matricNumber: "A20CS1005",
          program: "Computer Science",
          currentGPA: 2.85,
          currentSemester: "2024/2025-1",
          enrolledCourses: 4,
          lastMeeting: null,
          hasRecentIssues: true,
          improvingTrend: false,
          hasUpcomingMeeting: true,
        },
        {
          id: 6,
          name: "Lisa Chen",
          matricNumber: "A20CS1006",
          program: "Computer Science",
          currentGPA: 3.95,
          currentSemester: "2024/2025-1",
          enrolledCourses: 6,
          lastMeeting: "2024-10-22",
          hasRecentIssues: false,
          improvingTrend: true,
          hasUpcomingMeeting: false,
        },
      ],
      recentActivities: [
        {
          id: 1,
          type: "grade_alert",
          description: "Marcus Johnson's GPA dropped below 2.5",
          timestamp: new Date(Date.now() - 2 * 60 * 60 * 1000),
        },
        {
          id: 2,
          type: "meeting_completed",
          description: "Completed meeting with Emily Davis",
          timestamp: new Date(Date.now() - 4 * 60 * 60 * 1000),
        },
        {
          id: 3,
          type: "note_added",
          description: "Added counseling note for John Smith",
          timestamp: new Date(Date.now() - 6 * 60 * 60 * 1000),
        },
        {
          id: 4,
          type: "progress_report",
          description: "Generated progress report for Sarah Williams",
          timestamp: new Date(Date.now() - 8 * 60 * 60 * 1000),
        },
      ],
      upcomingMeetings: [
        {
          id: 1,
          studentName: "Emily Davis",
          dateTime: new Date(Date.now() + 24 * 60 * 60 * 1000),
          purpose: "Mid-semester progress review",
        },
        {
          id: 2,
          studentName: "Michael Brown",
          dateTime: new Date(Date.now() + 48 * 60 * 60 * 1000),
          purpose: "Academic performance discussion",
        },
      ],
    };
  },
  computed: {
    totalAdvisees() {
      return this.advisees.length;
    },

    atRiskStudents() {
      return this.advisees.filter((student) => student.currentGPA < 2.5).length;
    },

    atRiskStudentsList() {
      return this.advisees
        .filter((student) => student.currentGPA < 2.5)
        .map((student) => ({
          ...student,
          riskLevel: student.currentGPA < 2.0 ? "Critical" : "Warning",
        }));
    },

    recentMeetings() {
      const oneWeekAgo = new Date(Date.now() - 7 * 24 * 60 * 60 * 1000);
      return this.advisees.filter(
        (student) =>
          student.lastMeeting && new Date(student.lastMeeting) > oneWeekAgo
      ).length;
    },

    performanceStats() {
      const stats = {
        excellent: 0,
        good: 0,
        average: 0,
        atRisk: 0,
      };

      this.advisees.forEach((student) => {
        if (student.currentGPA >= 3.5) stats.excellent++;
        else if (student.currentGPA >= 3.0) stats.good++;
        else if (student.currentGPA >= 2.5) stats.average++;
        else stats.atRisk++;
      });

      return stats;
    },

    filteredAdvisees() {
      let filtered = this.advisees;

      // Apply search filter
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase();
        filtered = filtered.filter(
          (student) =>
            student.name.toLowerCase().includes(query) ||
            student.matricNumber.toLowerCase().includes(query) ||
            student.program.toLowerCase().includes(query)
        );
      }

      // Apply category filter
      switch (this.selectedFilter) {
        case "excellent":
          filtered = filtered.filter((student) => student.currentGPA >= 3.5);
          break;
        case "good":
          filtered = filtered.filter(
            (student) => student.currentGPA >= 3.0 && student.currentGPA < 3.5
          );
          break;
        case "average":
          filtered = filtered.filter(
            (student) => student.currentGPA >= 2.5 && student.currentGPA < 3.0
          );
          break;
        case "at-risk":
          filtered = filtered.filter((student) => student.currentGPA < 2.5);
          break;
        case "recent-meetings": {
          const oneWeekAgo = new Date(Date.now() - 7 * 24 * 60 * 60 * 1000);
          filtered = filtered.filter(
            (student) =>
              student.lastMeeting && new Date(student.lastMeeting) > oneWeekAgo
          );
          break;
        }
      }

      return filtered;
    },
  },
  methods: {
    // Search and filter methods
    updateSearch(query) {
      this.searchQuery = query;
    },

    updateFilter(filter) {
      this.selectedFilter = filter;
    },

    updateViewMode(mode) {
      this.viewMode = mode;
    },

    // Navigation methods
    viewStudentProfile(studentId) {
      this.$router.push(`/advisor/student/${studentId}`);
    },

    viewAllAtRiskStudents() {
      this.selectedFilter = "at-risk";
      this.scrollToAdvisees();
    },

    viewAllActivity() {
      this.$router.push("/advisor/activity");
    },

    scheduleMeeting() {
      this.$router.push("/advisor/schedule-meeting");
    },

    scheduleStudentMeeting(studentId) {
      this.$router.push(`/advisor/schedule-meeting/${studentId}`);
    },

    addStudentNote(studentId) {
      this.$router.push(`/advisor/student/${studentId}/notes`);
    },

    viewMeetingDetails(meetingId) {
      this.$router.push(`/advisor/meeting/${meetingId}`);
    },

    // Quick Actions methods
    generateProgressReports() {
      console.log("Generating progress reports...");
    },

    scheduleGroupMeeting() {
      this.$router.push("/advisor/group-meeting");
    },

    exportStudentData() {
      console.log("Exporting student data...");
    },

    viewAnalytics() {
      this.$router.push("/advisor/analytics");
    },

    manageNotifications() {
      this.$router.push("/advisor/notifications");
    },

    accessResources() {
      this.$router.push("/advisor/resources");
    },

    // Utility methods
    scrollToAdvisees() {
      this.$nextTick(() => {
        const adviseesSection = document.querySelector(".advisees-section");
        if (adviseesSection) {
          adviseesSection.scrollIntoView({ behavior: "smooth" });
        }
      });
    },
  },
};
</script>

<style scoped>
.advisor-dashboard {
  padding: 20px;
  background-color: #f8fafc;
  min-height: 100vh;
}

/* Responsive Design */
@media (max-width: 768px) {
  .advisor-dashboard {
    padding: 16px;
  }
}
</style>
