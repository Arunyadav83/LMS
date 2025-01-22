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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the file input is set and there are no errors
    if (isset($_FILES['image_path']) && $_FILES['image_path']['error'] === UPLOAD_ERR_OK) {
        $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8'); // Sanitize input
        $successstory = htmlspecialchars($_POST['successtory'], ENT_QUOTES, 'UTF-8'); // Sanitize input

        // Handle the uploaded file
        $fileTmpPath = $_FILES['image_path']['tmp_name'];
        $fileName = basename($_FILES['image_path']['name']);
        $fileName = str_replace(' ', '_', $fileName); // Replace spaces with underscores for consistency
        $uploadFileDir = 'D:/Xampp/htdocs/LMS/LMS/uploaded_images/uploaded_images/';
 // Absolute path for storing images
        $dest_path = $uploadFileDir . $fileName;

        // Check if the directory exists, if not, create it
        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0755, true); // Create the directory with permissions
        }

        // Move the file to the specified directory
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            // Store the relative path for database storage
            $relativePath = 'uploaded_images/' . $fileName;

            // Prepare the SQL query using prepared statements
            $stmt = $conn->prepare("INSERT INTO student_success_stories (name, successtory, image_path) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $successstory, $relativePath);

            if ($stmt->execute()) {
                // Redirect to successstory.php after successful insertion
                header("Location: successstory.php");
                exit(); // Ensure no further code is executed after the redirect
            } else {
                echo "Error saving details to the database: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "There was an error moving the uploaded file.";
        }
    } else {
        // Handle the error
        echo "Error: " . (isset($_FILES['image_path']['error']) ? $_FILES['image_path']['error'] : 'File not uploaded');
    }
} else {
    echo "Invalid request method.";
}


$conn->close();
?>
