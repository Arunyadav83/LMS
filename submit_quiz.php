<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!is_logged_in()) {
    $_SESSION['error'] = "You must be logged in to submit a quiz.";
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: index.php");
    exit();
}

$class_id = (int)$_POST['class_id'];
$user_id = $_SESSION['user_id']; // Assuming you store user_id in session upon login

// Check if the user exists in the users table
$user_check_query = "SELECT id FROM users WHERE id = ?";
$user_check_stmt = mysqli_prepare($conn, $user_check_query);
mysqli_stmt_bind_param($user_check_stmt, "i", $user_id);
mysqli_stmt_execute($user_check_stmt);
$user_result = mysqli_stmt_get_result($user_check_stmt);

if (mysqli_num_rows($user_result) == 0) {
    $_SESSION['error'] = "User not found in the database. Please log in again.";
    header("Location: login.php");
    exit();
}

$score = 0;
$total_questions = 0;
$quiz_data = [];

foreach ($_POST as $key => $value) {
    if (strpos($key, 'question_') === 0) {
        $question_id = substr($key, 9);
        $selected_answer_id = (int)$value;

        $query = "SELECT qa.id, qa.answer_text, qa.is_correct, qa.feedback, qq.question_text
                  FROM quiz_answers qa
                  JOIN quiz_questions qq ON qa.question_id = qq.id
                  WHERE qa.question_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $question_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $question_data = [
            'question_text' => '',
            'answers' => [],
            'selected_answer_id' => $selected_answer_id
        ];

        while ($answer = mysqli_fetch_assoc($result)) {
            $question_data['question_text'] = $answer['question_text'];
            $question_data['answers'][] = [
                'id' => $answer['id'],
                'answer_text' => $answer['answer_text'],
                'is_correct' => $answer['is_correct'],
                'feedback' => $answer['feedback']
            ];

            if ($answer['id'] == $selected_answer_id && $answer['is_correct']) {
                $score++;
            }
        }

        $quiz_data[$question_id] = $question_data;
        $total_questions++;
    }
}
if ($total_questions > 0) {
    $percentage_score = ($score / $total_questions) * 100;
} else {
    $percentage_score = 0; // Set to 0% if no questions were answered
    $_SESSION['error'] = "No questions were answered or found in the quiz.";
    header("Location: quiz_result.php?class_id=" . $class_id);
    exit();
}


// Fetch tutor name
$tutor_query = "SELECT t.full_name AS tutor_name
                FROM classes c
                JOIN courses co ON c.course_id = co.id
                JOIN tutors t ON co.tutor_id = t.id
                WHERE c.id = ?";
$tutor_stmt = mysqli_prepare($conn, $tutor_query);
mysqli_stmt_bind_param($tutor_stmt, "i", $class_id);
mysqli_stmt_execute($tutor_stmt);
$tutor_result = mysqli_stmt_get_result($tutor_stmt);
$tutor = mysqli_fetch_assoc($tutor_result);
$tutor_name = $tutor['tutor_name'];

// Store the quiz result in the database
$insert_query = "INSERT INTO quiz_results (user_id, class_id, score, total_questions, percentage, tutor_name) VALUES (?, ?, ?, ?, ?, ?)";
$insert_stmt = mysqli_prepare($conn, $insert_query);
mysqli_stmt_bind_param($insert_stmt, "iiidds", $user_id, $class_id, $score, $total_questions, $percentage_score, $tutor_name);

if (mysqli_stmt_execute($insert_stmt)) {
    $_SESSION['success'] = "Quiz submitted successfully. Your score: $score out of $total_questions (" . number_format($percentage_score, 2) . "%)";
    $_SESSION['quiz_data'] = $quiz_data;
} else {
    $_SESSION['error'] = "There was an error saving your quiz result. Please try again. Error: " . mysqli_error($conn);
}

// Fetch user details for the message
$user_query = "SELECT username, email FROM users WHERE id = ?";
$user_stmt = mysqli_prepare($conn, $user_query);
mysqli_stmt_bind_param($user_stmt, "i", $user_id);
mysqli_stmt_execute($user_stmt);
$user_result = mysqli_stmt_get_result($user_stmt);
$user = mysqli_fetch_assoc($user_result);

$_SESSION['success'] .= "<br>Student Name: " . htmlspecialchars($user['username']) . "<br>Email: " . htmlspecialchars($user['email']) . "<br>Tutor Name: " . htmlspecialchars($tutor_name);

// Check if the quiz was passed (you may want to define a passing threshold)
$passing_threshold = 70; // For example, 70% to pass
$quiz_passed = $percentage_score >= $passing_threshold;

// Insert into quiz_completions if passed
if ($quiz_passed) {
    $insert_completion_query = "INSERT INTO quiz_completions (user_id, class_id, completed_at) VALUES (?, ?, NOW())";
    $insert_completion_stmt = mysqli_prepare($conn, $insert_completion_query);
    mysqli_stmt_bind_param($insert_completion_stmt, "ii", $user_id, $class_id);
    
    if (mysqli_stmt_execute($insert_completion_stmt)) {
        $_SESSION['success'] .= "<br>Quiz completed successfully.";
        $_SESSION['quiz_passed'] = true;

        // Fetch the current class's course_id and created_at
        $current_class_query = "SELECT course_id, created_at FROM classes WHERE id = ?";
        $current_class_stmt = mysqli_prepare($conn, $current_class_query);
        mysqli_stmt_bind_param($current_class_stmt, "i", $class_id);
        mysqli_stmt_execute($current_class_stmt);
        $current_class_result = mysqli_stmt_get_result($current_class_stmt);
        $current_class = mysqli_fetch_assoc($current_class_result);

        // Unlock the next class
        $unlock_next_class_query = "UPDATE classes SET is_unlocked = TRUE 
                                    WHERE course_id = ? 
                                    AND created_at > ? 
                                    ORDER BY created_at ASC LIMIT 1";
        $unlock_next_class_stmt = mysqli_prepare($conn, $unlock_next_class_query);
        mysqli_stmt_bind_param($unlock_next_class_stmt, "is", $current_class['course_id'], $current_class['created_at']);
        
        if (mysqli_stmt_execute($unlock_next_class_stmt)) {
            $affected_rows = mysqli_stmt_affected_rows($unlock_next_class_stmt);
            if ($affected_rows > 0) {
                $_SESSION['success'] .= " The next lesson has been unlocked.";
            } else {
                $_SESSION['success'] .= " No next lesson to unlock.";
            }
        } else {
            $_SESSION['error'] .= "<br>There was an error unlocking the next lesson. Error: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['error'] .= "<br>There was an error recording your quiz completion. Error: " . mysqli_error($conn);
    }
}

header("Location: quiz_result.php?class_id=" . $class_id);
exit();