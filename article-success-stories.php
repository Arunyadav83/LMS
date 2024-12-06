<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Adjust your DB password
$dbname = "lms"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $successtory = $_POST['successtory'];
    $image_path = $_POST['image_path']; // For file uploads, adjust this to handle files.

    $stmt = $conn->prepare("INSERT INTO student_success_stories (name, successtory, image_path) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $successtory, $image_path);

    if ($stmt->execute()) {
        // Success message with a link
        echo "<p style='color: green; text-align: center;'>Success story added successfully! <a href='successstory.php'>View Success Stories</a></p>";
        header("Location: successstory.php");
        exit(); // Ensure no further code is executed after redirection
    } else {
        echo "<p style='color: red; text-align: center;'>Error: " . $conn->error . "</p>";
    }

    $stmt->close();
}

$conn->close();
?>
