<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$conn = new mysqli('localhost', 'root', '', 'hostel-manage');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = $_SESSION['user_id']; // Assuming user ID is stored in session

$result = $conn->query("SELECT * FROM receipts WHERE user_id = $userId AND approved = 1");

if (!$result) {
    die("Query failed: " . $conn->error); // Added error handling for the query
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
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
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

        img {
            margin-top: 10px;
            border-radius: 10px;
            width: 100px; /* Adjust image width */
            height: auto; /* Maintain aspect ratio */
        }

        /* Button styles */
        #openModal {
            background-color: #2c91c1;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        #openModal:hover {
            background-color: #1f6f95;
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
            <?php if ($result->num_rows > 0): ?>
                <table border="1">
                    <tr>
                        <th>Receipt</th>
                        <th>Upload Date</th>
                    </tr>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><img src="<?php echo $row['file_path']; ?>" alt="Receipt"></td>
                        <td><?php echo $row['upload_date']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>No approved receipts available.</p>
            <?php endif; ?>
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
                        <input type="file" id="fileInput" name="receiptFile" required>
                        <button type="submit" id="uploadBtn">Upload Receipt</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Get elements
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
