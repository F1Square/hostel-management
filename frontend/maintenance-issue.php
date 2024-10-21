<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Issue</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Textarea styling */
        
        textarea {
            resize: none; /* Disable resizing */
            padding: 10px; /* Inner padding */
            border: 2px solid #3498db; /* Thicker border */
            border-radius: 4px; /* Rounded corners */
            font-size: 16px; /* Font size */
            margin-bottom: 20px; /* Space below the textarea */
            transition: border-color 0.3s; /* Smooth transition for border color */
            width: 100%; /* Full width */
            box-sizing: border-box; /* Include padding and border in width */
        }

        /* Textarea focus effect */
        textarea:focus {
            border-color: #2980b9; /* Darker border on focus */
            outline: none; /* Remove default outline */
        }

        /* Submit button styling */
        input[type="submit"] {
            background-color: #3498db; /* Button color */
            color: white; /* Text color */
            border: none; /* Remove border */
            padding: 10px 15px; /* Padding */
            border-radius: 4px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor */
            font-size: 16px; /* Font size */
            transition: background-color 0.3s; /* Smooth transition for background color */
            width: 100%; /* Full width */
            box-sizing: border-box; /* Include padding in width */
        }

        /* Button hover effect */
        input[type="submit"]:hover {
            background-color: #2980b9; /* Darker shade on hover */
        }

        /* Button active effect */
        input[type="submit"]:active {
            background-color: #1a6d99; /* Even darker shade on active */
        }

        /* Top bar styling */
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
    <?php 
    session_start(); // Start the session
    include 'sidebar.php'; 
    ?>
    <div class="content">
        <div class="top-bar">
            <h1>SDHOSTEL</h1>
            <div class="user">
                <!-- Profile image that triggers the dropdown -->
                <img src="photos/Gpay.png" alt="Profile" onclick="toggleDropdown()">
                <!-- Dropdown menu for logout -->
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
