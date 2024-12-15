<?php
session_start();
require_once '../config.php';
require_once '../functions.php';

// Check if the user is logged in as an admin
if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

$current_page = 'courses';

// Fetch all tutors
$query = "SELECT id, full_name FROM tutors";
$result = mysqli_query($conn, $query);
$tutors = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Set default value for tutor_id
$tutor_id = isset($_POST['tutor_id']) ? (int)$_POST['tutor_id'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_course'])) {
        $title_id = (int)$_POST['title_id'];
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $tutor_id = (int)$_POST['tutor_id'];

        // Validate tutor_id
        $stmt = $conn->prepare("SELECT id FROM tutors WHERE id = ? AND role = 'instructor'");
        $stmt->bind_param("i", $tutor_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Insert course
            $stmt = $conn->prepare("INSERT INTO courses (title_id, description, tutor_id) VALUES (?, ?, ?)");
            $stmt->bind_param("isi", $title_id, $description, $tutor_id);
            if ($stmt->execute()) {
                echo "Course added successfully.";
            } else {
                error_log("Error: " . $stmt->error);
                echo "An error occurred. Please try again.";
            }
        } else {
            echo "Invalid tutor selected.";
        }
    } elseif (isset($_POST['edit_course'])) {
        $id = (int)$_POST['id'];
        $title_id = mysqli_real_escape_string($conn, $_POST['title']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $tutor_id = (int)$_POST['tutor_id'];

        // Ensure the tutor is valid and an instructor
        $tutor_query = "SELECT full_name FROM tutors WHERE id = $tutor_id AND role = 'instructor'";
        $tutor_result = mysqli_query($conn, $tutor_query);

        if (mysqli_num_rows($tutor_result) > 0) {
            // Fetch the current tutor ID to check if a change is being made
            $current_tutor_query = "SELECT tutor_id FROM courses WHERE id = $id";
            $current_tutor_result = mysqli_query($conn, $current_tutor_query);
            $current_tutor = mysqli_fetch_assoc($current_tutor_result)['tutor_id'];

            if ($current_tutor != $tutor_id) {
                // Update the course with new details
                $query = "UPDATE courses 
                          SET title = '$title_id', 
                              description = '$description', 
                              tutor_id = $tutor_id 
                          WHERE id = $id";

                if (mysqli_query($conn, $query)) {
                    echo "Course updated successfully.";
                } else {
                    echo "Error updating course: " . mysqli_error($conn);
                }
            } else {
                echo "No changes were made as the tutor is the same.";
            }
        } else {
            echo "Invalid tutor selected.";
        }
    } elseif (isset($_POST['delete_course'])) {
        $id = (int)$_POST['id'];

        // Delete the enrollments associated with the course first
        $enrollment_Delete_query = "DELETE FROM enrollments WHERE course_id = $id";
        $payment_delete_query = "DELETE FROM payments WHERE course_id = $id";
        if (mysqli_query($conn, $enrollment_Delete_query) && mysqli_query($conn, $payment_delete_query)) {
            // If successful, delete the course
            $query = "DELETE FROM courses WHERE id = $id";
            if (mysqli_query($conn, $query)) {
                echo "Course deleted successfully.";
            } else {
                echo "Error deleting course: " . mysqli_error($conn);
            }
        } else {
            echo "Error deleting enrollments or payments: " . mysqli_error($conn);
        }
    }
}

// Fetch all courses with tutor details
$query = "SELECT c.*, t.full_name as tutor_name 
          FROM courses c 
          LEFT JOIN tutors t ON c.tutor_id = t.id";
