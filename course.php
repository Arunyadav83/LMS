<?php
require_once 'config.php';
require_once 'functions.php';
include 'header.php';
// Check if course ID is provided
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "No course selected.";
    header("Location: index.php");
    exit();
}
$razorpayKey = 'rzp_test_Bvq9kiuaq8gkcs';
$course_id = (int)$_GET['id']; // Get course ID from URL
// Fetch course details
$course_query = "SELECT c.id, c.title, c.description, t.full_name AS tutor_name, t.email AS tutor_email, c.tutor_id 
                 FROM courses c
                 LEFT JOIN tutors t ON c.tutor_id = t.id
                 WHERE c.id = ?";
$course_stmt = mysqli_prepare($conn, $course_query);
if (!$course_stmt) {
    die("Database query preparation failed: " . mysqli_error($conn));
}
// Check if binding parameters is successful
if (!mysqli_stmt_bind_param($course_stmt, "i", $course_id)) {
    die("Parameter binding failed: " . mysqli_error($conn));
}
mysqli_stmt_execute($course_stmt);
$course_result = mysqli_stmt_get_result($course_stmt);
$course = mysqli_fetch_assoc($course_result);
if (!$course) {
    $_SESSION['error'] = "Invalid course selected.";
    header("Location: index.php");
    exit();
}
$tutor_id = $course['tutor_id']; // Use tutor_id from the course
// Fetch the average rating for the tutor
$rating_query = "SELECT AVG(rating) AS avg_rating, COUNT(id) AS num_reviews FROM tutor_reviews WHERE tutor_id = ?";
$rating_stmt = mysqli_prepare($conn, $rating_query);
mysqli_stmt_bind_param($rating_stmt, "i", $tutor_id);
mysqli_stmt_execute($rating_stmt);
$rating_result = mysqli_stmt_get_result($rating_stmt);
$rating_data = mysqli_fetch_assoc($rating_result);
$avg_rating = isset($rating_data['avg_rating']) ? round($rating_data['avg_rating'], 1) : 0;
$num_reviews = isset($rating_data['num_reviews']) ? $rating_data['num_reviews'] : 0;
// Check if the user is already enrolled in the course
$enrollment_query = "SELECT COUNT(*) AS count FROM enrollments WHERE user_id = ? AND course_id = ?";
$enrollment_stmt = mysqli_prepare($conn, $enrollment_query);
if ($enrollment_stmt) {
    mysqli_stmt_bind_param($enrollment_stmt, "ii", $_SESSION['user_id'], $course['id']);
    mysqli_stmt_execute($enrollment_stmt);
    $enrollment_result = mysqli_stmt_get_result($enrollment_stmt);
    $enrollment_data = mysqli_fetch_assoc($enrollment_result);
    if ($enrollment_data) {
        $is_enrolled = ($enrollment_data['count'] > 0);
    } else {
        $is_enrolled = false;
    }
} else {
    // Handle errors in query preparation
    echo "Failed to prepare the enrollment query.";
    $is_enrolled = false; // Default to not enrolled if query fails
}
// Fetch classes (videos) for this course
$classes_query = "SELECT id, class_name, description, video_path, is_online, online_link, schedule_time, course_id, is_unlocked
                  FROM classes
                  WHERE course_id = ?
                  ORDER BY created_at ASC";
