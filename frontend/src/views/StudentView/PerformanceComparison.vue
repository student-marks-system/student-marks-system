<template>
  <div class="performance-comparison">
    <!-- Header -->
    <ComparisonHeader />

    <!-- Course Selection -->
    <CourseSelector
      :courses="enrolledCourses"
      :selected-course="selectedCourse"
      @course-selected="selectCourse"
    />

    <div v-if="selectedCourse" class="comparison-content">
      <!-- Performance Overview Cards -->
      <PerformanceOverview :course="selectedCourse" />

      <!-- Comparison Controls -->
      <ComparisonControls
        :selected-comparison-type="selectedComparisonType"
        :selected-view-type="selectedViewType"
        @update-comparison-type="updateComparisonType"
        @update-view-type="updateViewType"
      />

      <!-- Assessment Comparison (Bar Chart View) -->
      <AssessmentComparison
        v-if="selectedViewType === 'assessments'"
        :assessments="selectedCourse.assessments"
      />

      <!-- Grade Distribution (Chart View) -->
      <GradeDistribution
        v-if="selectedViewType === 'distribution'"
        :grade-distribution="gradeDistribution"
        :selected-course="selectedCourse"
      />

      <!-- Percentile Ranking (Table View) -->
      <PercentileRanking
        v-if="selectedViewType === 'percentiles'"
        :selected-course="selectedCourse"
      />

      <!-- Class Performance Summary -->
      <ClassSummary :selected-course="selectedCourse" />

      <!-- Anonymous Comments Section -->
      <AnonymousInsights :selected-course="selectedCourse" />
    </div>

    <!-- No Course Selected State -->
    <NoCourseSelected v-else />
  </div>
</template>

<script>
// Import components
import ComparisonHeader from "@/components/StudentComponents/PerformanceComparisonComponents/ComparisonHeader.vue";
import CourseSelector from "@/components/StudentComponents/PerformanceComparisonComponents/CourseSelector.vue";
import PerformanceOverview from "@/components/StudentComponents/PerformanceComparisonComponents/PerformanceOverview.vue";
import ComparisonControls from "@/components/StudentComponents/PerformanceComparisonComponents/ComparisonControls.vue";
import AssessmentComparison from "@/components/StudentComponents/PerformanceComparisonComponents/AssessmentComparison.vue";
import GradeDistribution from "@/components/StudentComponents/PerformanceComparisonComponents/GradeDistribution.vue";
import PercentileRanking from "@/components/StudentComponents/PerformanceComparisonComponents/PercentileRanking.vue";
import ClassSummary from "@/components/StudentComponents/PerformanceComparisonComponents/ClassSummary.vue";
import AnonymousInsights from "@/components/StudentComponents/PerformanceComparisonComponents/AnonymousInsights.vue";
import NoCourseSelected from "@/components/StudentComponents/PerformanceComparisonComponents/NoCourseSelected.vue";

