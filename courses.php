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

// Razorpay API key from the config
$razorpayKey = 'rzp_test_Bvq9kiuaq8gkcs'; // Your Razorpay API key
?>

<div class="container mt-2">
    <h1 class="mb-2 text-center text-md-left">All Courses</h1>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="row">
            <?php while ($course = mysqli_fetch_assoc($result)): ?>
                <div class="col-12 col-sm-6 col-md-3 mb-3">
                    <div class="card h-60" style="padding: 0px;!important">
                        <img
                            src="assets/images/<?php echo htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8'); ?>.jpg"
                            class="card-img-top img-fluid"
                            style="max-height: 150px; object-fit: cover;"
                            alt="<?php echo htmlspecialchars($course['title']); ?>">
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

<!-- Include jQuery and Razorpay scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<!-- Include SweetAlert CSS and JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

<!-- <script>
    function showSuccessAlert() {
        swal("Enroll successful!", "You have successfully enrolled in the course.", "success");
    }
</script> -->

<script>
    var username = '<?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') : ''; ?>';
</script>

<script>
    function enrollCourse(courseId, userId) {
        $.ajax({
            type: 'POST',
            url: 'create_order.php',
            data: {
                course_id: courseId,
                user_id: userId
            },
            dataType: 'json',
            success: function(response) {
                console.log("RAW response:", response);
                if (response.success) {
                    var options = {
                        key: '<?php echo $razorpayKey; ?>',
                        amount: response.course_prize * 100, // Convert to paise
                        currency: 'INR',
                        name: 'Course Enrollment',
                        description: 'Enroll in ' + response.title,
                        image: 'assets/images/logo.png',
                        order_id: response.order_id,
                        handler: function(paymentResponse) {
                            verifyPayment(paymentResponse, response, courseId, userId);
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
                    var rzp1 = new Razorpay(options);
                    rzp1.on('payment.failed', function(response) {
                        console.error("Payment failed:", response.error);
                        showErrorAlert("Payment Failed!", response.error.description);
                    });
                    rzp1.open();
                } else {
                    showErrorAlert("Order Creation Failed!", response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Order Creation AJAX error:", status, error);
                showErrorAlert("Enrollment Failed!", "An unexpected error occurred. Please try again.");
            }
        });

    }

    function verifyPayment(paymentResponse, orderResponse, courseId, userId) {
        $.ajax({
            type: 'POST',
            url: 'verify_payment.php',
            data: {
                razorpay_payment_id: paymentResponse.razorpay_payment_id,
                order_id: paymentResponse.razorpay_order_id,
                razorpay_signature: paymentResponse.razorpay_signature,
                course_id: courseId,
                course_prize: orderResponse.course_prize,
                title: orderResponse.title,
                tutor_id: orderResponse.tutor_id
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    swal("Success!", response.message, "success");
                } else {
                    swal("Failed!", response.message, "error");
                }
            },
            error: function(xhr, status, error) {
                console.error("Verification error:", error);
                swal("Failed!", "An unexpected error occurred while verifying the payment.", "error");
            }
        });
    }

    function showSuccessAlert() {
        swal({
            title: "Enrollment Successful!",
            text: "You have successfully enrolled in the course.",
            icon: "success",
            button: "OK",
            className: "green-bg"
        })
    }

    function showErrorAlert(title, message) {
        swal({
            title: title,
            text: message,
            icon: "error",
            button: "OK",
        });
    }
</script>
<?php include 'footer.php'; ?>
<!-- <script>
    function showSuccessAlert() {
    swal({
        title: "Enrollment Successful!",
        text: "You have successfully enrolled in the course.",
        icon: "success",
        button: "OK",
    }).then(function() {
        // Optionally, you can redirect the user or refresh the page
        window.location.reload();
    });
}
</script> -->