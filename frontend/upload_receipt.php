<?php
session_start(); // Start the session to access session variables

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hostel-manage";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and handle the file upload
    if (isset($_FILES['receiptFile']) && $_FILES['receiptFile']['error'] == 0) {
        // Get the file info
        $fileTmpPath = $_FILES['receiptFile']['tmp_name'];
        $fileName = $_FILES['receiptFile']['name'];
        
        // Define the upload directory
        $uploadFileDir = './uploads/';
        $dest_path = $uploadFileDir . basename($fileName);

        // Move the uploaded file to the destination directory
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            // Get the OTR number from the session
            if (isset($_SESSION['otr_number'])) {
                $otr_number = $_SESSION['otr_number']; // Get the OTR number from the session
                
                // Check if otr_number exists in the users table
                $stmt_check = $conn->prepare("SELECT COUNT(*) FROM users WHERE otr_number = ?");
                $stmt_check->bind_param("s", $otr_number);
                $stmt_check->execute();
                $stmt_check->bind_result($count);
                $stmt_check->fetch();
                $stmt_check->close();

                if ($count > 0) {
                    // OTR number exists, proceed with the insertion
                    $stmt = $conn->prepare("INSERT INTO receipts (otr_number, file_path) VALUES (?, ?)");
                    $stmt->bind_param("ss", $otr_number, $dest_path);

                    if ($stmt->execute()) {
                        echo "<script>alert('Receipt uploaded successfully!');</script>";
                        echo "<script>window.location.href='hostel-fees.php';</script>"; // Redirect to hostel-fees.php
                    } else {
                        echo "<script>alert('Error inserting data: " . $stmt->error . "');</script>";
                    }
                    $stmt->close(); // Close statement
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
