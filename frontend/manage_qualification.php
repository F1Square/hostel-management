<?php
session_start();

if (!isset($_SESSION['email'])) {
    $_SESSION['errMsg'] = "You need to be logged in to view this content";
    header('location: login.php');
    exit();
}

$msg = "";
$qualifications = [];

try {
    $dsn = 'mysql:host=localhost;dbname=practicle_9';
    $pdo = new PDO($dsn, 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    $email = $_SESSION['email'];
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();
    $user_id = $user['id'];

    $sql = "SELECT * FROM education WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $qualifications = $stmt->fetchAll();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_qualification'])) {
        $qualification = isset($_POST['qualification']) ? trim($_POST['qualification']) : '';
        $institution = isset($_POST['institution']) ? trim($_POST['institution']) : '';
        $year_of_completion = isset($_POST['year_of_completion']) ? trim($_POST['year_of_completion']) : '';

        if ($qualification == "" || $institution == "" || $year_of_completion == "") {
            $msg = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
            </button>
            All fields are required.</div>";
        } else {
            $checkDuplicateQuery = "SELECT * FROM education WHERE user_id = :user_id 
                                     AND qualification = :qualification 
                                     AND institution = :institution 
                                     AND year_of_completion = :year_of_completion";
            $stmt = $pdo->prepare($checkDuplicateQuery);
            $stmt->execute([
                ':user_id' => $user_id,
                ':qualification' => $qualification,
                ':institution' => $institution,
                ':year_of_completion' => $year_of_completion
            ]);

            if ($stmt->rowCount() > 0) {
                $msg = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                Duplicate entry found. The qualification already exists.</div>";
            } else {
                $sql = "INSERT INTO education (user_id, qualification, institution, year_of_completion) VALUES (:user_id, :qualification, :institution, :year_of_completion)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':user_id' => $user_id,
                    ':qualification' => $qualification,
                    ':institution' => $institution,
                    ':year_of_completion' => $year_of_completion
                ]);

                $msg = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                Qualification added successfully!</div>";

                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
        }
    }
} catch (PDOException $e) {
    $msg = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>&times;</span>
    </button>
    Database error: " . $e->getMessage() . "</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Educational Qualifications</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container" style="margin-top: 50px;">
        <h2>Manage Educational Qualifications</h2>
        <?php if ($msg != "") echo $msg; ?>

        <form method="POST">
            <div class="form-group">
                <label for="qualification">Qualification:</label>
                <input type="text" class="form-control" id="qualification" name="qualification" required>
            </div>
            <div class="form-group">
                <label for="institution">Institution:</label>
                <input type="text" class="form-control" id="institution" name="institution" required>
            </div>
            <div class="form-group">
                <label for="year_of_completion">Year of Completion:</label>
                <input type="number" class="form-control" id="year_of_completion" name="year_of_completion" min="1900" max="<?php echo date('Y'); ?>" required>
            </div>
            <button type="submit" name="add_qualification" class="btn btn-primary">Add Qualification</button>
        </form>

        <h3 class="mt-5">Your Qualifications</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Qualification</th>
                    <th>Institution</th>
                    <th>Year of Completion</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($qualifications) > 0): ?>
                    <?php foreach ($qualifications as $qualification): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($qualification['qualification']); ?></td>
                            <td><?php echo htmlspecialchars($qualification['institution']); ?></td>
                            <td><?php echo htmlspecialchars($qualification['year_of_completion']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No qualifications found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
