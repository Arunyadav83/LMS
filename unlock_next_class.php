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

// Check if the user has passed the quiz for the current class
$quiz_query = "SELECT percentage FROM quiz_results WHERE user_id = ? AND class_id = ? ORDER BY submitted_at DESC LIMIT 1";
$quiz_stmt = mysqli_prepare($conn, $quiz_query);
mysqli_stmt_bind_param($quiz_stmt, "ii", $user_id, $class_id);
mysqli_stmt_execute($quiz_stmt);
$quiz_data = mysqli_fetch_assoc(mysqli_stmt_get_result($quiz_stmt));

if ($quiz_data && $quiz_data['percentage'] >= 70) {
    // Get the next class to unlock
    $next_class_query = "SELECT id FROM classes WHERE course_id = ? AND id > ? ORDER BY id ASC LIMIT 1";
    $next_class_stmt = mysqli_prepare($conn, $next_class_query);
    mysqli_stmt_bind_param($next_class_stmt, "ii", $course_id, $class_id);
    mysqli_stmt_execute($next_class_stmt);
    $next_class = mysqli_fetch_assoc(mysqli_stmt_get_result($next_class_stmt));

    if ($next_class) {
        // Unlock the next class in the `class_progress` table
        $unlock_query = "INSERT INTO class_progress (user_id, class_id, is_unlocked) VALUES (?, ?, 1) 
                         ON DUPLICATE KEY UPDATE is_unlocked = 1";
        $unlock_stmt = mysqli_prepare($conn, $unlock_query);
        mysqli_stmt_bind_param($unlock_stmt, "ii", $user_id, $next_class['id']);
        if (mysqli_stmt_execute($unlock_stmt)) {
            $_SESSION['success'] = "The next lesson has been unlocked!";
        } else {
            $_SESSION['error'] = "Failed to unlock the next lesson. Try again.";
        }
    } else {
        $_SESSION['success'] = "You've completed all lessons!";
    }
} else {
    $_SESSION['error'] = "You must pass the quiz with at least 70% to unlock the next lesson.";
}

header("Location: course.php?id=" . $course_id);
exit();
