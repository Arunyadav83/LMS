<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start(); // Ensure session is started

require_once 'config.php';
require_once 'functions.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Basic validation
    if (empty($username) || empty($password)) {
        $error = "Both username and password are required.";
    } else {
        // Check if user exists and is a student
        $query = "SELECT * FROM users WHERE username = '$username' AND role = 'student'";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die("Database query failed: " . mysqli_error($conn));
        }

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                // Password is correct, start a new session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_role'] = 'student';

                // Success message
                $success = "Login successful!";
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }
    }
}

include 'header.php';

// Fetch all courses from the database
$query = "SELECT c.id, c.title, c.description, t.full_name AS tutor_name 
          FROM courses c
          LEFT JOIN tutors t ON c.tutor_id = t.id
          ORDER BY c.created_at DESC";
$result = mysqli_query($conn, $query);
?>



<!-- Display SweetAlert2 based on success or error -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if ($success): ?>
    <script>
        Swal.fire({
            title: 'Success!',
            text: '<?php echo $success; ?>',
            icon: 'success',
            confirmButtonText: 'Ok',
            timer: 2000, // Set a timer of 2 seconds before redirect
            timerProgressBar: true, // Optionally show a progress bar
        }).then((result) => {

            window.location.href = 'index.php'; // Redirect to the homepage after timer
        });
    </script>
<?php elseif ($error): ?>
    <script>
        Swal.fire({
            title: 'Error!',
            text: '<?php echo $error; ?>',
            icon: 'error',
            confirmButtonText: 'Try Again'
        });
    </script>
<?php endif; ?>
<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    /* Overall container styling */
    .login-container {
        display: flex;
        align-items: center;
        margin: 30px;
        justify-content: center;
        height: 80vh;
        padding: 20px;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        max-width: 800px;
        margin: 0 auto;
    }

    /* User login image */
    .user-login-image {
        width: 200px;
        /* Adjust width as needed */
        height: auto;
        border-radius: 10px;
        margin-right: 30px;
        /* Space between image and form */
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
    }

    /* Form container */
    .form-container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        max-width: 400px;
    }

    /* Logo and heading styling */
    .logo-image {
        width: 50px;
        height: auto;
        margin-bottom: 10px;
    }

    .form-container h2 {
        display: flex;
        align-items: center;
        font-size: 24px;
        margin-bottom: 20px;
        color: #333;
    }

    .form-container h2 img {
        margin-right: 10px;
    }

    /* Form styling */
    .form-control {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    /* Button styling */
    .btn-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
        width: 100%;
    }

    .btn-primary {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-link {
        font-size: 14px;
        color: #007bff;
        text-decoration: none;
        text-align: center;
    }

    .btn-link:hover {
        text-decoration: underline;
    }

    /* Register link styling */
    .register-link {
        text-align: center;
        margin-top: 15px;
        font-size: 14px;
    }
</style>


<div class="login-container">
    <!-- Left side image -->
    <img src="assets/images/userlogin.png" alt="User Login" class="user-login-image">

    <!-- Right side login form -->
    <div class="form-container">
        <h2>
            <img src="assets/images/logo2.png" alt="Logo" class="logo-image">
            Student Login
        </h2>
        <form action="login.php" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="btn-container">
                <button type="submit" class="btn btn-primary">Login</button>
                <button type="button" class="btn btn-link" onclick="window.location.href='forgot_password.php';">Forgot Password</button>
            </div>
            <p class="register-link">
                Don't have an account? <a href="register.php">Register here</a>
            </p>
        </form>
    </div>
</div>




<!-- Bootstrap 5 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>