<?php
session_start();
$user_id = $_SESSION['user_id']; // Assuming user ID is stored in session

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "lms";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch courses the user is enrolled in
$sql = "SELECT course_id FROM enrollments WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$enrolled_courses = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $enrolled_courses[] = $row['course_id'];
    }
}

// If the user is not enrolled in any courses
if (empty($enrolled_courses)) {
    echo json_encode(['message' => 'You have not enrolled in any courses.']);
    exit;
}

// Fetch recorded classes for the enrolled courses
$course_ids = implode(',', $enrolled_courses);
$sql = "SELECT * FROM classes WHERE course_id IN ($course_ids) AND video_path != ''";
$result = $conn->query($sql);

$classes = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $classes[] = $row;
    }
} else {
    echo json_encode(['message' => 'This course does not have any recorded videos.']);
    exit;
}

// Close the connection
$conn->close();

// Return the classes as JSON
header('Content-Type: application/json');
echo json_encode($classes);
?> 