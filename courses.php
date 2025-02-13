<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config.php';
require_once 'functions.php';
ob_start();
include 'header.php';

// Fetch all courses from the database
$query = "SELECT c.id, c.title, c.description, c.course_prize, t.full_name AS tutor_name, c.duration 
          FROM courses c
          LEFT JOIN tutors t ON c.tutor_id = t.id
          ORDER BY c.created_at DESC";

$result = mysqli_query($conn, $query);
$razorpayKey = 'rzp_test_Bvq9kiuaq8gkcs';

// Rest of your PHP logic remains the same...
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        cursor: pointer;
    }

    .card:hover {
        transform: scale(1.02);
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    @media (min-width: 1536px) { 
        .container {
             max-width: 1300px !important;
              }
               }
</style>

<body class="bg-gray-100">

    <div class="container mx-auto px-4 py-8">
        <!-- Search Bar -->
        <div class="max-w-xl mx-auto mb-8">
            <div class="relative">
                <input
                    type="text"
                    id="searchInput"
                    placeholder="Search courses..."
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none shadow-sm text-base" style="
    margin-top: 34px;
">
                <button class="absolute right-3 top-1/2 -translate-y-1/2">
                    <i class="fas fa-search text-gray-400 text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Title Section -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-900">Featured Courses</h1>
            <p class="text-gray-600 mt-2">Expand your knowledge with our expertly crafted courses</p>
        </div>

        <!-- Course Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($course = mysqli_fetch_assoc($result)): ?>
                    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden card">

                        <!-- Clickable Image -->
                        <a href="course.php?id=<?php echo $course['id']; ?>" class="relative block">
                            <img
                                src="assets/images/<?php echo htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8'); ?>.jpg"
                                alt="<?php echo htmlspecialchars($course['title']); ?>"
                                class="w-full h-40 object-cover">
                            <div class="absolute top-3 right-3 bg-white px-2 py-1 rounded-full text-xs font-medium text-gray-700 shadow-sm">
                                <i class="fas fa-clock mr-1 text-blue-500"></i>
                                <?php echo isset($course['duration']) ? htmlspecialchars($course['duration']) : 'Not Available'; ?>
                            </div>
                        </a>

                        <div class="p-4">
                            <!-- Clickable Course Title -->
                            <h3 class="text-lg font-semibold text-gray-900 mb-1 hover:text-blue-600 transition-colors">
                                <a href="course.php?id=<?php echo $course['id']; ?>">
                                    <?php echo htmlspecialchars($course['title']); ?>
                                </a>
                            </h3>

                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                <?php echo htmlspecialchars($course['description']); ?>
                            </p>

                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-user text-blue-500 text-sm"></i>
                                    </div>
                                    <span class="text-xs text-gray-700"><?php echo htmlspecialchars($course['tutor_name']); ?></span>
                                </div>
                                <span class="text-lg font-bold text-blue-600">
                                    ₹<?php echo number_format((float)$course['course_prize'], 2); ?>
                                </span>
                            </div>

                            <?php if (is_logged_in()): ?>
                                <div class="flex gap-2">
                                    <button
                                        onclick="enrollCourse(<?php echo $course['id']; ?>, <?php echo $_SESSION['user_id']; ?>)"
                                        class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                        Enroll Now
                                    </button>
                                    <button
                                        onclick="addToCart(<?php echo $course['id']; ?>)"
                                        class="p-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                                        <i class="fas fa-shopping-cart text-sm"></i>
                                    </button>
                                </div>
                            <?php else: ?>
                                <a href="login.php" class="block w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium text-center">
                                    Login to Enroll
                                </a>
                            <?php endif; ?>
                        </div>

                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-6">
                    <p class="text-gray-500">No courses available at the moment.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <!-- Your existing JavaScript code remains the same -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const cards = document.querySelectorAll(".card");

            cards.forEach((card) => {
                card.addEventListener("mouseenter", () => {
                    card.style.transition = "transform 0.3s ease-out";
                    card.style.transform = "scale(1.05)";
                });

                card.addEventListener("mouseleave", () => {
                    card.style.transform = "scale(1)";
                });
            });
        });
        var username = '<?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') : ''; ?>';
        var razorpayKey = '<?php echo $razorpayKey; ?>';

        function performSearch() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const courses = document.querySelectorAll('.card'); // Use the correct class selector
        let found = false;

        courses.forEach(course => {
            const title = course.querySelector('h3').textContent.toLowerCase();
            const description = course.querySelector('p').textContent.toLowerCase();

            if (title.includes(query) || description.includes(query)) {
                course.style.display = 'block';
                found = true;
            } else {
                course.style.display = 'none';
            }
        });

        if (!found && query !== '') {
            Swal.fire({
                title: 'No Results',
                text: 'No courses match your search.',
                icon: 'info',
                confirmButtonText: 'OK'
            });
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
                    if (response && response.success && response.orders && Array.isArray(response.orders)) {
                        var options = {
                            key: razorpayKey,
                            amount: response.total_price * 100,
                            currency: 'INR',
                            name: 'Course Enrollment',
                            description: 'Enroll in ' + response.title,
                            image: 'assets/images/logo2.png',
                            order_id: response.orders[0].order_id,
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
                        Swal.fire("Order Creation Failed!", response.message, "error");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Order Creation AJAX error:", status, error);
                    Swal.fire("Enrollment Failed!", "An unexpected error occurred. Please try again.", "error");
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
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Course added to cart successfully!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000,
                            toast: true,
                            position: 'top-end',
                            timerProgressBar: true
                        }).then(() => {
                            window.location.href = 'fetch_cart.php';
                        });
                    } else if (data.message === 'Course already in cart') {
                        Swal.fire({
                            title: 'Already in Cart',
                            text: 'You have already added this course to your cart.',
                            icon: 'info',
                            showConfirmButton: false,
                            timer: 3000,
                            toast: true,
                            position: 'top-end',
                            timerProgressBar: true
                        }).then(() => {
                            window.location.href = 'fetch_cart.php';
                        });
                    } else if (data.message === 'Course already enrolled') {
                        Swal.fire({
                            title: 'Already Enrolled!',
                            text: 'You are already enrolled in this course.',
                            icon: 'info',
                            showConfirmButton: false,
                            timer: 3000,
                            toast: true,
                            position: 'top-end',
                            timerProgressBar: true
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to add course to cart: ' + data.message,
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 3000,
                            toast: true,
                            position: 'top-end',
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
                        showConfirmButton: false,
                        timer: 3000,
                        toast: true,
                        position: 'top-end',
                        timerProgressBar: true
                    });
                });
        }

        function showBuffering() {
            Swal.fire({
                title: "Processing...",
                text: "Please wait while we verify your payment.",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        function verifyPayment(paymentResponse, orderResponse, courseId, userId) {
            const order_id = orderResponse.orders[0]?.order_id;
            if (!order_id || !paymentResponse.razorpay_payment_id || !paymentResponse.razorpay_signature) {
                console.error("Missing required payment details");
                Swal.fire({
                    icon: 'error',
                    title: "Payment Error",
                    text: "Required payment details are missing!"
                });
                return;
            }
            $.ajax({
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
                    tutor_id: orderResponse.tutor_id
                },
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    if (response.success) {
                        Swal.fire({
                            title: "Enrollment Successful!",
                            text: "Payment Verified and Enrollment Successful!",
                            icon: "success",
                            confirmButtonText: "OK",
                        });
                    } else {
                        Swal.fire({
                            title: "Failed!",
                            text: response.message,
                            icon: "error",
                            confirmButtonText: "OK",
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    console.error("Verification error:", error);
                    Swal.fire({
                        title: "Failed!",
                        text: "An unexpected error occurred while verifying the payment.",
                        icon: "error",
                        confirmButtonText: "OK",
                    });
                }
            });
        }
    </script>
</body>

</html>

<?php include 'footer.php'; ?>