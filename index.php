<?php
include 'header.php';
require_once 'config.php';
require_once 'functions.php';


// // Fetch counts from the database
// $students_count_result = mysqli_query($conn, "SELECT COUNT(DISTINCT user_id) as count FROM enrollments WHERE user_id IS NOT NULL");
// if (!$students_count_result) {
//     die('Query Error: ' . mysqli_error($conn)); // Error handling
// }
// $students_count = mysqli_fetch_assoc($students_count_result)['count'];

// $courses_count_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM courses");
// if (!$courses_count_result) {
//     die('Query Error: ' . mysqli_error($conn)); // Error handling
// }
// $courses_count = mysqli_fetch_assoc($courses_count_result)['count'];

// $tutors_count_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM tutors");
// if (!$tutors_count_result) {
//     die('Query Error: ' . mysqli_error($conn)); // Error handling
// }
// $tutors_count = mysqli_fetch_assoc($tutors_count_result)['count'];
// 
?>

<!-- Hero Section with Parallax Effect -->
<!-- <div class="hero-section text-center py-5 mb-5 parallax-window" data-parallax="scroll" data-image-src="assets/images/hero-bg.jpg" style="background-size: cover; background-position: center;">
    <div class="container">
        <h1 class="display-4 mb-4 animate__animated animate__fadeInDown">Welcome to Ultrakey Learning</h1>
        <p class="lead mb-4 animate__animated animate__fadeInUp">Empower your future with our cutting-edge online courses</p>
        <?php if (!is_logged_in()): ?>
            <a href="register.php" class="btn btn-primary btn-lg me-2 animate__animated animate__fadeInLeft">Get Started</a>
            <a href="login.php" class="btn btn-outline-light btn-lg animate__animated animate__fadeInRight">Login</a>
        <?php endif; ?>
    </div>
</div> -->
<!-- Hero Section with Parallax Effect -->
<div
    class="hero-section text-center py-5 mb-5 parallax-window"
    data-parallax="scroll"
    style="background-size: cover; background-position: center; margin-bottom: 10%; position: relative; top: -20px; overflow: hidden;">
    <!-- Overlay for opacity -->
    <div class="hero-overlay"></div>

    <div class="container position-relative">
        <h4 class="display-4 mb-4 animate__animated animate__fadeInDown" style="margin-top: 97px;">Welcome to Ultrakey Learning</h4>
        <p class="lead mb-4 animate__animated animate__fadeInUp">Empower your future with our cutting-edge online courses</p>
        <?php if (!is_logged_in()): ?>
            <a href="register.php" class="btn btn-primary btn-lg me-2 animate__animated animate__fadeInLeft">Get Started</a>
            <a href="login.php" class="btn btn-outline-light btn-lg animate__animated animate__fadeInRight">Login</a>
        <?php endif; ?>
    </div>
</div>

