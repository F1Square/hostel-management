<?php
session_start(); 
if (!isset($_SESSION['role'])) {
    
    echo "<script>
        alert('You need to login first. Access denied.');
        window.location.href = '../login.php'; // Redirect to the homepage or any other page
    </script>";
    exit(); 
}

if ($_SESSION['role'] !== 'admin') {
    
    echo "<script>
        alert('You are not an admin. Access denied.');
        window.location.href = '../login.php'; // Redirect to the homepage or any other page
    </script>";
    exit(); 
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SDHostel</title>

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
            color: white;
            width: 100%;
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

        .top-bar h1 {
            margin-left: 20px;
        }

        .user {
            display: flex;
            align-items: center;
            position: relative; 
        }

        .user img {
            width: 30px;
            height: 30px;
            margin-left: 10px;
            border-radius: 50%;
            cursor: pointer; 
        }

        .dropdown {
            display: none; 
            position: absolute;
            right: 0;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 90px;
            z-index: 1000; 
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
            text-decoration: none;
        }

        .top-bar h1 a:hover {
            color: #ddd;
        }

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
    </style>

    <script>
        
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdown-menu');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }

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

    <div class="content">
        <div class="top-bar">
            <h1><a href="dashboard.php">SDHOSTEL</a></h1>
            <div class="user">
                
                <img src="../photos/user.webp" alt="Profile Picture" class="profile-pic" onclick="toggleDropdown()">
                <div id="dropdown-menu" class="dropdown">
                    <a href="../logout.php">Logout</a>
                </div>
            </div>
        </div>
        <div class="main-content">
        </div>
    </div>
</body>

</html>
