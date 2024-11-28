<?php
session_start();
require_once '../config.php';
require_once '../functions.php';

// Check if the user is logged in as an admin
if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Debugging output
    echo '<pre>';
    print_r($_POST);
    echo '</pre>';

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $topics = isset($_POST['topics']) ? $_POST['topics'] : [];
    

    // Insert new course
    $insert_query = "INSERT INTO courses (title, description) VALUES ('$title', '$description')";
    mysqli_query($conn, $insert_query);
    $course_id = mysqli_insert_id($conn);

    // Insert topics
    foreach ($topics as $topic) {
        $topic = mysqli_real_escape_string($conn, $topic);
        $insert_topic_query = "INSERT INTO course_topics (course_id, topic_name) VALUES ($course_id, '$topic')";
        mysqli_query($conn, $insert_topic_query);
    }

    header("Location: courses_list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Course - LMS Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

       #a {
            color:  #00CED1 ; /* Change the color of the heading */
            font-size: 2.5rem; /* Increase font size */
            margin-bottom: 20px; /* Add some space below the heading */
        }
         .label{
           color:#00CED1;
           font-size:20px;
            
         }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 id="a">Add New Course</h1>
        <form action="" method="post">
            <div class="mb-3">
                <label for="title" class="form-label label">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="topics" class="form-label label">Topics (one per line)</label>
                <textarea class="form-control" id="topics" name="topics[]" rows="5"></textarea>
            </div>
            <div class="mb-3">
                <!-- Removed Select Tutor dropdown -->
                <!-- <label for="tutor_id" class="form-label label">Select Tutor</label>
                <select class="form-select" id="tutor_id" name="tutor_id" required>
                    <option value="">Select Tutor</option>
                    <?php
                    // Fetch all tutors for the dropdown
                    $query = "SELECT id, full_name FROM tutors";
                    $result = mysqli_query($conn, $query);
                    $tutors = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    foreach ($tutors as $tutor): ?>
                        <option value="<?php echo $tutor['id']; ?>"><?php echo htmlspecialchars($tutor['full_name']); ?></option>
                    <?php endforeach; ?>
                </select> -->
            </div>
            <button type="submit" class="btn btn-primary">Add Course</button>
            <a href="courses_list.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>