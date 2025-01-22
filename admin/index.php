<?php
session_start();
require_once '../config.php';
require_once '../functions.php';

if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}


$current_page = 'index';

// Fetch user counts from the database
$query = "SELECT 
            COUNT(*) AS total_users, 
            SUM(CASE WHEN role = 'student' THEN 1 ELSE 0 END) AS total_students 
          FROM users"; // Assuming 'users' is your table name

$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);

    $total_users = $row['total_users'];
    $total_students = $row['total_students'];
} else {
    echo "Error: " . mysqli_error($conn);
    $total_users = 0;
    $total_students = 0;
}

// Fetch total instructors count from the tutors table
$tutors_count_query = "SELECT COUNT(*) AS total_instructors FROM tutors"; // Assuming 'tutors' is your table name
$tutors_count_result = mysqli_query($conn, $tutors_count_query);

if ($tutors_count_result) {
    $row_tutors = mysqli_fetch_assoc($tutors_count_result);
    $total_instructors = $row_tutors['total_instructors'];
} else {
    echo "Error: " . mysqli_error($conn);
    $total_instructors = 0;
}

// Fetch total courses count
$query_courses = "SELECT COUNT(*) AS total_courses FROM courses"; // Assuming 'courses' is your table name
$result_courses = mysqli_query($conn, $query_courses);

if ($result_courses) {
    $row_courses = mysqli_fetch_assoc($result_courses);
    $total_courses = $row_courses['total_courses'];
} else {
    echo "Error: " . mysqli_error($conn);
    $total_courses = 0;
}

// Fetch recent activities (latest course)
$query_recent = "SELECT title, created_at FROM courses ORDER BY created_at DESC LIMIT 1"; // Fetch the latest course
$result_recent = mysqli_query($conn, $query_recent);

if ($result_recent) {
    $recent_courses = mysqli_fetch_all($result_recent, MYSQLI_ASSOC);
} else {
    echo "Error: " . mysqli_error($conn);
    $recent_courses = [];
}

// Fetch unread messages count
$query_unread = "SELECT COUNT(*) AS unread_count FROM messages WHERE status = 'unread'"; // Adjust the table and column names as necessary
$result_unread = mysqli_query($conn, $query_unread);

