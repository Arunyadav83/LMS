<?php

// Include database configuration
require_once 'config.php'; // Database connection

// Include the Razorpay PHP library using Composer's autoload
require 'vendor/autoload.php'; 

// Set the response content type to JSON
header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Check if the required parameters are provided
if (empty($_POST['course_id']) || empty($_POST['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

// Retrieve and validate input data
$courseId = intval($_POST['course_id']);
$userId = intval($_POST['user_id']);

// Fetch course price from the database
$query = "SELECT course_prize FROM courses WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $courseId);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
    exit;
}

$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Course not found']);
    exit;
}

$course = $result->fetch_assoc();
if (!isset($course['course_prize']) || $course['course_prize'] <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid course price']);
    exit;
}

$coursePrice = $course['course_prize'] * 100; // Convert to paise for Razorpay

// Initialize the Razorpay API
use Razorpay\Api\Api;

$apiKey = "rzp_test_Bvq9kiuaq8gkcs";
$apiSecret = "qnN6ytUKNw6beVzQUw7OBiJM";
$api = new Api($apiKey, $apiSecret);

try {
    // Create Razorpay order
    $order = $api->order->create([
        'receipt' => 'rcptid_' . $courseId,
        'amount' => $coursePrice, // Amount in paise
        'currency' => 'INR'
    ]);

    // Respond with the order details
    echo json_encode([
        'success' => true,
        'order_id' => $order['id'],
        'course_price' => $coursePrice
    ]);
} catch (Exception $e) {
    // Handle errors in order creation
    echo json_encode([
        'success' => false,
        'message' => 'Failed to create order. ' . $e->getMessage()
    ]);
}


?>
