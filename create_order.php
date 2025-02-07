<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 // Start the session to fetch logged-in user details
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config.php';
require_once 'vendor/autoload.php';

// Debug: Log raw POST data
file_put_contents('debug_log.txt', "Raw POST Data: " . print_r($_POST, true) . "\n", FILE_APPEND);

if (!isset($_SESSION['user_id'])) {
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

// Debug: Check if course_id or course_ids exists
if (!isset($_POST['course_id']) && !isset($_POST['course_ids'])) {
    die(json_encode(['success' => false, 'message' => 'Missing course ID(s)', 'debug' => $_POST]));
}

// Handle both single and multiple course IDs
if (isset($_POST['course_id'])) {
    // Single course ID
    $courseIds = [$_POST['course_id']];
} else {
    // Multiple course IDs (JSON array)
    $courseIds = json_decode($_POST['course_ids'], true);
    if (!is_array($courseIds)) {
        die(json_encode(['success' => false, 'message' => 'Invalid course IDs format']));
    }
}

// Debug: Log course IDs
file_put_contents('debug_log.txt', "Course IDs: " . print_r($courseIds, true) . "\n", FILE_APPEND);

// Query to get course details
$query = "SELECT c.id, c.title AS title, c.course_prize AS course_prize, t.id AS tutor_id
          FROM courses c
          LEFT JOIN tutors t ON c.tutor_id = t.id
          WHERE c.id = ?";

$response = [];
$totalPrice = 0; // Initialize total price

foreach ($courseIds as $id) {
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die(json_encode(['success' => false, 'message' => 'SQL error: ' . $conn->error]));
    }
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $course = $result->fetch_assoc();

        // Debug: Log the fetched course details
        file_put_contents('debug_log.txt', "Fetched Course Details for ID $id: " . print_r($course, true) . "\n", FILE_APPEND);

        // Check if course_prize exists and is valid
        if (!isset($course['course_prize']) || empty($course['course_prize'])) {
            die(json_encode(['success' => false, 'message' => 'Course prize is missing or invalid']));
        }



        // Debug: Log the course prize
        file_put_contents('debug_log.txt', "Course Prize for ID $id: " . $course['course_prize'] . "\n", FILE_APPEND);

        // Add the course prize to the total price
        $totalPrice += $course['course_prize'];

        $razorpayOrderData = [
            'amount' => $course['course_prize'] * 100, // Razorpay expects amount in paise
            'currency' => 'INR',
            'receipt' => 'order_' . $id,
            'notes' => [
                'course_id' => $id,
                'tutor_id' => $course['tutor_id'],
                'course_name' => $course['title'],
                'course_prize' => $course['course_prize'],
                'title' => $course['title'],
                'username' => $username, // From logged-in user
                'email' => $email,       // From logged-in user
            ]
        ];

        file_put_contents('debug_log.txt', "Razorpay Order Data: " . json_encode($razorpayOrderData) . "\n", FILE_APPEND);

        file_put_contents('debug_log.txt', "Course Details (Title, Tutor ID): " . json_encode($course) . "\n", FILE_APPEND);

        


        require_once('vendor/autoload.php');
        $apiKey = 'rzp_test_Bvq9kiuaq8gkcs';
        $apiSecret = 'qnN6ytUKNw6beVzQUw7OBiJM';
        $api = new \Razorpay\Api\Api($apiKey, $apiSecret);

        // Create the order
        try {
            $order = $api->order->create($razorpayOrderData);

            $response[] = [
                'success' => true,
                'order_id' => $order->id,
                'course_id' => $id,
                'user_id' => $logged_in_user_id,
                'course_prize' => $course['course_prize'],
                'title' => $course['title'],
                'tutor_id' => $course['tutor_id'],
                'enrolled_at' => date('Y-m-d H:i:s'),
                'email' => $email,
                'username' => $username,
            ];

            
        } catch (Exception $e) {
            file_put_contents('debug_log.txt', "Razorpay error for Course ID: $id - " . $e->getMessage() . "\n", FILE_APPEND);
            $response[] = ['success' => false, 'message' => $e->getMessage()];
        }
    } else {
        file_put_contents('debug_log.txt', "Course not found for ID: $id\n", FILE_APPEND);
        $response[] = ['success' => false, 'message' => 'Course not found', 'course_id' => $id];
    }
}



file_put_contents('debug_log.txt', "Final Response Data: " . json_encode($response) . "\n", FILE_APPEND);

// Debug: Log the total price
file_put_contents('debug_log.txt', "Total Price: " . $totalPrice . "\n", FILE_APPEND);

// Return all orders and total price
echo json_encode(['success' => true, 'orders' => $response, 'total_price' => $totalPrice]);
?>