<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'config.php';
include 'header.php';

$razorpayKey = 'rzp_test_Bvq9kiuaq8gkcs';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "User is not logged in"]);
    exit;
}

$userId = intval($_SESSION['user_id']);

// Handle course deletion from cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_course'])) {
    if (empty($_POST['course_id'])) {
        echo json_encode(["success" => false, "message" => "Missing course ID"]);
        exit;
    }
    $courseId = intval($_POST['course_id']);

    $deleteQuery = "DELETE FROM cart WHERE user_id = ? AND course_id = ?";
    $stmt = $conn->prepare($deleteQuery);

    if ($stmt) {
        $stmt->bind_param('ii', $userId, $courseId);
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Course removed from cart"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to remove course"]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Failed to prepare delete statement: " . $conn->error]);
    }
    exit;
}

// Fetch cart items
$query = "SELECT c.id, c.course_id, courses.title, courses.course_prize, c.added_at, u.username
          FROM cart c 
          JOIN courses ON c.course_id = courses.id 
          JOIN users u ON c.user_id = u.id
          WHERE c.user_id = ?";

$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Failed to prepare select statement: " . $conn->error]);
    exit;
}

$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

$courses = [];
$totalPrice = 0;

while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
    $totalPrice += $row['course_prize'];
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .cart-item {
            transition: transform 0.2s ease-in-out;
        }

        .cart-item:hover {
            transform: translateY(-2px);
        }

        .remove-btn {
            transition: all 0.2s ease;
        }

        .remove-btn:hover {
            background-color: #FEE2E2;
        }

        .enroll-btn {
            transition: all 0.3s ease;
        }

        .enroll-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .cart-table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .cart-table th {
            background: linear-gradient(to right, #2563eb, #3b82f6);
            color: white;
            font-weight: 600;
        }

        .cart-row {
            transition: all 0.3s ease;
        }

        .cart-row:hover {
            background-color: #f8fafc;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .course-image {
            transition: transform 0.3s ease;
        }

        .cart-row:hover .course-image {
            transform: scale(1.05);
        }

        /* .remove-btn {
            transition: all 0.2s ease;
            opacity: 0;
        } */

        /* .cart-row:hover .remove-btn {
            opacity: 1;
        } */

        .enroll-btn {
            transition: all 0.3s ease;
        }

        .enroll-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        @media (max-width: 768px) {
            .cart-table thead {
                display: none;
            }

            .cart-table,
            .cart-table tbody,
            .cart-table tr,
            .cart-table td {
                display: block;
                width: 100%;
            }

            .cart-table tr {
                margin-bottom: 1rem;
                border: 1px solid #e5e7eb;
                border-radius: 0.5rem;
                padding: 1rem;
            }

            .cart-table td {
                text-align: right;
                padding: 0.5rem 0;
                position: relative;
            }

            .cart-table td::before {
                content: attr(data-label);
                float: left;
                font-weight: 600;
                color: #4b5563;
            }

            .remove-btn {
                opacity: 1;
                width: 100%;
                justify-content: center;
            }
        }
    </style>
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="flex items-center justify-center gap-3 mb-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin-top: 57px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h1 class="text-4xl font-bold text-gray-900" style="margin-top: 55px;">Your Cart</h1>
        </div>

        <div class="bg-white/90 backdrop-blur-sm rounded-xl shadow-xl border border-gray-100 overflow-hidden">
            <?php if (count($courses) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="cart-table w-full">
                        <thead>
                            <tr>
                                <th class="px-6 py-4 text-left">Course</th>
                                <th class="px-6 py-4 text-left">Title</th>
                                <th class="px-6 py-4 text-left">Added Date</th>
                                <th class="px-6 py-4 text-right">Price</th>
                                <th class="px-6 py-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($courses as $course): ?>
                                <tr class="cart-row">
                                    <td class="px-6 py-4" data-label="Course">
                                        <div class="w-24 h-16 rounded-lg overflow-hidden bg-gray-100">
                                            <img
                                                src="assets/images/<?php echo htmlspecialchars($course['title']); ?>.jpg"
                                                alt="<?php echo htmlspecialchars($course['title']); ?>"
                                                class="course-image w-full h-full object-cover">
                                        </div>
                                    </td>
                                    <td class="px-6 py-4" data-label="Title">
                                        <h2 class="font-semibold text-gray-900">
                                            <?php echo htmlspecialchars($course['title']); ?>
                                        </h2>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600" data-label="Added Date">
                                        <?php echo date('M d, Y', strtotime($course['added_at'])); ?>
                                    </td>
                                    <td class="px-6 py-4 text-right font-medium text-blue-600" data-label="Price">
                                        ₹<?php echo number_format($course['course_prize'], 2); ?>
                                    </td>
                                    <td class="px-6 py-4" data-label="Action">
                                        <form action="" method="POST" class="flex justify-center">
                                            <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($course['course_id']); ?>">
                                            <button
                                                type="submit"
                                                name="delete_course"
                                                class="remove-btn flex items-center gap-2 px-4 py-2 rounded-lg text-red-600 hover:bg-red-50">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>

                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="p-6 bg-gradient-to-r from-blue-50 to-gray-50 md:flex md:justify-between md:items-center">
                    <div class="text-2xl font-bold text-gray-900 mb-4 md:mb-0">
                        Total Price: <span class="text-blue-600">₹<?php echo number_format($totalPrice, 2); ?></span>
                    </div>
                    <button
                        onclick="enrollCourse(<?php echo $totalPrice * 100; ?>, <?php echo $userId; ?>)"
                        class="enroll-btn bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg py-2 px-4 font-medium hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 w-full md:w-auto shadow-lg hover:shadow-xl transition duration-300 ease-in-out transform hover:-translate-y-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Check Out
                    </button>
                </div>
            <?php else: ?>
                <div class="p-12 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-2">Your cart is empty</h2>
                    <p class="text-gray-600 mb-6">Add courses to your cart to get started.</p>
                    <a
                        href="courses.php"
                        class="inline-flex items-center justify-center px-8 py-3 rounded-full text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 font-medium transition-all duration-300 shadow-lg hover:shadow-xl">
                        Browse Courses
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
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

        function enrollCourse(amount, userId) {
            if (amount <= 0) {
                Swal.fire("Error!", "Invalid amount for payment.", "error");
                return;
            }

            let courseIds = [];
            document.querySelectorAll('input[name="course_id"]').forEach(input => {
                courseIds.push(input.value);
            });

            if (courseIds.length === 0) {
                Swal.fire("Error!", "No courses selected.", "error");
                return;
            }

            console.log("Sending data:", {
                amount: amount,
                user_id: userId,
                course_ids: courseIds
            });

            $.ajax({
                type: 'POST',
                url: 'create_order.php',
                data: {
                    amount: amount,
                    user_id: userId,
                    course_ids: JSON.stringify(courseIds), // Send as JSON array
                    total_price: amount
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
                            key: razorpayKey,
                            amount: amount, // Convert to paise
                            currency: 'INR',
                            name: 'Course Enrollment',
                            description: 'Payment for selected courses',
                            image: 'assets/images/logo2.png',
                            order_id: response.orders[0].order_id, // Use the first order ID
                            handler: function(paymentResponse) {
                                console.log("Payment Response:", paymentResponse);
                                showBuffering();
                                verifyPayment(paymentResponse, response, courseIds, userId);
                            },
                            theme: {
                                color: '#F37254'
                            }
                        };

                        var rzp1 = new Razorpay(options);
                        rzp1.open();
                    } else {
                        Swal.fire("Order Creation Failed!", response.message || "Unknown error occurred", "error");
                    }
                },
                error: function(xhr) {
                    console.error("Order Creation Error:", xhr.responseText);
                    Swal.fire("Enrollment Failed!", "An unexpected error occurred.", "error");
                }
            });
        }

        function verifyPayment(paymentResponse, orderResponse, courseIds, userId) {
            const order_id = orderResponse.orders[0]?.order_id;

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
                    course_ids: JSON.stringify(courseIds),
                    user_id: userId
                },
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    console.log("Verification Response:", response);
                    if (response.success) {
                        showSuccessAlert();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: "Failed!",
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    Swal.close();
                    console.error("Verification Error:", xhr);
                    Swal.fire({
                        icon: 'error',
                        title: "Error!",
                        text: "An error occurred while verifying payment."
                    });
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

        function showErrorAlert(message) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message,
            });
        }
    </script>
    <style>
        @media (max-width: 768px) {
            .text-2xl {
                font-size: 1.5rem;
            }
            .enroll-btn {
                width: 100%;
            }
        }
        @media (min-width: 768px) {
            .text-2xl {
                font-size: 2rem;
            }
        }
        @media (min-width: 1536px) { 
        .container {
             max-width: 1300px !important;
              }
               }
    </style>
</body>



</html>

<?php include 'footer.php'; ?>