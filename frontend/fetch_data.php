<?php
session_start();
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== 1) {
    header('Location: login.php');
    exit();
}

// Database connection
$con = new mysqli('localhost', 'root', '', 'hostel-manage');

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Fetch gate pass and leave requests for the logged-in user
$otr_number = $_SESSION['otr_number'];
$query = "SELECT * FROM gatepass WHERE otr_number = '$otr_number'";
$result = $con->query($query);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row; // Collect data
    }
}

echo json_encode($data); // Return data in JSON format
$con->close();
?>
