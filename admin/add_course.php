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

    // Insert the new course into the database
    $insert_query = "INSERT INTO courses (title, description, course_prize) VALUES ('$title', '$description', $course_prize)";
    
    if (mysqli_query($conn, $insert_query)) {
        // Redirect to courses.php after successful addition
        header("Location: courses.php");
        exit();
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
    <script src="https://cdn.tiny.cloud/1/azj9n0neceenohuu03tmpx6oq579m7sfow413lvfsebb2293/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#modal_description',
            plugins: 'lists link image table',
            toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image',
            menubar: false,
            setup: function (editor) {
                editor.on('change', function () {
                    editor.save(); // Ensure the content is saved to the textarea
                });
            }
        });

        document.addEventListener('touchstart', function(e) {
            // Your code here
        }, { passive: true });
    </script>
</head>
<body>
    <div class="container mt-4">
        <h1>Add New Course</h1>
        <form action="" method="post">
            <div class="mb-3">
                <label for="modal_title" class="form-label">Title</label>
                <input type="text" class="form-control" id="modal_title" name="title" required>
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
            <button type="submit" name="add_course" class="btn btn-primary">Add Course</button>
        </form>
    </div>
</body>
</html>