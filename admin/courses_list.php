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
    $delete_query = "DELETE FROM courses WHERE id = $course_id";
    mysqli_query($conn, $delete_query);
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
</head>
<style>
    .mb-4 {
        /* color: #f3899d;
        margin-left: 10px;
        color:; */
    }
    h1
    {
        color:navy;
    }
    .card-title {
        color: black;
    }
    body {
        background-color:lightblue;
        border-radius: 10px;
        
    }
    .btn-navy {
    background-color: navy;
    color: white;
    border: none;
}
.btn-navy:hover {
    background-color: #fff;
    color: navy;
     /* A darker shade of navy for hover effect */
}
.card {
    min-height: 200px; /* Set a minimum height for the cards */
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
                        <a href="add_course.php" class="btn btn-navy">Add New Course</a>
                    </div>

                    <!-- Course List (Replaced with Cards) -->
                    <div class="row">
                        <?php foreach ($courses as $course): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title text-black"><?php echo htmlspecialchars($course['title']); ?></h5>
                                    <div class="position-relative" style="float: right; margin-top: -38px;background-color: #f3899d;">
                                            <button class="btn btn-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="edit_course.php?id=<?php echo $course['id']; ?>"><i class="fas fa-pencil-alt"></i> Edit</a></li>
                                                <li>
                                                    <form action="" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this course?');">
                                                        <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                                                        <button type="submit" name="delete_course" class="dropdown-item"><i class="fas fa-trash"></i> Delete</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    <p class="card-text"><?php echo htmlspecialchars($course['description']); ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="card-text"><strong>Topics Covered:</strong> <?php echo htmlspecialchars($course['topics']); ?></p>
                                        <!-- <div class="position-relative">
                                            <button class="btn btn-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="edit_course.php?id=<?php echo $course['id']; ?>">Edit</a></li>
                                                <li>
                                                    <form action="" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this course?');">
                                                        <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                                                        <button type="submit" name="delete_course" class="dropdown-item">Delete</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>