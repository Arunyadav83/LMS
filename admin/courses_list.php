<?php
session_start();
require_once '../config.php';
require_once '../functions.php';

// Check if the user is logged in as an admin
if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

$current_page = 'courses_list';

// Handle delete action
if (isset($_POST['delete_course'])) {
    $course_id = (int)$_POST['course_id'];
    // Ensure the course ID is valid before executing the delete query
    if ($course_id > 0) {
        // First, delete related records in the quiz_results table
        $delete_quiz_results_query = "DELETE FROM quiz_results WHERE class_id IN (SELECT id FROM classes WHERE course_id = $course_id)";
        if (!mysqli_query($conn, $delete_quiz_results_query)) {
            echo "<div class='alert alert-danger'>Error deleting related quiz results: " . mysqli_error($conn) . "</div>";
        }

        // Next, delete related records in the quiz_answers table
        $delete_quiz_answers_query = "DELETE FROM quiz_answers WHERE question_id IN (SELECT id FROM quiz_questions WHERE class_id IN (SELECT id FROM classes WHERE course_id = $course_id))";
        if (!mysqli_query($conn, $delete_quiz_answers_query)) {
            echo "<div class='alert alert-danger'>Error deleting related quiz answers: " . mysqli_error($conn) . "</div>";
        }

        // Then, delete related records in the quiz_questions table
        $delete_quiz_questions_query = "DELETE FROM quiz_questions WHERE class_id IN (SELECT id FROM classes WHERE course_id = $course_id)";
        if (!mysqli_query($conn, $delete_quiz_questions_query)) {
            echo "<div class='alert alert-danger'>Error deleting related quiz questions: " . mysqli_error($conn) . "</div>";
        }

        // Next, delete related records in the enrollments table
        $delete_enrollments_query = "DELETE FROM enrollments WHERE course_id = $course_id";
        if (!mysqli_query($conn, $delete_enrollments_query)) {
            echo "<div class='alert alert-danger'>Error deleting related enrollments: " . mysqli_error($conn) . "</div>";
        }

        $delete_payments_query = "DELETE FROM payments WHERE course_id = $course_id"; if (!mysqli_query($conn, $delete_payments_query)) { echo "<div class='alert alert-danger'>Error deleting related payments: " . mysqli_error($conn) . "</div>"; }
        // Finally, delete related records in the classes table
        $delete_classes_query = "DELETE FROM classes WHERE course_id = $course_id";
        if (!mysqli_query($conn, $delete_classes_query)) {
            echo "<div class='alert alert-danger'>Error deleting related classes: " . mysqli_error($conn) . "</div>";
        }

        // Now delete the course
        $delete_query = "DELETE FROM courses WHERE id = $course_id";
        if (mysqli_query($conn, $delete_query)) {
            if (mysqli_affected_rows($conn) > 0) {
                echo "<div class='alert alert-success'>Course deleted successfully.</div>";
            } else {
                echo "<div class='alert alert-danger'>No course found with that ID.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Error deleting course: " . mysqli_error($conn) . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Invalid course ID.</div>";
    }
}

// Handle update action
if (isset($_POST['update_course'])) {
    $course_id = (int)$_POST['course_id'];
    $course_title = mysqli_real_escape_string($conn, $_POST['course_title']);
    $course_description = mysqli_real_escape_string($conn, $_POST['course_description']);
    $course_topics = mysqli_real_escape_string($conn, $_POST['course_topics']);
    $course_prize = (float)$_POST['course_prize'];
    $tutor_id = isset($_POST['tutor_id']) ? (int)$_POST['tutor_id'] : null;

    // Check if tutor_id exists in the users table
    if ($tutor_id !== null) {
        $tutor_exists_query = "SELECT COUNT(*) FROM tutor WHERE id = $tutor_id";
        $tutor_exists_result = mysqli_query($conn, $tutor_exists_query);

        if (!$tutor_exists_result) {
            echo "<script>Swal.fire('Error', 'Error checking tutor: " . mysqli_error($conn) . "', 'error');</script>";
            exit();
        }

        $tutor_exists = mysqli_fetch_row($tutor_exists_result)[0] > 0;

        if (!$tutor_exists) {
            echo "<script>Swal.fire('Error', 'Tutor ID does not exist. Please add the tutor first.', 'error');</script>";
            exit();
        }
    }

    // Ensure course_id is valid and proceed with update
    if ($course_id > 0) {
        $update_query = "
            UPDATE courses 
            SET 
                title = '$course_title', 
                description = '$course_description', 
                topics = '$course_topics', 
                course_prize = $course_prize, 
                tutor_id = $tutor_id 
            WHERE id = $course_id
        ";

        if (mysqli_query($conn, $update_query)) {
            if (mysqli_affected_rows($conn) > 0) {
                $successMessage = json_encode("Course updated successfully."); // Safely encode message
                $redirectURL = json_encode("courses.php"); // Safely encode URL
                echo "<script>
                    Swal.fire('Success', $successMessage, 'success').then(() => {
                        window.location.href = $redirectURL; // Redirect after success
                    });
                </script>";
            } else {
                $warningMessage = json_encode("No changes made or course not found.");
                echo "<script>
                    Swal.fire('Warning', $warningMessage, 'warning');
                </script>";
            }
        }
            
    } else {
        echo "<script>Swal.fire('Error', 'Invalid course ID.', 'error');</script>";
    }
}

// Fetch all courses with their topics
$query = "SELECT c.id, c.title, c.description, 
          GROUP_CONCAT(ct.topic_name SEPARATOR ', ') as topics
          FROM courses c 
          LEFT JOIN course_topics ct ON c.id = ct.course_id
          GROUP BY c.id";
$result = mysqli_query($conn, $query);
$courses = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses List - LMS Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<style>
    .card {
        margin-bottom: 20px; /* Adjust this value as needed */
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
                    <h1 class="mb-4">Courses List</h1>
                       <!-- Add this just before the table in the main content section -->
                       <div class="mb-3">
                        <a href="add_course.php" class="btn btn-success">Add New Course</a>
                    </div>
                    
                    <div style="margin-left: 800px;">
                    <button id="listViewBtn" class="btn btn-primary" onclick="showListView()">
                        <i class="fas fa-list"></i>
                    </button>
                    <button id="gridViewBtn" class="btn btn-secondary" onclick="showGridView()">
                        <i class="fas fa-th"></i> 
                    </button>
                    </div>

                    <!-- List View -->
                    <div id="listView" class="view">
                        <h2>List View</h2>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Topics</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($courses as $course): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($course['id']); ?></td>
                                    <td><?php echo htmlspecialchars($course['title']); ?></td>
                                    <td><?php echo htmlspecialchars($course['description']); ?></td>
                                    <td><?php echo htmlspecialchars($course['topics']); ?></td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                                <li>
                                                    <button class="dropdown-item edit-btn" data-bs-toggle="modal" data-bs-target="#editModal" 
                                                            data-id="<?php echo $course['id']; ?>" 
                                                            data-title="<?php echo htmlspecialchars($course['title']); ?>" 
                                                            data-description="<?php echo htmlspecialchars($course['description']); ?>" 
                                                            data-topics="<?php echo htmlspecialchars($course['topics']); ?>" 
                                                            data-prize="<?php echo isset($course['course_prize']) ? htmlspecialchars($course['course_prize']) : ''; ?>">Edit</button>
                                                </li>
                                                <li>
                                                    <form action="" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this course?');">
                                                        <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                                                        <button type="submit" name="delete_course" class="dropdown-item">Delete</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>   

                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Grid View -->
                    <div id="gridView" class="view">
                        <h2>Grid View</h2>
                        <div class="row g-4" id="enrollmentGrid">
                            <?php foreach ($courses as $course): ?>
                            <div class="col-md-4 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="dropdown float-end">
                                            <a class="btn btn-secondary" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                                <li>
                                                    <button class="dropdown-item edit-btn" data-bs-toggle="modal" data-bs-target="#editModal" 
                                                            data-id="<?php echo $course['id']; ?>" 
                                                            data-title="<?php echo htmlspecialchars($course['title']); ?>" 
                                                            data-description="<?php echo htmlspecialchars($course['description']); ?>" 
                                                            data-topics="<?php echo htmlspecialchars($course['topics']); ?>" 
                                                            data-prize="<?php  isset($course['course_prize']) ? htmlspecialchars($course['course_prize']) : ''; ?>">Edit</button>
                                                </li>
                                                <li>
                                                    <form action="" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this course?');">
                                                        <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                                                        <button type="submit" name="delete_course" class="dropdown-item">Delete</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                        <h5 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                                        <p class="card-text"><?php echo htmlspecialchars($course['description']); ?></p>
                                        <p class="card-text"><strong>Topics Covered:</strong> <?php echo htmlspecialchars($course['topics']); ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Add Course Modal -->
    <div class="modal fade" id="addCourseModal" tabindex="-1" aria-labelledby="addCourseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCourseModalLabel">Add New Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="modal_title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="modal_title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="modal_topics" class="form-label">Topics Covered</label>
                            <input type="text" class="form-control" id="modal_topics" name="topics" required>
                        </div>
                        <div class="mb-3">
                            <label for="modal_description" class="form-label">Description</label>
                            <textarea class="form-control" id="modal_description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="modal_course_price" class="form-label">Course Price</label>
                            <input type="number" class="form-control" id="modal_course_price" name="course_price" required>
                        </div>
                        <button type="submit" name="add_course" class="btn btn-primary">Add Course</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Course Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editCourseForm" method="post" action="">
                        <input type="hidden" name="course_id" id="course_id">
                        <div class="mb-3">
                            <label for="course_title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="course_title" name="course_title" required>
                        </div>
                        <div class="mb-3">
                            <label for="course_description" class="form-label">Description</label>
                            <textarea class="form-control" id="course_description" name="course_description" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="course_topics" class="form-label">Topics</label>
                            <input type="text" class="form-control" id="course_topics" name="course_topics">
                        </div>
                        <div class="mb-3">
                            <label for="course_prize" class="form-label">Course Price</label>
                            <input type="number" class="form-control" id="course_prize" name="course_prize" required>
                        </div>
                        <button type="submit" name="update_course" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Show List View
        function showListView() {
            document.getElementById('listView').style.display = 'block';
            document.getElementById('gridView').style.display = 'none';
            document.getElementById('listViewBtn').classList.add('btn-primary');
            document.getElementById('listViewBtn').classList.remove('btn-secondary');
            document.getElementById('gridViewBtn').classList.add('btn-secondary');
            document.getElementById('gridViewBtn').classList.remove('btn-primary');
        }

        // Show Grid View
        function showGridView() {
            document.getElementById('listView').style.display = 'none';
            document.getElementById('gridView').style.display = 'block';
            document.getElementById('gridViewBtn').classList.add('btn-primary');
            document.getElementById('gridViewBtn').classList.remove('btn-secondary');
            document.getElementById('listViewBtn').classList.add('btn-secondary');
            document.getElementById('listViewBtn').classList.remove('btn-primary');
        }

        // Populate the edit modal with course data
        const editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Button that triggered the modal
            const courseId = button.getAttribute('data-id');
            const courseTitle = button.getAttribute('data-title');
            const courseDescription = button.getAttribute('data-description');
            const courseTopics = button.getAttribute('data-topics');
            const coursePrize = button.getAttribute('data-prize');

            // Update the modal's content
            const modalTitle = editModal.querySelector('.modal-title');
            const courseIdInput = editModal.querySelector('#course_id');
            const courseTitleInput = editModal.querySelector('#course_title');
            const courseDescriptionInput = editModal.querySelector('#course_description');
            const courseTopicsInput = editModal.querySelector('#course_topics');
            const coursePrizeInput = editModal.querySelector('#course_prize');

            modalTitle.textContent = 'Edit Course: ' + courseTitle;
            courseIdInput.value = courseId;
            courseTitleInput.value = courseTitle;
            courseDescriptionInput.value = courseDescription;
            courseTopicsInput.value = courseTopics;
            coursePrizeInput.value = coursePrize;
        });

        // Ensure only one view is visible on load
        document.addEventListener('DOMContentLoaded', function() {
            showListView(); // or showGridView() based on your preference
        });
    </script>
</body>
</html>