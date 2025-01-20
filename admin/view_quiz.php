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

<style>
    /* General styles */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
    color: #333;
}

/* Container styles */
.container {
    max-width: 800px;
    margin: 30px auto;
    padding: 15px;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Heading styles */
h1 {
    color: #007bff;
    font-size: 28px;
    text-align: center;
    margin-bottom: 20px;
    font-weight: bold;
}

/* Card styles */
.card {
    border: none;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 15px;
    background-color: #ffffff;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Card header */
.card-header {
    background-color: #f1f1f1;
    padding: 15px;
    cursor: pointer;
}

.card-header h5 {
    margin: 0;
    font-size: 18px;
    font-weight: bold;
}

/* Card body styles */
.card-body {
    padding: 15px;
    background-color: #ffffff;
}

/* Answer list styles */
.list-group {
    padding: 0;
    margin: 0;
    list-style: none;
}

.list-group-item {
    padding: 10px 15px;
    margin-bottom: 5px;
    border: 1px solid #ddd;
    border-radius: 5px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #f9f9f9;
}

/* Correct answer styles */
.list-group-item.bg-success {
    background-color: #28a745 !important;
    color: #ffffff !important;
}

.list-group-item.bg-success .badge {
    background-color: #ffc107;
    color: #000;
}

/* Badge styles */
.badge {
    font-size: 12px;
    padding: 5px 10px;
    border-radius: 12px;
}

/* Hover effects */
.card-header:hover {
    background-color: #e9ecef;
}

.list-group-item:hover {
    background-color: #f1f1f1;
    transition: background-color 0.3s ease;
}

/* Alert styles */
.alert {
    margin-top: 20px;
    padding: 15px;
    border-radius: 5px;
    color: #555;
    background-color: #e9ecef;
    border: 1px solid #ddd;
    text-align: center;
}

</style>
<div class="container">
    <h1>Quiz Questions</h1>
    <?php if (mysqli_num_rows($questions_result) > 0): ?>
        <?php while ($question = mysqli_fetch_assoc($questions_result)): ?>
            <div class="card">
                <div class="card-header" data-bs-toggle="collapse" data-bs-target="#question_<?php echo $question['id']; ?>" aria-expanded="false">
                    <h5 class="mb-0"><?php echo htmlspecialchars($question['question_text']); ?></h5>
                </div>
                <div id="question_<?php echo $question['id']; ?>" class="collapse">
                    <div class="card-body">
                        <?php
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
                                <li class="list-group-item <?php echo $answer['is_correct'] ? 'bg-success' : ''; ?>">
                                    <span><?php echo htmlspecialchars($answer['answer_text']); ?></span>
                                    <?php if ($answer['is_correct']): ?>
                                        <span class="badge">Correct Answer</span>
                                    <?php endif; ?>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="alert alert-info">
            No questions found for this quiz.
        </div>
    <?php endif; ?>
</div>
