<template>
  <div class="course-card" @click="$emit('course-clicked', course.id)">
    <div class="course-header">
      <h3>{{ course.code }}</h3>
      <span class="course-credits">{{ course.credits }} Credits</span>
    </div>
    <h4 class="course-name">{{ course.name }}</h4>

    <!-- Progress Bar -->
    <div class="progress-section">
      <div class="progress-label">
        <span>Progress</span>
        <span class="progress-percentage">
          {{ course.currentMarks }}/{{ course.totalMarks }}
        </span>
      </div>
      <div class="progress-bar">
        <div
          class="progress-fill"
          :style="{ width: course.progressPercentage + '%' }"
          :class="getProgressClass(course.progressPercentage)"
        ></div>
      </div>
    </div>

    <!-- Quick Stats -->
    <div class="course-stats">
      <div class="stat-item">
        <span class="stat-label">Current Grade</span>
        <span class="stat-value" :class="getGradeClass(course.currentGrade)">
          {{ course.currentGrade }}
        </span>
      </div>
      <div class="stat-item">
        <span class="stat-label">Class Average</span>
        <span class="stat-value">{{ course.classAverage }}%</span>
      </div>
    </div>

    <!-- Assessment Status -->
    <div class="assessment-status">
      <div class="status-item">
        <span class="status-dot completed"></span>
        <span>{{ course.completedAssessments }} Completed</span>
      </div>
      <div class="status-item">
        <span class="status-dot pending"></span>
        <span>{{ course.pendingAssessments }} Pending</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "CourseCard",
  props: {
    course: {
      type: Object,
      required: true,
      default: () => ({}),
    },
  },
  emits: ["course-clicked"],
  methods: {
    getProgressClass(percentage) {
      if (percentage >= 80) return "excellent";
      if (percentage >= 70) return "good";
      if (percentage >= 60) return "average";
      return "needs-improvement";
    },

    getGradeClass(grade) {
      const gradeMap = {
        "A+": "grade-a-plus",
        A: "grade-a",
        "A-": "grade-a-minus",
        "B+": "grade-b-plus",
        B: "grade-b",
        "B-": "grade-b-minus",
        "C+": "grade-c-plus",
        C: "grade-c",
      };
      return gradeMap[grade] || "grade-default";
    },
  },
};
</script>

<style scoped>
.course-card {
  background: white;
  border-radius: 12px;
  padding: 24px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;
}

.course-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

.course-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 8px;
}

.course-header h3 {
  font-size: 1.2rem;
  font-weight: 700;
  color: #1e293b;
  margin: 0;
}

.course-credits {
  background: #e2e8f0;
  color: #475569;
  padding: 4px 8px;
  border-radius: 6px;
  font-size: 0.8rem;
  font-weight: 600;
}

.course-name {
  font-size: 1rem;
  color: #64748b;
  margin: 0 0 20px 0;
  font-weight: 500;
}

.progress-section {
  margin-bottom: 16px;
}

.progress-label {
  display: flex;
  justify-content: space-between;
  margin-bottom: 8px;
  font-size: 0.9rem;
  font-weight: 600;
}

.progress-bar {
  width: 100%;
  height: 8px;
  background: #e2e8f0;
  border-radius: 4px;
  overflow: hidden;
}

.progress-fill {
  height: 100%;
  transition: width 0.3s ease;
  border-radius: 4px;
}

.progress-fill.excellent {
  background: #059669;
}

.progress-fill.good {
  background: #3b82f6;
}

.progress-fill.average {
  background: #f59e0b;
}

.progress-fill.needs-improvement {
  background: #ef4444;
}

.course-stats {
  display: flex;
  justify-content: space-between;
  margin-bottom: 16px;
}

.stat-item {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.stat-item .stat-label {
  font-size: 0.8rem;
  color: #64748b;
}

.stat-item .stat-value {
  font-weight: 700;
  font-size: 1rem;
}

/* Grade Colors */
.grade-a-plus,
.grade-a {
  color: #059669;
}

.grade-a-minus,
.grade-b-plus {
  color: #3b82f6;
}

.grade-b {
  color: #f59e0b;
}

.grade-b-minus,
.grade-c-plus,
.grade-c {
  color: #ef4444;
}

.assessment-status {
  display: flex;
  gap: 16px;
}

.status-item {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 0.8rem;
  color: #64748b;
}

.status-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
}

.status-dot.completed {
  background: #059669;
}

.status-dot.pending {
  background: #f59e0b;
}
</style>
