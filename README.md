# 🎓 Student Marks System

A full-featured web application for managing and monitoring student course marks. Built using **Vue.js** (frontend), **PHP Slim Framework** (backend), and **MySQL** (database), the system supports multiple user roles including lecturers, students, and academic advisors.

---

## 📁 Project Structure

student-marks-system/
├── frontend/ # Vue.js frontend app
└── backend/ # PHP Slim backend API



---

## 🚀 Technologies Used

| Layer     | Technology           |
|-----------|----------------------|
| Frontend  | Vue.js (Vue CLI), Axios, Chart.js |
| Backend   | PHP Slim Framework, Composer |
| Database  | MySQL                |
| API Comm  | RESTful API (JSON)   |
| Version Control | Git + GitHub   |

---

## 👥 User Roles & Features

### 👨‍🏫 Lecturer
- Secure login
- Manage student records
- Add/edit continuous assessment marks (70%)
- Enter final exam marks (30%)
- Auto-calculate total marks
- Visual analytics (progress charts)
- Export as CSV
- Notify students of mark updates

### 👩‍🎓 Student
- Login with matric number and PIN
- View marks and breakdown
- Progress bar visualization
- Anonymous comparison with classmates
- View rank and percentile
- Simulate marks ("What-if" tool)
- Request remark with justification

### 👨‍💼 Academic Advisor
- Secure login
- Access advisee's mark breakdown
- Highlight at-risk students (GPA < 2.0)
- Add notes & consultation history
- Export consultation reports

### 🛠 Admin (Optional)
- Manage users and roles
- Assign lecturers to courses
- View logs and activity
- Reset passwords

---

## 🧪 Project Setup

### 1. Clone the Repository
```bash
git clone https://github.com/YOUR-ORG-NAME/student-marks-system.git
cd student-marks-system
2. Setup Frontend

cd frontend
npm install
npm run serve
3. Setup Backend

cd backend
composer install
php -S localhost:8080 -t public
⚠️ Make sure to configure database connection in .env file in backend.

📌 Notes
Ensure CORS is enabled in the backend to allow frontend requests

Use .env files to manage API URLs and DB credentials

Follow a branching strategy (e.g., frontend-dev, backend-dev, main)

📃 License
This project is part of a university assignment for the Web Technology course (SECJ3483). For educational purposes only.

## 👥 Team Responsibilities

Our team has divided the project into functional modules based on expertise and workload. Each member is responsible for both the frontend and backend implementation of their assigned features.

### 🔐 Azmain – Authentication & Assessment Management

**Authentication and Authorization Module**
- Secure Login (Lecturer, Student, Advisor, Admin)
- Role-Based Access Control (RBAC)
- Reset User Passwords (Admin)

**Assessment Management Module**
- Create/Edit Continuous Assessment Components (Lecturer)
- Add/Edit Marks for Components (70%) (Lecturer)
- Add Final Exam Marks (30%) (Lecturer)
- Bulk Upload via CSV (Lecturer)
- Export Marks as CSV (Lecturer)
- Add Feedback/Remarks per Student (Lecturer)

---

### 🧑‍💼 Tabinda – User Management & Notifications

**User Management Module**
- Manage User Accounts and Roles (Admin)
- Manage Student Records (Add/Edit/Delete) (Lecturer)
- Manage Student Enrollment (Admin, Lecturer)
- View System Logs and Mark Updates (Admin)

**Notification Module**
- Notification to students on mark updates (Lecturer, Student)

---

### 🧑‍🏫 Wajeeha – Course & Remark Request Management

**Course Management Module**
- Manage Course List (CRUD) (Lecturer)
- Assign Lecturers to Courses (Admin)

**Remark Request Module**
- Submit Remark Requests with Justification (Student)

---

### 📊 Daney – Student Performance & Advisor Module

**Student Performance Module**
- View Component-wise Marks and Total (Student)
- View Full Mark Breakdown (Lecturer, Student, Advisor)
- View Progress Bar and Assessment Breakdown (Student)
- Performance Analytics & Averages (Lecturer, Student, Advisor)
- Compare Marks Anonymously with Coursemates (Student)
- View Personal Class Rank and Percentile (Student)
- View Class Average per Component (Student, Advisor)
- Performance Trend Graphs (Lecturer, Student, Advisor)
- "What-If" Simulator for Grade Prediction (Student)

**Advisor Module**
- View List of Advisees/Students Under Supervision
- Access Advisees' Full Mark Breakdown Across All Enrolled Courses
- Highlight At-Risk Students (e.g., GPA < 2.0 or bottom 20%)
- Add Private Notes/Meeting Records per Advisee
- Export Student Consultation Reports
- View Advisee's Overall Course Performance
