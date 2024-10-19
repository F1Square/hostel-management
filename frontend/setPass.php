<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('location: index.php');
    exit();
}

if (isset($_POST['submit'])) {
    $con = new mysqli('localhost', 'root', '', 'hostel-manage');

    if ($con->connect_error) {
        die('Connection Failed: ' . $con->connect_error);
    }

    $email = $con->real_escape_string($_POST['email']);
    $password = $con->real_escape_string($_POST['password']);
    $password2 = $con->real_escape_string($_POST['password2']);

    // Check if passwords match
    if ($password != $password2) {
        header('location: newpassword.php?mp=false');
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Check if the email exists
    $checkEmail = $con->query("SELECT id FROM users WHERE email='$email'");

    if ($checkEmail->num_rows > 0) {
        // Terminate token and reset the link
        $con->query("UPDATE users SET keyToken='' WHERE email='$email'");
        // Update password
        $con->query("UPDATE users SET password='$hashedPassword' WHERE email='$email'");
        header('location: login.php/?resetStatus=success');
    } else {
        header('location: newpassword.php?mp=emailNotFound');
    }
    $con->close();
}
?>
