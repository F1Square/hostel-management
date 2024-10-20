<?php
session_start();
$conn = new mysqli('localhost', 'username', 'password', 'hostel_management');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id']; // Assuming user ID is stored in session
    $targetDir = "uploads/"; // Directory for uploaded receipts
    $targetFile = $targetDir . basename($_FILES["receiptFile"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if file is an image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["receiptFile"]["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check if file already exists
    if (file_exists($targetFile)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["receiptFile"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["receiptFile"]["tmp_name"], $targetFile)) {
            // Insert file info into database
            $stmt = $conn->prepare("INSERT INTO receipts (user_id, file_path) VALUES (?, ?)");
            $stmt->bind_param("is", $userId, $targetFile);
            $stmt->execute();
            echo "The file " . htmlspecialchars(basename($_FILES["receiptFile"]["name"])) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

$conn->close();
?>
