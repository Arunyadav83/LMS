<?php
session_start();
require_once '../config.php';

// Assuming you have a way to identify the user
$user_id = $_SESSION['user_id']; // Adjust as necessary

// Update the message status to 'read'
$query = "UPDATE messages SET status = 'read' WHERE status = 'unread' AND user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

// Return a success response
echo json_encode(['success' => true]);
?>
