<?php
$servername = "localhost";  
$username = "root";         
$password = "";             
$dbname = "hostel-manage";  


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $type = $_POST['type'];
    $reason = $_POST['reason'];
    $outdate = $_POST['outdate'];
    $returndate = $_POST['returndate'];
    $outtime = $_POST['outtime'];
    $intime = $_POST['intime'];

    
    $sql = "INSERT INTO manages (type, reason, outdate, returndate, outtime, intime) 
            VALUES ('$type', '$reason', '$outdate', '$returndate', '$outtime', '$intime')";

    if ($conn->query($sql) === TRUE) {
        echo "Record inserted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    
    $conn->close();
}
?>
