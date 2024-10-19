<?php
// Include your DB connection
include 'db_connection.php';

// Fetch all pending fee receipts
$query = "SELECT f.*, s.name, s.registration_no FROM fee_uploads f 
          JOIN students s ON f.student_id = s.id
          WHERE f.status = 'Pending'";
$result = mysqli_query($conn, $query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve Hostel Fees</title>
</head>
<body>
    <h2>Pending Fee Receipts</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Registration No</th>
                <th>Receipt</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['registration_no']; ?></td>
                    <td><a href="<?php echo $row['file_path']; ?>" target="_blank">View Receipt</a></td>
                    <td>
                        <form method="post" action="approve_fee.php">
                            <input type="hidden" name="student_id" value="<?php echo $row['student_id']; ?>">
                            <button type="submit">Approve</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
