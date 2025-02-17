<?php
session_start();
require_once 'config.php';
require_once 'vendor/autoload.php'; // Include PHPMailer if installed via Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error = '';
$success = '';

$otp_validity_time = 30; // Time in seconds, OTP is valid for 30 seconds

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['resend_otp']) && isset($_SESSION['email'])) {
        // Resend OTP if the user clicked on the resend button
        $email = $_SESSION['email'];
        $otp = rand(100000, 999999); // Generate random OTP
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_time'] = time(); // Store OTP time

        // Send OTP via PHPMailer
        sendOtpEmail($email, $otp);
        $success = "OTP has been resent to your email.";
    } else {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $otp = rand(100000, 999999); // Generate random OTP

        // Check if email exists
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            // Store OTP in the session for validation later
            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $email;
            $_SESSION['otp_time'] = time(); // Store OTP time

            // Send OTP via PHPMailer
            sendOtpEmail($email, $otp);

            // Redirect to OTP verification page
            header("Location: verify_otp.php");
            exit;
        } else {
            $error = "Email not found.";
        }
    }
}

function sendOtpEmail($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com'; // SMTP server (Gmail in this example)
        $mail->SMTPAuth = true;
        $mail->Username = 'arun.bhairi@ultrakeyit.com'; // Your email address
        $mail->Password = 'Arun@1234'; // Your email password or app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email content
        $mail->setFrom('arun.bhairi@ultrakeyit.com', 'UltrakeyIt'); // Sender's email and name
        $mail->addAddress($email); // Recipient's email
        $mail->Subject = 'Password Reset OTP';
        $mail->Body = "Your OTP for password reset is: $otp";

        // Send email
        $mail->send();
    } catch (Exception $e) {
        global $error;
        $error = "Failed to send OTP. Mailer Error: " . $mail->ErrorInfo;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #fdfcfb 0%, #e2d1c3 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 
                        0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 2rem;
            width: 90%;
            max-width: 400px;
            transition: transform 0.2s;
        }

        .container:hover {
            transform: translateY(-2px);
        }

        h2 {
            color: #1a1a1a;
            font-size: 1.5rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.2s;
            outline: none;
            box-sizing: border-box;
        }

        .form-group input:focus {
            border-color: #9b87f5;
            box-shadow: 0 0 0 3px rgba(155, 135, 245, 0.1);
        }

        .btn {
            width: 100%;
            background-color: #9b87f5;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn:hover {
            background-color: #8b74f1;
            transform: translateY(-1px);
        }

        .btn:active {
            transform: translateY(0);
        }

        .error-message {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            text-align: center;
        }

        .success-message {
            color: #059669;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            text-align: center;
        }

        .text-muted {
            color: #6b7280;
            font-size: 0.875rem;
            text-align: center;
            margin-top: 1rem;
        }

        .text-muted a {
            color: #9b87f5;
            text-decoration: none;
            font-weight: 500;
        }

        .text-muted a:hover {
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .container {
            animation: fadeIn 0.3s ease-out;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        <form action="forgot_password.php" method="post">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="Enter your email"
                    required
                >
            </div>
            <button type="submit" class="btn">Reset Password</button>
        </form>

        <?php if ($error): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>

        <?php if (isset($_SESSION['otp_time']) && (time() - $_SESSION['otp_time'] > $otp_validity_time)): ?>
            <form action="forgot_password.php" method="post">
                <button type="submit" name="resend_otp" class="btn" style="margin-top: 1rem; background-color: #6b7280;">
                    Resend OTP
                </button>
            </form>
        <?php endif; ?>

        <?php if ($success): ?>
            <p class="success-message"><?php echo $success; ?></p>
        <?php endif; ?>

        <p class="text-muted">
            Remember your password? <a href="login.php">Login here</a>
        </p>
    </div>
</body>
</html>