export default {
  name: "PerformanceComparison",
  components: {
    ComparisonHeader,
    CourseSelector,
    PerformanceOverview,
    ComparisonControls,
    AssessmentComparison,
    GradeDistribution,
    PercentileRanking,
    ClassSummary,
    AnonymousInsights,
    NoCourseSelected,
  },
  data() {
    return {
      selectedCourse: null,
      selectedComparisonType: "individual",
      selectedViewType: "assessments",
      enrolledCourses: [
        {
          id: 1,
          code: "SECJ3483",
          name: "Web Technology",
          yourAverage: 78.5,
          yourRank: 15,
          yourPercentile: 67,
          yourGrade: "B+",
          classAverage: 72.3,
          totalStudents: 45,
          standardDeviation: 12.4,
          trend: +2.5,
          assessments: [
            {
              id: 1,
              name: "Quiz 1",
              type: "quiz",
              weight: 10,
              yourScore: 18,
              maxScore: 20,
              classAverage: 16.2,
              highestScore: 20,
              yourPercentile: 78,
            },
            {
              id: 2,
              name: "Assignment 1",
              type: "assignment",
              weight: 15,
              yourScore: 85,
              maxScore: 100,
              classAverage: 74.5,
              highestScore: 95,
              yourPercentile: 72,
            },
            {
              id: 3,
              name: "Lab Exercise 1",
              type: "lab",
              weight: 5,
              yourScore: 45,
              maxScore: 50,
              classAverage: 41.8,
              highestScore: 50,
              yourPercentile: 81,
            },
            {
              id: 4,
              name: "Test 1",
              type: "test",
              weight: 20,
              yourScore: 78,
              maxScore: 100,
              classAverage: 69.2,
              highestScore: 92,
              yourPercentile: 65,
            },
          ],
        },
        {
          id: 2,
          code: "SECJ2203",
          name: "Database Systems",
          yourAverage: 85.2,
          yourRank: 8,
          yourPercentile: 82,
          yourGrade: "A-",
          classAverage: 74.8,
          totalStudents: 42,
          standardDeviation: 14.1,
          trend: +4.2,
          assessments: [
            {
              id: 5,
              name: "Quiz 1",
              type: "quiz",
              weight: 10,
              yourScore: 19,
              maxScore: 20,
              classAverage: 15.8,
              highestScore: 20,
              yourPercentile: 85,
            },
            {
              id: 6,
              name: "Assignment 1",
              type: "assignment",
              weight: 20,
              yourScore: 88,
              maxScore: 100,
              classAverage: 76.3,
              highestScore: 96,
              yourPercentile: 78,
            },
            {
              id: 7,
              name: "Lab Exercise 1",
              type: "lab",
              weight: 10,
              yourScore: 92,
              maxScore: 100,
              classAverage: 82.1,
              highestScore: 98,
              yourPercentile: 89,
            },
          ],
        },
        {
          id: 3,
          code: "SECJ3103",
          name: "Software Engineering",
          yourAverage: 72.0,
          yourRank: 22,
          yourPercentile: 51,
          yourGrade: "B",
          classAverage: 69.5,
          totalStudents: 38,
          standardDeviation: 15.8,
          trend: -1.8,
          assessments: [
            {
              id: 8,
              name: "Quiz 1",
              type: "quiz",
              weight: 15,
              yourScore: 14,
              maxScore: 20,
              classAverage: 13.2,
              highestScore: 19,
              yourPercentile: 58,
            },
            {
              id: 9,
              name: "Assignment 1",
              type: "assignment",
              weight: 25,
              yourScore: 75,
              maxScore: 100,
              classAverage: 71.8,
              highestScore: 89,
              yourPercentile: 55,
            },
          ],
        },
      ],
    };
  },
  computed: {
    gradeDistribution() {
      if (!this.selectedCourse) return [];

      // Mock grade distribution data
      const distribution = [
        { grade: "A+", count: 3 },
        { grade: "A", count: 5 },
        { grade: "A-", count: 8 },
        { grade: "B+", count: 12 },
        { grade: "B", count: 10 },
        { grade: "B-", count: 4 },
        { grade: "C+", count: 2 },
        { grade: "C", count: 1 },
        { grade: "C-", count: 0 },
      ];

      return distribution;
    },
  },
  methods: {
    selectCourse(course) {
      this.selectedCourse = course;
    },

    updateComparisonType(type) {
      this.selectedComparisonType = type;
    },

    updateViewType(type) {
      this.selectedViewType = type;
    },

    // API methods for loading data
    async loadPerformanceData() {
      try {
        // TODO: Replace with actual API calls
        // const response = await this.$http.get('/api/student/performance-comparison');
        // this.enrolledCourses = response.data;
      } catch (error) {
        console.error("Error loading performance data:", error);
        this.showError("Failed to load performance data");
      }
    },

    showError(message) {
      // TODO: Implement proper error handling/notification
      alert(message);
    },
  },
  async mounted() {
    // Auto-select first course if available
    if (this.enrolledCourses.length > 0) {
      this.selectedCourse = this.enrolledCourses[0];
    }

    // Load performance data
    await this.loadPerformanceData();
  },
};
</script>

<style scoped>
.performance-comparison {
  padding: 20px;
  background-color: #f8fafc;
  min-height: 100vh;
}

.comparison-content {
  /* Content container styles if needed */
}

/* Global responsive adjustments */
@media (max-width: 768px) {
  .performance-comparison {
    padding: 16px;
  }
}
</style>
