<?php
session_start(); // Start the session to fetch logged-in user details
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    // Ensure the user is logged in
    die(json_encode(['success' => false, 'message' => 'User is not logged in']));
}

// Get the logged-in user's ID from the session
$logged_in_user_id = $_SESSION['user_id'];

// Fetch the logged-in user's details
$user_query = "SELECT username, email FROM users WHERE id = ?";
$user_stmt = $conn->prepare($user_query);
if (!$user_stmt) {
    die(json_encode(['success' => false, 'message' => 'SQL error (user fetch): ' . $conn->error]));
}
$user_stmt->bind_param('i', $logged_in_user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if ($user_result->num_rows === 0) {
    die(json_encode(['success' => false, 'message' => 'Logged-in user not found']));
}

$user = $user_result->fetch_assoc();
$username = $user['username'];
$email = $user['email'];

// Fetch the course details
if (isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];

    // Query to get the course details
    $query = "SELECT c.id, c.title AS title, c.course_prize AS course_prize, t.id AS tutor_id
              FROM courses c
              LEFT JOIN tutors t ON c.tutor_id = t.id
              WHERE c.id = ?";
    
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die(json_encode(['success' => false, 'message' => 'SQL error: ' . $conn->error]));
    }

    $stmt->bind_param('i', $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $course = $result->fetch_assoc();

        $razorpayOrderData = [
            'amount' => $course['course_prize'] * 100, // Razorpay expects amount in paise
            'currency' => 'INR',
            'receipt' => 'order_' . $course_id,
            'notes' => [
                'course_id' => $course_id,
                'tutor_id' => $course['tutor_id'],
                'course_name' => $course['title'],
                'course_prize' => $course['course_prize'],
                'title' => $course['title'],
                'username' => $username, // From logged-in user
                'email' => $email,       // From logged-in user
            ]
        ];
        
        require_once('vendor/autoload.php');
        $apiKey = 'rzp_test_Bvq9kiuaq8gkcs';
        $apiSecret = 'qnN6ytUKNw6beVzQUw7OBiJM';
        $api = new \Razorpay\Api\Api($apiKey, $apiSecret);

        // Create the order
        try {
            $order = $api->order->create($razorpayOrderData);

            // Prepare the response for a successful order creation
            echo json_encode([
                'success' => true,
                'order_id' => $order->id,
                'course_id' => $course_id,
                'user_id' => $logged_in_user_id,
                'course_prize' => $course['course_prize'],
                'title' => $course['title'],
                'tutor_id' => $course['tutor_id'],
                'enrolled_at' => date('Y-m-d H:i:s'),
                'email' => $email,       // From logged-in user
                'username' => $username, // From logged-in user
                
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Course not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing course ID']);
}
?>