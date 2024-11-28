<nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
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
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'certificate') ? 'active' : ''; ?>" href="certificate.php">
                    <i class="fas fa-certificate"></i> Certificate
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'quiz_result') ? 'active' : ''; ?>" href="quiz_result.php">
                    <i class="fas fa-poll"></i> Quiz Result
                </a>
            </li>
        </ul>
    </div>
</nav>
