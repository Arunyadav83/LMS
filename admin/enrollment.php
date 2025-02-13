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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .navbar-brand {
            font-size: 20px;
            /* margin-inline-start: 20px; */
            color: white;
        }

        h1 {
            color: #16308b;
        }

        .nav-item {
            color: white;
            padding-inline: 20px;
            /* text-decoration: underline; */
        }

        .nav-link:hover {
            color: white;
            /* text-decoration: underline; */
        }

        .navbar {
            background-color: #1a237e;
            margin: auto;
            padding: 0px 5px;
            /* Adjust top-bottom and left-right padding to reduce height */
            line-height: 1.2;
            /* Reduce line height for inner elements */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Optional: Adds a subtle shadow for depth */
        }

        .button {
            padding-inline: 10px;
            text-decoration: none;
            color: #0433c3;
            padding-block: 10px;
        }

        .button:hover {
            background-color: #0433c3;
            color: white;
            border-radius: 30px !important;
        }

        @media (max-width: 768px) {
            .table_list {
                overflow-x: auto;
                width: 100%;
            }

            .a {
                margin-left: 120px !important;
                text-align: center;
            }
        }

        /* Grid View Styles */
        .card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid rgba(0, 0, 0, 0.125);
            margin-bottom: 20px;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
        }

        .card-header {
            background-color: #1a237e;
            color: white;
            padding: 0.75rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dropdown-menu {
            min-width: 120px;
            padding: 0.5rem 0;
            margin: 0;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #333;
        }

        .dropdown-item i {
            width: 16px;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .dropdown-item.text-danger:hover {
            background-color: #fff5f5;
        }

        .kebab-menu {
            background: transparent;
            border: none;
            color: white;
            font-size: 20px;
            padding: 5px 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .kebab-menu:hover {
            opacity: 0.8;
        }

        .kebab-menu:focus {
            outline: none;
            box-shadow: none;
        }

        .card-body {
            padding: 1.25rem;
        }

        .card-body i {
            width: 20px;
            color: #1a237e;
        }

        .enrollment-card {
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .enrollment-card {
                width: 100%;
            }
        }

        /* Kebab Menu Styles */
        .kebab-menu {
            position: relative;
            display: inline-block;
        }

        .kebab-button {
            background: none;
            border: none;
            padding: 5px;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            gap: 3px;
            align-items: center;
        }

        .kebab-dot {
            width: 4px;
            height: 4px;
            background-color: #666;
            border-radius: 50%;
        }

        .popup-menu {
            position: absolute;
            right: 0;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            border-radius: 4px;
            display: none;
            z-index: 1000;
            min-width: 120px;
        }

        .popup-menu.show {
            display: block;
        }

        .popup-menu a {
            display: block;
            padding: 8px 15px;
            text-decoration: none;
            color: #333;
        }

        .popup-menu a:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->


    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include 'sidebar.php'; ?>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="container mt-4">
                    <h1 class="mb-4" style="margin-top:81px">Enrollments</h1>

                    <div style="margin-left: 900px;margin-top: -71px;" class="a">
                        <button id="listViewBtn" class="btn btn-primary me-2" onclick="showListView()">
                            <i class="fas fa-list"></i>
                        </button>
                        <button id="gridViewBtn" class="btn btn-secondary" onclick="showGridView()">
                            <i class="fas fa-th"></i>
                        </button>
                    </div>
                    <!-- List View -->
                    <div id="listView" class="view">
                        <div class="card shadow-sm mb-4" style=" margin-top: 25px;">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <!-- <h2 class="mb-0">Enrollments List</h2> -->
                                <!-- <button class="btn btn-light btn-sm">
                                    <i class="fas fa-plus"></i> Add Enrollment
                                </button> -->
                            </div>
                            <div class="card-body">
                                <div class="table_list">
                                    <table class="table table-hover table-striped align-middle">
                                        <thead class="table-primary">
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
                                                        <div class="kebab-menu">
                                                            <button class="kebab-button" onclick="toggleMenu(this)">
                                                                <span class="kebab-dot"></span>
                                                                <span class="kebab-dot"></span>
                                                                <span class="kebab-dot"></span>
                                                            </button>

                                                            <!-- Debugging: Show Enrollment ID -->
                                                            <?php echo "<!-- Enrollment ID: " . htmlspecialchars($enrollment['id']) . " -->"; ?>

                                                            <!-- Popup Menu -->
                                                            <div class="popup-menu">
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#editEnrollmentModal<?php echo htmlspecialchars($enrollment['id']); ?>">
                                                                    <i class="fas fa-edit text-primary"></i> Edit
                                                                </a>
                                                                <form method="POST" class="d-inline">
                                                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($enrollment['id']); ?>">
                                                                    <button type="submit" name="delete_enrollment" class="dropdown-item text-danger"
                                                                        onclick="return confirm('Are you sure you want to delete this enrollment?')">
                                                                        <i class="fas fa-trash"></i> Delete
                                                                    </button>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    </td>
                                                </tr>

                                                <!-- Edit Enrollment Modal -->

                                                <div class="modal fade" id="editEnrollmentModal<?php echo htmlspecialchars($enrollment['id']); ?>"
                                                    tabindex="-1" aria-labelledby="editEnrollmentModalLabel<?php echo htmlspecialchars($enrollment['id']); ?>"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editEnrollmentModalLabel<?php echo htmlspecialchars($enrollment['id']); ?>">
                                                                    Edit Enrollment
                                                                </h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="" method="post">
                                                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($enrollment['id']); ?>">
                                                                    <div class="row">
                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="edit_username_<?php echo htmlspecialchars($enrollment['id']); ?>" class="form-label">Username</label>
                                                                            <input type="text" class="form-control"
                                                                                id="edit_username_<?php echo htmlspecialchars($enrollment['id']); ?>"
                                                                                name="username" value="<?php echo htmlspecialchars($enrollment['username']); ?>" required>
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="edit_email_<?php echo htmlspecialchars($enrollment['id']); ?>" class="form-label">Email</label>
                                                                            <input type="email" class="form-control"
                                                                                id="edit_email_<?php echo htmlspecialchars($enrollment['id']); ?>"
                                                                                name="email" value="<?php echo htmlspecialchars($enrollment['email']); ?>" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="edit_course_name_<?php echo htmlspecialchars($enrollment['id']); ?>" class="form-label">Course Name</label>
                                                                        <input type="text" class="form-control"
                                                                            id="edit_course_name_<?php echo htmlspecialchars($enrollment['id']); ?>"
                                                                            name="course_name" value="<?php echo htmlspecialchars($enrollment['course_name']); ?>" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="edit_enrolled_at_<?php echo htmlspecialchars($enrollment['id']); ?>" class="form-label">Enrolled At</label>
                                                                        <input type="date" class="form-control"
                                                                            id="edit_enrolled_at_<?php echo htmlspecialchars($enrollment['id']); ?>"
                                                                            name="enrolled_at" value="<?php echo htmlspecialchars($enrollment['enrolled_at']); ?>" required>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                        <button type="submit" name="update_enrollment" class="btn btn-primary">Save changes</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Ensure Bootstrap JS is Loaded -->
                                                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grid View -->
                    <div id="gridView" class="view" style="display: none;">
                        <div class="mb-3">
                            <input type="text" id="searchBar" class="form-control" placeholder="Search by username or email" onkeyup="filterEnrollments()" style="margin-top: 25px;">
                        </div>
                        <div class="row g-4">
                            <?php foreach ($enrollments as $enrollment): ?>
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="card h-100">
                                        <div class="card-header bg-primary d-flex justify-content-between align-items-center">
                                            <h5 class="card-title mb-0 text-white"><?php echo htmlspecialchars($enrollment['course_name']); ?></h5>
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-light btn-sm" data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editEnrollment<?php echo $enrollment['id']; ?>">
                                                            <i class="fas fa-edit text-primary me-2"></i> Edit
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this enrollment?');">
                                                            <input type="hidden" name="id" value="<?php echo $enrollment['id']; ?>">
                                                            <button type="submit" name="delete_enrollment" class="dropdown-item text-danger">
                                                                <i class="fas fa-trash-alt me-2"></i> Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <i class="fas fa-user me-2 text-primary"></i>
                                                <strong>Student:</strong> <?php echo htmlspecialchars($enrollment['username']); ?>
                                            </div>
                                            <div class="mb-3">
                                                <i class="fas fa-envelope me-2 text-primary"></i>
                                                <strong>Email:</strong> <?php echo htmlspecialchars($enrollment['email']); ?>
                                            </div>
                                            <div class="mb-3">
                                                <i class="fas fa-chalkboard-teacher me-2 text-primary"></i>
                                                <strong>Tutor:</strong> <?php echo htmlspecialchars($enrollment['tutor_name']); ?>
                                            </div>
                                            <div>
                                                <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                                <strong>Enrolled:</strong> <?php echo date('M d, Y', strtotime($enrollment['enrolled_at'])); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Enrollment</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="username" class="form-label">Username</label>
                                                <input type="text" class="form-control" id="username" placeholder="Enter username" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email" placeholder="Enter email" required>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="course_name" class="form-label">Course Name</label>
                                            <input type="text" class="form-control" id="course_name" placeholder="Enter course name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="enrolled_at" class="form-label">Enrolled At</label>
                                            <input type="date" class="form-control" id="enrolled_at" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
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

        function toggleMenu(button) {
            // Close all other menus
            document.querySelectorAll('.popup-menu.show').forEach(menu => {
                if (menu !== button.nextElementSibling) {
                    menu.classList.remove('show');
                }
            });

            // Toggle the clicked menu
            const menu = button.nextElementSibling;
            menu.classList.toggle('show');
        }

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.kebab-menu')) {
                document.querySelectorAll('.popup-menu.show').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });
    </script>
</body>

</html>