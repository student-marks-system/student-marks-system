<template>
  <div class="assign-container">
    <h1 class="title">👩‍🏫 Assign Lecturers to Courses</h1>

    <div v-for="course in courses" :key="course.id" class="course-card">
      <div class="course-header">
        <div class="course-info">
          <h3 class="course-name">{{ course.course_code }} — {{ course.course_name }}</h3>
          <div class="badges">
            <span class="badge">🎓 Credits: {{ course.credits }}</span>
            <span class="badge">📆 Semester: {{ course.semester }}</span>
            <span class="badge">📚 Year: {{ course.academic_year }}</span>
            <span class="badge status" :class="course.is_active ? 'active' : 'inactive'">
              {{ course.is_active ? "🟢 Active" : "🔴 Inactive" }}
            </span>
          </div>
        </div>
      </div>

      <div class="assign-section">
        <select v-model="course.lecturerId" class="lecturer-select">
          <option disabled value="">👩‍🎓 Select Lecturer</option>
          <option v-for="lecturer in lecturers" :key="lecturer.id" :value="lecturer.id">
            {{ lecturer.name }}
          </option>
        </select>
        <button @click="assignLecturer(course)" class="assign-btn">✅ Assign</button>
      </div>

      <transition name="fade">
        <div v-if="course.successMessage" class="success-message">
          {{ course.successMessage }}
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      courses: [],
      lecturers: [],
    };
  },
  mounted() {
    this.loadMockData();
  },
  methods: {
    loadMockData() {
      this.courses = [
        {
          id: 1,
          course_code: "MATH101",
          course_name: "Calculus I",
          credits: 3,
          semester: "Fall",
          academic_year: "2024/2025",
          is_active: true,
          lecturerId: "",
          successMessage: "",
        },
        {
          id: 2,
          course_code: "PHYS201",
          course_name: "General Physics",
          credits: 4,
          semester: "Spring",
          academic_year: "2024/2025",
          is_active: true,
          lecturerId: "",
          successMessage: "",
        },
        {
          id: 3,
          course_code: "CHEM110",
          course_name: "Organic Chemistry",
          credits: 3,
          semester: "Fall",
          academic_year: "2024/2025",
          is_active: false,
          lecturerId: "",
          successMessage: "",
        },
      ];

      this.lecturers = [
        { id: 1, name: "Dr. Alice Johnson" },
        { id: 2, name: "Prof. Mark Lee" },
        { id: 3, name: "Dr. Emily Chen" },
      ];
    },

    assignLecturer(course) {
      if (!course.lecturerId) {
        alert("Please select a lecturer before assigning.");
        return;
      }

      const lecturer = this.lecturers.find((lec) => lec.id === course.lecturerId);
      course.successMessage = `✅ ${lecturer.name} assigned successfully!`;

      setTimeout(() => {
        course.successMessage = "";
      }, 3000);
    },
  },
};
</script>

<style scoped>
.assign-container {
  max-width: 950px;
  margin: 50px auto;
  padding: 30px 20px;
  background: #f4f7fa;
  border-radius: 12px;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.title {
  text-align: center;
  font-size: 2.6rem;
  color: #2c3e50;
  margin-bottom: 40px;
}

.course-card {
  background: #ffffff;
  border-left: 6px solid #3498db;
  padding: 20px 25px;
  margin-bottom: 30px;
  border-radius: 10px;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.course-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}

.course-header {
  margin-bottom: 15px;
}

.course-info {
  margin-bottom: 10px;
}

.course-name {
  font-size: 1.5rem;
  color: #34495e;
  margin-bottom: 10px;
}

.badges {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.badge {
  background: #ecf0f1;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.9rem;
  color: #333;
}

.badge.status {
  font-weight: bold;
}

.badge.active {
  background: #2ecc71;
  color: #fff;
}

.badge.inactive {
  background: #e74c3c;
  color: #fff;
}

.assign-section {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 10px;
  margin-top: 10px;
}

.lecturer-select {
  padding: 10px 14px;
  border: 2px solid #ccc;
  border-radius: 6px;
  font-size: 1rem;
  min-width: 230px;
  transition: border-color 0.3s;
}

.lecturer-select:focus {
  border-color: #3498db;
  outline: none;
}

.assign-btn {
  padding: 10px 20px;
  background: #3498db;
  color: #fff;
  border: none;
  border-radius: 6px;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.3s;
}

.assign-btn:hover {
  background: #2980b9;
}

.success-message {
  margin-top: 15px;
  padding: 10px 15px;
  background: #2ecc71;
  color: #fff;
  border-radius: 6px;
  font-size: 0.95rem;
  font-weight: 500;
}

/* Fade transition */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>
