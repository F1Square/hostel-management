<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="styles.css">
    <style>
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

        .dropdown {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            z-index: 1;
        }

        .dropdown a {
            display: block;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
        }

        .dropdown a:hover {
            background-color: #f1f1f1;
        }

        .form-group {
            margin: 20px 0;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        .form-group button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <ul class="menu">
            <li><a href="hostel-fees.php">Hostel Fees</a></li>
            <li><a href="maintenance-issue.php">Maintenance Issue</a></li>
            <li><a href="gate-pass.php">Gate Pass & Leave</a></li>
            <li><a href="gate-pass-status.php">Status</a></li>
            <li><a href="change-password.php">Change Password</a></li>
            <li><a href="update-profile.php">Update Profile</a></li>
        </ul>
    </div>

    <div class="content">
        <div class="top-bar">
            <h1><a href="dashboard.php">SDHOSTEL</a></h1>
            <div class="user">
                <img src="photos/user.webp" alt="User Image" onclick="toggleDropdown()">
                <div id="logoutDropdown" class="dropdown">
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>

        <div class="main-content">
            <h2>Update Profile</h2>

            <?php
            session_start();

            if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
                echo "<script>
                    alert('You need to login as a student first.');
                    window.location.href = 'login.php';
                </script>";
                exit();
            }

            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "hostel-manage";
            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            if (isset($_POST['update'])) {
                $phone = $_POST['phone'];
                $address = $_POST['address'];
                $city = $_POST['city'];
                $pincode = $_POST['pincode'];
                $otr_number = $_SESSION['otr_number'];

                $sql = "UPDATE users SET phone = ?, address = ?, city = ?, pincode = ? WHERE otr_number = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssi", $phone, $address, $city, $pincode, $otr_number);

                if ($stmt->execute()) {
                    echo "<script>alert('Profile updated successfully!'); document.getElementById('profileForm').reset();</script>";
                } else {
                    echo "<script>alert('Error updating profile: " . $conn->error . "');</script>";
                }

                $stmt->close();
            }

            if (isset($_SESSION['otr_number'])) {
                $otr_number = $_SESSION['otr_number'];
                $sql = "SELECT phone, address, city, pincode FROM users WHERE otr_number = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $otr_number);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                } else {
                    echo "<p>No user data found.</p>";
                }

                $stmt->close();
            } else {
                echo "<p>User is not logged in.</p>";
            }

            $conn->close();
            ?>

            <form id="profileForm" action="update-profile.php" method="post" onsubmit="return validateForm()">
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" value="" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" value="" required>
                </div>
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" value="" required>
                </div>
                <div class="form-group">
                    <label for="pincode">Pin Code</label>
                    <input type="text" id="pincode" name="pincode" value="" required>
                </div>
                <div class="form-group">
                    <button type="submit" name="update">Update Profile</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function validateForm() {
            
            var phone = document.getElementById("phone").value;
            var city = document.getElementById("city").value;
            var pincode = document.getElementById("pincode").value;

           
            var phonePattern = /^[6-9]\d{9}$/;
            if (!phonePattern.test(phone)) {
                alert("Please enter a valid phone number.");
                return false;
            }

            var cityPattern = /^[a-zA-Z\s]+$/;
            if (!cityPattern.test(city)) {
                alert("Please enter a valid city. Only alphabetic characters are allowed.");
                return false;
            }

            var pincodePattern = /^\d{6}$/;
            if (!pincodePattern.test(pincode)) {
                alert("Please enter a valid pincode. It must be exactly 6 digits.");
                return false;
            }

            return true;
        }

        function toggleDropdown() {
            var dropdown = document.getElementById("logoutDropdown");
            dropdown.classList.toggle("show");
        }

        window.onclick = function (event) {
            if (!event.target.matches('.user img')) {
                var dropdowns = document.getElementsByClassName("dropdown");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        };
    </script>
</body>

</html>