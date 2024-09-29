Job Portal Android Application

This repository contains the source code for a Job Portal Android Application developed using Java and MySQL. The application allows job seekers to search for job listings, apply for jobs, and manage their applications.

Features
For Job Seekers:
- Browse job listings by category
- Apply for jobs by uploading a resume and cover letter
- View saved/bookmarked jobs
- Manage job applications

Technology Stack

- Frontend: Android (Java)
- Backend: PHP
- Database: MySQL
- API Communication: RESTful API (PHP-based)

Prerequisites

Before running the application, ensure that you have the following installed:

1. Android Studio: The development environment for Android applications.
2. XAMPP or WAMP: A local server to host your MySQL database and PHP files.
3. MySQL: For the database management system.
4. Java: For the Android application logic.

Project Setup

Step 1: Backend (PHP + MySQL Setup)

1. Clone or download the repository.
2. Navigate to the `backend` folder and place it in the `htdocs` directory of your local server (XAMPP/WAMP).
3. Create a MySQL database called `job_portal`.
4. Import the SQL file `job_portal.sql` located in the `backend` folder into your MySQL database.
5. Open `config.php` in the `backend` folder and update the database credentials:
   php
   <?php
   $dbHost = 'localhost';
   $dbUser = 'root'; // Update with your MySQL username
   $dbPass = '';     // Update with your MySQL password
   $dbName = 'job_portal'; 
   ?>
   

6. Start your XAMPP/WAMP server and make sure Apache and MySQL services are running.

Step 2: Android Application Setup

1. Open Android Studio.
2. Clone or download this repository.
3. Open the Android project in Android Studio.

   Replace `YOUR_LOCAL_SERVER_IP` with your local IP address (e.g., `http://192.168.1.100/backend/`) or your domain name if hosted remotely.
   
4. Sync and build the project.

Step 3: Running the Application

1. Connect your Android device or use an Android emulator.
2. Run the project from Android Studio.
3. The application should launch, allowing you to register as a job seeker or employer.

Usage
1. Sign Up: Register as a new user.
2. Login: Login using your credentials to access the Job Portal.
3. Job Seekers:
   - Browse available jobs, save them, or apply with your resume.

License
This project is licensed under the MIT License.
