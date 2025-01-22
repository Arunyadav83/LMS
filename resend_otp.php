<?php
// Start a session to track user data
session_start();

// Include database connection file (if needed)
include 'include.php'; // Replace with your database connection script

// Function to generate a random OTP
function generateOTP($length = 6) {
    $otp = '';
    for ($i = 0; $i < $length; $i++) {
        $otp .= mt_rand(0, 9); // Generate a random digit
    }
    return $otp;
}

// Function to send the OTP via email
function sendOTPEmail($email, $otp) {
    $subject = "Your OTP for Password Reset";
    $message = "
        <html>
        <head>
            <title>OTP for Password Reset</title>
        </head>
        <body>
            <p>Dear User,</p>
            <p>Your OTP for resetting your password is: <strong>$otp</strong></p>
            <p>Please use this OTP to complete the process. The OTP is valid for 10 minutes.</p>
            <br>
            <p>Regards,<br>Luxury Stay Support Team</p>
        </body>
        </html>
    ";

    // Email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: no-reply@luxurystay.com" . "\r\n";

    // Send the email
    return mail($email, $subject, $message, $headers);
}

// Check if the user has requested to resend the OTP
if (isset($_SESSION['user_email'])) {
    $userEmail = $_SESSION['user_email']; // Retrieve the user's email from the session

    // Generate a new OTP
    $newOtp = generateOTP();

    // Store the OTP in the session for later verification
    $_SESSION['otp'] = $newOtp;

    // Optional: Save the OTP in the database for verification
    $expiryTime = time() + (10 * 60); // OTP valid for 10 minutes
    $query = "UPDATE users SET otp = '$newOtp', otp_expiry = '$expiryTime' WHERE email = '$userEmail'";
    if (mysqli_query($conn, $query)) {
        // Send the OTP email
        if (sendOTPEmail($userEmail, $newOtp)) {
            // Success message
            $_SESSION['success'] = "A new OTP has been sent to your email.";
        } else {
            // Email sending failed
            $_SESSION['error'] = "Failed to send OTP. Please try again.";
        }
    } else {
        // Database update failed
        $_SESSION['error'] = "Failed to update OTP in the database.";
    }
} else {
    // User email not found in the session
    $_SESSION['error'] = "Unable to process your request. Please try again.";
}

// Redirect back to the OTP form page
header("Location: otp_verification.php");
exit();
?>
