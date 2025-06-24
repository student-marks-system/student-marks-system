<template>
  <nav class="lecturer-navbar">
    <div class="nav-container">
      <!-- Logo and Brand -->
      <div class="nav-brand">
        <router-link to="/lecturer/dashboard" class="brand-link">
          <div class="brand-icon">🎓</div>
          <span class="brand-text">Lecturer Portal</span>
        </router-link>
      </div>

      <!-- Navigation Links -->
      <div class="nav-links">
        <router-link
          to="/lecturer/dashboard"
          class="nav-item"
          :class="{ active: $route.path === '/lecturer/dashboard' }"
        >
          <div class="nav-icon">🏠</div>
          <span class="nav-text">Dashboard</span>
        </router-link>

        <div
          class="nav-dropdown"
          @mouseover="showCoursesDropdown = true"
          @mouseleave="showCoursesDropdown = false"
        >
          <div
            class="nav-item dropdown-trigger"
            :class="{ active: isCoursesActive }"
          >
            <div class="nav-icon">📚</div>
            <span class="nav-text">My Courses</span>
            <div class="dropdown-arrow">▼</div>
          </div>
          <div class="dropdown-menu" :class="{ show: showCoursesDropdown }">
            <router-link
              v-for="course in lecturerCourses"
              :key="course.id"
              :to="`/lecturer/course/${course.id}`"
              class="dropdown-item"
            >
              <div class="course-info">
                <span class="course-code">{{ course.code }}</span>
                <span class="course-name">{{ course.name }}</span>
              </div>
              <div class="course-stats">
                <span class="student-count"
                  >{{ course.enrolled_students }} students</span
                >
              </div>
            </router-link>
          </div>
        </div>

        <router-link
          to="/lecturer/analytics"
          class="nav-item"
          :class="{ active: $route.path === '/lecturer/analytics' }"
        >
          <div class="nav-icon">📊</div>
          <span class="nav-text">Analytics</span>
        </router-link>

        <router-link
          to="/lecturer/notifications"
          class="nav-item"
          :class="{ active: $route.path === '/lecturer/notifications' }"
        >
          <div class="nav-icon">🔔</div>
          <span class="nav-text">Notifications</span>
        </router-link>
      </div>

      <!-- User Menu -->
      <div class="user-menu">
        <div
          class="user-dropdown"
          @mouseover="showUserDropdown = true"
          @mouseleave="showUserDropdown = false"
        >
          <div class="user-info">
            <div class="user-avatar">
              {{ getLecturerInitials(lecturerName) }}
            </div>
            <div class="user-details">
              <span class="user-name">{{ lecturerName }}</span>
              <span class="user-role">Lecturer</span>
            </div>
            <div class="dropdown-arrow">▼</div>
          </div>
          <div class="dropdown-menu" :class="{ show: showUserDropdown }">
            <router-link to="/lecturer/profile" class="dropdown-item">
              <div class="dropdown-icon">👤</div>
              <span>Profile</span>
            </router-link>
            <router-link to="/lecturer/settings" class="dropdown-item">
              <div class="dropdown-icon">⚙️</div>
              <span>Settings</span>
            </router-link>
            <div class="dropdown-divider"></div>
            <button @click="logout" class="dropdown-item logout-btn">
              <div class="dropdown-icon">🚪</div>
              <span>Logout</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Menu Toggle -->
      <button class="mobile-menu-toggle" @click="toggleMobileMenu">
        <span class="hamburger"></span>
      </button>
    </div>

    <!-- Mobile Navigation Menu -->
    <div class="mobile-nav" :class="{ show: showMobileMenu }">
      <div class="mobile-nav-header">
        <div class="mobile-profile">
          <div class="mobile-avatar">
            {{ getLecturerInitials(lecturerName) }}
          </div>
          <div class="mobile-profile-info">
            <h4>{{ lecturerName }}</h4>
            <p>Lecturer</p>
          </div>
        </div>
      </div>

      <div class="mobile-nav-links">
        <router-link
          to="/lecturer/dashboard"
          class="mobile-nav-item"
          @click="closeMobileMenu"
        >
          <div class="mobile-nav-icon">🏠</div>
          <span>Dashboard</span>
        </router-link>

        <div class="mobile-nav-section">
          <h5>My Courses</h5>
          <router-link
            v-for="course in lecturerCourses"
            :key="course.id"
            :to="`/lecturer/course/${course.id}`"
            class="mobile-nav-item course-item"
            @click="closeMobileMenu"
          >
            <div class="mobile-course-info">
              <span class="mobile-course-code">{{ course.code }}</span>
              <span class="mobile-course-name">{{ course.name }}</span>
            </div>
            <div class="mobile-course-stats">
              {{ course.enrolled_students }} students
            </div>
          </router-link>
        </div>

        <router-link
          to="/lecturer/analytics"
          class="mobile-nav-item"
          @click="closeMobileMenu"
        >
          <div class="mobile-nav-icon">📊</div>
          <span>Analytics</span>
        </router-link>

        <router-link
          to="/lecturer/notifications"
          class="mobile-nav-item"
          @click="closeMobileMenu"
        >
          <div class="mobile-nav-icon">🔔</div>
          <span>Notifications</span>
        </router-link>

        <router-link
          to="/lecturer/profile"
          class="mobile-nav-item"
          @click="closeMobileMenu"
        >
          <div class="mobile-nav-icon">👤</div>
          <span>Profile</span>
        </router-link>

        <router-link
          to="/lecturer/settings"
          class="mobile-nav-item"
          @click="closeMobileMenu"
        >
          <div class="mobile-nav-icon">⚙️</div>
          <span>Settings</span>
        </router-link>

        <button @click="logout" class="mobile-nav-item logout-btn">
          <div class="mobile-nav-icon">🚪</div>
          <span>Logout</span>
        </button>
      </div>
    </div>
  </nav>
