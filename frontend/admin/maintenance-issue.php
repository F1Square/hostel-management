<?php
session_start(); // Start the session

if (!isset($_SESSION['role'])) {
    // User is not an admin, show alert and redirect to a different page (like homepage)
    echo "<script>
        alert('You need to login first. Access denied.');
        window.location.href = '../login.php'; // Redirect to the homepage or any other page
    </script>";
    exit(); // Stop further execution
}

if ($_SESSION['role'] !== 'admin') {
    // User is not an admin, show alert and redirect to a different page (like homepage)
    echo "<script>
        alert('You are not an admin. Access denied.');
        window.location.href = '../login.php'; // Redirect to the homepage or any other page
    </script>";
    exit(); // Stop further execution
}

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

        .dropdown {
            display: none;
            /* Initially hidden */
            position: absolute;
            right: 0;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 90px;
            z-index: 1000;
            /* Ensure it appears above other elements */
        }

        .dropdown a {
            color: #333;
            padding: 10px 15px;
            text-decoration: none;
            display: block;
        }

        .dropdown a:hover {
            background-color: #f4f4f4;
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
    <script>
        // Toggle dropdown visibility
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdown-menu');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }

        // Close dropdown if clicked outside
        window.onclick = function (event) {
            if (!event.target.matches('.profile-pic')) {
                const dropdowns = document.getElementsByClassName("dropdown");
                for (let i = 0; i < dropdowns.length; i++) {
                    dropdowns[i].style.display = "none";
                }
            }
        };
    </script>
</head>
<body>
<div class="sidebar">
        <ul class="menu">
            <li><a href="AdminHostelFees.php">Hostel Fees</a></li>
            <li><a href="maintenance-issue.php">Maintenance Issue</a></li>
            <li><a href="gate-pass.php">Gate Pass & Leave</a></li>
          
        </ul>
    </div>
     <!-- Include your sidebar -->
    <div class="content">
    <div class="top-bar">
        <h1><a href="dashboard.php" style="color: white; text-decoration: none;">SDHOSTEL</a></h1>

            <div class="user">
               
                <img src="../photos/Gpay.png" alt="Profile Picture" class="profile-pic" onclick="toggleDropdown()">
                <div id="dropdown-menu" class="dropdown">
                    <a href="../logout.php">Logout</a>
                </div>
            </div>
        </div>
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
