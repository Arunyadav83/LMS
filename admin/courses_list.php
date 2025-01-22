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

        $delete_payments_query = "DELETE FROM payments WHERE course_id = $course_id";
        if (!mysqli_query($conn, $delete_payments_query)) {
            echo "<div class='alert alert-danger'>Error deleting related payments: " . mysqli_error($conn) . "</div>";
        }
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

// Include SweetAlert2 script only once
echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';

// Handle update action
// Handle update action
if (isset($_POST['update_course'])) {
    $course_id = (int)$_POST['course_id'];
    $course_title = mysqli_real_escape_string($conn, $_POST['course_title']);
    $course_description = mysqli_real_escape_string($conn, $_POST['course_description']);
    $course_topics = mysqli_real_escape_string($conn, $_POST['course_topics']);
    $course_prize = (float)$_POST['course_prize'];
    $course_duration = mysqli_real_escape_string($conn, $_POST['course_duration']); // Handle duration as a string
    $tutor_id = isset($_POST['tutor_id']) ? (int)$_POST['tutor_id'] : null;

    // Handle image upload (optional)
    $image_path = null;
    if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
        $image_path = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }

    // Ensure course_id is valid and proceed with update
    if ($course_id > 0) {
        // Prepare the updated_at timestamp
        $updated_at = date('Y-m-d H:i:s');

        // Start building the update query dynamically
        $update_query_parts = [];

        // Add fields to update dynamically if they are not empty or null
        if (!empty($course_title)) {
            $update_query_parts[] = "title = '$course_title'";
        }
        if (!empty($course_description)) {
            $update_query_parts[] = "description = '$course_description'";
        }
        if (!empty($course_topics)) {
            $update_query_parts[] = "topics = '$course_topics'";
        }
        if ($course_prize > 0) {
            $update_query_parts[] = "course_prize = $course_prize";
        }
        if (!empty($course_duration)) { // Add the duration as a string if it's provided
            $update_query_parts[] = "duration = '$course_duration'";
        }
        if ($tutor_id !== null) {
            $update_query_parts[] = "tutor_id = $tutor_id";
        }

        // Always update the updated_at field
        $update_query_parts[] = "updated_at = '$updated_at'";

        // Add image_path if available
        if ($image_path !== null) {
            $update_query_parts[] = "image_path = '$image_path'";
        }

        // Join the parts with commas to form the SET clause
        $update_query = "UPDATE courses SET " . implode(", ", $update_query_parts) . " WHERE id = $course_id";

        // Execute the query
        if (mysqli_query($conn, $update_query)) {
            if (mysqli_affected_rows($conn) > 0) {
                $successMessage = "Course updated successfully.";
                $redirectURL = "courses_list.php";
                echo "<script>
                window.onload = function() {
                    Swal.fire({
                        title: 'Success',
                        text: " . json_encode($successMessage) . ", 
                        icon: 'success'
                    }).then(() => {
                        window.location.href = '$redirectURL';
                    });
                };
                </script>";
            } else {
                $warningMessage = "No changes made or course not found.";
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire('Warning', " . json_encode($warningMessage) . ", 'warning');
                    });
                </script>";
            }
        } else {
            $errorMessage = "Error updating course: " . mysqli_error($conn);
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire('Error', " . json_encode($errorMessage) . ", 'error');
                });
            </script>";
        }
    } else {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire('Error', 'Invalid course ID.', 'error');
            });
        </script>";
    }
}



// Fetch all courses with their topics
// Fetch all courses
$query = "SELECT id, title, description, course_prize, topics ,duration  FROM courses";


