<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php'; // Include your DB config or any setup file
require 'vendor/autoload.php'; // Include Composer's autoloader

use Razorpay\Api\Api; // Use the Razorpay API class

$apiKey = 'rzp_test_Bvq9kiuaq8gkcs';
$apiSecret = 'qnN6ytUKNw6beVzQUw7OBiJM';

// Initialize Razorpay API
$api = new Api($apiKey, $apiSecret);

// Debugging output
error_log('Request Method: ' . $_SERVER['REQUEST_METHOD']);
error_log(print_r($_POST, true)); // Log the contents of $_POST

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Check if the required parameters are provided
if (!isset($_POST['course_id']) || !isset($_POST['user_id']) || empty($_POST['course_id']) || empty($_POST['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

// Get course details from the request
$courseId = $_POST['course_id'];
$userId = $_POST['user_id'];

// Fetch course details from the database
$query = "SELECT * FROM courses WHERE id = $courseId";
$result = mysqli_query($conn, $query);

// Check for errors in the query
if (!$result) {
    echo json_encode(['error' => 'Database query failed: ' . mysqli_error($conn)]);
    exit; // Stop further execution
}

$course = mysqli_fetch_assoc($result);

// Check if course exists
if (!$course) {
    echo json_encode(['error' => 'Course not found']);
    exit; // Stop further execution
}

// Calculate the amount (in paise, since Razorpay expects the amount in INR paise)
$amount = $course['course_prize'] * 100; 

// Create an order
$order = $api->order->create([
    'receipt' => 'order_' . uniqid(),
    'amount' => $amount, // Amount in paise
    'currency' => 'INR',
    'payment_capture' => 1 // Automatically capture payment
]);

// Send order ID and course details as JSON response
echo json_encode([
    'order_id' => $order['id'],
    'amount' => $amount,
    'course_title' => $course['title'],
    'course_id' => $courseId,
    'user_id' => $userId
]);
?>


