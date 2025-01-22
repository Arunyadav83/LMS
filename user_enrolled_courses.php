<?php
// Start session and include necessary files
session_start();
require_once 'config.php'; // Database connection file

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Fetch enrolled courses for the user
$query = "
    SELECT e.course_id, e.status, e.enrolled_at, c.title, c.duration, 
           t.username AS tutor_name
    FROM enrollments e
    JOIN courses c ON e.course_id = c.id
    JOIN tutors t ON c.tutor_id = t.id
    WHERE e.user_id = ?";

// Prepare the statement
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Query Preparation Failed: " . $conn->error);
}

// Bind the parameter
$stmt->bind_param("i", $user_id);

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

// Close the statement
$stmt->close();
?>

<?php
include 'header.php'?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Enrolled Courses</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .course-card {
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .course-image {
            height: 200px;
            object-fit: cover;
        }
        .progress-bar {
            background-color: #007bff;
        }
        .card-body {
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4" style="margin-top: 132px;">My Enrolled Courses</h2>
        <div class="row">
            <?php if (!empty($courses)) : ?>
                <?php foreach ($courses as $course) : ?>
                    <div class="col-md-4">
                        <div class="card course-card">
                        <img
                            src="assets/images/<?php echo htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8'); ?>.jpg"
                            class="card-img-top img-fluid"
                            style="max-height: 150px; object-fit: cover;"
                            alt="<?php echo htmlspecialchars($course['title']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                                <p class="card-text">
                                    <strong>Duration:</strong> <?php echo htmlspecialchars($course['duration']); ?><br>
                                    <!-- <strong>Videos:</strong> <?php echo htmlspecialchars($course['videos_count']); ?><br> -->
                                    <strong>Tutor:</strong> <?php echo htmlspecialchars($course['tutor_name']); ?><br>
                                    <strong>Enrolled At:</strong> <?php echo htmlspecialchars($course['enrolled_at']); ?><br>
                                </p>
                                <div class="progress mb-3">
                                    <div class="progress-bar" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                        50% Completed
                                    </div>
                                </div>
                                <a href="courses.php?course_id=<?php echo $course['course_id']; ?>" class="btn btn-primary w-100">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="text-center">You haven't enrolled in any courses yet.</p>
            <?php endif; ?>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php
include 'footer.php'?>
</html>
