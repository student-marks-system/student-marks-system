<template>
  <div class="container">
    <h1 class="title">🎓 Course Management Dashboard</h1>

    <!-- Add Course Form -->
    <form @submit.prevent="addCourse" class="add-course-form">
      <h2>Add New Course</h2>
      <div class="form-group">
        <input v-model="newCourse.course_code" placeholder="Course Code (e.g., CS101)" required />
        <input v-model="newCourse.course_name" placeholder="Course Name (e.g., Programming I)" required />
        <input type="number" v-model.number="newCourse.credits" placeholder="Credits (e.g., 3)" required />
        <input v-model="newCourse.semester" placeholder="Semester (e.g., 1)" required />
        <input v-model="newCourse.academic_year" placeholder="Academic Year (e.g., 2024/2025)" required />
        <button type="submit">➕ Add Course</button>
      </div>
    </form>

    <!-- Course Grid -->
    <div v-if="courses.length" class="course-grid">
      <div v-for="course in courses" :key="course.id" class="course-card">
        <div class="course-header">
          <div v-if="editingCourse && editingCourse.id === course.id">
            <input v-model="editingCourse.course_code" />
            <input v-model="editingCourse.course_name" />
          </div>
          <div v-else>
            <h3>{{ course.course_code }}: {{ course.course_name }}</h3>
          </div>
          <span :class="['badge', course.is_active ? 'active' : 'inactive']">
            {{ course.is_active ? 'ACTIVE' : 'INACTIVE' }}
          </span>
        </div>

        <div class="course-details">
          <p><strong>Credits:</strong> {{ course.credits }}</p>
          <p><strong>Semester:</strong> {{ course.semester }}</p>
          <p><strong>Academic Year:</strong> {{ course.academic_year }}</p>
          <p><strong>Created:</strong> {{ formatDate(course.created_at) }}</p>
          <p><strong>Updated:</strong> {{ formatDate(course.updated_at) }}</p>
        </div>

        <div class="course-actions">
          <template v-if="editingCourse && editingCourse.id === course.id">
            <button @click="updateCourse" class="btn save">💾 Save</button>
            <button @click="cancelEdit" class="btn cancel">✖ Cancel</button>
          </template>
          <template v-else>
            <button @click="editCourse(course)" class="btn edit">✏️ Edit</button>
            <button @click="askDelete(course)" class="btn delete" title="Delete this course">🗑 Delete</button>
          </template>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="empty-state">
      <p>No courses found. Use the form above to add a new course 📖</p>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="modal-overlay">
      <div class="modal">
        <h3>Confirm Deletion</h3>
        <p>Are you sure you want to delete <strong>{{ courseToDelete?.course_name }}</strong>?</p>
        <div class="modal-actions">
          <button @click="confirmDelete(courseToDelete.id)" class="btn confirm">✔ Yes, Delete</button>
          <button @click="cancelDelete" class="btn cancel">✖ Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      courses: [],
      newCourse: {
        course_code: "",
        course_name: "",
        credits: 3,
        semester: "1",
        academic_year: "2024/2025",
        is_active: true,
      },
      editingCourse: null,
      nextId: 6,
      showDeleteModal: false,
      courseToDelete: null,
    };
  },
  mounted() {
    this.fetchCourses();
  },
  methods: {
    fetchCourses() {
      this.courses = [
        {
          id: 1,
          course_code: "CS101",
          course_name: "Introduction to Programming",
          credits: 3,
          semester: "1",
          academic_year: "2024/2025",
          is_active: true,
          created_at: "2024-01-10",
          updated_at: "2024-03-15",
        },
        {
          id: 2,
          course_code: "MATH201",
          course_name: "Calculus II",
          credits: 4,
          semester: "2",
          academic_year: "2024/2025",
          is_active: false,
          created_at: "2024-02-05",
          updated_at: "2024-04-20",
        },
      ];
    },
    addCourse() {
      const newC = {
        ...this.newCourse,
        id: this.nextId++,
        is_active: true,
        created_at: new Date().toISOString().split("T")[0],
        updated_at: new Date().toISOString().split("T")[0],
      };
      this.courses.push(newC);
      this.newCourse = {
        course_code: "",
        course_name: "",
        credits: 3,
        semester: "1",
        academic_year: "2024/2025",
        is_active: true,
      };
    },
    editCourse(course) {
      this.editingCourse = { ...course };
    },
    updateCourse() {
      const index = this.courses.findIndex(c => c.id === this.editingCourse.id);
      if (index !== -1) {
        this.editingCourse.updated_at = new Date().toISOString().split("T")[0];
        this.courses[index] = { ...this.editingCourse };
      }
      this.editingCourse = null;
    },
    cancelEdit() {
      this.editingCourse = null;
    },
    askDelete(course) {
      this.courseToDelete = course;
      this.showDeleteModal = true;
    },
    confirmDelete(id) {
      this.courses = this.courses.filter(c => c.id !== id);
      this.cancelDelete();
    },
    cancelDelete() {
      this.showDeleteModal = false;
      this.courseToDelete = null;
    },
    formatDate(date) {
      if (!date) return "N/A";
      return new Date(date).toLocaleDateString();
    },
  },
};
</script>

