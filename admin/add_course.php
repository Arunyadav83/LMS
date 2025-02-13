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
    $duration = mysqli_real_escape_string($conn, $_POST['duration']);

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
    $insert_query = "INSERT INTO courses (title, description, course_prize, topics, image_path,duration) VALUES ('$title', '$description', $course_prize, '$topics', '$image_path','$duration')";

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
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.tiny.cloud/1/azj9n0neceenohuu03tmpx6oq579m7sfow413lvfsebb2293/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

  <script>
    tinymce.init({
      selector: '#modal_description',
      plugins: 'lists link image table',
      toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image',
      menubar: false,
      setup: function (editor) {
        editor.on('change', function () {
          editor.save();
        });
      }
    });

    <?php if (isset($_SESSION['course_added']) && $_SESSION['course_added'] === true): ?>
      document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
          title: 'Course Added Successfully!',
          text: 'The new course has been added.',
          icon: 'success',
          confirmButtonText: 'OK'
        }).then(() => {
          location.href = "courses.php";
        });
      });
      <?php unset($_SESSION['course_added']); ?>
    <?php endif; ?>
  </script>
</head>

<body class="bg-gray-100 min-h-screen">
  <?php include 'sidebar.php'; ?>

  <div class="flex justify-center items-center min-h-screen">
    <div class="bg-white shadow-md rounded-lg w-full max-w-3xl p-8">
      <h1 class="text-3xl font-bold text-center mb-6">Add New Course</h1>
      <form action="" method="post" enctype="multipart/form-data" class="grid grid-cols-1 gap-6">
        
        <!-- Title -->
        <div>
          <label for="modal_title" class="block text-gray-700 font-medium mb-2">Course Title</label>
          <input type="text" id="modal_title" name="title" class="w-full border border-gray-300 p-3 rounded-lg" placeholder="Enter course title" required>
        </div>

        <!-- Course Image -->
        <div>
          <label for="modal_course_image" class="block text-gray-700 font-medium mb-2">Course Image</label>
          <input type="file" id="modal_course_image" name="course_image" class="w-full border border-gray-300 p-3 rounded-lg" accept=".jpg,.jpeg,.png,.gif" required>
        </div>

        <!-- Description -->
        <div>
          <label for="modal_description" class="block text-gray-700 font-medium mb-2">Description</label>
          <textarea id="modal_description" name="description" class="w-full border border-gray-300 p-3 rounded-lg" rows="3" placeholder="Write a detailed description" required></textarea>
        </div>

        <!-- Topics Covered -->
        <div>
          <label for="modal_topics" class="block text-gray-700 font-medium mb-2">Topics Covered</label>
          <input type="text" id="modal_topics" name="topics" class="w-full border border-gray-300 p-3 rounded-lg" placeholder="Enter course topics" required>
        </div>

        <!-- Course Price -->
        <div>
          <label for="modal_course_price" class="block text-gray-700 font-medium mb-2">Course Price</label>
          <input type="number" id="modal_course_price" name="course_price" class="w-full border border-gray-300 p-3 rounded-lg" placeholder="Enter price (e.g., 99.99)" required>
        </div>

        <!-- Duration -->
        <div>
          <label for="modal_duration" class="block text-gray-700 font-medium mb-2">Duration (in hours)</label>
          <input type="text" id="modal_duration" name="duration" class="w-full border border-gray-300 p-3 rounded-lg" placeholder="Enter course duration" required>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-center">
          <button type="submit" name="add_course" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-all duration-300">Add Course</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>

