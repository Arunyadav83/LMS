<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tutor_id = $_POST['tutor_id'];
    $user_id = $_POST['user_id'];
    $rating = $_POST['rating'];
    $review = mysqli_real_escape_string($conn, $_POST['review']);

    // Check if the user has already rated the tutor
    $check_query = "SELECT * FROM tutor_reviews WHERE tutor_id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt, "ii", $tutor_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error'] = "You have already submitted a rating for this tutor.";
        header("Location: course.php?id=" . $_POST['course_id']);
        exit();
    }

    // Insert the rating into the database
    $insert_query = "INSERT INTO tutor_reviews (tutor_id, user_id, rating, review) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, "iiis", $tutor_id, $user_id, $rating, $review);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Your rating has been submitted successfully.";
    } else {
        $_SESSION['error'] = "Failed to submit rating.";
    }

    header("Location: course.php?id=" . $_POST['course_id']);
    exit();
}
?>
