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
        $quiz_data = $_SESSION['quiz_data'];
        unset($_SESSION['quiz_data']);
        
        foreach ($quiz_data as $question_id => $question_data):
        ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Question: <?php echo htmlspecialchars($question_data['question_text']); ?></h5>
                    <?php foreach ($question_data['answers'] as $answer): ?>
                        <div class="mb-2 <?php echo $answer['is_correct'] ? 'text-success' : 'text-danger'; ?>">
                            <strong>Option: <?php echo htmlspecialchars($answer['answer_text']); ?></strong>
                            <?php if ($answer['id'] == $question_data['selected_answer_id']): ?>
                                (Your selection)
                            <?php endif; ?>
                            <br>
                            Explanation: <?php echo htmlspecialchars($answer['feedback']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="mt-4">
        <form action="unlock_next_class.php" method="post">
            <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
            <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
            <button type="submit" class="btn btn-primary mb-3">Unlock Next Lesson</button>
        </form>
    </div>

    <a href="course.php?id=<?php echo $course_id; ?>" class="btn btn-secondary">Back to Course</a>
</div>

<?php include 'footer.php'; ?>