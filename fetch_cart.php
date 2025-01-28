<?php
session_start();
header('Content-Type: text/html');

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    echo "<h1>You must be logged in to view the cart.</h1>";
    exit;
}

include 'config.php'; // Replace with your actual DB connection file

$userId = $_SESSION['user_id'];
$totalPrice = 0; // Initialize the variable to avoid undefined errors

// Check if delete_course is set
if (isset($_POST['delete_course']) && isset($_POST['course_id'])) {
    
    $courseId = intval($_POST['course_id']);

    // Prepare the delete query
    $deleteQuery = "DELETE FROM cart WHERE user_id = ? AND course_id = ?";
    $stmt = $conn->prepare($deleteQuery);

    if ($stmt) {
        $stmt->bind_param('ii', $userId, $courseId);
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Course removed from your cart successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to remove course from the cart."]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Failed to prepare delete statement: " . $conn->error]);
        exit;
    }
}

// Fetch cart items and calculate total price
$query = "SELECT c.id, c.user_id, u.username, c.course_id, c.added_at, courses.title, courses.course_prize
          FROM cart c
          JOIN users u ON c.user_id = u.id
          JOIN courses ON c.course_id = courses.id
          WHERE c.user_id = ?";

$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Failed to prepare select statement: " . $conn->error]);
    exit;
}

$razorpayKey = 'rzp_test_Bvq9kiuaq8gkcs';
$stmt->bind_param('i', $userId); // Correct binding for user_id only
$stmt->execute();
$result = $stmt->get_result();

$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
    $totalPrice += $row['course_prize']; // Sum the prices
}

$stmt->close();
$conn->close();
echo json_encode(["success" => true, "courses" => $courses, "totalPrice" => $totalPrice]);  // Proper JSON response


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
                        <p>Added by: <span><?php echo htmlspecialchars($course['username']); ?></span></p>
                        <p>Price: ₹<?php echo htmlspecialchars($course['course_prize']); ?></p>
                        <p class="cart-timestamp">Added on: <?php echo htmlspecialchars($course['added_at']); ?></p>
                    </div>
                    <form action="fetch_cart.php" method="POST" style="display: inline;">
                        <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($course['course_id']); ?>">
                        <button type="submit" class="btn btn-danger btn-sm" name="delete_course">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
            <h2>Total Price: ₹<?php echo $totalPrice; ?></h2>
            <button onclick="enrollCourse(<?php echo $totalPrice * 100; ?>, <?php echo $userId; ?>)">Enroll and Pay</button>
        <?php else: ?>
            <h2>Your cart is empty!</h2>
        <?php endif; ?>
    </div>

    <script>
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
            console.log("RAW response:", response); // Log the raw response for debugging
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
            console.error("Response:", xhr.responseText); // Log the error response
            showErrorAlert("Enrollment Failed!", "An unexpected error occurred. Please try again.");
        }
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
            Swal.close(); // Close the buffering alert after payment verification
            if (response.success) {
                showSuccessAlert();
            } else {
                Swal.fire({
                    title: "Failed to enroll.",
                    text: "Payment could not be verified. Please try again.",
                    icon: "error",
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.close();
            showErrorAlert("Payment Verification Failed!", "There was an issue verifying your payment. Please try again.");
        }
    });
}

function showSuccessAlert() {
    Swal.fire({
        title: "Success!",
        text: "Your payment has been successfully processed.",
        icon: "success",
        confirmButtonText: "OK"
    });
}

function showErrorAlert(title, message) {
    Swal.fire({
        title: title,
        text: message,
        icon: "error",
        confirmButtonText: "OK"
    });
}

    </script>
</body>

</html>
