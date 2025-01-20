<!-- Sidebar -->
<nav class="col-md-3 col-lg-2 d-md-block bg-gradient sidebar">
    <div class="position-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'index') ? 'active' : ''; ?>" href="index.php">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'courses') ? 'active' : ''; ?>" href="courses.php">
                    <i class="fas fa-graduation-cap"></i> Courses
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'courses_list') ? 'active' : ''; ?>" href="courses_list.php">
                    <i class="fas fa-list"></i> Courses List
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'classes') ? 'active' : ''; ?>" href="classes.php">
                    <i class="fas fa-chalkboard-teacher"></i> Classes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'students') ? 'active' : ''; ?>" href="students.php">
                    <i class="fas fa-user-graduate"></i> Students
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'users') ? 'active' : ''; ?>" href="users.php">
                    <i class="fas fa-users"></i> Tutors
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'enrollment') ? 'active' : ''; ?>" href="enrollment.php">
                    <i class="fas fa-user-plus"></i> Enrollment
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'settings') ? 'active' : ''; ?>" href="settings.php">
                    <i class="fas fa-cog"></i> Settings
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'videos') ? 'active' : ''; ?>" href="videos.php">
                    <i class="fas fa-video"></i> Videos
                </a>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'certificate') ? 'active' : ''; ?>" href="certificate.php">
                    <i class="fas fa-certificate"></i> Certificate
                </a>
            </li> -->
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'quiz_result') ? 'active' : ''; ?>" href="quiz_result.php">
                    <i class="fas fa-poll"></i> Quiz Result
                </a>
            </li>
        </ul>
    </div>
</nav>
<style>
    /* Sidebar Styling */
    .sidebar {
        background: linear-gradient(135deg, rgb(186, 134, 241), rgb(98, 149, 236));
        /* min-height: 100vh; */
        color: #fff;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        padding: 10px 0;
    }

    /* Navigation Link Styling */
    .nav-link {
        font-size: 1.1rem;
        padding: 15px 20px;
        color: rgb(18, 84, 150);
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    /* Hover Effect for Links */
    .nav-link:hover {
        background:#16308b;
        color: white;
        border-radius: 5px;
    }

    /* Icon Size */
    .nav-link i {
        font-size: 1.3rem;
    }

    /* Active Link Styling */
    .nav-link.active {
        background:#16308b;
        color: white;
        font-weight:bolder;
        border-left: 4px solid #f8c102;
        border-radius: 5px;
    }

    /* Navigation Item Spacing */
    .nav-item {
        margin-bottom: 10px;
    }

 /* Responsive Sidebar */
    /* @media (max-width: 768px) {
        .sidebar {
            min-height: auto;
            padding: 20px;
            transform: translateX(-100%);
        }

        .sidebar-toggle {
        position: fixed;
        top: 10px;
        left: 10px;
        z-index: 1100;
        background: #16308b;
        color: #fff;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
    } */
    
    .sidebar.show {
        transform: translateX(0);
    }
        .nav-link {
            font-size: 1rem;
        }
    
</style>
<script>
    // JavaScript to toggle the sidebar on smaller screens
document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.querySelector('.sidebar');
    const toggleButton = document.createElement('button');
    // toggleButton.className = 'sidebar-toggle';
    // toggleButton.innerText = '☰';
    // document.body.appendChild(toggleButton);

    toggleButton.addEventListener('click', function () {
        sidebar.classList.toggle('show');
    });
});

</script>
