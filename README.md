# Streamlined Online Attendance & Tracking System
Web-based Student Management System is a simple, efficient, and user-friendly platform built using PHP, HTML, CSS, JavaScript, and MySQL. It allows educational institutions to manage student information, records, and academic performance digitally. This system can be used by administrators and teachers to easily manage student data, making it an ideal project for college and school systems.

# Features
# 1. Student Information Management
Add, edit, delete, and view student details.
Manage student academic information, personal details, and enrollment data.
# 2. User Roles
Admin: Full access to manage students and system data.
Teacher: Restricted access to manage specific data related to assigned students.
# 3. Student Academic Management
Manage academic records such as grades and attendance.
Generate reports and view overall student performance.
# 4. Search and Filter
Quickly search for student records using a search bar.
Filter students based on different criteria like grade, performance, etc.
# 5. Responsive Design
The application is responsive and can be accessed on different devices, including mobile, tablet, and desktop, ensuring a consistent experience.

# Technologies Used
The system leverages the following technologies:

PHP: Backend server-side language for processing and handling logic.

HTML5 & CSS3: Frontend structure and styling.
JavaScript: Frontend scripting for dynamic elements.
MySQL: Relational database for storing student, admin, and academic information.
AJAX: Used for asynchronous data fetching and updating without page reload.
Bootstrap 4: For responsive and mobile-first UI design.
MySQL: Relational database for storing student, admin, and academic information.
AJAX: Used for asynchronous data fetching and updating without page reload.
Bootstrap 4: For responsive and mobile-first UI design.

# Installation Instructions
# 1. Clone the Repository
Clone the repository from GitHub to your local machine:
```bash
git clone https://github.com/AabidRazakhan/Student-management-system.git
# 2. Set Up Database
Create a new MySQL database.
Import the SQL file provided in the repository (usually located in the /database/ directory) into your MySQL database to create the necessary tables.
# 3. Configure Database Connection
Open the db_config.php file located in the /includes/ directory.
<?php
$servername = "your_servername";
$username = "your_username";
$password = "your_password";
$dbname = "your_dbname";
?>
# 4. Run the Application
Start your local server (e.g., using XAMPP or WAMP).
Navigate to the project directory and open index.php in your browser.
You should now be able to access the login page and use the system.
# 5. Default Admin Credentials
Username: admin
Password: password123 (You can modify this in the database or through the admin panel.)
# Screenshots

![Screenshot (184)](https://github.com/user-attachments/assets/3175a530-e727-4d59-b6cf-42e4127db0c0)
![Screenshot (190)](https://github.com/user-attachments/assets/9f8eba24-636c-43cb-b01b-9714b5ef91b1)
![Screenshot (191)](https://github.com/user-attachments/assets/5995a555-54d6-4d73-a023-14da97c9af00)
![Screenshot (188)](https://github.com/user-attachments/assets/1a7af234-3638-4672-87b5-51f12c48f26d)

# Future Enhancements
Student Login: Allow students to log in and view their own data and performance.
Parent Access: Provide parents with access to monitor their child’s academic performance.
Notifications: Email or SMS notifications for important updates like exam schedules, attendance issues, etc.
Attendance Management: Integrate detailed attendance tracking with automated reports.
