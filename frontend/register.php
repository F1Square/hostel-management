<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

$msg = "";

if (isset($_POST['submit'])) {
    // Database connection
    $con = mysqli_connect('localhost', 'root', '', 'hostel-manage');

    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Sanitize and validate inputs
    $name = trim($con->real_escape_string($_POST['name']));
    $email = trim($con->real_escape_string($_POST['email']));
    $password1 = $con->real_escape_string($_POST['password']);
    $password2 = $con->real_escape_string($_POST['cPassword']);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "<div class='alert alert-dismissible alert-warning'>
                <button type='button' class='close' data-dismiss='alert'>&times;</button>
                Invalid email format.
                </div>";
    }

    // Validate name (letters and spaces only)
    if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        $msg = "<div class='alert alert-dismissible alert-warning'>
                <button type='button' class='close' data-dismiss='alert'>&times;</button>
                Name can only contain letters and spaces.
                </div>";
    }

    // Password validation: At least one uppercase, lowercase, number, and special character, 6+ chars
    $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/';

    if ($password1 != $password2 || !preg_match($passwordPattern, $password1)) {
        $msg = "<div class='alert alert-dismissible alert-warning'>
                <button type='button' class='close' data-dismiss='alert'>&times;</button>
                Passwords must match and meet the required criteria.
                </div>";
    } else {
        // Check if email exists using prepared statements
        $stmt = $con->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $msg = "<div class='alert alert-dismissible alert-warning'>
                    <button type='button' class='close' data-dismiss='alert'>&times;</button>
                    Email already exists in the database.
                    </div>";
        } else {
            // Generate token and hash password
            $token = bin2hex(random_bytes(5)); // Generates a random token
            $hashedPassword = password_hash($password1, PASSWORD_BCRYPT);

            $result = $con->query("SELECT otr_number FROM users ORDER BY id DESC LIMIT 1");
            $lastOTR = $result->fetch_assoc();

            if ($lastOTR) {
                $lastOTRNumber = (int)substr($lastOTR['otr_number'], 2); // Get the last 4 digits
                $newOTRNumber = $lastOTRNumber + 1; // Increment by 2
            } else {
                $newOTRNumber = 1; // If no users, start with 0001
            }

            $OTRNumber = '24' . str_pad($newOTRNumber, 4, '0', STR_PAD_LEFT);

            // Insert new user using prepared statement
            $stmt = $con->prepare("INSERT INTO users (firstName, email, password, otr_number, isEmailConfirmed, token, keyToken) VALUES (?, ?, ?, ?, 0, ?, ?)");
            $stmt->bind_param("ssssss", $name, $email, $hashedPassword, $OTRNumber, $token, $token);

            if ($stmt->execute()) {
                // Send verification email
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'agb709443@gmail.com'; // Replace with your email
                $mail->Password = 'nlzudyrsmcogpbwl'; // Replace with your app-specific password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('agb709443@gmail.com', 'Abhi');
                $mail->addAddress($email, $name);
                $mail->Subject = "Please verify your email!";
                $mail->isHTML(true);
                $mail->Body = "Hello $name, <br>
                Please click on the link below to confirm your email.<br><br>
                <a href='http://localhost/hostel-manage/frontend/confirm.php?email=$email&token=$token'>Confirm email</a>";

                if ($mail->send()) {
                    $msg = "<div class='alert alert-dismissible alert-success'>
                            <button type='button' class='close' data-dismiss='alert'>&times;</button>
                            <strong>Registration complete!</strong> Please check your email for confirmation.
                            </div>";
                } else {
                    $msg = "<div class='alert alert-dismissible alert-danger'>
                            <button type='button' class='close' data-dismiss='alert'>&times;</button>
                            <strong>Email sending failed:</strong> " . $mail->ErrorInfo . "
                            </div>";
                }
            } else {
                $msg = "<div class='alert alert-dismissible alert-danger'>
                        <button type='button' class='close' data-dismiss='alert'>&times;</button>
                        <strong>Database error:</strong> " . $stmt->error . "
                        </div>";
            }
        }

        $stmt->close();
    }
    $con->close();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">

    <style>
        .password-criteria {
            font-size: 0.9rem;
            color: gray;
            text-align: left; /* Ensures left alignment */
            margin-top: -10px; /* Adjust the space above to keep it closer to the password field */
        }
        .password-criteria span {
            display: block;
        }
        .invalid {
            color: red;
        }
        .valid {
            color: green;
        }
    </style>
    <script>
        // Password validation script
        function validatePassword() {
            let password = document.getElementById("password").value;
            let criteria = document.getElementById("password-criteria");

            let upperCase = /[A-Z]/.test(password);
            let lowerCase = /[a-z]/.test(password);
            let number = /[0-9]/.test(password);
            let specialChar = /[\W_]/.test(password);
            let minLength = password.length >= 6;

            document.getElementById("criteria-uppercase").className = upperCase ? "valid" : "invalid";
            document.getElementById("criteria-lowercase").className = lowerCase ? "valid" : "invalid";
            document.getElementById("criteria-number").className = number ? "valid" : "invalid";
            document.getElementById("criteria-specialchar").className = specialChar ? "valid" : "invalid";
            document.getElementById("criteria-length").className = minLength ? "valid" : "invalid";
        }
    </script>
</head>
<body>
    <div class="container" style="margin-top: 100px;">
        <div class="row justify-content-center">
            <div class="col-md-6 col-md-offset-3" align="center">
                <h1 class="display-3">Register</h1> <br>
                <?php if ($msg != "") echo $msg; ?>
                <br>
                <form method="post" action="register.php">
                    <input class="form-control" name="name" placeholder="Name..." required><br>
                    <input class="form-control" name="email"  placeholder="Email..." required><br>

                    <!-- Password field -->
                    <input class="form-control" id="password" name="password" type="password" placeholder="Password..." required oninput="validatePassword()"><br>

                    <!-- Password criteria section below the password input -->
             

                   <input class="form-control" name="cPassword" type="password" placeholder="Confirm Password..." required><br>
                    <input class="btn btn-primary" type="submit" name="submit" value="Register">
                </form> <br>
                <p class="lead">Already a user? <a href="login.php">Log in</a>.</p>
            </div>
        </div>
    </div>
</body>
</html>
