<?php
session_start();
require_once '../config.php';
require_once '../functions.php';

// Check if user is logged in and is a tutor
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'instructor') {
    header("Location: login.php");
    exit();
}

$class_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch class details, video, and questions
$query = "SELECT c.*, v.path as video_path 
          FROM classes c 
          LEFT JOIN videos v ON c.video_id = v.id 
          WHERE c.id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $class_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$class = mysqli_fetch_assoc($result);

if (!$class) {
    die("Class not found");
}

// Fetch questions
$query = "SELECT q.*, GROUP_CONCAT(CONCAT(a.id, ':', a.answer_text, ':', a.feedback) SEPARATOR '||') as answers
          FROM quiz_questions q
          LEFT JOIN quiz_answers a ON q.id = a.question_id
          WHERE q.class_id = ?
          GROUP BY q.id";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $class_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$questions = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Class - <?php echo htmlspecialchars($class['class_name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1><?php echo htmlspecialchars($class['class_name']); ?></h1>
        <p><?php echo htmlspecialchars($class['description']); ?></p>
        
        <?php if ($class['video_path']): ?>
            <div class="mb-4">
                <h2>Class Video</h2>
                <video width="640" height="360" controls>
                    <source src="<?php echo htmlspecialchars($class['video_path']); ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        <?php endif; ?>
        
        <h2>Quiz Questions</h2>
        <?php foreach ($questions as $question): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($question['question_text']); ?></h5>
                    <ul class="list-group">
                        <?php 
                        $answers = explode('||', $question['answers']);
                        foreach ($answers as $answer):
                            list($id, $text, $feedback) = explode(':', $answer);
                        ?>
                            <li class="list-group-item">
                                <?php echo htmlspecialchars($text); ?>
                                <small class="d-block text-muted">Feedback: <?php echo htmlspecialchars($feedback); ?></small>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>