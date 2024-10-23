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

    if ($password != $password2) {
        header('location: newpassword.php?mp=false');
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $checkEmail = $con->query("SELECT id FROM users WHERE email='$email'");

    if ($checkEmail->num_rows > 0) {

        $con->query("UPDATE users SET keyToken='' WHERE email='$email'");
        $con->query("UPDATE users SET password='$hashedPassword' WHERE email='$email'");
        header('location: login.php/?resetStatus=success');
    } else {
        header('location: newpassword.php?mp=emailNotFound');
    }
    $con->close();
}
?>
