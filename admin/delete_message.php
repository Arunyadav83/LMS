<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lms";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the ID is set
if (isset($_POST['id'])) {
    $id = intval($_POST['id']); // Ensure the ID is an integer

    // Prepare the delete statement
    $sql = "DELETE FROM messages WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Message deleted successfully.";
    } else {
        echo "Error deleting message: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "No ID provided.";
}

$conn->close();
?>