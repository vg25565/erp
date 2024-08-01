CREATE DATABASE IF NOT EXISTS students;

USE students;

CREATE TABLE IF NOT EXISTS student_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    branch VARCHAR(255) NOT NULL,
    year VARCHAR(255) NOT NULL,
    student_id VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table to store faculty login information
CREATE TABLE IF NOT EXISTS faculty (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table to store login history
CREATE TABLE IF NOT EXISTS login_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    faculty_id INT NOT NULL,
    login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (faculty_id) REFERENCES faculty(id)
);

-- Table to store student details
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(255) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    year VARCHAR(255) NOT NULL
);

-- Table to store faculty timetables
CREATE TABLE IF NOT EXISTS timetables (
    id INT AUTO_INCREMENT PRIMARY KEY,
    faculty_id INT NOT NULL,
    day_of_week VARCHAR(255) NOT NULL,
    period INT NOT NULL,
    subject VARCHAR(255) NOT NULL,
    FOREIGN KEY (faculty_id) REFERENCES faculty(id)
);

-- Table to store attendance records
CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    faculty_id INT NOT NULL,
    student_id VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    status ENUM('Present', 'Absent') NOT NULL,
    FOREIGN KEY (faculty_id) REFERENCES faculty(id),
    FOREIGN KEY (student_id) REFERENCES students(student_id)
);

-- Insert sample faculty records
INSERT INTO faculty (email, password) VALUES 
('teacher1@example.com', SHA2('password1', 256)),
('teacher2@example.com', SHA2('password2', 256));

-- Insert sample students
INSERT INTO students (student_id, name, year) VALUES 
('S001', 'Alice Smith', 'FE-A'),
('S002', 'Bob Johnson', 'FE-A'),
('S003', 'Charlie Brown', 'FE-B');

-- Insert sample timetable records
INSERT INTO timetables (faculty_id, day_of_week, period, subject) VALUES 
(1, 'Monday', 1, 'Mathematics'),
(1, 'Wednesday', 2, 'Physics'),
(2, 'Tuesday', 1, 'Chemistry'),
(2, 'Thursday', 2, 'Biology');