$result = mysqli_query($conn, $query);
$courses = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Fetch all unique course titles
$query = "SELECT DISTINCT id, title FROM courses ORDER BY title";
$result = mysqli_query($conn, $query);
$course_titles = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses - LMS Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        #a {
            color: #00CED1;
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .add-course-form {
            background-color: #00CED1;
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .courses-list th,
        .courses-list td {
            font-size: 1.1rem;
        }

        .grid-view .card {
            height: 100%;
        }

        .view-toggle {
            margin-bottom: 1rem;
        }
    </style>
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
                    <h1 class="mb-4">Courses</h1>

                    <!-- Add Course Form -->
                    <h2 id="a">Add New Course</h2>
                    <form action="" method="post" class="mb-4 add-course-form">
                        <div class="mb-3">
                            <label for="title_id" class="form-label">Title</label>
                            <select class="form-control" id="title_id" name="title_id" required>
                                <option value="">Select Course Title</option>
                                <?php foreach ($course_titles as $title): ?>
                                    <option value="<?php echo $title['id']; ?>"><?php echo htmlspecialchars($title['title']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="tutor_id" class="form-label">Tutor</label>
                            <select class="form-control" id="tutor_id" name="tutor_id" required>
                                <option value="">Select Tutor</option>
                                <?php foreach ($tutors as $tutor): ?>
                                    <option value="<?php echo $tutor['id']; ?>" <?php echo (isset($_POST['tutor_id']) && $_POST['tutor_id'] == $tutor['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($tutor['full_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" name="add_course" class="btn btn-primary">Add Course</button>
                    </form>

                    <!-- Courses List -->
                    <h2>Courses List</h2>
                    <div class="view-toggle text-end mb-3">
                        <button class="btn btn-outline-primary" id="grid-view-btn"><i class="fas fa-th"></i> Grid</button>
                        <button class="btn btn-primary" id="list-view-btn"><i class="fas fa-list"></i> List</button>
                    </div>

                    <!-- List View -->
                    <div id="list-view">
                        <table class="table table-striped courses-list">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Tutor</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($courses as $course): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($course['id']); ?></td>
                                        <td><?php echo htmlspecialchars($course['title']); ?></td>
                                        <td><?php echo htmlspecialchars($course['description']); ?></td>
                                        <td><?php echo htmlspecialchars($course['tutor_name']); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editCourse<?php echo $course['id']; ?>">Edit</button>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="id" value="<?php echo $course['id']; ?>">
                                                <button type="submit" name="delete_course" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Grid View -->
                    <div id="grid-view" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" style="display: none;">
                        <?php foreach ($courses as $course): ?>
                            <div class="col">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                                        <p class="card-text"><?php echo htmlspecialchars($course['description']); ?></p>
                                        <p class="card-text"><small class="text-muted">Tutor: <?php echo htmlspecialchars($course['tutor_name']); ?></small></p>
                                    </div>
                                    <div class="card-footer">
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editCourse<?php echo $course['id']; ?>">Edit</button>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="id" value="<?php echo $course['id']; ?>">
                                            <button type="submit" name="delete_course" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Edit Course Modals -->
                    <?php foreach ($courses as $course): ?>
                        <div class="modal fade" id="editCourse<?php echo $course['id']; ?>" tabindex="-1" aria-labelledby="editCourseLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <form method="post">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editCourseLabel">Edit Course</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id" value="<?php echo $course['id']; ?>">
                                            <div class="mb-3">
                                                <label for="title" class="form-label">Title</label>
                                                <select class="form-control" id="title" name="title" required>
                                                    <option value="">Select Course Title</option>
                                                    <?php foreach ($course_titles as $title): ?>
                                                        <option value="<?php echo $title['title']; ?>" <?php echo ($course['title'] == $title['title']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($title['title']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="description" class="form-label">Description</label>
                                                <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($course['description']); ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="tutor_id" class="form-label">Tutor</label>
                                                <select class="form-control" id="tutor_id" name="tutor_id" required>
                                                    <option value="">Select Tutor</option>
                                                    <?php foreach ($tutors as $tutor): ?>
                                                        <option value="<?php echo $tutor['id']; ?>" <?php echo ($course['tutor_id'] == $tutor['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($tutor['full_name']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="edit_course" class="btn btn-primary">Save changes</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const gridViewBtn = document.getElementById('grid-view-btn');
            const listViewBtn = document.getElementById('list-view-btn');
            const gridView = document.getElementById('grid-view');
            const listView = document.getElementById('list-view');

            gridViewBtn.addEventListener('click', function() {
                gridView.style.display = 'flex';
                listView.style.display = 'none';
                gridViewBtn.classList.add('btn-primary');
                gridViewBtn.classList.remove('btn-outline-primary');
                listViewBtn.classList.add('btn-outline-primary');
                listViewBtn.classList.remove('btn-primary');
            });

            listViewBtn.addEventListener('click', function() {
                gridView.style.display = 'none';
                listView.style.display = 'block';
                listViewBtn.classList.add('btn-primary');
                listViewBtn.classList.remove('btn-outline-primary');
                gridViewBtn.classList.add('btn-outline-primary');
                gridViewBtn.classList.remove('btn-primary');
            });
        });
    </script>
</body>

</html>

