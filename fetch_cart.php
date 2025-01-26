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

// Check if delete_course is set
if (isset($_POST['delete_course']) && isset($_POST['course_id'])) {
    $courseId = intval($_POST['course_id']);

    // Prepare the delete query
    $deleteQuery = "DELETE FROM cart WHERE user_id = ? AND course_id = ?";

    $stmt = $conn->prepare($deleteQuery);
    if (!$stmt) {
        die("<h1>Failed to prepare delete statement: " . $conn->error . "</h1>");
    }

    // Bind parameters and execute the delete query
    $stmt->bind_param('ii', $userId, $courseId);
    if ($stmt->execute()) {
        echo "<h1>Course removed from your cart successfully.</h1>";
    } else {
        echo "<h1>Failed to remove course from the cart.</h1>";
    }

    $stmt->close();
}

// Select query to fetch cart items
$query = "SELECT c.id, c.user_id, u.username, c.course_id, c.added_at, courses.title
FROM cart c
JOIN users u ON c.user_id = u.id
JOIN courses ON c.course_id = courses.id
WHERE c.user_id = ?;";

$stmt = $conn->prepare($query);
if (!$stmt) {
    die("<h1>Failed to prepare select statement: " . $conn->error . "</h1>");
}

$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
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
                    <img
                        src="assets/images/<?php echo htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8'); ?>.jpg"
                        class="card-img-top img-fluid"
                        style="max-height: 150px; object-fit: cover;"
                        alt="<?php echo htmlspecialchars($course['title']); ?>">
                    <div class="cart-details">
                        <h2>Course ID: <?php echo htmlspecialchars($course['course_id']); ?></h2>
                        <p>Added by: <span><?php echo htmlspecialchars($course['username']); ?></span></p>
                        <p class="cart-timestamp">Added on: <?php echo htmlspecialchars($course['added_at']); ?></p>
                    </div>
                    <!-- Trash Icon to Remove Item -->
                    <form action="fetch_cart.php" method="POST" style="display: inline;">
                        <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($course['course_id']); ?>">
                        <button type="submit" class="btn btn-danger btn-sm" title="Remove from Cart" name="delete_course">
                            <i class="fa fa-trash"></i> <!-- Trash Icon -->
                        </button>

                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <h2 style="text-align: center;">Your cart is empty!</h2>
        <?php endif; ?>
    </div>

</body>

</html>