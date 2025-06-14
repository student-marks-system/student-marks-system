<template>
  <div class="advisee-profile">
    <!-- Header Component -->
    <AdviseeProfileHeader :student="student" />

    <!-- Alert Section Component -->
    <ProfileAlertsSection
      :alerts="student.alerts"
      @handle-alert="handleAlert"
    />

    <!-- Quick Actions Bar Component -->
    <QuickActionsBar
      @schedule-meeting="scheduleMeeting"
      @add-note="addNote"
      @export-report="exportReport"
      @send-message="sendMessage"
    />

    <!-- Main Content -->
    <div class="main-content">
      <!-- Left Column -->
      <div class="left-column">
        <!-- Academic Overview Component -->
        <AcademicOverviewCard :student="student" />

        <!-- Current Courses Component -->
        <CurrentCoursesCard
          :courses="student.currentCourses"
          @view-course-details="viewCourseDetails"
        />

        <!-- Performance Trends Component -->
        <PerformanceTrendsCard
          :performance-trends="performanceTrends"
          :selected-trend-period="selectedTrendPeriod"
          @update-trend-period="updateTrendPeriod"
        />
      </div>

      <!-- Right Column -->
      <div class="right-column">
        <!-- Advisor Notes Component -->
        <AdvisorNotesCard
          :advisor-notes="advisorNotes"
          @add-note="addNote"
          @edit-note="editNote"
          @delete-note="deleteNote"
        />

        <!-- Meeting History Component -->
        <MeetingHistoryCard
          :meeting-history="meetingHistory"
          @view-meeting-details="viewMeetingDetails"
          @schedule-meeting="scheduleMeeting"
        />

        <!-- Academic History Component -->
        <AcademicHistoryCard
          :academic-history="academicHistory"
          :selected-history-view="selectedHistoryView"
          @update-history-view="updateHistoryView"
        />

        <!-- Risk Assessment Component -->
        <RiskAssessmentCard
          :risk-level="student.riskLevel"
          :risk-factors="riskFactors"
          :recommendations="student.recommendations"
        />
      </div>
    </div>

    <!-- Note Modal Component -->
    <NoteModal
      :show-modal="showNoteModal"
      :is-editing="editingNote !== null"
      :note-form="noteForm"
      @close-modal="closeNoteModal"
      @save-note="saveNote"
      @update-note-type="updateNoteType"
      @update-note-content="updateNoteContent"
    />
  </div>
</template>

<script>
// Import all the components
import AdviseeProfileHeader from "@/components/AdvisorComponents/AdviseeProfileComponents/AdviseeProfileHeader.vue";
import ProfileAlertsSection from "@/components/AdvisorComponents/AdviseeProfileComponents/ProfileAlertsSection.vue";
import QuickActionsBar from "@/components/AdvisorComponents/AdviseeProfileComponents/QuickActionsBar.vue";
import AcademicOverviewCard from "@/components/AdvisorComponents/AdviseeProfileComponents/AcademicOverviewCard.vue";
import CurrentCoursesCard from "@/components/AdvisorComponents/AdviseeProfileComponents/CurrentCoursesCard.vue";
import PerformanceTrendsCard from "@/components/AdvisorComponents/AdviseeProfileComponents/PerformanceTrendsCard.vue";
import AdvisorNotesCard from "@/components/AdvisorComponents/AdviseeProfileComponents/AdvisorNotesCard.vue";
import MeetingHistoryCard from "@/components/AdvisorComponents/AdviseeProfileComponents/MeetingHistoryCard.vue";
import AcademicHistoryCard from "@/components/AdvisorComponents/AdviseeProfileComponents/AcademicHistoryCard.vue";
import RiskAssessmentCard from "@/components/AdvisorComponents/AdviseeProfileComponents/RiskAssessmentCard.vue";
import NoteModal from "@/components/AdvisorComponents/AdviseeProfileComponents/NoteModal.vue";