<style scoped>
.container {
  max-width: 1000px;
  margin: 40px auto;
  padding: 20px;
  background: #f8f9fa;
  border-radius: 12px;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.title {
  text-align: center;
  font-size: 2.8rem;
  margin-bottom: 30px;
  color: #2c3e50;
}

.add-course-form {
  background: #fff;
  padding: 20px;
  border-radius: 8px;
  margin-bottom: 40px;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
}

.add-course-form h2 {
  margin-bottom: 20px;
  color: #34495e;
}

.form-group {
  display: grid;
  gap: 15px;
}

.form-group input {
  padding: 12px 15px;
  border: 2px solid #ddd;
  border-radius: 6px;
  font-size: 1rem;
  transition: border 0.3s;
}

.form-group input:focus {
  border-color: #3498db;
  outline: none;
}

.form-group button {
  background: #3498db;
  color: #fff;
  padding: 14px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: bold;
  transition: background 0.3s;
}

.form-group button:hover {
  background: #2980b9;
}

.course-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 25px;
}

.course-card {
  background: #fff;
  border-radius: 10px;
  padding: 25px;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
  transition: transform 0.2s;
}

.course-card:hover {
  transform: translateY(-5px);
}

.course-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.course-header h3 {
  margin: 0;
  font-size: 1.3rem;
  color: #2c3e50;
}

.badge {
  padding: 5px 12px;
  border-radius: 50px;
  font-size: 0.75rem;
  font-weight: bold;
  color: #fff;
}

.badge.active {
  background: #2ecc71;
}

.badge.inactive {
  background: #e74c3c;
}

.course-header input {
  margin-right: 10px;
  padding: 6px 8px;
}

.course-details {
  margin-top: 15px;
  color: #555;
}

.course-details p {
  margin: 4px 0;
}

.course-actions {
  margin-top: 20px;
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}

.btn {
  padding: 8px 14px;
  font-size: 0.85rem;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.btn.edit {
  background: #f1c40f;
  color: #fff;
}

.btn.edit:hover {
  background: #d4ac0d;
}

.btn.delete {
  background: #e74c3c;
  color: #fff;
}

.btn.delete:hover {
  background: #c0392b;
}

.btn.save {
  background: #2ecc71;
  color: #fff;
}

.btn.cancel {
  background: #7f8c8d;
  color: #fff;
}

.btn.confirm {
  background: #e67e22;
  color: white;
}

.btn.confirm:hover {
  background: #d35400;
}

.empty-state {
  text-align: center;
  font-size: 1.1rem;
  color: #888;
}

/* Modal Styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.4);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 999;
}

.modal {
  background: #fff;
  padding: 30px;
  border-radius: 10px;
  width: 350px;
  max-width: 90%;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  text-align: center;
}

.modal h3 {
  margin-bottom: 15px;
  font-size: 1.4rem;
  color: #2c3e50;
}

.modal p {
  font-size: 1rem;
  margin-bottom: 20px;
}

.modal-actions {
  display: flex;
  justify-content: center;
  gap: 15px;
}
</style>
