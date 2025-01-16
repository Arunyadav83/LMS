<?php
require_once 'config.php';
require_once 'functions.php';
require 'vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } else {
        // Check if username or email already exists
        $check_query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $error = "Username or email already exists.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $insert_query = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashed_password', 'student')";
            if (mysqli_query($conn, $insert_query)) {
                $success = "Registration successful. You can now log in.";

                // Send confirmation email
                $mail = new PHPMailer(true);
                try {
                    // SMTP configuration
                    $mail->isSMTP();
                    $mail->Host = 'smtp.hostinger.com'; // Replace with your SMTP server
                    $mail->SMTPAuth = true;
                    $mail->Username = 'arun.bhairi@ultrakeyit.com'; // Replace with your email
                    $mail->Password = 'Arun@1234'; // Replace with your email password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // Email settings
                    $mail->setFrom('arun.bhairi@ultrakeyit.com', 'Ultrakey Learning'); // Replace with your email and name
                    $mail->addAddress($email, $username);

                    $mail->isHTML(true);
                    $mail->Subject = 'Welcome to Our Website!';
                    $mail->Body = "
                        <h2>Thank you for registering with us!</h2>
                        <p>Hi <b>$username</b>,</p>
                        <p>We are thrilled to have you on board. Below are your login details:</p>
                        <ul>
                            <li><b>Email:</b> $email</li>
                            <li><b>Password:</b> $password</li>
                        </ul>
                        <p>Please keep this information secure.</p>
                        <p>Best regards,<br>Ultrakey Learning</p>
                    ";

                    $mail->send();
                } catch (Exception $e) {
                    $error = "Registration successful, but email could not be sent. Error: {$mail->ErrorInfo}";
                }
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}

include 'header.php';
?>
<style>
    body {
        background-color: rgb(151, 192, 232);
        height: 800px;
    }

    .form-container {
        max-width: 500px;
        /* height: 800px; */
        margin: 100px auto 30px;
        /* Increased top margin to 80px */
        background: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .form-heading {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    h2 {
        margin-top: 4%;
        margin-left: 3%;
    }

    img {
        width: 100px;
        height: 50px;

    }
</style>

<div class="form-container">
    <div class="form-heading">
        <img src="assets/images/logo2.png" alt="Logo">
        <h2>Student Registration</h2>
    </div>

    <?php if ($error): ?>
        <div id="error_message" class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div id="success_message" class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form action="register.php" method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>

    <p class="mt-3 text-center">Already have an account? <a href="login.php">Login here</a></p>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<?php include 'footer.php'; ?>

<!-- Display SweetAlert2 based on success or error -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        <?php if ($error): ?>
            Swal.fire({
                title: 'Error!',
                text: '<?php echo $error; ?>',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>

        <?php if ($success): ?>
            Swal.fire({
                title: 'Success!',
                text: '<?php echo $success; ?>',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to login page after success
                    window.location.href = "login.php";
                }
            });
        <?php endif; ?>
    });
</script>
