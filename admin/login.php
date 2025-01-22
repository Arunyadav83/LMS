<?php
session_start();
require_once '../config.php';
require_once '../functions.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Both email and password are required.";
    } else {
        $query = "SELECT * FROM admin_users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['admin_email'] = $user['email'];

                // Set a flag for JavaScript to handle success
                echo "<script>
                    const loginSuccess = true;
                    sessionStorage.setItem('loginSuccess', 'true');
                    window.location.href = 'login.php';
                </script>";
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
    /* Global Styles */
    /* Global Styles */
    body {
        background-color: #f4f7fa;
        font-family: Arial, sans-serif;
    }

    /* Container */
    .container-fluid {
        max-width: 100%;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Flex Layout for Image and Form */
    .row {
        display: flex;
        justify-content: center;
        align-items: stretch;
        /* max-width: 600px; */
        height: 80vh;
        background-color:rgb(210, 220, 251);
        /* Ensure equal height between image and form */
    }

    /* Card Style */
    .card {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 30px;
        height: 100%;
    }

    /* Image Section */
    #loginImage {
        max-width: 100%;
        height: 100%;
        /* Ensure the image takes full height */
        object-fit: cover;
        /* Maintain aspect ratio and fill container */
        border-radius: 10px;
    }

    /* Heading Style */
    h2 {
        font-size: 2rem;
        font-weight: bold;
        color: #333;
    }

    /* Form Inputs */
    .form-label {
        font-weight: 500;
        color: #555;
    }

    .form-control {
        border-radius: 8px;
        padding: 10px;
        margin-bottom: 15px;
    }

    /* Button Style */
    .btn-primary {
        background-color: #4e73df;
        border: none;
        padding: 12px;
        border-radius: 8px;
        color: white;
        font-size: 16px;
    }

    .btn-primary:hover {
        background-color: #375aeb;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {

        .col-md-6,
        .col-lg-4 {
            margin-bottom: 30px;
        }

        .card {
            padding: 20px;
        }

        #loginImage {
           
            width: 100%;
            max-width: 300px;
            margin: 30px auto 20px auto;
            /* Hide image on small screens */
        }
    }

    /* Alert Styling */
    .alert {
        font-size: 14px;
        font-weight: 600;
    }
</style>

<body>

    <div class="container-fluid mt-5">
        <div class="row justify-content-center align-items-center">
            <!-- Image Section (Left Side) -->
            <div class="col-md-6 col-lg-5 d-flex justify-content-center align-items-center">
                <img src="../assets/images/adminlogin.png" class="img-fluid" alt="Admin Login Image" id="loginImage">
            </div>

            <!-- Form Section (Right Side) -->
            <div class="col-md-6 col-lg-4">
                <div class="card p-5 shadow-sm">
                    <h2 class="mb-4 text-center">Admin Login</h2>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    <form action="login.php" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        // Check sessionStorage for login success
        if (sessionStorage.getItem('loginSuccess') === 'true') {
            Swal.fire({
                title: 'Welcome!',
                text: 'Login successful. Redirecting...',
                icon: 'success',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                sessionStorage.removeItem('loginSuccess');
                window.location.href = 'index.php';
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>