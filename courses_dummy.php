<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config.php';
require_once 'functions.php';
include 'header.php';

// $duration = htmlspecialchars($course['duration']); 

// Fetch all courses from the database
$query = "SELECT c.id, c.title, c.description, c.course_prize, t.full_name AS tutor_name ,duration 
          FROM courses c
          LEFT JOIN tutors t ON c.tutor_id = t.id
          ORDER BY c.created_at DESC";

$result = mysqli_query($conn, $query);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'enrollCourse') {
    $class_id = $_POST['class_id'];
    $course_id = $_POST['course_id'];
    $user_id = $_POST['user_id'];

    // Enroll logic
    $enroll_query = "INSERT INTO enrollments (user_id, course_id, class_id, enrolled_at) VALUES (?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $enroll_query);
    mysqli_stmt_bind_param($stmt, "iii", $user_id, $course_id, $class_id);
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = "Enrolled successfully!";
        header("Location: courses.php");
        exit;
    } else {
        $_SESSION['error'] = "Failed to enroll. Please try again.";
        header("Location: courses.php");
        exit;
    }
}

function generateOrderId() {
    // Your logic to generate an order ID (e.g., using a random number or database)
    return uniqid('order_');
}
// Razorpay API key from the config
$razorpayKey = 'rzp_test_Bvq9kiuaq8gkcs'; // Your Razorpay API key
?>
<style>
    /* Base styles */
    :root {
        --primary-color: #2099cf;
        --secondary-color: #15755f;
        --text-color: #2c3e50;
        --light-gray: #f8f9fa;
        --border-radius: 15px;
    }

    body {
        overflow-x: hidden;
    }

    /* Course Card Styles */
    .course-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .course-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .card-img-top {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .card-body {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .card-title {
        font-size: 18px;
        color: #333;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .course-meta {
        margin-bottom: 20px;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
        color: #666;
    }

    .meta-item i {
        color: #17a2b8;
        font-size: 16px;
        width: 20px;
        text-align: center;
    }

    /* Button Styles */
    .button-group {
        display: flex;
        gap: 10px;
        margin-top: auto;
        padding: 0;
    }

    .btn-enroll, .btn-cart {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 10px 10px;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 100;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        min-width: 100px;
    }

    .btn-enroll {
        background-color: #17a2b8;
        color: white;
        border: none;
    }

    .btn-enroll:hover {
        background-color: #138496;
        box-shadow: 0 2px 5px rgba(23, 162, 184, 0.3);
    }

    .btn-cart {
        background-color: #fff;
        color: #17a2b8;
        border: 1px solid #17a2b8;
    }

    .btn-cart:hover {
        background-color: #f8f9fa;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .btn-enroll i, .btn-cart i {
        font-size: 14px;
    }

    @media (max-width: 576px) {
        .button-group {
            flex-direction: column;
            gap: 8px;
        }

        .btn-enroll, .btn-cart {
            width: 100%;
            padding: 8px 15px;
            font-size: 14px;
        }
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .card-body {
            padding: 15px;
        }

        .card-title {
            font-size: 16px;
            margin-bottom: 12px;
        }

        .meta-item {
            font-size: 14px;
            margin-bottom: 8px;
        }
    }

    @media (max-width: 576px) {
        .card-img-top {
            height: 180px;
        }

        .card-body {
            padding: 12px;
        }
    }

    /* New styles for courses header and search bar */
    .courses-header {
        display: flex;
        justify-content: space-around;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 20px;
        
    }

    .section-title {
        font-size: 2.2rem;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
        position: relative;
        padding-bottom: 10px;
    }

    .section-title::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 60px;
        height: 3px;
        background: linear-gradient(45deg, #2099cf, #15755f);
        border-radius: 2px;
    }

    .search-bar-container {
        flex: 0 0 auto;
        min-width: 300px;
    }

    .input-group {
        position: relative;
        display: flex;
        align-items: center;
        background: white;
        border-radius: 50px;
        padding: 5px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .input-group:hover {
        box-shadow: 0 5px 20px rgba(32, 153, 207, 0.15);
        transform: translateY(-2px);
    }

    .form-control {
        border: none;
        padding: 12px 20px;
        border-radius: 25px;
        font-size: 1rem;
        color: #2c3e50;
        width: 100%;
        background: transparent;
    }

    .form-control:focus {
        outline: none;
        box-shadow: none;
    }

    .form-control::placeholder {
        color: #94a3b8;
    }

    .input-group-append .btn {
        background: linear-gradient(45deg, #2099cf, #15755f);
        border: none;
        color: white;
        padding: 12px 20px;
        border-radius: 25px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .input-group-append .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(32, 153, 207, 0.2);
    }

    .input-group-append .btn i {
        font-size: 1rem;
    }

    @media (max-width: 768px) {
        .courses-header {
            flex-direction: column;
            align-items: stretch;
            gap: 15px;
            margin-top: 20px;
        }

        .section-title {
            text-align: center;
            font-size: 1.8rem;
        }

        .section-title::after {
            left: 50%;
            transform: translateX(-50%);
        }

        .search-bar-container {
            width: 100%;
        }

        .input-group {
            width: 100%;
        }
    }
    .hero-section h1 {
    font-weight: 300;
    margin-bottom: 20px;
}
</style>
</head>
<body>
    <!--banner-start-->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 mb-4 animate__animated animate__fadeInDown" >All Courses</h1>
            <!-- <p class="lead animate__animated animate__fadeInUp">Empowering minds through innovation</p> -->
        </div>
    </section>
    <!--banner-end-->
    <div class="courses-header">
        <h2 class="section-title">Explore Our Courses</h2>
        <div class="search-bar-container">
            <div class="input-group">
                <input type="text" id="searchInput" class="form-control" 
                       placeholder="Search courses..." aria-label="Search">
                <div class="input-group-append">
                    <button class="btn" type="button" onclick="performSearch()">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="course-container">
            <div class="container">
                <div class="row g-4">
                    <?php while ($course = mysqli_fetch_assoc($result)): ?>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="course-card">
                                <img src="assets/images/<?php echo htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8'); ?>.jpg"
                                    class="card-img-top"
                                    alt="<?php echo htmlspecialchars($course['title']); ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                                    <div class="course-meta">
                                        <div class="meta-item">
                                            <i class="fas fa-user-tie"></i>
                                            <span><?php echo htmlspecialchars($course['tutor_name']); ?></span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="fas fa-clock"></i>
                                            <span><?php echo htmlspecialchars($course['duration']); ?></span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="fas fa-rupee-sign"></i>
                                            <span><?php echo htmlspecialchars($course['course_prize']); ?></span>
                                        </div>
                                    </div>
                                    <div class="button-group">
                                        <?php if (is_logged_in()): ?>
                                            <button class="btn-enroll" onclick="enrollCourse(<?php echo $course['id']; ?>, <?php echo $_SESSION['user_id']; ?>)">
                                                <i class="fas fa-graduation-cap"></i>
                                                <span>ENROLL NOW</span>
                                            </button>
                                            <button class="btn-cart" onclick="addToCart(<?php echo $course['id']; ?>)">
                                                <i class="fas fa-shopping-cart"></i>
                                                <span>CART</span>
                                            </button>
                                        <?php else: ?>
                                            <a href="login.php" class="btn-enroll">
                                                <i class="fas fa-sign-in-alt"></i>
                                                <span>LOGIN TO ENROLL</span>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle me-2"></i>No courses available at the moment.
        </div>
    <?php endif; ?>

    <!-- Include jQuery and Razorpay scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <!-- Include SweetAlert CSS and JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function performSearch() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            const courses = document.querySelectorAll('.course-card');
            let found = false;

            courses.forEach(course => {
                const title = course.querySelector('.card-title').textContent.toLowerCase();
                const description = course.querySelector('.back-description').textContent.toLowerCase();
                if (title.includes(query) || description.includes(query)) {
                    course.style.display = 'block';
                    found = true;
                } else {
                    course.style.display = 'none';
                }
            });

            if (!found) {
                document.getElementById('searchError').style.display = 'block';
            } else {
                document.getElementById('searchError').style.display = 'none';
            }
        }

        function enrollCourse(courseId, userId) {
            $.ajax({
                type: 'POST',
                url: 'create_order.php',
                data: {
                    course_id: courseId,
                    user_id: userId,
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
                            image: 'assets/images/logo2.png',
                            order_id: response.order_id,
                            handler: function(paymentResponse) {
                                showBuffering(); // Show buffering before verifying payment
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

        function addToCart(courseId) {
            fetch('add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        courseId: courseId
                    }),
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Show success SweetAlert with a green color (without redirection)
                        Swal.fire({
                            title: 'Success!',
                            text: 'Course added to cart successfully!',
                            icon: 'success',
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            background: '#28a745', // Green background color
                            color: '#fff', // White text color
                            toast: true, // Makes it appear as a toast
                            timerProgressBar: true // Adds a progress bar during the timer
                        });
                    } else if (data.message === 'Course already in cart') {
                        // Show message if the course is already in the cart
                        Swal.fire({
                            title: 'Already in Cart',
                            text: 'You have already added this course to your cart.',
                            icon: 'info',
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            background: '#ffc107', // Yellow background for info
                            color: '#fff',
                            toast: true,
                            timerProgressBar: true
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to add course to cart: ' + data.message,
                            icon: 'error',
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            background: '#dc3545', // Red background for error
                            color: '#fff',
                            toast: true,
                            timerProgressBar: true
                        });
                    }
                })
                .catch(error => {
                    console.error('Error adding course to cart:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred. Please try again later.',
                        icon: 'error',
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        background: '#dc3545', // Red background for error
                        color: '#fff',
                        toast: true,
                        timerProgressBar: true
                    });
                });
        }

        function showBuffering() {
            Swal.fire({
                title: "Processing...",
                text: "Please wait while we verify your payment.",
                allowOutsideClick: false, // Prevent clicking outside the alert
                allowEscapeKey: false, // Disable escape key
                didOpen: () => {
                    Swal.showLoading(); // Display a spinner or loader
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
                    // showBuffering();
                    Swal.close(); // Close the buffering alert after payment verification
                    if (response.success) {
                        showSuccessAlert();
                    } else {
                        swal({
                            title: "Failed!",
                            text: response.message,
                            icon: "error",
                            timer: 2000,
                            button: false,
                            className: "red-bg"
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close(); // Close the buffering alert in case of an error
                    console.error("Verification error:", error);
                    swal("Failed!", "An unexpected error occurred while verifying the payment.", "error");
                }
            });
        }

        function showSuccessAlert() {
            Swal.fire({
                title: "Enrollment Successful!",
                text: "Payment Verified and Enrollment Successful!",
                icon: "success",
                showConfirmButton: true,
                confirmButtonText: "OK",
            });
        }

        function showErrorAlert(title, message) {
            swal({
                title: title,
                text: message,
                icon: "error",
                button: "OK",
            });
        }

        // Add flip card functionality
        function flipCard(button) {
            const card = button.closest('.course-card').querySelector('.card-inner');
            card.style.transform = card.style.transform === 'rotateY(180deg)' ? 'rotateY(0deg)' : 'rotateY(180deg)';
        }

        // Add event listeners for flip buttons
        document.addEventListener('DOMContentLoaded', function() {
            const flipButtons = document.querySelectorAll('.flip-button');
            flipButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.stopPropagation(); // Prevent event bubbling
                });
            });
        });
    </script>
    <?php include 'footer.php'; ?>