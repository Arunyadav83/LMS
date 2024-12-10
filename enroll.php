<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config.php'; // Include your DB config or any setup file
require 'vendor/autoload.php'; // Include Composer's autoloader

use Razorpay\Api\Api;

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get course details from the request
    $courseId = $_POST['course_id'];
    $userId = $_POST['user_id'];

    // Fetch course details from the database
    $query = "SELECT * FROM courses WHERE id = $courseId";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $course = mysqli_fetch_assoc($result);

        // Calculate the amount (in paise, since Razorpay expects the amount in INR paise)
        $amount = $course['price'] * 100; 

        // Initialize Razorpay API
        $apiKey = 'rzp_test_Bvq9kiuaq8gkcs';
        $apiSecret = 'qnN6ytUKNw6beVzQUw7OBiJM';
        $api = new Api($apiKey, $apiSecret);

        // Create an order
        $order = $api->order->create([
            'receipt' => 'order_' . uniqid(),
            'amount' => $amount, // Amount in paise
            'currency' => 'INR',
            'payment_capture' => 1 // Automatically capture payment
        ]);

        // After creating the response
        $response = [
            'order_id' => $order['id'],
            'amount' => $amount,
            'course_title' => $course['title'],
            'course_id' => $courseId,
            'user_id' => $userId,
            'success' => true // Indicate success
        ];

        // Log the response to a file for debugging
        file_put_contents('debug.log', print_r($response, true));

        // Send JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // Handle case where course is not found
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Course not found.'
        ]);
    }
} else {
    // Handle invalid request method
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
}
?>
