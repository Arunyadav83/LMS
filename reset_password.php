<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newPassword = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Validate passwords
    if ($newPassword !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        // Assuming the user's email is stored in the session
        if (isset($_SESSION['email'])) {
            $userEmail = $_SESSION['email'];

            // Update the password in the database
            $query = "UPDATE users SET password = '$hashedPassword' WHERE email = '$userEmail'";
            if (mysqli_query($conn, $query)) {
                $success = "Password reset successful!";
            } else {
                $error = "Failed to reset password. Please try again.";
            }
        } else {
            $error = "Session expired. Please try logging in again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script>
        // JavaScript Validation
        function validateForm() {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (newPassword !== confirmPassword) {
                alert("Passwords do not match!");
                return false;
            }
            return true;
        }
    </script>
    <style>
        /* Styling */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, rgb(59, 197, 221), rgb(158, 235, 177));
            color: #fff;
        }
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
        .form-container h2 {
            margin-bottom: 1rem;
            color: #ff9a9e;
            font-size: 1.8rem;
        }
        .form-container .form-control {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        .form-container .form-control:focus {
            border-color: #ff9a9e;
            outline: none;
            box-shadow: 0 0 5px rgba(255, 154, 158, 0.5);
        }
        .form-container .btn {
            background: #ff9a9e;
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
            background: #fad0c4;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Reset Password</h2>
        <form action="reset_password.php" method="post" onsubmit="return validateForm()">
            <label for="new_password" class="form-label">New Password</label>
            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter new password" required>

            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Re-enter new password" required>

            <button type="submit" class="btn">Reset Password</button>
        </form>
    </div>

    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if ($success): ?>
        <script>
            Swal.fire({
                title: 'Success!',
                text: '<?php echo $success; ?>',
                icon: 'success',
                confirmButtonText: 'Ok',
                timer: 2000, // Redirect after 2 seconds
                timerProgressBar: true
            }).then((result) => {
                if (result.isConfirmed || result.dismiss === Swal.DismissReason.timer) {
                    window.location.href = 'login.php'; // Redirect to login page
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
</body>
</html>