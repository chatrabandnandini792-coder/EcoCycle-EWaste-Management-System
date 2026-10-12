<?php
mysqli_report(MYSQLI_REPORT_OFF);
$host = 'localhost';
$user = 'root';
$password = ''; // Default XAMPP MySQL password is often blank on local setups.
$database = 'ewaste_management';
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    http_response_code(500);
    exit('Database connection failed. Start MySQL in XAMPP and import database.sql in phpMyAdmin. Details: ' . htmlspecialchars($conn->connect_error));
}
$conn->set_charset('utf8mb4');
