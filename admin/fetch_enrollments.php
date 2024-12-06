<?php
require_once '../config.php';
require_once '../functions.php';

if (!isset($_GET['user'])) {
    echo "No user specified.";
    exit();
}

$username = mysqli_real_escape_string($conn, $_GET['user']);

// Fetch user enrollments
$query = "SELECT u.username, u.email, e.course_name, e.enrolled_at
          FROM enrollments e
          LEFT JOIN users u ON e.user_id = u.id
          WHERE u.username = '$username'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $user_enrollments = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    echo "No enrollments found for the user.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($username); ?> - Enrollments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1><?php echo htmlspecialchars($username); ?>'s Enrollments</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Enrolled At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($user_enrollments as $enrollment): ?>
                <tr>
                    <td><?php echo htmlspecialchars($enrollment['course_name']); ?></td>
                    <td>
                        <?php 
                        // Check if enrolled_at is a valid date
                        echo ($enrollment['enrolled_at'] !== '0000-00-00 00:00:00') ? htmlspecialchars($enrollment['enrolled_at']) : 'N/A'; 
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="enrollment.php" class="btn btn-secondary">Back</a>
    </div>
</body>
</html>
