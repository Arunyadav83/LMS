<?php
require_once 'config.php';
require_once 'functions.php';
include 'header.php';

$message = '';
$error = '';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $error = "You must be logged in to enroll in a course.";
} else {
    $user_id = $_SESSION['user_id']; // Use the session user ID

    // Check if the user exists
    $user_check_query = "SELECT * FROM users WHERE id = ?";
    $user_check_stmt = mysqli_prepare($conn, $user_check_query);
    mysqli_stmt_bind_param($user_check_stmt, "i", $user_id);
    mysqli_stmt_execute($user_check_stmt);
    $user_check_result = mysqli_stmt_get_result($user_check_stmt);

    if (mysqli_num_rows($user_check_result) === 0) {
        $error = "The user ID you're trying to enroll with does not exist in the users table.";
    } else {
        // Check if a course_id is provided
        if (!isset($_GET['course_id'])) {
            $error = "No course selected for enrollment.";
        } else {
            $course_id = (int)$_GET['course_id'];

            // Check if the user is already enrolled in the course
            $check_query = "SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?";
            $check_stmt = mysqli_prepare($conn, $check_query);
            mysqli_stmt_bind_param($check_stmt, "ii", $user_id, $course_id);
            mysqli_stmt_execute($check_stmt);
            $check_result = mysqli_stmt_get_result($check_stmt);

            if (mysqli_num_rows($check_result) > 0) {
                $message = "You are already enrolled in this course.";
            } else {
                // Fetch course details
                $course_query = "SELECT title, tutor_id FROM courses WHERE id = ?";
                $course_stmt = mysqli_prepare($conn, $course_query);
                mysqli_stmt_bind_param($course_stmt, "i", $course_id);
                mysqli_stmt_execute($course_stmt);
                $course_result = mysqli_stmt_get_result($course_stmt);
                $course = mysqli_fetch_assoc($course_result);

                if (!$course) {
                    $error = "Invalid course selected.";
                } else {
                    // Enroll in the course
                    $enroll_query = "INSERT INTO enrollments (user_id, course_id, course_name, tutor_id, enrolled_at) VALUES (?, ?, ?, ?, NOW())";
                    $enroll_stmt = mysqli_prepare($conn, $enroll_query);
                    mysqli_stmt_bind_param($enroll_stmt, "iisi", $user_id, $course_id, $course['title'], $course['tutor_id']);

                    if (mysqli_stmt_execute($enroll_stmt)) {
                        $message = "You have successfully enrolled in the course: " . $course['title'];
                    } else {
                        $error = "There was an error enrolling in the course. Please try again. Error: " . mysqli_error($conn);
                    }
                }
            }
        }
    }
}
?>

<div class="container mt-4">
    <h2>Course Enrollment</h2>
    <?php if ($message): ?>
        <div class="alert alert-success" role="alert">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    <a href="index.php" class="btn btn-primary">Back to Courses</a>
</div>

<?php include 'footer.php'; ?>