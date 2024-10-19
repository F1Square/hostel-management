<?php
// db_connection.php

// Database configuration
$host = 'localhost'; // Database host
$dbname = 'hostel-manage'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
