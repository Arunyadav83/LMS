<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

$userId = intval($_SESSION['user_id']);

// Delete all courses in the cart for the user after successful payment
$deleteQuery = "DELETE FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($deleteQuery);

if ($stmt) {
    $stmt->bind_param('i', $userId);
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Cart cleared"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to clear cart"]);
    }
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Failed to prepare delete statement: " . $conn->error]);
}

$conn->close();
?>

