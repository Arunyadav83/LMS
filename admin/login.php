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
    body {
        background: linear-gradient(135deg, #45B5AA, #367c76);
        font-family: 'Segoe UI', sans-serif;
        margin: 0;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    /* Container */
    .container-fluid {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Row Layout */
    .row {
        background: white;
        border-radius: 20px;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        display: flex;
        align-items: center;
        min-height: 400px;
        justify-content: center;
        max-width: 1000px;
        margin: 0 auto;
    }

    /* Image Section */
    .col-md-6.col-lg-5 {
        padding: 0;
    }

    #loginImage {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: all 0.3s ease;
    }

    /* Form Section */
    .col-md-6.col-lg-4 {
        padding: 0px;
    }

    .card {
        border: none;
        box-shadow: none;
        padding: 0;
        background: transparent;
    }

    /* Form Elements */
    h2 {
        color: #333;
        font-size: 2.2rem;
        font-weight: 600;
        margin-bottom: 30px;
        text-align: center;
    }

    .form-label {
        color: #555;
        font-weight: 500;
        font-size: 0.95rem;
        margin-bottom: 8px;
    }

    .form-control {
        padding: 14px 16px;
        border: 1.5px solid #ddd;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .form-control:focus {
        border-color: #45B5AA;
        box-shadow: 0 0 0 4px rgba(69, 181, 170, 0.1);
        outline: none;
    }

    .btn-primary {
        background: #45B5AA;
        color: white;
        padding: 14px;
        border: none;
        border-radius: 10px;
        font-size: 1.1rem;
        font-weight: 600;
        transition: all 0.3s ease;
        margin-top: 10px;
    }

    .btn-primary:hover {
        background: #367c76;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(69, 181, 170, 0.3);
    }

    /* Alert Styling */
    .alert {
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 25px;
        font-size: 0.95rem;
    }

    .alert-danger {
        background: #fff2f2;
        color: #e74c3c;
        border: 1px solid #ffd1d1;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .container-fluid {
            padding: 20px;
        }
        
        .row {
            min-height: 500px;
        }
    }

    @media (max-width: 992px) {
        .col-md-6.col-lg-4 {
            padding: 30px;
        }

        h2 {
            font-size: 2rem;
        }
    }

    @media (max-width: 768px) {
        body {
            padding: 15px;
        }

        .row {
            flex-direction: column;
            min-height: auto;
        }

        .col-md-6.col-lg-5 {
            display: none; /* Hide image on mobile */
        }

        .col-md-6.col-lg-4 {
            width: 100%;
            padding: 25px;
        }

        h2 {
            font-size: 1.8rem;
            margin-bottom: 25px;
        }

        .form-control {
            padding: 12px 14px;
        }

        .btn-primary {
            padding: 12px;
            font-size: 1rem;
        }
    }

    @media (max-width: 480px) {
        body {
            padding: 10px;
        }

        .col-md-6.col-lg-4 {
            padding: 20px;
        }

        h2 {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .form-control {
            padding: 10px 12px;
            font-size: 0.95rem;
        }

        .btn-primary {
            padding: 10px;
            font-size: 0.95rem;
        }

        .alert {
            padding: 12px;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
    }

    /* Handle Mobile Input */
    @media (max-width: 768px) {
        input[type="email"],
        input[type="password"] {
            font-size: 16px; /* Prevents zoom on iOS */
        }

        .form-control {
            min-height: 44px; /* Better touch targets */
        }
    }

    /* Hide image on tablet and mobile */
    @media (max-width: 1024px) {
        .col-md-6.col-lg-5 {
            display: none !important; /* Force hide with !important */
        }

        /* Adjust form section when image is hidden */
        .col-md-6.col-lg-4 {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            padding: 30px;
        }

        .row {
            justify-content: center;
            min-height: auto;
        }
    }

    /* Additional mobile optimizations */
    @media (max-width: 768px) {
        .col-md-6.col-lg-4 {
            padding: 25px;
            max-width: 400px;
        }
    }

    @media (max-width: 480px) {
        .col-md-6.col-lg-4 {
            padding: 20px;
            max-width: 100%;
        }
    }
    .p-5 {
    padding: 2rem !important;
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
                text: 'Login successful....',
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