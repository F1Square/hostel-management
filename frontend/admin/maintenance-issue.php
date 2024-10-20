<?php
session_start(); // Start the session

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hostel-manage";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch maintenance issues from the database
$sql = "SELECT id, otr_number, issue, submitted_at FROM maintenance_issues ORDER BY submitted_at DESC"; // You can adjust the order as needed
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Maintenance Issues</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <style>
        /* Basic table styling */
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
            /* Keep the sidebar fixed on the left */
            height: 100%;
        }

        .sidebar .profile {
            text-align: center;
        }

        .sidebar .profile img {
            width: 50px;
            border-radius: 50%;
        }

        .username {
            margin-top: 10px;
            font-size: 18px;
        }

        .menu {
            list-style-type: none;
            margin-top: 20px;
        }

        .menu li {
            padding: 10px 0;
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .menu li:hover {
            background-color: #444;
        }

        .menu li a {
            text-decoration: none;
            /* Remove underline from links */
            color: white;
            /* Set button (link) color to white */
            width: 100%;
            display: block;
            padding-left: 10px;
        }

        .menu li a:hover {
            color: #ddd;
            /* Optional hover color */
        }

        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            margin-left: 250px;
            /* Adjust for the sidebar width */
        }

        .top-bar {
            background-color: #2c91c1;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        .top-bar h1 {
            margin-left: 20px;
        }

        .user {
            display: flex;
            align-items: center;
        }

        .user img {
            width: 30px;
            height: 30px;
            margin-left: 10px;
            border-radius: 50%;
            text-decoration: none;
            /* Remove underline from image inside links */
        }

        .main-content {
            padding: 20px;
        }

        .top-bar h1 a {
            color: white;
            /* Set the link color to white */
            text-decoration: none;
            /* Remove underline from the link */
        }

        .top-bar h1 a:hover {
            color: #ddd;
            /* Optional: Change color on hover */
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

        @media (max-width: 600px) {
            .menu li {
                text-align: center;
            }

            .menu li a {
                padding-left: 0;
            }
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?> <!-- Include your sidebar -->
    <div class="content">
        <?php include 'topbar.php'; ?> <!-- Include your top bar -->
        <div class="main-content">
            <h2>Maintenance Issues</h2>
            <table>
                <thead>
                    <tr>
                    
                        <th>OTR Number</th>
                        <th>Issue</th>
                        <th>Submitted At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                
                                <td><?php echo htmlspecialchars($row['otr_number']); ?></td>
                                <td><?php echo htmlspecialchars($row['issue']); ?></td>
                                <td><?php echo htmlspecialchars($row['submitted_at']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No maintenance issues submitted.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
