<?php
// Start the session
session_start();
// if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== 1 || $_SESSION['role'] !== 'watchman') {
//     header('Location: login.php');
//     exit();
// }
date_default_timezone_set('Asia/Kolkata');
// Database connection
$con = new mysqli('localhost', 'root', '', 'hostel-manage');

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Initialize variables
$status_message = '';
$image = '';

// If watchman submits the form with the student's OTR number
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otr_number = $_POST['otr_number'];
    $isApproved = false;

    // Check if the student has an approved request
    $query = "SELECT status, date_from, out_time FROM gatepass WHERE otr_number = ? ORDER BY id DESC LIMIT 1";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $otr_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $request = $result->fetch_assoc();

    if ($request) {
        if ($request['status'] === 'Approved') {
            // Fetch current date and time
            $current_date = date('Y-m-d');
            $current_time = date('H:i:s');
            // echo $current_time;
            // Compare current date and time with the request details
            if ($current_date >= $request['date_from'] && $current_time >= $request['out_time']) {
                $status_message = 'Request approved. Student can go outside.';
                $image = 'right.png';  // Show checkmark image
                $isApproved = true;
            } else {
                $status_message = 'Request approved, but it’s not yet time to leave.';
                $image = 'cross.png';  // Show cross image if it's not yet time to go
            }
        } else {
            $status_message = 'Request not approved. Student cannot go outside.';
            $image = 'cross.png';  // Use the cross image for disapproval
        }
    } else {
        $status_message = 'No request found for this OTR number.';
        $image = 'cross.png';  // Show cross image if no request exists
    }

    $stmt->close();
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Security Check</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f4f4f4;
        }

        .container {
            text-align: center;
        }

        .status-message {
            font-size: 24px;
            margin-top: 20px;
        }

        .image {
            margin-top: 20px;
        }

        img {
            width: 150px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Security Check</h1>
        <form method="POST">
            <input type="text" name="otr_number" placeholder="Enter OTR Number" required>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <?php if (!empty($status_message)) : ?>
            <div class="status-message">
                <?php echo $status_message; ?>
            </div>
            <div class="image">
                <img src="<?php echo $image; ?>" alt="Status Image">
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
