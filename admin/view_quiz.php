<?php
require_once '../config.php';
require_once '../functions.php';

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
    <h1>View Quiz Questions and Answers</h1>
    <?php if (mysqli_num_rows($questions_result) > 0): ?>
        <?php while ($question = mysqli_fetch_assoc($questions_result)): ?>
            <div class="mb-3">
                <h5 class="dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#question_<?php echo $question['id']; ?>" aria-expanded="false">
                    <?php echo htmlspecialchars($question['question_text']); ?>
                </h5>
                <div id="question_<?php echo $question['id']; ?>" class="collapse">
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
                    <ul class="list-group">
                        <?php while ($answer = mysqli_fetch_assoc($answers_result)): ?>
                            <li class="list-group-item">
                                <?php echo htmlspecialchars($answer['answer_text']); ?>
                                <?php if ($answer['is_correct']): ?>
                                    <span class="badge bg-success ms-2">Correct</span>
                                <?php endif; ?>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No questions found for this quiz.</p>
    <?php endif; ?>
</div>