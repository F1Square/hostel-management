<?php
session_start();
if (!isset($_SESSION['role'])) {
    // User is not an admin, show alert and redirect to a different page (like homepage)
    echo "<script>
        alert('You need to login first. Access denied.');
        window.location.href = 'index.php'; // Redirect to the homepage or any other page
    </script>";
    exit(); // Stop further execution
}

if ($_SESSION['role'] !== 'student') {
    // User is not an admin, show alert and redirect to a different page (like homepage)
    echo "<script>
        alert('You are not an Studnet. Access denied.');
        window.location.href = 'index.php'; // Redirect to the homepage or any other page
    </script>";
    exit(); // Stop further execution
}
include('db_connection.php'); // Include your database connection file

// Assuming the user is logged in and you have the user ID stored in the session
$user_id = $_SESSION['otr_number'];

// Fetch the user's old password hash from the database
$sql = "SELECT password FROM users WHERE otr_number = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // Bind the user_id parameter
$stmt->execute();
$stmt->bind_result($storedPasswordHash);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Verify if the old password matches the stored password
    if (!password_verify($old_password, $storedPasswordHash)) {
        echo "<script>alert('Old password is incorrect!'); window.location.href='change-password.php';</script>";
        exit;
    }

    // Check if the new password meets the complexity requirements
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $new_password)) {
        echo "<script>alert('New password must contain at least 8 characters, one uppercase letter, one lowercase letter, one digit, and one special character.'); window.location.href='change-password.php';</script>";
        exit;
    }

    // Check if new password and confirm password match
    if ($new_password !== $confirm_password) {
        echo "<script>alert('New password and confirm password do not match!'); window.location.href='change-password.php';</script>";
        exit;
    }

    // Hash the new password
    $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the password in the database
    $sql = "UPDATE users SET password = ? WHERE otr_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_password_hash, $user_id);
    $stmt->execute();

    // Assuming password update is successful
    echo "<script>alert('Password changed successfully!'); window.location.href='change-password.php';</script>";
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SDHostel - Change Password</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            height: 100vh;
            background-color: #f4f4f4;
        }

        .sidebar {
            width: 250px;
            background-color: #333;
            color: white;
            padding: 20px;
            position: fixed;
            height: 100%;
        }

        .sidebar .menu {
            list-style-type: none;
            margin-top: 20px;
        }

        .menu li {
            padding: 10px 0;
            cursor: pointer;
        }

        .menu li:hover {
            background-color: #444;
        }

        .menu li a {
            text-decoration: none;
            color: white;
            display: block;
            padding-left: 10px;
        }

        .menu li a:hover {
            color: #ddd;
        }

        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            margin-left: 250px;
        }

        .top-bar {
            background-color: #2c91c1;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        .top-bar h1 a {
            color: white;
            text-decoration: none;
        }

        .user {
            position: relative;
            display: inline-block;
        }

        .user img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
        }

        .dropdown {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            z-index: 1;
        }

        .dropdown a {
            display: block;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
        }

        .dropdown a:hover {
            background-color: #f1f1f1;
        }

        .show {
            display: block;
        }

        .main-content {
            padding: 20px;
        }

        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }

        .form-container h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group input:focus {
            outline: none;
            border-color: #2c91c1;
        }

        .form-group button {
            padding: 10px 20px;
            background-color: #2c91c1;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #2379a1;
        }
    </style>
    <script>
        function toggleDropdown() {
            document.getElementById("logoutDropdown").classList.toggle("show");
        }

        window.onclick = function(event) {
            if (!event.target.matches('.user img')) {
                var dropdowns = document.getElementsByClassName("dropdown");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }

        function validatePassword() {
            var oldPassword = document.getElementById("old_password").value;
            var newPassword = document.getElementById("new_password").value;
            var confirmPassword = document.getElementById("confirm_password").value;

            // Regular expression to check password complexity
            var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

            if (!regex.test(newPassword)) {
                alert("New password must contain at least 8 characters, one uppercase letter, one lowercase letter, one digit, and one special character.");
                return false;
            }

            if (newPassword !== confirmPassword) {
                alert("New password and confirm password do not match.");
                return false;
            }

            return true;  // If everything is valid, allow form submission
        }
    </script>
</head>

<body>
    <div class="sidebar">
        <ul class="menu">
            <li><a href="hostel-fees.php">Hostel Fees</a></li>
            <li><a href="maintenance-issue.php">Maintenance Issue</a></li>
            <li><a href="gate-pass.php">Gate Pass & Leave</a></li>
            <li><a href="gate-pass-status.php">Status</a></li>
            <li><a href="change-password.php">Change Password</a></li>
        </ul>
    </div>

    <div class="content">
        <div class="top-bar">
            <h1><a href="dashboard.php">SDHOSTEL</a></h1>
            <div class="user">
                <img src="photos/Gpay.png" alt="User Image" onclick="toggleDropdown()">
                <div id="logoutDropdown" class="dropdown">
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="form-container">
                <h2>Change Password</h2>
                <form action="change-password.php" method="POST" onsubmit="return validatePassword();">
                    <div class="form-group">
                        <label for="old_password">Old Password</label>
                        <input type="password" id="old_password" name="old_password" placeholder="Enter Old Password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" placeholder="Enter New Password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Enter Same As New Password" required>
                    </div>
                    <div class="form-group">
                        <button type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
