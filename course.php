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

$avg_rating = ($rating_data['avg_rating']) ? round($rating_data['avg_rating'], 1) : 0; // Default to 0 if no ratings
$num_reviews = $rating_data['num_reviews'];

// Check if the user is already enrolled in the course
$enrollment_query = "SELECT COUNT(*) AS count FROM enrollments WHERE user_id = ? AND course_id = ?";
$enrollment_stmt = mysqli_prepare($conn, $enrollment_query);

if ($enrollment_stmt) {
    mysqli_stmt_bind_param($enrollment_stmt, "ii", $_SESSION['user_id'], $course['id']);
    mysqli_stmt_execute($enrollment_stmt);

    // Fetch the result
    $enrollment_result = mysqli_stmt_get_result($enrollment_stmt);
    $enrollment_data = mysqli_fetch_assoc($enrollment_result);
    $is_enrolled = ($enrollment_data['count'] > 0);

    mysqli_stmt_close($enrollment_stmt);
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
        .course-header {
            background: linear-gradient(135deg, #4F46E5 0%, #2563EB 100%);
        }

        .lesson-card {
            transition: all 0.3s ease;
        }

        .lesson-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.15);
        }

        .rating-stars input[type="radio"] {
            display: none;
        }

        .rating-stars label {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .rating-stars label:hover {
            transform: scale(1.2);
        }

        .video-container {
            position: relative;
            padding-top: 56.25%;
        }

        .video-container video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 0.5rem;
        }

        .progress-bar {
            height: 4px;
            background: #E5E7EB;
            border-radius: 2px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            background: #2563EB;
            transition: width 0.3s ease;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Course Header -->
    <div class="course-header text-white py-16 px-4 mb-8">
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="w-full md:w-2/3">
                <h1 class="text-4xl font-bold mb-4" style="font-family: 'Nunito', sans-serif;"><?php echo htmlspecialchars($course['title']); ?></h1>

                    <p class="text-lg text-blue-100 mb-6"><?php echo htmlspecialchars($course['description']); ?></p>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center">
                            <span class="text-yellow-400 text-xl"><?php echo str_repeat('★', $avg_rating); ?></span>
                            <span class="ml-2 text-blue-100"><?php echo $avg_rating; ?> (<?php echo $num_reviews; ?> reviews)</span>
                        </div>
                        <?php if (!$is_enrolled): ?>
                            <button class="enrollButton bg-white text-blue-600 px-6 py-2 rounded-full font-semibold hover:bg-blue-50 transition-colors"
                                data-course-id="<?php echo $course_id; ?>"
                                data-user-id="<?php echo $_SESSION['user_id']; ?>"
                                data-course-prize="<?php echo isset($course['course_prize']) ? $course['course_prize'] : '0'; ?>">
                                Enroll Now
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="w-full md:w-1/3">
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <img src="assets/images/tutor.jpg" alt="<?php echo htmlspecialchars($course['tutor_name']); ?>"
                                class="w-16 h-16 rounded-full object-cover border-2 border-white">
                            <div>
                                <h3 class="font-semibold"><?php echo htmlspecialchars($course['tutor_name']); ?></h3>
                                <p class="text-blue-100"><?php echo htmlspecialchars($course['tutor_email']); ?></p>
                            </div>
                        </div>
                        <div class="text-sm text-blue-100">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fas fa-video"></i>
                                <span><?php echo mysqli_num_rows($classes_result); ?> Lessons</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-clock"></i>
                                <span>Self-paced learning</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Content -->
   <!-- Course Content -->
<div class="max-w-6xl mx-auto px-4 mb-16">
    <h2 class="text-2xl font-bold text-gray-900 mb-8">Course Content</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php
        if (mysqli_num_rows($classes_result) > 0):
            $lesson_number = 1;
            while ($class = mysqli_fetch_assoc($classes_result)):
                // Check if this lesson should be unlocked
                $is_preview = ($lesson_number === 1);
                $is_unlocked = false;

                if ($is_preview || $is_enrolled) {
                    // Check lesson unlock status
                    $progress_query = "SELECT is_unlocked FROM class_progress WHERE user_id = ? AND class_id = ?";
                    $progress_stmt = mysqli_prepare($conn, $progress_query);
                    mysqli_stmt_bind_param($progress_stmt, "ii", $_SESSION['user_id'], $class['id']);
                    mysqli_stmt_execute($progress_stmt);
                    $progress_result = mysqli_fetch_assoc(mysqli_stmt_get_result($progress_stmt));
                    $is_unlocked = $progress_result['is_unlocked'] ?? false;

                    // First lesson is always unlocked
                    if ($is_preview) {
                        $is_unlocked = true;
                    }
                    // Second lesson is unlocked upon enrollment
                    elseif ($lesson_number === 2 && $is_enrolled) {
                        $unlock_query = "INSERT INTO class_progress (user_id, class_id, is_unlocked) 
                           VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE is_unlocked = 1";
                        $unlock_stmt = mysqli_prepare($conn, $unlock_query);
                        mysqli_stmt_bind_param($unlock_stmt, "ii", $_SESSION['user_id'], $class['id']);
                        mysqli_stmt_execute($unlock_stmt);
                        $is_unlocked = true;
                    }
                    // Other lessons require previous quiz completion
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
                <div class="lesson-card bg-white rounded-xl overflow-hidden shadow-lg">
                    <?php if ($is_unlocked): ?>
                        <div class="video-container">
                            <video controls controlsList="nodownload">
                                <source src="<?php echo 'serve_video.php?video=' . urlencode($class['video_path']); ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    <?php else: ?>
                        <div class="bg-gray-100 h-48 flex items-center justify-center">
                            <i class="fas fa-lock text-4xl text-gray-400"></i>
                        </div>
                    <?php endif; ?>

                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm font-medium text-blue-600">Lesson <?php echo $lesson_number; ?></span>
                            <?php if ($is_unlocked): ?>
                                <span class="text-sm text-green-600"><i class="fas fa-unlock"></i> Unlocked</span>
                            <?php else: ?>
                                <span class="text-sm text-gray-500"><i class="fas fa-lock"></i> Locked</span>
                            <?php endif; ?>
                        </div>

                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            <?php echo htmlspecialchars($class['class_name']); ?>
                        </h3>
                        <p class="text-gray-600 text-sm mb-4">
                            <?php echo htmlspecialchars($class['description']); ?>
                        </p>

                        <?php if ($is_unlocked): ?>
                            <?php if (!$is_preview): ?>
                                <a href="take_quiz.php?class_id=<?php echo $class['id']; ?>"
                                    class="block w-full bg-blue-600 text-white text-center py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                                    Take Quiz
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <?php if (!$is_enrolled): ?>
                                    <p class="text-sm text-gray-600 mb-3">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        Enroll to unlock this lesson
                                    </p>
                                    <button class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors enrollButton"
                                        data-course-id="<?php echo $course_id; ?>"
                                        data-user-id="<?php echo $_SESSION['user_id']; ?>"
                                        data-course-prize="<?php echo isset($course['course_prize']) ? $course['course_prize'] : '0'; ?>">
                                        Enroll Now
                                    </button>
                                <?php else: ?>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-trophy mr-2"></i>
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
        <div class="mt-16 bg-white rounded-xl shadow-lg p-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Rate Your Experience</h3>
            <form method="POST" action="submit_rating.php" class="max-w-2xl">
                <input type="hidden" name="tutor_id" value="<?php echo $tutor_id; ?>">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">

                <div class="rating-stars flex items-center gap-4 mb-6">
                    <span class="text-gray-700">Rating:</span>
                    <div class="flex gap-2">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>" required>
                            <label for="star<?php echo $i; ?>" class="text-3xl text-yellow-400 hover:text-yellow-500">★</label>
                        <?php endfor; ?>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="review" class="block text-gray-700 mb-2">Your Review</label>
                    <textarea id="review" name="review" rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Share your experience with this course..."></textarea>
                </div>

                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Submit Review
                </button>
            </form>
        </div>
    <?php endif; ?>
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
