<template>
  <div class="course-detail-view">
    <!-- Course Header -->
    <CourseHeader :course="course" />

    <!-- Progress Overview -->
    <ProgressOverview :course="course" />

    <!-- Assessment Components -->
    <AssessmentsSection
      :assessments="assessments"
      :active-filter="activeFilter"
      @update-filter="updateFilter"
      @request-remark="requestRemark"
      @view-assessment-details="viewAssessmentDetails"
    />

    <!-- Grade Breakdown Chart -->
    <GradeBreakdown :weight-categories="weightCategories" />

    <!-- Class Performance Comparison -->
    <ClassPerformanceComparison
      :top-assessments="topAssessments"
      :above-average-count="aboveAverageCount"
      :total-assessments="course.totalAssessments"
      :percentile-rank="course.percentileRank"
      :improvement-trend="improvementTrend"
      :improvement-class="improvementClass"
    />
  </div>
</template>

<script>
// Import components
import CourseHeader from "@/components/StudentComponents/CourseDetailViewComponents/CourseHeader.vue";
import ProgressOverview from "@/components/StudentComponents/CourseDetailViewComponents/ProgressOverview.vue";
import AssessmentsSection from "@/components/StudentComponents/CourseDetailViewComponents/AssessmentsSection.vue";
import GradeBreakdown from "@/components/StudentComponents/CourseDetailViewComponents/GradeBreakdown.vue";
import ClassPerformanceComparison from "@/components/StudentComponents/CourseDetailViewComponents/ClassPerformanceComparison.vue";

