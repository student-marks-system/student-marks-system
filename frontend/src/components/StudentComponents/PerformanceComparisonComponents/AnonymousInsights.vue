<template>
  <div class="anonymous-insights">
    <h3>Anonymous Class Insights</h3>
    <div class="insights-grid">
      <div class="insight-card">
        <div class="insight-icon">💡</div>
        <div class="insight-content">
          <h4>Most Challenging Assessment</h4>
          <p>{{ getMostChallengingAssessment() }}</p>
          <span class="insight-detail">Based on class average scores</span>
        </div>
      </div>

      <div class="insight-card">
        <div class="insight-icon">⭐</div>
        <div class="insight-content">
          <h4>Best Class Performance</h4>
          <p>{{ getBestClassPerformance() }}</p>
          <span class="insight-detail">Highest average score achieved</span>
        </div>
      </div>

      <div class="insight-card">
        <div class="insight-icon">📚</div>
        <div class="insight-content">
          <h4>Improvement Opportunity</h4>
          <p>{{ getImprovementOpportunity() }}</p>
          <span class="insight-detail">Area where you can gain the most</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "AnonymousInsights",
  props: {
    selectedCourse: {
      type: Object,
      required: true,
      default: () => ({}),
    },
  },
  methods: {
    getMostChallengingAssessment() {
      if (
        !this.selectedCourse.assessments ||
        this.selectedCourse.assessments.length === 0
      ) {
        return "No assessments available";
      }

      return this.selectedCourse.assessments.reduce((min, assessment) =>
        assessment.classAverage / assessment.maxScore <
        min.classAverage / min.maxScore
          ? assessment
          : min
      ).name;
    },

    getBestClassPerformance() {
      if (
        !this.selectedCourse.assessments ||
        this.selectedCourse.assessments.length === 0
      ) {
        return "No assessments available";
      }

      return this.selectedCourse.assessments.reduce((max, assessment) =>
        assessment.classAverage / assessment.maxScore >
        max.classAverage / max.maxScore
          ? assessment
          : max
      ).name;
    },

    getImprovementOpportunity() {
      if (
        !this.selectedCourse.assessments ||
        this.selectedCourse.assessments.length === 0
      ) {
        return "No assessments available";
      }

      return this.selectedCourse.assessments.reduce((min, assessment) => {
        const yourPercentage =
          (assessment.yourScore / assessment.maxScore) * 100;
        const classPercentage =
          (assessment.classAverage / assessment.maxScore) * 100;
        const gap = classPercentage - yourPercentage;

        const minYourPercentage = (min.yourScore / min.maxScore) * 100;
        const minClassPercentage = (min.classAverage / min.maxScore) * 100;
        const minGap = minClassPercentage - minYourPercentage;

        return gap > minGap ? assessment : min;
      }).name;
    },
  },
};
</script>

<style scoped>
.anonymous-insights {
  background: white;
  border-radius: 12px;
  padding: 30px;
  margin-bottom: 30px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.anonymous-insights h3 {
  margin: 0 0 24px 0;
  font-size: 1.5rem;
  font-weight: 700;
  color: #1e293b;
}

.insights-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 20px;
}

.insight-card {
  background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
  border-radius: 12px;
  padding: 24px;
  border-left: 4px solid #3b82f6;
}

.insight-icon {
  font-size: 2rem;
  margin-bottom: 16px;
}

.insight-content h4 {
  margin: 0 0 12px 0;
  font-size: 1.1rem;
  font-weight: 700;
  color: #1e293b;
}

.insight-content p {
  margin: 0 0 8px 0;
  font-size: 1rem;
  color: #1e293b;
  font-weight: 600;
}

.insight-detail {
  font-size: 0.8rem;
  color: #64748b;
  font-style: italic;
}

@media (max-width: 768px) {
  .insights-grid {
    grid-template-columns: 1fr;
  }
}
</style>
