<template>
  <div class="create-assessment-page">
    <h2>Add New Assessment Component</h2>

    <form @submit.prevent="submitForm">
      <div class="form-group">
        <label>Name:</label>
        <input v-model="form.component_name" required />
      </div>

      <div class="form-group">
        <label>Type:</label>
        <select v-model="form.component_type" required>
          <option value="">Select Type</option>
          <option value="quiz">Quiz</option>
          <option value="assignment">Assignment</option>
          <option value="project">Project</option>
        </select>
      </div>

      <div class="form-group">
        <label>Max Marks:</label>
        <input type="number" v-model="form.max_marks" required />
      </div>

      <div class="form-group">
        <label>Weight (%):</label>
        <input type="number" v-model="form.weight_percentage" required />
      </div>

      <div class="form-group">
        <label>Description:</label>
        <textarea v-model="form.description" rows="3"></textarea>
      </div>

      <div class="form-group">
        <label>
          <input type="checkbox" v-model="form.is_published" />
          Publish Now
        </label>
      </div>

      <div class="form-actions">
        <button type="submit">Save</button>
        <router-link :to="`/lecturer/course/${courseId}/components`">
          <button type="button">Cancel</button>
        </router-link>
      </div>

      <p v-if="errorMessage" class="error">{{ errorMessage }}</p>
    </form>
  </div>
</template>

<script>
import { lecturerAPI } from '@/services/api';

export default {
  name: 'CreateComponent',
  data() {
    return {
      form: {
        component_name: '',
        component_type: '',
        max_marks: null,
        weight_percentage: null,
        description: '',
        is_published: true,
      },
      errorMessage: '',
    };
  },
  computed: {
    courseId() {
      return this.$route.params.courseId;
    },
  },
  methods: {
    async submitForm() {
      const courseId = this.courseId;
      if (!courseId) {
        this.errorMessage = 'Course ID is missing.';
        return;
      }

      try {
        const response = await lecturerAPI.updateCourseComponent(courseId, this.form);
        if (response.success) {
          this.$router.push(`/lecturer/course/${courseId}/components`);
        } else {
          this.errorMessage = response.message || 'Failed to save.';
        }
      } catch (err) {
        console.error(err);
        this.errorMessage = 'An unexpected error occurred while saving.';
      }
    },
  },
};
</script>

<style scoped>
.create-assessment-page {
  padding: 1.5rem;
  max-width: 600px;
  margin: 0 auto;
  background: #f9f9f9;
  border-radius: 8px;
}

.form-group {
  margin-bottom: 1rem;
}

input,
select,
textarea {
  width: 100%;
  padding: 0.5rem;
  margin-top: 0.25rem;
  box-sizing: border-box;
}

.form-actions {
  display: flex;
  gap: 1rem;
  margin-top: 1rem;
}

button {
  padding: 0.5rem 1rem;
  cursor: pointer;
}

.error {
  color: red;
  margin-top: 1rem;
}
</style>
