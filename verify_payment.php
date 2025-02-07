<?php
header('Content-Type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Razorpay\Api\Api;

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'C:\xampp\php\logs\php_errors.log');

require_once 'config.php';
require 'vendor/autoload.php';

session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lms";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

// Razorpay API credentials
$apiKey = 'rzp_test_Bvq9kiuaq8gkcs';
$apiSecret = 'qnN6ytUKNw6beVzQUw7OBiJM';
$api = new Api($apiKey, $apiSecret);

// Check if user is logged in
$current_user_id = $_SESSION['user_id'] ?? null;
if (!$current_user_id) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Function to get user details
function getUserDetails($conn, $userId)
{
    $query = "SELECT username, email FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc() ?: null;
}

// Fetch user details
$user = getUserDetails($conn, $current_user_id);
if (!$user) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}

// Validate required fields
$requiredFields = ['razorpay_payment_id', 'razorpay_order_id', 'razorpay_signature'];
foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
        exit;
    }
}

// Handle both single course_id and multiple course_ids
$course_ids = isset($_POST['course_ids']) ? json_decode($_POST['course_ids'], true) : [];
$course_id = $_POST['course_id'] ?? null;

// If course_ids exist, use them; otherwise, use the single course_id
if (!empty($course_ids) && is_array($course_ids)) {
    $course_ids = $course_ids;
} elseif (!empty($course_id)) {
    $course_ids = [$course_id];
} else {
    echo json_encode(['success' => false, 'message' => 'No course selected']);
    exit;
}

$payment_id = $_POST['razorpay_payment_id'];
$order_id = $_POST['razorpay_order_id'];
$razorpay_signature = $_POST['razorpay_signature'];
$status = 'success';

// Function to check if the user is already enrolled
function isUserAlreadyEnrolled($conn, $userId, $courseId)
{
    $query = "SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $userId, $courseId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->num_rows > 0;
}

// Fetch course details and total price
$total_price = 0;
$course_details = [];
foreach ($course_ids as $course_id) {
    $courseQuery = "SELECT id, title, tutor_id, course_prize FROM courses WHERE id = ?";
    $courseStmt = $conn->prepare($courseQuery);
    $courseStmt->bind_param('i', $course_id);
    $courseStmt->execute();
    $courseResult = $courseStmt->get_result();
    $course = $courseResult->fetch_assoc();
    $courseStmt->close();

    if ($course) {
        $total_price += (float)$course['course_prize'];
        $course_details[] = $course;
    } else {
        echo json_encode(['success' => false, 'message' => "Invalid course ID: $course_id"]);
        exit;
    }
}

try {
    // Verify the payment signature
    $api->utility->verifyPaymentSignature([
        'razorpay_order_id' => $order_id,
        'razorpay_payment_id' => $payment_id,
        'razorpay_signature' => $razorpay_signature
    ]);

    $conn->begin_transaction();

    // Insert payment details for each course
    foreach ($course_details as $course) {
        $paymentQuery = "INSERT INTO payments (user_id, course_id, order_id, payment_id, amount, status, signature) 
                         VALUES (?, ?, ?, ?, ?, ?, ?)";
        $paymentStmt = $conn->prepare($paymentQuery);
        $paymentStmt->bind_param('iissdss', $current_user_id, $course['id'], $order_id, $payment_id, $course['course_prize'], $status, $razorpay_signature);
        if (!$paymentStmt->execute()) {
            $conn->rollback();
            die(json_encode(['success' => false, 'message' => 'Payment query execution failed: ' . $paymentStmt->error]));
        }
        $paymentStmt->close();
    }

    // Enroll the user in the courses
    foreach ($course_details as $course) {
        if (!isUserAlreadyEnrolled($conn, $current_user_id, $course['id'])) {
            $enrollmentQuery = "INSERT INTO enrollments (user_id, course_id, course_name, tutor_id, payment_id, status, enrolled_at) 
                                VALUES (?, ?, ?, ?, ?, ?, NOW())";
            $enrollmentStmt = $conn->prepare($enrollmentQuery);
            $enrollmentStmt->bind_param('iisiss', $current_user_id, $course['id'], $course['title'], $course['tutor_id'], $payment_id, $status);
            $enrollmentStmt->execute();
            $enrollmentStmt->close();
        }
    }

    $conn->commit();

    // Send email confirmation
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'arun.bhairi@ultrakeyit.com';
    $mail->Password = 'Arun@1234';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->setFrom('arun.bhairi@ultrakeyit.com', 'LMS');
    $mail->addAddress($user['email'], $user['username']);
    $mail->Subject = 'Course Enrollment Confirmation';
    $mail->Body = "Hello {$user['username']},\n\nYou have successfully enrolled in the following course(s):\n\n";
    foreach ($course_details as $course) {
        $mail->Body .= "- {$course['title']}\n";
    }
    $mail->Body .= "\nTotal Amount Paid: ₹" . number_format($total_price, 2);
    $mail->send();

    echo json_encode(['success' => true, 'message' => 'Payment verified and enrollment successful', 'total_price' => $total_price]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Error processing payment: ' . $e->getMessage()]);
}
?>
