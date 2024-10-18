<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

$msg = "";
if (isset($_POST['submit'])) {
    $con = new mysqli('localhost', 'root', '', 'practicle_9');
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    $email = $con->real_escape_string($_POST['email']);
    
    // Server-side email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "<div class='alert alert-dismissible alert-warning'>
            <button type='button' class='close' data-dismiss='alert'>&times;</button>
            Invalid email format.</div>";
    } else {
        $sql = $con->query("SELECT email FROM users WHERE email='$email'");
        if ($sql->num_rows == 0) {
            $msg = "<div class='alert alert-dismissible alert-warning'>
                <button type='button' class='close' data-dismiss='alert'>&times;</button>
                Email doesn't exist.</div>";
        } else {
            $sql1 = $con->query("SELECT firstName FROM users WHERE email='$email'");
            $data = $sql1->fetch_array();
            $name = $data['firstName'];
            
            // Token generation
            $passwordToken = hash('Sha512', 'dhumbar78barde.xembarab./a.out_o9oom88/avtya344_tyav');
            $passwordToken = str_shuffle($passwordToken);
            $token = substr($passwordToken, 12, 37); 
            
            // Update passwordToken
            $con->query("UPDATE users SET keyToken='$token' WHERE email='$email'");
            
            // Send RESET link via mail
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'agb709443@gmail.com'; 
            $mail->Password = 'nlzudyrsmcogpbwl'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
            $mail->Port = 587;

            $mail->setFrom('agb709443@gmail.com', 'Abhi');
            $mail->addAddress($email, $name);
            $mail->Subject = "Reset your password!";
            $mail->isHTML(true);
            $mail->Body = "Hello $name, <br>
            Please click on the link below to reset your password.<br><br>
            <a href='http://localhost/practicle_91/newpassword.php?email=$email&token=$token'>Reset Password</a>"; 
            
            if ($mail->send()) {
                $msg = "<div class='alert alert-dismissible alert-success'>
                        <button type='button' class='close' data-dismiss='alert'>&times;</button>
                        <strong>Check your email!</strong> We have sent you a reset link.
                        </div>";
            } else {
                $msg = "<div class='alert alert-dismissible alert-danger'>
                        <button type='button' class='close' data-dismiss='alert'>&times;</button>
                        <strong>Something went wrong:</strong> " . $mail->ErrorInfo . "
                        </div>";
            }
        }
    }

    $con->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password</title>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
</head>
<body>
    <div class="container text-center" style="padding-top:4%">
        <h2 class="display-4">Reset Your Password !!</h2> <br>
        <?php 
            if ($msg != "") {
                echo $msg . "<br>";
            }
        ?>
        <form action="forgotpassword.php" method="post">
            <input type="email" name="email" id="email" class="form-control" 
                   placeholder="Enter registered email" 
                   pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" 
                   title="Please enter a valid email address (e.g. user@example.com)" 
                   required/> <br>
            <input type="submit" name='submit' class="btn btn-primary" value="Send reset link"/>
        </form>
    </div>
</body>
</html>
