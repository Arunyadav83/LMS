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
            }
        );
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
    /* Centering the form */
    .login-container {
    max-width: 400px;
    margin: 130px auto 50px auto; /* Increased top margin */
    padding: 30px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #f9f9f9;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}


    .login-container h2 {
        text-align: center;
        margin-bottom: 20px;
        font-size: 24px;
        color: #333;
    }

    .form-control {
        width: 100%;
        max-width: 350px;
    }

    /* Styling the buttons */
    .btn-primary {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        border-radius: 5px;
    }

    .btn-link {
        color: #007bff;
        font-size: 14px;
        text-decoration: none;
    }

    .btn-link:hover {
        text-decoration: underline;
    }

    /* Add some space between the buttons */
    .mb-3 {
        margin-bottom: 20px;
    }

    /* Add margin for the register link */
    .register-link {
        text-align: center;
        margin-top: 20px;
        font-size: 14px;
    }

    img {
        width: 100px;
        height: 6%;
        margin-right: 14%;

    }
    /* Styling the buttons */
.btn-primary, .btn-link {
    padding: 10px;
    font-size: 16px;
    border-radius: 5px;
    width: 48%; /* Adjust the width to fit the buttons side by side */
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-link {
    color: #007bff;
    font-size: 14px;
    text-decoration: none;
}

.btn-link:hover {
    text-decoration: underline;
}

/* Add some space between the buttons */
.btn-container {
    display: flex;
    justify-content: space-between;
    gap: 10px; /* Optional gap between buttons */
}

/* Add space between form inputs */
.mb-3 {
    margin-bottom: 20px;
}

</style>

<div class="login-container">
    <h2>
        <img src="assets/images/logo2.png" alt="Logo"> Student Login
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
                <p class="register-link">Don't have an account? <a href="register.php">Register here</a></p>
    </form>
</div>

<!-- Bootstrap 5 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>