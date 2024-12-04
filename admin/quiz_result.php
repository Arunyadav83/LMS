<?php
session_start();
require_once '../config.php';
require_once '../functions.php';

// Check if the user is logged in as an admin
if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

$current_page = 'quiz_results';

// Handle search and filter
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$tutor_filter = isset($_GET['tutor']) ? mysqli_real_escape_string($conn, $_GET['tutor']) : '';
$course_filter = isset($_GET['course']) ? mysqli_real_escape_string($conn, $_GET['course']) : '';

// Fetch all quiz results with search and filter
$query = "SELECT qr.*, u.username, c.class_name, co.title as course_title
          FROM quiz_results qr
          JOIN users u ON qr.user_id = u.id
          JOIN classes c ON qr.class_id = c.id
          JOIN courses co ON c.course_id = co.id
          WHERE (u.username LIKE '%$search%' OR c.class_name LIKE '%$search%' OR qr.tutor_name LIKE '%$search%')";

if ($tutor_filter) {
    $query .= " AND qr.tutor_name = '$tutor_filter'";
}

if ($course_filter) {
    $query .= " AND co.title = '$course_filter'";
}

$query .= " ORDER BY qr.submitted_at DESC";

$result = mysqli_query($conn, $query);

// Fetch unique tutors and courses for filter dropdowns
$tutors_query = "SELECT DISTINCT tutor_name FROM quiz_results ORDER BY tutor_name";
$tutors_result = mysqli_query($conn, $tutors_query);

$courses_query = "SELECT DISTINCT title FROM courses ORDER BY title";
$courses_result = mysqli_query($conn, $courses_query);

// Handle Excel export
if (isset($_GET['export']) && $_GET['export'] == 'excel') {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="quiz_results.xls"');
    header('Cache-Control: max-age=0');
    
    echo "ID\tStudent\tClass\tCourse\tTutor\tScore\tTotal Questions\tPercentage\tSubmitted At\n";
    
    mysqli_data_seek($result, 0);
    while ($row = mysqli_fetch_assoc($result)) {
        echo implode("\t", [
            $row['id'],
            $row['username'],
            $row['class_name'],
            $row['course_title'],
            $row['tutor_name'],
            $row['score'],
            $row['total_questions'],
            number_format($row['percentage'], 2) . '%',
            $row['submitted_at']
        ]) . "\n";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Results - LMS Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                    <h1 class="mb-4">Quiz Results</h1>

                    <!-- Search and Filter Form -->
                    <form method="GET" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
                            </div>
                            <div class="col-md-3">
                                <select name="tutor" class="form-select">
                                    <option value="">All Tutors</option>
                                    <?php while ($tutor = mysqli_fetch_assoc($tutors_result)): ?>
                                        <option value="<?php echo htmlspecialchars($tutor['tutor_name']); ?>" <?php echo $tutor_filter == $tutor['tutor_name'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($tutor['tutor_name']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="course" class="form-select">
                                    <option value="">All Courses</option>
                                    <?php while ($course = mysqli_fetch_assoc($courses_result)): ?>
                                        <option value="<?php echo htmlspecialchars($course['title']); ?>" <?php echo $course_filter == $course['title'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($course['title']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                            </div>
                        </div>
                    </form>

                    <!-- Export to Excel Button -->
                    <a href="?export=excel<?php echo $search ? '&search=' . urlencode($search) : ''; echo $tutor_filter ? '&tutor=' . urlencode($tutor_filter) : ''; echo $course_filter ? '&course=' . urlencode($course_filter) : ''; ?>" class="btn btn-success mb-3">Export to Excel</a>

                    <!-- Quiz Results Table -->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Student</th>
                                <th>Class</th>
                                <th>Course</th>
                                <th>Tutor</th>
                                <th>Score</th>
                                <th>Total Questions</th>
                                <th>Percentage</th>
                                <th>Submitted At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row['class_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['course_title']); ?></td>
                                    <td><?php echo htmlspecialchars($row['tutor_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['score']); ?></td>
                                    <td><?php echo htmlspecialchars($row['total_questions']); ?></td>
                                    <td><?php echo number_format($row['percentage'], 2); ?>%</td>
                                    <td><?php echo htmlspecialchars($row['submitted_at']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>