<?php
// Database configuration for XAMPP
define('DB_HOST', 'localhost');
define('DB_USER', 'root');     // Default XAMPP username
define('DB_PASS', '');         // Default XAMPP password
define('DB_NAME', 'voting_db'); // Your database name

// Create connection
$conn = new mysqli("localhost", "root", "", "voting_system");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>