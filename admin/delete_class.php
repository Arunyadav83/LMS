<?php
session_start();
require_once '../config.php';
require_once '../functions.php';

// Check if the user is logged in and is a tutor
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'instructor') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $class_id = (int)$_GET['id'];
    
    // First delete associated records
    $queries = [
        "DELETE FROM quiz_completions WHERE class_id = ?", // Add this line to delete quiz_completions first
        "DELETE FROM quiz_results WHERE class_id = ?",
        "DELETE qa FROM quiz_answers qa 
         INNER JOIN quiz_questions qq ON qa.question_id = qq.id 
         WHERE qq.class_id = ?",
        "DELETE FROM quiz_questions WHERE class_id = ?",
    ];
    
    // Execute each cleanup query
    foreach ($queries as $query) {
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $class_id);
        mysqli_stmt_execute($stmt);
    }

    // Get video path before deleting class
    $video_query = "SELECT video_path FROM classes WHERE id = ?";
    $video_stmt = mysqli_prepare($conn, $video_query);
    mysqli_stmt_bind_param($video_stmt, "i", $class_id);
    mysqli_stmt_execute($video_stmt);
    $video_result = mysqli_stmt_get_result($video_stmt);
    $video_data = mysqli_fetch_assoc($video_result);
    $video_path = $video_data['video_path'] ?? '';

    // Delete the class
    $delete_query = "DELETE FROM classes WHERE id = ?";
    $delete_stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($delete_stmt, "i", $class_id);
    
    if (mysqli_stmt_execute($delete_stmt)) {
        // Delete associated video file if it exists
        if (!empty($video_path) && file_exists('../' . $video_path)) {
            unlink('../' . $video_path);
        }
        
        $_SESSION['success_message'] = "Class deleted successfully.";
        header("Location: classes.php?action=deleted");
        exit();
    } else {
        $_SESSION['error_message'] = "Failed to delete class: " . mysqli_error($conn);
        header("Location: classes.php");
        exit();
    }
} else {
    $_SESSION['error_message'] = "Invalid class ID.";
    header("Location: classes.php");
    exit();
}
