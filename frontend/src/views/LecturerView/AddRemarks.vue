<template>
  <div class="add-remarks-view">
    <div class="remarks-card">
      <h2 class="title">💬 Add Feedback / Remarks</h2>

      <!-- Student Selection -->
      <div class="form-group">
        <label for="studentSelect">👤 Select Student</label>
        <select id="studentSelect" v-model="selectedStudentId" @change="loadExistingMark">
          <option disabled value="">-- Choose Student --</option>
          <option
            v-for="student in students"
            :key="student.id"
            :value="student.id"
          >
            {{ student.full_name }} ({{ student.username }})
          </option>
        </select>
      </div>

      <!-- Component Selection -->
      <div class="form-group">
        <label for="componentSelect">📝 Select Assessment Component</label>
        <select id="componentSelect" v-model="selectedComponentId" @change="loadExistingMark">
          <option disabled value="">-- Choose Component --</option>
          <option
            v-for="component in components"
            :key="component.id"
            :value="component.id"
          >
            {{ component.component_name }} ({{ component.component_type }})
          </option>
        </select>
      </div>

      <!-- Feedback Area -->
      <div v-if="markInfo" class="feedback-section">
        <p class="marks-info">
          <strong>Marks Obtained:</strong>
          <span>{{ markInfo.marks_obtained ?? "Not graded yet" }}</span>
        </p>

        <div class="form-group">
          <label for="remarksTextarea">🗨️ Remarks</label>
          <textarea
            id="remarksTextarea"
            v-model="remarks"
            rows="4"
            placeholder="Enter constructive feedback or remarks here..."
          ></textarea>
        </div>

        <button class="save-btn" @click="submitRemarks">💾 Save Remarks</button>
      </div>
    </div>
  </div>
</template>


<script>
import api from "@/services/api";
import authService from "@/services/auth";

export default {
  name: "AddRemarks",
  data() {
    return {
      selectedStudentId: "",
      selectedComponentId: "",
      students: [],
      components: [],
      markInfo: null,
      remarks: "",
    };
  },
  async mounted() {
    const lecturerId = authService.getCurrentUserId();
    const courseId = this.$route.params.courseId;

    // Load enrolled students
    const studentRes = await api.get(`/lecturer/${lecturerId}/course/${courseId}/students`);
    this.students = studentRes.data || [];

    // Load assessment components
    const componentRes = await api.get(`/lecturer/${lecturerId}/course/${courseId}/components`);
    this.components = componentRes.data || [];
  },
  methods: {
    async loadExistingMark() {
      this.markInfo = null;
      this.remarks = "";

      if (!this.selectedStudentId || !this.selectedComponentId) return;

      const res = await api.get(
        `/lecturer/${authService.getCurrentUserId()}/student/${this.selectedStudentId}/component/${this.selectedComponentId}/mark`
      );

      if (res.success && res.data) {
        this.markInfo = res.data;
        this.remarks = res.data.remarks || "";
      } else {
        this.markInfo = { marks_obtained: null };
      }
    },
    async submitRemarks() {
      if (!this.markInfo?.id) {
        alert("Mark record not found. Please grade the student first.");
        return;
      }

      const res = await api.put(
        `/lecturer/${authService.getCurrentUserId()}/marks/${this.markInfo.id}/remarks`,
        { remarks: this.remarks }
      );

      if (res.success) {
        alert("Remarks saved successfully!");
      } else {
        alert("Failed to save remarks.");
      }
    },
  },
};
</script>
<style scoped>
.add-remarks-view {
  max-width: 720px;
  margin: 40px auto;
  padding: 24px;
  font-family: 'Segoe UI', sans-serif;
}

.remarks-card {
  background: #ffffff;
  border-radius: 12px;
  padding: 32px;
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
  border: 1px solid #e5e7eb;
}

.title {
  font-size: 1.6rem;
  margin-bottom: 24px;
  font-weight: bold;
  color: #1f2937;
  display: flex;
  align-items: center;
  gap: 8px;
}

.form-group {
  margin-bottom: 20px;
}

label {
  display: block;
  margin-bottom: 6px;
  font-weight: 600;
  color: #374151;
}

select,
textarea {
  width: 100%;
  padding: 12px;
  border-radius: 8px;
  border: 1px solid #d1d5db;
  font-size: 1rem;
}

select:focus,
textarea:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
}

.marks-info {
  background: #f1f5f9;
  padding: 10px 16px;
  border-radius: 8px;
  font-size: 0.95rem;
  margin-bottom: 20px;
}

.save-btn {
  background: #3b82f6;
  color: white;
  border: none;
  padding: 12px 20px;
  font-size: 1rem;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  transition: background 0.2s;
}

.save-btn:hover {
  background: #2563eb;
}
</style>
