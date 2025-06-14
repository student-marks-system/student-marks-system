<template>
  <div class="performance-trends">
    <!-- Header -->
    <TrendsHeader />

    <!-- Course Selection and Time Range -->
    <TrendsControls
      :courses="enrolledCourses"
      :selected-course-id="selectedCourseId"
      :selected-time-range="selectedTimeRange"
      :selected-chart-type="selectedChartType"
      @update-course="updateCourse"
      @update-time-range="updateTimeRange"
      @update-chart-type="updateChartType"
    />

    <!-- Performance Overview Summary -->
    <OverviewSummary
      :overall-trend="overallTrend"
      :current-average="currentAverage"
      :best-performance="bestPerformance"
      :biggest-improvement="biggestImprovement"
    />

    <!-- Main Trends Chart -->
    <TrendsChart
      :performance-data="performanceData"
      :class-average-data="classAverageData"
      :chart-type="selectedChartType"
      :selected-course-id="selectedCourseId"
    />

    <!-- Detailed Analytics -->
    <DetailedAnalytics
      :assessment-type-performance="assessmentTypePerformance"
      :monthly-progress="monthlyProgress"
      :improvement-areas="improvementAreas"
      :performance-insights="performanceInsights"
    />

    <!-- Export and Actions -->
    <ActionsSection
      @export-trends-report="exportTrendsReport"
      @set-performance-goals="setPerformanceGoals"
      @share-progress="shareProgress"
      @schedule-review="scheduleReview"
    />
  </div>
</template>

<script>
// Import components
import TrendsHeader from "@/components/StudentComponents/PerformanceTrendsComponents/TrendsHeader.vue";
import TrendsControls from "@/components/StudentComponents/PerformanceTrendsComponents/TrendsControls.vue";
import OverviewSummary from "@/components/StudentComponents/PerformanceTrendsComponents/OverviewSummary.vue";
import TrendsChart from "@/components/StudentComponents/PerformanceTrendsComponents/TrendsChart.vue";
import DetailedAnalytics from "@/components/StudentComponents/PerformanceTrendsComponents/DetailedAnalytics.vue";
import ActionsSection from "@/components/StudentComponents/PerformanceTrendsComponents/ActionsSection.vue";

