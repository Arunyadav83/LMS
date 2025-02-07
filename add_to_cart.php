<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require_once 'config.php';

session_start();

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Get request data
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['courseId']) || empty($data['courseId'])) {
    echo json_encode(['success' => false, 'message' => 'Course ID is missing']);
    exit;
}

$courseId = intval($data['courseId']);

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User is not logged in']);
    exit;
}

$userId = intval($_SESSION['user_id']);

// Check if the course is already enrolled
$query = "SELECT * FROM enrollments WHERE course_id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $courseId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Course already enrolled']);
    exit;
}

// Check if the course is already in the cart
$query = "SELECT * FROM cart WHERE course_id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $courseId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Course already in cart']);
    exit;
}

// Add course to cart
$query = "INSERT INTO cart (course_id, user_id) VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $courseId, $userId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Course added to cart']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to add course to cart']);
}

$stmt->close();
$conn->close();

?>
