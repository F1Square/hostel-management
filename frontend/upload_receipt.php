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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    if (isset($_FILES['receiptFile']) && $_FILES['receiptFile']['error'] == 0) {

        $fileTmpPath = $_FILES['receiptFile']['tmp_name'];
        $fileName = $_FILES['receiptFile']['name'];
        
        $uploadFileDir = './uploads/';
        $dest_path = $uploadFileDir . basename($fileName);
        
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            // Get the OTR number from the session
            if (isset($_SESSION['otr_number'])) {
                $otr_number = $_SESSION['otr_number']; 
                
                
                $stmt_check = $conn->prepare("SELECT COUNT(*) FROM users WHERE otr_number = ?");
                $stmt_check->bind_param("s", $otr_number);
                $stmt_check->execute();
                $stmt_check->bind_result($count);
                $stmt_check->fetch();
                $stmt_check->close();

                if ($count > 0) {
                    
                    $stmt = $conn->prepare("INSERT INTO receipts (otr_number, file_path) VALUES (?, ?)");
                    $stmt->bind_param("ss", $otr_number, $dest_path);

                    if ($stmt->execute()) {
                        echo "<script>alert('Receipt uploaded successfully!');</script>";
                        echo "<script>window.location.href='hostel-fees.php';</script>"; 
                    } else {
                        echo "<script>alert('Error inserting data: " . $stmt->error . "');</script>";
                    }
                    $stmt->close(); 
                } else {
                    echo "<script>alert('OTR number does not exist in users table.');</script>";
                }
            } else {
                echo "<script>alert('OTR number is not set in the session.');</script>";
            }
        } else {
            echo "<script>alert('There was an error moving the uploaded file.');</script>";
        }
    } else {
        echo "<script>alert('No file uploaded or there was an upload error.');</script>";
    }
}

$conn->close();
?>
