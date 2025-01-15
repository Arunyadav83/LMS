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
function truncateText($text, $maxLength = 100)
{
    if (strlen($text) > $maxLength) {
        return substr($text, 0, $maxLength) . '...';
    }
    return $text;
}

// Set default value for tutor_id
$tutor_id = isset($_POST['tutor_id']) ? (int)$_POST['tutor_id'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_course'])) {
        $title_id = (int)$_POST['title_id'];
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $tutor_id = (int)$_POST['tutor_id'];
        // $topics=mysql_real_escape_string($conn, $_POST['topics']);

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
        $id = (int)$_POST['id']; // Course ID
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $tutor_id = (int)$_POST['tutor_id']; // Ensure tutor_id is an integer

        // Verify if tutor exists in the tutors table
        $query = "SELECT id FROM tutors WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $tutor_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            // Tutor exists in the tutors table, proceed to update course
            $query = "UPDATE courses 
                      SET title = ?, 
                          description = ?, 
                          tutor_id = ? 
                      WHERE id = ?";
            $update_stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($update_stmt, 'ssii', $title, $description, $tutor_id, $id);

            if (mysqli_stmt_execute($update_stmt)) {
                // Successfully updated the course
                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: 'Success',
                            text: 'Course updated successfully!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = 'courses.php'; // Redirect to courses page
                        });
                    });
                </script>";
            } else {
                // Error updating the course
                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Error updating course: " . mysqli_error($conn) . "',
                            icon: 'error',
                            confirmButtonText: 'Try Again'
                        });
                    });
                </script>";
            }
        } else {
            // Tutor does not exist in tutors table, handle error
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'The selected tutor does not exist in the tutors table.',
                        icon: 'error',
                        confirmButtonText: 'Try Again'
                    });
                });
            </script>";
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
                // Display success SweetAlert
                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'The course has been deleted successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = 'courses_list.php'; // Redirect to course list
                        });
                    });
                </script>";
            } else {
                // Display error SweetAlert for course deletion
                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Error deleting course: " . mysqli_error($conn) . "',
                            icon: 'error',
                            confirmButtonText: 'Try Again'
                        });
                    });
                </script>";
            }
        } else {
            // Display error SweetAlert for enrollments or payments deletion
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Error deleting enrollments or payments: " . mysqli_error($conn) . "',
                        icon: 'error',
                        confirmButtonText: 'Try Again'
                    });
                });
            </script>";
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">




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

        .card {
            border-radius: 12px;
            transition: transform 0.2s, box-shadow 0.2s;
            overflow: hidden;
        }

        main {
            background-color: rgb(244, 244, 255);
        }

        .card:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 1.2rem;
            box-shadow: 0px 2px lightblue 0.8px;
            background-color: rgb(255, 255, 255);
            ;

        }

        .edit-btn i,
        .delete-btn i {
            font-size: 1.2rem;
            color: black;
            transition: color 0.3s ease;
        }

        .edit-btn i:hover {
            color: lightblue;
            /* Hover effect for edit button */
        }

        .delete-btn i:hover {
            color: red;
            /* Hover effect for delete button */
        }

        .card-title {
            font-size: 1.0rem;
            /* Larger font size for the title */
            font-weight: bold;
            margin: 0;
            /* Remove bottom margin for proper alignment */
        }

        .card-text {
            font-size: 1.1rem;
            /* Adjust font size for description and tutor */
        }

        .d-flex {
            display: flex;
            align-items: center;
        }

        .gap-2>* {
            margin-left: 8px;
            /* Spacing between buttons */
        }

        .navbar-brand {
            font-size: 20px;
            /* margin-inline-start: 20px; */
            color: white;


        }

        .nav-link {
            color: white;
            padding-inline: 20px;
            text-decoration: underline;

        }

        .nav-link:hover {
            color: white;
            /* text-decoration: underline; */
        }

        .navbar {
            background-color:#1a237e;
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
     h2{
        color: #16308b;
     }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg custom-navbar">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="container-fluid">
            <a class="navbar-brand text-light fw-bold" href="index.php">LMS Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item">
                        <a class="nav-link text-light d-flex align-items-center" href="#">
                            <i class="fas fa-user me-2"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light d-flex align-items-center" href="logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
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
                    <!-- <h1 class="mb-4">Courses</h1> -->

                    <!-- Add Course Form -->
                    <!-- <h2 id="a">Add New Course</h2> -->
                    <!-- <form action="" method="post" class="mb-4 add-course-form">
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
                    </form> -->

                    <!-- Courses List -->
                    <h2>Courses</h2>
                    <div class="view-toggle text-end mb-3">
                        <button class="btn btn-outline-primary" id="grid-view-btn"><i class="fas fa-th"></i></button>
                        <button class="btn btn-primary" id="list-view-btn"><i class="fas fa-list"></i> </button>
                    </div>

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
                                        <td><?php echo htmlspecialchars(truncateText($course['description'], 50)); ?></td>
                                        <td><?php echo htmlspecialchars($course['tutor_name']); ?></td>
                                        <td>
                                            <div class="d-flex">
                                                <!-- Edit Button with Icon -->
                                                <button type="button" class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#editCourse<?php echo $course['id']; ?>">
                                                    <i class="bi bi-pencil"></i> <!-- Pencil icon -->
                                                </button>

                                                <!-- Delete Button with Icon -->
                                                <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this course?');">
                                                    <input type="hidden" name="id" value="<?php echo $course['id']; ?>">
                                                    <button type="submit" name="delete_course" class="btn btn-danger btn-sm">
                                                        <i class="bi bi-trash"></i> <!-- Trash icon -->
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Grid View -->
                    <div id="grid-view" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" style="display: none;">
                        <?php foreach ($courses as $course): ?>
                            <div class="col" id="course-card-<?php echo $course['id']; ?>">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body">
                                        <!-- Title with Buttons on Same Line -->
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="card-title" style="color: black; margin: 0;">Title: <?php echo htmlspecialchars($course['title']); ?></h5>
                                            <div class="d-flex gap-2">
                                                <!-- Edit Button -->
                                                <button type="button" class="btn btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editCourse<?php echo $course['id']; ?>">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <!-- Delete Button -->
                                                <button type="button" class="btn btn-sm delete-btn" onclick="confirmDelete(event, <?php echo $course['id']; ?>)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Description and Tutor -->
                                        <p class="card-text text-muted">Description: <?php echo htmlspecialchars(truncateText($course['description'], 50)); ?></p>
                                        <p class="card-text" style="color: black;">Tutor: <?php echo htmlspecialchars($course['tutor_name']); ?></p>
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

        function confirmDelete(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.closest('form').submit();
                }
            });
            return false;
        }

        // Ensure SweetAlert is properly initialized
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Swal === 'undefined') {
                console.error('SweetAlert2 is not loaded!');
            }
        });
    </script>
</body>

</html>