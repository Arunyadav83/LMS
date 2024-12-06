<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - Ultrakey</title>
    <style>
        /* Basic styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .profile-container {
            max-width: 600px;
            margin: auto;
            background: lightgray;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .profile-header {
            display: flex;
            align-items: center;
        }
        .profile-header img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-right: 20px;
        }
        .profile-details {
            flex: 1;
        }
        .cart-box {
            margin-top: 20px;
            padding: 10px;
            background: #e9ecef;
            border-radius: 5px;
        }
        .options {
            cursor: pointer;
            color: #007bff;
        }
        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .popup-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 300px;
        }
    </style>
</head>
<body>
    <?php
    session_start(); // Start the session

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

    // Fetch the number of admins
    $result = $conn->query("SELECT COUNT(*) as admin_count FROM admin_users");
    $row = $result->fetch_assoc();
    $adminCount = $row['admin_count'];

    // Fetch the currently logged-in admin details
    if (isset($_SESSION['admin_id'])) { // Check if the session variable is set
        $loggedInAdminId = $_SESSION['admin_id']; // Replace with your session variable
        $result = $conn->query("SELECT username FROM admin_users WHERE id = $loggedInAdminId");
        
        if ($result) { // Check if the query was successful
            $adminDetails = $result->fetch_assoc();
        } else {
            die("Error fetching admin details: " . $conn->error);
        }
    } else {
        die("No admin is logged in.");
    }
    ?>

    <div class="profile-container">
        <div class="profile-header">
        <img src="uploads/resumes/default-male.png">
            <div class="profile-details">
                <h2><?php echo htmlspecialchars($adminDetails['username']); ?></h2>
                <p>Password: ********</p>
                <p>Total Admins: <?php echo $adminCount; ?></p>
            </div>
        </div>
        <div class="cart-box">
            <h3>Cart Details</h3>
            <p>Your cart is currently empty.</p>
            <div class="options" onclick="showPopup()">⋮</div>
        </div>
    </div>

    <div class="popup" id="popup">
        <div class="popup-content">
            <h3>Edit or Delete</h3>
            <button onclick="editProfile()">Edit</button>
            <button onclick="deleteProfile()">Delete</button>
            <button onclick="closePopup()">Close</button>
        </div>
    </div>

    <script>
        function showPopup() {
            document.getElementById('popup').style.display = 'flex';
        }

        function closePopup() {
            document.getElementById('popup').style.display = 'none';
        }

        function editProfile() {
            // Logic for editing the profile
            alert('Edit profile functionality to be implemented.');
            closePopup();
        }

        function deleteProfile() {
            // Logic for deleting the profile
            alert('Delete profile functionality to be implemented.');
            closePopup();
        }
    </script>
</body>
</html>