$result = mysqli_query($conn, $query);
$courses = mysqli_fetch_all($result, MYSQLI_ASSOC);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
} else {
    echo "No courses found.";
}
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
        margin-bottom: 20px;
        /* Adjust this value as needed */
    }

    /* General grid styling */
    #enrollmentGrid {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        /* Adjust spacing between cards */
    }

    /* Default: Three cards per row */
    #enrollmentGrid .col-md-4 {
        flex: 1 1 calc(33.333% - 16px);
        max-width: calc(33.333% - 16px);
    }

    /* Responsive styling for smaller screens (468px and below) */
    @media (max-width: 468px) {
        #enrollmentGrid .col-md-4 {
            flex: 1 1 100%;
            /* One card per row */
            max-width: 120%;
        }

        main {
            width: 90%;
            /* Adjust this value as needed */
            margin: 0 auto;
            /* Center the main element */
        }

        body {
            width: 100%;
        }


        #enrollmentGrid .card {
            margin-bottom: 16px;
            /* Add space between cards */
        }
    }

    /* Responsive styling for medium screens (789px and below) */
    @media (max-width: 789px) {
        #enrollmentGrid .col-md-4 {
            flex: 1 1 50%;
            /* Two cards per row */
            max-width: 150%;
        }
    }


    #enrollmentGrid .card {
        position: relative;
        /* Make the card a positioned element */
        height: 100%;
        /* Ensures cards stretch to the same height */
        display: flex;
        background-color: rgb(222, 222, 242);
        flex-direction: column;
        justify-content: space-evenly;
        /* Ensures proper spacing within the card */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border: none;
        /* Optional: Removes default border for cleaner design */
        border-radius: 8px;
        /* Optional: Adds rounded corners */
    }


    #enrollmentGrid .dropdown {
        position: absolute;
        top: 10px;
        /* Adjust spacing from the top */
        right: 10px;
        /* Adjust spacing from the right */
        z-index: 1;
        /* Ensure dropdown menu appears above other elements */
    }

    h3 {
        color: #16308b
    }

    main {
        background-color: rgb(244, 244, 255);
    }

    .dropdown-menu {
        z-index: 2;
        /* Ensure the dropdown appears above other elements */
    }

    #enrollmentGrid .card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .card-title {
        margin-bottom: 10px;
        font-size: 1.25rem;
    }

    .card-text {
        flex-grow: 1;
        /* Ensures equal spacing between title and topics */
        font-size: 0.9rem;
    }

    .navbar {
        background-color: #1a237e;
        margin: 0;
        padding: 0px 5px;
        /* Adjust padding for comfortable spacing */
        line-height: 1.2;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        /* Adds a subtle shadow */
        /* position: fixed; */
        /* Makes the navbar fixed */
        top: 0;
        /* Sticks to the top of the viewport */
        left: 0;
        width: 100%;
        /* Ensures the navbar spans the full width */
        z-index: 1000;
        /* Keeps the navbar above other elements */
    }

    .a {
        margin-left: 850px;
    }

    .navbar-brand {
        font-size: 23px;
        color: white;
    }

    .nav-link {
        color: white;
        padding-inline: 20px;
        text-decoration: underline;
    }

    .nav-link:hover {
        color: white;
    }

    .button {
        padding-inline: 30px;
        font-weight: bolder;
        text-decoration: none;
        color: #0433c3;
        padding-block: 10px;
        border-radius: 30px;
        transition: all 0.3s ease;
    }

    .button:hover {
        color: white;
        background-color: #0433c3;
        border-radius: 30px;
    }

    .description-preview {
        display: inline;
    }

    .full-description {
        display: inline;
        white-space: normal;
    }

    .read-more-link {
        color: #007bff;
        text-decoration: none;
        cursor: pointer;
    }

    .read-more-link:hover {
        text-decoration: underline;
    }

    /* Scrollable container for buttons */
    #buttonContainer {
        overflow-x: auto;
        /* Allow horizontal scrolling */
        display: flex;
        justify-content: center;
        /* Center the buttons */
        margin-bottom: 20px;
        /* Add space below the buttons */
    }

    #buttonContainer button {
        margin: 0 10px;
        /* Add space between buttons */
        min-width: 100px;
        /* Ensure buttons are wide enough to tap on small screens */
    }

    /* Adjust button styling */
    @media (max-width: 468px) {
        #buttonContainer {
            overflow-x: auto;
        }

        #buttonContainer button {
            margin: 0 5px;
            /* Reduce spacing on smaller screens */
        }

        main {
            max-width: 800px;
        }
    }

    @media (max-width: 768px) {

        #listView h2 {
            font-size: 1.5rem;
            top: 0;
            /* Reset position */
        }

        .table td,
        .table th {
            font-size: 0.875rem;
            /* Smaller font for better fit */
            white-space: normal;

            /* Prevent text overflow */
        }

        main {
            max-width: 800px;
        }

        .badge {
            font-size: 0.75rem;
            /* Smaller badge size */
        }

        .dropdown-menu {
            font-size: 0.875rem;
            /* Smaller dropdown text */
        }

        .a {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-right: 840px;

        }

        .table_list {
            overflow-x: auto;
            width: 100%;

        }
    }