</template>

<script>
import authService from "@/services/auth";
import { lecturerAPI, utilityAPI } from "@/services/api";

export default {
  name: "LecturerNavbar",
  data() {
    return {
      showCoursesDropdown: false,
      showUserDropdown: false,
      showMobileMenu: false,
      lecturerName: "Loading...",
      lecturerId: null,
      lecturerCourses: [],
    };
  },
  computed: {
    isCoursesActive() {
      return this.$route.path.startsWith("/lecturer/course/");
    },
  },
  async mounted() {
    await this.loadLecturerData();

    // Close dropdowns when clicking outside
    document.addEventListener("click", (event) => {
      if (!this.$el.contains(event.target)) {
        this.showUserDropdown = false;
        this.showCoursesDropdown = false;
      }
    });
  },
  methods: {
    async loadLecturerData() {
      try {
        // Get user profile
        const userResponse = await utilityAPI.getUserProfile();
        if (userResponse.success) {
          this.lecturerName = userResponse.data.full_name;
          this.lecturerId = userResponse.data.id;

          // Load lecturer's courses
          const coursesResponse = await lecturerAPI.getCourses(this.lecturerId);
          if (coursesResponse.success) {
            this.lecturerCourses = coursesResponse.data.courses || [];
          }
        }
      } catch (error) {
        console.error("Error loading lecturer data:", error);
      }
    },

    getLecturerInitials(name) {
      return name
        .split(" ")
        .map((n) => n[0])
        .join("")
        .toUpperCase()
        .slice(0, 2);
    },

    toggleMobileMenu() {
      this.showMobileMenu = !this.showMobileMenu;
    },

    closeMobileMenu() {
      this.showMobileMenu = false;
    },

    async logout() {
      console.log("Logging out lecturer...");
      try {
        await authService.logout();
        this.$router.push("/login");
      } catch (error) {
        console.error("Logout error:", error);
        // Force redirect even if logout fails
        this.$router.push("/login");
      }
    },
  },
};
</script>

<style scoped>
.lecturer-navbar {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  height: 80px;
  background: white;
  border-bottom: 1px solid #e2e8f0;
  z-index: 1000;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.nav-container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 20px;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

/* Brand */
.nav-brand {
  flex-shrink: 0;
}

.brand-link {
  display: flex;
  align-items: center;
  text-decoration: none;
  color: #1e293b;
  font-weight: 700;
  font-size: 1.2rem;
}

.brand-icon {
  font-size: 1.5rem;
  margin-right: 8px;
}

/* Navigation Links */
.nav-links {
  display: flex;
  align-items: center;
  gap: 8px;
}

.nav-item {
  display: flex;
  align-items: center;
  padding: 12px 16px;
  text-decoration: none;
  color: #64748b;
  border-radius: 8px;
  transition: all 0.2s;
  font-weight: 500;
}

.nav-item:hover,
.nav-item.active {
  background: #eff6ff;
  color: #3b82f6;
}

.nav-icon {
  font-size: 1.1rem;
  margin-right: 8px;
}

/* Dropdown */
.nav-dropdown {
  position: relative;
}

.dropdown-trigger {
  cursor: pointer;
}

.dropdown-arrow {
  font-size: 0.8rem;
  margin-left: 4px;
  transition: transform 0.2s;
}

.dropdown-menu {
  position: absolute;
  top: 100%;
  left: 0;
  min-width: 280px;
  background: white;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  opacity: 0;
  visibility: hidden;
  transform: translateY(-10px);
  transition: all 0.2s;
  z-index: 1000;
}

.dropdown-menu.show {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}

.dropdown-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 16px;
  text-decoration: none;
  color: #374151;
  transition: background 0.2s;
  border-bottom: 1px solid #f3f4f6;
}

