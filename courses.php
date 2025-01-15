<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config.php';
require_once 'functions.php';
include 'header.php';

// Fetch all courses from the database
$query = "SELECT c.id, c.title, c.description, c.course_prize, t.full_name AS tutor_name 
          FROM courses c
          LEFT JOIN tutors t ON c.tutor_id = t.id
          ORDER BY c.created_at DESC";

$result = mysqli_query($conn, $query);

// Razorpay API key from the config
$razorpayKey = 'rzp_test_Bvq9kiuaq8gkcs'; // Your Razorpay API key
?>


<div class="search-bar-container d-flex justify-content-center align-items-center mb-3">
    <div class="input-group" style="max-width: 400px;">
        <input type="text" id="searchInput" class="form-control" placeholder="Search courses..." aria-label="Search">
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" onclick="performSearch()">
                <i class="fa fa-search"></i>
            </button>
        </div>
    </div>
    <div id="searchResults" class="dropdown-menu" style="display: none; max-height: 200px; overflow-y: hidden;"></div>
</div>
<div class="container mt-2">
<div id="searchError" class="text-danger text-center" style="display: none;">No matching courses found!</div>

    <h4 class="mb-2 text-center text-md-left" style="margin-top: -4%; ">All Courses</h4>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="row">
            <?php while ($course = mysqli_fetch_assoc($result)): ?>
                <div class="col-12 col-sm-6 col-md-3 mb-3">
                     <div class="card h-60" style="padding: 0px;!important">
                        <img
                            src="assets/images/<?php echo htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8'); ?>.jpg"
                            class="card-img-top img-fluid"
                            style="max-height: 150px; object-fit: contain;"
                            alt="<?php echo htmlspecialchars($course['title']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                            <p class="card-text" style="max-height: 50px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo htmlspecialchars($course['description']); ?></p>
                            <p class="card-text"><small class="text-muted">Tutor: <?php echo htmlspecialchars($course['tutor_name']); ?></small></p>
                            <p class="card-text"><strong>Price:</strong> ₹<?php echo number_format((float)$course['course_prize'], 2); ?></p>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <?php if (is_logged_in()): ?>
                                <a href="course.php?id=<?php echo $course['id']; ?>"
                                    class="btn btn-primary btn-sm">View Course</a>
                                <a href="javascript:void(0)"
                                    onclick="enrollCourse(<?php echo $course['id']; ?>, <?php echo $_SESSION['user_id']; ?>)"
                                    class="btn btn-success btn-sm">Enroll</a>
                            <?php else: ?>
                                <a href="login.php"
                                    class="btn btn-secondary btn-sm">Login to Enroll</a>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<!-- <script>
    function showSuccessAlert() {
        swal("Enroll successful!", "You have successfully enrolled in the course.", "success");
    }
</script> -->

<style>
    .card-footer .btn {
        margin-left: 6px;
    }

    .card-footer .btn-success {
        width: 100px;
    }
    .input-group-append {
    display: flex;
    align-items: center;
}
body, html {
    overflow-x: hidden;  /* Prevent horizontal overflow */
}



.input-group .btn {
    background-color: #007bff; /* Bootstrap primary color */
    color: #fff;
    border: 1px solid #007bff;
    border-radius: 0 4px 4px 0; /* Rounded corners for the right side */
    padding: 8px 12px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.input-group .btn:hover {
    background-color: #0056b3; /* Darker shade on hover */
}

.input-group .fa-search {
    font-size: 16px;
}

#searchInput {
    border-right: none; /* Remove right border to merge with the button */
    border-radius: 4px 0 0 4px;
    margin-right: 16px;
    margin-bottom: 1%;
    height: auto;
     /* Rounded corners for the left side */
}

.search-bar-container {
    margin-top: 120px;  /* Space above the search bar */
    position: relative;
    transform: translateX(30px);  /* Move the search bar slightly to the right without causing overflow */
    width: 100%;
    left: 430px;                    
    /* Add space above the search bar */
}


/* For screens smaller than or equal to 768px */
@media (max-width: 768px) {
    .search-bar-container {
        margin-top: 90px;
        left: 0;
        width: 100%;
        padding: 0 10px;
    }z

    .input-group {
        width: 100%; /* Make input group take full width */
    }

    .input-group-append {
        width: 100%; /* Make button take full width */
        justify-content: flex-end; /* Align button to the right */
    }

    .input-group .btn {
        width: 40px; /* Make the button smaller for mobile view */
        /* padding: 8px; */
        position: relative;
        bottom: 43px;
        right: 22px;
    }

    #searchInput {
    width: calc(100% - 50px); /* Ensure input takes the remaining space */
    margin-right: 34px;
    position: relative;
    right: 34px;
 /* Adds a margin at the top */
    border-radius: 4px 0 0 4px;
}

}


</style>
<script>
    var username = '<?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') : ''; ?>';
</script>

<script>
    function performSearch() {
    const query = document.getElementById('searchInput').value.toLowerCase();
    const courses = document.querySelectorAll('.card');
    let found = false;

    courses.forEach(course => {
        const title = course.querySelector('.card-title').textContent.toLowerCase();
        const description = course.querySelector('.card-text').textContent.toLowerCase();
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
                        image: 'assets/images/logo2.png',
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

                    showSuccessAlert();
                } else {
                    swal({
                        title: "Failed!",
                        text: response.message,
                        icon: "error",
                        timer: 3000, // Time in milliseconds (3 seconds)
                        button: false, // Disable the OK button
                        className: "red-bg"
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("Verification error:", error);
                swal("Failed!", "An unexpected error occurred while verifying the payment.", "error");
            }
        });
    }

    function showSuccessAlert() {
        Swal.fire({
            title: "Enrollment Successful!",
            text: "Payment Verified and Enrollment Successful!",
            icon: "success", // Ensures the green tick mark is shown
            // timer: 1000, // Duration of the alert in milliseconds
            showConfirmButton: true, // "OK" button
            confirmButtonText: "OK", // Button text
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