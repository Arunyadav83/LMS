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
        
        // Debugging output
        echo "Title ID: $title_id, Tutor ID: $tutor_id<br>";
        
        // Check if tutor_id exists in the users table
        $tutorCheckQuery = "SELECT id FROM tutors
       WHERE id = $tutor_id";
        $tutorCheckResult = mysqli_query($conn, $tutorCheckQuery);
        
        // Check if title_id exists in the courses table
        $titleCheckQuery = "SELECT id FROM courses WHERE id = $title_id";
        $titleCheckResult = mysqli_query($conn, $titleCheckQuery);

        if (mysqli_num_rows($tutorCheckResult) > 0 && mysqli_num_rows($titleCheckResult) > 0) {
            // Insert the new course with the selected tutor
            $query = "INSERT INTO courses (title, description, tutor_id) 
                      VALUES ((SELECT title FROM courses WHERE id = $title_id LIMIT 1), '$description', $tutor_id)";
            
            // Debugging output for the query
            echo "Executing Query: $query<br>";

            if (mysqli_query($conn, $query)) {
                echo "Course added successfully.";
            } else {
                echo "Error: " . mysqli_error($conn); // Show the SQL error
            }
        } else {
            echo "Invalid tutor or title selected.";
        }
    } elseif (isset($_POST['edit_course'])) {
        $id = (int)$_POST['id'];
        $title_id = (int)$_POST['title_id'];
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $tutor_id = (int)$_POST['tutor_id']; // Ensure this is being set correctly
        $new_tutor_name = mysqli_real_escape_string($conn, $_POST['new_tutor_name'] ?? ''); // New tutor name

        // Debugging output
        echo "Editing Course ID: $id, Title ID: $title_id, Description: $description, Tutor ID: $tutor_id, New Tutor Name: $new_tutor_name<br>";

        // Check if tutor_id exists in the users table
        $tutorCheckQuery = "SELECT id FROM users WHERE id = $tutor_id";
        $tutorCheckResult = mysqli_query($conn, $tutorCheckQuery);
        $tutorExists = mysqli_num_rows($tutorCheckResult) > 0;

        if ($tutorExists) {
            $query = "UPDATE courses 
                      SET title = (SELECT title FROM courses WHERE id = $title_id), 
                          description = '$description', 
                          tutor_id = $tutor_id 
                      WHERE id = $id";
            if (!mysqli_query($conn, $query)) {
                echo "Error: " . mysqli_error($conn);
            }
        } elseif (!empty($new_tutor_name)) {
            // Insert new tutor if provided
            $insertTutorQuery = "INSERT INTO users (full_name) VALUES ('$new_tutor_name')";
            if (mysqli_query($conn, $insertTutorQuery)) {
                $new_tutor_id = mysqli_insert_id($conn); // Get the new tutor's ID
                // Now update the course with the new tutor
                $query = "UPDATE courses 
                          SET title = (SELECT title FROM courses WHERE id = $title_id), 
                              description = '$description', 
                              tutor_id = $new_tutor_id 
                          WHERE id = $id";
                if (!mysqli_query($conn, $query)) {
                    echo "Error: " . mysqli_error($conn);
                }
            } else {
                echo "Error adding new tutor: " . mysqli_error($conn);
            }
        } else {
            echo "Please select a tutor or add a new one.";
        }
    } elseif (isset($_POST['delete_course'])) {
        $id = (int)$_POST['id'];
        $query = "DELETE FROM courses WHERE id=$id";
        if (!mysqli_query($conn, $query)) {
            echo "Error: " . mysqli_error($conn);
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
        .list-view, .grid-view {
            display: none; /* Hide both views by default */
        }
        .active {
            display: block; /* Show the active view */
        }
        /* Add styles for the Add New Course section */
        #a {
            color:  navy ; /* Change the color of the heading */
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
        body {
            background-color: lightblue;
        }
        .btn-navy {
            background-color:navy; /* Change to a darker purple */
            color: #E6E6FA; /* Light lavender text color */
            border: none; /* Remove border */
            padding: 10px 20px; /* Add padding for a better look */
            border-radius: 5px; /* Rounded corners */
            font-size: 1rem; /* Adjust font size */
        }
        .btn-navy:hover {
            background-color: #E6E6FA; /* Light lavender on hover */
            color: #4B0082; /* Dark purple text on hover */
            border: none; /* Keep border removed on hover */
        }
        /* Add styles for the view toggle buttons */
        /* .view-toggle {
            margin-bottom: 20px;
        }
        .view-toggle button {
            margin-right: 10px;
        }
        .active {
            background-color: blue; /* Active button color */
            color: white; /* Active button text color */
        }
        .inactive {
            background-color: gray; /* Inactive button color */
            color: black; /* Inactive button text color */
        } */
     </style>
    <script>
        function showListView() {
            document.getElementById('listView').classList.add('active');
            document.getElementById('gridView').classList.remove('active');
            document.getElementById('listViewBtn').classList.add('btn-primary');
            document.getElementById('listViewBtn').classList.remove('btn-secondary');
            document.getElementById('gridViewBtn').classList.add('btn-secondary');
            document.getElementById('gridViewBtn').classList.remove('btn-primary');
        }

        function showGridView() {
            document.getElementById('gridView').classList.add('active');
            document.getElementById('listView').classList.remove('active');
            document.getElementById('gridViewBtn').classList.add('btn-primary');
            document.getElementById('gridViewBtn').classList.remove('btn-secondary');
            document.getElementById('listViewBtn').classList.add('btn-secondary');
            document.getElementById('listViewBtn').classList.remove('btn-primary');
        }
    </script>
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

                    <!-- View Toggle Buttons -->
                    <div class="mb-3">
                        <button id="listViewBtn" class="btn btn-secondary" onclick="showListView()">List View</button>
                        <button id="gridViewBtn" class="btn btn-secondary" onclick="showGridView()">Grid View</button>
                    </div>

                    <!-- List View -->
                    <div id="listView" class="list-view active">
                        <h2>Courses List</h2>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Tutor</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($courses as $course): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($course['title']); ?></td>
                                    <td><?php echo htmlspecialchars($course['description']); ?></td>
                                    <td><?php echo htmlspecialchars($course['tutor_name']); ?></td>
                                    <td>
                                        <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#editCourse<?php echo $course['id']; ?>">Edit</button>
                                        <form action="" method="post" class="d-inline">
                                            <input type="hidden" name="id" value="<?php echo $course['id']; ?>">
                                            <button type="submit" name="delete_course" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this course?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Grid View -->
                    <div id="gridView" class="grid-view">
                        <h2>Courses Grid</h2>
                        <div class="row">
                            <?php foreach ($courses as $course): ?>
                            <div class="col-md-4 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                                        <p class="card-text"><?php echo htmlspecialchars($course['description']); ?></p>
                                        <p class="card-text"><strong>Tutor:</strong> <?php echo htmlspecialchars($course['tutor_name']); ?></p>
                                        <div class="dropdown" style="position: absolute; right: 10px; top: 10px;">
                                            <button class="btn btn-secondary" type="button" id="dropdownMenuButton<?php echo $course['id']; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton<?php echo $course['id']; ?>">
                                                <li>
                                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editCourse<?php echo $course['id']; ?>">
                                                        <i class="fas fa-pencil-alt"></i> Edit
                                                    </button>
                                                </li>
                                                <li>
                                                    <form action="" method="post" class="d-inline">
                                                        <input type="hidden" name="id" value="<?php echo $course['id']; ?>">
                                                        <button type="submit" name="delete_course" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this course?')">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
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

    <!-- Edit Course Modal -->
    <?php foreach ($courses as $course): ?>
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
                            <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#addTutorModal">Add New Tutor</button>
                        </div>
                        <button type="submit" name="edit_course" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>