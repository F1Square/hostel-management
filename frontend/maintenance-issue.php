<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Issue</title>
    <link rel="stylesheet" href="styles.css">
    <style>

        
        textarea {
            resize: none; 
            padding: 10px; 
            border: 2px solid #3498db; 
            border-radius: 4px; 
            font-size: 16px; 
            margin-bottom: 20px; 
            transition: border-color 0.3s;
            width: 100%; 
            box-sizing: border-box; 
        }

        textarea:focus {
            border-color: #2980b9; 
            outline: none; 
        }

        input[type="submit"] {
            background-color: #3498db; 
            color: white; 
            border: none; 
            padding: 10px 15px; 
            border-radius: 4px; 
            cursor: pointer; 
            font-size: 16px; 
            transition: background-color 0.3s; 
            width: 100%; 
            box-sizing: border-box; 
        }

        input[type="submit"]:hover {
            background-color: #2980b9; 
        }

        input[type="submit"]:active {
            background-color: #1a6d99; 
        }

        .top-bar {
            background-color: #2c91c1;
            color: white;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-bar .user {
            position: relative;
            display: inline-block;
        }

        .top-bar .user img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
        }

        .top-bar .user .dropdown {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            z-index: 1;
        }

        .top-bar .user .dropdown a {
            display: block;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
        }

        .top-bar .user .dropdown a:hover {
            background-color: #f1f1f1;
        }

        .top-bar .user .show {
            display: block;
        }
    </style>
    <script>
        function toggleDropdown() {
            document.getElementById("dropdown").classList.toggle("show");
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
    <?php 
    session_start(); 
    if (!isset($_SESSION['role'])) {
        
        echo "<script>
            alert('You need to login first. Access denied.');
            window.location.href = 'login.php'; // Redirect to the homepage or any other page
        </script>";
        exit(); 
    }
    
    if ($_SESSION['role'] !== 'student') {
        
        echo "<script>
            alert('You are not an student. Access denied.');
            window.location.href = 'login.php'; // Redirect to the homepage or any other page
        </script>";
        exit(); 
    }
    include 'sidebar.php'; 
    ?>
    <div class="content">
        <div class="top-bar">
            <h1>SDHOSTEL</h1>
            <div class="user">
                
                <img src="photos/user.webp" alt="Profile" onclick="toggleDropdown()">
                
                <div id="dropdown" class="dropdown">
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>
        <div class="main-content">
            <h2>Describe the maintenance issue:</h2>
            <form action="submit_issue.php" method="post">
                <textarea id="issue" name="issue" rows="10" required></textarea>
                <input type="submit" value="Submit Issue">
            </form>
        </div>
    </div>
</body>
</html>
