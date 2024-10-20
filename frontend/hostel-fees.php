<?php
session_start(); // Ensure this is the first line

if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo '<script>alert("Receipt uploaded successfully!");</script>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Fees</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Your existing styles here */
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        h2, h3 {
            color: #333;
        }

        /* Modal Styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0, 0, 0, 0.5); /* Black w/ opacity */
            justify-content: center; /* Center modal */
            align-items: center; /* Center modal */
        }

        .modal-content {
            background-color: #fefefe; /* White background */
            padding: 20px;
            border-radius: 10px;
            position: relative;
            text-align: center;
            width: 400px;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 20px;
            cursor: pointer;
        }

        /* Image styles */
        img {
            margin-top: 10px;
            border-radius: 10px;
            width: 100%; /* Full width */
            height: auto; /* Maintain aspect ratio */
        }

        /* Button styles */
        button {
            background-color: #2c91c1; /* Default background color */
            color: white; /* White text color */
            border: none; /* Remove border */
            padding: 10px 20px; /* Add padding */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor on hover */
            font-size: 16px; /* Font size */
            transition: background-color 0.3s; /* Smooth transition for hover effect */
        }

        button:hover {
            background-color: #1f6f95; /* Darker blue on hover */
        }

        /* Receipt List Styles */
        .receipts-list {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        /* Sidebar and Topbar Styles */
        .sidebar {
            width: 250px;
            background-color: #333;
            color: white;
            padding: 20px;
            position: fixed;
            height: 100%;
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
            width: 40px; /* Size of the dummy image */
            height: 40px; /* Size of the dummy image */
            border-radius: 50%; /* Make it round */
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

        .show {
            display: block;
        }

        .main-content {
            padding: 20px;
        }

        /* Modal Styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0, 0, 0, 0.5); /* Black w/ opacity */
            justify-content: center; /* Center modal */
            align-items: center; /* Center modal */
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <?php include 'topbar.php'; ?>

        <div class="main-content">
            <h2>Hostel Fees</h2>
            <button id="openModal">View Bank Details</button>

            <h3>Your Approved Receipts</h3>
            <!-- Fetch and display approved receipts here -->
        </div>

        <!-- Modal for showing image and upload form -->
        <div id="modal" class="modal">
            <div class="modal-content">
                <span class="close-btn" id="closeModal">&times;</span>
                <h3>Bank Details</h3>
                <img src="photos/Gpay.png" alt="Hostel Fees" style="width: 100%; height: auto;">

                <div class="upload-section" id="uploadSection">
                    <p>Upload your transaction photos:</p>
                    <form id="receiptUploadForm" action="upload_receipt.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="otr_number" value="<?php echo htmlspecialchars($otr_number_value); ?>">
                        <input type="file" id="fileInput" name="receiptFile" required>
                        <button type="submit" id="uploadBtn">Upload Receipt</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
        };

        var modal = document.getElementById('modal');
        var openModalBtn = document.getElementById('openModal');
        var closeModalBtn = document.getElementById('closeModal');

        // Open the modal when the button is clicked
        openModalBtn.addEventListener('click', function () {
            modal.style.display = 'flex';
        });

        // Close the modal when the close button is clicked
        closeModalBtn.addEventListener('click', function () {
            modal.style.display = 'none';
        });

        // Close the modal if clicked outside of the modal content
        window.onclick = function (event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>
