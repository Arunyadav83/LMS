<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Include the configuration file
require_once 'config.php';
// Database connection using the values defined in config.php
// $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Check for connection errors
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Check if the courseId is provided in the request body
$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['courseId'])) {
    echo json_encode(['success' => false, 'message' => 'No courseId provided.']);
    exit;
}

$courseId = $data['courseId'];

// Assuming you have the user_id available, e.g., from session or request
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User is not logged in']);
    exit;
}
$userId = $_SESSION['user_id']; // or from your request data

// Check if the course is already enrolled by the user
$query = "SELECT * FROM enrollments WHERE course_id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $courseId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Course is already enrolled by the user
    echo json_encode(['success' => false, 'message' => 'Course already enrolled']);
} else {
    // Check if the course is already in the cart
    $query = "SELECT * FROM cart WHERE course_id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $courseId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Course is already in the cart
        echo json_encode(['success' => false, 'message' => 'Course already in cart']);
    } else {
        // Add course to cart
        $query = "INSERT INTO cart (course_id, user_id) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $courseId, $userId);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Course added to cart']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add course to cart']);
        }
    }
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
