<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SDHostel - Gate Pass & Leave Request</title>
    <link rel="stylesheet" href="styles.css">
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

        .main-content {
            padding: 20px;
        }

        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }

        .form-container h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #2c91c1;
        }

        .form-group button {
            padding: 10px 20px;
            background-color: #2c91c1;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #2379a1;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <ul class="menu">
            <li><a href="hostel-fees.php">Hostel Fees</a></li>
            <li><a href="maintenance-issue.php">Maintenance Issue</a></li>
            <li><a href="gate-pass.php">Gate Pass & Leave</a></li>
            <li><a href="reporting-history.php">Reporting History</a></li>
            <li><a href="change-password.php">Change Password</a></li>
        </ul>
    </div>

    <div class="content">
        <div class="top-bar">
            <h1><a href="dashboard.php">SDHOSTEL</a></h1>
            <div class="user">
                <span>Student</span>
            </div>
        </div>

        <div class="main-content">
            <div class="form-container">
                <h2>Gate Pass & Leave Request</h2>
                <form action="gate-pass.php" method="POST">
                    <div class="form-group">
                        <label for="type">Type:</label>
                        <select id="type" name="type" required>
                            <option value="">Select Type</option>
                            <option value="Gate">Gate Pass</option>
                            <option value="Leave">Leave</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="reason">Reason:</label>
                        <select id="reason" name="reason" required>
                            <option value="">Select Reason</option>
                            <option value="Medical">Medical</option>
                            <option value="Relative Meeting">Relative Meeting</option>
                            <option value="College">College</option>
                            <option value="Home">Home</option>
                            <option value="Salon">Salon</option>
                            <option value="Shopping">Shopping</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="out_time">Approximate Out Time:</label>
                        <input type="time" id="out_time" name="out_time" required>
                    </div>

                    <div class="form-group">
                        <label for="in_time">Approximate In Time:</label>
                        <input type="time" id="in_time" name="in_time" required>
                    </div>

                    <div class="form-group">
                        <label for="date_range">Date from:</label>
                        <input type="date" id="date_range" name="date_range" required>
                    </div>

                    <div class="form-group">
                        <label for="date_range">Date To:</label>
                        <input type="date" id="date_range" name="date_range" required>
                    </div>

                    <div class="form-group">
                        <button type="submit">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
