<?php
// config.php
$host = 'localhost'; // Database host
$dbname = 'lms'; // Your database name
$username = 'root'; // Database username
$password = ''; // Database password

// Create a MySQLi connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check if the connection was successful
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
