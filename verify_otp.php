// Example of setting OTP in session
<?php
session_start();
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entered_otp = $_POST['otp'];

    // Check if OTP session is set and the entered OTP matches
    if (isset($_SESSION['otp']) && $entered_otp == $_SESSION['otp']) {
        // OTP is correct, redirect to reset password page
        header("Location: reset_password.php");
        exit(); // Always call exit after a header redirection
    } else {
        $error = "Invalid OTP.";
    }
}
?>
   <style>
        /* General Body Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            /* background: linear-gradient(105deg,rgb(211, 176, 248),rgb(139, 175, 236)); */
            color: #fff;
        }

        /* Form Container */
        .form-container {
            background: #fff;
            color: #333;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        /* Heading Styling */
        .form-container h2 {
            margin-bottom: 1rem;
            color: #6a11cb;
            font-size: 1.8rem;
        }

        /* Form Input Styling */
        .form-container .form-control {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .form-container .form-control:focus {
            border-color:rgb(132, 183, 236);
            outline: none;
            box-shadow: 0 0 5px rgba(106, 17, 203, 0.5);
        }

        /* Button Styling */
        .form-container .btn {
            background:rgb(110, 172, 227);
            color: #fff;
            border: none;
            padding: 0.8rem 1.2rem;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease-in-out;
            width: 100%;
        }

        .form-container .btn:hover {
            background: #2575fc;
        }

        /* Additional Styling for Small Screens */
        @media (max-width: 768px) {
            .form-container {
                padding: 1.5rem;
            }

            .form-container h2 {
                font-size: 1.5rem;
            }
        }
    </style>

<div class="form-container">
    <h2>Verify OTP</h2>
    <form action="verify_otp.php" method="post">
        <label for="otp" class="form-label">Enter OTP</label>
        <input type="text" class="form-control" id="otp" name="otp" placeholder="Enter the 6-digit OTP" required>
        <button type="submit" class="btn">Verify OTP</button>
    </form>
</div>

<?php if ($error): ?>
    <p class="text-danger"><?php echo $error; ?></p>
<?php endif; ?>