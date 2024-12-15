<?php
// fetch_live_class.php

$host = "localhost";
$username = "root";
$password = "";
$database = "lms";

$conn = new mysqli($host, $username, $password, $database);

// Fetch live class details based on class ID (Assuming class_id is sent via GET or POST)
$class_id = $_GET['class_id']; 

// Check for valid class_id
if (isset($class_id)) {
    $query = "SELECT online_link FROM classes WHERE id = ?";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $class_id);
        $stmt->execute();
        $stmt->bind_result($online_link);
        
        if ($stmt->fetch()) {
            // If online_link exists, return it as JSON
            echo json_encode(['online_link' => $online_link]);
        } else {
            // If no class or link is found
            echo json_encode(['error' => 'No live class link available']);
        }
        
        $stmt->close();
    } else {
        echo json_encode(['error' => 'Database query failed']);
    }
} else {
    echo json_encode(['error' => 'Invalid class ID']);
}

$conn->close();
?>
