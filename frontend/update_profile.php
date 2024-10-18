<?php
session_start();

if (!isset($_SESSION['email'])) {
    $_SESSION['errMsg'] = "You need to be logged in to view this content";
    header('location: login.php');
    exit();
}

$msg = "";

try {
    $dsn = 'mysql:host=localhost;dbname=practicle_9';
    $pdo = new PDO($dsn, 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    $email = isset($_SESSION['email']) ? $_SESSION['email'] : null;

    $phone = '';
    $address = '';
    $pincode = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
        $address = isset($_POST['address']) ? trim($_POST['address']) : '';
        $pincode = isset($_POST['pincode']) ? trim($_POST['pincode']) : '';

        if ($phone == "" || $address == "" || $pincode == "") {
            $msg = "<div class='alert alert-dismissible alert-warning'>
            <button type='button' class='close' data-dismiss='alert'>&times;</button>
            All fields are required.</div>";
        } elseif (!preg_match('/^[6-9]\d{9}$/', $phone)) {
            $msg = "<div class='alert alert-dismissible alert-warning'>
            <button type='button' class='close' data-dismiss='alert'>&times;</button>
            Phone number should start with 6, 7, 8, or 9 and be 10 digits long.</div>";
        } elseif (!preg_match('/^\d{6}$/', $pincode)) {
            $msg = "<div class='alert alert-dismissible alert-warning'>
            <button type='button' class='close' data-dismiss='alert'>&times;</button>
            Pincode should be exactly 6 digits.</div>";
        } else {

            $sql = "UPDATE users SET phone = :phone, address = :address, pincode = :pincode WHERE email = :email";
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':phone' => $phone,
                ':address' => $address,
                ':pincode' => $pincode,
                ':email' => $email
            ]);

            if ($stmt->rowCount()) {
                $msg = "<div class='alert alert-dismissible alert-success'>
                <button type='button' class='close' data-dismiss='alert'>&times;</button>
                Profile updated successfully!</div>";
            } else {
                $msg = "<div class='alert alert-dismissible alert-danger'>
                <button type='button' class='close' data-dismiss='alert'>&times;</button>
                No changes were made.</div>";
            }
        }
    }

    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':email' => $email]);

    $user = $stmt->fetch();

    if (!$user) {
        $_SESSION['errMsg'] = "User not found";
        header('location: login.php');
        exit();
    }
} catch (PDOException $e) {
    $msg = "<div class='alert alert-dismissible alert-danger'>
    <button type='button' class='close' data-dismiss='alert'>&times;</button>
    Database error: " . $e->getMessage() . "</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container" style="margin-top: 50px;">
        <h2>Update Profile</h2>
        
        <?php if ($msg != ""): ?>
            <?php echo $msg; ?>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars(isset($_POST['phone']) ? $_POST['phone'] : ''); ?>" >
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars(isset($_POST['address']) ? $_POST['address'] : ''); ?>" >
            </div>
            <div class="form-group">
                <label for="pincode">Pincode:</label>
                <input type="text" class="form-control" id="pincode" name="pincode" value="<?php echo htmlspecialchars(isset($_POST['pincode']) ? $_POST['pincode'] : ''); ?>" >
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
</body>
</html>
