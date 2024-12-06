<?php
session_start();

// Database connection
$host = 'localhost'; // Update with your database host
$db = 'lms'; // Update with your database name
$user = 'root'; // Update with your database username
$pass = ''; // Update with your database password

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming user details are stored in session after login
$user_email = $_SESSION['user_email'] ?? '';
$user_username = $_SESSION['user_username'] ?? '';

// Initialize variables to avoid undefined variable warnings
$father_name = '';
$phone_number = '';
$emergency_number = '';

// Fetch user details from the database
if ($user_email) {
    $stmt = $conn->prepare("SELECT name, email, username, father_name, phone_number, emergency_number FROM users WHERE user_id = ? OR username = ?");
    $stmt->bind_param("ss", $user_id, $user_username);
    $stmt->execute();
    $stmt->bind_result($name, $email, $username, $father_name, $phone_number, $emergency_number);
    $stmt->fetch();
    $stmt->close();
}

// Handle form submission for additional details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $father_name = $_POST['father_name'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';
    $emergency_number = $_POST['emergency_number'] ?? '';
    $email = $_POST['email'] ?? '';
    $username = $_POST['username'] ?? '';
    $name = $_POST['name'] ?? '';
    
    // Save these details to the database or process as needed
    // ...
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <script>
        function toggleAdditionalDetails() {
            const additionalDetails = document.getElementById('additional-details');
            additionalDetails.style.display = additionalDetails.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <h1>User Profile</h1>
    <p>Email: <?php echo htmlspecialchars($user_email); ?></p>
    <p>Username: <?php echo htmlspecialchars($user_username); ?></p>
    <p>Father's Name: <?php echo htmlspecialchars($father_name); ?></p>
    <p>Phone Number: <?php echo htmlspecialchars($phone_number); ?></p>
    <p>Emergency Number: <?php echo htmlspecialchars($emergency_number); ?></p>
    <p>Email: <?php echo htmlspecialchars($email); ?></p>
    <p>Username: <?php echo htmlspecialchars($username); ?></p>
    <p>Name: <?php echo htmlspecialchars($name); ?></p> 

    <button onclick="toggleAdditionalDetails()">+</button>
    <div id="additional-details" style="display: none;">
        <form method="POST">
            <label for="father_name">Father's Name:</label>
            <input type="text" id="father_name" name="father_name" required><br>

            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" required><br>

            <label for="emergency_number">Emergency Number:</label>
            <input type="text" id="emergency_number" name="emergency_number" required><br>

            <input type="submit" value="Save Details">
        </form>
    </div>
</body>
</html>
