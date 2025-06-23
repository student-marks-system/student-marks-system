<template>
  <div class="p-6">
    <h2 class="text-xl font-bold mb-4">Assessment Components</h2>
    <button @click="openForm()" class="btn btn-primary mb-3">Add New</button>

    <table class="table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Type</th>
          <th>Max Marks</th>
          <th>Weight (%)</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="component in components" :key="component.id">
          <td>{{ component.name }}</td>
          <td>{{ component.type }}</td>
          <td>{{ component.max_marks }}</td>
          <td>{{ component.weight_percentage }}</td>
          <td>
            <button class="btn btn-sm btn-warning" @click="openForm(component)">Edit</button>
            <button class="btn btn-sm btn-danger" @click="deleteComponent(component.id)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Using CreateComponent instead of AssessmentForm -->
    <CreateComponent
      v-if="showForm"
      :component="selectedComponent"
      :course-id="courseId"
      @close="closeForm"
      @saved="fetchComponents"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import CreateComponent from '@/views/LecturerView/CreateComponent.vue'
import api from '@/services/api'

const route = useRoute()
const courseId = route.params.courseId

const components = ref([])
const showForm = ref(false)
const selectedComponent = ref(null)

const fetchComponents = async () => {
  try {
    const res = await api.get(`/lecturer/course/${courseId}/components`)
    components.value = res.data.components
  } catch (err) {
    console.error('Failed to fetch components:', err)
  }
}

const openForm = (comp = null) => {
  selectedComponent.value = comp
  showForm.value = true
}

const closeForm = () => {
  selectedComponent.value = null
  showForm.value = false
}

const deleteComponent = async (id) => {
  if (confirm('Delete this component?')) {
    try {
      await api.delete(`/lecturer/assessment/${id}`)
      fetchComponents()
    } catch (err) {
      console.error('Delete failed:', err)
    }
  }
}

onMounted(fetchComponents)
</script>
