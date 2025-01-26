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

$course_id = (int)$_GET['id'];
// Check if the user is already enrolled in the course
$enrollment_query = "SELECT COUNT(*) AS count FROM enrollments WHERE user_id = ? AND course_id = ?";
$enrollment_stmt = mysqli_prepare($conn, $enrollment_query);

if ($enrollment_stmt) {
    mysqli_stmt_bind_param($enrollment_stmt, "ii", $_SESSION['user_id'], $class['course_id']);
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

// Fetch course details
$course_query = "SELECT c.id, c.title, c.description, t.full_name AS tutor_name 
                 FROM courses c
                 LEFT JOIN tutors t ON c.tutor_id = t.id
                 WHERE c.id = ?";
$course_stmt = mysqli_prepare($conn, $course_query);
if (!$course_stmt) {
    die("Database query preparation failed: " . mysqli_error($conn));
}

// Check if binding parameters is successfull
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


<div class="container mt-4">
    <h1 class="text-center text-primary"><?php echo htmlspecialchars($course['title']); ?></h1>
    <div class="row align-items-center">
        <!-- Tutor Image -->
        <?php
        $tutor_image_path = 'assets/images/' . strtolower(str_replace(' ', '_', $course['title'])) . '.jpg';
        ?>
        <div class="col-md-4">
            <?php if (file_exists($tutor_image_path)): ?>
                <img
                    src="<?php echo $tutor_image_path; ?>"
                    class="img-fluid rounded-square"
                    style="max-height: 150px; object-fit: cover;margin-top: 26px;"
                    alt="<?php echo htmlspecialchars($course['title']); ?>">
            <?php else: ?>
                <img
                    src="assets/images/default_tutor.jpg"
                    class="img-fluid rounded-circle"
                    style="max-height: 150px; object-fit: cover;"
                    alt="Default Tutor">
            <?php endif; ?>
        </div>
        <div class="col-md-8">
            <p class="lead"><strong>Tutor:</strong> <?php echo htmlspecialchars($course['tutor_name']); ?></p>
            <p class="text-secondary"><?php echo htmlspecialchars($course['description']); ?></p>
        </div>
    </div>

    <h2 class="mt-5 mb-4 text-center text-info">Course Content</h2>

    <?php if (mysqli_num_rows($classes_result) > 0): ?>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php
            $lesson_number = 1; // Start lesson numbering
            $previous_lesson_unlocked = true; // Preview is always unlocked

            while ($class = mysqli_fetch_assoc($classes_result)):

                $is_preview = ($lesson_number === 1); // First lesson is a preview
                $is_enrolled = ($is_preview || $class['is_unlocked'] == 1 || $is_enrolled);

                // Check if user is enrolled in the course
                $enrollment_check_query = "SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?";
                $enrollment_stmt = mysqli_prepare($conn, $enrollment_check_query);
                mysqli_stmt_bind_param($enrollment_stmt, "ii", $_SESSION['user_id'], $class['course_id']);
                mysqli_stmt_execute($enrollment_stmt);
                $enrollment_result = mysqli_stmt_get_result($enrollment_stmt);
                $is_enrolled = mysqli_num_rows($enrollment_result) > 0;

                // Check if previous lesson's quiz was completed with at least 70%
                $prev_class_id = $class['id'] - 1;
                $quiz_check_query = "SELECT percentage FROM quiz_results WHERE user_id = ? AND class_id = ? ORDER BY submitted_at DESC LIMIT 1";
                $quiz_stmt = mysqli_prepare($conn, $quiz_check_query);
                mysqli_stmt_bind_param($quiz_stmt, "ii", $_SESSION['user_id'], $prev_class_id);
                mysqli_stmt_execute($quiz_stmt);
                $quiz_result = mysqli_stmt_get_result($quiz_stmt);
                $quiz_score = mysqli_fetch_assoc($quiz_result);

                // Determine if the current lesson is unlocked
                $is_unlocked = ($is_preview || $is_enrolled) && ($lesson_number == 2 || ($quiz_score && $quiz_score['percentage'] >= 70));

                // Display each lesson
            ?>
                <div class="col">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-success">
                                Lesson <?php echo $lesson_number; ?>: <?php echo htmlspecialchars($class['class_name']); ?>
                            </h5>

                            <?php if ($is_preview): ?>
                                <!-- Always show preview video -->
                                <?php if (!empty($class['video_path'])): ?>
                                    <?php $video_url = 'serve_video.php?video=' . urlencode($class['video_path']); ?>
                                    <div class="video-container">
                                        <video
                                            class="rounded mb-3 video-player"
                                            controls
                                            controlsList="nodownload"
                                            style="width: 100%; cursor: pointer;">
                                            <source src="<?php echo $video_url; ?>" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                <?php else: ?>
                                    <p class="text-danger">No video available for this class.</p>
                                <?php endif; ?>

                            <?php elseif ($is_unlocked): ?>
                                <!-- Show lesson video -->
                                <?php if (!empty($class['video_path'])): ?>
                                    <?php $video_url = 'serve_video.php?video=' . urlencode($class['video_path']); ?>
                                    <div class="video-container">
                                        <video
                                            class="rounded mb-3 video-player"
                                            controls
                                            controlsList="nodownload"
                                            style="width: 100%; cursor: pointer;">
                                            <source src="<?php echo $video_url; ?>" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>

                                    <!-- Show Take Quiz button for the current lesson -->
                                    <a href="take_quiz.php?class_id=<?php echo $class['id']; ?>" class="btn btn-primary btn-block mt-3">
                                        Take Quiz
                                    </a>
                                <?php else: ?>
                                    <p class="text-danger">No video available for this class.</p>
                                <?php endif; ?>

                            <?php else: ?>
                                <!-- Lesson is locked -->
                                <p class="text-muted">
                                    This lesson is locked. Complete the previous lesson's quiz with at least 70% to unlock.
                                </p>
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





</div>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>



<script>
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('enrollButton')) {
            var courseId = event.target.getAttribute('data-course-id');
            var userId = event.target.getAttribute('data-user-id');
            var classId = event.target.getAttribute('data-class-id');
            enrollCourse(courseId, userId, classId);
        }
    });

    function enrollCourse(courseId, userId, classId) {
        console.log("Initiating enrollment...");

        jQuery.ajax({
            type: 'POST',
            url: 'create_order.php',
            data: {
                course_id: courseId,
                user_id: userId,
                class_id: classId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var options = {
                        key: '<?php echo $razorpayKey; ?>',
                        amount: response.course_prize * 100,
                        currency: 'INR',
                        name: 'Course Enrollment',
                        description: 'Enroll in ' + response.title,
                        order_id: response.order_id,
                        handler: function(paymentResponse) {
                            showBuffering();
                            verifyPayment(paymentResponse, response, courseId, userId);
                        },
                        theme: {
                            color: '#F37254'
                        }
                    };
                    var rzp1 = new Razorpay(options);
                    rzp1.open();
                } else {
                    showErrorAlert("Order Creation Failed!", response.message);
                }
            },
            error: function(xhr, status, error) {
                showErrorAlert("Enrollment Failed!", "An unexpected error occurred. Please try again.");
            }
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

    function verifyPayment(paymentResponse, orderResponse, courseId, userId) {
        jQuery.ajax({
            type: 'POST',
            url: 'verify_payment.php',
            data: {
                razorpay_payment_id: paymentResponse.razorpay_payment_id,
                order_id: paymentResponse.razorpay_order_id,
                razorpay_signature: paymentResponse.razorpay_signature,
                course_id: courseId
            },
            success: function(response) {
                Swal.close();
                if (response.success) {
                    console.log("Verification Response:", response);
                    Swal.fire("Enrollment Successful!", "Payment Verified and Enrollment Successful!", "success");
                } else {
                    console.error("Verification Error:", response.message);
                    Swal.fire("Failed!", response.message, "error");
                }
            },
            error: function() {
                Swal.close();
                Swal.fire("Error!", "An error occurred while verifying payment.", "error");
            }
        });
    }
</script>




<?php include 'footer.php'; ?>