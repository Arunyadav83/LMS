<?php
// getCoursePrice.php

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include your database connection here
include('config.php');

if (isset($_GET['course_id'])) {
    $courseId = $_GET['course_id'];

    // Prepare the query to fetch course prize
    $query = "SELECT course_prize FROM courses WHERE id = ?";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('i', $courseId); // 'i' for integer (assuming course_id is an integer)
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch the course prize if course exists
            $row = $result->fetch_assoc();
            echo json_encode([
                'success' => true,
                'course_prize' => $row['course_prize']
            ]);
        } else {
            // Return error if course is not found
            echo json_encode([
                'success' => false,
                'message' => 'Course not found'
            ]);
        }

        $stmt->close();
    } else {
        // Return error if query preparation fails
        echo json_encode([
            'success' => false,
            'message' => 'Failed to prepare SQL query: ' . $conn->error
        ]);
    }

} else {
    // Return error if course_id is missing
    echo json_encode([
        'success' => false,
        'message' => 'Course ID is missing'
    ]);
}

$conn->close();
?>
