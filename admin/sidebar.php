<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ultrakey LMS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: var(--bs-body-font-family);
            font-size: var(--bs-body-font-size);
        }
/* Updated Header Styles */
.header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 70px;
    background: #1a69a5;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 20px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    z-index: 9999; /* Increased z-index to stay on top */
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    font-family: var(--bs-body-font-family);
    font-size: var(--bs-body-font-size);
}

/* Ensure other elements are below the header */
body {
    padding-top: 0px; /* Add padding to prevent content overlap */
}

.some-buttons {
    z-index: 100; /* Lower than header */
    position: relative; /* Prevents floating over header */
}


        .logo {
            width: 120px;
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 40px;
        }

        .search-container {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }

        .search-box {
            width: 100%;
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 20px;
            outline: none;
            font-family: var(--bs-body-font-family);
            font-size: var(--bs-body-font-size);
        }

        .search-btn {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: #2674b7;
            color: white;
            border: none;
            padding: 6px 15px;
            border-radius: 15px;
            cursor: pointer;
        }

        .user-profile {
            margin-left: auto;
            position: relative;
        }

        .profile-circle {
            width: 35px;
            height: 35px;
            background: #FFD700;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: black;
            cursor: pointer;
        }

        .dropdown {
            position: absolute;
            top: 45px;
            right: 0;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 5px;
            min-width: 200px;
            display: none;
        }

        .dropdown.active {
            display: block;
        }

        .dropdown a {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #333;
        }

        .dropdown a i {
            margin-right: 10px;
            width: 20px;
        }

        .dropdown a:hover {
            background: #f5f5f5;
        }

        .logout {
            color: #dc3545 !important;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .search-container {
                max-width: 250px;
            }
        }

        @media (max-width: 576px) {
            .search-container {
                display: none;
            }
        }

        /* Add these new styles in the <style> section */
        .sidebar {
            position: fixed;
            left: 0;
            top: 70px;
            bottom: 0;
            width: 250px;
            background: linear-gradient(135deg, rgb(26 111 168), rgb(29 48 139)) !important;;
            color: white;
            overflow-y: auto;
            z-index: 999;
            /* Custom scrollbar styles */
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
        }

        /* Webkit scrollbar styles (Chrome, Safari, newer Edge) */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background-color: rgba(255, 255, 255, 0.5);
        }

        /* Add shadow to indicate scrollable content */
        .sidebar::after {
            content: '';
            position: fixed;
            left: 0;
            bottom: 0;
            width: 250px;
            height: 30px;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.1));
            pointer-events: none;
        }

        /* .sidebar-menu {
            padding: 20px 0 40px 0; /* Added bottom padding for shadow visibility */
    

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: white;
            text-decoration: none;
            transition: background 0.3s;
            /* font-weight: bold; */
            font-size: 18px;
            /* margin: 15px 0; */
            font-family: var(--bs-body-font-family);
        }

        .sidebar-menu a:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
            font-size: 20px;
        }

        /* Update icon styles with individual gradient backgrounds */
        .sidebar-menu i {
            margin-right: 15px;
            width: 35px;  /* Increased width for better icon container */
            height: 35px;  /* Added height to make it square */
            text-align: center;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;  /* Rounded corners */
            background: rgba(255, 255, 255, 0.1);  /* Default light background */
        }

        /* Individual icon gradient backgrounds */
        .sidebar-menu .fa-tachometer-alt {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: white;
        }

        .sidebar-menu .fa-graduation-cap {
            background: linear-gradient(135deg, #98FB98, #3CB371);
            color: white;
        }

        .sidebar-menu .fa-list {
            background: linear-gradient(135deg, #87CEEB, #4682B4);
            color: white;
        }

        .sidebar-menu .fa-chalkboard {
            background: linear-gradient(135deg, #FFA07A, #FF6347);
            color: white;
        }

        .sidebar-menu .fa-user-graduate {
            background: linear-gradient(135deg, #DDA0DD, #BA55D3);
            color: white;
        }

        .sidebar-menu .fa-chalkboard-teacher {
            background: linear-gradient(135deg, #F0E68C, #DAA520);
            color: white;
        }

        .sidebar-menu .fa-user-plus {
            background: linear-gradient(135deg, #98FF98, #32CD32);
            color: white;
        }

        .sidebar-menu .fa-cog {
            background: linear-gradient(135deg, #B0C4DE, #4169E1);
            color: white;
        }

        .sidebar-menu .fa-video {
            background: linear-gradient(135deg, #FFB6C1, #FF69B4);
            color: white;
        }

        .sidebar-menu .fa-clipboard-list {
            background: linear-gradient(135deg, #E6E6FA, #9370DB);
            color: white;
        }

        /* Hover effect for icons */
        .sidebar-menu a:hover i {
            filter: brightness(1.1);
            transform: scale(1.05);
            transition: all 0.3s ease;
        }

        /* Add this to push main content away from sidebar */
        .main-content {
            margin-left: 250px;
            /* margin-top: 70px; */
            padding: 0px;
            /* min-height: calc(0vh - 70px); */
        }

        /* Add these styles in the <style> section */
        .toggle-btn {
            display: none;
            background: none;
            border: none;
            color:rgb(253, 253, 253);
            font-size: 24px;
            cursor: pointer;
            padding: 8px 12px;
            margin-right: 15px;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        .toggle-btn:hover {
            background-color: #f0f0f0;
        }

        .toggle-btn:active {
            background-color: #e0e0e0;
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            .toggle-btn {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .header {
                padding: 0 15px;
            }

            /* Adjust logo for mobile */
            .logo {
                margin-left: 5px;
            }

            .logo img {
                height: 35px; /* Slightly smaller logo on mobile */
            }

            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            /* Optional: Add overlay when sidebar is open */
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 70px;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 998;
            }

            .sidebar-overlay.active {
                display: block;
            }
        }
    </style>
</head>
<body>
<header class="header">
    <button class="toggle-btn">
        <i class="fas fa-bars"></i>
    </button>
    <div class="logo">
        <img src="../assets/images/logo.png" alt="Logo">
    </div>
    <div class="search-container">
        <input type="text" class="search-box" placeholder="Search...">
        <button class="search-btn">
            <i class="fas fa-search"></i>
        </button>
    </div>
    <div class="user-profile">
        <div class="profile-circle">AD</div>
        <div class="dropdown">
            <a href="profile.php"><i class="fas fa-user"></i>Profile</a>
            <a href="settings.php"><i class="fas fa-cog"></i>Settings</a>
            <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </div>
    </div>
</header>

    <div class="sidebar-overlay"></div>

    <div class="sidebar">
        <div class="sidebar-menu">
            <a href="index.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
            <a href="courses.php"><i class="fas fa-graduation-cap"></i>Courses</a>
            <a href="courses_list.php"><i class="fas fa-list"></i>Courses List</a>
            <a href="classes.php"><i class="fas fa-chalkboard"></i>Classes</a>

            <a href="students.php"><i class="fas fa-user-graduate"></i>Students</a>
            <a href="users.php"><i class="fas fa-chalkboard-teacher"></i>Tutors</a>
            <a href="enrollment.php"><i class="fas fa-user-plus"></i>Enrollment</a>
            <a href="settings.php"><i class="fas fa-cog"></i>Settings</a>
            <a href="videos.php"><i class="fas fa-video"></i>Videos</a>
            <a href="quiz_result.php"><i class="fas fa-clipboard-list"></i>Quiz Result</a>
           
        </div>
    </div>

    <div class="main-content">
        <!-- Your page content goes here -->
    </div>

    <script>
        // Toggle dropdown menu
        const profileCircle = document.querySelector('.profile-circle');
        const dropdown = document.querySelector('.dropdown');

        profileCircle.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('active');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', () => {
            dropdown.classList.remove('active');
        });

        dropdown.addEventListener('click', (e) => {
            e.stopPropagation();
        });

        // Toggle sidebar
        const toggleBtn = document.querySelector('.toggle-btn');
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.querySelector('.sidebar-overlay');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        });

        // Close sidebar when clicking overlay
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
    </script>
</body>
</html>
