<template>
  <div class="assign-lecturers">
    <h2>📘 Assign Lecturers to Courses</h2>

    <div v-if="message" :class="['alert', messageType]">{{ message }}</div>

    <form @submit.prevent="assignLecturer" class="form">
      <!-- Course Dropdown -->
      <div class="form-group">
        <label>Select Course</label>
        <select v-model="form.course_id" required>
          <option disabled value="">-- Choose Course --</option>
          <option v-for="course in courses" :key="course.id" :value="course.id">
            {{ course.course_code }} - {{ course.course_name }}
          </option>
        </select>
      </div>

        <div class="form-group">
        <label>Select Lecturer</label>
        <select v-model="form.lecturer_id" required>
          <option disabled value="">-- Choose Lecturer --</option>
          <option v-for="lecturer in lecturers" :key="lecturer.lecturer_id" :value="lecturer.lecturer_id">
            {{ lecturer.lecturer_name }} 
          </option>
        </select>
      </div>

      <!-- Role Dropdown -->
      <div class="form-group">
        <label>Select Role</label>
<select v-model="form.role" required>
  <option disabled value="">-- Select Role --</option>
  <option value="primary">Lecturer</option>
  <option value="secondary">Assistant Lecturer</option>
</select>


      </div>

      <button type="submit" class="btn primary">Assign</button>
    </form>

    <!-- Assigned Lecturers List -->
    <div v-if="assigned && assigned.length" class="assigned-box">
      <h3>👨‍🏫 Assigned Lecturers</h3>
      <ul class="assigned-list">
        <li v-for="item in assigned" :key="item.id">
          <span><strong>{{ item.lecturer_name }}</strong> — {{ item.role }}</span>
          <button class="btn small delete" @click="confirmUnassign(item)">🗑️</button>
        </li>
      </ul>
    </div>

    <!-- Confirmation Modal -->
    <div v-if="showModal" class="modal-overlay">
      <div class="modal">
        <p>Unassign <strong>{{ selectedToDelete.lecturer_name }}</strong> from course?</p>
        <div class="modal-actions">
          <button @click="unassignLecturer" class="btn delete">Yes</button>
          <button @click="cancelUnassign" class="btn">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api from '@/api';

export default {
  data() {
    return {
      courses: [],
      lecturers: [],
      assigned: [],
      form: {
        course_id: '',
        lecturer_id: '',
        role: 'primary'
      },
      message: '',
      messageType: '',
      showModal: false,
      selectedToDelete: null
    };
  },
  methods: {
    fetchCourses() {
      api.get('/courses')
        .then(res => this.courses = res.data)
        .catch(() => this.showMessage('❌ Failed to fetch courses', 'error'));
    },
    fetchLecturers() {
      api.get('/users?type=lecturer')
        .then(res => this.lecturers = res.data)
        .catch(() => this.showMessage('❌ Failed to fetch lecturers', 'error'));
    },
    fetchAssigned() {
      if (this.form.course_id) {
        api.get(`/course-lecturers/${this.form.course_id}`)
          .then(res => this.assigned = res.data)
          .catch(() => this.showMessage('❌ Failed to fetch assigned lecturers', 'error'));
      } else {
        this.assigned = [];
      }
    },
async assignLecturer() {
  try {
    const response = await api.post('/course-lecturers', this.form); // Fixed endpoint
    console.log('Assign response:', response.data); // Debugging
    if (response.data.status === 'assigned') {
      this.showMessage('✅ Lecturer assigned successfully!', 'success');
      this.fetchAssigned();
      this.form.lecturer_id = '';
      this.form.role = 'primary';
    } else {
      this.showMessage('❌ Failed: Unexpected response', 'error');
    }
  } catch (error) {
    console.error('AXIOS ERROR:', error); // Debugging
    this.showMessage('❌ to assign lecturer.', 'error');
  }
},

    confirmUnassign(item) {
      this.selectedToDelete = item;
      this.showModal = true;
    },
    cancelUnassign() {
      this.selectedToDelete = null;
      this.showModal = false;
    },
    unassignLecturer() {
      api.delete(`/course-lecturers/${this.form.course_id}/${this.selectedToDelete.lecturer_id}`)
        .then(() => {
          this.fetchAssigned();
          this.showMessage('Lecturer unassigned.', 'success');
        })
        .catch(() => this.showMessage('❌ Failed to unassign lecturer.', 'error'))
        .finally(() => this.cancelUnassign());
    },
    showMessage(msg, type) {
      this.message = msg;
      this.messageType = type;
      setTimeout(() => {
        this.message = '';
        this.messageType = '';
      }, 3000);
    }
  },
  watch: {
    'form.course_id': 'fetchAssigned'
  },
  mounted() {
    this.fetchCourses();
    this.fetchLecturers();
  }
};
</script>

<style scoped>
.assign-lecturers {
  max-width: 700px;
  margin: auto;
  padding: 2rem;
  background: #f9f9f9;
  color: #333;
  border-radius: 12px;
  font-family: "Segoe UI", sans-serif;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

h2, h3 {
  text-align: center;
  margin-bottom: 1.5rem;
  color: #2c3e50;
}

.alert.success {
  background: #e0f7e9;
  color: #2e7d32;
  padding: 0.75rem;
  margin-bottom: 1rem;
  border-radius: 6px;
}
.alert.error {
  background: #fdecea;
  color: #c62828;
  padding: 0.75rem;
  margin-bottom: 1rem;
  border-radius: 6px;
}

.form-group {
  margin-bottom: 1.2rem;
}
label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
}
input, select {
  width: 100%;
  padding: 0.55rem;
  border-radius: 6px;
  border: 1px solid #ccc;
  background: #fff;
  color: #333;
  font-size: 0.95rem;
}

.btn {
  padding: 0.5rem 1rem;
  border: none;
  margin-right: 0.5rem;
  border-radius: 6px;
  cursor: pointer;
  background: #ddd;
  color: #000;
  transition: background 0.3s ease;
}
.btn.primary {
  background: #007bff;
  color: #fff;
}
.btn.primary:hover {
  background: #0056b3;
}
.btn.delete {
  background: #e53935;
  color: #fff;
}
.btn.delete:hover {
  background: #b71c1c;
}
.btn.small {
  font-size: 0.8rem;
  padding: 0.3rem 0.6rem;
}

.assigned-box {
  margin-top: 2rem;
}
.assigned-list {
  list-style: none;
  padding: 0;
}
.assigned-list li {
  background: #f1f1f1;
  padding: 0.75rem;
  margin-bottom: 0.5rem;
  border-radius: 6px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: #333;
}

.modal-overlay {
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.5);
  display: flex; justify-content: center; align-items: center;
  z-index: 9999;
}
.modal {
  background: #fff;
  padding: 2rem;
  border-radius: 10px;
  color: #000;
  width: 90%;
  max-width: 400px;
  text-align: center;
}
.modal-actions {
  margin-top: 1rem;
  display: flex;
  justify-content: space-evenly;
}
</style>