<div class="container mt-5">
    <?php if (is_logged_in()): ?>
        <div class="welcome-back mb-5 animate__animated animate__fadeIn">
            <h2>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            <p>Ready to continue your learning journey? Check out your dashboard or explore new courses below.</p>
            <!-- <a href="index.php" class="btn btn-primary">Go to Dashboard</a> -->
        </div>
    <?php endif; ?>

    <!-- Features Section with Hover Effects -->
    <section class="features mb-5">
        <h2 class="text-center mb-4">Why Choose Ultrakey Learning?</h2>
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card h-100 animate__animated animate__fadeInUp hover-card">
                    <div class="card-body text-center">
                        <i class="fas fa-laptop-code fa-3x mb-3 text-primary"></i>
                        <h5 class="card-title">Learn Anywhere</h5>
                        <p class="card-text">Access our courses from any device, anytime, anywhere.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                    <div class="card-body text-center">
                        <i class="fas fa-certificate fa-3x mb-3 text-primary"></i>
                        <h5 class="card-title">Certified Courses</h5>
                        <p class="card-text">Earn certificates recognized by top industries.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100 animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-3x mb-3 text-primary"></i>
                        <h5 class="card-title">Expert Instructors</h5>
                        <p class="card-text">Learn from industry professionals and thought leaders.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="explore">
        <section class="course-categories mb-5">
            <h2 class="text-center mb-4">Explore Our Course Categories</h2>
            <div class="row">
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="category-card">
                        <img src="assets/images/programming.jpg" alt="Programming" class="img-fluid">
                        <div class="category-overlay">
                            <h3>Programming</h3>
                            <a href="courses.php?category=programming" class="btn btn-light">Explore</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="category-card">
                        <img src="assets/images/design.jpg" alt="Design" class="img-fluid">
                        <div class="category-overlay">
                            <h3>Design</h3>
                            <a href="courses.php?category=design" class="btn btn-light">Explore</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="category-card">
                        <img src="assets/images/business.jpg" alt="Business" class="img-fluid">
                        <div class="category-overlay">
                            <h3>Business</h3>
                            <a href="courses.php?category=business" class="btn btn-light">Explore</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="category-card">
                        <img src="assets/images/language.jpg" alt="Language" class="img-fluid">
                        <div class="category-overlay">
                            <h3>Language</h3>
                            <a href="courses.php?category=language" class="btn btn-light">Explore</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <section class="featured-courses mb-5" style="margin: 43px;">
        <h2 class="text-center mb-4">Featured Courses</h2>
        <?php
        // Fetch featured courses (limit to 3 for this example)
        $query = "SELECT c.id, c.title, c.description, t.full_name AS tutor_name 
                  FROM courses c
                  LEFT JOIN tutors t ON c.tutor_id = t.id
                  ORDER BY c.created_at DESC LIMIT 3";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0):
        ?>
            <div class="row">
                <?php while ($course = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 animate__animated animate__fadeIn" style="height: 150px; overflow: hidden;">
                            <img
                                src="assets/images/<?php echo htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8'); ?>.jpg" style="height: 180px; width: 100%; object-fit: cover;">
                            <div class="card-body" style="padding: 28px;">
                                <h5 class="card-title" style="font-size: 20px;"><?php echo htmlspecialchars($course['title']); ?></h5>
                                <p class="card-text" style="font-size: 12px;"><?php echo htmlspecialchars(substr($course['description'], 0, 60)) . '...'; ?></p>
                                <p class="card-text" style="font-size: 10px;"><small class="text-muted">Tutor: <?php echo htmlspecialchars($course['tutor_name']); ?></small></p>
                            </div>
                            <div class="card-footer bg-transparent border-0" style="padding: 35px; display: flex; justify-content: center; align-items: center; margin-top: -50px;">
                                <?php if (is_logged_in()): ?>
                                    <a href="course.php?id=<?php echo $course['id']; ?>" class="btn btn-primary btn-sm" style="margin-right: 10px;">View Course</a>
                                    <a href="courses.php" class="btn btn-success btn-sm" style="margin-right: 10px;">Enroll</a>
                                    <!-- Add to Cart Icon -->
                                    <button class="btn btn-warning btn-sm" onclick="addToCart(<?php echo $course['id']; ?>)">
                                        <i class="fa fa-shopping-cart"></i> Add to Cart
                                    </button>
                                <?php else: ?>
                                    <a href="login.php" class="btn btn-secondary btn-sm">Login to Enroll</a>
                                <?php endif; ?>
                            </div>


                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="text-center">
                <a href="courses.php" class="btn btn-primary">View All Courses</a>
            </div>
        <?php else: ?>
            <p class="text-center">No courses are available at the moment. Please check back later.</p>
        <?php endif; ?>
    </section>

    <section class="stats-counter mb-5 py-5 bg-light">
        <div class="container">
            <div class="row text-center">
                <?php
                // Fetch counts from the database
                $students_count_result = mysqli_query($conn, "SELECT COUNT(DISTINCT user_id) as count FROM enrollments WHERE user_id IS NOT NULL");
                if (!$students_count_result) {
                    die('Query Error: ' . mysqli_error($conn)); // Error handling
                }
                $students_count = mysqli_fetch_assoc($students_count_result)['count'];
                // echo "Students Count: " . $students_count; // Debugging output

                $courses_count_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM courses");
                if (!$courses_count_result) {
                    die('Query Error: ' . mysqli_error($conn)); // Error handling
                }
                $courses_count = mysqli_fetch_assoc($courses_count_result)['count'];

                $tutors_count_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM tutors");
                if (!$tutors_count_result) {
                    die('Query Error: ' . mysqli_error($conn)); // Error handling
                }
                $tutors_count = mysqli_fetch_assoc($tutors_count_result)['count'];
                ?>

                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="counter">
                        <i class="fas fa-users fa-3x mb-3 text-primary"></i>
                        <h2 class="count" data-count="<?php echo $students_count; ?>">0</h2>
                        <p>Students Enrolled</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="counter">
                        <i class="fas fa-book fa-3x mb-3 text-primary"></i>
                        <h2 class="count" data-count="<?php echo $courses_count; ?>">0</h2>
                        <p>Courses Available</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="counter">
                        <i class="fas fa-chalkboard-teacher fa-3x mb-3 text-primary"></i>
                        <h2 class="count" data-count="<?php echo $tutors_count; ?>">0</h2>
                        <p>Expert Instructors</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="counter">
                        <i class="fas fa-globe fa-3x mb-3 text-primary"></i>
                        <h2 id="countries-count" class="count" data-count="50">0</h2>
                        <p>Countries Reached</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="testimonials mb-5">
        <h2 class="text-center mb-4">What Our Students Say</h2>
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card h-100 animate__animated animate__fadeIn">
                    <div class="card-body">
                        <img src="assets/images/student1.jpg" alt="John Doe" class="rounded-circle mb-3" width="80">
                        <p class="card-text">"Ultrakey Learning has transformed my career. The courses are top-notch and the instructors are amazing!"</p>
                        <p class="card-text"><strong style="font-weight: bold;">- Sandhya, Web Developer</strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100 animate__animated animate__fadeIn" style="animation-delay: 0.2s;">
                    <div class="card-body">
                        <img src="assets/images/student2.jpg" alt="Jane Smith" class="rounded-circle mb-3" width="80">
                        <p class="card-text">"I've learned more in 3 months with Ultrakey Learning than I did in a year at university. Highly recommended!"</p>
                        <p class="card-text"><strong style="font-weight: bold;">- Neha, Android Developer</strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100 animate__animated animate__fadeIn" style="animation-delay: 0.4s;">
                    <div class="card-body">
                        <img src="assets/images/student3.jpg" alt="Mike Johnson" class="rounded-circle mb-3" width="80">
                        <p class="card-text">"The flexibility of online learning combined with the quality of content makes Ultrakey Learning unbeatable."</p>
                        <p class="card-text"><strong style="font-weight: bold;">- Arun, Business Analyst</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="cta text-center py-5 mb-5">
        <h2 class="mb-3">Ready to Start Your Learning Journey?</h2>
        <p class="lead mb-4">Join thousands of students already learning on Ultrakey Learning</p>
        <a href="register.php" class="btn btn-primary btn-lg animate__animated animate__pulse animate__infinite">Get Started Now</a>
    </section>

    <section class="lms-showcase mb-5">
        <div class="container">
            <h2 class="text-center mb-5">Experience the Power of Ultrakey Learning</h2>
            <div class="row align-items-center mb-5">
                <div class="col-md-6">
                    <img src="assets/images/interactive-lessons.jpg" alt="Interactive Lessons" class="img-fluid rounded shadow-lg responsive-img" style="max-width: 100%; height: 400px; margin-left: 15%">
                </div>
                <div class="col-md-6">
                    <h3>Interactive Lessons</h3>
                    <p>Engage with our cutting-edge interactive lessons that make learning fun and effective. Our platform offers a variety of multimedia content, quizzes, and hands-on exercises to keep you motivated and ensure better retention of knowledge.</p>
                    <a href="#" class="btn btn-outline-primary">Learn More</a>
                </div>
            </div>
            <div class="row align-items-center mb-5">
                <div class="col-md-6 order-md-2">
                    <img src="assets/images/progress-tracking.jpg" alt="Progress Tracking" class="img-fluid rounded shadow-lg">
                </div>
                <div class="col-md-6 order-md-1">
                    <h3>Comprehensive Progress Tracking</h3>
                    <p style="text-align: justify ; margin-right:15%">Stay on top of your learning journey with our detailed progress tracking system. Monitor your course completion, quiz scores, and skill development in real-time. Set personal goals and watch as you achieve them step by step.</p>
                    <a href="#" class="btn btn-outline-primary">Explore Features</a>
                </div>
            </div>
            <div class="row align-items-center mb-5">
                <div class="col-md-6">
                    <img src="assets/images/community-forums.jpg" alt="Community Forums" class="img-fluid rounded shadow-lg">
                </div>
                <div class="col-md-6">
                    <h3 style="margin-left:30px ;" class="a">Vibrant Learning Community</h3>
                    <p style="margin-left: 28px">Join our thriving community of learners and educators. Participate in discussions, share knowledge, and get support from peers and instructors.</p>
                    <a href="#" class="btn btn-outline-primary" style="margin-left: 34px;">Join the Community</a>
                </div>
            </div>
        </div>
    </section>

</div>

<script>

function addToCart(courseId) {
    window.location.href = `courses.php?addToCart=${courseId}`;

    }
    function animateCounter(element, start, end, duration) {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            element.innerText = Math.floor(progress * (end - start) + start);
            if (progress < 1) {
                requestAnimationFrame(step);
            }
        };
        requestAnimationFrame(step);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const studentsCountElement = document.querySelector('.count[data-count="<?php echo $students_count; ?>"]');
        animateCounter(studentsCountElement, 0, <?php echo $students_count; ?>, 2000); // Animate from 0 to the actual count
        const countriesCountElement = document.getElementById('countries-count');
        animateCounter(countriesCountElement, 0, 12, 2000); // Animate from 0 to 12 over 2 seconds
        const coursesCountElement = document.querySelector('.count[data-count="<?php echo $courses_count; ?>"]');
        animateCounter(coursesCountElement, 0, <?php echo $courses_count; ?>, 2000); // Animate from 0 to the actual count
        const tutorsCountElement = document.querySelector('.count[data-count="<?php echo $tutors_count; ?>"]');
        animateCounter(tutorsCountElement, 0, <?php echo $tutors_count; ?>, 2000); // Animate from 0 to the actual count
    });
