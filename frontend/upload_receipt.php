<?php
session_start(); // Assuming session is being used to track logged-in user info

// Connect to your database
include 'db_connection.php'; // Make sure to include your database connection file

// Check if a file has been uploaded
if (isset($_FILES['receiptFile']) && $_FILES['receiptFile']['error'] == 0) {
    $targetDir = "uploads/"; // Directory to save uploaded files
    $fileName = basename($_FILES["receiptFile"]["name"]);
    $targetFilePath = $targetDir . $fileName;

    // Allow certain file formats (you can extend this if needed)
    $allowedTypes = array('jpg', 'png', 'jpeg', 'pdf');
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    if (in_array(strtolower($fileType), $allowedTypes)) {
        // Move uploaded file to server directory
        if (move_uploaded_file($_FILES["receiptFile"]["tmp_name"], $targetFilePath)) {
            // Get student ID or other info from session
            $studentId = $_SESSION['student_id']; // Assuming logged-in student has ID stored in session
            
            // Insert file info and status into database
            $query = "INSERT INTO fee_uploads (student_id, file_path, status) VALUES ('$studentId', '$targetFilePath', 'Pending')";
            if (mysqli_query($conn, $query)) {
                $_SESSION['uploadSuccess'] = "Receipt uploaded successfully!";
            } else {
                $_SESSION['uploadError'] = "Failed to save receipt information in the database.";
            }
        } else {
            $_SESSION['uploadError'] = "Sorry, there was an error uploading your file.";
        }
    } else {
        $_SESSION['uploadError'] = "Sorry, only JPG, JPEG, PNG & PDF files are allowed.";
    }
} else {
    $_SESSION['uploadError'] = "Please select a file to upload.";
}

// Redirect back to the main page after upload
header("Location: hostel_fees.php");
?>
