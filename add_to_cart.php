<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lms";

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);

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
$userId = $_SESSION['user_id']; // or from your request data

// Check if the course is already in the cart
$query = "SELECT * FROM cart WHERE course_id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare query: ' . $conn->error]);
    exit;
}

$stmt->bind_param("ii", $courseId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Course already in the cart
    echo json_encode(['success' => false, 'message' => 'Course already in cart']);
} else {
    // Add the course to the cart
    $insertQuery = "INSERT INTO cart (course_id, user_id) VALUES (?, ?)";
    $insertStmt = $conn->prepare($insertQuery);
    if (!$insertStmt) {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare insert query: ' . $conn->error]);
        exit;
    }

    $insertStmt->bind_param("ii", $courseId, $userId);
    $insertStmt->execute();

    if ($insertStmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Course added to cart']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add course to cart']);
    }
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
