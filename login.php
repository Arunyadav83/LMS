<?php
require_once 'config.php';
require_once 'functions.php';

$error = '';

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

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                // Password is correct, start a new session
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_role'] = 'student';

                // Debug: Print session after setting
                echo "<pre>";
                print_r($_SESSION);
                echo "</pre>";

                // Redirect to index page
                redirect('index.php');
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }
    }
}

include 'header.php';
?>

<h2>Student Login</h2>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
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