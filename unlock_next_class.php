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

$class_id = (int)$_POST['class_id'];
$course_id = (int)$_POST['course_id'];

// Unlock the next class
$unlock_next_class_query = "UPDATE classes SET is_unlocked = TRUE 
                            WHERE course_id = ? 
                            AND id > ?
                            AND is_unlocked = FALSE
                            ORDER BY id ASC LIMIT 1";
$unlock_next_class_stmt = mysqli_prepare($conn, $unlock_next_class_query);
mysqli_stmt_bind_param($unlock_next_class_stmt, "ii", $course_id, $class_id);

if (mysqli_stmt_execute($unlock_next_class_stmt)) {
    $affected_rows = mysqli_stmt_affected_rows($unlock_next_class_stmt);
    if ($affected_rows > 0) {
        $_SESSION['success'] = "The next lesson has been unlocked.";
    } else {
        $_SESSION['success'] = "No next lesson to unlock. You've completed all available lessons.";
    }
} else {
    $_SESSION['error'] = "There was an error unlocking the next lesson. Please try again or contact support.";
}

header("Location: course.php?id=" . $course_id);
exit();