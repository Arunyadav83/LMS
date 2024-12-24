<?php
session_start();
require_once '../config.php';
require_once '../functions.php';
 
// At the beginning of your PHP script, add this function:
function ensure_directory_exists($path)
{
    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }
}
 
// Check if the user is logging out
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
 
// Check if the user is logging in
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
 
    $query = "SELECT * FROM tutors WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
 
    if ($tutor = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $tutor['password'])) {
            $_SESSION['user_id'] = $tutor['id'];
            $_SESSION['role'] = $tutor['role'];
            $_SESSION['full_name'] = $tutor['full_name'];
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $error = "Invalid email or password";
        }
    } else {
        $error = "Invalid email or password";
    }
}
 
// Check if the user is logged in and is a tutor
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'instructor') {
    // If not logged in, show login form
?>
    <!DOCTYPE html>
    <html lang="en">
 
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tutor Login - LMS</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .mb-4 {
                color: hotpink;
                font-size: 35px
            }
 
            .form-label {
                color: blue
            }
        </style>
    </head>
 
    <body>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6" style="background-color:rgba(178, 445, 215, 0.5); padding: 20px; border-radius: 5px;">
                    <h2 class="mb-4">Tutor Login</h2>
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <form method="post" action="">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary mx-auto d-block">Login</button>
                    </form>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
 
    </html>
<?php
    exit();
}
 
$tutor_id = $_SESSION['user_id'];
$tutor_name = $_SESSION['full_name'];
 
// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_class'])) {
        $course_id = (int)$_POST['course_id'];
        $class_name = mysqli_real_escape_string($conn, $_POST['class_name']);
        $description = strip_tags(mysqli_real_escape_string($conn, $_POST['description']));
 
        // Handle video upload
        $video_path = '';
        if (isset($_FILES['class_video']) && $_FILES['class_video']['error'] == 0) {
            $upload_dir = '../uploads/class_videos/';
            ensure_directory_exists($upload_dir);
            $video_path = $upload_dir . time() . '_' . $_FILES['class_video']['name'];
            if (move_uploaded_file($_FILES['class_video']['tmp_name'], $video_path)) {
                $video_path = str_replace('../', '', $video_path); // Remove the '../' from the beginning for database storage
            } else {
                $error = "Failed to upload video. Error: " . $_FILES['class_video']['error'];
            }
        }
 
        // Handle online class scheduling
        $is_online = isset($_POST['is_online']) ? 1 : 0;
        $online_link = mysqli_real_escape_string($conn, $_POST['online_link'] ?? '');
        $schedule_time = mysqli_real_escape_string($conn, $_POST['schedule_time'] ?? '');
           // Clean up the bio (remove \r characters)
        //  $bio = str_replace("\r", "", $bio);
 
        $query = "INSERT INTO classes (course_id, tutor_id, class_name, description, video_path, is_online, online_link, schedule_time)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "iisssiis", $course_id, $tutor_id, $class_name, $description, $video_path, $is_online, $online_link, $schedule_time);
        mysqli_stmt_execute($stmt);
 
        $class_id = mysqli_insert_id($conn);
 
        // Handle quiz questions
        if (isset($_POST['questions'])) {
            foreach ($_POST['questions'] as $index => $question) {
                $question_text = mysqli_real_escape_string($conn, $question);
                $correct_answer = mysqli_real_escape_string($conn, $_POST['correct_answers'][$index]);
 
                $query = "INSERT INTO quiz_questions (class_id, question_text, correct_answer)
                          VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "iss", $class_id, $question_text, $correct_answer);
                mysqli_stmt_execute($stmt);
 
                $question_id = mysqli_insert_id($conn);
 
                // Insert answer options and feedback
                foreach ($_POST['answers'][$index] as $answer_index => $answer) {
                    $answer_text = mysqli_real_escape_string($conn, $answer);
                    $feedback_text = mysqli_real_escape_string($conn, $_POST['feedback'][$index][$answer_index]);
                    $query = "INSERT INTO quiz_answers (question_id, answer_text, feedback)
                              VALUES (?, ?, ?)";
                    $stmt = mysqli_prepare($conn, $query);
                    mysqli_stmt_bind_param($stmt, "iss", $question_id, $answer_text, $feedback_text);
                    mysqli_stmt_execute($stmt);
                }
            }
        }
    }
}
 
// Fetch courses assigned to the logged-in tutor
// Fetch courses assigned to the logged-in tutor
$query = "SELECT * FROM courses WHERE tutor_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $tutor_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$courses = mysqli_fetch_all($result, MYSQLI_ASSOC);
 
$query = "SELECT c.*, co.title as course_title
          FROM classes c
          JOIN courses co ON c.course_id = co.id
          WHERE c.tutor_id = ?
          ORDER BY c.created_at DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $tutor_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$classes = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
 
<!DOCTYPE html>
<html lang="en">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Class - LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tiny.cloud/1/azj9n0neceenohuu03tmpx6oq579m7sfow413lvfsebb2293/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#description',
            plugins: 'lists link image table',
            toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image',
            menubar: false,
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
            }
        });
    </script>
    <style>
        body {
            background-color:white;
            width: 100%;
 
        }
 
        label {
            font-size: 23px;
        }
    </style>
</head>
 
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">LMS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($tutor_name); ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="?logout=1"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Add New Class</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="course_id" class="form-label">Course</label>
                <select class="form-select" name="course_id" id="course_id" required>
                    <option value="">Select a course</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?= htmlspecialchars($course['id']) ?>">
                            <?= htmlspecialchars($course['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
 
            <div class="mb-3">
                <label for="class_name" class="form-label">Class Name</label>
                <input type="text" class="form-control" name="class_name" id="class_name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" class="form-control" rows="5" required></textarea>
            </div>
            <div class="mb-3">
                <label for="class_video" class="form-label">Upload Class Video</label>
                <input type="file" class="form-control" name="class_video" id="class_video">
            </div>
            <div class="mb-3">
                <input type="checkbox" name="is_online" id="is_online" value="1">
                <label for="is_online" class="form-label">Online Class</label>
            </div>
            <div class="mb-3">
                <label for="online_link" class="form-label">Online Link (If online)</label>
                <input type="url" class="form-control" name="online_link" id="online_link">
            </div>
            <div class="mb-3">
                <label for="schedule_time" class="form-label">Scheduled Time</label>
                <input type="datetime-local" class="form-control" name="schedule_time" id="schedule_time">
            </div>
            <button type="submit" class="btn btn-primary" name="add_class">Add Class</button>
        </form>
    </div>
</body>
 
</html>
 