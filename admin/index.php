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

// Fetch unread messages count
$query_unread = "SELECT COUNT(*) AS unread_count FROM messages WHERE status = 'unread'"; // Adjust the table and column names as necessary
$result_unread = mysqli_query($conn, $query_unread);

// Check if the query was successful
if ($result_unread) {
    $row_unread = mysqli_fetch_assoc($result_unread);
    $unread_count = $row_unread['unread_count'];
} else {
    // Output the error message
    echo "Error: " . mysqli_error($conn);
    $unread_count = 0; // Set to 0 or handle as needed
}


// Query to fetch total revenue from the payments table
$query_revenue = "SELECT SUM(amount) AS total_revenue FROM payments WHERE status = 'success'"; // Adjust 'completed' status if necessary
$result_revenue = mysqli_query($conn, $query_revenue);

// Fetch the total revenue result
$row_revenue = mysqli_fetch_assoc($result_revenue);
$total_revenue = $row_revenue['total_revenue'] ? $row_revenue['total_revenue'] : 0;
// Fetch most enrolled courses
$query = "
    SELECT 
        course_id, 
        course_name, 
        COUNT(*) as total_enrollments 
    FROM enrollments 
    GROUP BY course_id, course_name 
    ORDER BY total_enrollments DESC 
    LIMIT 3";
$result = mysqli_query($conn, $query);

if ($result) {
    $most_enrolled_courses = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    echo "Error: " . mysqli_error($conn);
    $most_enrolled_courses = []; // Handle as needed
}
$query = "
    SELECT 
        enrollments.user_id,
        courses.title AS course_title,
        enrollments.enrolled_at
    FROM 
        enrollments
    INNER JOIN courses ON enrollments.course_id = courses.id
    ORDER BY enrollments.enrolled_at DESC
    LIMIT 3
