<template>
  <div class="marks-log-container">
    <h1 class="title">🛠️ System Logs (Admin View)</h1>

    <table class="marks-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Timestamp</th>
          <th>User</th>
          <th>Role</th>
          <th>Action</th>
          <th>Type</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(log, index) in logs" :key="index">
          <td>{{ index + 1 }}</td>
          <td>{{ log.timestamp }}</td>
          <td>{{ log.user }}</td>
          <td>{{ log.role }}</td>
          <td>{{ log.action }}</td>
          <td><span :class="['type', log.type.toLowerCase()]">{{ log.type }}</span></td>
          <td><span :class="['status', log.status.toLowerCase()]">{{ log.status }}</span></td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      logs: [],
    };
  },
  mounted() {
    this.loadMockLogs();
  },
  methods: {
    loadMockLogs() {
      this.logs = [
        {
          timestamp: "2025-06-17 09:12:34",
          user: "admin01",
          role: "Admin",
          action: "Logged in",
          type: "Login",
          status: "Success",
        },
        {
          timestamp: "2025-06-17 09:14:58",
          user: "admin01",
          role: "Admin",
          action: "Updated student marks",
          type: "Update",
          status: "Success",
        },
        {
          timestamp: "2025-06-17 09:16:22",
          user: "lecturer22",
          role: "Lecturer",
          action: "Tried deleting record",
          type: "Delete",
          status: "Failed",
        },
        {
          timestamp: "2025-06-17 09:18:47",
          user: "admin01",
          role: "Admin",
          action: "Added new lecturer",
          type: "Insert",
          status: "Success",
        },
        {
          timestamp: "2025-06-17 09:20:01",
          user: "student56",
          role: "Student",
          action: "Viewed course list",
          type: "View",
          status: "Success",
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

.type,
.status {
  padding: 4px 10px;
  border-radius: 12px;
  font-weight: bold;
  color: white;
}

/* Type badges */
.type.login {
  background-color: #2980b9;
}
.type.update {
  background-color: #f39c12;
}
.type.insert {
  background-color: #2ecc71;
}
.type.delete {
  background-color: #e74c3c;
}
.type.view {
  background-color: #8e44ad;
}

/* Status badges */
.status.success {
  background-color: #2ecc71;
}
.status.failed {
  background-color: #e74c3c;
}
</style>
