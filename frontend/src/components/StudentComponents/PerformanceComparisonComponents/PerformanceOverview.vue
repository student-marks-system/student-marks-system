<template>
  <div class="overview-cards">
    <div class="overview-card your-performance">
      <div class="card-header">
        <h3>Your Performance</h3>
        <div class="performance-icon">👤</div>
      </div>
      <div class="card-stats">
        <div class="stat-item">
          <span class="stat-value">{{ course.yourAverage }}%</span>
          <span class="stat-label">Overall Average</span>
        </div>
        <div class="stat-item">
          <span class="stat-value">#{{ course.yourRank }}</span>
          <span class="stat-label">Class Rank</span>
        </div>
        <div class="stat-item">
          <span class="stat-value">{{ course.yourPercentile }}%</span>
          <span class="stat-label">Percentile</span>
        </div>
      </div>
    </div>

    <div class="overview-card class-stats">
      <div class="card-header">
        <h3>Class Statistics</h3>
        <div class="performance-icon">👥</div>
      </div>
      <div class="card-stats">
        <div class="stat-item">
          <span class="stat-value">{{ course.classAverage }}%</span>
          <span class="stat-label">Class Average</span>
        </div>
        <div class="stat-item">
          <span class="stat-value">{{ course.totalStudents }}</span>
          <span class="stat-label">Total Students</span>
        </div>
        <div class="stat-item">
          <span class="stat-value">{{ course.standardDeviation }}%</span>
          <span class="stat-label">Std Deviation</span>
        </div>
      </div>
    </div>

    <div class="overview-card performance-trend">
      <div class="card-header">
        <h3>Performance Trend</h3>
        <div class="performance-icon">📈</div>
      </div>
      <div class="card-stats">
        <div class="stat-item">
          <span class="stat-value" :class="getTrendClass(course.trend)">
            {{ course.trend }}%
          </span>
          <span class="stat-label">Change from Last Assessment</span>
        </div>
        <div class="stat-item">
          <span class="stat-value">
            {{ getAboveAverageCount() }}/{{ course.assessments.length }}
          </span>
          <span class="stat-label">Above Average</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "PerformanceOverview",
  props: {
    course: {
      type: Object,
      required: true,
      default: () => ({}),
    },
  },
  methods: {
    getTrendClass(trend) {
      return trend > 0
        ? "trend-positive"
        : trend < 0
        ? "trend-negative"
        : "trend-neutral";
    },

    getAboveAverageCount() {
      if (!this.course.assessments) return 0;
      return this.course.assessments.filter((a) => a.yourScore > a.classAverage)
        .length;
    },
  },
};
</script>

<style scoped>
.overview-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.overview-card {
  background: white;
  border-radius: 12px;
  padding: 24px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  border-left: 4px solid;
}

.overview-card.your-performance {
  border-left-color: #3b82f6;
}

.overview-card.class-stats {
  border-left-color: #10b981;
}

.overview-card.performance-trend {
  border-left-color: #f59e0b;
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.card-header h3 {
  margin: 0;
  font-size: 1.1rem;
  font-weight: 700;
  color: #1e293b;
}

.performance-icon {
  font-size: 1.5rem;
  opacity: 0.7;
}

.card-stats {
  display: flex;
  justify-content: space-between;
  gap: 16px;
}

.stat-item {
  text-align: center;
  flex: 1;
}

.stat-value {
  display: block;
  font-size: 1.8rem;
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 4px;
}

.stat-value.trend-positive {
  color: #10b981;
}

.stat-value.trend-negative {
  color: #ef4444;
}

.stat-label {
  font-size: 0.8rem;
  color: #64748b;
  font-weight: 500;
}

@media (max-width: 768px) {
  .overview-cards {
    grid-template-columns: 1fr;
  }

  .card-stats {
    flex-direction: column;
    gap: 12px;
  }
}
</style>