";
$recent_enrollments =mysqli_query($conn, $query);
function time_ago($datetime, $full = false)
{
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

    // Manually calculate weeks based on days
    $weeks = floor($diff->d / 7);
    $diff->d -= $weeks * 7; // Update days after calculating weeks

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

    foreach ($units as $k => $v) {
        if ($k === 'w' && $weeks) {
            $string[] = $weeks . ' ' . $v . ($weeks > 1 ? 's' : '');
        } elseif ($k !== 'w' && $diff->$k) {
            $string[] = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        }
    }

    if (!$full) {
        $string = array_slice($string, 0, 1);
    }

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
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
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
            box-shadow: 2px 0 5px rgba(0, 0, 0, .1);
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
            box-shadow: 0 4px 6px rgba(0, 0, 0, .1);
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
            margin-bottom: 30px;
            /* Adjust this value to increase or decrease the space */
        }

        /* Optional: If you want to specifically target the Quick Actions card */
        .quick-actions-card {
            margin-top: 20px;
            /* Adjust this value for additional spacing above the Quick Actions card */
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
            padding: 0.7rem 1rem;
            /* Adjust padding as needed */
            transition: padding 0.3s;
            /* Smooth transition */
        }

        .badge {
            background-color: red;
            /* Set badge color to red */
        }

        .navbar {
            background-color: #16308b;
        }

        .text-right {
            float: right;
        }

        .navbar-link {
            color: white;
            padding-inline: 20px;
            text-decoration: none;

        }

        .navbar-link:hover {
            color: white;
            text-decoration: underline;
        }

        .button {
            padding-inline: 10px;
            text-decoration: none;
            color: #0433c3;
            padding-block: 10px;

        }

        .button:hover {
            background-color: #0433c3;
            color: white;
            border-radius: 30px !important;
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
                        <a class="navbar-link" href="profile.php"><i class="fas fa-user"></i> Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="navbar-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                    <li class="nav-item">
                        <a class="navbar-link position-relative" href="fetchmessages.php" id="notificationLink">
                            <i class="fas fa-bell"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" id="messageCount" style="display: <?php echo $unread_count > 0 ? 'inline' : 'none'; ?>;"><?php echo $unread_count; ?></span>
                        </a>
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
                    <h3 class=" fw-bold" style="color:#16308b; margin-bottom:45px">Admin Dashboard</h3>

                    <!-- Statistics Cards Row -->
                    <div class="row g-4 mb-4">
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="card dashboard-card text-white" style="background-color: #ff8c00; color: white;">
                                <div class="card-body text-center">
                                    <i class="fas fa-rupee-sign card-icon" style="font-size: 1.5rem;"></i>
                                    <!-- Title for Revenue -->
                                    <p class="card-text" style="font-size: 1rem; margin-top: -10px;">Total Revenue</p>
                                    <!-- Revenue Amount with increased font size -->
                                    <h3 class="card-title" style="font-size: 2.2rem; font-weight: bold; margin-top: -10px;">
                                        ₹<?php echo number_format($total_revenue, 0); ?> <!-- Display total revenue -->
                                    </h3>
                                    <!-- Additional description text -->
                                    <small  style="font-size: 0.9rem; color: white;">As of Today</small>

                                </div>
                            </div>
                        </div>



                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="card dashboard-card  text-white" style="background-color:#8314fd; color: white;">
                                <div class="card-body text-center">
                                    <i class="fas fa-user-graduate card-icon"></i>
                                    <p class="card-text" style="font-size: 1rem; margin-top: -5px;">Total Students</p>
                                    <h3 class="card-title" style="font-size: 2rem; font-weight: bold; margin-top: -10px;"><?php echo $total_students; ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="card dashboard-card  text-white" style="background-color: #0433c3; color: white;">
                                <div class="card-body text-center">
                                    <i class="fas fa-chalkboard-teacher card-icon"></i>
                                    <p class="card-text" style="font-size: 1rem; margin-top: -5px;">Total Instructors</p>
                                    <h3 class="card-title" style="font-size: 2rem; font-weight: bold; margin-top: -10px;"><?php echo $total_instructors; ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="card dashboard-card  text-white " style="background-color:#9e00db; color: white;">
                                <div class="card-body text-center">
                                    <i class="fas fa-book card-icon"></i>
                                    <p class="card-text" style="font-size: 1rem; margin-top: -5px;">Total Courses</p>
                                    <h3 class="card-title" style="font-size: 2rem; font-weight: bold; margin-top: -10px;"><?php echo $total_courses; ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 quick-actions-card">
                        <div class="card dashboard-card">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0" style="color: #0433c3;">Quick Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-around flex-wrap gap-2">
                                    <a href="add_course.php" class="button">
                                        <i class="fas fa-plus"></i> Add New Course
                                    </a>
                                    <a href="users.php" class="button ">
                                        <i class="fas fa-user-plus"></i> Add New User
                                    </a>
                                    <a href="settings.php" class="button">
                                        <i class="fas fa-cog"></i> Settings
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity Section -->
                    <div class="card dashboard-card">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0" style="color: #0433c3;">Recent Activities</h5>
    </div>
    <div class="card-body">
        <div class="d-flex align-items-center flex-wrap gap-4">
            <!-- Heading on the left -->
            <div class="text-left">
                <h6 class="mb-1">Recent Enrollments:</h6>
            </div>

            <!-- Loop through enrollments -->
            <?php foreach ($recent_enrollments as $enrollment): ?>
                <div class="d-flex flex-row gap-2 justify-items-between align-items-center text-center activity-item">
                    <p class="text-muted mb-1">
                        <?php echo htmlspecialchars($enrollment['user_id']); ?> 
                        enrolled in 
                        "<?php echo htmlspecialchars($enrollment['course_title']); ?>"
                    </p>
                    <small class="text-muted">
                        <?php echo time_ago($enrollment['enrolled_at']); ?>
                    </small>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

                    <div class="card dashboard-card">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0" style="color: #0433c3;">Most Enrolled Courses</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-4">
                                <!-- Heading on the left -->
                                <div class="text-left">
                                    <!-- <h6 class="mb-1">Top Courses:</h6> -->
                                </div>

                                <!-- Loop through most enrolled courses -->
                                <?php foreach ($most_enrolled_courses as $course): ?>
                                    <div class="d-flex flex-column align-items-center text-center activity-item" style="margin-bottom: 20px;background: #f8f9fa; border-radius: 10px; padding: 15px;">
                                        <!-- Course Image -->
                                        <img
                                            src="../assets/images/<?php echo htmlspecialchars($course['course_name']); ?>.jpg"
                                            alt="<?php echo htmlspecialchars($course['course_name']); ?>"
                                            class="rounded"
                                            style="width: 180px; height: 100px; object-fit: contain; margin-bottom: 10px;" />

                                        <!-- Course Name -->
                                        <p class="mb-1 font-weight-bold"><?php echo htmlspecialchars($course['course_name']); ?></p>

                                        <!-- Total Enrollments -->
                                        <small class="text-muted mb-1"><?php echo $course['total_enrollments']; ?> Enrollments</small>

                                        <!-- Static Star Rating -->
                                        <div style="color: #FFD700; font-size: 16px; margin-top: 5px;">
                                            ★ ★ ★ ★ ☆
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                            </div>
                        </div>
                    </div>

            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let messageCount = 0; // Initialize message count

        // Fetch new messages count
        function fetchNewMessages() {
            fetch('fetchmessages.php') // New endpoint to get unread message count
                .then(response => response.json())
                .then(data => {
                    const messageCountElement = document.getElementById('messageCount');
                    if (data.unread_count > messageCount) {
                        messageCount = data.unread_count; // Update message count
                        messageCountElement.textContent = messageCount;
                        messageCountElement.style.display = 'inline'; // Show the badge
                    } else if (data.unread_count === 0) {
                        messageCountElement.style.display = 'none'; // Hide if no messages
                    }
                })
                .catch(error => console.error('Error fetching messages:', error));
        }

        // Function to reset message count and clear messages
        function resetMessages() {
            messageCount = 0; // Reset count
            const messageCountElement = document.getElementById('messageCount');
            messageCountElement.style.display = 'none'; // Hide the badge

            const messagesContainer = document.getElementById('messagesContainer');
            messagesContainer.innerHTML = ''; // Clear existing messages
        }

        // Call this function when the dashboard is loaded
        document.addEventListener('DOMContentLoaded', function() {
            resetMessages(); // Reset messages when the dashboard is loaded
        });

        // Reset message count and update status when the notification is clicked
        document.getElementById('notificationLink').addEventListener('click', function() {
            fetch('reset_message_status.php') // Call the PHP file to reset message status
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        messageCount = 0; // Reset count
                        const messageCountElement = document.getElementById('messageCount');
                        messageCountElement.style.display = 'none'; // Hide the badge
                        fetchMessages(); // Fetch and display messages
                    }
                })
                .catch(error => console.error('Error resetting message status:', error));
        });

        // Function to fetch and display messages
        function fetchMessages() {
            fetch('fetchmessages.php') // Adjust this to your actual endpoint
                .then(response => response.json())
                .then(messages => {
                    const messagesContainer = document.getElementById('messagesContainer'); // Ensure you have a container for messages
                    messagesContainer.innerHTML = ''; // Clear existing messages

                    if (messages.length > 0) {
                        messages.forEach(message => {
                            const messageElement = document.createElement('div');
                            messageElement.classList.add('message-item');
                            messageElement.innerHTML = `
                                <p>${message.content}</p> <!-- Adjust based on your message structure -->
                                <small>${time_ago(message.created_at)}</small> <!-- Assuming you have a time_ago function -->
                            `;
                            messagesContainer.appendChild(messageElement);
                        });
                    } else {
                        messagesContainer.innerHTML = '<p>No new messages.</p>';
                    }
                })
                .catch(error => console.error('Error fetching messages:', error));
        }

        // Call the function to fetch new messages count
        fetchNewMessages();

        // Set interval to fetch new messages every 5 seconds
        setInterval(fetchNewMessages, 5000);
    </script>
</body>

</html>