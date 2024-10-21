<?php
// Start the session to check if the user is logged in
session_start();
if (!isset($_SESSION['role'])) {
    // User is not an admin, show alert and redirect to a different page (like homepage)
    echo "<script>
        alert('You need to login first. Access denied.');
        window.location.href = 'login.php'; // Redirect to the homepage or any other page
    </script>";
    exit(); // Stop further execution
}

if ($_SESSION['role'] !== 'student') {
    // User is not an admin, show alert and redirect to a different page (like homepage)
    echo "<script>
        alert('You are not an student. Access denied.');
        window.location.href = 'login.php'; // Redirect to the homepage or any other page
    </script>";
    exit(); // Stop further execution
}

// Database connection
$con = new mysqli('localhost', 'root', '', 'hostel-manage');

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Fetch gate pass and leave requests for the logged-in user
$otr_number = $_SESSION['otr_number'];
$query = "SELECT * FROM gatepass WHERE otr_number = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $otr_number); // Assuming otr_number is a string
$stmt->execute();
$result = $stmt->get_result();

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row; // Collect data
    }
} else {
    // No records found
    $data = [];
}

$stmt->close(); // Close the prepared statement
$con->close(); // Close the connection
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Gate Pass Status</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
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

        .user .dropdown {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            z-index: 1;
        }

        .user .dropdown a {
            display: block;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
        }

        .user .dropdown a:hover {
            background-color: #f1f1f1;
        }

        .user .show {
            display: block;
        }

        .main-content {
            padding: 20px;
        }

        .table {
            width: 100%;
            margin-top: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .content {
                margin-left: 0;
            }
        }
    </style>
    <script>
        function toggleDropdown() {
            document.getElementById("dropdown").classList.toggle("show");
        }

        // Close the dropdown if the user clicks outside of it
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
            <li><a href="update-profile.php">Update profile</a></li>
        </ul>
    </div>

    <div class="content">
        <div class="top-bar">
            <h1><a href="dashboard.php">SDHOSTEL</a></h1>
            <div class="user">
                <!-- Profile image -->
                <img src="photos/Gpay.png" alt="Profile" onclick="toggleDropdown()">
                <!-- Dropdown menu -->
                <div id="dropdown" class="dropdown">
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>

        <div class="main-content">
            <h2 class="my-4">Gate Pass & Leave Requests Status</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Reason</th>
                        <th>Out Date</th>
                        <th>Return Date</th>
                        <th>Out Time</th>
                        <th>In Time</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data)) : ?>
                        <?php foreach ($data as $row) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['type']); ?></td>
                                <td><?php echo htmlspecialchars($row['reason']); ?></td>
                                <td><?php echo htmlspecialchars($row['date_from']); ?></td>
                                <td><?php echo htmlspecialchars($row['date_to']); ?></td>
                                <td><?php echo htmlspecialchars($row['out_time']); ?></td>
                                <td><?php echo htmlspecialchars($row['in_time']); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8" class="text-center">No records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
