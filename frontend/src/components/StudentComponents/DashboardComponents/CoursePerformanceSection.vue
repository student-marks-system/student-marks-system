<template>
  <div class="course-performance-section">
    <div class="section-header">
      <h2>Course Performance</h2>
      <button class="view-all-btn" @click="$emit('view-all-courses')">
        View All Details
      </button>
    </div>

    <div class="course-cards">
      <CourseCard
        v-for="course in courses"
        :key="course.id"
        :course="course"
        @course-clicked="handleCourseClick"
      />
    </div>
  </div>
</template>

<script>
import CourseCard from "./CourseCard.vue";

export default {
  name: "CoursePerformanceSection",
  components: {
    CourseCard,
  },
  props: {
    courses: {
      type: Array,
      required: true,
      default: () => [],
    },
  },
  emits: ["view-all-courses", "course-details"],
  methods: {
    handleCourseClick(courseId) {
      this.$emit("course-details", courseId);
    },
  },
};
</script>

<style scoped>
.course-performance-section {
  margin-bottom: 30px;
}

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.section-header h2 {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1e293b;
  margin: 0;
}

.view-all-btn {
  background: #3b82f6;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s;
}

.view-all-btn:hover {
  background: #2563eb;
}

.course-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 20px;
}

/* Responsive Design */
@media (max-width: 768px) {
  .course-cards {
    grid-template-columns: 1fr;
  }
}
</style>