export default {
  name: "CourseDetailView",
  components: {
    CourseHeader,
    ProgressOverview,
    AssessmentsSection,
    GradeBreakdown,
    ClassPerformanceComparison,
  },
  data() {
    return {
      activeFilter: "all",
      course: {
        id: 1,
        code: "SECJ3483",
        name: "Web Technology",
        credits: 3,
        semester: "2024/2025-1",
        currentGrade: "B+",
        currentMarks: 78.5,
        totalMarks: 100,
        classRank: 15,
        totalStudents: 45,
        classAverage: 72.3,
        progressPercentage: 65,
        completedAssessments: 4,
        totalAssessments: 6,
        percentileRank: 67,
      },
      assessments: [
        {
          id: 1,
          name: "Quiz 1",
          type: "quiz",
          weight: 10,
          maxMarks: 20,
          marksObtained: 18,
          dueDate: "2024-09-15",
          gradedAt: "2024-09-16",
          description: "HTML and CSS Fundamentals",
          remarks:
            "Excellent understanding of CSS selectors. Minor issues with responsive design.",
          status: "completed",
        },
        {
          id: 2,
          name: "Assignment 1",
          type: "assignment",
          weight: 15,
          maxMarks: 100,
          marksObtained: 85,
          dueDate: "2024-09-25",
          gradedAt: "2024-09-28",
          description: "Build a responsive website using HTML/CSS",
          remarks:
            "Great work on the layout and styling. The responsive design works well across devices.",
          status: "completed",
        },
        {
          id: 3,
          name: "Lab Exercise 1",
          type: "lab",
          weight: 5,
          maxMarks: 50,
          marksObtained: 45,
          dueDate: "2024-10-01",
          gradedAt: "2024-10-02",
          description: "JavaScript DOM Manipulation",
          remarks:
            "Good understanding of DOM events. Consider optimizing your code structure.",
          status: "completed",
        },
        {
          id: 4,
          name: "Test 1",
          type: "test",
          weight: 20,
          maxMarks: 100,
          marksObtained: 78,
          dueDate: "2024-10-10",
          gradedAt: "2024-10-12",
          description:
            "Mid-semester examination covering HTML, CSS, JavaScript",
          remarks:
            "Solid performance overall. Review JavaScript async concepts for improvement.",
          status: "completed",
        },
        {
          id: 5,
          name: "Assignment 2",
          type: "assignment",
          weight: 20,
          maxMarks: 100,
          marksObtained: null,
          dueDate: "2024-11-15",
          gradedAt: null,
          description: "Full-stack web application using PHP and MySQL",
          remarks: null,
          status: "pending",
        },
        {
          id: 6,
          name: "Final Exam",
          type: "final_exam",
          weight: 30,
          maxMarks: 100,
          marksObtained: null,
          dueDate: "2024-12-15",
          gradedAt: null,
          description: "Comprehensive final examination",
          remarks: null,
          status: "pending",
        },
      ],
    };
  },
  computed: {
    weightCategories() {
      const categories = {
        Quizzes: {
          weight: 0,
          obtainedMarks: 0,
          totalMarks: 0,
          status: "completed",
        },
        Assignments: {
          weight: 0,
          obtainedMarks: 0,
          totalMarks: 0,
          status: "partial",
        },
        Labs: {
          weight: 0,
          obtainedMarks: 0,
          totalMarks: 0,
          status: "completed",
        },
        Tests: {
          weight: 0,
          obtainedMarks: 0,
          totalMarks: 0,
          status: "completed",
        },
        "Final Exam": {
          weight: 0,
          obtainedMarks: 0,
          totalMarks: 0,
          status: "pending",
        },
      };

      this.assessments.forEach((assessment) => {
        let category;
        switch (assessment.type) {
          case "quiz":
            category = "Quizzes";
            break;
          case "assignment":
            category = "Assignments";
            break;
          case "lab":
            category = "Labs";
            break;
          case "test":
            category = "Tests";
            break;
          case "final_exam":
            category = "Final Exam";
            break;
          default:
            return;
        }

        categories[category].weight += assessment.weight;
        categories[category].totalMarks += assessment.maxMarks;
        if (assessment.marksObtained !== null) {
          categories[category].obtainedMarks += assessment.marksObtained;
        }
      });

      return Object.entries(categories).map(([name, data]) => ({
        name,
        ...data,
      }));
    },

    topAssessments() {
      return this.assessments
        .filter((a) => a.marksObtained !== null)
        .map((a) => ({
          id: a.id,
          name: a.name,
          yourPercentage: Math.round((a.marksObtained / a.maxMarks) * 100),
          classPercentage: Math.round(Math.random() * 20 + 65), // Mock class average
        }))
        .slice(0, 4);
    },

    aboveAverageCount() {
      return this.topAssessments.filter(
        (a) => a.yourPercentage > a.classPercentage
      ).length;
    },

    improvementTrend() {
      const completed = this.assessments.filter(
        (a) => a.marksObtained !== null
      );
      if (completed.length < 2) return "N/A";

      const latest = completed[completed.length - 1];
      const previous = completed[completed.length - 2];

      const latestPercentage = (latest.marksObtained / latest.maxMarks) * 100;
      const previousPercentage =
        (previous.marksObtained / previous.maxMarks) * 100;

      const difference = latestPercentage - previousPercentage;
      return difference > 0
        ? `+${difference.toFixed(1)}%`
        : `${difference.toFixed(1)}%`;
    },

    improvementClass() {
      const trend = this.improvementTrend;
      if (trend === "N/A") return "";
      return trend.startsWith("+")
        ? "improvement-positive"
        : "improvement-negative";
    },
  },
  methods: {
    updateFilter(filterKey) {
      this.activeFilter = filterKey;
    },

    requestRemark(assessmentId) {
      this.$router.push(`/remark-request/${assessmentId}`);
    },

    viewAssessmentDetails(assessmentId) {
      // Could open a modal or navigate to detailed view
      console.log("View assessment details:", assessmentId);
      // TODO: Implement assessment details view
    },

    async loadCourseData() {
      try {
        // TODO: Replace with actual API calls
        // const courseId = this.$route.params.id;
        // const response = await this.$http.get(`/api/courses/${courseId}/details`);
        // this.course = response.data.course;
        // this.assessments = response.data.assessments;
      } catch (error) {
        console.error("Error loading course data:", error);
        this.showError("Failed to load course data");
      }
    },

    showError(message) {
      // TODO: Implement proper error handling/notification
      alert(message);
    },
  },
  async mounted() {
    // Load course data when component mounts
    await this.loadCourseData();
  },
};
</script>

<style scoped>
.course-detail-view {
  padding: 20px;
  background-color: #f8fafc;
  min-height: 100vh;
}

/* Global responsive adjustments */
@media (max-width: 768px) {
  .course-detail-view {
    padding: 16px;
  }
}
</style>
