<?php
require 'config.php'; // Include database & Razorpay API config

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$courseIds = isset($input['course_ids']) ? $input['course_ids'] : [];

// Validate input
if (!is_array($courseIds) || empty($courseIds)) {
    echo json_encode(["success" => false, "message" => "Invalid or missing course IDs"]);
    exit;
}

$orderResponses = [];

foreach ($courseIds as $course_id) {
    if (empty($course_id)) {
        continue; // Skip empty IDs
    }

    // Fetch course details
    $query = "SELECT c.id, c.title, c.course_prize, t.id AS tutor_id 
              FROM courses c 
              LEFT JOIN tutors t ON c.tutor_id = t.id 
              WHERE c.id = ?";
    
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode(["success" => false, "message" => "SQL error: " . $conn->error]);
        exit;
    }

    $stmt->bind_param('i', $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["success" => false, "message" => "Course ID $course_id not found"]);
        exit;
    }

    $course = $result->fetch_assoc();
    $stmt->close();

    if (empty($course['course_prize'])) {
        echo json_encode(["success" => false, "message" => "Course price is missing for ID $course_id"]);
        exit;
    }

    // Create Razorpay order
    $razorpayOrderData = [
        'amount' => $course['course_prize'] * 100, // Convert to paise
        'currency' => 'INR',
        'receipt' => 'order_' . $course_id,
        'notes' => [
            'course_id' => $course_id,
            'tutor_id' => $course['tutor_id'],
            'course_name' => $course['title'],
            'course_prize' => $course['course_prize'],
            'username' => $input['username'],
            'email' => $input['email'],
        ]
    ];

    try {
        $order = $api->order->create($razorpayOrderData);
        $orderResponses[] = [
            'success' => true,
            'order_id' => $order->id,
            'course_id' => $course_id,
            'user_id' => $input['user_id'],
            'course_prize' => $course['course_prize'],
            'title' => $course['title'],
            'tutor_id' => $course['tutor_id'],
            'enrolled_at' => date('Y-m-d H:i:s'),
            'email' => $input['email'],
            'username' => $input['username'],
        ];
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Razorpay error: " . $e->getMessage()]);
        exit;
    }
}

// Return final JSON response
echo json_encode(["success" => true, "orders" => $orderResponses]);
?>
