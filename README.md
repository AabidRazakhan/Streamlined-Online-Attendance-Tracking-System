# Web-based-Attendance-management-system
This project is a simple Web-based Student Management System built using PHP, HTML, CSS, JavaScript, and MySQL for database management. It allows administrators to manage student information such as adding new students, updating their details, and deleting or viewing the records.

# Features
Add Student: Allows adding a new student with personal and academic details.
Update Student: Enables updating the student's information.
Delete Student: Remove a student’s record from the system.
View Student: Display a list of all students with their details.

# Tech Stack
Frontend: HTML, CSS, JavaScript
Backend: PHP
Database: MySQL

# Getting Started
# Prerequisites
Before running the project, make sure you have the following installed:

PHP (Version 7.4 or above)
MySQL (Version 5.7 or above)
Apache Server (You can use XAMPP or WAMP for this)

# Installation
Follow these steps to set up the project locally:

# Clone the Repository:
Copy code
git clone https://github.com/AabidRazakhan/Student-management-system.git

# Move to the Project Directory:
Copy code
cd Student-management-system

# Set up the Database:

Open phpMyAdmin or any MySQL client.
* Create a database named student_management.
* Import the SQL file located in the repository (student_management.sql) to create the necessary tables.
  
Copy code
# CREATE DATABASE student_management;
* USE student_management;
* SOURCE path_to_your_downloaded_repo/student_management.sql;

# Configure Database Connection:
Open the file config.php in the project directory.
Update the database credentials to match your local environment.

<?php
$servername = "localhost";
$username = "root"; // your MySQL username
$password = ""; // your MySQL password
$dbname = "student_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>

# Run the Project:

Move the project folder to your web server directory (e.g., htdocs for XAMPP).
Start your web server (Apache and MySQL in XAMPP).

# Open your browser and navigate to:
Copy code
http://localhost/Student-management-system

# Usage
# Dashboard: From the main dashboard, you can view all students' records, add a new student, update details, or delete a student.
# Add Student: Fill in the form with personal and academic details, and submit it to add a new student.
# Update Student: Click on a student’s record to edit their information.
# Delete Student: Remove a student from the system by clicking the delete button next to their record.
