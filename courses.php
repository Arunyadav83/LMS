<?php
require_once 'config.php';
require_once 'functions.php';
include 'header.php';

// Fetch all courses from the database
$query = "SELECT c.id, c.title, c.description, t.full_name AS tutor_name 
          FROM courses c
          LEFT JOIN tutors t ON c.tutor_id = t.id
          ORDER BY c.created_at DESC";
$result = mysqli_query($conn, $query);
?>

<div class="container mt-2">
    <h1 class="mb-2 text-center text-md-left">All Courses</h1>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="row">
            <?php while ($course = mysqli_fetch_assoc($result)): ?>
                <div class="col-12 col-sm-6 col-md-3 mb-3">
                    <div class="card h-75">
                        <img src="assets/images/-<?php echo $course['id']; ?>.jpg" class="card-img-top img-fluid" style="max-height: 150px; object-fit: cover;" alt="<?php echo htmlspecialchars($course['title']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                            <p class="card-text" style="max-height: 50px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo htmlspecialchars($course['description']); ?></p>
                            <p class="card-text"><small class="text-muted">Tutor: <?php echo htmlspecialchars($course['tutor_name']); ?></small></p>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <?php if (is_logged_in()): ?>
                                <a href="course.php?id=<?php echo $course['id']; ?>" class="btn btn-primary btn-sm">View Course</a>
                                <a href="enroll.php?course_id=<?php echo $course['id']; ?>" class="btn btn-success btn-sm">Enroll</a>
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

<?php include 'footer.php'; ?> 

<!-- Include SweetAlert CSS and JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

<script>
function showSuccessAlert() {
    swal("Enroll successful!", "You have successfully enrolled in the course.", "success");
}
</script> 

<script>
$(document).ready(function() {
    $('.enroll-form').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        var form = $(this);
        $.ajax({
            type: 'POST',
            url: 'enroll.php', // URL to the PHP file that processes the enrollment
            data: form.serialize(), // Serialize form data
            success: function(response) {
                // Assuming the response contains a success message
                if (response.success) {
                    // Redirect to enroll.php with course_id
                    var courseId = form.find('input[name="course_id"]').val();
                    window.location.href = 'enroll.php?course_id=' + courseId;
                } else {
                    swal("Enrollment failed!", response.message, "error");
                }
            },
            error: function() {
                // Show error alert
                swal("Enrollment failed!", "Please try again.", "error");
            }
        });
    });
});
</script> 
