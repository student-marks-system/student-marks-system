-- Course Mark Management System Database Schema

-- Users table - stores all system users (lecturers, students, advisors)
-- Think of this as the master directory of everyone who can access the system
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) UNIQUE NOT NULL, -- matric number for students, staff ID for others
    email VARCHAR(150) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL, -- never store plain text passwords!
    full_name VARCHAR(200) NOT NULL,
    user_type ENUM('lecturer', 'student', 'advisor', 'admin') NOT NULL,
    is_active BOOLEAN DEFAULT TRUE, -- allows us to deactivate users without deleting data
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Add indexes for frequently searched columns
    INDEX idx_username (username),
    INDEX idx_user_type (user_type),
    INDEX idx_email (email)
);

-- Courses table - stores information about academic courses
-- Each course can have multiple lecturers and many students enrolled
CREATE TABLE courses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_code VARCHAR(20) UNIQUE NOT NULL, -- e.g., 'SECJ3483'
    course_name VARCHAR(200) NOT NULL, -- e.g., 'Web Technology'
    credits INT NOT NULL DEFAULT 3,
    semester VARCHAR(20) NOT NULL, -- e.g., '2024/2025-1'
    academic_year VARCHAR(10) NOT NULL, -- e.g., '2024/2025'
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_course_code (course_code),
    INDEX idx_semester (semester, academic_year)
);

-- Course enrollments - links students to courses they're taking
-- This is a many-to-many relationship between users and courses
CREATE TABLE course_enrollments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    enrollment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('active', 'dropped', 'completed') DEFAULT 'active',
    
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    
    -- Ensure a student can't be enrolled in the same course twice
    UNIQUE KEY unique_enrollment (student_id, course_id),
    INDEX idx_student_courses (student_id),
    INDEX idx_course_students (course_id)
);

-- Course assignments - links lecturers to courses they teach
CREATE TABLE course_lecturers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    lecturer_id INT NOT NULL,
    course_id INT NOT NULL,
    role ENUM('primary', 'secondary') DEFAULT 'primary', -- primary lecturer vs assistant
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (lecturer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    
    UNIQUE KEY unique_assignment (lecturer_id, course_id),
    INDEX idx_lecturer_courses (lecturer_id),
    INDEX idx_course_lecturers (course_id)
);

-- Assessment components - defines the different types of assessments
-- This gives us flexibility to have different assessment structures per course
CREATE TABLE assessment_components (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    component_name VARCHAR(100) NOT NULL, -- e.g., 'Quiz 1', 'Assignment 1', 'Lab Exercise 3'
    component_type ENUM('quiz', 'assignment', 'exercise', 'lab', 'test', 'final_exam', 'other') NOT NULL,
    max_marks DECIMAL(5,2) NOT NULL, -- maximum possible marks (e.g., 100.00)
    weight_percentage DECIMAL(5,2) NOT NULL, -- contribution to final grade (e.g., 15.50 for 15.5%)
    due_date DATE NULL, -- when the assessment is due (optional)
    description TEXT, -- detailed description of the assessment
    is_published BOOLEAN DEFAULT FALSE, -- whether students can see this component yet
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    INDEX idx_course_components (course_id),
    INDEX idx_component_type (component_type)
);

-- Student marks - stores individual student scores for each assessment component
-- This is where the actual grades live
CREATE TABLE student_marks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    assessment_component_id INT NOT NULL,
    marks_obtained DECIMAL(5,2) NULL, -- actual marks scored (NULL if not yet graded)
    remarks TEXT, -- lecturer's comments or feedback
    graded_by INT NULL, -- which lecturer entered/updated these marks
    graded_at TIMESTAMP NULL, -- when the marks were entered
    is_final BOOLEAN DEFAULT FALSE, -- whether these marks are finalized
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (assessment_component_id) REFERENCES assessment_components(id) ON DELETE CASCADE,
    FOREIGN KEY (graded_by) REFERENCES users(id) ON DELETE SET NULL,
    
    -- Ensure one mark entry per student per component
    UNIQUE KEY unique_student_assessment (student_id, assessment_component_id),
    INDEX idx_student_marks (student_id),
    INDEX idx_assessment_marks (assessment_component_id),
    INDEX idx_graded_by (graded_by)
);

-- Academic advisor assignments - links advisors to their advisees
CREATE TABLE advisor_assignments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    advisor_id INT NOT NULL,
    student_id INT NOT NULL,
    assigned_date DATE NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    notes TEXT, -- advisor's notes about the student
    
    FOREIGN KEY (advisor_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    
    INDEX idx_advisor_students (advisor_id),
    INDEX idx_student_advisor (student_id)
);

-- Remark requests - when students request mark reviews
CREATE TABLE remark_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    assessment_component_id INT NOT NULL,
    original_marks DECIMAL(5,2) NOT NULL, -- marks before the remark request
    requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    justification TEXT NOT NULL, -- student's reason for requesting remark
    status ENUM('pending', 'approved', 'rejected', 'under_review') DEFAULT 'pending',
    reviewed_by INT NULL, -- lecturer who handled the request
    review_comments TEXT, -- lecturer's response
    new_marks DECIMAL(5,2) NULL, -- updated marks if approved
    reviewed_at TIMESTAMP NULL,
    
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (assessment_component_id) REFERENCES assessment_components(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL,
    
    INDEX idx_student_requests (student_id),
    INDEX idx_status (status),
    INDEX idx_component_requests (assessment_component_id)
);

