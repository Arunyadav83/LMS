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

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lms";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$apiKey = 'rzp_test_Bvq9kiuaq8gkcs';
$apiSecret = 'qnN6ytUKNw6beVzQUw7OBiJM';
$api = new Api($apiKey, $apiSecret);

$current_user_id = $_SESSION['user_id'] ?? null;
if (!$current_user_id) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

function getUserDetails($conn, $userId) {
    $query = "SELECT username, email FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc() ?: null;
}

$user = getUserDetails($conn, $current_user_id);
if (!$user) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}

$requiredFields = ['course_id', 'razorpay_payment_id', 'order_id', 'razorpay_signature', 'course_prize', 'title', 'tutor_id'];
foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
        exit;
    }
}

$course_id = $_POST['course_id'];
$payment_id = $_POST['razorpay_payment_id'];
$order_id = $_POST['order_id'];
$razorpay_signature = $_POST['razorpay_signature'];
$course_prize = (float)$_POST['course_prize'];
$title = $_POST['title'];
$tutor_id = $_POST['tutor_id'];
$status = 'success';

function isUserAlreadyEnrolled($conn, $userId, $courseId) {
    $query = "SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $userId, $courseId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->num_rows > 0;
}

if (isUserAlreadyEnrolled($conn, $current_user_id, $course_id)) {
    echo json_encode(['success' => false, 'message' => 'You are already enrolled in this course']);
    exit;
}

try {
    $api->utility->verifyPaymentSignature([
        'razorpay_order_id' => $order_id,
        'razorpay_payment_id' => $payment_id,
        'razorpay_signature' => $razorpay_signature,
    ]);

    $paymentQuery = "INSERT INTO payments (user_id, course_id, order_id, payment_id, amount, status, signature) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $paymentStmt = $conn->prepare($paymentQuery);
    $paymentStmt->bind_param('iissdss', $current_user_id, $course_id, $order_id, $payment_id, $course_prize, $status, $razorpay_signature);
    $paymentStmt->execute();
    $paymentStmt->close();

    $enrollmentQuery = "INSERT INTO enrollments (user_id, course_id, course_name, tutor_id, payment_id, status, enrolled_at) VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $enrollmentStmt = $conn->prepare($enrollmentQuery);
    $enrollmentStmt->bind_param('iissss', $current_user_id, $course_id, $title, $tutor_id, $payment_id, $status);
    $enrollmentStmt->execute();
    $enrollmentStmt->close();

    // Return success response for SweetAlert
    echo json_encode(['success' => true, 'message' => 'Payment verified and enrollment successful']);

    // Send email in the background
    ob_start();
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
    $mail->Body = "Hello {$user['username']},\n\nYou have successfully enrolled in the course: $title.";
    $mail->send();
    ob_end_clean();

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error processing payment']);
}
