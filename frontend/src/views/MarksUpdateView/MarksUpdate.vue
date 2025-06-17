<template>
  <div class="marks-log-container">
    <h1 class="title">📊 Mark Update Logs (Admin View)</h1>

    <table class="marks-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Student ID</th>
          <th>Assessment ID</th>
          <th>Marks Obtained</th>
          <th>Remarks</th>
          <th>Graded By</th>
          <th>Graded At</th>
          <th>Final</th>
          <th>Updated At</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="log in marksLogs" :key="log.id">
          <td>{{ log.id }}</td>
          <td>{{ log.student_id }}</td>
          <td>{{ log.assessment_component_id }}</td>
          <td>{{ log.marks_obtained }}</td>
          <td>{{ log.remarks }}</td>
          <td>{{ log.graded_by }}</td>
          <td>{{ log.graded_at }}</td>
          <td>
            <span :class="log.is_final ? 'final yes' : 'final no'">
              {{ log.is_final ? '✅ Final' : '❌ Not Final' }}
            </span>
          </td>
          <td>{{ log.updated_at }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      marksLogs: [],
    };
  },
  mounted() {
    this.loadMockLogs();
  },
  methods: {
    loadMockLogs() {
      this.marksLogs = [
        {
          id: 1,
          student_id: "S1234",
          assessment_component_id: "AC001",
          marks_obtained: 88,
          remarks: "Good performance",
          graded_by: "Dr. Alice",
          graded_at: "2025-06-15 09:30",
          is_final: true,
          updated_at: "2025-06-15 10:00",
        },
        {
          id: 2,
          student_id: "S1235",
          assessment_component_id: "AC002",
          marks_obtained: 65,
          remarks: "Needs improvement",
          graded_by: "Prof. Mark",
          graded_at: "2025-06-16 13:15",
          is_final: false,
          updated_at: "2025-06-16 13:45",
        },
        {
          id: 3,
          student_id: "S1236",
          assessment_component_id: "AC003",
          marks_obtained: 75,
          remarks: "Satisfactory",
          graded_by: "Dr. Emily",
          graded_at: "2025-06-17 11:00",
          is_final: true,
          updated_at: "2025-06-17 11:10",
        },
      ];
    },
  },
};
</script>

<style scoped>
.marks-log-container {
  max-width: 1000px;
  margin: 40px auto;
  padding: 20px 25px;
  background-color: #f9f9f9;
  border-radius: 12px;
  box-shadow: 0 6px 24px rgba(0, 0, 0, 0.05);
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.title {
  text-align: center;
  font-size: 2rem;
  color: #2c3e50;
  margin-bottom: 25px;
}

.marks-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.95rem;
}

.marks-table thead {
  background-color: #3498db;
  color: #fff;
}

.marks-table th,
.marks-table td {
  padding: 12px 15px;
  border: 1px solid #ddd;
  text-align: center;
}

.marks-table tbody tr:nth-child(even) {
  background-color: #f2f2f2;
}

.final {
  display: inline-block;
  min-width: 100px;
  padding: 6px 12px;
  border-radius: 20px;
  font-weight: bold;
  font-size: 0.9rem;
  text-align: center;
}

.final.yes {
  background-color: #2ecc71;
  color: white;
}

.final.no {
  background-color: #e74c3c;
  color: white;
}
</style>