if ($result_unread) {
    $row_unread = mysqli_fetch_assoc($result_unread);
    $unread_count = $row_unread['unread_count'];
} else {
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
    users.username, 
    courses.title AS course_title,
    enrollments.enrolled_at
FROM 
    enrollments
INNER JOIN users ON enrollments.user_id = users.id
INNER JOIN courses ON enrollments.course_id = courses.id
ORDER BY enrollments.enrolled_at DESC
LIMIT 3;

";
$recent_enrollments = mysqli_query($conn, $query);
function time_ago($datetime, $full = false)
{
    // Set the timezone to match your data
    $timezone = new DateTimeZone('Asia/Kolkata'); // Adjust to your time zone if needed

    // Create DateTime objects
    $now = new DateTime('now', $timezone);
    $ago = new DateTime($datetime, $timezone);
    $diff = $now->diff($ago);

    // If the provided time is in the future
    if ($diff->invert) {
        return 'just now';
    }

    // Check if the difference is less than 1 minute
    if ($diff->h == 0 && $diff->i == 0 && $diff->s < 60) {
        return 'just now';
    }

    // Handle time differences dynamically
    $string = [];
    $units = [
        'y' => 'year',
        'm' => 'month',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    ];

    foreach ($units as $key => $unit) {
        if ($diff->$key) {
            $string[] = $diff->$key . ' ' . $unit . ($diff->$key > 1 ? 's' : '');
        }
    }

    // Handle weeks separately (if days exceed 7)
    if ($diff->d >= 7) {
        $weeks = floor($diff->d / 7);
        $string = [$weeks . ' week' . ($weeks > 1 ? 's' : '')];
    }

    // Return the first unit for a concise output or full string
    if (!$full) {
        $string = array_slice($string, 0, 1);
    }

    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

$query = "
SELECT 
    p.id,
    CONCAT(u.username) AS username,  -- Adjusted to use first and last name
    title AS course_name,
    p.amount,
    p.status,
    p.created_at
FROM payments p
JOIN users u ON p.user_id = u.id  -- Join the users table using user_id
JOIN courses c ON p.course_id = c.id  -- Join the courses table using course_id
ORDER BY p.created_at DESC
LIMIT 5
";

$stmt = mysqli_query($conn, $query);

if ($stmt) {
    $results = [];
    while ($row = mysqli_fetch_assoc($stmt)) {
        $results[] = $row;
    }
    // Use $results as an array of rows
    // print_r($results); // Debugging output
} else {
    // Debug the error if query fails
    echo "Error: " . mysqli_error($conn);
}




function get_recent_students()
{
    // Assuming you are using PDO to connect to your database
    global $pdo;  // Your PDO connection object

    $sql = "SELECT * FROM users WHERE role = 'student' ORDER BY created_at DESC LIMIT 5";  // Adjust LIMIT as needed
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Include FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

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

        /* Calendar Container */
        #calendar {
            width: 100%;
            max-width: 320px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
            font-family: 'Arial', sans-serif;
        }

        /* Header */
        #calendar .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        #calendar .calendar-header h3 {
            margin: 0;
            font-size: 1.2rem;
            color: #333;
        }

        #calendar .calendar-header button {
            background: #0433c3;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s;
        }

        #calendar .calendar-header button:hover {
            background: #021a66;
        }

        /* Days of the Week */
        #calendar .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            margin-top: 10px;
            text-align: center;
            font-weight: bold;
            color: #555;
        }

        /* Days Grid */
        #calendar .calendar-dates {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            grid-gap: 5px;
            margin-top: 10px;
        }

        #calendar .calendar-dates div {
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            transition: background 0.3s;
            cursor: pointer;
            font-size: 0.9rem;
        }

        /* Hover Effect */
        #calendar .calendar-dates div:hover {
            background: #f1f1f1;
        }

        /* Today Highlight */
        #calendar .calendar-dates .today {
            background: #ff8c00;
            color: #fff;
            font-weight: bold;
        }

        /* Event Days */
        #calendar .calendar-dates .event {
            background: #0433c3;
            color: #fff;
            font-weight: bold;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            #calendar {
                max-width: 100%;
            }

            .fc-toolbar.fc-header-toolbar {
                display: none;
                /* Hides the toolbar with week/month/day options */
            }

            .table_list {
                overflow-x: auto;
                width: 100%
            }
            :root {
            --sidebar-width: 250px;
        }


        }
    </style>
    <!-- <script>
        $(document).ready(function() {
            // Initialize FullCalendar
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth', // Month view
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [ // Example events
                    {
                        title: 'Event 1',
                        start: '2025-01-20'
                    },
                    {
                        title: 'Event 2',
                        start: '2025-01-22',
                        end: '2025-01-25'
                    }
                ]
            });
            calendar.render();
        });
        document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            left: '', // Leave empty to remove "week", "month", etc.
            center: 'title',
            right: ''
        },
        initialView: 'dayGridMonth' // Specify the default view
    });

    calendar.render();
});

    </script> -->

