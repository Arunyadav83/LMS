<?php
session_start();
require_once '../config.php';
require_once '../functions.php';

// Check if the user is logged in as an admin
if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

$current_page = 'index';

// Fetch user counts from the database
$query = "SELECT COUNT(*) AS total_users, 
                 SUM(CASE WHEN role = 'instructor' THEN 1 ELSE 0 END) AS total_instructors, 
                 SUM(CASE WHEN role = 'student' THEN 1 ELSE 0 END) AS total_students 
                 
          FROM users"; // Assuming 'users' is your table name
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

$total_users = $row['total_users'];
$total_instructors = $row['total_instructors'];
$total_students = $row['total_students'];

// Fetch total courses count
$query_courses = "SELECT COUNT(*) AS total_courses FROM courses"; // Assuming 'courses' is your table name
$result_courses = mysqli_query($conn, $query_courses);
$row_courses = mysqli_fetch_assoc($result_courses);
$total_courses = $row_courses['total_courses'];

// Fetch recent activities
$query_recent = "SELECT title, created_at FROM courses ORDER BY created_at DESC LIMIT 1"; // Fetch the latest course
$result_recent = mysqli_query($conn, $query_recent);
$recent_courses = mysqli_fetch_all($result_recent, MYSQLI_ASSOC);

