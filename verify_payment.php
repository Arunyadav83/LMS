<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php'; // Include DB configuration
require('vendor/autoload.php'); // Include Razorpay SDK

use Razorpay\Api\Api;

// Razorpay API credentials
$apiKey = 'rzp_test_Bvq9kiuaq8gkcs';
$apiSecret = 'qnN6ytUKNw6beVzQUw7OBiJM';

// Initialize Razorpay API
$api = new Api($apiKey, $apiSecret);

// Debugging: Log incoming parameters
error_log(print_r($_GET, true)); // Log all GET parameters

$course_id = $_GET['course_id'] ?? null;
$razorpay_payment_id = $_GET['razorpay_payment_id'] ?? null;
$order_id = $_GET['order_id'] ?? null;
$razorpay_signature = $_GET['razorpay_signature'] ?? null;

// Check if any required fields are missing
if (!$course_id || !$razorpay_payment_id || !$order_id || !$razorpay_signature) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Verify required fields
if (!$razorpay_payment_id || !$order_id || !$course_id || !$razorpay_signature) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Verify payment signature
try {
    error_log("Verifying payment with the following parameters:");
    error_log("Order ID: " . $order_id);
    error_log("Payment ID: " . $razorpay_payment_id);
    error_log("Signature: " . $razorpay_signature);

    $api->utility->verifyPaymentSignature([
        'razorpay_order_id' => $order_id,
        'razorpay_payment_id' => $razorpay_payment_id,
        'razorpay_signature' => $razorpay_signature
    ]);

    // If verification is successful
    $status = 'success';

    // Insert payment details into the `payments` table
    $paymentQuery = "INSERT INTO payments (user_id, course_id, order_id, amount, status, payment_id) 
                     VALUES (?, ?, ?, ?, ?, ?, ?)";
    $paymentStmt = $conn->prepare($paymentQuery);
    $paymentStmt->bind_param('iissss', $userId, $courseId, $razorpayPaymentId, $razorpayOrderId, $amount, $status);
    $paymentStmt->execute();

    // Insert enrollment details into the `enrollments` table
    $enrollmentQuery = "INSERT INTO enrollments (user_id, course_id, payment_id, course_name, tutor_id, enrolled_at) 
                            VALUES (?, ?, ?, ?, ?, NOW())";
    $enrollmentStmt = $conn->prepare($enrollmentQuery);
    $enrollmentStmt->bind_param('iissss', $userId, $courseId, $razorpayPaymentId, $courseName, $tutorId);
    $enrollmentStmt->execute();

    // Respond with success
    echo json_encode(['success' => true, 'message' => 'Payment verified and enrollment created successfully']);
} catch (Exception $e) {
    // If verification fails, log the error
    error_log("Error verifying payment: " . $e->getMessage());

    // Insert failed payment into the `payments` table
    $status = 'failed';
    $failedPaymentQuery = "INSERT INTO payments (user_id, course_id, payment_id, order_id, amount, status) 
                           VALUES (?, ?, ?, ?, ?, ?)";
    $failedPaymentStmt = $conn->prepare($failedPaymentQuery);
    $failedPaymentStmt->bind_param('iissss', $userId, $courseId, $razorpayPaymentId, $razorpayOrderId, $amount, $status);
    $failedPaymentStmt->execute();

    // Respond with failure
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
