FEEDBACK SYSTEM SETUP INSTRUCTIONS
====================================

PREREQUISITES:
1. XAMPP/WAMP/MAMP installed
2. PHP 7.4 or higher
3. MySQL 5.7 or higher

SETUP STEPS:
============

1. Start your local server (Apache and MySQL)

2. Create the database:
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create new database named 'feedback_db'
   - Run the SQL script provided in your requirement (copy and execute all CREATE TABLE statements)

3. Configure database connection:
   - Open config.php
   - Update database credentials:
     $username = 'root' (or your MySQL username)
     $password = '' (or your MySQL password)

4. Create admin user (if not already in SQL):
   Run this in phpMyAdmin SQL tab:
   INSERT INTO Admin (AdminName, Email, Password) VALUES 
   ('Admin', 'admin@feedback.com', 'admin123');

5. Place all PHP files in your web server directory:
   - For XAMPP: C:\xampp\htdocs\feedback-system\
   - For WAMP: C:\wamp\www\feedback-system\

6. Access the system:
   - Open browser and go to: http://localhost/feedback-system/

USAGE:
======

For Users:
----------
1. Submit feedback through the main page
2. Rate products from 1-5 stars
3. View all public feedback with responses

For Admins:
-----------
1. Login at: http://localhost/feedback-system/admin_login.php
2. Demo credentials: admin@feedback.com / admin123
3. View dashboard with statistics
4. Respond to pending feedback
5. Update feedback status (Pending/Resolved)

FEATURES:
=========
✅ User feedback submission
✅ Product and category selection
✅ Rating system (1-5 stars)
✅ Sentiment analysis (Positive/Negative/Neutral)
✅ Admin dashboard with statistics
✅ Response system for admins
✅ Search and filter feedback
✅ View all feedback publicly
✅ Responsive design
✅ Database views for optimized queries

TROUBLESHOOTING:
================

1. "Connection failed" error:
   - Check if MySQL is running
   - Verify database credentials in config.php
   - Ensure database 'feedback_db' exists

2. "Table not found" error:
   - Run the complete SQL script again
   - Check if all tables were created successfully

3. "Cannot modify header" error:
   - Remove any spaces or output before <?php tags
   - Check file encoding (should be UTF-8 without BOM)

SUPPORT:
========
For any issues, check that all files are in the same directory and database is properly configured.

DEMO DATA:
==========
The SQL script includes sample data for testing:
- 4 products
- 5 categories  
- 5 users
- 8 feedback entries
- Admin user (admin@feedback.com / admin123)

FILE STRUCTURE:
===============
1. config.php - Database configuration
2. index.php - Main page with feedback form
3. submit_feedback.php - Process feedback submission
4. view_feedback.php - Public feedback view
5. admin_login.php - Admin authentication
6. admin_dashboard.php - Admin control panel
7. respond_feedback.php - Response form for