<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config.php';

// Fetch the course details
if (isset($_POST['course_id'], $_POST['user_id'])) {
    $course_id = $_POST['course_id'];
    $user_id = $_POST['user_id'];

    // Query to get the course details
    $query = $query = "SELECT c.id, c.title AS title, c.course_prize AS course_prize, t.id AS tutor_id 
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
            'amount' => $course['course_prize'] * 100,
            // Razorpay expects the amount in paise
            'currency' => 'INR',
            'receipt' => 'order_' . $course_id,
            'notes' => [
                'course_id' => $course_id,
                'tutor_id' => $course['tutor_id'],
                'course_name' => $course['title'],
                'course_prize' => $course['course_prize'],
                'title' => $course['title']
            
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
                'success' => true,                // Indicates the success of the operation
                'order_id' => $order->id,        // The ID of the created order
                'course_id' => $course_id,        // The ID of the course
                'user_id' => $user_id,            // The ID of the user
                // 'amount' => $course['course_prize'], // Amount in paise
                'course_prize' => $course['course_prize'], // Original course prize
                'title' => $course['title']  ,
                'tutor_id' => $course['tutor_id'],
                'enrolled_at' => date('Y-m-d H:i:s')
                 // Title of the course
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Course not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing course or user ID']);
}
?>
