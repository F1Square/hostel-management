<?php
function redirect() {
    header('Location: register.php');
    exit();
}

if (!isset($_GET['email']) || !isset($_GET['token'])) {
    redirect();
} else {
    $con = mysqli_connect('localhost', 'root', '', 'hostel-manage');

    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $email = $con->real_escape_string($_GET['email']);
    $token = $con->real_escape_string($_GET['token']);

    $sql = "SELECT id FROM users WHERE email='$email' AND token='$token' AND isEmailConfirmed=0";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        $sql = "UPDATE users SET isEmailConfirmed=1, token='' WHERE email='$email'";
        if (mysqli_query($con, $sql)) {
            $message = "Email verification complete! You can now log in.";
        } else {
            $message = "Error updating record: " . mysqli_error($con);
        }
    } else {
        $message = "Invalid token or email.";
    }
}

$con->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email Confirmation</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
</head>
<body>
    <div class="jumbotron text-center">
        <h2 class="display-3"><?php echo isset($message) ? $message : "Processing..."; ?></h2>
        <p class="lead">Please <a href="login.php">Login</a> to continue.</p>
    </div>
</body>
</html>