export default {
  name: "PerformanceTrends",
  components: {
    TrendsHeader,
    TrendsControls,
    OverviewSummary,
    TrendsChart,
    DetailedAnalytics,
    ActionsSection,
  },
  data() {
    return {
      selectedCourseId: "all",
      selectedTimeRange: "semester",
      selectedChartType: "line",
      enrolledCourses: [
        { id: 1, code: "SECJ3483", name: "Web Technology" },
        { id: 2, code: "SECJ2203", name: "Database Systems" },
        { id: 3, code: "SECJ3103", name: "Software Engineering" },
      ],
      performanceData: [
        {
          label: "Sep",
          percentage: 75,
          name: "Quiz 1",
          score: 75,
          date: "Sep 15, 2024",
          type: "Quiz",
        },
        {
          label: "Oct",
          percentage: 82,
          name: "Assignment 1",
          score: 82,
          date: "Oct 1, 2024",
          type: "Assignment",
        },
        {
          label: "Oct",
          percentage: 78,
          name: "Lab 1",
          score: 78,
          date: "Oct 15, 2024",
          type: "Lab",
        },
        {
          label: "Nov",
          percentage: 85,
          name: "Test 1",
          score: 85,
          date: "Nov 1, 2024",
          type: "Test",
        },
        {
          label: "Nov",
          percentage: 88,
          name: "Assignment 2",
          score: 88,
          date: "Nov 15, 2024",
          type: "Assignment",
        },
        {
          label: "Dec",
          percentage: 92,
          name: "Quiz 2",
          score: 92,
          date: "Dec 1, 2024",
          type: "Quiz",
        },
      ],
      classAverageData: [
        { percentage: 70 },
        { percentage: 74 },
        { percentage: 72 },
        { percentage: 76 },
        { percentage: 78 },
        { percentage: 80 },
      ],
    };
  },
  computed: {
    overallTrend() {
      if (this.performanceData.length < 2)
        return {
          direction: "stable",
          percentage: 0,
          description: "Not enough data",
        };

      const first = this.performanceData[0].percentage;
      const last =
        this.performanceData[this.performanceData.length - 1].percentage;
      const change = last - first;

      if (change > 5) {
        return {
          direction: "up",
          percentage: change.toFixed(1),
          description: "Improving performance",
        };
      } else if (change < -5) {
        return {
          direction: "down",
          percentage: change.toFixed(1),
          description: "Declining performance",
        };
      } else {
        return {
          direction: "stable",
          percentage: change.toFixed(1),
          description: "Stable performance",
        };
      }
    },

    currentAverage() {
      const sum = this.performanceData.reduce(
        (total, item) => total + item.percentage,
        0
      );
      return (sum / this.performanceData.length).toFixed(1);
    },

    bestPerformance() {
      const best = this.performanceData.reduce((max, item) =>
        item.percentage > max.percentage ? item : max
      );
      return { score: best.percentage, assessment: best.name };
    },

    biggestImprovement() {
      let maxImprovement = 0;
      let period = "";

      for (let i = 1; i < this.performanceData.length; i++) {
        const improvement =
          this.performanceData[i].percentage -
          this.performanceData[i - 1].percentage;
        if (improvement > maxImprovement) {
          maxImprovement = improvement;
          period = `${this.performanceData[i - 1].label} to ${
            this.performanceData[i].label
          }`;
        }
      }

      return { percentage: maxImprovement.toFixed(1), period };
    },

    assessmentTypePerformance() {
      const types = {};

      this.performanceData.forEach((item) => {
        if (!types[item.type]) {
          types[item.type] = { scores: [], count: 0 };
        }
        types[item.type].scores.push(item.percentage);
        types[item.type].count++;
      });

      return Object.keys(types).map((type) => {
        const scores = types[type].scores;
        const average =
          scores.reduce((sum, score) => sum + score, 0) / scores.length;
        const trend =
          scores.length > 1 ? scores[scores.length - 1] - scores[0] : 0;

        return {
          type,
          name: type + "s",
          average: average.toFixed(1),
          count: types[type].count,
          trend: trend.toFixed(1),
        };
      });
    },

    monthlyProgress() {
      const months = {};

      this.performanceData.forEach((item) => {
        if (!months[item.label]) {
          months[item.label] = { scores: [], count: 0 };
        }
        months[item.label].scores.push(item.percentage);
        months[item.label].count++;
      });

      return Object.keys(months).map((month) => {
        const scores = months[month].scores;
        const average =
          scores.reduce((sum, score) => sum + score, 0) / scores.length;

        return {
          month,
          average: average.toFixed(1),
          percentage: average,
          count: months[month].count,
        };
      });
    },

    improvementAreas() {
      return [
        {
          area: "Quiz Performance",
          description: "Focus on quick recall and time management",
          currentAverage: 73,
          target: 85,
          priority: "high",
          icon: "⚡",
        },
        {
          area: "Lab Exercises",
          description: "Strengthen practical implementation skills",
          currentAverage: 78,
          target: 88,
          priority: "medium",
          icon: "🔬",
        },
        {
          area: "Assignments",
          description: "Maintain current excellent performance",
          currentAverage: 85,
          target: 90,
          priority: "low",
          icon: "📝",
        },
      ];
    },

    performanceInsights() {
      return [
        {
          id: 1,
          type: "positive",
          icon: "🎉",
          title: "Consistent Improvement",
          description:
            "Youve shown steady improvement over the last 3 assessments.",
          metrics: [
            { label: "Improvement Rate", value: "+12%" },
            { label: "Consistency Score", value: "85%" },
          ],
        },
        {
          id: 2,
          type: "warning",
          icon: "⚠️",
          title: "Quiz Performance Gap",
          description:
            "Your quiz scores are below your assignment performance.",
          metrics: [
            { label: "Quiz Average", value: "73%" },
            { label: "Assignment Average", value: "85%" },
          ],
        },
        {
          id: 3,
          type: "info",
          icon: "💡",
          title: "Peak Performance Time",
          description: "You perform best in November assessments.",
          metrics: [
            { label: "Best Month", value: "November" },
            { label: "Average Score", value: "87%" },
          ],
        },
      ];
    },
  },
  methods: {
    updateCourse(courseId) {
      this.selectedCourseId = courseId;
      this.updateTrends();
    },

    updateTimeRange(timeRange) {
      this.selectedTimeRange = timeRange;
      this.updateTrends();
    },

    updateChartType(chartType) {
      this.selectedChartType = chartType;
    },

    updateTrends() {
      // This would typically make an API call to fetch new data
      console.log(
        "Updating trends for course:",
        this.selectedCourseId,
        "range:",
        this.selectedTimeRange
      );
      // TODO: Replace with actual API call
      // this.loadTrendsData();
    },

    async loadTrendsData() {
      try {
        // TODO: Replace with actual API calls
        // const response = await this.$http.get('/api/student/performance-trends', {
        //   params: {
        //     courseId: this.selectedCourseId,
        //     timeRange: this.selectedTimeRange,
        //   }
        // });
        // this.performanceData = response.data.performanceData;
        // this.classAverageData = response.data.classAverageData;
      } catch (error) {
        console.error("Error loading trends data:", error);
        this.showError("Failed to load trends data");
      }
    },

    exportTrendsReport() {
      // Generate and download trends report
      console.log("Exporting trends report...");
      // TODO: Implement export functionality
    },

    setPerformanceGoals() {
      // Open goal setting modal
      console.log("Setting performance goals...");
      // TODO: Implement goal setting modal
    },

    shareProgress() {
      // Share progress with advisor/parents
      console.log("Sharing progress...");
      // TODO: Implement sharing functionality
    },

    scheduleReview() {
      // Schedule performance review meeting
      console.log("Scheduling review...");
      // TODO: Implement scheduling functionality
    },

    showError(message) {
      // TODO: Implement proper error handling/notification
      alert(message);
    },
  },
  async mounted() {
    // Load initial trends data
    await this.loadTrendsData();
  },
};
</script>

<style scoped>
.performance-trends {
  padding: 20px;
  background-color: #f8fafc;
  min-height: 100vh;
}

/* Global responsive adjustments */
@media (max-width: 768px) {
  .performance-trends {
    padding: 16px;
  }
}
</style>
