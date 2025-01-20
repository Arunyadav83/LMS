<?php
require_once 'config.php';
require_once 'functions.php';
include 'header.php';

if (!is_logged_in()) {
    $_SESSION['error'] = "You must be logged in to view quiz results.";
    header("Location: login.php");
    exit();
}

if (!isset($_GET['class_id'])) {
    $_SESSION['error'] = "No class selected for quiz results.";
    header("Location: index.php");
    exit();
}

$class_id = (int)$_GET['class_id'];

// Fetch class details and associated course ID
$class_query = "SELECT c.class_name, c.course_id FROM classes c WHERE c.id = ?";
$class_stmt = mysqli_prepare($conn, $class_query);
mysqli_stmt_bind_param($class_stmt, "i", $class_id);
mysqli_stmt_execute($class_stmt);
$class_result = mysqli_stmt_get_result($class_stmt);
$class = mysqli_fetch_assoc($class_result);

if (!$class) {
    $_SESSION['error'] = "Invalid class selected.";
    header("Location: index.php");
    exit();
}

$course_id = $class['course_id'];

?>

<div class="container mt-4">
    <h1>Quiz Results - <?php echo htmlspecialchars($class['class_name']); ?></h1>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['quiz_data'])): ?>
        <h2>Question Explanations</h2>
        <?php
        // Fetch the quiz data from the session
        $quiz_data = $_SESSION['quiz_data'];

        foreach ($quiz_data as $question_id => $question_data):
            // Initialize the required variables
            $question_text = $question_data['question_text'];
            $user_selected_answer_id = $question_data['selected_answer_id'];
            $correct_answer_id = isset($question_data['correct_answer_id']) ? $question_data['correct_answer_id'] : null; // Check if correct_answer_id exists
            $answers = $question_data['answers'];
        ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Question: <?php echo htmlspecialchars($question_text); ?></h5>
                    <?php foreach ($answers as $answer): ?>
                        <div class="mb-2 <?php echo $answer['is_correct'] ? 'text-success' : 'text-danger'; ?>">
                            <strong>Option: <?php echo htmlspecialchars($answer['answer_text']); ?></strong>
                            <?php if ($answer['id'] == $user_selected_answer_id): ?>
                                <span class="<?php echo $answer['is_correct'] ? 'badge bg-success' : 'badge bg-danger'; ?>">
                                    (Your selection)
                                </span>
                            <?php endif; ?>

                            <br>
                            Explanation: <?php echo htmlspecialchars($answer['feedback']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <?php unset($_SESSION['quiz_data']); ?>
    <?php endif; ?>

    <?php
    // Check if user has passed the quiz
    $quiz_check_query = "SELECT percentage FROM quiz_results WHERE user_id = ? AND class_id = ? ORDER BY submitted_at DESC LIMIT 1";
    $quiz_check_stmt = mysqli_prepare($conn, $quiz_check_query);
    mysqli_stmt_bind_param($quiz_check_stmt, "ii", $_SESSION['user_id'], $class_id);
    mysqli_stmt_execute($quiz_check_stmt);
    $quiz_result = mysqli_stmt_get_result($quiz_check_stmt);
    $quiz_score = mysqli_fetch_assoc($quiz_result);
    
    if ($quiz_score && $quiz_score['percentage'] >= 70): ?>
        <div class="mt-4">
            <form action="unlock_next_class.php" method="post">
                <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                <input type="hidden" name="quiz_score" value="<?php echo $quiz_score['percentage']; ?>">
                <button type="submit" class="btn btn-primary mb-3">Unlock Next Lesson</button>
            </form>
        </div>
    <?php else: ?>
        <div class="alert alert-warning mt-4">
            You need to score at least 70% on the quiz to unlock the next lesson.
        </div>
    <?php endif; ?>

    <a href="course.php?id=<?php echo $course_id; ?>" class="btn btn-secondary">Back to Course</a>
</div>

<?php include 'footer.php'; ?>
