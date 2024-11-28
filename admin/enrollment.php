<?php
session_start();
require_once '../config.php';
require_once '../functions.php';

// Check if the user is logged in as an admin
if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

$current_page = 'enrollment';

// Fetch all enrollments with user and course details
$query = "SELECT e.id, e.user_id, e.course_id, e.course_name, e.tutor_id, e.enrolled_at,
                 u.username, u.email,
                 t.full_name AS tutor_name
          FROM enrollments e
          LEFT JOIN users u ON e.user_id = u.id
          LEFT JOIN tutors t ON e.tutor_id = t.id
          ORDER BY e.enrolled_at DESC";

$result = mysqli_query($conn, $query);
$enrollments = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollments - LMS Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">LMS Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-user"></i> Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include 'sidebar.php'; ?>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="container mt-4">
                    <h1 class="mb-4">Enrollments</h1>

                    <!-- Enrollments List -->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Email</th>
                                <th>Course</th>
                                <th>Tutor</th>
                                <th>Enrolled At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($enrollments as $enrollment): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($enrollment['id']); ?></td>
                                <td><?php echo htmlspecialchars($enrollment['username']); ?></td>
                                <td><?php echo htmlspecialchars($enrollment['email']); ?></td>
                                <td><?php echo htmlspecialchars($enrollment['course_name']); ?></td>
                                <td><?php echo htmlspecialchars($enrollment['tutor_name']); ?></td>
                                <td><?php echo htmlspecialchars($enrollment['enrolled_at']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>