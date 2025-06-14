<template>
  <div class="what-if-simulator">
    <!-- Header -->
    <SimulatorHeader />

    <!-- Course Selection -->
    <CourseSelection
      :courses="enrolledCourses"
      :selected-course="selectedCourse"
      @course-selected="selectCourse"
    />

    <!-- Simulator Content -->
    <div v-if="selectedCourse" class="simulator-content">
      <!-- Current Status Overview -->
      <StatusOverview
        :course="selectedCourse"
        :projected-grade="projectedGrade"
        :grade-goals="gradeGoals"
        :selected-goal="selectedGoal"
        @goal-selected="selectGoal"
      />

      <!-- Assessment Simulator -->
      <AssessmentSimulator
        :assessments="selectedCourse.assessments"
        @update-projections="updateProjections"
        @reset-to-actual="resetToActual"
        @fill-optimistic="fillOptimistic"
        @fill-realistic="fillRealistic"
        @fill-conservative="fillConservative"
      />

      <!-- Scenario Analysis -->
      <ScenarioAnalysis :scenarios="scenarios" />

      <!-- Grade Breakdown Chart -->
      <GradeBreakdownVisualization
        :grade-breakdown="gradeBreakdown"
        :completed-weight="completedWeight"
        :current-contribution="currentContribution"
        :projected-contribution="projectedContribution"
      />

      <!-- Recommendations -->
      <RecommendationsSection
        :recommendations="recommendations"
        @recommendation-action="handleRecommendationAction"
      />
    </div>

    <!-- No Course Selected State -->
    <NoCourseSelected v-else />
  </div>
</template>

<script>
// Import components
import SimulatorHeader from "@/components/StudentComponents/WhatIfSimulatorComponents/SimulatorHeader.vue";
import CourseSelection from "@/components/StudentComponents/WhatIfSimulatorComponents/CourseSelection.vue";
import StatusOverview from "@/components/StudentComponents/WhatIfSimulatorComponents/StatusOverview.vue";
import AssessmentSimulator from "@/components/StudentComponents/WhatIfSimulatorComponents/AssessmentSimulator.vue";
import ScenarioAnalysis from "@/components/StudentComponents/WhatIfSimulatorComponents/ScenarioAnalysis.vue";
import GradeBreakdownVisualization from "@/components/StudentComponents/WhatIfSimulatorComponents/GradeBreakdownVisualization.vue";
import RecommendationsSection from "@/components/StudentComponents/WhatIfSimulatorComponents/RecommendationsSection.vue";
import NoCourseSelected from "@/components/StudentComponents/WhatIfSimulatorComponents/NoCourseSelected.vue";

