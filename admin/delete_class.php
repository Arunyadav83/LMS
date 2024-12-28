<?php
session_start();
require_once '../config.php';
require_once '../functions.php';

// Check if the user is logged in and is a tutor
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'instructor') {
    header("Location: login.php");
    exit();
}
header("Location: classes.php?action=deleted");



// Perform the deletion logic
$id = $_GET['id']; // Assuming the ID is passed as a GET parameter

if ($id) {
    // Example deletion query
    $delete_query = "DELETE FROM classes WHERE id = $id";
    $result = mysqli_query($conn, $delete_query);

    if ($result) {
        // Redirect with the action parameter after a successful deletion
        header("Location: classes.php?action=deleted");
        exit; // Ensure no further processing
    } else {
        echo "Error deleting class: " . mysqli_error($conn); // Optional error handling
    }
}

if (isset($_GET['id'])) {
    $class_id = (int)$_GET['id'];
    $tutor_id = $_SESSION['user_id'];

    // First, check if the class belongs to the logged-in tutor
    $query = "SELECT * FROM classes WHERE id = ? AND tutor_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $class_id, $tutor_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // The class belongs to the tutor, proceed with deletion

        // Delete associated quiz results
        $query = "DELETE FROM quiz_results WHERE class_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $class_id);
        mysqli_stmt_execute($stmt);

        // Delete associated quiz questions and answers
        $query = "DELETE qa FROM quiz_answers qa
                  INNER JOIN quiz_questions qq ON qa.question_id = qq.id
                  WHERE qq.class_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $class_id);
        mysqli_stmt_execute($stmt);

        $query = "DELETE FROM quiz_questions WHERE class_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $class_id);
        mysqli_stmt_execute($stmt);

        // Delete the class
        $query = "DELETE FROM classes WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $class_id);
        mysqli_stmt_execute($stmt);

        // Optionally, delete the associated video file
        $class = mysqli_fetch_assoc($result);
        if (!empty($class['video_path']) && file_exists('../' . $class['video_path'])) {
            unlink('../' . $class['video_path']);
        }

        $_SESSION['success_message'] = "Class deleted successfully.";
    } else {
        $_SESSION['error_message'] = "You don't have permission to delete this class.";
    }
} else {
    $_SESSION['error_message'] = "Invalid class ID.";
}

// Redirect back to the classes page
header("Location: classes.php");
exit();