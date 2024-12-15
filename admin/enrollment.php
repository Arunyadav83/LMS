<?php
session_start();
require_once '../config.php';
require_once '../functions.php';

// Check if the user is logged in as an admin
if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

// Handle delete action
if (isset($_POST['delete_enrollment'])) {
    $enrollment_id = (int)$_POST['id']; // Get the enrollment ID from the form submission

    // Ensure the enrollment ID is valid before executing the delete query
    if ($enrollment_id > 0) {
        $delete_query = "DELETE FROM enrollments WHERE id = $enrollment_id";

        if (mysqli_query($conn, $delete_query)) {
            echo "<div class='alert alert-success'>Enrollment deleted successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error deleting enrollment: " . mysqli_error($conn) . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Invalid enrollment ID.</div>";
    }
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

// Group enrollments by user
$grouped_enrollments = [];
foreach ($enrollments as $enrollment) {
    $grouped_enrollments[$enrollment['username']][] = $enrollment;
}
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
<style>
    h1 {
        color: darkblue;
    }
</style>

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

                    <div style="margin-left: 700px;">
                    <button id="listViewBtn" class="btn btn-primary" onclick="showListView()">
                        <i class="fas fa-list"></i> List 
                    </button>
                    <button id="gridViewBtn" class="btn btn-secondary" onclick="showGridView()">
                        <i class="fas fa-th"></i> Grid 
                    </button>
                    </div>
                    <!-- List View -->
                    <div id="listView" class="view">
                        <h2>Enrollments List</h2>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Course</th>
                                    <th>Tutor</th>
                                    <th>Enrolled At</th>
                                    <th>Actions</th>
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
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary" type="button" id="dropdownMenuButton<?php echo $enrollment['id']; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i> <!-- Three horizontal lines -->
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton<?php echo $enrollment['id']; ?>">
                                                    <li>
                                                        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editEnrollment<?php echo $enrollment['id']; ?>">
                                                            <i class="fas fa-pencil-alt"></i> Edit
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <form action="" method="post" class="d-inline">
                                                            <input type="hidden" name="id" value="<?php echo $enrollment['id']; ?>">
                                                            <button type="submit" name="delete_enrollment" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this enrollment?')">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Edit Enrollment Modal -->
                                    <div class="modal fade" id="editEnrollment<?php echo $enrollment['id']; ?>" tabindex="-1" aria-labelledby="editEnrollmentLabel<?php echo $enrollment['id']; ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editEnrollmentLabel<?php echo $enrollment['id']; ?>">Edit Enrollment</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="" method="post">
                                                        <input type="hidden" name="id" value="<?php echo $enrollment['id']; ?>">
                                                        <div class="mb-3">
                                                            <label for="edit_username<?php echo $enrollment['id']; ?>" class="form-label">Username</label>
                                                            <input type="text" class="form-control" id="edit_username<?php echo $enrollment['id']; ?>" name="username" value="<?php echo htmlspecialchars($enrollment['username']); ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="edit_email<?php echo $enrollment['id']; ?>" class="form-label">Email</label>
                                                            <input type="email" class="form-control" id="edit_email<?php echo $enrollment['id']; ?>" name="email" value="<?php echo htmlspecialchars($enrollment['email']); ?>" required>
                                                        </div>
                                                        <button type="submit" name="update_enrollment" class="btn btn-primary">Save changes</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Grid View -->
                    <div id="gridView" class="view" style="display: none;">
                        <h2>Grid View</h2>
                        <!-- Search Bar for Grid View -->
                        <div class="mb-3">
                            <input type="text" id="searchBar" class="form-control" placeholder="Search by username or email" onkeyup="filterEnrollments()">
                        </div>
                        <div class="row g-4" id="enrollmentGrid">
                            <?php foreach ($grouped_enrollments as $username => $enrollments): ?>
                                <div class="col-md-4 enrollment-card" data-username="<?php echo htmlspecialchars($username); ?>" data-email="<?php echo htmlspecialchars($enrollments[0]['email']); ?>">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="dropdown float-end">
                                                <a class="btn btn-secondary" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editModal">Edit</a></li>
                                                    <li><a class="dropdown-item" href="#">Delete</a></li>
                                                </ul>
                                            </div>
                                            <h5 class="card-title"><?php echo htmlspecialchars($username); ?></h5>
                                            <p class="card-text"><?php echo htmlspecialchars($enrollments[0]['email']); ?></p>
                                            <?php if (!empty($enrollments[0]['user_id'])): ?>
                                                <a href="fetch_enrollments.php?user=<?php echo htmlspecialchars($username); ?>" class="btn btn-primary">View Enrollments</a>
                                            <?php else: ?>
                                                <span class="text-danger">No user specified</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Enrollment</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Form for editing enrollment details -->
                                    <form>
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="username" value="" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" value="" required>
                                        </div>
                                        <!-- Add other fields as necessary -->
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showListView() {
            document.getElementById('listView').style.display = 'block';
            document.getElementById('gridView').style.display = 'none';
            document.getElementById('listViewBtn').classList.add('btn-primary');
            document.getElementById('listViewBtn').classList.remove('btn-secondary');
            document.getElementById('gridViewBtn').classList.add('btn-secondary');
            document.getElementById('gridViewBtn').classList.remove('btn-primary');
        }

        function showGridView() {
            document.getElementById('listView').style.display = 'none';
            document.getElementById('gridView').style.display = 'block';
            document.getElementById('gridViewBtn').classList.add('btn-primary');
            document.getElementById('gridViewBtn').classList.remove('btn-secondary');
            document.getElementById('listViewBtn').classList.add('btn-secondary');
            document.getElementById('listViewBtn').classList.remove('btn-primary');
        }

        function filterEnrollments() {
            const input = document.getElementById('searchBar');
            const filter = input.value.toLowerCase();
            const cards = document.querySelectorAll('.enrollment-card');

            cards.forEach(card => {
                const username = card.getAttribute('data-username').toLowerCase();
                const email = card.getAttribute('data-email').toLowerCase();
                if (username.includes(filter) || email.includes(filter)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
</body>

</html>