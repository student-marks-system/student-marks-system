<template>
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon">📊</div>
      <div class="stat-content">
        <h3>Overall GPA</h3>
        <p class="stat-value">{{ overallGPA }}</p>
        <span class="stat-change" :class="gpaChangeClass">
          {{ gpaChange }}
        </span>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon">📚</div>
      <div class="stat-content">
        <h3>Enrolled Courses</h3>
        <p class="stat-value">{{ enrolledCoursesCount }}</p>
        <span class="stat-label">Active</span>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon">🏆</div>
      <div class="stat-content">
        <h3>Class Rank</h3>
        <p class="stat-value">#{{ classRank }}</p>
        <span class="stat-label">of {{ totalStudents }}</span>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon">📈</div>
      <div class="stat-content">
        <h3>Percentile</h3>
        <p class="stat-value">{{ percentile }}%</p>
        <span class="stat-label">Above average</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "StatsGrid",
  props: {
    overallGPA: {
      type: [String, Number],
      required: true,
      default: "0.00",
    },
    gpaChange: {
      type: String,
      required: true,
      default: "+0.00",
    },
    enrolledCoursesCount: {
      type: Number,
      required: true,
      default: 0,
    },
    classRank: {
      type: Number,
      required: true,
      default: 0,
    },
    totalStudents: {
      type: Number,
      required: true,
      default: 0,
    },
    percentile: {
      type: Number,
      required: true,
      default: 0,
    },
  },
  computed: {
    gpaChangeClass() {
      return this.gpaChange.startsWith("+") ? "increase" : "decrease";
    },
  },
};
</script>

<style scoped>
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.stat-card {
  background: white;
  padding: 24px;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
  gap: 16px;
  transition: transform 0.2s, box-shadow 0.2s;
}

.stat-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

.stat-icon {
  font-size: 2rem;
  width: 60px;
  height: 60px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f1f5f9;
  border-radius: 12px;
}

.stat-content h3 {
  margin: 0 0 8px 0;
  font-size: 0.9rem;
  color: #64748b;
  font-weight: 600;
}

.stat-value {
  font-size: 1.8rem;
  font-weight: 700;
  color: #1e293b;
  margin: 0;
}

.stat-change {
  font-size: 0.8rem;
  font-weight: 600;
  padding: 2px 8px;
  border-radius: 12px;
}

.stat-change.increase {
  color: #059669;
  background: #d1fae5;
}

.stat-change.decrease {
  color: #dc2626;
  background: #fee2e2;
}

.stat-label {
  font-size: 0.8rem;
  color: #64748b;
}

/* Responsive Design */
@media (max-width: 768px) {
  .stats-grid {
    grid-template-columns: 1fr;
  }
}
</style>
