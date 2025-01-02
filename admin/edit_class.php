<?php
session_start();
require_once '../config.php';
require_once '../functions.php';

// Check if the user is logged in and is a tutor
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'instructor') {
    header("Location: classes.php");
    exit();
}

$tutor_id = $_SESSION['user_id'];
$class_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch the class details
$query = "SELECT c.*, co.title as course_title 
          FROM classes c 
          JOIN courses co ON c.course_id = co.id 
          WHERE c.id = ? AND c.tutor_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ii", $class_id, $tutor_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$class = mysqli_fetch_assoc($result);

if (!$class) {
    $_SESSION['error_message'] = "Class not found or you don't have permission to edit it.";
    header("Location: classes.php");
    exit();
}

// Fetch all courses
$query = "SELECT id, title FROM courses";
$result = mysqli_query($conn, $query);
$courses = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Fetch quiz questions for this class
$query = "SELECT q.*, a.id as answer_id, a.answer_text, a.is_correct, a.feedback
          FROM quiz_questions q
          LEFT JOIN quiz_answers a ON q.id = a.question_id
          WHERE q.class_id = ?
          ORDER BY q.id, a.id";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $class_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$quiz_data = mysqli_fetch_all($result, MYSQLI_ASSOC);

$questions = [];
foreach ($quiz_data as $row) {
    if (!isset($questions[$row['id']])) {
        $questions[$row['id']] = [
            'id' => $row['id'],
            'question_text' => $row['question_text'],
            'answers' => []
        ];
    }
    $questions[$row['id']]['answers'][] = [
        'id' => $row['answer_id'],
        'answer_text' => $row['answer_text'],
        'is_correct' => $row['is_correct'],
        'feedback' => $row['feedback']
    ];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_id = (int)$_POST['course_id'];
    $class_name = mysqli_real_escape_string($conn, $_POST['class_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $is_online = isset($_POST['is_online']) ? 1 : 0;
    $online_link = mysqli_real_escape_string($conn, $_POST['online_link'] ?? '');
    $schedule_time = mysqli_real_escape_string($conn, $_POST['schedule_time'] ?? '');

    // Handle video upload if a new video is provided
    if (isset($_FILES['class_video']) && $_FILES['class_video']['error'] == 0) {
        $upload_dir = '../uploads/class_videos/';
        ensure_directory_exists($upload_dir);
        $video_path = $upload_dir . time() . '_' . $_FILES['class_video']['name'];
        if (move_uploaded_file($_FILES['class_video']['tmp_name'], $video_path)) {
            $video_path = str_replace('../', '', $video_path);
            // Update the video path in the database
            $update_video_query = "UPDATE classes SET video_path = ? WHERE id = ?";
            $update_video_stmt = mysqli_prepare($conn, $update_video_query);
            mysqli_stmt_bind_param($update_video_stmt, "si", $video_path, $class_id);
            mysqli_stmt_execute($update_video_stmt);
        } else {
            $_SESSION['error_message'] = "Failed to upload video. Error: " . $_FILES['class_video']['error'];
        }
    }

    // Update class details
    $update_query = "UPDATE classes SET course_id = ?, class_name = ?, description = ?, is_online = ?, online_link = ?, schedule_time = ? WHERE id = ?";
    $update_stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($update_stmt, "issiisi", $course_id, $class_name, $description, $is_online, $online_link, $schedule_time, $class_id);
    mysqli_stmt_execute($update_stmt);

    // Update quiz questions and answers
    if (isset($_POST['questions'])) {
        foreach ($_POST['questions'] as $index => $question_text) {
            $question_id = $_POST['question_ids'][$index] ?? null;
            $question_text = mysqli_real_escape_string($conn, $question_text);

            if ($question_id) {
                // Update existing question
                $update_question_query = "UPDATE quiz_questions SET question_text = ? WHERE id = ?";
                $update_question_stmt = mysqli_prepare($conn, $update_question_query);
                mysqli_stmt_bind_param($update_question_stmt, "si", $question_text, $question_id);
                mysqli_stmt_execute($update_question_stmt);
            } else {
                // Insert new question
                $insert_question_query = "INSERT INTO quiz_questions (class_id, question_text) VALUES (?, ?)";
                $insert_question_stmt = mysqli_prepare($conn, $insert_question_query);
                mysqli_stmt_bind_param($insert_question_stmt, "is", $class_id, $question_text);
                mysqli_stmt_execute($insert_question_stmt);
                $question_id = mysqli_insert_id($conn);
            }

            // Update or insert answers
            foreach ($_POST['answers'][$index] as $answer_index => $answer_text) {
                $answer_id = $_POST['answer_ids'][$index][$answer_index] ?? null;
                $is_correct = ($_POST['correct_answers'][$index] == $answer_index) ? 1 : 0;
                $feedback = mysqli_real_escape_string($conn, $_POST['feedback'][$index][$answer_index]);

                if ($answer_id) {
                    // Update existing answer
                    $update_answer_query = "UPDATE quiz_answers SET answer_text = ?, is_correct = ?, feedback = ? WHERE id = ?";
                    $update_answer_stmt = mysqli_prepare($conn, $update_answer_query);
                    mysqli_stmt_bind_param($update_answer_stmt, "sisi", $answer_text, $is_correct, $feedback, $answer_id);
                    mysqli_stmt_execute($update_answer_stmt);
                } else {
                    // Insert new answer
                    $insert_answer_query = "INSERT INTO quiz_answers (question_id, answer_text, is_correct, feedback) VALUES (?, ?, ?, ?)";
                    $insert_answer_stmt = mysqli_prepare($conn, $insert_answer_query);
                    mysqli_stmt_bind_param($insert_answer_stmt, "isis", $question_id, $answer_text, $is_correct, $feedback);
                    mysqli_stmt_execute($insert_answer_stmt);
                }
            }
        }
    }

    $_SESSION['success_message'] = "Class updated successfully.";
    header("Location: classes.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Class - LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Edit Class</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="course_id" class="form-label">Course</label>
                <select class="form-control" id="course_id" name="course_id" required>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?php echo $course['id']; ?>" <?php echo ($course['id'] == $class['course_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($course['title']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="class_name" class="form-label">Class Name</label>
                <input type="text" class="form-control" id="class_name" name="class_name" value="<?php echo htmlspecialchars($class['class_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($class['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="class_video" class="form-label">Class Video</label>
                <input type="file" class="form-control" id="class_video" name="class_video">
            </div>
            <button type="submit" class="btn btn-primary">Update Class</button>
        </form>
    </div>
    <?php


if (isset($_SESSION[''])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       

        // Perform the update logic here (e.g., updating the course in the database).
        $update_success = updateCourse($_POST);

        // If the update is successful, redirect with the `action=updated` parameter.
        if ($update_success) {
            header("Location: edit_class.php?id=" . $_GET['id'] . "&action=updated");
            exit;
        } else {
            echo "Failed to update the course.";
        }
    }
}

function updateCourse($data) {
    // Include your database connection
    include 'config.php';

    // Prepare the SQL query to update the course
    $course_id = $_GET['id'];
    $course_name = $data['course_name'];
    $course_description = $data['course_description'];

    $query = "UPDATE courses SET name = ?, description = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $course_name, $course_description, $course_id);

    // Execute the query and return success or failure
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}




if (isset($_GET['action']) && $_GET['action'] === 'updated') {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
    Swal.fire({
        icon: 'success',
        title: 'Class Updated Successfully!',
        showConfirmButton: false,
        timer: 1500
    });
    </script>";
}


?>

</body>
</html>