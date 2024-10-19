<?php
// Database connection
$servername = "localhost";  // Replace with your server details
$username = "root";         // Replace with your database username
$password = "";             // Replace with your database password
$dbname = "hostel-manage";  // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $type = $_POST['type'];
    $reason = $_POST['reason'];
    $outdate = $_POST['outdate'];
    $returndate = $_POST['returndate'];
    $outtime = $_POST['outtime'];
    $intime = $_POST['intime'];

    // Insert data into database
    $sql = "INSERT INTO manages (type, reason, outdate, returndate, outtime, intime) 
            VALUES ('$type', '$reason', '$outdate', '$returndate', '$outtime', '$intime')";

    if ($conn->query($sql) === TRUE) {
        echo "Record inserted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the connection
    $conn->close();
}
?>
