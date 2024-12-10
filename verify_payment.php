<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'C:\xampp\php\logs\php_errors.log');

require_once 'config.php';
require('vendor/autoload.php');

use Razorpay\Api\Api;

// Razorpay API credentials
$apiKey = 'rzp_test_Bvq9kiuaq8gkcs';
$apiSecret = 'qnN6ytUKNw6beVzQUw7OBiJM';

// Initialize Razorpay API
$api = new Api($apiKey, $apiSecret);

// Log incoming POST data
error_log("Incoming POST data: " . print_r($_POST, true));

// Retrieve POST data
$course_id = $_POST['course_id'] ?? null;
$payment_id = $_POST['razorpay_payment_id'] ?? null;
$order_id = $_POST['order_id'] ?? null;
$razorpay_signature = $_POST['razorpay_signature'] ?? null;
$user_id = $_POST['user_id'] ?? null;
$course_prize = $_POST['course_prize'] ?? null;
$title = $_POST['title'] ?? null;
$tutor_id = $_POST['tutor_id'] ?? null;
$enrolled_at = $_POST['enrolled_at'] ?? null;



// Check for missing fields
if (!$course_id || !$payment_id || !$order_id || !$razorpay_signature || !$user_id || !$course_prize || !$title || !$tutor_id || !$enrolled_at) {
    error_log("Error: Missing required fields in POST data");
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

try {
    // Verify payment signature
    $api->utility->verifyPaymentSignature([
        'razorpay_order_id' => $order_id,
        'razorpay_payment_id' => $payment_id,
        'razorpay_signature' => $razorpay_signature,
    ]);

    // Payment verification successful
    $status = 'success';

    // Insert payment details into `payments` table
    $paymentQuery = "INSERT INTO payments (user_id, course_id, order_id, payment_id, amount, status, signature) 
                     VALUES (?, ?, ?, ?, ?, ?, ?)";
    $paymentStmt = $conn->prepare($paymentQuery);
    if (!$paymentStmt) {
        error_log("Error preparing payments query: " . $conn->error);
        throw new Exception("SQL error (payments): " . $conn->error);
    }
    $paymentStmt->bind_param('iissdss', $user_id, $course_id, $order_id, $payment_id, $course_prize, $status, $razorpay_signature);
    $paymentStmt->execute();

// Insert enrollment details into `enrollments` table
$enrollmentQuery = "INSERT INTO enrollments (user_id, course_id, payment_id, course_name, enrolled_at, status, tutor_id) 
                    VALUES (?, ?, ?, ?, NOW(), ?, ?)";

$enrollmentStmt = $conn->prepare($enrollmentQuery);
if (!$enrollmentStmt) {
    error_log("Error preparing enrollments query: " . $conn->error);
    throw new Exception("SQL error (enrollments): " . $conn->error);
}

// Define the enrollment status
$enrollmentStatus = 'success';

// Correct the type definition and parameters (7 placeholders, 7 variables)
$enrollmentStmt->bind_param("iisssi", $user_id, $course_id, $payment_id, $title, $enrollmentStatus, $tutor_id);

$enrollmentStmt->execute();

if ($enrollmentStmt->affected_rows > 0) {
    error_log("Enrollment data inserted successfully for user_id: $user_id, course_id: $course_id, tutor_id: $tutor_id");
} else {
    error_log("Failed to insert enrollment data for user_id: $user_id, course_id: $course_id, tutor_id: $tutor_id");
}

  $enrollmentStmt->execute();
    if ($enrollmentStmt->affected_rows > 0) {
        error_log("Enrollment data inserted successfully for user_id: $user_id, course_id: $course_id , tutor_id: $tutor_id , enrolled_at: $enrolled_at");
    } else {
        error_log("Failed to insert enrollment data for user_id: $user_id, course_id: $course_id , tutor_id: $tutor_id , enrolled_at: $enrolled_at"); 
    }
    error_log("Tutor ID: " . print_r($tutor_id, true));

    $enrollmentStmt->close();

    // Respond with success
    echo json_encode(['success' => true, 'message' => 'Payment verified and enrollment created successfully']);
} catch (Exception $e) {
    // Log and respond with error
    error_log("Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Payment verification failed: ' . $e->getMessage()]);
}
