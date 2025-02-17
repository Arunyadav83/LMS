// apply_coupon.php (Backend Logic for Applying Coupon)
<?php
session_start();
include 'config.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $couponCode = trim($_POST['coupon_code']);
    $courseId = intval($_POST['course_id']);
    
    // Check if coupon exists in the database
    $query = "SELECT * FROM coupons WHERE code = '$couponCode' AND status = 'active'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $coupon = mysqli_fetch_assoc($result);
        $discount = (float)$coupon['discount'];
        
        // Fetch course price
        $courseQuery = "SELECT course_prize FROM courses WHERE id = $courseId";
        $courseResult = mysqli_query($conn, $courseQuery);
        $course = mysqli_fetch_assoc($courseResult);
        $originalPrice = (float)$course['course_prize'];
        
        // Calculate new price
        $newPrice = $originalPrice - ($originalPrice * ($discount / 100));
        
        echo json_encode(['success' => true, 'discount' => $discount, 'new_price' => $newPrice]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid or expired coupon code']);
    }
}
?>

<!-- Frontend Code (Coupon Input and Apply Button) -->
<div class="mt-4">
    <input type="text" id="couponCode" placeholder="Enter Coupon Code" class="w-full px-3 py-2 border rounded">
    <button onclick="applyCoupon(<?php echo $course['id']; ?>)" class="bg-green-500 text-white px-4 py-2 rounded mt-2">Apply Coupon</button>
</div>

<!-- JavaScript Code for Applying Coupon -->
<script>
function applyCoupon(courseId) {
    let couponCode = document.getElementById("couponCode").value;
    
    fetch('apply_coupon.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `coupon_code=${couponCode}&course_id=${courseId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Discount Applied!',
                text: `You got a ${data.discount}% discount! New price: ₹${data.new_price.toFixed(2)}`,
                icon: 'success'
            });
        } else {
            Swal.fire({
                title: 'Invalid Coupon',
                text: data.message,
                icon: 'error'
            });
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
