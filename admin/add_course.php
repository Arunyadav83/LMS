<?php
session_start();
require_once '../config.php';
require_once '../functions.php';

// Check if the user is logged in as an admin
if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

// Handle course addition
if (isset($_POST['add_course'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = strip_tags(mysqli_real_escape_string($conn, $_POST['description']));
    $topics = mysqli_real_escape_string($conn, $_POST['topics']);
    $course_prize = (float)$_POST['course_price'];

    // Handle image upload
    $image_path = '';
    if (isset($_FILES['course_image']) && $_FILES['course_image']['error'] === 0) {
        $image_name = basename($_FILES['course_image']['name']);
        $image_name = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $image_name); // Sanitize filename

        // Set absolute path for storing the image
        $target_dir = realpath(__DIR__ . '/../assets/images') . '/'; // Ensure directory is absolute
        $target_file = $target_dir . $image_name;

        // Validate file type
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (in_array($file_type, $allowed_types)) {
            // Ensure directory exists
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true); // Create directory with write permissions
            }

            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES['course_image']['tmp_name'], $target_file)) {
                // Use relative path for database storage
                $image_path = "assets/images/" . $image_name;
            } else {
                echo "<div class='alert alert-danger'>Error: Could not save the image file. Check directory permissions.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Invalid file type. Please upload a JPG, JPEG, PNG, or GIF file.</div>";
        }
    } elseif (isset($_FILES['course_image']['error']) && $_FILES['course_image']['error'] !== 0) {
        echo "<div class='alert alert-danger'>Error: File upload error code " . $_FILES['course_image']['error'] . "</div>";
    }

    // Insert the new course into the database
    $insert_query = "INSERT INTO courses (title, description, course_prize, topics, image_path) VALUES ('$title', '$description', $course_prize, '$topics', '$image_path')";

    if (mysqli_query($conn, $insert_query)) {
        // Set session variable for successful addition
        $_SESSION['course_added'] = true;
        echo "<div class='alert alert-success'>Course added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error adding course: " . mysqli_error($conn) . "</div>";
    }
}
?>

 


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tiny.cloud/1/azj9n0neceenohuu03tmpx6oq579m7sfow413lvfsebb2293/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#modal_description',
            plugins: 'lists link image table',
            toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image',
            menubar: false,
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save(); // Ensure the content is saved to the textarea
                });
            }
        });

        // Display SweetAlert if course added
        <?php if (isset($_SESSION['course_added']) && $_SESSION['course_added'] === true): ?>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Course Added Successfully!',
                    text: 'The new course has been added.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Redirect after SweetAlert is closed with a 3-second delay
                    location.href = "courses.php";
                     // 3 seconds delay
                });
            });
            <?php unset($_SESSION['course_added']); ?>
        <?php endif; ?>
    </script>
    <style>
        
    .navbar {
        background-color: #1a237e;
        margin: 0;
        padding: 24px 5px;
        /* Adjust padding for comfortable spacing */
        line-height: 1.2;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        /* Adds a subtle shadow */
        /* position: fixed; */
        /* Makes the navbar fixed */
        top: 0;
        /* Sticks to the top of the viewport */
        left: 0;
        width: 100%;
        position: sticky;
        /* Ensures the navbar spans the full width */
        z-index: 1000;
        /* Keeps the navbar above other elements */
    }
    h1{
        color: #1a237e;
        text-align: center;
        margin: 34px;
    }
    .container{
        width: 100%;
        height: auto;
        margin-top: 23%;
        margin-bottom: 15%;
        padding: 54px;
        background-color:rgb(148, 156, 248);
    }
    .button {
        padding-inline: 30px;
        font-weight: bolder;
        text-decoration: none;
        color: #0433c3;
        padding-block: 10px;
        border-radius: 30px;
        transition: all 0.3s ease;
    }

    .button:hover {
        color: white;
        background-color: #0433c3;
        border-radius: 30px;
    }

    </style>
</head>
<link rel="icon" type="image/x-icon" href="assets/images/apple-touch-icon.png">
<body>

<nav class="navbar navbar-expand-lg custom-navbar">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="container-fluid">
            <a class="navbar-brand text-light fw-bold" href="index.php">LMS Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <!-- <span class="navbar-toggler-icon"></span> -->
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item">
                        <a class="nav-link text-light d-flex align-items-center" href="#">
                            <i class="fas fa-user me-2"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light d-flex align-items-center" href="logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <h1>Add New Course</h1>
    
    <div class="container mt-4">
   
        
        <form action="" method="post">
            <div class="mb-3">
                <label for="modal_title" class="form-label">Title</label>
                <input type="text" class="form-control" id="modal_title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="modal_course_image" class="form-label">Course Image</label>
                <input type="file" class="form-control" id="modal_course_image" name="course_image" accept=".jpg,.jpeg,.png,.gif" required>
            </div>

            <div class="mb-3">
                <label for="modal_description" class="form-label">Description</label>
                <textarea class="form-control" id="modal_description" name="description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="modal_topics" class="form-label">Topics Covered</label>
                <input type="text" class="form-control" id="modal_topics" name="topics" required>
            </div>
            <div class="mb-3">
                <label for="modal_course_price" class="form-label">Course Price</label>
                <input type="number" class="form-control" id="modal_course_price" name="course_price" required>
            </div>
            <button type="submit" name="add_course" class="button">Add Course</button>
        </form>
    </div>
</body>

</html>