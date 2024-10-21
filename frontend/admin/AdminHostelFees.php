<?php
require('../fpdf186/fpdf.php'); 

if (!isset($_SESSION['role'])) {
    // User is not an admin, show alert and redirect to a different page (like homepage)
    echo "<script>
        alert('You need to login first. Access denied.');
        window.location.href = '../index.php'; // Redirect to the homepage or any other page
    </script>";
    exit(); // Stop further execution
}

if ($_SESSION['user_role'] !== 'admin') {
    // User is not an admin, show alert and redirect to a different page (like homepage)
    echo "<script>
        alert('You are not an admin. Access denied.');
        window.location.href = '../index.php'; // Redirect to the homepage or any other page
    </script>";
    exit(); // Stop further execution
}
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
function generatePDFReceipt($receiptData) {
    // Ensure the receipts folder exists
    if (!file_exists('../receipts/')) {
        mkdir('../receipts/', 0777, true);
    }
    
    // require('../fpdf186/fpdf.php');  // Ensure the correct case
    
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    // Generate receipt content
    $pdf->Cell(40, 10, 'Receipt');
    $pdf->Ln();
    $pdf->Cell(40, 10, 'OTR Number: ' . $receiptData['otr_number']);
    $pdf->Ln();
    $pdf->Cell(40, 10, 'Amount Paid: ' . 1500);
    $pdf->Ln();
    // $pdf->Cell(40, 10, 'Payment Date: ' . $receiptData['payment_date']);
    // $pdf->Ln();

    // Save the PDF to a folder
    $pdf->Output('F', '../receipts/' . $receiptData['otr_number'] . '.pdf');

    // Update database with the path to the generated PDF receipt
    global $conn;
    $stmt = $conn->prepare("UPDATE receipts SET receipt_path = ? WHERE id = ?");
    $receipt_path = '../receipts/' . $receiptData['otr_number'] . '.pdf';
    $stmt->bind_param("si", $receipt_path, $receiptData['id']);
    $stmt->execute();
    $stmt->close();
}

// Handle approval or rejection
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $receipt_id = $_POST['receipt_id'];
    $action = $_POST['action'];

    if ($action == 'approve') {
        $stmt = $conn->prepare("UPDATE receipts SET status = 'approved' WHERE id = ?");
        $receipt_query = $conn->prepare("SELECT * FROM receipts WHERE id = ?");
        $receipt_query->bind_param("i", $receipt_id);
        $receipt_query->execute();
        $receipt_result = $receipt_query->get_result();
        $receipt_data = $receipt_result->fetch_assoc();
        generatePDFReceipt($receipt_data); // This function will create the PDF
        $receipt_query->close();
    } else {
        $stmt = $conn->prepare("UPDATE receipts SET status = 'rejected' WHERE id = ?");
    }

    $stmt->bind_param("i", $receipt_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch receipts
$sql = "SELECT r.id, r.otr_number, r.file_path, r.status FROM receipts r";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Receipts</title>

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
            /* For dropdown positioning */
        }

        .user img {
            width: 30px;
            height: 30px;
            margin-left: 10px;
            border-radius: 50%;
            cursor: pointer;
            /* Change cursor to pointer for the profile picture */
        }

        .dropdown {
            display: none;
            /* Initially hidden */
            position: absolute;
            right: 0;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 90px;
            z-index: 1000;
            /* Ensure it appears above other elements */
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
            flex: 1;
            margin-top: 20px;
            /* Adjusted margin for top spacing */
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        /* Responsive Design */
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
        // Toggle dropdown visibility
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdown-menu');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }

        // Close dropdown if clicked outside
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
        <h1><a href="dashboard.php" style="color: white; text-decoration: none;">SDHOSTEL</a></h1>

            <div class="user">
               
                <img src="../photos/Gpay.png" alt="Profile Picture" class="profile-pic" onclick="toggleDropdown()">
                <div id="dropdown-menu" class="dropdown">
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>
        <div class="main-content">
            <h2>Manage Receipts</h2>
            <table>
                <thead>
                    <tr>
                        <th>OTR Number</th>
                        <th>File Path</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                            
                                <td><?php echo htmlspecialchars($row['otr_number']); ?></td>
                                <td>
                                    <a href="../<?php echo htmlspecialchars($row['file_path']); ?>" target="_blank">View
                                        Receipt</a>
                                </td>
                                <td><?php echo ucfirst($row['status']); ?></td>
                                <td>
                                    <?php if ($row['status'] == 'pending'): ?>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="receipt_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" name="action" value="approve"
                                                style="padding: 5px 10px; background-color: green; color: white; border: none; cursor: pointer;">Approve</button>
                                            <button type="submit" name="action" value="reject"
                                                style="padding: 5px 10px; background-color: red; color: white; border: none; cursor: pointer;">Reject</button>
                                        </form>
                                    <?php else: ?>
                                        <span><?php echo ucfirst($row['status']); ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No receipts found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>

<?php
$conn->close();
?>