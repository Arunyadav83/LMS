<?php
// Start output buffering
ob_start();
// Include required files
require_once 'config.php';
require_once 'functions.php';
// Start session
if (session_status() === PHP_SESSION_NONE) {
   
    session_start();
}
// $cart_count = 0;
// if (is_logged_in()) {
//     $cart_query = "SELECT COUNT(*) AS cart_count FROM cart WHERE user_id = ?";
//     $cart_stmt = mysqli_prepare($conn, $cart_query);
//     mysqli_stmt_bind_param($cart_stmt, "i", $_SESSION['user_id']);
//     mysqli_stmt_execute($cart_stmt);
//     $cart_result = mysqli_fetch_assoc(mysqli_stmt_get_result($cart_stmt));
//     $cart_count = $cart_result['cart_count'] ?? 0;
// } 


// Clear any existing output buffers and start fresh
if (ob_get_length()) ob_clean();
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
</head>
<style>
    
        .container {
            margin-top: 20px !important;
            position: relative;
        }

        .navbar {
            padding: 0.4px 10px !important;
            /* background-color: #007bff !important; */
        }
        .dropdown-item{
            color:black;
        }

</style>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="assets/images/logo.png" alt="UltraKey Logo" style="height: 35px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="courses.php"><i class="fas fa-book"></i> Courses</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.php"><i class="fas fa-life-ring"></i> About Us</a>
                </li>
                <?php if (is_logged_in()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item" href="user_profile.php">
                                    <i class="fas fa-id-card"></i> Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="logout.php">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="user_enrolled_courses.php">
                                    <i class="fas fa-book-open"></i> My Enrolled Courses
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="fetch_cart.php">
                                    <i class="fas fa-heart"></i> My Wishlist
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="fetch_cart.php?reset=true">
                            <i class="fas fa-shopping-cart"></i> Cart 
                            <span class="badge bg-danger"><?php echo $cart_count; ?></span>
                        </a>
                    </li> -->
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
    <div class="container" style="margin-top: 80px;">
        <!-- Add any page content here -->
    </div>
</body>
</html>
