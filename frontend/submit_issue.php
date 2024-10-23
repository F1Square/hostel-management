<?php
session_start(); 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hostel-manage";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['issue'])) {
    $issue = $_POST['issue'];
    $otr_number = $_SESSION['otr_number']; 

    
    $stmt = $conn->prepare("INSERT INTO maintenance_issues (otr_number, issue) VALUES (?, ?)");
    $stmt->bind_param("ss", $otr_number, $issue);

    
    if ($stmt->execute()) {
        
        echo "<script>
                alert('Maintenance issue submitted successfully.');
                window.location.href = 'maintenance-issue.php'; // Redirect to maintenance-issue.php
              </script>";
    } else {
        
        echo "<script>
                alert('Error: " . addslashes($stmt->error) . "');
              </script>";
    }

    $stmt->close();
}

$conn->close();
?>
