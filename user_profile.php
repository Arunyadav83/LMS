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
$user_email = $_SESSION['email'] ?? '';
$user_username = $_SESSION['username'] ?? '';

if (!$user_email && !$user_username) {
    echo "<p class='error'>Session variables for user email or username are not set.</p>";
}

// Initialize variables
$name = '';
$father_name = '';
$phone_number = '';
$emergency_contact = '';
$email = '';
$username = '';
$role = '';
$created_at = '';
$is_active = 0;

// Handle file upload
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    $file_tmp = $_FILES['profile_image']['tmp_name'];
    $file_name = preg_replace('/\s+/', '-', strtolower($username)) . '.jpg'; // Generate file name based on username
    $upload_path = $upload_directory . $file_name;

    if (move_uploaded_file($file_tmp, $upload_path)) {
        $uploaded_image = $file_name;
    } else {
        echo "<p class='error'>Failed to upload image.</p>";
    }
}

// Fetch user details from the database
if ($user_email || $user_username) {
    $stmt = $conn->prepare("SELECT username, email, role, created_at, is_active, father_name, phone_number, emergency_contact FROM users WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $user_email, $user_username);
    $stmt->execute();
    $stmt->bind_result($username, $email, $role, $created_at, $is_active, $father_name, $phone_number, $emergency_contact);

    if (!$stmt->fetch()) {
        echo "<p class='error'>No user found with the given email or username.</p>";
    }
    $stmt->close();
}

// Handle form submission for additional details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $father_name = $_POST['father_name'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';
    $emergency_contact = $_POST['emergency_contact'] ?? '';

    // Update details in the database
    if ($user_email || $user_username) {
        $stmt = $conn->prepare("UPDATE users SET father_name = ?, phone_number = ?, emergency_contact = ? WHERE email = ? OR username = ?");
        $stmt->bind_param("sssss", $father_name, $phone_number, $emergency_contact, $user_email, $user_username);
        if ($stmt->execute()) {
            echo "<p class='success'>Details updated successfully!</p>";
        } else {
            echo "<p class='error'>Failed to update details: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .profile-info p {
            margin: 10px 0;
            font-size: 16px;
            color: #555;
        }

        .profile-info span {
            font-weight: bold;
            color: #333;
        }
        .profile-info img {
            display: block;
            margin: 0 auto 20px;
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .profile-info p {
            margin: 10px 0;
            font-size: 16px;
            color: #555;
        }

        .profile-info span {
            font-weight: bold;
            color: #333;
        }

        .button-container {
            text-align: center;
            margin: 20px 0;
        }

        .button-container button {
            background-color: #007BFF;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .button-container button:hover {
            background-color: #0056b3;
        }

        #additional-details {
            display: none;
            margin-top: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        .success {
            color: green;
            text-align: center;
        }

        .error {
            color: red;
            text-align: center;
        }
        @media (max-width: 768px) {
            .container {
                margin: 20px;
                padding: 15px;
            }

            h1 {
                font-size: 24px;
            }

            .profile-info p {
                font-size: 14px;
            }

            input[type="submit"],
            .button-container button {
                font-size: 14px;
                padding: 8px 16px;
            }
        }
    </style>
    <script>
        function toggleAdditionalDetails() {
            const additionalDetails = document.getElementById('additional-details');
            additionalDetails.style.display = additionalDetails.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>User Profile</h1>
        <div class="profile-info">
            <p><span>Email:</span> <?php echo htmlspecialchars($email); ?></p>
            <p><span>Username:</span> <?php echo htmlspecialchars($username); ?></p>
            <p><span>Role:</span> <?php echo htmlspecialchars($role); ?></p>
            <p><span>Created At:</span> <?php echo htmlspecialchars($created_at); ?></p>
            <p><span>Is Active:</span> <?php echo htmlspecialchars($is_active ? 'Yes' : 'No'); ?></p>
            <p><span>Father's Name:</span> <?php echo htmlspecialchars($father_name); ?></p>
            <p><span>Phone Number:</span> <?php echo htmlspecialchars($phone_number); ?></p>
            <p><span>Emergency Contact:</span> <?php echo htmlspecialchars($emergency_contact); ?></p>
        </div>

        <div class="button-container">
            <button onclick="toggleAdditionalDetails()">Edit Additional Details</button>
        </div>

        <div id="additional-details">
            <form method="POST">
                <label for="father_name">Father's Name:</label>
                <input type="text" id="father_name" name="father_name" value="<?php echo htmlspecialchars($father_name); ?>" required>

                <label for="phone_number">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>" required>

                <label for="emergency_contact">Emergency Contact:</label>
                <input type="text" id="emergency_contact" name="emergency_contact" value="<?php echo htmlspecialchars($emergency_contact); ?>" required>

                <input type="submit" value="Save Details">
            </form>
        </div>
    </div>
</body>
</html>
