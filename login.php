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

<h2>Student Login</h2>

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
            if (result.dismiss === Swal.DismissReason.timer) {
                window.location.href = 'index.php'; // Redirect to the homepage after timer
            }
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

<form action="login.php" method="post">
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
</form>

<p class="mt-3">Don't have an account? <a href="register.php">Register here</a></p>

<?php include 'footer.php'; ?>
