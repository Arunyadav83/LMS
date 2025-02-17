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
    echo "<p class='text-red-600'>Session variables for user email or username are not set.</p>";
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
$profile_image = '';

// Handle file upload
$upload_directory = 'uploads/profile_images/';
if (!file_exists($upload_directory)) {
    mkdir($upload_directory, 0777, true); // Create directory if it doesn't exist
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch additional details from form
    $father_name = $_POST['father_name'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';
    $emergency_contact = $_POST['emergency_contact'] ?? '';

    // Handle file upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['profile_image']['tmp_name'];
        $file_ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
        $file_name = uniqid('profile_', true) . '.' . $file_ext; // Generate unique file name
        $upload_path = $upload_directory . $file_name;

        if (move_uploaded_file($file_tmp, $upload_path)) {
            $profile_image = $file_name; // Save file name to the database
        } else {
            echo "<p class='text-red-600'>Failed to upload image.</p>";
        }
    }

    // Update details in the database
    if ($user_email || $user_username) {
        $stmt = $conn->prepare(
            "UPDATE users SET father_name = ?, phone_number = ?, emergency_contact = ?, profile_image = ? WHERE email = ? OR username = ?"
        );
        $stmt->bind_param("ssssss", $father_name, $phone_number, $emergency_contact, $profile_image, $user_email, $user_username);
        if ($stmt->execute()) {
            echo "<p class='text-green-600'>Details updated successfully!</p>";
        } else {
            echo "<p class='text-red-600'>Failed to update details: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
}

// Fetch user details from the database
if ($user_email || $user_username) {
    $stmt = $conn->prepare("SELECT username, email, role, created_at, is_active, father_name, phone_number, emergency_contact, profile_image FROM users WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $user_email, $user_username);
    $stmt->execute();
    $stmt->bind_result($username, $email, $role, $created_at, $is_active, $father_name, $phone_number, $emergency_contact, $profile_image);

    if (!$stmt->fetch()) {
        echo "<p class='text-red-600'>No user found with the given email or username.</p>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <!-- <div class="w-1/4 bg-blue-800 text-white min-h-screen p-6">
            <h2 class="text-2xl font-bold mb-8">User Dashboard</h2>
            <ul>
                <li><a href="#" class="block py-2 px-4 hover:bg-blue-700 rounded">Profile</a></li>
                <li><a href="#" class="block py-2 px-4 hover:bg-blue-700 rounded">Settings</a></li>
                <li><a href="#" class="block py-2 px-4 hover:bg-blue-700 rounded">Logout</a></li>
            </ul>
        </div> -->

        <!-- Main Content -->
        <div class="w-3/4 p-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h1 class="text-3xl font-semibold text-gray-800 mb-6">User Profile</h1>
                
                <div class="flex items-center mb-6">
                    <div class="w-32 h-32 rounded-full overflow-hidden">
                        <?php if ($profile_image): ?>
                            <img src="uploads/profile_images/<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Image" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full bg-gray-300 flex items-center justify-center text-white text-xl">No Image</div>
                        <?php endif; ?>
                    </div>
                    <div class="ml-6">
                        <p class="text-xl text-gray-800"><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                        <p class="text-xl text-gray-800"><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
                        <p class="text-xl text-gray-800"><strong>Role:</strong> <?php echo htmlspecialchars($role); ?></p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-lg text-gray-600"><strong>Created At:</strong> <?php echo htmlspecialchars($created_at); ?></p>
                        <p class="text-lg text-gray-600"><strong>Status:</strong> <?php echo htmlspecialchars($is_active ? 'Active' : 'Inactive'); ?></p>
                    </div>
                    <div>
                        <p class="text-lg text-gray-600"><strong>Father's Name:</strong> <?php echo htmlspecialchars($father_name); ?></p>
                        <p class="text-lg text-gray-600"><strong>Phone Number:</strong> <?php echo htmlspecialchars($phone_number); ?></p>
                        <p class="text-lg text-gray-600"><strong>Emergency Contact:</strong> <?php echo htmlspecialchars($emergency_contact); ?></p>
                    </div>
                </div>

                <button onclick="document.getElementById('additional-details').classList.toggle('hidden')" class="w-full bg-blue-500 text-white py-2 rounded-md mt-6 hover:bg-blue-600">Edit Profile</button>

                <div id="additional-details" class="hidden mt-6">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="space-y-4">
                            <div>
                                <label for="father_name" class="block text-gray-700">Father's Name</label>
                                <input type="text" id="father_name" name="father_name" value="<?php echo htmlspecialchars($father_name); ?>" class="w-full p-3 border border-gray-300 rounded-md" required>
                            </div>
                            <div>
                                <label for="phone_number" class="block text-gray-700">Phone Number</label>
                                <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>" class="w-full p-3 border border-gray-300 rounded-md" required>
                            </div>
                            <div>
                                <label for="emergency_contact" class="block text-gray-700">Emergency Contact</label>
                                <input type="text" id="emergency_contact" name="emergency_contact" value="<?php echo htmlspecialchars($emergency_contact); ?>" class="w-full p-3 border border-gray-300 rounded-md" required>
                            </div>
                            <div>
                                <label for="profile_image" class="block text-gray-700">Profile Image</label>
                                <input type="file" id="profile_image" name="profile_image" class="w-full p-3 border border-gray-300 rounded-md">
                            </div>
                            <div>
                                <input type="submit" value="Save Changes" class="w-full bg-green-500 text-white py-2 rounded-md hover:bg-green-600">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