export default {
  name: "AdviseeProfile",
  components: {
    AdviseeProfileHeader,
    ProfileAlertsSection,
    QuickActionsBar,
    AcademicOverviewCard,
    CurrentCoursesCard,
    PerformanceTrendsCard,
    AdvisorNotesCard,
    MeetingHistoryCard,
    AcademicHistoryCard,
    RiskAssessmentCard,
    NoteModal,
  },
  data() {
    return {
      studentId: this.$route.params.id,
      selectedTrendPeriod: "semester",
      selectedHistoryView: "semester",
      showNoteModal: false,
      editingNote: null,
      noteForm: {
        type: "meeting",
        content: "",
      },
      student: {
        id: 1,
        name: "John Smith",
        matricNumber: "A20CS1001",
        program: "Bachelor of Computer Science",
        currentSemester: "2024/2025-1",
        yearOfStudy: "Year 3 Semester 1",
        currentGPA: 3.25,
        cumulativeGPA: 3.18,
        gpaTrend: 0.15,
        totalCreditHours: 78,
        currentCreditLoad: 18,
        academicStanding: "Good Standing",
        expectedGraduation: "June 2026",
        riskLevel: "Low Risk",
        recommendations: [
          "Maintain current study habits",
          "Consider advanced courses in area of interest",
          "Explore internship opportunities",
        ],
        alerts: [
          {
            id: 1,
            type: "info",
            title: "Midterm Performance",
            message:
              "Recent improvement in Web Technology course. Keep up the good work!",
            actionText: "View Details",
          },
        ],
        currentCourses: [
          {
            id: 1,
            code: "SECJ3483",
            name: "Web Technology",
            credits: 3,
            currentGrade: 3.3,
            progress: 65,
            lecturer: "Dr. Sarah Johnson",
          },
          {
            id: 2,
            code: "SECJ2203",
            name: "Database Systems",
            credits: 3,
            currentGrade: 3.7,
            progress: 70,
            lecturer: "Prof. Michael Chen",
          },
          {
            id: 3,
            code: "SECJ3103",
            name: "Software Engineering",
            credits: 3,
            currentGrade: 2.8,
            progress: 60,
            lecturer: "Dr. Sarah Johnson",
          },
          {
            id: 4,
            code: "SECJ3833",
            name: "Data Structures",
            credits: 3,
            currentGrade: 3.5,
            progress: 75,
            lecturer: "Dr. Ahmad Rahman",
          },
          {
            id: 5,
            code: "SECJ3863",
            name: "Computer Networks",
            credits: 3,
            currentGrade: null,
            progress: 45,
            lecturer: "Prof. Lisa Wong",
          },
        ],
      },
      advisorNotes: [
        {
          id: 1,
          date: new Date("2024-10-15"),
          type: "meeting",
          content:
            "Discussed midterm performance and study strategies. Student is motivated and responding well to feedback. Recommended additional practice problems for Software Engineering.",
        },
        {
          id: 2,
          date: new Date("2024-10-01"),
          type: "concern",
          content:
            "Student mentioned struggling with Software Engineering concepts. Arranged study group and additional consultation hours.",
        },
        {
          id: 3,
          date: new Date("2024-09-20"),
          type: "achievement",
          content:
            "Excellent performance in Database Systems. Student shows strong analytical skills and understanding of complex queries.",
        },
      ],
      meetingHistory: [
        {
          id: 1,
          date: new Date("2024-10-15"),
          type: "scheduled",
          title: "Mid-Semester Progress Review",
          summary:
            "Reviewed academic progress, discussed challenges in Software Engineering, and set goals for remainder of semester.",
          outcomes: [
            "Join study group for Software Engineering",
            "Schedule weekly check-ins",
            "Complete practice problems by next week",
          ],
        },
        {
          id: 2,
          date: new Date("2024-09-20"),
          type: "urgent",
          title: "Academic Support Discussion",
          summary:
            "Emergency meeting to address concerns about falling behind in coursework.",
          outcomes: [
            "Developed study schedule",
            "Connected with tutoring services",
            "Set up regular progress monitoring",
          ],
        },
      ],
      academicHistory: [
        {
          id: 1,
          name: "2024/2025-1 (Current)",
          gpa: 3.25,
          credits: 15,
          courseCount: 5,
          status: "In Progress",
        },
        {
          id: 2,
          name: "2023/2024-2",
          gpa: 3.1,
          credits: 18,
          courseCount: 6,
          status: "Completed",
        },
        {
          id: 3,
          name: "2023/2024-1",
          gpa: 3.35,
          credits: 15,
          courseCount: 5,
          status: "Completed",
        },
        {
          id: 4,
          name: "2022/2023-2",
          gpa: 2.95,
          credits: 18,
          courseCount: 6,
          status: "Completed",
        },
      ],
      performanceTrends: [
        { label: "2022/23-2", gpa: 2.95 },
        { label: "2023/24-1", gpa: 3.35 },
        { label: "2023/24-2", gpa: 3.1 },
        { label: "2024/25-1", gpa: 3.25 },
      ],
      riskFactors: [
        {
          name: "Academic Performance",
          score: 7,
          level: "good",
          description: "GPA above average and showing improvement",
        },
        {
          name: "Course Load Management",
          score: 6,
          level: "average",
          description: "Managing current course load adequately",
        },
        {
          name: "Attendance & Participation",
          score: 8,
          level: "excellent",
          description: "Excellent attendance and active participation",
        },
        {
          name: "Study Habits",
          score: 7,
          level: "good",
          description: "Good study habits with room for improvement",
        },
        {
          name: "Support System",
          score: 9,
          level: "excellent",
          description: "Strong family and peer support network",
        },
      ],
    };
  },
  methods: {
    // Update methods for child component events
    updateTrendPeriod(period) {
      this.selectedTrendPeriod = period;
    },

    updateHistoryView(view) {
      this.selectedHistoryView = view;
    },

    updateNoteType(type) {
      this.noteForm.type = type;
    },

    updateNoteContent(content) {
      this.noteForm.content = content;
    },

    // Navigation and action methods
    viewCourseDetails(courseId) {
      this.$router.push(
        `/advisor/course/${courseId}/student/${this.studentId}`
      );
    },

    scheduleMeeting() {
      this.$router.push(`/advisor/schedule-meeting/${this.studentId}`);
    },

    exportReport() {
      console.log("Exporting student report...");
    },

    sendMessage() {
      console.log("Sending message to student...");
    },

    handleAlert(alertId) {
      console.log("Handling alert:", alertId);
    },

    viewMeetingDetails(meetingId) {
      this.$router.push(`/advisor/meeting/${meetingId}`);
    },

    // Note management methods
    addNote() {
      this.editingNote = null;
      this.noteForm = {
        type: "meeting",
        content: "",
      };
      this.showNoteModal = true;
    },

    editNote(noteId) {
      const note = this.advisorNotes.find((n) => n.id === noteId);
      if (note) {
        this.editingNote = noteId;
        this.noteForm = {
          type: note.type,
          content: note.content,
        };
        this.showNoteModal = true;
      }
    },

    deleteNote(noteId) {
      if (confirm("Are you sure you want to delete this note?")) {
        this.advisorNotes = this.advisorNotes.filter((n) => n.id !== noteId);
      }
    },

    saveNote() {
      if (!this.noteForm.content.trim()) return;

      if (this.editingNote) {
        // Update existing note
        const noteIndex = this.advisorNotes.findIndex(
          (n) => n.id === this.editingNote
        );
        if (noteIndex !== -1) {
          this.advisorNotes[noteIndex] = {
            ...this.advisorNotes[noteIndex],
            type: this.noteForm.type,
            content: this.noteForm.content,
          };
        }
      } else {
        // Add new note
        const newNote = {
          id: Date.now(),
          date: new Date(),
          type: this.noteForm.type,
          content: this.noteForm.content,
        };
        this.advisorNotes.unshift(newNote);
      }

      this.closeNoteModal();
    },

    closeNoteModal() {
      this.showNoteModal = false;
      this.editingNote = null;
      this.noteForm = {
        type: "meeting",
        content: "",
      };
    },
  },
};
</script>

<style scoped>
.advisee-profile {
  padding: 20px;
  background-color: #f8fafc;
  min-height: 100vh;
}

/* Main Content */
.main-content {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 30px;
}

.left-column,
.right-column {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

/* Responsive Design */
@media (max-width: 1200px) {
  .main-content {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .advisee-profile {
    padding: 16px;
  }
}
</style>