.dropdown-item:last-child {
  border-bottom: none;
}

.dropdown-item:hover {
  background: #f9fafb;
}

.course-info {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}

.course-code {
  font-weight: 600;
  color: #1e293b;
  font-size: 0.9rem;
}

.course-name {
  font-size: 0.8rem;
  color: #6b7280;
  margin-top: 2px;
}

.course-stats {
  font-size: 0.8rem;
  color: #6b7280;
}

/* User Menu */
.user-menu {
  position: relative;
}

.user-dropdown {
  cursor: pointer;
}

.user-info {
  display: flex;
  align-items: center;
  padding: 8px 12px;
  border-radius: 8px;
  transition: background 0.2s;
}

.user-info:hover {
  background: #f9fafb;
}

.user-avatar {
  width: 36px;
  height: 36px;
  background: #3b82f6;
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 0.9rem;
  margin-right: 12px;
}

.user-details {
  display: flex;
  flex-direction: column;
  margin-right: 8px;
}

.user-name {
  font-weight: 600;
  color: #1e293b;
  font-size: 0.9rem;
}

.user-role {
  font-size: 0.8rem;
  color: #6b7280;
}

.dropdown-icon {
  font-size: 1rem;
  margin-right: 8px;
}

.dropdown-divider {
  height: 1px;
  background: #e5e7eb;
  margin: 8px 0;
}

.logout-btn {
  background: none;
  border: none;
  width: 100%;
  text-align: left;
  cursor: pointer;
  color: #ef4444;
}

.logout-btn:hover {
  background: #fef2f2;
}

/* Mobile Menu */
.mobile-menu-toggle {
  display: none;
  background: none;
  border: none;
  cursor: pointer;
  padding: 8px;
}

.hamburger {
  display: block;
  width: 24px;
  height: 2px;
  background: #374151;
  position: relative;
  transition: all 0.3s;
}

.hamburger::before,
.hamburger::after {
  content: "";
  position: absolute;
  width: 24px;
  height: 2px;
  background: #374151;
  transition: all 0.3s;
}

.hamburger::before {
  top: -6px;
}

.hamburger::after {
  bottom: -6px;
}

.mobile-nav {
  position: fixed;
  top: 80px;
  left: 0;
  right: 0;
  background: white;
  border-top: 1px solid #e2e8f0;
  transform: translateY(-100%);
  transition: transform 0.3s;
  z-index: 999;
  max-height: calc(100vh - 80px);
  overflow-y: auto;
}

.mobile-nav.show {
  transform: translateY(0);
}

.mobile-nav-header {
  padding: 20px;
  border-bottom: 1px solid #e5e7eb;
}

.mobile-profile {
  display: flex;
  align-items: center;
}

.mobile-avatar {
  width: 48px;
  height: 48px;
  background: #3b82f6;
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 1.1rem;
  margin-right: 16px;
}

.mobile-profile-info h4 {
  margin: 0;
  font-size: 1.1rem;
  color: #1e293b;
}

.mobile-profile-info p {
  margin: 4px 0 0 0;
  color: #6b7280;
  font-size: 0.9rem;
}

.mobile-nav-links {
  padding: 20px;
}

.mobile-nav-item {
  display: flex;
  align-items: center;
  padding: 16px 0;
  text-decoration: none;
  color: #374151;
  border-bottom: 1px solid #f3f4f6;
  font-weight: 500;
}

.mobile-nav-item:last-child {
  border-bottom: none;
}

.mobile-nav-icon {
  font-size: 1.2rem;
  margin-right: 16px;
  width: 24px;
  text-align: center;
}

.mobile-nav-section {
  margin: 24px 0;
}

.mobile-nav-section h5 {
  margin: 0 0 12px 0;
  color: #6b7280;
  font-size: 0.9rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.mobile-course-info {
  display: flex;
  flex-direction: column;
  flex: 1;
}

.mobile-course-code {
  font-weight: 600;
  color: #1e293b;
}

.mobile-course-name {
  font-size: 0.9rem;
  color: #6b7280;
  margin-top: 2px;
}

.mobile-course-stats {
  font-size: 0.8rem;
  color: #6b7280;
}

/* Responsive Design */
@media (max-width: 768px) {
  .nav-links,
  .user-menu {
    display: none;
  }

  .mobile-menu-toggle {
    display: block;
  }

  .nav-container {
    padding: 0 16px;
  }
}
</style>
