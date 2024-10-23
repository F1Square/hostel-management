<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    $email = $_POST['email'];
    $token = $_POST['token'];

    
    if ($password !== $password2) {
        echo "<script>alert('Passwords do not match. Please try again.');</script>";
    } else {
        
        $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{6,}$/';

        
        if (!preg_match($passwordPattern, $password)) {
            echo "<script>alert('Password must contain at least 6 characters, including uppercase, lowercase, and a special character.');</script>";
        } else {
            
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $con = new mysqli('localhost', 'root', '', 'hostel-manage');
            if ($con->connect_error) {
                die("Connection failed: " . $con->connect_error);
            }

            
            $sql = "UPDATE users SET password='$hashedPassword' WHERE email='$email' AND keyToken='$token'";

            if ($con->query($sql) === TRUE) {
                
                echo "<script>alert('Passwords updated sucessfully ..!');</script>";
                header('Location: login.php');
                exit(); 
            } else {
                echo "<script>alert('Error updating password: " . $con->error . "');</script>";
            }

            $con->close();
        }
    }
}

if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];
} else {
    die('Invalid request.');
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
        <h3 class="display-4">Reset Password</h3>
        
        <form action="newpassword.php" method="post">
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
