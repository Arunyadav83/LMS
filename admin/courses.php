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
$tutor_id = isset($_POST['tutor_id']) ? (int)$_POST['tutor_id'] : null; // Default to null if not set

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_course'])) {
        $title_id = (int)$_POST['title_id'];
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $tutor_id = (int)$_POST['tutor_id']; // Ensure this is being set correctly
        
        // Check if tutor_id is valid before inserting
        if ($tutor_id > 0) {
            $query = "INSERT INTO courses (title, description, tutor_id) 
                      SELECT title, '$description', $tutor_id 
                      FROM courses WHERE id = $title_id";
            mysqli_query($conn, $query);
        }
    } elseif (isset($_POST['edit_course'])) {
        $id = (int)$_POST['id'];
        $title_id = (int)$_POST['title_id'];
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $tutor_id = (int)$_POST['tutor_id'];
        
        $query = "UPDATE courses 
                  SET title = (SELECT title FROM courses WHERE id = $title_id), 
                      description = '$description', 
                      tutor_id = $tutor_id 
                  WHERE id = $id";
        mysqli_query($conn, $query);
    } elseif (isset($_POST['delete_course'])) {
        $id = (int)$_POST['id'];
        $query = "DELETE FROM courses WHERE id=$id";
        mysqli_query($conn, $query);
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
        /* Add styles for the Add New Course section */
        #a {
            color:  #00CED1 ; /* Change the color of the heading */
            font-size: 2.5rem; /* Increase font size */
            margin-bottom: 20px; /* Add some space below the heading */
        }

        .add-course-form {
            background-color: #00CED1 (; /* Light background for the form */
            border: 1px solid #ced4da; /* Border around the form */
            border-radius: 5px; /* Rounded corners */
            padding: 20px; /* Padding inside the form */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        /* Increase font size for the courses list */
        .courses-list th, .courses-list td {
            font-size: 1.1rem; /* Increase font size for table headers and cells */
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
                    <table class="table table-striped">
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
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editCourse<?php echo $course['id']; ?>">
                                        Edit
                                    </button>
                                    <form action="" method="post" class="d-inline">
                                        <input type="hidden" name="id" value="<?php echo $course['id']; ?>">
                                        <button type="submit" name="delete_course" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this course?')">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Course Modal -->
                            <div class="modal fade" id="editCourse<?php echo $course['id']; ?>" tabindex="-1" aria-labelledby="editCourseLabel<?php echo $course['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editCourseLabel<?php echo $course['id']; ?>">Edit Course</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="" method="post">
                                                <input type="hidden" name="id" value="<?php echo $course['id']; ?>">
                                                <div class="mb-3">
                                                    <label for="edit_title_id<?php echo $course['id']; ?>" class="form-label">Title</label>
                                                    <select class="form-control" id="edit_title_id<?php echo $course['id']; ?>" name="title_id" required>
                                                        <?php foreach ($course_titles as $title): ?>
                                                            <option value="<?php echo $title['id']; ?>" <?php echo ($title['title'] == $course['title']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($title['title']); ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit_description<?php echo $course['id']; ?>" class="form-label">Description</label>
                                                    <textarea class="form-control" id="edit_description<?php echo $course['id']; ?>" name="description" rows="3"><?php echo htmlspecialchars($course['description']); ?></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit_tutor_id<?php echo $course['id']; ?>" class="form-label">Tutor</label>
                                                    <select class="form-control" id="edit_tutor_id<?php echo $course['id']; ?>" name="tutor_id" required>
                                                        <option value="">Select Tutor</option>
                                                        <?php foreach ($tutors as $tutor): ?>
                                                            <option value="<?php echo $tutor['id']; ?>" <?php echo ($tutor['id'] == $course['tutor_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($tutor['full_name']); ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <button type="submit" name="edit_course" class="btn btn-primary">Save Changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
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