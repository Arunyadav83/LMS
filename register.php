
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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/x-icon" href="assets/images/apple-touch-icon.png">
</head>
<style>
    body {
        min-height: 100vh;
        margin: 0;
        padding: 0;
        /* display: flex; */
        align-items: center;
        /* margin-left: 148px; */
        margin-top: 34px;
        /* overflow: hidden; */
        justify-content: center;
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #45B5AA, #367c76);
    }

    .form-container {
        display: flex;
        flex-direction: row;
        align-items: center;
        align-self: center;
        background-color: #fff;
        border-radius: 15px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        max-width: 1000px;
        width: 90%;
        margin-top: 89px;
        margin-left: 150px;
        
    }

    .form-section {
        flex: 1;
        padding: 40px;
    }

    .image-section {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f5f5f5;
    }

    .image-section img {
        max-width: 100%;
        height: auto;
    }

    .form-heading {
        text-align: center;
        margin-bottom: 30px;
    }

    .form-heading img {
        width: 120px;
        height: auto;
        margin-bottom: 20px;
    }

    .form-heading h2 {
        color: #333;
        font-size: 2rem;
        margin: 0;
    }

    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-row .mb-3 {
        flex: 1;
    }

    .form-label {
        color: #555;
        font-weight: 500;
        font-size: 0.95rem;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 12px;
        border: 1.5px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .form-control:focus {
        border-color: #45B5AA;
        background: white;
        box-shadow: 0 0 0 4px rgba(69, 181, 170, 0.1);
        outline: none;
    }

    .btn-primary {
        background: #45B5AA;
        color: white;
        padding: 14px;
        border: none;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        width: 100%;
        transition: all 0.3s ease;
        margin-top: 10px;
    }

    .btn-primary:hover {
        background: #367c76;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(69, 181, 170, 0.3);
    }

    .alert {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-danger {
        background: #fff2f2;
        color: #e74c3c;
        border: 1px solid #ffd1d1;
    }

    .alert-success {
        background: #f0fff4;
        color: #2ecc71;
        border: 1px solid #d1ffdd;
    }

    .mt-3 {
        margin-top: 20px;
        text-align: center;
        color: #666;
    }

    .mt-3 a {
        color: #45B5AA;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .mt-3 a:hover {
        color: #367c76;
    }
/* Responsive Design */
@media (max-width: 768px) {
    .form-container {
        flex-direction: column;
        width: 100%;
        border-radius: 0;
        padding: 20px;
    }
   
    .image-section {
        order: -1; /* Moves image above the form */
        padding: 10px;
    }

    .form-section {
        padding: 10px;
        width: 100%;
    }

    .form-row {
        flex-direction: column;
        gap: 10px;
    }

    .form-heading h2 {
        font-size: 1.8rem;
    }

    .btn-primary {
        padding: 12px;
        font-size: 1rem;
    }
}

@media (max-width: 480px) {
    .form-heading h2 {
        font-size: 1.5rem;
    }

    .form-control {
        padding: 10px;
        font-size: 0.9rem;
    }

    .btn-primary {
        padding: 10px;
        font-size: 0.95rem;
    }

    .alert {
        padding: 10px;
        font-size: 0.9rem;
    }
    .container{
        overflow-x: hidden;
        width: 100%;
    }


}
</style>
<body>
    

<div class="container">

<div class="form-container">
    <div class="form-section">
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
            <div class="form-row">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
            </div>
            <div class="form-row">
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="phonenumber" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="phonenumber" name="phonenumber" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>

        <p class="mt-3 text-center">Already have an account? <a href="login.php" style="color: #45B5AA; text-decoration: none;">Login here</a></p>
    </div>

    <div class="image-section">
        <img src="assets/images/regsitrastion2.jpg" alt="Registration Image">
    </div>
</div>

</div>
<?php include 'footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 -->
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
                    window.location.href = "login.php";
                }
            });
        <?php endif; ?>
    });
</script>


    
</body>