</style>

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
                    <h3 class="mb-4">Courses List</h3>
                    <!-- Add this just before the table in the main content section -->
                    <div class="mb-3">
                        <a href="add_course.php" class="button">Add New Course</a>
                    </div>

                    <div style=" display: flex; justify-content: center; position:relative;bottom: 34px;margin-left: 185px;">
                        <div class="a">
                            <button id="listViewBtn" class="btn btn-primary me" onclick="showListView()">
                                <i class="fas fa-list"></i>
                            </button>
                            <button id="gridViewBtn" class="btn btn-secondary me2" onclick="showGridView()">
                                <i class="fas fa-th"></i>
                            </button>
                        </div>
                    </div>


                    <!-- List View -->
                    <div id="listView" class="view container mt-5">
                        <h2 class="mb-4">List View</h2>
                        <div class="table_list">
                            <table class="table table-striped table-bordered align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Topics</th>
                                        <th scope="col">Duration</th>
                                        <th scope="col" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($courses as $course): ?>
                                        <tr>
                                            <td data-label="ID"><?php echo htmlspecialchars($course['id']); ?></td>
                                            <td data-label="Title"><?php echo htmlspecialchars($course['title']); ?></td>
                                            <td data-label="Description"><?php echo htmlspecialchars($course['description']); ?></td>

                                            <td data-label="Topics">
                                                <?php
                                                $topics = explode(',', $course['topics']);
                                                foreach ($topics as $topic): ?>
                                                    <span class="badge bg-primary me-1"><?php echo htmlspecialchars(trim($topic)); ?></span>
                                                <?php endforeach; ?>
                                            </td>
                                            <td data-label="Duration"><?php echo htmlspecialchars($course['duration'] . " "); ?></td> <!-- Added Duration -->
                                            <td data-label="Actions" class="text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-light btn-sm" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                                        <li>
                                                            <button class="dropdown-item edit-btn" data-bs-toggle="modal" data-bs-target="#editModal"
                                                                data-id="<?php echo htmlspecialchars($course['id'] ?? ''); ?>"
                                                                data-title="<?php echo htmlspecialchars($course['title'] ?? ''); ?>"
                                                                data-description="<?php echo htmlspecialchars(substr($course['description'] ?? '', 0, 100)); ?>..."
                                                                data-topics="<?php echo htmlspecialchars($course['topics'] ?? ''); ?>"
                                                                data-prize="<?php echo htmlspecialchars($course['course_prize'] ?? ''); ?>"
                                                                data-duration="<?php echo htmlspecialchars($course['duration'] ?? ''); ?>">Edit</button>
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
                    </div>



                    <!-- Grid View -->
                    <div id="gridView" class="view">
                        <h2>Grid View</h2>
                        <div class="row g-4" id="enrollmentGrid">
                            <?php foreach ($courses as $course): ?>
                                <div class="col-12 col-sm-6 col-md-4 mb-4">
                                    <div class="card">
                                        <div class="card-body d-flex">
                                            <!-- Image on the left -->
                                            <img
                                                src="../assets/images/<?php echo htmlspecialchars($course['title']); ?>.jpg"
                                                alt="<?php echo htmlspecialchars($course['title']); ?>"
                                                class="rounded"
                                                style="width: 100px; height: 100px; object-fit: contain; margin-bottom: 10px;" />

                                            <!-- Card content -->
                                            <div>
                                                <div class="dropdown float-end">
                                                    <a class="btn btn-secondary" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                                        <li>
                                                            <button class="dropdown-item edit-btn" data-bs-toggle="modal" data-bs-target="#editModal"
                                                                data-id="<?php echo $course['id']; ?>"
                                                                data-title="<?php echo htmlspecialchars($course['title']); ?>"
                                                                data-description="<?php echo htmlspecialchars(substr($course['description'], 0, 100)); ?>..."
                                                                data-topics="<?php echo htmlspecialchars($course['topics']); ?>"
                                                                data-prize="<?php echo isset($course['course_prize']) ? htmlspecialchars($course['course_prize']) : ''; ?>"
                                                                data-duration="<?php echo htmlspecialchars($course['duration'] ?? ''); ?>"
                                                                >
                                                                Edit
                                                            </button>
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
                                                <p class="card-text">
                                                    <span class="description-preview" id="desc-<?php echo $course['id']; ?>">
                                                        <?php echo htmlspecialchars(substr($course['description'], 0, 100)); ?>...
                                                    </span>
                                                    <span class="full-description d-none" id="full-desc-<?php echo $course['id']; ?>">
                                                        <?php echo htmlspecialchars($course['description']); ?>
                                                    </span>

                                                    <!-- <a href="javascript:void(0);" class="read-more-link" onclick="toggleDescription('<?php echo $course['id']; ?>')">Read More</a> -->
                                                </p>
                                                <p class="card-text"><strong>Topics Covered:</strong> <?php echo htmlspecialchars($course['topics']); ?></p>
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
    <!-- Modal for editing course -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editCourseForm" method="post" action="">
                        <input type="hidden" name="course_id" id="course_id" value="<?php echo $course['id']; ?>">

                        <div class="mb-3">
                            <label for="course_title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="course_title" name="course_title" value="<?php echo $course['title']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="course_description" class="form-label">Description</label>
                            <textarea class="form-control" id="course_description" name="course_description" required><?php echo $course['description']; ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="course_topics" class="form-label">Topics</label>
                            <input type="text" class="form-control" id="course_topics" name="course_topics" value="<?php echo $course['topics']; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="course_duration" class="form-label">Course Duration:</label>
                            <input type="text" class="form-control" id="course_duration" name="course_duration"
                                value="<?php echo htmlspecialchars($course['duration'] ?? ''); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="course_prize" class="form-label">Course Price</label>
                            <input type="number" class="form-control" id="course_prize" name="course_prize" value="<?php echo $course['course_prize']; ?>" required>
                        </div>
                        <button type="submit" name="update_course" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- <script> -->
    <!-- document.addEventListener('DOMContentLoaded', () => {
            // Test Swal.fire to ensure it's working
            Swal.fire('Test', 'SweetAlert2 is loaded and working!', 'success');
        }); -->
    </script>
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

        function toggleDescription(courseId) {
            const preview = document.getElementById(`desc-${courseId}`);
            const fullDesc = document.getElementById(`full-desc-${courseId}`);
            const link = preview.nextElementSibling;

            if (preview.classList.contains('d-none')) {
                preview.classList.remove('d-none');
                fullDesc.classList.add('d-none');
                link.textContent = 'Read More';
            } else {
                preview.classList.add('d-none');
                fullDesc.classList.remove('d-none');
                link.textContent = 'Show Less';
            }
        }


        // Populate the edit modal with course data
        const editModal = document.getElementById('editModal');

        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget; // Button that triggered the modal
            const courseId = button.getAttribute('data-id');
            const courseTitle = button.getAttribute('data-title');
            const courseDescription = button.getAttribute('data-description');
            const courseTopics = button.getAttribute('data-topics');
            const coursePrize = button.getAttribute('data-prize');
            const courseDuration = button.getAttribute('data-duration'); // Retrieve the duration

            // Update the modal's content
            const modalTitle = editModal.querySelector('.modal-title');
            const courseIdInput = editModal.querySelector('#course_id');
            const courseTitleInput = editModal.querySelector('#course_title');
            const courseDescriptionInput = editModal.querySelector('#course_description');
            const courseTopicsInput = editModal.querySelector('#course_topics');
            const coursePrizeInput = editModal.querySelector('#course_prize');
            const courseDurationInput = editModal.querySelector('#course_duration'); // For duration field

            // Populate the modal fields
            courseIdInput.value = courseId;
            courseTitleInput.value = courseTitle;
            courseDescriptionInput.value = courseDescription;
            courseTopicsInput.value = courseTopics;
            coursePrizeInput.value = coursePrize;
            courseDurationInput.value = courseDuration; // Populate the duration field
        });


        // Ensure only one view is visible on load
        document.addEventListener('DOMContentLoaded', function() {
            showListView(); // or showGridView() based on your preference
        });
    </script>
</body>

</html>