<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    $email = $_POST['email'];
    $token = $_POST['token'];

    // Check if passwords match
    if ($password !== $password2) {
        header('Location: resetPassword.php?email=' . urlencode($email) . '&token=' . urlencode($token) . '&mp=false');
        exit();
    }

    // Regex pattern to validate the password (uppercase, lowercase, special character)
    $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{6,}$/';

    // Validate password
    if (!preg_match($passwordPattern, $password)) {
        // Redirect with an error message
        header('Location: resetPassword.php?email=' . urlencode($email) . '&token=' . urlencode($token) . '&mp=invalid');
        exit();
    }

    // Proceed with password update (hash it before saving)
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Update the password in the database (you would perform the SQL update here)
    $sql = "UPDATE users SET password='$hashedPassword' WHERE email='$email' AND keyToken='$token'";

    if ($con->query($sql) === TRUE) {
        echo "Password updated successfully.";
        // Redirect to login or success page
        header('Location: login.php?reset=success');
    } else {
        echo "Error updating password: " . $con->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Set New Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
</head>
<body>
    <div class="container text-center" style="padding-top:4%;">
        <h3 class="display-4"> Reset Password </h3>
        
        <?php
        if (!empty($msg)) {
            echo $msg; 
        }
        ?>

        <form action="setPass.php" method="post">
            <p> Reset password for <?php echo htmlspecialchars($email); ?> </p><br>
            <input type="password" class="form-control" placeholder="New password" name="password" id="password" required/> <br>
            <input type="password" class="form-control" placeholder="Re-enter new password" name="password2" id="password2" required/> <br>
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>" />
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>" />
            <input type="submit" name="submit" class="btn btn-primary" />
        </form>
    </div>
</body>
</html>