-- System notifications - for notifying users about mark updates, etc.
CREATE TABLE notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('mark_update', 'remark_status', 'system_alert', 'general') NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_notifications (user_id),
    INDEX idx_unread (user_id, is_read)
);

-- Audit log - tracks all important changes for security and debugging
CREATE TABLE audit_log (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NULL, -- who made the change
    action VARCHAR(100) NOT NULL, -- what they did (e.g., 'UPDATE_MARKS', 'CREATE_USER')
    table_name VARCHAR(50) NOT NULL, -- which table was affected
    record_id INT NOT NULL, -- which specific record
    old_values JSON NULL, -- what the data looked like before
    new_values JSON NULL, -- what the data looks like after
    ip_address VARCHAR(45) NULL, -- user's IP address
    user_agent TEXT NULL, -- browser information
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_actions (user_id),
    INDEX idx_table_records (table_name, record_id),
    INDEX idx_action_time (action, created_at)
);

-- Insert some sample data to get started

-- Sample users (passwords would be hashed in real application)
INSERT INTO users (username, email, password_hash, full_name, user_type) VALUES
('LEK001', 'lecturer1@university.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dr. Sarah Johnson', 'lecturer'),
('LEK002', 'lecturer2@university.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Prof. Michael Chen', 'lecturer'),
('ADV001', 'advisor1@university.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dr. Amanda Wilson', 'advisor'),
('A20CS1001', 'student1@student.university.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Smith', 'student'),
('A20CS1002', 'student2@student.university.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Emily Davis', 'student'),
('A20CS1003', 'student3@student.university.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Marcus Johnson', 'student');

-- Sample courses
INSERT INTO courses (course_code, course_name, credits, semester, academic_year) VALUES
('SECJ3483', 'Web Technology', 3, '2024/2025-1', '2024/2025'),
('SECJ2203', 'Database Systems', 3, '2024/2025-1', '2024/2025'),
('SECJ3103', 'Software Engineering', 3, '2024/2025-1', '2024/2025');

-- Link lecturers to courses
INSERT INTO course_lecturers (lecturer_id, course_id, role) VALUES
(1, 1, 'primary'), -- Dr. Sarah teaches Web Technology
(2, 2, 'primary'), -- Prof. Michael teaches Database Systems
(1, 3, 'primary'); -- Dr. Sarah also teaches Software Engineering

-- Enroll students in courses
INSERT INTO course_enrollments (student_id, course_id) VALUES
(4, 1), (4, 2), (4, 3), -- John takes all three courses
(5, 1), (5, 2), -- Emily takes Web Tech and Database
(6, 1), (6, 3); -- Marcus takes Web Tech and Software Engineering

-- Assign advisor to students
INSERT INTO advisor_assignments (advisor_id, student_id, assigned_date) VALUES
(3, 4, '2024-09-01'), -- Dr. Amanda advises John
(3, 5, '2024-09-01'), -- Dr. Amanda advises Emily
(3, 6, '2024-09-01'); -- Dr. Amanda advises Marcus

-- Sample assessment components for Web Technology course
INSERT INTO assessment_components (course_id, component_name, component_type, max_marks, weight_percentage, description, is_published) VALUES
(1, 'Quiz 1', 'quiz', 20.00, 10.00, 'HTML and CSS Fundamentals', TRUE),
(1, 'Assignment 1', 'assignment', 100.00, 15.00, 'Build a responsive website using HTML/CSS', TRUE),
(1, 'Lab Exercise 1', 'lab', 50.00, 5.00, 'JavaScript DOM Manipulation', TRUE),
(1, 'Test 1', 'test', 100.00, 20.00, 'Mid-semester examination covering HTML, CSS, JavaScript', TRUE),
(1, 'Assignment 2', 'assignment', 100.00, 20.00, 'Full-stack web application using PHP and MySQL', FALSE),
(1, 'Final Exam', 'final_exam', 100.00, 30.00, 'Comprehensive final examination', FALSE);

-- Some sample marks for demonstration
INSERT INTO student_marks (student_id, assessment_component_id, marks_obtained, graded_by, graded_at, is_final) VALUES
(4, 1, 18.00, 1, NOW(), TRUE), -- John's Quiz 1: 18/20
(4, 2, 85.00, 1, NOW(), TRUE), -- John's Assignment 1: 85/100
(4, 3, 45.00, 1, NOW(), TRUE), -- John's Lab 1: 45/50
(5, 1, 16.00, 1, NOW(), TRUE), -- Emily's Quiz 1: 16/20
(5, 2, 92.00, 1, NOW(), TRUE), -- Emily's Assignment 1: 92/100
(5, 3, 48.00, 1, NOW(), TRUE), -- Emily's Lab 1: 48/50
(6, 1, 14.00, 1, NOW(), TRUE), -- Marcus's Quiz 1: 14/20
(6, 2, 78.00, 1, NOW(), TRUE), -- Marcus's Assignment 1: 78/100
(6, 3, 42.00, 1, NOW(), TRUE); -- Marcus's Lab 1: 42/50