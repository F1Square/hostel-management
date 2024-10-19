<?php
// Connect to the database
include 'db_connection.php';

if (isset($_POST['student_id'])) {
    $studentId = $_POST['student_id'];

    // Update the status of the receipt to "Approved"
    $query = "UPDATE fee_uploads SET status = 'Approved' WHERE student_id = '$studentId'";
    
    if (mysqli_query($conn, $query)) {
        echo "Fee receipt approved!";
        header("Location: admin_approve_fees.php"); // Redirect to admin page after approval
    } else {
        echo "Error approving fee receipt.";
    }
}
?>