</script>
<style>
    #explore {
        background-color: rgb(180, 218, 228);
        /* Light Cyan */
        padding: 20px;
        border-radius: 10px;
        width: 100%;
    }

    .hero-section {
        background-image: url('assets/images/hero-bg.jpg');
        background-size: cover;
        background-position: center;
        position: relative;
    }

    .testimonials {
        box-shadow: 0px 3px 2px grey;
    }

    .stats-counter {
        background: linear-gradient(135deg, rgb(166, 208, 250), rgb(124, 244, 224));
        /* Blue and Green Gradient */
        color: black;
        /* Ensures text remains readable */
        padding: 50px 0;
        border-radius: 10px;
        /* Optional: Adds rounded corners for a modern touch */
    }


    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(100, 113, 122, 0.5);
        /* Adjust opacity here */
        z-index: 1;
    }

    .container {
        position: relative;
        z-index: 2;

        /* Ensure content appears above the overlay */
    }

    .img-fluid {
        max-width: 100%;
        height: auto;
        /* Maintain aspect ratio */
        display: block;
        /* Removes extra space below the image */
        margin: 0 auto;
        /* Center the image */
    }

    /* General styles */
    .a {
        margin-right: 23%;
        /* Apply the margin-right for larger screens */
    }

    /* Adjustments for Mobile View */
    @media (max-width: 768px) {
        .a {
            margin-right: 5%;
            /* Reduce the margin-right on smaller screens */
        }
    }
</style>
<?php include 'footer.php'; ?>