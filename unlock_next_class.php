<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!is_logged_in()) {
    $_SESSION['error'] = "You must be logged in to unlock classes.";
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: index.php");
    exit();
}

$class_id = (int)$_POST['class_id'] ?? 0;
$course_id = (int)$_POST['course_id'] ?? 0;
$user_id = $_SESSION['user_id'] ?? 0;

if ($class_id === 0 || $course_id === 0) {
    $_SESSION['error'] = "Invalid class or course ID.";
    header("Location: index.php");
    exit();
}

// Check if user has completed the quiz for current class
$check_quiz_query = "SELECT COUNT(*) as quiz_count, AVG(percentage) as avg_score 
                     FROM quiz_results 
                     WHERE user_id = ? AND class_id = ?";

$check_quiz_stmt = mysqli_prepare($conn, $check_quiz_query);
mysqli_stmt_bind_param($check_quiz_stmt, "ii", $user_id, $class_id);
mysqli_stmt_execute($check_quiz_stmt);
$quiz_result = mysqli_stmt_get_result($check_quiz_stmt);
$quiz_data = mysqli_fetch_assoc($quiz_result);

// Verify quiz completion and passing score (e.g., 70%)
if ($quiz_data['quiz_count'] > 0 && $quiz_data['avg_score'] >= 70) {
    // Get the next class that's not unlocked
    $next_class_query = "SELECT id FROM classes 
                        WHERE course_id = ? 
                        AND id > ? 
                        AND is_unlocked = FALSE 
                        ORDER BY id ASC LIMIT 1";
    $next_class_stmt = mysqli_prepare($conn, $next_class_query);
    mysqli_stmt_bind_param($next_class_stmt, "ii", $course_id, $class_id);
    mysqli_stmt_execute($next_class_stmt);
    $next_class_result = mysqli_stmt_get_result($next_class_stmt);
    
    if ($next_class = mysqli_fetch_assoc($next_class_result)) {
        // Unlock the next class
        $unlock_query = "UPDATE classes SET is_unlocked = TRUE WHERE id = ?";
        $unlock_stmt = mysqli_prepare($conn, $unlock_query);
        mysqli_stmt_bind_param($unlock_stmt, "i", $next_class['id']);

        if (mysqli_stmt_execute($unlock_stmt)) {
            $_SESSION['success'] = "Congratulations! The next lesson has been unlocked.";
        } else {
            $_SESSION['error'] = "Error unlocking next lesson: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['success'] = "You've completed all available lessons in this course!";
    }
} else {
    $_SESSION['error'] = "Please complete the quiz with a passing score (70% or higher) to unlock the next lesson.";
}

header("Location: course.php?id=" . $course_id);
exit();
