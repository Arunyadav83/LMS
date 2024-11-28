<!-- <?php
require_once 'config.php';
require_once 'functions.php';

// Check if class_id is provided
if (isset($_POST['class_id'])) {
    $class_id = (int)$_POST['class_id'];
    $user_id = $_SESSION['user_id']; // Assuming you have user ID stored in session

    // Update the database to unlock the class
    $unlock_query = "UPDATE classes SET is_unlocked = 1 WHERE id = ? AND course_id IN (SELECT id FROM courses WHERE tutor_id = ?)";
    $stmt = mysqli_prepare($conn, $unlock_query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $class_id, $user_id);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Class unlocked successfully.";
        } else {
            $_SESSION['error'] = "Failed to unlock class: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['error'] = "Database query preparation failed: " . mysqli_error($conn);
    }
} else {
    $_SESSION['error'] = "No class ID provided.";
}

header("Location: course.php?id=" . $_GET['id']); // Redirect back to the course page
exit();
?>  -->