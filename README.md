
# Streamlined Online Attendance & Tracking System

Streamlined Online Attendance & Tracking System is a simple, efficient, and user-friendly platform built using PHP, HTML, CSS, JavaScript, and MySQL. It allows educational institutions to manage student information, records, and academic performance digitally. This system can be used by administrators and teachers to easily manage student data, making it an ideal project for college and school systems.


## Technologies Used

The system leverages the following technologies:

- **PHP**: Backend server-side language for processing and handling logic.
- **HTML5**: Markup language for structuring content on the web.
- **CSS3** : Stylesheet language for designing and styling web pages.
- **JavaScript**: Frontend scripting language for adding interactivity and dynamic elements.
- **MySQL**: Relational database management system for storing student, admin, and academic information.
- **AJAX**: Technique for asynchronous data fetching and updating without page reload.
- **Bootstrap 4**: Frontend framework for responsive and mobile-first UI design.



## Features

1. **Student Information Management**  
   Add, edit, delete, and view student profiles, including academic and personal data.

2. **User Role Management**  
   Admins have full access; teachers have limited access to assigned students.

3. **Academic Performance Tracking**  
   Manage grades and attendance, with report generation capabilities.

4. **Search and Filter Options**  
   Quickly find student records with search and filtering tools.

5. **Responsive User Interface**  
   Access the system on mobile, tablet, and desktop devices seamlessly.


## Installation Instructions

Follow these steps to set up the Student Management System on your local machine:

1. **Clone the Repository**  
   Clone the repository from GitHub to your local machine:  
   ```bash
   https://github.com/AabidRazakhan/Streamlined-Online-Attendance-Tracking-System.git
2. **Set Up Database**

- Create a new MySQL database.
- Import the SQL file provided in the repository (usually located in the /database/ directory) into your MySQL database to create the necessary tables.
3. **Configure Database Connection**
Open the db_config.php file located in the /includes/ directory and update the database connection details.

4. **Run the Application**

- Start your local server (e.g., using XAMPP or WAMP).
- Navigate to the project directory and open index.php in your browser. You should now be able to access the login page and use the system.
5. **Default Admin Credentials**
Use the following credentials to log in:

- **Username:** admin
- **Password:** password123
(You can modify this in the database or through the admin panel.)


    
## Screenshots


**Login Page**![Screenshot (196)](https://github.com/user-attachments/assets/9ff9771e-5e41-4007-a690-ff089366f304)

**Dashboard Page**![Screenshot (190)](https://github.com/user-attachments/assets/9f8eba24-636c-43cb-b01b-9714b5ef91b1)



## Future Enhancements
The following features are planned for future development:

- **Student Login**:  
  Allow students to log in and view their own data and academic performance.

- **Parent Access**:  
  Provide parents with access to monitor their child’s academic performance.

- **Notifications**:  
  Implement email or SMS notifications for important updates, such as exam schedules and attendance issues.

- **Attendance Management**:  
  Integrate detailed attendance tracking with automated reporting capabilities.
