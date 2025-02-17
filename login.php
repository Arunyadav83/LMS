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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to Your Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #40C9B5;
            --secondary-color: #34A597;
            --background-color: #367c76;
        }

        body {
            background-color: var(--background-color);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            /* overflow: hidden; */
        }

        .login-container {
            display: flex;
            min-height: 100vh;
            padding: 5rem;
            max-width: 1200px;
            margin: 0 auto;
            /* overflow: hidden; Prevent scrollbar */
        }

        .login-card {
            display: flex;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            width: 100%;
        }

        .illustration-section {
            flex: 1;
            background-color: #f5fffd;
            padding: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-image {
            width: 100%;
            max-width: 500px;
            height: auto;
        }

        .form-section {
            
    flex: 1;
    padding: 50px;
    display: flex
;
    flex-direction: column;
    justify-content: center;
    max-width: 500px;
}
        

        .logo {
            width: 200px;
            margin-bottom: 2rem;
        }

        .login-heading {
            font-size: 2rem;
            color: #333;
            margin-bottom: 2rem;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }

        .form-control {
            padding: 0.8rem 1rem 0.8rem 2.5rem;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            width: 100%;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(64, 201, 181, 0.1);
            outline: none;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .forgot-link {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .btn-login {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.8rem;
            border-radius: 8px;
            font-weight: 500;
            width: 100%;
            margin-bottom: 1.5rem;
            transition: background-color 0.3s;
        }

        .btn-login:hover {
            background-color: var(--secondary-color);
        }

        .social-login {
            text-align: center;
        }

        .social-text {
            color: #666;
            margin-bottom: 1rem;
        }

        .social-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .social-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: transform 0.3s;
        }

        .social-btn:hover {
            transform: translateY(-2px);
        }

        .facebook { background-color: #1877F2; }
        .google { background-color: #DB4437; }
        .twitter { background-color: #1DA1F2; }

        .social-btn i {
            color: white;
            font-size: 1.2rem;
        }

        @media (max-width: 768px) {
            .login-card {
                flex-direction: column;
            }

            .illustration-section {
                padding: 2rem;
            }

            .form-section {
                padding: 2rem;
            }

            .login-image {
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="illustration-section">
                <img src="assets/images/userlogin.png" alt="Login Illustration" class="login-image">
            </div>
            <div class="form-section">
                <img src="assets/images/logo2.png" alt="Logo" class="logo">
                <h1 class="login-heading">Login to Your Account</h1>
                <form method="post" action="login.php">
                    <div class="form-group">
                        <i class="fas fa-user"></i>
                        <input type="text" 
                               class="form-control" 
                               name="username" 
                               placeholder="Username"
                               required>
                    </div>
                    <div class="form-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" 
                               class="form-control" 
                               name="password" 
                               placeholder="Password"
                               required>
                    </div>
                    <div class="remember-forgot">
                        <label class="remember-me">
                            <input type="checkbox" name="remember"> Remember me
                        </label>
                        <a href="forgot_password.php" class="forgot-link">Forgot Password?</a>
                    </div>
                    <button type="submit" class="btn-login">Login</button>
                    <div class="social-login">
                        <p class="social-text">Or login with</p>
                        <div class="social-buttons">
                            <button type="button" class="social-btn facebook">
                                <i class="fab fa-facebook-f"></i>
                            </button>
                            <button type="button" class="social-btn google">
                                <i class="fab fa-google"></i>
                            </button>
                            <button type="button" class="social-btn twitter">
                                <i class="fab fa-twitter"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if ($success): ?>
        <script>
            Swal.fire({
                title: 'Success!',
                text: '<?php echo $success; ?>',
                icon: 'success',
                confirmButtonText: 'Ok',
                timer: 2000,
                timerProgressBar: true,
            }).then((result) => {
                window.location.href = 'index.php';
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
</body>
</html>


<?php
include 'footer.php';?>


<!-- Bootstrap 5 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>