export default {
  name: "WhatIfSimulator",
  components: {
    SimulatorHeader,
    CourseSelection,
    StatusOverview,
    AssessmentSimulator,
    ScenarioAnalysis,
    GradeBreakdownVisualization,
    RecommendationsSection,
    NoCourseSelected,
  },
  data() {
    return {
      selectedCourse: null,
      selectedGoal: null,
      enrolledCourses: [
        {
          id: 1,
          code: "SECJ3483",
          name: "Web Technology",
          currentGrade: "B+",
          currentMarks: 78.5,
          completedWeight: 50,
          assessments: [
            {
              id: 1,
              name: "Quiz 1",
              type: "quiz",
              weight: 10,
              maxScore: 20,
              actualScore: 18,
              completed: true,
              dueDate: null,
              description: "HTML and CSS Fundamentals",
            },
            {
              id: 2,
              name: "Assignment 1",
              type: "assignment",
              weight: 15,
              maxScore: 100,
              actualScore: 85,
              completed: true,
              dueDate: null,
              description: "Responsive Website Design",
            },
            {
              id: 3,
              name: "Lab Exercise 1",
              type: "lab",
              weight: 5,
              maxScore: 50,
              actualScore: 45,
              completed: true,
              dueDate: null,
              description: "JavaScript DOM Manipulation",
            },
            {
              id: 4,
              name: "Test 1",
              type: "test",
              weight: 20,
              maxScore: 100,
              actualScore: 78,
              completed: true,
              dueDate: null,
              description: "Mid-semester Examination",
            },
            {
              id: 5,
              name: "Assignment 2",
              type: "assignment",
              weight: 20,
              maxScore: 100,
              predictedScore: 80,
              completed: false,
              dueDate: "2024-11-15",
              description: "Full-stack Web Application",
            },
            {
              id: 6,
              name: "Final Exam",
              type: "final_exam",
              weight: 30,
              maxScore: 100,
              predictedScore: 75,
              completed: false,
              dueDate: "2024-12-15",
              description: "Comprehensive Final Examination",
            },
          ],
        },
        {
          id: 2,
          code: "SECJ2203",
          name: "Database Systems",
          currentGrade: "A-",
          currentMarks: 85.2,
          completedWeight: 40,
          assessments: [
            {
              id: 7,
              name: "Quiz 1",
              type: "quiz",
              weight: 10,
              maxScore: 20,
              actualScore: 19,
              completed: true,
              dueDate: null,
              description: "Database Fundamentals",
            },
            {
              id: 8,
              name: "Assignment 1",
              type: "assignment",
              weight: 20,
              maxScore: 100,
              actualScore: 88,
              completed: true,
              dueDate: null,
              description: "ER Diagram Design",
            },
            {
              id: 9,
              name: "Lab Exercise 1",
              type: "lab",
              weight: 10,
              maxScore: 100,
              actualScore: 92,
              completed: true,
              dueDate: null,
              description: "SQL Queries",
            },
            {
              id: 10,
              name: "Test 1",
              type: "test",
              weight: 25,
              maxScore: 100,
              predictedScore: 85,
              completed: false,
              dueDate: "2024-11-20",
              description: "Database Design and Normalization",
            },
            {
              id: 11,
              name: "Final Project",
              type: "assignment",
              weight: 25,
              maxScore: 100,
              predictedScore: 90,
              completed: false,
              dueDate: "2024-12-10",
              description: "Database Application Development",
            },
            {
              id: 12,
              name: "Final Exam",
              type: "final_exam",
              weight: 10,
              maxScore: 100,
              predictedScore: 80,
              completed: false,
              dueDate: "2024-12-18",
              description: "Comprehensive Final Examination",
            },
          ],
        },
        {
          id: 3,
          code: "SECJ3103",
          name: "Software Engineering",
          currentGrade: "B",
          currentMarks: 72.0,
          completedWeight: 35,
          assessments: [
            {
              id: 13,
              name: "Quiz 1",
              type: "quiz",
              weight: 15,
              maxScore: 20,
              actualScore: 14,
              completed: true,
              dueDate: null,
              description: "Software Development Life Cycle",
            },
            {
              id: 14,
              name: "Assignment 1",
              type: "assignment",
              weight: 20,
              maxScore: 100,
              actualScore: 75,
              completed: true,
              dueDate: null,
              description: "Requirements Analysis",
            },
            {
              id: 15,
              name: "Test 1",
              type: "test",
              weight: 30,
              maxScore: 100,
              predictedScore: 75,
              completed: false,
              dueDate: "2024-11-25",
              description: "Design Patterns and Architecture",
            },
            {
              id: 16,
              name: "Final Project",
              type: "assignment",
              weight: 25,
              maxScore: 100,
              predictedScore: 80,
              completed: false,
              dueDate: "2024-12-05",
              description: "Software Development Project",
            },
            {
              id: 17,
              name: "Final Exam",
              type: "final_exam",
              weight: 10,
              maxScore: 100,
              predictedScore: 70,
              completed: false,
              dueDate: "2024-12-20",
              description: "Comprehensive Final Examination",
            },
          ],
        },
      ],
    };
  },
  computed: {
    projectedGrade() {
      if (!this.selectedCourse)
        return { letter: "N/A", percentage: 0, status: "", trend: "" };

      const totalScore = this.calculateTotalScore();
      const letter = this.getLetterGrade(totalScore);
      const current = this.selectedCourse.currentMarks;

      let status = "";
      let trend = "";

      if (totalScore > current) {
        status = "Improving";
        trend = "positive";
      } else if (totalScore < current) {
        status = "Declining";
        trend = "negative";
      } else {
        status = "Stable";
        trend = "neutral";
      }

      return {
        letter,
        percentage: totalScore.toFixed(1),
        status,
        trend,
      };
    },

    gradeGoals() {
      if (!this.selectedCourse) return [];

      const goals = [
        { grade: "A+", minScore: 90 },
        { grade: "A", minScore: 80 },
        { grade: "A-", minScore: 75 },
        { grade: "B+", minScore: 70 },
        { grade: "B", minScore: 65 },
        { grade: "B-", minScore: 60 },
        { grade: "C+", minScore: 55 },
        { grade: "C", minScore: 50 },
      ];

      return goals.map((goal) => {
        const requiredScore = this.calculateRequiredScore(goal.minScore);
        return {
          grade: goal.grade,
          required: requiredScore.toFixed(1),
          achievable: requiredScore <= 100 && requiredScore >= 0,
        };
      });
    },

    scenarios() {
      if (!this.selectedCourse) return {};

      return {
        bestCase: this.calculateBestCase(),
        realistic: this.calculateRealisticCase(),
        worstCase: this.calculateWorstCase(),
      };
    },

    gradeBreakdown() {
      if (!this.selectedCourse) return [];

      return this.selectedCourse.assessments.map((assessment) => {
        const score = assessment.completed
          ? (assessment.actualScore / assessment.maxScore) * 100
          : (assessment.predictedScore / assessment.maxScore) * 100;

        return {
          name: assessment.name,
          weight: assessment.weight,
          score: score.toFixed(1),
          status: assessment.completed ? "completed" : "predicted",
        };
      });
    },

    completedWeight() {
      if (!this.selectedCourse) return 0;
      return this.selectedCourse.assessments
        .filter((a) => a.completed)
        .reduce((sum, a) => sum + a.weight, 0);
    },

    currentContribution() {
      if (!this.selectedCourse) return 0;
      return this.selectedCourse.assessments
        .filter((a) => a.completed)
        .reduce((sum, a) => sum + (a.actualScore / a.maxScore) * a.weight, 0);
    },

    projectedContribution() {
      if (!this.selectedCourse) return 0;
      return this.selectedCourse.assessments
        .filter((a) => !a.completed)
        .reduce(
          (sum, a) => sum + (a.predictedScore / a.maxScore) * a.weight,
          0
        );
    },

    recommendations() {
      if (!this.selectedCourse) return [];

      const recommendations = [];
      const totalScore = this.calculateTotalScore();

      // Add recommendations based on current performance
      if (totalScore < 65) {
        recommendations.push({
          id: 1,
          title: "Focus on Upcoming Assessments",
          description:
            "Your current trajectory suggests you need to improve significantly in remaining assessments.",
          priority: "high",
          icon: "🚨",
          focusArea: "All remaining assessments",
          impact: "Prevent failing grade",
          actionText: "Create Study Plan",
          timeframe: "Immediate",
        });
      }

      if (totalScore >= 65 && totalScore < 75) {
        recommendations.push({
          id: 2,
          title: "Target Higher Performance",
          description:
            "You're on track for a passing grade, but could achieve better with focused effort.",
          priority: "medium",
          icon: "📈",
          focusArea: "High-weight assessments",
          impact: "Improve final grade by 5-10%",
          actionText: "Optimize Strategy",
          timeframe: "2-3 weeks",
        });
      }

      if (totalScore >= 75) {
        recommendations.push({
          id: 3,
          title: "Maintain Excellence",
          description:
            "You're performing well! Focus on consistency to secure your target grade.",
          priority: "low",
          icon: "⭐",
          focusArea: "Consistency across all areas",
          impact: "Secure current grade level",
          actionText: "Stay on Track",
          timeframe: "Ongoing",
        });
      }

      // Check for specific assessment types that need attention
      const quizAverage = this.getAssessmentTypeAverage("quiz");
      if (quizAverage < 70) {
        recommendations.push({
          id: 4,
          title: "Improve Quiz Performance",
          description:
            "Your quiz scores are below optimal. Focus on quick recall and time management.",
          priority: "medium",
          icon: "⚡",
          focusArea: "Quiz preparation",
          impact: "Improve overall grade by 3-5%",
          actionText: "Practice Quizzes",
        });
      }

      return recommendations;
    },
  },
  methods: {
    selectCourse(course) {
      this.selectedCourse = { ...course };
      this.updateProjections();
    },

    selectGoal(grade) {
      this.selectedGoal = grade;
    },

    calculateTotalScore() {
      if (!this.selectedCourse) return 0;

      let totalScore = 0;

      this.selectedCourse.assessments.forEach((assessment) => {
        const score = assessment.completed
          ? assessment.actualScore
          : assessment.predictedScore;
        totalScore += (score / assessment.maxScore) * assessment.weight;
      });

      return totalScore;
    },

    getLetterGrade(percentage) {
      if (percentage >= 90) return "A+";
      if (percentage >= 80) return "A";
      if (percentage >= 75) return "A-";
      if (percentage >= 70) return "B+";
      if (percentage >= 65) return "B";
      if (percentage >= 60) return "B-";
      if (percentage >= 55) return "C+";
      if (percentage >= 50) return "C";
      return "F";
    },

    calculateRequiredScore(targetPercentage) {
      if (!this.selectedCourse) return 0;

      const currentContribution = this.currentContribution;
      const remainingWeight = 100 - this.completedWeight;

      if (remainingWeight === 0) return 0;

      const requiredContribution = targetPercentage - currentContribution;
      return (requiredContribution / remainingWeight) * 100;
    },

    updateProjections() {
      // This method is called when predicted scores change
      // The computed properties will automatically update
    },

    resetToActual() {
      if (!this.selectedCourse) return;

      this.selectedCourse.assessments.forEach((assessment) => {
        if (!assessment.completed) {
          assessment.predictedScore = 0;
        }
      });
      this.updateProjections();
    },

    fillOptimistic() {
      if (!this.selectedCourse) return;

      this.selectedCourse.assessments.forEach((assessment) => {
        if (!assessment.completed) {
          assessment.predictedScore = Math.round(assessment.maxScore * 0.9); // 90%
        }
      });
      this.updateProjections();
    },

    fillRealistic() {
      if (!this.selectedCourse) return;

      this.selectedCourse.assessments.forEach((assessment) => {
        if (!assessment.completed) {
          assessment.predictedScore = Math.round(assessment.maxScore * 0.75); // 75%
        }
      });
      this.updateProjections();
    },

    fillConservative() {
      if (!this.selectedCourse) return;

      this.selectedCourse.assessments.forEach((assessment) => {
        if (!assessment.completed) {
          assessment.predictedScore = Math.round(assessment.maxScore * 0.6); // 60%
        }
      });
      this.updateProjections();
    },

    calculateBestCase() {
      if (!this.selectedCourse) return {};

      // Calculate assuming 95% on all remaining assessments
      let totalScore = this.currentContribution;
      const pendingAssessments = this.selectedCourse.assessments.filter(
        (a) => !a.completed
      );

      pendingAssessments.forEach((assessment) => {
        totalScore += 0.95 * assessment.weight;
      });

      return {
        grade: this.getLetterGrade(totalScore),
        percentage: totalScore.toFixed(1),
        description:
          "Achieving excellent performance on all remaining assessments",
        requirements: [
          "Score 95% or higher on all remaining assessments",
          "Consistent study and preparation",
          "Excel in final examination",
        ],
      };
    },

    calculateRealisticCase() {
      if (!this.selectedCourse) return {};

      // Calculate based on current trend (average of completed assessments)
      const completedAssessments = this.selectedCourse.assessments.filter(
        (a) => a.completed
      );
      const averagePerformance =
        completedAssessments.reduce(
          (sum, a) => sum + a.actualScore / a.maxScore,
          0
        ) / completedAssessments.length;

      let totalScore = this.currentContribution;
      const pendingAssessments = this.selectedCourse.assessments.filter(
        (a) => !a.completed
      );

      pendingAssessments.forEach((assessment) => {
        totalScore += averagePerformance * assessment.weight;
      });

      return {
        grade: this.getLetterGrade(totalScore),
        percentage: totalScore.toFixed(1),
        description: "Maintaining current performance level",
        requirements: [
          `Maintain ${(averagePerformance * 100).toFixed(
            0
          )}% average on remaining assessments`,
          "Continue current study habits",
          "Stay consistent with effort",
        ],
      };
    },

    calculateWorstCase() {
      if (!this.selectedCourse) return {};

      // Calculate assuming 50% on all remaining assessments
      let totalScore = this.currentContribution;
      const pendingAssessments = this.selectedCourse.assessments.filter(
        (a) => !a.completed
      );

      pendingAssessments.forEach((assessment) => {
        totalScore += 0.5 * assessment.weight;
      });

      return {
        grade: this.getLetterGrade(totalScore),
        percentage: totalScore.toFixed(1),
        description: "Poor performance on remaining assessments",
        requirements: [
          "Score at least 65% on remaining assessments",
          "Seek help from instructors",
          "Develop comprehensive study plan",
          "Consider tutoring or study groups",
        ],
      };
    },

    getAssessmentTypeAverage(type) {
      if (!this.selectedCourse) return 0;

      const assessments = this.selectedCourse.assessments.filter(
        (a) => a.type === type && a.completed
      );

      if (assessments.length === 0) return 0;

      const total = assessments.reduce(
        (sum, a) => sum + (a.actualScore / a.maxScore) * 100,
        0
      );

      return total / assessments.length;
    },

    handleRecommendationAction(recommendation) {
      // Handle recommendation actions
      console.log("Recommendation action:", recommendation.actionText);
      // TODO: Implement specific actions based on recommendation type

      switch (recommendation.id) {
        case 1:
          // Create study plan
          this.$router.push("/study-planner");
          break;
        case 2:
          // Optimize strategy
          this.$router.push("/performance-analysis");
          break;
        case 3:
          // Stay on track
          this.$router.push("/goal-tracker");
          break;
        case 4:
          // Practice quizzes
          this.$router.push("/quiz-practice");
          break;
        default:
          console.log("Unknown recommendation action");
      }
    },

    async loadSimulatorData() {
      try {
        // TODO: Replace with actual API calls
        // const response = await this.$http.get('/api/student/simulator-data');
        // this.enrolledCourses = response.data;
      } catch (error) {
        console.error("Error loading simulator data:", error);
        this.showError("Failed to load simulator data");
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
      this.selectedCourse = { ...this.enrolledCourses[0] };
    }

    // Load simulator data
    await this.loadSimulatorData();
  },
};
</script>

<style scoped>
.what-if-simulator {
  padding: 20px;
  background-color: #f8fafc;
  min-height: 100vh;
}

.simulator-content {
  /* Main content styles if needed */
}

/* Global responsive adjustments */
@media (max-width: 768px) {
  .what-if-simulator {
    padding: 16px;
  }
}
</style>
