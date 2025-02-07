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

// Assuming you have a form submission to assign a tutor to a course
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id']) && isset($_POST['tutor_id'])) {
    $course_id = $_POST['course_id'];
    $tutor_id = $_POST['tutor_id'];

    // Prepare the SQL statement to update the course with the selected tutor
    $query_assign_tutor = "UPDATE courses SET tutor_id = ? WHERE id = ?";
    $stmt = $conn->prepare($query_assign_tutor);

    // Check if the statement preparation was successful
    if ($stmt) {
        $stmt->bind_param("ii", $tutor_id, $course_id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Tutor assigned successfully."; // Success message
        } else {
            $_SESSION['error'] = "Failed to assign tutor: " . $stmt->error; // Error message
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Failed to prepare statement: " . $conn->error; // Error message
    }
}

?>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="/path/to/local/jquery.min.js"></script>
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    /* Style the stars for the rating */
    .stars {
        display: flex;
        direction: row;
    }

    .stars input[type="radio"] {
        display: none;
    }

    .stars label {
        font-size: 24px;
        color: gray;
        cursor: pointer;
    }

    .stars .filled {
        color: gold;
    }

    /* Style the review form */
    .rating-form textarea {
        width: 100%;
        padding: 10px;
        margin-top: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .rating-form button {
        margin-top: 10px;
    }
</style>


<div class="container mt-4">
    <h1 class="text-center text-primary"><?php echo htmlspecialchars($course['title']); ?></h1>
    <div class="row align-items-center">
        <!-- Tutor Image -->
        <?php
        $tutor_image_path = 'assets/images/' . strtolower(str_replace(' ', '_', $course['title'])) . '.jpg';
        ?>
        <div class="col-md-4">
            <?php
            // Assuming $tutor_image_path and $fullName are defined
            if (file_exists($tutor_image_path)): ?>
                <img
                    src="<?php echo $tutor_image_path; ?>"
                    class="img-fluid rounded-square"
                    style="max-height: 150px; object-fit: cover; margin-top: 26px;"
                    alt="<?php echo htmlspecialchars($course['title']); ?>">
            <?php else: ?>
                <?php
                // Assume the image file is named based on the tutor's full name (e.g., "John_Doe.jpg")
                $imagePath = "assets/images/" . str_replace(' ', '_', $tutor_name) . ".jpg";

                // Check if the image file exists
                // if (!file_exists($imagePath)) {
                //     $imagePath = "assets/images/default.jpg"; // Use a default image if none found
                // }
                ?>
                <img
                    src="<?php echo $imagePath; ?>"
                    class="img-fluid rounded-circle"
                    style="max-height: 150px; object-fit: cover;"
                    alt="Default Tutor">
            <?php endif; ?>
        </div>

        <div class="col-md-8">
            <p class="lead"><strong>Tutor:</strong> <?php echo htmlspecialchars($course['tutor_name']); ?></p>
            <p class="lead"><strong>&#9993; :</strong> <?php echo htmlspecialchars($course['tutor_email']); ?></p>
            <p class="text-secondary"><?php echo htmlspecialchars($course['description']); ?></p>
        </div>

        <div class="rating-container">
            <h5> Rating: <?php echo $avg_rating; ?> ★ (<?php echo $num_reviews; ?> Reviews)</h5>
            <div class="stars">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <span class="star <?php echo ($i <= $avg_rating) ? 'filled' : ''; ?>">&#9733;</span>
                <?php endfor; ?>
            </div>
        </div>
    </div>

    <h2 class="mt-5 mb-4 text-center text-info">Course Content</h2>
    <?php if (mysqli_num_rows($classes_result) > 0): ?>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php
            $lesson_number = 1;

            while ($class = mysqli_fetch_assoc($classes_result)):

                $is_preview = ($lesson_number === 1); // First lesson is a preview

                // Check if the user is enrolled in the course
                $enrollment_query = "SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?";
                $enrollment_stmt = mysqli_prepare($conn, $enrollment_query);
                mysqli_stmt_bind_param($enrollment_stmt, "ii", $_SESSION['user_id'], $class['course_id']);
                mysqli_stmt_execute($enrollment_stmt);
                $is_enrolled = mysqli_stmt_get_result($enrollment_stmt)->num_rows > 0;
                // Check if the current class is unlocked for this user
                $is_unlocked = false;
                if ($is_preview || $is_enrolled) {
                    $progress_query = "SELECT is_unlocked FROM class_progress WHERE user_id = ? AND class_id = ?";
                    $progress_stmt = mysqli_prepare($conn, $progress_query);
                    mysqli_stmt_bind_param($progress_stmt, "ii", $_SESSION['user_id'], $class['id']);
                    mysqli_stmt_execute($progress_stmt);
                    $progress_result = mysqli_fetch_assoc(mysqli_stmt_get_result($progress_stmt));

                    // If the user has unlocked this class explicitly, allow access
                    $is_unlocked = $progress_result['is_unlocked'] ?? false;

                    // Unlock the second lesson upon enrollment
                    if ($lesson_number === 2 && $is_enrolled && !$is_unlocked) {
                        $unlock_second_lesson_query = "INSERT INTO class_progress (user_id, class_id, is_unlocked) VALUES (?, ?, 1)
                                                   ON DUPLICATE KEY UPDATE is_unlocked = 1";
                        $unlock_second_stmt = mysqli_prepare($conn, $unlock_second_lesson_query);
                        mysqli_stmt_bind_param($unlock_second_stmt, "ii", $_SESSION['user_id'], $class['id']);
                        mysqli_stmt_execute($unlock_second_stmt);
                        $is_unlocked = true;
                    }

                    // Check if the quiz for the previous class was completed with at least 70%
                    if ($lesson_number > 2 && !$is_unlocked) {
                        $prev_class_id = $class['id'] - 1;
                        $quiz_query = "SELECT percentage FROM quiz_results WHERE user_id = ? AND class_id = ? ORDER BY submitted_at DESC LIMIT 1";
                        $quiz_stmt = mysqli_prepare($conn, $quiz_query);
                        mysqli_stmt_bind_param($quiz_stmt, "ii", $_SESSION['user_id'], $prev_class_id);
                        mysqli_stmt_execute($quiz_stmt);
                        $quiz_result = mysqli_fetch_assoc(mysqli_stmt_get_result($quiz_stmt));
                        $is_quiz_completed = $quiz_result && $quiz_result['percentage'] >= 70;

                        if ($is_quiz_completed) {
                            // Unlock the current class for the user
                            $unlock_query = "INSERT INTO class_progress (user_id, class_id, is_unlocked) VALUES (?, ?, 1)
                                         ON DUPLICATE KEY UPDATE is_unlocked = 1";
                            $unlock_stmt = mysqli_prepare($conn, $unlock_query);
                            mysqli_stmt_bind_param($unlock_stmt, "ii", $_SESSION['user_id'], $class['id']);
                            mysqli_stmt_execute($unlock_stmt);

                            $is_unlocked = true;
                        }
                    }
                }
            ?>
                <div class="col">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-success">
                                Lesson <?php echo $lesson_number; ?>: <?php echo htmlspecialchars($class['class_name']); ?>
                            </h5>

                            <?php if ($is_preview): ?>
                                <div class="video-container">
                                    <video class="rounded mb-3 video-player" controls controlsList="nodownload" style="width: 100%; cursor: pointer;">
                                        <source src="<?php echo 'serve_video.php?video=' . urlencode($class['video_path']); ?>" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>

                            <?php elseif ($is_unlocked): ?>
                                <div class="video-container">
                                    <video class="rounded mb-3 video-player" controls controlsList="nodownload" style="width: 100%; cursor: pointer;">
                                        <source src="<?php echo 'serve_video.php?video=' . urlencode($class['video_path']); ?>" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                                <a href="take_quiz.php?class_id=<?php echo $class['id']; ?>" class="btn btn-primary btn-block mt-3">Take Quiz</a>

                            <?php else: ?>
                                <p class="text-muted">
                                    <?php if (!$is_enrolled): ?>
                                        You must enroll in this course to unlock this lesson.
                                    <?php else: ?>
                                        This lesson is locked. Complete the previous lesson's quiz with at least 70% score to unlock.
                                    <?php endif; ?>
                                </p>
                                <?php if (!$is_enrolled): ?>
                                    <button class="btn btn-primary enrollButton"
                                        data-course-id="<?php echo isset($class['course_id']) ? htmlspecialchars($class['course_id']) : ''; ?>"
                                        data-user-id="<?php echo isset($class['user_id']) ? htmlspecialchars($class['user_id']) : ''; ?>"
                                        data-course-prize="<?php echo isset($class['course_prize']) ? htmlspecialchars($class['course_prize']) : '0'; ?>"

                                        data-class-id="<?php echo isset($class['class_id']) ? htmlspecialchars($class['class_id']) : ''; ?>">
                                        Enroll Now
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php
                $lesson_number++;
            endwhile;
            ?>
        </div>
    <?php else: ?>
        <p>No classes available for this course.</p>
    <?php endif; ?>

    <!-- Form to Rate Tutor (Display only for logged-in users) -->
    <?php if (!empty($_SESSION['user_id'])): ?>
        <div class="rating-form">
            <h5>Rate Tutor</h5>
            <form method="POST" action="submit_rating.php">
                <input type="hidden" name="tutor_id" value="<?php echo $tutor_id; ?>">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">

                <!-- Rating Stars -->
                <div class="stars">
                    <input type="radio" id="star5" name="rating" value="5" required><label for="star5">&#9733;</label>
                    <input type="radio" id="star4" name="rating" value="4" required><label for="star4">&#9733;</label>
                    <input type="radio" id="star3" name="rating" value="3" required><label for="star3">&#9733;</label>
                    <input type="radio" id="star2" name="rating" value="2" required><label for="star2">&#9733;</label>
                    <input type="radio" id="star1" name="rating" value="1" required><label for="star1">&#9733;</label>
                </div>


                <textarea name="review" placeholder="Write your review here..." rows="4"></textarea>
                <button type="submit" class="btn btn-primary">Submit Review</button>
            </form>
        </div>
    <?php endif; ?>



</div>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>



<script>
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('enrollButton')) {
            var courseId = event.target.getAttribute('data-course-id');
            var userId = event.target.getAttribute('data-user-id');
            var classId = event.target.getAttribute('data-class-id');
            var tutorId = event.target.getAttribute('data-tutor-id'); // Retrieve tutor_id

            console.log("Course ID:", courseId);
            console.log("Tutor ID:", tutorId);

            fetchCoursePrize(courseId, function(coursePrize) {
                enrollCourse(courseId, userId, classId, coursePrize, tutorId);
            });
        }
    });


    // Function to fetch the course prize dynamically
    function fetchCoursePrize(courseId, callback) {
        jQuery.ajax({
            type: 'GET',
            url: 'getCoursePrice.php', // Your PHP endpoint to fetch course prize
            data: {
                course_id: courseId
            },
            dataType: 'json',
            success: function(response) {
                console.log("Response from getCoursePrice.php:", response); // Log the full response
                if (response.success) {
                    callback(response.course_prize); // Pass course prize to callback
                } else {
                    console.error("Error fetching course prize:", response.message); // Log error message
                    showErrorAlert("Course Prize Fetch Failed", response.message);
                }
            },
            error: function(xhr, status, error) {
                try {
                    let response = JSON.parse(xhr.responseText); // Try parsing the response
                    console.error("AJAX Error:", response.message); // Log error message
                    showErrorAlert("Failed to Fetch Course Prize", response.message);
                } catch (e) {
                    console.error("AJAX Error:", error); // If parsing fails, log the original error
                    showErrorAlert("Failed to Fetch Course Prize", "An unexpected error occurred. Please try again.");
                }
            }
        });
    }



    // Function to enroll the course
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
            // didOpen: () => Swal.showLoading()
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
            tutor_id: orderResponse.tutor_id// Default to avoid null value
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
<?php include 'footer.php'; ?>