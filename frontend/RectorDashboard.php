<?php
// Database connection
$servername = "localhost";  // Replace with your server details
$username = "root";         // Replace with your database username
$password = "";             // Replace with your database password
$dbname = "hostel-manage";  // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle approval/rejection
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $id = $_POST['id']; // Hidden input field to get the row's id
    $action = $_POST['action'];

    if ($action == 'approve') {
        $status = 'Approved';
    } elseif ($action == 'reject') {
        $status = 'Rejected';
    }

    // Update the status in the database
    $sql_update = "UPDATE manages SET status='$status' WHERE id=$id";
    if ($conn->query($sql_update) === TRUE) {
        echo "<div class='alert alert-success'>Record updated successfully</div>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Fetch data from database
$sql = "SELECT * FROM manages WHERE status='Pending'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Manage Records</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" crossorigin="anonymous">
    <style>
        body {
            margin: 20px;
        }
        table {
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="my-4">Manage Records</h2>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Reason</th>
                    <th>Out Date</th>
                    <th>Return Date</th>
                    <th>Out Time</th>
                    <th>In Time</th>
                    
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row["type"] . "</td>
                                <td>" . $row["reason"] . "</td>
                                <td>" . $row["outdate"] . "</td>
                                <td>" . $row["returndate"] . "</td>
                                <td>" . $row["outtime"] . "</td>
                                <td>" . $row["intime"] . "</td>
                                
                                <td>
                                    <form method='POST' action=''>
                                        <input type='hidden' name='id' value='" . $row["id"] . "'>
                                        <button type='submit' name='action' value='approve' class='btn btn-success btn-sm'>Approve</button>
                                        <button type='submit' name='action' value='reject' class='btn btn-danger btn-sm'>Reject</button>
                                    </form>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php $conn->close(); ?>
</body>
</html>
