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
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <?php include 'topbar.php'; ?>
        <div class="main-content">
            <h2>Maintenance Issue</h2>
           
            <form action="submit_issue.php" method="post"> <!-- Change action to your submission script -->
                <label for="issue">Describe the maintenance issue:</label>
                <textarea id="issue" name="issue" rows="10" required></textarea>
                <input type="submit" value="Submit Issue">
            </form>
        </div>
    </div>
</body>
</html>
