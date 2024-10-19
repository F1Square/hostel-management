<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Fees</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Black background with transparency */
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

        #uploadStatus {
            margin-top: 10px;
            color: green;
        }

        .upload-section {
            margin-top: 20px;
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
        </div>

        <!-- Modal for showing image and upload form -->
        <div id="modal" class="modal">
            <div class="modal-content">
                <span class="close-btn" id="closeModal">&times;</span>
                <h3>Bank Details</h3>
                <img src="photos/Gpay.png" alt="Hostel Fees" style="width: 100%; height: auto;">
                <!-- Upload section moved outside the image -->
                <div class="upload-section">
                    <p>Upload your bank payment receipt:</p>
                    <!-- Updated form to handle file uploads -->
                    <form id="receiptUploadForm" action="upload_receipt.php" method="POST" enctype="multipart/form-data">
                        <input type="file" id="fileInput" name="receiptFile">
                        <button type="submit" id="uploadBtn">Upload Receipt</button>
                        <p id="uploadStatus"></p>
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
        var uploadStatus = document.getElementById('uploadStatus');
        var uploadForm = document.getElementById('receiptUploadForm');

        // Open the modal when the button is clicked
        openModalBtn.addEventListener('click', function() {
            modal.style.display = 'flex';
        });

        // Close the modal when the close button is clicked
        closeModalBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        // Close the modal if clicked outside of the modal content
        window.onclick = function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>
