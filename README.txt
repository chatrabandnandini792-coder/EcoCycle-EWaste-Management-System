EcoCycle - E-Waste Management System
=====================================

TECH STACK
- HTML5 + CSS3 + JavaScript for the responsive user interface
- PHP for server-side processing
- MySQL database managed using phpMyAdmin
- Runs locally with XAMPP (Apache + MySQL)

IMPORTANT NOTE ABOUT "JAVA"
Java and JavaScript are different technologies. This project uses JavaScript for browser interactions and PHP to connect to MySQL through XAMPP/phpMyAdmin. If your college specifically requires Java (JSP/Servlets), this PHP project does not meet that Java-backend requirement.

INSTALLATION ON WINDOWS / XAMPP
1. Extract the folder named E_Waste_Management_System into:
   C:\xampp\htdocs\
   You should have:
   C:\xampp\htdocs\E_Waste_Management_System\index.php
2. Open XAMPP Control Panel. Start Apache and MySQL.
3. Open http://localhost/phpmyadmin/
4. Click the Import tab. Choose the file:
   C:\xampp\htdocs\E_Waste_Management_System\database.sql
   (If the extracted folder is elsewhere, choose database.sql from that folder.)
5. Click Import / Go and wait for the success message.
6. Open this website:
   http://localhost/E_Waste_Management_System/
7. Admin dashboard:
   http://localhost/E_Waste_Management_System/admin/login.php

DEMO ADMIN LOGIN
Username: admin
Password: EcoCycle@123

FEATURES
- Responsive EcoCycle landing page
- E-waste pickup request form with validation
- Saves requests to MySQL database
- Request ID confirmation after submission
- Categories for common electronic waste
- Dashboard with request counts
- Filter requests by status
- Admin can update status: Pending, Approved, Collected, Recycled, Cancelled
- Escaped output and prepared statements for form submission

DATABASE
Database name: ewaste_management
Table: pickup_requests

TROUBLESHOOTING
- If the website shows "Database connection failed", make sure MySQL is running and database.sql was imported.
- Default XAMPP commonly uses username root and a blank password. If you set a MySQL root password, update includes/db.php.
- If localhost shows 404, confirm the folder is exactly:
  C:\xampp\htdocs\E_Waste_Management_System\
  and that index.php is directly inside it.
- URL folder names must match the folder name exactly.
- If Apache does not start, another app may already be using port 80.
- Do not upload the demo admin credentials to a public website. Change the login implementation before deployment.
