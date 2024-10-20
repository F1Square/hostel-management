<?php
session_start();
$conn = new mysqli('localhost', 'username', 'password', 'hostel_management');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['approve'])) {
    $receiptId = intval($_GET['approve']);
    $conn->query("UPDATE receipts SET approved = 1 WHERE id = $receiptId");
}

// Fetch all receipts
$result = $conn->query("SELECT r.id, r.file_path, u.username FROM receipts r JOIN users u ON r.user_id = u.id");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Approve Receipts</title>
</head>
<body>
    <h2>Pending Receipts</h2>
    <table border="1">
        <tr>
            <th>Username</th>
            <th>Receipt</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['username']; ?></td>
            <td><img src="<?php echo $row['file_path']; ?>" alt="Receipt" style="width:100px;height:auto;"></td>
            <td><a href="?approve=<?php echo $row['id']; ?>">Approve</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
