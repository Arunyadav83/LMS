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
    <title>Courses List - Ultrakey LMS</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/sidebar.css">
    <style>
        /* Common Action Button Styles */
        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        
        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .edit-btn {
            background-color: #ffc107;
            color: #000;
        }
        
        .delete-btn {
            background-color: #dc3545;
            color: #fff;
        }
        
        .action-btn:hover {
            opacity: 0.8;
            transform: translateY(-1px);
        }

        /* View Toggle Buttons */
        .view-toggle {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            margin-top: 20px;
        }

        .view-btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .view-btn.active {
            background: #1a69a5;
            color: white;
        }

        /* Grid View Styles */
        .grid-view {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px 0;
        }

        .course-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .course-card:hover {
            transform: translateY(-5px);
        }

        .course-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-bottom: 1px solid #eee;
        }

        .course-content {
            padding: 15px;
        }

        .course-title {
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: #1a69a5;
        }

        .course-description {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 10px;
        }

        .course-topics {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-bottom: 15px;
        }

        .topic-badge {
            background: #e9ecef;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            color: #495057;
        }

        .card-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .grid-view {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }

        @media (max-width: 576px) {
            .grid-view {
                grid-template-columns: 1fr;
            }
        }

        /* Table Styles */
        .table th {
            background-color: #1a69a5;
            color: white;
        }

        .table-responsive {
            margin-top: 20px;
        }

        /* Add Course Button */
        .add-course-btn {
            background: #2674b7;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .add-course-btn:hover {
            background: #1a5c8f;
            color: white;
        }

        /* Hide/Show Views */
        .view-container {
            display: none;
        }

        .view-container.active {
            display: block;
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
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
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

        /* Add these new styles */
        .course-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .course-title {
            margin: 0;
            flex: 1;
        }

        .course-header .kebab-menu {
            margin-left: 10px;
        }

        .course-header .kebab-button {
            padding: 0 5px;
        }

        .course-header .popup-menu {
            right: 0;
            top: 100%;
            margin-top: 5px;
        }

        /* Responsive styles for list view */
        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
            }

            .table td, .table th {
                min-width: 100px;
                white-space: normal;
            }

            .table td:first-child,
            .table th:first-child {
                position: sticky;
                left: 0;
                background: white;
                z-index: 1;
            }

            .kebab-menu {
                position: static;
            }

            .popup-menu {
                position: fixed;
                left: 50%;
                top: 50%;
                transform: translate(-50%, -50%);
                width: 200px;
                box-shadow: 0 0 15px rgba(0,0,0,0.2);
            }
        }

        /* Responsive styles for grid view */
        .course-card {
            width: 100%;
            margin-bottom: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .course-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .course-content {
            padding: 15px;
        }

        .course-description {
            margin: 10px 0;
            font-size: 14px;
            color: #666;
        }

        .course-topics {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin: 10px 0;
        }

        .topic-badge {
            background: #f0f0f0;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }

        .course-duration {
            font-size: 14px;
            color: #666;
        }

        /* Grid view responsive breakpoints */
        @media (min-width: 576px) {
            .grid-view {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }
        }

        @media (min-width: 992px) {
            .grid-view {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 575px) {
            .grid-view {
                display: grid;
                grid-template-columns: 1fr;
                gap: 15px;
                padding: 10px;
            }

            .course-card {
                margin-bottom: 15px;
            }

            .course-image {
                height: 180px;
            }

            .course-header {
                flex-direction: row;
                align-items: center;
            }

            .course-title {
                font-size: 18px;
            }

            .course-description {
                font-size: 13px;
            }

            .topic-badge {
                font-size: 11px;
            }
        }

        /* Common responsive styles */
        .view-toggle {
            flex-wrap: wrap;
            gap: 10px;
        }

        @media (max-width: 480px) {
            .container {
                padding: 10px;
            }

            .popup-menu {
                min-width: 150px;
            }

            .popup-menu a {
                padding: 12px 15px;
                font-size: 14px;
            }
        }

        /* Add these styles for responsive table */
        @media (max-width: 768px) {
            .hide-on-mobile {
                display: none !important;
            }

            .table td, .table th {
                min-width: 100px;
                white-space: normal;
                font-size: 14px;
                padding: 10px 8px;
            }

            .table td:first-child,
            .table th:first-child {
                position: static;
                background: none;
            }
        }
        .grid-view {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); /* Responsive columns */
    gap: 20px; /* Space between cards */
    padding: 20px; /* Padding around the grid */
}

.course-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.course-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
}

.course-image {
    width: 100%;
    height: 150px;
    object-fit: cover; /* Maintain aspect ratio */
    border-bottom: 2px solid #eee; /* Bottom border for image */
}

.course-content {
    padding: 15px; /* Inner padding */
}

.course-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.course-title {
    font-size: 1.2rem;
    margin: 0;
    color: #333; /* Title color */
}

.kebab-menu {
    position: relative;
}

.kebab-button {
    background: transparent;
    border: none;
    cursor: pointer;
}

.kebab-dot {
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: #333;
    margin: 2px 0;
}

.course-description {
    font-size: 0.9rem;
    color: #666; /* Description color */
    margin: 10px 0;
}

.course-topics {
    display: flex;
    flex-wrap: wrap;
    gap: 5px; /* Space between topic badges */
    margin-bottom: 10px;
}

.topic-badge {
    background: #e9ecef; /* Badge background */
    padding: 4px 8px; /* Badge padding */
    border-radius: 4px; /* Rounded corners */
    font-size: 0.8rem;
    color: #495057; /* Badge text color */
}

.course-duration {
    color: #7f8c8d; /* Duration color */
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 5px; /* Space between icon and text */
}
        
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Courses List</h2>
                <div class="d-flex gap-3 align-items-center">
                    <div class="view-toggle">
                        <button class="view-btn active" data-view="list">
                            <i class="fas fa-list"></i> List
                        </button>
                        <button class="view-btn" data-view="grid">
                            <i class="fas fa-th"></i> Grid
                        </button>
                    </div>
                    <a href="add_course.php" class="add-course-btn">
                        <i class="fas fa-plus"></i> Add New Course
                    </a>
                </div>
            </div>

            <!-- List View -->
            <div id="listView" class="view-container active">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="hide-on-mobile">ID</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Topics</th>
                                        <th>Duration</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetch courses from database
                                    $query = "SELECT * FROM courses ORDER BY id DESC";
                                    $result = mysqli_query($conn, $query);
                                    
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td class='hide-on-mobile'>{$row['id']}</td>";
                                        echo "<td>{$row['title']}</td>";
                                        echo "<td>" . substr($row['description'], 0, 100) . "...</td>";
                                        echo "<td>{$row['topics']}</td>";
                                        echo "<td>{$row['duration']}</td>";
                                        echo "<td>
                                                <div class='kebab-menu'>
                                                    <button class='kebab-button' onclick='toggleMenu(this)'>
                                                        <div class='kebab-dot'></div>
                                                        <div class='kebab-dot'></div>
                                                        <div class='kebab-dot'></div>
                                                    </button>
                                                    <div class='popup-menu'>
                                                        <a href='#' onclick='editCourse({$row['id']})'>Edit</a>
                                                        <a href='#' onclick='deleteCourse({$row['id']})'>Delete</a>
                                                    </div>
                                                </div>
                                              </td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grid View -->
            <div id="gridView" class="view-container">
    <div class="grid-view">
        <?php
        // Reset the result pointer
        mysqli_data_seek($result, 0);
        
        while ($row = mysqli_fetch_assoc($result)) {
            $topics = explode(',', $row['topics']);
            echo "<div class='course-card' id='course-{$row['id']}'>
                    <img src='../assets/images/{$row['title']}.jpg' alt='{$row['title']}' class='course-image' onerror=\"this.src='../assets/images/default-course.jpg'\">
                    <div class='course-content'>
                        <div class='course-header'>
                            <h3 class='course-title'>{$row['title']}</h3>
                            <div class='kebab-menu'>
                                <button class='kebab-button' onclick='toggleMenu(this)'>
                                    <div class='kebab-dot'></div>
                                    <div class='kebab-dot'></div>
                                    <div class='kebab-dot'></div>
                                </button>
                                <div class='popup-menu'>
                                    <a href='#' onclick='editCourse({$row['id']})'>Edit</a>
                                    <a href='#' onclick='deleteCourse({$row['id']})'>Delete</a>
                                </div>
                            </div>
                        </div>
                        <p class='course-description'>" . substr($row['description'], 0, 100) . "...</p>
                        <div class='course-topics'>";
                        foreach ($topics as $topic) {
                            echo "<span class='topic-badge'>" . trim($topic) . "</span>";
                        }
            echo    "</div>
                        <div class='course-duration'>
                            <i class='fas fa-clock'></i> {$row['duration']}
                        </div>
                    </div>
                </div>";
        }
        ?>
    </div>
</div>
        </div>
    </div>

    <!-- Bootstrap JS and its dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // View Toggle Functionality
        const viewButtons = document.querySelectorAll('.view-btn');
        const viewContainers = document.querySelectorAll('.view-container');

        viewButtons.forEach(button => {
            button.addEventListener('click', () => {
                const view = button.dataset.view;
                
                // Update buttons
                viewButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                // Update views
                viewContainers.forEach(container => {
                    container.classList.remove('active');
                    if (container.id === view + 'View') {
                        container.classList.add('active');
                    }
                });
            });
        });

        function toggleMenu(button) {
            // Close all other open menus
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
                document.querySelectorAll('.popup-menu').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });

        function editCourse(courseId) {
            // Add your edit course logic here
            window.location.href = 'edit_course.php?id=' + courseId;
        }

        function deleteCourse(courseId) {
            // Add your delete course logic here
            if(confirm('Are you sure you want to delete this course?')) {
                window.location.href = 'delete_course.php?id=' + courseId;
            }
        }
    </script>
</body>
</html>