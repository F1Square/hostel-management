<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SDHostel</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        p{
            margin:20px;
        }
    </style>
    <script>
        function toggleDropdown() {
            document.getElementById("logoutDropdown").classList.toggle("show");
        }

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
        </ul>
    </div>

    <div class="content">
        <div class="top-bar">
            <h1><a href="dashboard.php">SDHOSTEL</a></h1>
            <div class="user">
                <img src="photos/Gpay.png" alt="User Image" onclick="toggleDropdown()">
                <div id="logoutDropdown" class="dropdown">
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>

        <div class="main-content">
            <h2>User Profile</h2>
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

            // Fetch user data from the users table
            if (isset($_SESSION['otr_number'])) {
                $otr_number = $_SESSION['otr_number'];
                $sql = "SELECT * FROM users WHERE otr_number = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $otr_number);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    ?>
                    <div class="profile-info">
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['firstName']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
                        <p><strong>OTR Number:</strong> <?php echo htmlspecialchars($user['otr_number']); ?></p>
                    </div>
                    <?php
                } else {
                    echo "<p>No user data found.</p>";
                }
                $stmt->close();
            } else {
                echo "<p>User is not logged in.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
