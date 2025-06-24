<template>
  <div class="remark-requests">
    <div class="page-header">
      <h1>Remark Requests</h1>
      <p>Submit requests for remarking of your assessments</p>
    </div>

    <div class="content-section">
      <div class="section-header">
        <h2>Submit New Request</h2>
      </div>

      <div class="request-form">
        <div class="form-group">
          <label for="course-select">Course</label>
          <select
            id="course-select"
            v-model="selectedCourse"
            class="form-control"
          >
            <option value="">Select a course</option>
            <option
              v-for="course in enrolledCourses"
              :key="course.id"
              :value="course.id"
            >
              {{ course.course_code }} - {{ course.course_name }}
            </option>
          </select>
        </div>

        <div class="form-group">
          <label for="component-select">Assessment Component</label>
          <select
            id="component-select"
            v-model="selectedComponent"
            class="form-control"
            :disabled="!selectedCourse"
          >
            <option value="">Select a component</option>
            <option
              v-for="component in courseComponents"
              :key="component.id"
              :value="component.id"
            >
              {{ component.component_name }} ({{ component.component_type }})
            </option>
          </select>
        </div>

        <div class="form-group">
          <label for="reason">Reason for Remark Request</label>
          <textarea
            id="reason"
            v-model="requestReason"
            class="form-control"
            rows="4"
            placeholder="Please provide a detailed explanation for your remark request..."
          ></textarea>
        </div>

        <div class="form-group">
          <label for="evidence">Supporting Evidence (Optional)</label>
          <textarea
            id="evidence"
            v-model="supportingEvidence"
            class="form-control"
            rows="3"
            placeholder="Any additional information or evidence to support your request..."
          ></textarea>
        </div>

        <div class="form-actions">
          <button
            @click="submitRequest"
            class="btn btn-primary"
            :disabled="!isFormValid"
          >
            Submit Request
          </button>
          <button @click="resetForm" class="btn btn-secondary">
            Reset Form
          </button>
        </div>
      </div>
    </div>

    <div class="content-section">
      <div class="section-header">
        <h2>My Requests</h2>
      </div>

      <div class="requests-list">
        <div v-if="loading" class="loading">Loading requests...</div>

        <div v-else-if="requests.length === 0" class="empty-state">
          <div class="empty-icon">📝</div>
          <h3>No requests yet</h3>
          <p>You haven't submitted any remark requests.</p>
        </div>

        <div v-else class="request-items">
          <div
            v-for="request in requests"
            :key="request.id"
            class="request-item"
          >
            <div class="request-header">
              <div class="request-info">
                <h4>
                  {{ request.course_code }} - {{ request.component_name }}
                </h4>
                <p class="request-date">
                  Submitted: {{ formatDate(request.created_at) }}
                </p>
              </div>
              <div class="request-status">
                <span :class="['status-badge', getStatusClass(request.status)]">
                  {{ request.status }}
                </span>
              </div>
            </div>

            <div class="request-details">
              <div class="detail-item">
                <strong>Reason:</strong>
                <p>{{ request.reason }}</p>
              </div>

              <div v-if="request.evidence" class="detail-item">
                <strong>Supporting Evidence:</strong>
                <p>{{ request.evidence }}</p>
              </div>

              <div v-if="request.lecturer_response" class="detail-item">
                <strong>Lecturer Response:</strong>
                <p>{{ request.lecturer_response }}</p>
              </div>

              <div v-if="request.updated_marks !== null" class="detail-item">
                <strong>Updated Marks:</strong>
                <p>{{ request.updated_marks }}/{{ request.max_marks }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { studentPerformanceAPI } from "@/services/api";

export default {
  name: "RemarkRequests",
  data() {
    return {
      loading: false,
      selectedCourse: "",
      selectedComponent: "",
      requestReason: "",
      supportingEvidence: "",
      enrolledCourses: [],
      courseComponents: [],
      requests: [],
    };
  },
  computed: {
    isFormValid() {
      return (
        this.selectedCourse &&
        this.selectedComponent &&
        this.requestReason.trim()
      );
    },
  },
  async mounted() {
    await this.loadData();
  },
  watch: {
    selectedCourse(newCourseId) {
      if (newCourseId) {
        this.loadCourseComponents(newCourseId);
      } else {
        this.courseComponents = [];
      }
      this.selectedComponent = "";
    },
  },
  methods: {
    async loadData() {
      this.loading = true;
      try {
        // Load enrolled courses and existing requests
        await Promise.all([this.loadEnrolledCourses(), this.loadRequests()]);
      } catch (error) {
        console.error("Error loading data:", error);
      } finally {
        this.loading = false;
      }
    },

    async loadEnrolledCourses() {
      // This would typically come from the student's enrolled courses
      // For now, using mock data
      this.enrolledCourses = [
        {
          id: 1,
          course_code: "CS101",
          course_name: "Introduction to Computer Science",
        },
        { id: 2, course_code: "MATH101", course_name: "Calculus I" },
        { id: 3, course_code: "ENG101", course_name: "English Composition" },
      ];
    },

    async loadCourseComponents(courseId) {
      try {
        const response = await studentPerformanceAPI.getClassAverages(courseId);
        if (response.success) {
          this.courseComponents = response.data.components || [];
        }
      } catch (error) {
        console.error("Error loading components:", error);
        this.courseComponents = [];
      }
    },

    async loadRequests() {
      try {
        // This would typically come from the API
        // For now, using mock data
        this.requests = [
          {
            id: 1,
            course_code: "CS101",
            component_name: "Midterm Exam",
            status: "pending",
            reason:
              "I believe there was an error in the grading of question 3.",
            evidence: "I have my original work showing the correct approach.",
            created_at: "2024-01-15T10:30:00Z",
            lecturer_response: null,
            updated_marks: null,
            max_marks: 100,
          },
          {
            id: 2,
            course_code: "MATH101",
            component_name: "Assignment 2",
            status: "approved",
            reason:
              "The grader missed awarding points for the correct solution method.",
            evidence: "",
            created_at: "2024-01-10T14:20:00Z",
            lecturer_response:
              "Your request has been approved. The marks have been updated.",
            updated_marks: 85,
            max_marks: 100,
          },
        ];
      } catch (error) {
        console.error("Error loading requests:", error);
        this.requests = [];
      }
    },

    async submitRequest() {
      if (!this.isFormValid) return;

      try {
        const requestData = {
          course_id: this.selectedCourse,
          component_id: this.selectedComponent,
          reason: this.requestReason,
          evidence: this.supportingEvidence,
        };

        // This would typically call the API
        console.log("Submitting request:", requestData);

        // For now, just add to the list
        const newRequest = {
          id: Date.now(),
          course_code: this.enrolledCourses.find(
            (c) => c.id == this.selectedCourse
          )?.course_code,
          component_name: this.courseComponents.find(
            (c) => c.id == this.selectedComponent
          )?.component_name,
          status: "pending",
          reason: this.requestReason,
          evidence: this.supportingEvidence,
          created_at: new Date().toISOString(),
          lecturer_response: null,
          updated_marks: null,
          max_marks: 100,
        };

        this.requests.unshift(newRequest);
        this.resetForm();

        // Show success message
        alert("Remark request submitted successfully!");
      } catch (error) {
        console.error("Error submitting request:", error);
        alert("Failed to submit request. Please try again.");
      }
    },

    resetForm() {
      this.selectedCourse = "";
      this.selectedComponent = "";
      this.requestReason = "";
      this.supportingEvidence = "";
    },

    getStatusClass(status) {
      const statusClasses = {
        pending: "status-pending",
        approved: "status-approved",
        rejected: "status-rejected",
        in_review: "status-review",
      };
      return statusClasses[status] || "status-pending";
    },

    formatDate(dateString) {
      if (!dateString) return "";
      const date = new Date(dateString);
      return date.toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
      });
    },
  },
};
</script>

