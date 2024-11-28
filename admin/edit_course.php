<?php
session_start();
require_once '../config.php';
require_once '../functions.php';

// Check if the user is logged in as an admin
if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

$course_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $topics = isset($_POST['topics']) ? explode("\n", $_POST['topics']) : [];

    // Update course
    $update_query = "UPDATE courses SET title = '$title', description = '$description' WHERE id = $course_id";
    mysqli_query($conn, $update_query);

    // Delete existing topics
    $delete_topics_query = "DELETE FROM course_topics WHERE course_id = $course_id";
    mysqli_query($conn, $delete_topics_query);

    // Insert new topics
    foreach ($topics as $topic) {
        $topic = trim(mysqli_real_escape_string($conn, $topic));
        if (!empty($topic)) {
            $insert_topic_query = "INSERT INTO course_topics (course_id, topic_name) VALUES ($course_id, '$topic')";
            mysqli_query($conn, $insert_topic_query);
        }
    }

    header("Location: courses_list.php");
    exit();
}

// Fetch course details
$query = "SELECT c.*, GROUP_CONCAT(ct.topic_name SEPARATOR '\n') as topics
          FROM courses c 
          LEFT JOIN course_topics ct ON c.id = ct.course_id
          WHERE c.id = $course_id
          GROUP BY c.id";
$result = mysqli_query($conn, $query);
$course = mysqli_fetch_assoc($result);

if (!$course) {
    header("Location: courses_list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course - LMS Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Course</h1>
        <form action="" method="post">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($course['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($course['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="topics" class="form-label">Topics (one per line)</label>
                <textarea class="form-control" id="topics" name="topics" rows="5"><?php echo htmlspecialchars($course['topics']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Course</button>
            <a href="courses_list.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>