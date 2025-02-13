
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'config.php'; // Database connection

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Both email and password are required.";
    } else {
        // Use prepared statements to prevent SQL injection
        $query = "SELECT id, email, password FROM users WHERE email = ? AND role = 'student'";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                session_regenerate_id(true); // Prevent session fixation
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                
                header("Location: dashboard.php"); // Redirect after successful login
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "User not found or not a student.";
        }

        $stmt->close();
    }
}

if (!empty($error)) {
    echo "<script>alert('" . htmlspecialchars($error, ENT_QUOTES) . "');</script>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LMS Platform</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #45B5AA, #367c76);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 1000px;
            display: flex;
            min-height: 600px;
        }

        /* Left Section - Form */
        .login-form {
            flex: 1;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            max-width: 500px;
        }

        .login-form h1 {
            color: #333;
            margin-bottom: 30px;
            font-size: 2rem;
        }

        /* Right Section - Image */
        .login-image {
            flex: 1.2;
            position: relative;
            overflow: hidden;
            min-height: 300px;
        }

        .login-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 0.3s ease;
        }

        .login-image::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(69, 181, 170, 0.2);
            pointer-events: none;
        }

        /* Form Elements */
        .input-group {
            margin-bottom: 25px;
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }

        .input-group input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .input-group input:focus {
            border-color: #45B5AA;
            outline: none;
            box-shadow: 0 0 0 3px rgba(69, 181, 170, 0.1);
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 0.9rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remember-me input[type="checkbox"] {
            accent-color: #45B5AA;
        }

        .forgot-password {
            color: #45B5AA;
            text-decoration: none;
        }

        .login-button {
            background: #45B5AA;
            color: white;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .login-button:hover {
            background: #367c76;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(69, 181, 170, 0.3);
        }

        .social-login {
            margin-top: 30px;
            text-align: center;
        }

        .social-login p {
            color: #666;
            margin-bottom: 15px;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .social-icons a {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: transform 0.3s ease;
        }

        .social-icons a:hover {
            transform: translateY(-3px);
        }

        .facebook { background: #1877f2; }
        .google { background: #db4437; }
        .twitter { background: #1da1f2; }

        /* Tablet View - Hide Image */
        @media (max-width: 1024px) {
            .login-image {
                display: none; /* Hide image */
            }

            .login-container {
                max-width: 500px; /* Adjust container width */
            }

            .login-form {
                flex: 1;
                max-width: 100%;
                padding: 40px;
            }
        }

        /* Mobile View - Keep Image Hidden */
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                max-width: 400px;
            }

            .login-form {
                padding: 30px;
            }
        }

        /* Small Mobile Adjustments */
        @media (max-width: 480px) {
            .login-container {
                margin: 10px;
            }

            .login-form {
                padding: 20px;
            }
        }

        /* Logo Styles */
        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-container img {
            max-width: 150px;
            height: auto;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        /* Responsive Logo Adjustments */
        @media (max-width: 1200px) {
            .logo-container img {
                max-width: 140px;
            }
        }

        @media (max-width: 1024px) {
            .logo-container img {
                max-width: 130px;
            }
        }

        @media (max-width: 768px) {
            .logo-container {
                margin-bottom: 25px;
            }

            .logo-container img {
                max-width: 120px;
            }
        }

        @media (max-width: 480px) {
            .logo-container {
                margin-bottom: 20px;
            }

            .logo-container img {
                max-width: 100px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Section - Form -->
        <div class="login-image">
            <img src="assets/images/userlogin.png" alt="Login Image">
        </div>
        <div class="login-form">
            <div class="logo-container">
                <img src="assets/images/logo2.png" alt="Company Logo">
            </div>
            <h1>Login to Your Account</h1>
            <form action="#" method="POST">
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" placeholder="Email Address" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" placeholder="Password" required>
                </div>
                <div class="remember-forgot">
                    <label class="remember-me">
                        <input type="checkbox">
                        Remember me
                    </label>
                    <a href="#" class="forgot-password">Forgot Password?</a>
                </div>
                <button type="submit" class="login-button">Login</button>
            </form>
            <div class="social-login">
                <p>Or login with</p>
                <div class="social-icons">
                    <a href="#" class="facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="google"><i class="fab fa-google"></i></a>
                    <a href="#" class="twitter"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>

        <!-- Right Section - Image -->
       <!-- <div class="login-image">
            <img src="assets/images/userlogin.png" alt="Login Image">
        </div> -->
    </div>
</body>
</html> 