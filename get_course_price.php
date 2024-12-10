<?php
require_once 'config.php';

if (isset($_GET['id'])) {
    $courseId = intval($_GET['id']);
    $query = "SELECT course_prize FROM courses WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $courseId);
    $stmt->execute();
    $stmt->bind_result($coursePrize);
    $stmt->fetch();
    
    if ($coursePrize) {
        echo json_encode(['success' => true, 'price' => $coursePrize]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Course not found.']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid course ID.']);
}
?>