</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark ">
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
                                    <small style="font-size: 0.9rem; color: white;">As of Today</small>

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
                            <!-- Recent Enrollments Section -->
                            <div class="d-flex align-items-center flex-wrap gap-4 mb-4">
                                <!-- Heading for Recent Enrollments -->
                                <div class="text-left w-100">
                                    <h6 class="mb-3">Recent Enrollments:</h6>
                                </div>

                                <!-- Loop through enrollments -->
                                <!-- Loop through enrollments -->
                                <?php foreach ($recent_enrollments as $enrollment): ?>
                                    <div class="d-flex flex-row gap-2 justify-items-between align-items-center text-center activity-item">
                                        <p class="text-muted mb-1">
                                            <!-- User ID: <?php echo htmlspecialchars($enrollment['user_id']); ?> -->
                                            <strong><?php echo htmlspecialchars($enrollment['username']); ?></strong>
                                            enrolled in
                                            "<?php echo htmlspecialchars($enrollment['course_title']); ?>"
                                        </p>
                                        <small class="text-muted">
                                            <?php echo time_ago($enrollment['enrolled_at']); ?>
                                        </small>
                                    </div>
                                <?php endforeach; ?>

                            </div>

                            <!-- New Courses Added Section -->
                            <div class="d-flex flex-wrap gap-4">
                                <!-- Heading for New Courses -->
                                <div class="text-left w-100">
                                    <h6 class="mb-3">New Courses Added:</h6>
                                </div>

                                <!-- Loop through courses -->
                                <?php foreach ($recent_courses as $course): ?>
                                    <div class="d-flex align-items-center gap-3 activity-item">
                                        <p class="mb-0 text-dark fw-bold"><?php echo htmlspecialchars($course['title']); ?></p>
                                        <small class="text-muted"><?php echo time_ago($course['created_at']); ?></small>
                                        <span class="badge bg-primary rounded-pill">New</span>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- New Students Registered Section -->

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
                    <div class="container mt-5">
                        <h4 class="card-title mb-0" style="color: #0433c3; padding: 20px;">Transaction History</h4>
                        <div class="table_list">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light"
                                    <tr>
                                    <th>Student</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($results)): ?>
                                        <?php foreach ($results as $row): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <!-- Optionally, you can add an avatar image -->
                                                        <!-- <img src="path/to/avatar.jpg" alt="Avatar" class="rounded-circle me-2" width="40" height="40"> -->
                                                        <span><?= htmlspecialchars($row['username']) ?></span>
                                                    </div>
                                                </td>
                                                <td>₹ <?= number_format($row['amount'], 2) ?></td>
                                                <td><?= htmlspecialchars(date('d M Y', strtotime($row['created_at']))) ?></td>
                                                <td>
                                                    <span class="badge bg-<?= strtolower($row['status']) === 'unpaid' ? 'warning text-dark' : 'success' ?>">
                                                        <?= ucfirst($row['status']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if (strtolower($row['status']) === 'unpaid'): ?>
                                                        <form method="POST" action="mark_paid.php" class="d-inline">
                                                            <input type="hidden" name="payment_id" value="<?= $row['id'] ?>">
                                                            <button type="submit" class="btn btn-primary btn-sm">Mark as Paid</button>
                                                        </form>
                                                    <?php else: ?>
                                                        <button disabled class="btn btn-secondary btn-sm"> Paid</button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">No recent transactions found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>



                <div id="messagesContainer"></div>


            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    let messageCount = 0; // Initialize message count

    // Fetch new messages count
    function fetchNewMessages() {
        fetch('fetchmessages.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(messages => {
                const messagesContainer = document.getElementById('messagesContainer');
                messagesContainer.innerHTML = '';

                if (messages.length > 0) {
                    messages.forEach(message => {
                        const messageElement = document.createElement('div');
                        messageElement.classList.add('message-item');
                        messageElement.innerHTML = `
                            <p>${message.content}</p>
                            <small>${time_ago(message.created_at)}</small>
                        `;
                        messagesContainer.appendChild(messageElement);
                    });
                } else {
                    messagesContainer.innerHTML = '<p>No new messages.</p>';
                }
            })
            .catch(error => {
            //   document.getElementById('messagesContainer').innerHTML = '<p>Error loading messages.</p>';
    });
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
    document.addEventListener('DOMContentLoaded', function () {
        fetchNewMessages()
        resetMessages(); // Reset messages when the dashboard is loaded
    });

    // Reset message count and update status when the notification is clicked
    document.getElementById('notificationLink').addEventListener('click', function () {
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
     fetchNewMessages()

    // Set interval to fetch new messages every 5 seconds
    setInterval(fetchNewMessages, 5000);
</script>

</body>

</html>