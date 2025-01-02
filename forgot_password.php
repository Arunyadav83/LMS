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

<style>
     body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        background: linear-gradient(120deg, #84fab0, #8fd3f4);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .container {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        padding: 30px;
        width: 100%;
        max-width: 400px;
        text-align: center;
    }

    h2 {
        color: #333;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 15px;
        text-align: left;
    }

    .form-group label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
        color: #555;
    }

    .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    .btn {
        background-color: #4caf50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s;
    }

    .btn:hover {
        background-color: #45a049;
    }

    .error-message {
        color: red;
        margin-top: 10px;
        font-size: 14px;
    }

    .text-muted {
        margin-top: 15px;
        font-size: 14px;
        color: #777;
    }

    .text-muted a {
        color: #4caf50;
        text-decoration: none;
    }

    .text-muted a:hover {
        text-decoration: underline;
    }
</style>

<div class="container">
    <h2>Forgot Password</h2>
    <form action="forgot_password.php" method="post">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <button type="submit" class="btn">Submit</button>
    </form>

    <?php if ($error): ?>
        <p class="error-message"> <?php echo $error; ?> </p>
    <?php endif; ?>

    <?php if (isset($_SESSION['otp_time']) && (time() - $_SESSION['otp_time'] > $otp_validity_time)): ?>
        <!-- Resend OTP Button -->
        <form action="forgot_password.php" method="post">
            <button type="submit" name="resend_otp" class="btn">Resend OTP</button>
        </form>
    <?php endif; ?>

    <?php if ($success): ?>
        <p class="text-muted"><?php echo $success; ?></p>
    <?php endif; ?>
</div>