$classes_stmt = mysqli_prepare($conn, $classes_query);
if (!$classes_stmt) {
    die("Database query preparation failed: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($classes_stmt, "i", $course_id);
mysqli_stmt_execute($classes_stmt);
$classes_result = mysqli_stmt_get_result($classes_stmt);
// Function to check if the previous quiz was completed and if enough time has passed
function isPreviousQuizCompletedAndTimeElapsed($conn, $class_id, $user_id)
{
    $query = "SELECT completed_at FROM quiz_completions 
              WHERE class_id = ? AND user_id = ? 
              ORDER BY completed_at DESC LIMIT 1";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $class_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $completion = mysqli_fetch_assoc($result);
    if ($completion) {
        $completed_time = strtotime($completion['completed_at']);
        $current_time = time();
        return ($current_time - $completed_time) >= 60; // 60 seconds = 1 minute
    }
    return false;
}
// Debugging: Check if course ID exists
if (!isset($course['tutor_id']) || empty($course['tutor_id'])) {
    die("Error: Tutor ID is missing.");
}
$tutor_id = (int) $course['tutor_id']; // Ensure it's an integer
// Use prepared statements to prevent SQL injection
$tutor_query = "SELECT full_name FROM tutors WHERE id = ?";
$stmt = mysqli_prepare($conn, $tutor_query);
if (!$stmt) {
    die("SQL Prepare Failed: " . mysqli_error($conn));
}
// Bind parameters and execute
mysqli_stmt_bind_param($stmt, "i", $tutor_id);
mysqli_stmt_execute($stmt);
$tutor_result = mysqli_stmt_get_result($stmt);
// Check if query execution was successful
if (!$tutor_result) {
    die("Query Failed: " . mysqli_error($conn));
}
// Fetch tutor details safely
$tutor = mysqli_fetch_assoc($tutor_result);
if (!$tutor) {
    die("Error: No tutor found with ID $tutor_id");
}
$tutor_name = $tutor['full_name'];
$image_path = "assets/images/" . strtolower(str_replace(' ', '_', $tutor_name)) . ".jpg";
// Check if image file exists, else use a default image
if (!file_exists($image_path)) {
    $image_path = "assets/images/default.jpg"; // Fallback image
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['title']); ?> - Course Details</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <style>
        /* Core styles */
        :root {
            --primary-color: #4F46E5;
            --secondary-color: #2563EB;
            --bg-gradient: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }

        body {
            background-color: #F3F4F6;
            font-family: system-ui, -apple-system, sans-serif;
        }

        /* Header Styles */
        .course-header {
            background: var(--bg-gradient);
            padding: clamp(2rem, 5vw, 4rem) 1rem;
            margin-bottom: 2rem;
        }

        /* Course Info Card */
        .course-info-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: clamp(1.5rem, 4vw, 2rem);
            margin: 0 auto;
            max-width: 1200px;
            width: 90%;
        }

        .course-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        @media (min-width: 768px) {
            .course-grid {
                grid-template-columns: 2fr 1fr;
            }
        }

        /* Course Title and Description */
        .course-title {
            font-size: clamp(1.5rem, 4vw, 2.5rem);
            color: #1E293B;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .course-description {
            font-size: clamp(1rem, 2vw, 1.125rem);
            color: #475569;
            margin-bottom: 1.5rem;
        }

        /* Tutor Profile */
        .tutor-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .tutor-image {
            width: clamp(3rem, 8vw, 4rem);
            height: clamp(3rem, 8vw, 4rem);
            border-radius: 50%;
            object-fit: cover;
        }

        /* Lessons Grid */
        .lessons-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: clamp(1rem, 3vw, 2rem);
            padding: clamp(1rem, 3vw, 2rem);
        }

        .lesson-card {
            background: white;
            border-radius: 0.75rem;
            overflow: hidden;
            transition: transform 0.3s ease;
            height: 100%;
            /* width: 300px; */
        }

        .lesson-card:hover {
            transform: translateY(-4px);
        }

        /* Video Container */
        .video-container {
            position: relative;
            padding-top: 56.25%;
            background: #F1F5F9;
        }

        .video-container video,
        .video-container .lock-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .lock-overlay {
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.1);
        }

        /* Buttons and Interactive Elements */
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--secondary-color);
        }

        /* Rating Section */
        .rating-section {
            background: white;
            border-radius: 1rem;
            padding: clamp(1.5rem, 4vw, 2rem);
            margin-top: 2rem;
        }

        .rating-stars {
            display: flex;
            gap: 0.5rem;
            font-size: clamp(1.5rem, 3vw, 2rem);
        }

        /* Responsive Text Utilities */
        .text-responsive-sm {
            font-size: clamp(0.875rem, 2vw, 1rem);
        }

        .text-responsive-base {
            font-size: clamp(1rem, 2.5vw, 1.125rem);
        }

        .text-responsive-lg {
            font-size: clamp(1.125rem, 3vw, 1.25rem);
        }

        .text-responsive-xl {
            font-size: clamp(1.25rem, 3.5vw, 1.5rem);
        }

        /* Mobile Optimizations */
        @media (max-width: 640px) {
            .course-info-card {
                padding: 1rem;
                width: 95%;
            }

            .tutor-profile {
                flex-direction: column;
                text-align: center;
            }

            .rating-section {
                padding: 1rem;
            }

            .btn {
                width: 100%;
                text-align: center;
            }
        }

        /* Tablet Optimizations */
        @media (min-width: 641px) and (max-width: 1024px) {
            .course-info-card {
                width: 92%;
            }

            .lessons-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .lesson-card {
                width: 100%;
            }
        }

        /* Loading Spinner */
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .container {
            overflow-x: hidden;
            width: 100%;
        }

        @media (min-width: 1536px) {
            .container {
                max-width: 1300px !important;
            }
        }

        .stars {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
        }

        .stars input {
            display: none;
        }

        .stars label {
            font-size: 2.5rem;
            color: white;
            text-shadow: 0 0 2px black;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .stars input:checked~label,
        .stars label:hover,
        .stars label:hover~label {
            color: gold;
            text-shadow: none;
        }

        @media (max-width: 640px) {
            .stars label {
                font-size: 2rem;
            }
        }

        .a {
            max-width: 1000px;
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Course Header -->
        <div class="course-header" style="margin-top: 49px;">
            <div class="course-info-card">
                <div class="course-grid">
                    <div>
                        <h1 class="course-title"><?php echo htmlspecialchars($course['title']); ?></h1>
                        <p class="course-description"><?php echo htmlspecialchars($course['description']); ?></p>
                        <div class="tutor-profile">
                            <img src="<?php echo $image_path; ?>" alt="<?php echo $tutor_name; ?>" class="tutor-image">
                            <div>
                                <h3 class="text-responsive-lg"><?php echo htmlspecialchars($tutor['full_name']); ?></h3>
                                <p class="text-responsive-sm">
                                    <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($course['tutor_email']); ?>
                                </p>

                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center">
                                <span class="text-yellow-500 text-xl">
                                    <?php echo str_repeat('★', $avg_rating); ?>
                                </span>
                                <span class="ml-2 text-indigo-700">
                                    <?php echo $avg_rating; ?> (<?php echo $num_reviews; ?> reviews)
                                </span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="bg-white shadow-lg rounded-2xl p-6">
                            <div class="text-sm text-indigo-600">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-video text-indigo-400"></i>
                                    <span><?php echo mysqli_num_rows($classes_result); ?> Lessons</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-clock text-indigo-400"></i>
                                    <span>Self-paced learning</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="max-w-6xl mx-auto px-4 mb-16">
    <h2 class="text-2xl font-bold text-gray-900 mb-8">Course Content</h2>
    <div class="lessons-grid">
        <?php
        if (mysqli_num_rows($classes_result) > 0):
            $lesson_number = 1;
            while ($class = mysqli_fetch_assoc($classes_result)):
                // Check if this lesson should be unlocked
                $is_preview = ($lesson_number === 1); // First lesson is always a preview
                $is_unlocked = false;

                if ($is_preview || $is_enrolled) {
                    // Check lesson unlock status
                    $progress_query = "SELECT is_unlocked FROM class_progress WHERE user_id = ? AND class_id = ?";
                    $progress_stmt = mysqli_prepare($conn, $progress_query);
                    mysqli_stmt_bind_param($progress_stmt, "ii", $_SESSION['user_id'], $class['id']);
                    mysqli_stmt_execute($progress_stmt);
                    $progress_result = mysqli_fetch_assoc(mysqli_stmt_get_result($progress_stmt));
                    $is_unlocked = $progress_result['is_unlocked'] ?? false;
                    
                    // If it's the preview lesson, automatically unlock it
                    if ($is_preview) {
                        $is_unlocked = true;
                    }
                    
                    // Unlock second lesson upon enrollment
                    elseif ($lesson_number === 2 && $is_enrolled) {
                        $unlock_query = "INSERT INTO class_progress (user_id, class_id, is_unlocked) 
                           VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE is_unlocked = 1";
                        $unlock_stmt = mysqli_prepare($conn, $unlock_query);
                        mysqli_stmt_bind_param($unlock_stmt, "ii", $_SESSION['user_id'], $class['id']);
                        mysqli_stmt_execute($unlock_stmt);
                        $is_unlocked = true;
                    }
                    // For lessons beyond the second, require completion of the previous quiz
                    elseif ($lesson_number > 2) {
                        $prev_class_id = $class['id'] - 1;
                        $quiz_query = "SELECT percentage FROM quiz_results 
                            WHERE user_id = ? AND class_id = ? 
                            ORDER BY submitted_at DESC LIMIT 1";
                        $quiz_stmt = mysqli_prepare($conn, $quiz_query);
                        mysqli_stmt_bind_param($quiz_stmt, "ii", $_SESSION['user_id'], $prev_class_id);
                        mysqli_stmt_execute($quiz_stmt);
                        $quiz_result = mysqli_fetch_assoc(mysqli_stmt_get_result($quiz_stmt));
                        
                        if ($quiz_result && $quiz_result['percentage'] >= 70) {
                            $unlock_query = "INSERT INTO class_progress (user_id, class_id, is_unlocked) 
                               VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE is_unlocked = 1";
                            $unlock_stmt = mysqli_prepare($conn, $unlock_query);
                            mysqli_stmt_bind_param($unlock_stmt, "ii", $_SESSION['user_id'], $class['id']);
                            mysqli_stmt_execute($unlock_stmt);
                            $is_unlocked = true;
                        }
                    }
                }
        ?>
            <div class="lesson-card w-56 h-64 sm:w-64 sm:h-72 md:w-72 md:h-80">
                <?php if ($is_unlocked): ?>
                    <div class="video-container">
                        <video controls controlsList="nodownload">
                            <source src="<?php echo 'serve_video.php?video=' . urlencode($class['video_path']); ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                <?php else: ?>
                    <div class="video-container">
                        <div class="lock-overlay">
                            <i class="fas fa-lock text-3xl text-white"></i>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="p-4 sm:p-5">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs sm:text-sm font-medium text-blue-600">Lesson <?php echo $lesson_number; ?></span>
                        <?php if ($is_unlocked): ?>
                            <span class="text-xs sm:text-sm text-green-600"><i class="fas fa-unlock"></i> Unlocked</span>
                        <?php else: ?>
                            <span class="text-xs sm:text-sm text-gray-500"><i class="fas fa-lock"></i> Locked</span>
                        <?php endif; ?>
                    </div>

                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">
                        <?php echo htmlspecialchars($class['class_name']); ?>
                    </h3>
                    <p class="text-gray-600 text-xs sm:text-sm mb-3">
                        <?php echo htmlspecialchars($class['description']); ?>
                    </p>

                    <?php if ($is_unlocked): ?>
                        <?php if (!$is_preview): ?>
                            <a href="take_quiz.php?class_id=<?php echo $class['id']; ?>"
                                class="block w-full bg-blue-600 text-white text-center py-1.5 sm:py-2 rounded-lg hover:bg-blue-700 transition duration-300 text-xs sm:text-sm">
                                Take Quiz
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <?php if (!$is_enrolled): ?>
                                <p class="text-xs sm:text-sm text-gray-600 mb-2">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Enroll to unlock this lesson
                                </p>
                                <button class="w-full bg-blue-600 text-white py-1.5 sm:py-2 rounded-lg hover:bg-blue-700 transition-colors enrollButton text-xs sm:text-sm"
                                    data-course-id="<?php echo $course_id; ?>"
                                    data-user-id="<?php echo $_SESSION['user_id']; ?>"
                                    data-course-prize="<?php echo isset($course['course_prize']) ? $course['course_prize'] : '0'; ?>">
                                    Enroll Now
                                </button>
                            <?php else: ?>
                                <p class="text-xs sm:text-sm text-gray-600">
                                    <i class="fas fa-trophy mr-1"></i>
                                    Complete previous lesson's quiz with 70% score to unlock
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php
            $lesson_number++;
            endwhile;
        else:
        ?>
            <div class="col-span-full text-center py-12">
                <i class="fas fa-book-open text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-600">No lessons available for this course yet.</p>
            </div>
        <?php endif; ?>
    </div>
    <!-- Tutor Rating Section -->
    <?php if (!empty($_SESSION['user_id'])): ?>
        <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-md" class="a">
            <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">Rate Your Experience</h3>
            <form method="POST" action="submit_rating.php">
                <input type="hidden" name="tutor_id" value="<?php echo $tutor_id; ?>">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">

                <!-- Star Rating (Right to Left Fill) -->
                <div class="stars mb-6 flex flex-row-reverse gap-2 justify-center">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <input class="hidden star" id="star-<?php echo $i; ?>" type="radio" name="rating" value="<?php echo $i; ?>" required>
                        <label class="text-3xl cursor-pointer text-gray-400 hover:text-yellow-500 transition-all" for="star-<?php echo $i; ?>">★</label>
                    <?php endfor; ?>
                </div>

                <!-- Review Text Area -->
                <div class="mb-4">
                    <label for="review" class="block text-gray-700 mb-2">Your Review</label>
                    <textarea id="review" name="review" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Share your experience..."></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-all">
                    Submit Review
                </button>
            </form>
        </div>
    <?php endif; ?>
</div>



    </div>
    <!-- Keep the existing JavaScript code unchanged -->
    <script>
        // JavaScript for handling enrollment and payment
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('enrollButton')) {
                var courseId = event.target.getAttribute('data-course-id');
                var userId = event.target.getAttribute('data-user-id');
                var coursePrize = event.target.getAttribute('data-course-prize');
                fetchCoursePrize(courseId, function(coursePrize) {
                    enrollCourse(courseId, userId, coursePrize);
                });
            }
        });

        function fetchCoursePrize(courseId, callback) {
            $.ajax({
                type: 'GET',
                url: 'getCoursePrice.php',
                data: {
                    course_id: courseId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        callback(response.course_prize);
                    } else {
                        Swal.fire("Error", response.message, "error");
                    }
                },
                error: function(xhr) {
                    Swal.fire("Error", "Failed to fetch course price.", "error");
                }
            });
        }

        function enrollCourse(courseId, userId, classId, coursePrize, tutorId) {
            $.ajax({
                type: 'POST',
                url: 'create_order.php',
                data: {
                    course_id: courseId,
                    user_id: userId,
                    tutor_id: tutorId, // Pass tutor_id to backend
                },
                dataType: 'json',
                success: function(response) {
                    console.log("RAW response:", response);
                    if (response && response.success && response.orders && Array.isArray(response.orders)) {
                        var razorpayKey = "<?php echo isset($razorpayKey) ? $razorpayKey : ''; ?>";
                        if (!razorpayKey) {
                            Swal.fire("Configuration Error", "Razorpay key is missing.", "error");
                            return;
                        }
                        var options = {
                            key: '<?php echo $razorpayKey; ?>',
                            amount: response.total_price * 100,
                            currency: 'INR',
                            name: 'Course Enrollment',
                            description: 'Enroll in ' + response.title,
                            image: 'assets/images/logo2.png',
                            order_id: response.orders[0].order_id,
                            handler: function(paymentResponse) {
                                console.log("Payment Response:", paymentResponse);
                                showBuffering();
                                // Pass tutor_id properly
                                verifyPayment(paymentResponse, response, courseId, userId, response.razorpay_order_id, response.title, tutorId, );
                            },
                            modal: {
                                ondismiss: function() {
                                    console.log("Checkout form closed");
                                }
                            },
                            theme: {
                                color: '#F37254'
                            }
                        };
                        console.log("Options object:", options);
                        var rzp1 = new Razorpay(options);
                        rzp1.on('payment.failed', function(response) {
                            console.error("Payment failed:", response.error);
                            Swal.fire("Payment Failed!", response.error.description, "error");
                        });
                        rzp1.open();
                    } else {
                        Swal.fire("Order Creation Failed!", response.message, "error");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Order Creation AJAX error:", status, error);
                    Swal.fire("Enrollment Failed!", "An unexpected error occurred. Please try again.", "error");
                }
            });
        }
        // Show error alert using SweetAlert
        function showErrorAlert(title, message) {
            Swal.fire({
                icon: 'error',
                title: title,
                text: message
            });
        }

        function showBuffering() {
            Swal.fire({
                title: "Processing...",
                text: "Please wait while we verify your payment.",
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
        }

        function verifyPayment(paymentResponse, orderResponse, courseId, userId, course_prize, title, tutor_id) {
            const order_id = (Array.isArray(orderResponse.orders) && orderResponse.orders.length > 0) ?
                orderResponse.orders[0].order_id :
                null;
            if (!order_id) {
                console.error("Order ID is missing");
                return;
            }
            if (!order_id || !paymentResponse.razorpay_payment_id || !paymentResponse.razorpay_signature) {
                console.error("Missing required payment details:", {
                    order_id,
                    razorpay_payment_id: paymentResponse.razorpay_payment_id,
                    razorpay_signature: paymentResponse.razorpay_signature
                });
                Swal.fire({
                    icon: 'error',
                    title: "Payment Error",
                    text: "Required payment details are missing!"
                });
                return;
            }
            jQuery.ajax({
                type: 'POST',
                url: 'verify_payment.php',
                data: {
                    razorpay_payment_id: paymentResponse.razorpay_payment_id,
                    razorpay_order_id: order_id,
                    razorpay_signature: paymentResponse.razorpay_signature,
                    course_id: courseId,
                    user_id: userId,
                    course_prize: orderResponse.course_prize,
                    title: orderResponse.title,
                    tutor_id: orderResponse.tutor_id // Default to avoid null value
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: "Enrollment Successful!",
                            text: "Payment Verified and Enrollment Successful!"
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: "Failed!",
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: "Error!",
                        text: "An error occurred while verifying payment."
                    });
                }
            });
        }
    </script>
</body>

</html>
<?php include 'footer.php'; ?>