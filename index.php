<?php
include 'header.php';
require_once 'config.php';
require_once 'functions.php';
date_default_timezone_set('Asia/Kolkata');
function get_greeting()
{
    $hour = date("H"); // Get current hour in 24-hour format

    if ($hour >= 7 && $hour < 13) {
        return "Good Morning";
    } elseif ($hour >= 13 && $hour < 16) {
        return "Good Afternoon";
    } else {
        return "Good Evening";
    }
}
?>

<!-- Hero Section with Parallax Effect -->
<div class="hero-section bg-cover bg-center py-20 mb-10 relative" style="background-image: url('assets/images/hero-bg.jpg');">
    <div class="hero-overlay absolute inset-0 bg-black opacity-50"></div>
    <div class="container mx-auto px-4 relative z-10 text-center">
        <h4 class="text-4xl md:text-5xl font-bold text-white mb-6 animate__animated animate__fadeInDown">Welcome to Ultrakey Learning</h4>
        <p class="text-lg md:text-xl text-white mb-8 animate__animated animate__fadeInUp">Empower your future with our cutting-edge online courses</p>
        <?php if (!is_logged_in()): ?>
            <div class="space-x-4">
                <a href="register.php" class="btn btn-primary bg-blue-600 text-white px-6 py-3 rounded-lg animate__animated animate__fadeInLeft">Get Started</a>
                <a href="login.php" class="btn btn-outline-light border border-white text-white px-6 py-3 rounded-lg animate__animated animate__fadeInRight">Login</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<div class="container mx-auto px-4 mt-10">
    <?php if (is_logged_in()): ?>
        <div class="welcome-back mb-10 animate__animated animate__fadeIn">
            <h2 class="text-2xl font-bold mb-2"><?php echo get_greeting(); ?>,<br>
                <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            <p class="text-gray-700">Ready to continue your learning journey? Check out your dashboard or explore new courses below.</p>
        </div>
    <?php endif; ?>

    <!-- Features Section -->
    <section class="features mb-10">
        <h2 class="text-3xl font-bold text-center mb-8">Why Choose Ultrakey Learning?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="card bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <div class="text-center">
                    <i class="fas fa-laptop-code text-4xl text-blue-600 mb-4"></i>
                    <h5 class="text-xl font-semibold mb-2">Learn Anywhere</h5>
                    <p class="text-gray-600">Access our courses from any device, anytime, anywhere.</p>
                </div>
            </div>
            <div class="card bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <div class="text-center">
                    <i class="fas fa-certificate text-4xl text-blue-600 mb-4"></i>
                    <h5 class="text-xl font-semibold mb-2">Certified Courses</h5>
                    <p class="text-gray-600">Earn certificates recognized by top industries.</p>
                </div>
            </div>
            <div class="card bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <div class="text-center">
                    <i class="fas fa-users text-4xl text-blue-600 mb-4"></i>
                    <h5 class="text-xl font-semibold mb-2">Expert Instructors</h5>
                    <p class="text-gray-600">Learn from industry professionals and thought leaders.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Course Categories Section -->
    <section class="course-categories mb-10 bg-blue-50 p-8 rounded-lg">
        <h2 class="text-3xl font-bold text-center mb-8">Explore Our Course Categories</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            <div class="category-card relative overflow-hidden rounded-lg">
                <img src="assets/images/programming.jpg" alt="Programming" class="w-full h-48 object-cover">
                <div class="category-overlay absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center text-white">
                    <h3 class="text-xl font-semibold">Programming</h3>
                    <a href="courses.php?category=programming" class="mt-2 px-4 py-2 bg-white text-blue-600 rounded-lg">Explore</a>
                </div>
            </div>
            <div class="category-card relative overflow-hidden rounded-lg">
                <img src="assets/images/design.jpg" alt="Design" class="w-full h-48 object-cover">
                <div class="category-overlay absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center text-white">
                    <h3 class="text-xl font-semibold">Design</h3>
                    <a href="courses.php?category=design" class="mt-2 px-4 py-2 bg-white text-blue-600 rounded-lg">Explore</a>
                </div>
            </div>
            <div class="category-card relative overflow-hidden rounded-lg">
                <img src="assets/images/business.jpg" alt="Business" class="w-full h-48 object-cover">
                <div class="category-overlay absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center text-white">
                    <h3 class="text-xl font-semibold">Business</h3>
                    <a href="courses.php?category=business" class="mt-2 px-4 py-2 bg-white text-blue-600 rounded-lg">Explore</a>
                </div>
            </div>
            <div class="category-card relative overflow-hidden rounded-lg">
                <img src="assets/images/language.jpg" alt="Language" class="w-full h-48 object-cover">
                <div class="category-overlay absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center text-white">
                    <h3 class="text-xl font-semibold">Language</h3>
                    <a href="courses.php?category=language" class="mt-2 px-4 py-2 bg-white text-blue-600 rounded-lg">Explore</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Courses Section -->
    <section class="featured-courses mx-auto max-w-7xl px-4 py-8">
        <h2 class="text-center text-3xl font-semibold text-gray-800 mb-6">Featured Courses</h2>

        <?php
        $query = "SELECT c.id, c.title, c.description, t.full_name AS tutor_name 
              FROM courses c
              LEFT JOIN tutors t ON c.tutor_id = t.id
              ORDER BY c.created_at DESC LIMIT 4";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0):
        ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php while ($course = mysqli_fetch_assoc($result)): ?>
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden transform transition duration-300 hover:scale-105">
                        <img src="assets/images/<?php echo htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8'); ?>.jpg"
                            alt="<?php echo htmlspecialchars($course['title']); ?>"
                            class="w-full h-40 object-cover">
                        <div class="p-4">
                            <h5 class="text-lg font-semibold text-gray-800 mb-2"><?php echo htmlspecialchars($course['title']); ?></h5>
                            <p class="text-gray-600 text-sm mb-3"><?php echo htmlspecialchars(substr($course['description'], 0, 60)) . '...'; ?></p>
                            <p class="text-xs text-gray-500">Tutor: <?php echo htmlspecialchars($course['tutor_name']); ?></p>
                        </div>
                        <div class="p-4 flex justify-center space-x-2">
                            <?php if (is_logged_in()): ?>
                                <a href="course.php?id=<?php echo $course['id']; ?>"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md text-xs hover:bg-blue-700">
                                    View
                                </a>
                                <a href="courses.php"
                                    class="px-3 py-2 bg-green-600 text-white rounded-md text-xs hover:bg-green-700">
                                    Enroll
                                </a>
                                <button class="px-4 py-2 bg-yellow-500 text-white rounded-md text-xs hover:bg-yellow-600 flex items-center space-x-1"
                                    onclick="addToCart(<?php echo $course['id']; ?>)">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span>Add</span>
                                </button>
                            <?php else: ?>
                                <a href="login.php" class="px-3 py-2 bg-gray-600 text-white rounded-lg text-xs hover:bg-gray-700">Login to Enroll</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="text-center mt-6">
                <a href="courses.php" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">View All Courses</a>
            </div>

        <?php else: ?>
            <p class="text-center text-gray-600">No courses are available at the moment. Please check back later.</p>
        <?php endif; ?>
    </section>



    <!-- Stats Counter Section -->
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
    <section class="testimonials mb-10">
        <h2 class="text-3xl font-bold text-center mb-8">What Our Students Say</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="card bg-white p-6 rounded-lg shadow-md">
                <img src="assets/images/student1.jpg" alt="John Doe" class="w-20 h-20 rounded-full mx-auto mb-4">
                <p class="text-gray-600 text-center">"Ultrakey Learning has transformed my career. The courses are top-notch and the instructors are amazing!"</p>
                <p class="text-center font-semibold mt-4">- Sandhya, Web Developer</p>
            </div>
            <div class="card bg-white p-6 rounded-lg shadow-md">
                <img src="assets/images/student2.jpg" alt="Jane Smith" class="w-20 h-20 rounded-full mx-auto mb-4">
                <p class="text-gray-600 text-center">"I've learned more in 3 months with Ultrakey Learning than I did in a year at university. Highly recommended!"</p>
                <p class="text-center font-semibold mt-4">- Neha, Android Developer</p>
            </div>
            <div class="card bg-white p-6 rounded-lg shadow-md">
                <img src="assets/images/student3.jpg" alt="Jane Smith" class="w-20 h-20 rounded-full mx-auto mb-4">
                <p class="text-gray-600 text-center">"The flexibility of online learning combined with the quality of content makes Ultrakey Learning unbeatable."</p>
                <p class="text-center font-semibold mt-4">- Arun, Busyness Analyst</p>
            </div>
        </div>
    </section>


    <section class="cta text-center py-5 mb-5">
        <h2 class="mb-3">Ready to Start Your Learning Journey?</h2>
        <p class="lead mb-4">Join thousands of students already learning on Ultrakey Learning</p>
        <a href="register.php" class="btn btn-primary btn-lg animate__animated animate__pulse animate__infinite">Get Started Now</a>
    </section>

    <section class="bg-gray-100 py-12">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-10">
                Experience the Power of Ultrakey Learning
            </h2>

            <!-- Interactive Lessons -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center mb-10">
                <div class="w-full">
                    <img src="assets/images/interactive-lessons.jpg" alt="Interactive Lessons" style="height: 389px;"
                        class="w-full h-[200px] object-cover rounded-lg shadow-md">
                </div>

                <div>
                    <h3 class="text-2xl font-semibold text-gray-800">Interactive Lessons</h3>
                    <p class="text-gray-600 mt-3">
                        Engage with our cutting-edge interactive lessons that make learning fun and effective.
                        Our platform offers a variety of multimedia content, quizzes, and hands-on exercises
                        to keep you motivated and ensure better retention of knowledge.
                    </p>
                    <a href="#" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg shadow-md 
                    hover:bg-blue-700 transition duration-300">
                        Learn More
                    </a>
                </div>

            </div>

            <!-- Progress Tracking (Text Left, Image Right) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center mb-10">
                <div>
                    <h3 class="text-2xl font-semibold text-gray-800">Comprehensive Progress Tracking</h3>
                    <p class="text-gray-600 mt-3">
                        Stay on top of your learning journey with our detailed progress tracking system.
                        Monitor your course completion, quiz scores, and skill development in real-time.
                        Set personal goals and watch as you achieve them step by step.
                    </p>
                    <a href="#" class="mt-4 inline-block bg-green-600 text-white px-6 py-2 rounded-lg shadow-md 
        hover:bg-green-700 transition duration-300">
                        Explore Features
                    </a>
                </div>
                <div>
                    <img src="assets/images/progress-tracking.jpg" alt="Progress Tracking"
                        class="w-full h-[350px] object-cover rounded-lg shadow-md md:order-first">
                </div>
            </div>


            <!-- Community Forums -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
                <div>
                    <img src="assets/images/community-forums.jpg" alt="Community Forums"
                        class="w-full h-[350px] object-cover rounded-lg shadow-md">
                </div>
                <div>
                    <h3 class="text-2xl font-semibold text-gray-800">Vibrant Learning Community</h3>
                    <p class="text-gray-600 mt-3">
                        Join our thriving community of learners and educators. Participate in discussions,
                        share knowledge, and get support from peers and instructors.
                    </p>
                    <a href="#" class="mt-4 inline-block bg-purple-600 text-white px-6 py-2 rounded-lg shadow-md 
                    hover:bg-purple-700 transition duration-300">
                        Join the Community
                    </a>
                </div>
            </div>
        </div>
    </section>



</div>
<style>
    @media (min-width: 1536px) {
        .container {
            max-width: 1300px !important;
        }
    }
</style>

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


    document.querySelectorAll(".explore-btn").forEach(button => {
        button.addEventListener("click", function(event) {
            event.preventDefault(); // Prevent default navigation for testing
            let url = this.getAttribute("href");
            console.log("Navigating to:", url);
            window.location.href = url; // Uncomment this when debugging is done
        });
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