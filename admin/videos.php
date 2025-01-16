<?php
require_once '../config.php';
require_once '../functions.php';

// Fetch all courses for the dropdown
$query = "SELECT id, title FROM courses";
$result = mysqli_query($conn, $query);
$courses = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Fetch videos and quizzes if a course is selected
$selected_course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : null;
$videos_and_quizzes = [];

if ($selected_course_id) {
    $query = "SELECT c.id, c.class_name, c.video_path, 
              (SELECT COUNT(*) FROM quiz_questions WHERE class_id = c.id) as quiz_count
              FROM classes c
              WHERE c.course_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $selected_course_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $videos_and_quizzes = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$current_page = 'videos';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Videos and Quizzes - LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<style>
    .navbar{
        background-color: #1a237e !important;
    }
    .h2{
        color: #1a237e;
    }
</style>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">LMS</a>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Videos and Quizzes</h1>
                </div>
                
                <form action="" method="get" class="mb-4">
    <div class="row">
        <div class="col-md-6">
            <select name="course_id" class="form-select" onchange="this.form.submit()">
                <option value="">Select a course</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?php echo $course['id']; ?>" <?php echo ($selected_course_id == $course['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($course['title']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</form>

<?php if ($selected_course_id): ?>
    <div class="row">
        <?php foreach ($videos_and_quizzes as $item): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($item['class_name']); ?></h5>

                        <?php if (!empty($item['video_path'])): ?>
                            <?php
                            // Base path for videos
                            $base_url = '../uploads/class_videos/';
                            $video_url = $base_url . $item['video_path'];
                            ?>

                            <!-- Embed the video -->
                            <video width="100%" controls>
                                <source src="<?php echo htmlspecialchars($video_url); ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        <?php else: ?>
                            <p>No video available</p>
                        <?php endif; ?>

                        <p>Quiz Questions: <?php echo $item['quiz_count']; ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php elseif (isset($_GET['course_id'])): ?>
    <p>No videos or quizzes found for this course.</p>
<?php endif; ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>