<style scoped>
.remark-requests {
  padding: 24px;
  max-width: 1200px;
  margin: 0 auto;
}

.page-header {
  margin-bottom: 32px;
}

.page-header h1 {
  margin: 0 0 8px 0;
  font-size: 2rem;
  font-weight: 700;
  color: #1e293b;
}

.page-header p {
  margin: 0;
  color: #64748b;
  font-size: 1.1rem;
}

.content-section {
  background: white;
  border-radius: 12px;
  padding: 24px;
  margin-bottom: 24px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.section-header {
  margin-bottom: 24px;
}

.section-header h2 {
  margin: 0;
  font-size: 1.5rem;
  font-weight: 600;
  color: #1e293b;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
  color: #374151;
}

.form-control {
  width: 100%;
  padding: 12px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 1rem;
  transition: border-color 0.2s;
}

.form-control:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-control:disabled {
  background-color: #f9fafb;
  cursor: not-allowed;
}

.form-actions {
  display: flex;
  gap: 12px;
  margin-top: 24px;
}

.btn {
  padding: 12px 24px;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-primary {
  background: #3b82f6;
  color: white;
}

.btn-primary:hover:not(:disabled) {
  background: #2563eb;
}

.btn-primary:disabled {
  background: #9ca3af;
  cursor: not-allowed;
}

.btn-secondary {
  background: #f3f4f6;
  color: #374151;
}

.btn-secondary:hover {
  background: #e5e7eb;
}

.requests-list {
  margin-top: 20px;
}

.loading {
  text-align: center;
  padding: 40px;
  color: #6b7280;
}

.empty-state {
  text-align: center;
  padding: 48px 24px;
  color: #6b7280;
}

.empty-icon {
  font-size: 3rem;
  margin-bottom: 16px;
}

.empty-state h3 {
  margin: 0 0 8px 0;
  font-size: 1.2rem;
}

.request-items {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.request-item {
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  padding: 20px;
  transition: box-shadow 0.2s;
}

.request-item:hover {
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.request-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 16px;
}

.request-info h4 {
  margin: 0 0 4px 0;
  font-size: 1.1rem;
  color: #1e293b;
}

.request-date {
  margin: 0;
  font-size: 0.9rem;
  color: #6b7280;
}

.status-badge {
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
  text-transform: capitalize;
}

.status-pending {
  background: #fef3c7;
  color: #92400e;
}

.status-approved {
  background: #dcfce7;
  color: #166534;
}

.status-rejected {
  background: #fee2e2;
  color: #991b1b;
}

.status-review {
  background: #dbeafe;
  color: #1e40af;
}

.request-details {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.detail-item strong {
  color: #374151;
  font-weight: 600;
}

.detail-item p {
  margin: 4px 0 0 0;
  color: #4b5563;
  line-height: 1.5;
}

@media (max-width: 768px) {
  .remark-requests {
    padding: 16px;
  }

  .form-actions {
    flex-direction: column;
  }

  .request-header {
    flex-direction: column;
    gap: 12px;
  }
}
</style>
