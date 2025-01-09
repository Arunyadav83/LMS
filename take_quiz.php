<?php
require_once 'config.php';
require_once 'functions.php';
include 'header.php';

if (!isset($_GET['class_id'])) {
    $_SESSION['error'] = "No class selected for quiz.";
    header("Location: index.php");
    exit();
}

$class_id = (int)$_GET['class_id'];

// Fetch quiz questions for this class
$questions_query = "SELECT * FROM quiz_questions WHERE class_id = ?";
$questions_stmt = mysqli_prepare($conn, $questions_query);

if (!$questions_stmt) {
    die("Failed to prepare statement for questions: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($questions_stmt, "i", $class_id);
mysqli_stmt_execute($questions_stmt);
$questions_result = mysqli_stmt_get_result($questions_stmt);

?>

<div class="container mt-4">
    <h1>Quiz</h1>
    
    <form id="quizForm" method="post" action="submit_quiz.php">
        <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
        <?php while ($question = mysqli_fetch_assoc($questions_result)): ?>
            <div class="mb-3">
                <h5><?php echo htmlspecialchars($question['question_text']); ?></h5>
                <?php
                // Fetch answers for this question
                $answers_query = "SELECT * FROM quiz_answers WHERE question_id = ?";
                $answers_stmt = mysqli_prepare($conn, $answers_query);

                if (!$answers_stmt) {
                    die("Failed to prepare statement for answers: " . mysqli_error($conn));
                }

                mysqli_stmt_bind_param($answers_stmt, "i", $question['id']);
                mysqli_stmt_execute($answers_stmt);
                $answers_result = mysqli_stmt_get_result($answers_stmt);
                ?>
                <?php while ($answer = mysqli_fetch_assoc($answers_result)): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="question_<?php echo $question['id']; ?>" id="answer_<?php echo $answer['id']; ?>" value="<?php echo $answer['id']; ?>">
                        <label class="form-check-label" for="answer_<?php echo $answer['id']; ?>">
                            <?php echo htmlspecialchars($answer['answer_text']); ?>
                        </label>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endwhile; ?>
        <button type="submit" class="btn btn-primary">Submit Quiz</button>
    </form>
</div>

<?php include 'footer.php'; ?>
