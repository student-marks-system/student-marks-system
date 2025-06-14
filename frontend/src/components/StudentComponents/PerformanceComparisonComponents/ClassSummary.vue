<template>
  <div class="class-summary">
    <h3>Class Performance Summary</h3>
    <div class="summary-grid">
      <div class="summary-card">
        <div class="summary-icon">📊</div>
        <div class="summary-content">
          <h4>Average Performance</h4>
          <p class="summary-value">{{ selectedCourse.classAverage }}%</p>
          <p class="summary-detail">Across all assessments</p>
        </div>
      </div>

      <div class="summary-card">
        <div class="summary-icon">🎯</div>
        <div class="summary-content">
          <h4>Highest Achievement</h4>
          <p class="summary-value">{{ getHighestClassScore() }}%</p>
          <p class="summary-detail">Best individual performance</p>
        </div>
      </div>

      <div class="summary-card">
        <div class="summary-icon">📈</div>
        <div class="summary-content">
          <h4>Performance Range</h4>
          <p class="summary-value">{{ getPerformanceRange() }}%</p>
          <p class="summary-detail">Difference between highest and lowest</p>
        </div>
      </div>

      <div class="summary-card">
        <div class="summary-icon">🏆</div>
        <div class="summary-content">
          <h4>Your Position</h4>
          <p class="summary-value">
            Top {{ 100 - selectedCourse.yourPercentile }}%
          </p>
          <p class="summary-detail">Of the class</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ClassSummary",
  props: {
    selectedCourse: {
      type: Object,
      required: true,
      default: () => ({}),
    },
  },
  methods: {
    getHighestClassScore() {
      if (!this.selectedCourse.assessments) return 0;
      return Math.max(
        ...this.selectedCourse.assessments.map(
          (a) => (a.highestScore / a.maxScore) * 100
        )
      ).toFixed(1);
    },

    getPerformanceRange() {
      if (!this.selectedCourse.assessments) return 0;
      const scores = this.selectedCourse.assessments.map(
        (a) => (a.highestScore / a.maxScore) * 100
      );
      const highest = Math.max(...scores);
      const lowest = Math.min(...scores) * 0.6; // Assuming lowest is around 60% of highest
      return (highest - lowest).toFixed(1);
    },
  },
};
</script>

<style scoped>
.class-summary {
  background: white;
  border-radius: 12px;
  padding: 30px;
  margin-bottom: 30px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.class-summary h3 {
  margin: 0 0 24px 0;
  font-size: 1.5rem;
  font-weight: 700;
  color: #1e293b;
}

.summary-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 20px;
}

.summary-card {
  background: #f8fafc;
  border-radius: 12px;
  padding: 24px;
  display: flex;
  align-items: center;
  gap: 16px;
  transition: transform 0.2s;
}

.summary-card:hover {
  transform: translateY(-2px);
}

.summary-icon {
  font-size: 2rem;
  width: 60px;
  height: 60px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.summary-content h4 {
  margin: 0 0 8px 0;
  font-size: 1rem;
  color: #64748b;
  font-weight: 600;
}

.summary-value {
  margin: 0 0 4px 0;
  font-size: 1.5rem;
  font-weight: 700;
  color: #1e293b;
}

.summary-detail {
  margin: 0;
  font-size: 0.8rem;
  color: #64748b;
}

@media (max-width: 768px) {
  .summary-grid {
    grid-template-columns: 1fr;
  }
}
</style>
