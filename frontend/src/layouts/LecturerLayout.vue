<template>
  <div class="lecturer-layout">
    <!-- Navigation Bar -->
    <LecturerNavbar />

    <!-- Main Content -->
    <main class="main-content">
      <router-view />
    </main>
  </div>
</template>

<script>
import LecturerNavbar from "@/components/navigation/LecturerNavbar.vue";

export default {
  name: "LecturerLayout",
  components: {
    LecturerNavbar,
  },
};
</script>

<style scoped>
.lecturer-layout {
  min-height: 100vh;
  background-color: #f8fafc;
}

.main-content {
  padding-top: 80px; /* Account for fixed navbar */
  min-height: calc(100vh - 80px);
}
</style>
