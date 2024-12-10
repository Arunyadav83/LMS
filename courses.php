<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config.php';
require_once 'functions.php';
include 'header.php';

// Fetch all courses from the database
$query = "SELECT c.id, c.title, c.description, t.full_name AS tutor_name 
          FROM courses c
          LEFT JOIN tutors t ON c.tutor_id = t.id
          ORDER BY c.created_at DESC";
$result = mysqli_query($conn, $query);

// Assuming you have defined the Razorpay key in your config.php
$razorpayKey = 'rzp_test_Bvq9kiuaq8gkcs'; // Your Razorpay API key
?>

<div class="container mt-2">
    <h1 class="mb-2 text-center text-md-left">All Courses</h1>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="row">
            <?php while ($course = mysqli_fetch_assoc($result)): ?>
                <div class="col-12 col-sm-6 col-md-3 mb-3">
                    <div class="card h-60" style="padding: 0px;!important">
                        <img src="assets/images/-<?php echo $course['id']; ?>.jpg" class="card-img-top img-fluid" style="max-height: 150px; object-fit: cover;" alt="<?php echo htmlspecialchars($course['title']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                            <p class="card-text" style="max-height: 50px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo htmlspecialchars($course['description']); ?></p>
                            <p class="card-text"><small class="text-muted">Tutor: <?php echo htmlspecialchars($course['tutor_name']); ?></small></p>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <?php if (is_logged_in()): ?>
                                <a href="course.php?id=<?php echo $course['id']; ?>" class="btn btn-primary btn-sm">View Course</a>
                                <a href="javascript:void(0)" onclick="enrollCourse(<?php echo $course['id']; ?>, <?php echo $_SESSION['user_id']; ?>)" class="btn btn-success btn-sm">Enroll</a>
                            <?php else: ?>
                                <a href="login.php" class="btn btn-secondary btn-sm">Login to Enroll</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No courses available at the moment.</p>
    <?php endif; ?>
</div>

<!-- Include jQuery before your scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<!-- Include SweetAlert CSS and JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

<script>
function showSuccessAlert() {
    swal("Enroll successful!", "You have successfully enrolled in the course.", "success");
}
</script>

<!-- <script>
$(document).ready(function() {
    $('.enroll-form').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        var form = $(this);
        $.ajax({
            type: 'POST',
            url: 'verify_payment.php', // URL to the PHP file that processes the enrollment
            data: form.serialize(), // Serialize form data
            success: function(response) {
                console.log("Raw response:", response); // Log the raw response
                try {
                    // Check if the response is already an object, and if not, parse it
                    if (typeof response === 'string') {
                        response = JSON.parse(response); // Parse the response if it's a string
                    }

                    if (response.success) {
                        var courseId = form.find('input[name="course_id"]').val();
                        
                        // First make the AJAX call
                        $.ajax({
                            type: 'POST',
                            url: 'verify_payment.php',
                            data: {
                                razorpay_payment_id: paymentResponse.razorpay_payment_id,
                                order_id: response.order_id,
                                razorpay_signature: paymentResponse.razorpay_signature,
                                course_id: courseId,
                                user_id: userId,
                                course_prize: response.course_prize,
                                title: response.title
                                tutor_id: response.tutor_id
                            },
                            success: function(ajaxResponse) {
                                // After successful AJAX call, redirect to verify_payment.php
                                window.location.href = 'verify_payment.php?course_id=' + courseId + 
                                    '&razorpay_payment_id=' + paymentResponse.razorpay_payment_id + 
                                    '&payment_id=' + paymentResponse.razorpay_payment_id +
                                    '&order_id=' + response.order_id + 
                                    '&razorpay_signature=' + paymentResponse.razorpay_signature +
                                    '&title=' + response.title +
                                    '&user_id=' + userId +
                                    '&course_id=' + courseId +
                                    '&course_prize=' + response.course_prize+
                                    '&tutor_id=' + response.tutor_id
                            },
                            error: function(xhr, status, error) {
                                console.error('Payment verification failed:', error);
                            }
                        });
                    } else {
                        swal("Enrollment failed!", response.message, "error");
                    }
                } catch (e) {
                    console.error("Parsing error:", e);
                    console.error("Response received:", response); // Log the raw response for debugging
                    swal("Enrollment failed!", "An unexpected error occurred. Please check the console for details.", "error");
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX error:", status, error); // Log AJAX error details
                swal("Enrollment failed!", "Please try again.", "error");
            }
        });
    });
});
</script> -->

<script>
function enrollCourse(courseId, userId) {
    $.ajax({
        type: 'POST',
        url: 'create_order.php',
        data: { course_id: courseId, user_id: userId },
        success: function(response) {
            console.log("Raw response:", response);
            try {
                response = typeof response === 'string' ? JSON.parse(response) : response;

                if (response.success) {
                    var options = {
                        key: '<?php echo $razorpayKey; ?>',
                        amount: response.amount,
                        currency: 'INR',
                        name: 'Course Enrollment',
                        description: 'Enroll in Course',
                        image: 'assets/images/logo.png',
                        order_id: response.order_id,
                        handler: function(paymentResponse) {
                            // Send payment details to the backend for verification
                            $.ajax({
                                type: 'POST',
                                url: 'verify_payment.php',
                               
                                    data: {
                                        razorpay_payment_id: paymentResponse.razorpay_payment_id,
                                        order_id: response.order_id,
                                        razorpay_signature: paymentResponse.razorpay_signature,
                                        course_id: courseId,
                                        user_id: userId,
                                        course_prize: response.course_prize,
                                        title: response.title,
                                        tutor_id: response.tutor_id,
                                        enrolled_at: response.enrolled_at
                                    },
                                success: function(verifyResponse) {
                                    console.log("Verify Response:", verifyResponse);
                                    verifyResponse = typeof verifyResponse === 'string' ? JSON.parse(verifyResponse) : verifyResponse;

                                    if (verifyResponse.success) {
                                        showSuccessAlert();
                                    } else {
                                        swal("Payment Verification Failed!", verifyResponse.message, "error");
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error("Verification AJAX error:", status, error);
                                    swal("Payment Verification Failed!", "Please try again.", "error");
                                }
                            });
                        },
                        theme: { color: '#F37254' }
                    };
                    var rzp1 = new Razorpay(options);
                    rzp1.open();
                } else {
                    swal("Order Creation Failed!", response.message, "error");
                }
            } catch (e) {
                console.error("Parsing error:", e, response);
                swal("Enrollment Failed!", "An unexpected error occurred.", "error");
            }
        },
        error: function(xhr, status, error) {
            console.error("Order Creation AJAX error:", status, error);
            swal("Enrollment Failed!", "Please try again.", "error");
        }
    });
}
</script>
