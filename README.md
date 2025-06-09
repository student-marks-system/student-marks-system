# 🎓 Student Marks System

A full-featured web application for managing and monitoring student course marks. Built using **Vue.js** (frontend), **PHP Slim Framework** (backend), and **MySQL** (database), the system supports multiple user roles including lecturers, students, and academic advisors.

---

## 📁 Project Structure

student-marks-system/
├── frontend/ # Vue.js frontend app
└── backend/ # PHP Slim backend API

yaml
Copy
Edit

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
bash
Copy
Edit
cd frontend
npm install
npm run serve
3. Setup Backend
bash
Copy
Edit
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
