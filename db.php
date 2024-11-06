<?php
// db.php - Database Connection
$host = '127.0.0.1';  // Or 'localhost'
$user = 'root';       // Default XAMPP MySQL user
$password = '';       // Default XAMPP MySQL password
$dbname = 'o_s_m_s';

// Establishing the connection
$connection = new mysqli($host, $user, $password, $dbname);

// Checking for a connection error
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
?>
