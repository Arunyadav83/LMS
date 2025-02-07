<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'config.php';

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
function generateOrderId()
{
    // Your logic to generate an order ID (e.g., using a random number or database)
    return uniqid('order_');
}

// Fetch cart items
$query = "SELECT c.id, c.course_id, courses.title, courses.course_prize, c.added_at , u.username
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        .container {
            width: 90%;
            margin: 20px auto;
        }

        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn i {
            margin-right: 5px;
        }

        h1 {
            text-align: center;
            margin: 20px 0;
            color: #444;
        }

        .cart-item {
            display: flex;
            width: 600px;
            height: auto;
            margin-left: 485px;
            margin-top: 23px;
            align-items: center;
            background: #fff;
            margin-bottom: 15px;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .cart-item img {
            width: 300px;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
            margin-right: 15px;
        }

        .cart-details {
            flex: 1;
        }

        .cart-details h2 {
            margin: 0;
            font-size: 20px;
            color: #555;
        }

        .cart-details p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
            display: flex;
        }

        .cart-details span {
            font-weight: bold;
            color: #333;
        }

        .cart-timestamp {
            font-size: 12px;
            color: #aaa;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Your Cart</h1>
        <?php if (count($courses) > 0): ?>
            <?php foreach ($courses as $course): ?>
                <div class="cart-item">

                    <img src="assets/images/<?php echo htmlspecialchars($course['title']); ?>.jpg" alt="<?php echo htmlspecialchars($course['title']); ?>">
                    <div class="cart-details">
                        <h2><?php echo htmlspecialchars($course['title']); ?></h2>
                        <p>Price: ₹<?php echo htmlspecialchars($course['course_prize']); ?></p>
                        <form action="" method="POST" style="display: inline;">
                            <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($course['course_id']); ?>">
                            <button type="submit" name="delete_course">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
            <h2>Total Price: ₹<?php echo $totalPrice; ?></h2>
            <button onclick="enrollCourse(<?php echo $totalPrice * 100; ?>, <?php echo $userId; ?>)">Enroll Now </button>

        <?php else: ?>
            <h2>Your cart is empty!</h2>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
<<<<<<< HEAD

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

=======
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
                course_ids: JSON.stringify(courseIds) // Send as JSON array
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
                        amount: amount * 100, // Convert to paise
                        currency: 'INR',
                        name: 'Course Enrollment',
                        description: 'Payment for selected courses',
                        image: 'assets/images/logo2.png',
                        order_id: response.orders[0].order_id, // Use the first order ID
                        handler: function(paymentResponse) {
                            console.log("Payment Response:", paymentResponse);
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

>>>>>>> e6d9cf326cc849502200bc0c07af71e6633905c8
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
</body>

</html>