// Function to format the date
function time_ago($datetime, $full = false) {
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    // Ensure that the difference is calculated correctly
    if ($diff->invert) {
        return 'just now'; // If the time is in the future
    }

    // Check if the time difference is less than 1 minute
    if ($diff->h == 0 && $diff->i == 0 && $diff->s < 60) {
        return 'just now'; // Show "just now" for activities within the last minute
    }

    // Check if the time difference is less than 30 minutes
    if ($diff->h == 0 && $diff->i < 30) {
        return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago'; // Show minutes for activities within the last 30 minutes
    }

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = [];
    $units = [
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    ];

    foreach ($units as $k => &$v) {
        if ($diff->$k) {
            $string[] = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 250px;
        }
        
        body {
            background-color: #f8f9fa;
            padding-top: 56px;
        }
        
        /* Navbar Styling */
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
            background: linear-gradient(to right, #1a237e, #0d47a1) !important;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            box-shadow: 2px 0 5px rgba(0,0,0,.1);
            height: calc(100vh - 56px);
            position: fixed;
            transition: all 0.3s;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            transition: all 0.3s;
        }
        
        /* Dashboard Cards */
        .dashboard-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,.1);
            transition: transform 0.3s;
            margin-bottom: 30px;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        
        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .card.dashboard-card {
    margin-bottom: 30px; /* Adjust this value to increase or decrease the space */
}

/* Optional: If you want to specifically target the Quick Actions card */
.quick-actions-card {
    margin-top: 20px; /* Adjust this value for additional spacing above the Quick Actions card */
}

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: calc(var(--sidebar-width) * -1);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .sidebar.active {
                margin-left: 0;
            }
        }

        .icon-box {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .filter-buttons .btn {
            border-radius: 20px;
            padding: 0.375rem 1rem;
        }

        .filter-buttons .btn.active {
            background-color: #0d6efd;
            color: white;
        }

        .card .btn-link {
            color: #6c757d;
            text-decoration: none;
        }

        .search-container .input-group-text,
        .search-container .form-control {
            background-color: #f8f9fa;
        }

        .search-container .form-control:focus {
            background-color: #fff;
            box-shadow: none;
            border-color: #ced4da;
        }

        .quick-actions-card {
            margin-top: 20px;
        }

        /* Add this CSS class for the smaller navbar */
        .navbar.scrolled {
            padding: 0.5rem 1rem; /* Adjust padding as needed */
            transition: padding 0.3s; /* Smooth transition */
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">LMS Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php"><i class="fas fa-user"></i> Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include 'sidebar.php'; ?>

            <!-- Main Content -->
            <main class="col main-content">
                <div class="container-fluid">
                    <h1 class="mb-4 fw-bold" style="color:#00CED1">Admin Dashboard</h1>
                    
                    <!-- Statistics Cards Row -->
                    <div class="row g-4 mb-4">
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="card dashboard-card  text-white" style="background-color: #d63384; color: white;">
                                <div class="card-body text-center">
                                    <i class="fas fa-users card-icon"></i>
                                    <h3 class="card-title"><?php echo $total_users; ?></h3>
                                    <p class="card-text">Total Users</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="card dashboard-card  text-white" style="background-color: #fd7e14; color: white;">
                                <div class="card-body text-center">
                                    <i class="fas fa-user-graduate card-icon"></i>
                                    <h3 class="card-title"><?php echo $total_students; ?></h3>
                                    <p class="card-text">Total Students</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="card dashboard-card  text-white" style="background-color: #20c997; color: white;">
                                <div class="card-body text-center">
                                    <i class="fas fa-chalkboard-teacher card-icon"></i>
                                    <h3 class="card-title"><?php echo $total_instructors; ?></h3>
                                    <p class="card-text">Total Instructors</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="card dashboard-card  text-white " style="background-color: #0dcaf0; color: white;">
                                <div class="card-body text-center">
                                    <i class="fas fa-book card-icon"></i>
                                    <h3 class="card-title"><?php echo $total_courses; ?></h3>
                                    <p class="card-text">Total Courses</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity Section -->
                    <div class="row mb-4">
                        <div class="col-12 col-xl-8 mb-4" style="margin-top: 40px" , >
                            <div class="card dashboard-card">
                                <div class="card-header bg-white">
                                    <h5 class="card-title mb-0">Recent Activities</h5>
                                </div>
                                <div class="card-body">
                                    <div class="list-group list-group-flush">
                                        <?php if (!empty($recent_courses)): ?>
                                            <?php foreach ($recent_courses as $course): ?>
                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1">New course added: <?php echo htmlspecialchars($course['title']); ?></h6>
                                                        <small class="text-muted"><?php echo time_ago($course['created_at']); ?></small>
                                                    </div>
                                                    <span class="badge bg-primary rounded-pill">New</span>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="list-group-item">No recent activities.</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-xl-4 mb-4 quick-actions-card">
                            <div class="card dashboard-card">
                                <div class="card-header bg-white">
                                    <h5 class="card-title mb-0">Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="add_course.php"><button class="btn btn-primary"><i class="fas fa-plus"></i> Add New Course</button></a>
                                        <a href="users.php"><button class="btn btn-success"><i class="fas fa-user-plus"></i> Add New User</button></a>
                                        <a href="settings.php"><button class="btn btn-info"><i class="fas fa-cog"></i> Settings</button></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add this after your existing cards section -->
                    <!-- <div class="row">
                        <div class="col-12 mb-4">
                            <div class="card dashboard-card">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Quick Access</h5>
                                    <button type="button" class="btn-close" aria-label="Close"></button>
                                </div>
                                <div class="card-body">
                                     Search Bar -->
                                    <!-- <div class="search-container mb-4"> -->
                                        <!-- <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="fas fa-search text-muted"></i>
                                            </span>
                                            <input type="text" class="form-control border-start-0" placeholder="Search here">
                                        </div>
                                    </div> -->

                                    <!-- Filter Buttons -->
                                    <!-- <div class="filter-buttons mb-4">
                                        <div class="d-flex flex-wrap gap-2">
                                            <button class="btn btn-primary active">All</button>
                                            <button class="btn btn-outline-secondary">My Favourites</button>
                                            <button class="btn btn-outline-secondary">Employee</button>
                                            <button class="btn btn-outline-secondary">Payroll</button>
                                            <button class="btn btn-outline-secondary">Leave</button>
                                            <button class="btn btn-outline-secondary">Other</button>
                                        </div>
                                    </div> -->

                                    <!-- Quick Access Cards
                                    <div class="row g-4">
                                        Add Holidays Card -->
                                       <!--- <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                                            <div class="card h-100 border rounded">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between">
                                                        <div class="icon-box bg-info bg-opacity-10 rounded p-2">
                                                            <i class="fas fa-calendar text-info"></i>
                                                        </div>
                                                        <button class="btn btn-link p-0">
                                                            <i class="far fa-star"></i>
                                                        </button>
                                                    </div>
                                                    <h6 class="mt-3">Add Holidays</h6>
                                                </div>
                                            </div>
                                        </div> -->

                                        <!-- Prepare Letter Card
                                        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                                            <div class="card h-100 border rounded">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between">
                                                        <div class="icon-box bg-primary bg-opacity-10 rounded p-2">
                                                            <i class="fas fa-file-alt text-primary"></i>
                                                        </div>
                                                        <button class="btn btn-link p-0">
                                                            <i class="far fa-star"></i>
                                                        </button>
                                                    </div>
                                                    <h6 class="mt-3">Prepare Letter</h6>
                                                </div>
                                            </div>
                                        </div> -->

                                        <!-- Import Data Card -->
                                        <!-- <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                                            <div class="card h-100 border rounded">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between">
                                                        <div class="icon-box bg-success bg-opacity-10 rounded p-2">
                                                            <i class="fas fa-file-excel text-success"></i>
                                                        </div>
                                                        <button class="btn btn-link p-0">
                                                            <i class="far fa-star"></i>
                                                        </button>
                                                    </div>
                                                    <h6 class="mt-3">Import Data From Excel</h6>
                                                </div>
                                            </div>
                                        </div> -->

                                        <!-- Add more quick access cards following the same pattern -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar on mobile
        document.querySelector('.navbar-toggler').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });

        // Change navbar style on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) { // Adjust the scroll threshold as needed
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>
