<?php
session_start();

require_once '../config.php';
require_once '../functions.php';

// At the beginning of your PHP script, add this function:
function ensure_directory_exists($path) {
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
            .mb-4{
                color: hotpink;
                font-size:35px
                
             }

             .form-label{
                color:blue
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
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        
        // Handle video upload
        $video_path = '';
        if (isset($_FILES['class_video']) && $_FILES['class_video']['error'] == 0) {
            $upload_dir = '../uploads/class_videos/';
            ensure_directory_exists($upload_dir);
            $video_path = $upload_dir . time() . '_' . $_FILES['class_video']['name'];
            if (move_uploaded_file($_FILES['class_video']['tmp_name'], $video_path)) {
                // File uploaded successfully
                $video_path = str_replace('../', '', $video_path); // Remove the '../' from the beginning for database storage
            } else {
                $error = "Failed to upload video. Error: " . $_FILES['class_video']['error'];
            }
        }
        
        // Handle online class scheduling
        $is_online = isset($_POST['is_online']) ? 1 : 0;
        $online_link = mysqli_real_escape_string($conn, $_POST['online_link'] ?? '');
        $schedule_time = mysqli_real_escape_string($conn, $_POST['schedule_time'] ?? '');
        
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
$query = "SELECT * FROM courses WHERE tutor_id = ?";  // Fetch courses for the specific tutor
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $tutor_id);  // Bind the tutor_id parameter
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$courses = mysqli_fetch_all($result, MYSQLI_ASSOC);  // Fetch all courses as an associative array

// Fetch classes created by the tutor
$query = "SELECT c.*, co.title as course_title 
          FROM classes c 
          JOIN courses co ON c.course_id = co.id 
          WHERE c.tutor_id = ?
          ORDER BY c.created_at DESC";  // Add this line to sort by creation date, newest first
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
    <title>Classes - LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        <?php
        if (isset($_SESSION['success_message'])) {
            echo "<div class='alert alert-success'>" . $_SESSION['success_message'] . "</div>";
            unset($_SESSION['success_message']);
        }
        if (isset($_SESSION['error_message'])) {
            echo "<div class='alert alert-danger'>" . $_SESSION['error_message'] . "</div>";
            unset($_SESSION['error_message']);
        }
        ?>
        <h1 class="mb-4">Classes - Tutor: <?php echo htmlspecialchars($tutor_name); ?></h1>
        
        <!-- Add Class Form -->
        <h2>Add New Class</h2>
        <form action="" method="post" enctype="multipart/form-data" id="addClassForm">
            <div class="mb-3">
                <label for="course_id" class="form-label">Course</label>
                <select class="form-control" id="course_id" name="course_id" required>
                    <option value="">Select Course</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?php echo $course['id']; ?>"><?php echo htmlspecialchars($course['title']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="class_name" class="form-label">Class Name</label>
                <input type="text" class="form-control" id="class_name" name="class_name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="class_video" class="form-label">Class Video</label>
                <input type="file" class="form-control" id="class_video" name="class_video">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_online" name="is_online">
                <label class="form-check-label" for="is_online">Online Class</label>
            </div>
            <div id="onlineClassDetails" style="display: none;">
                <div class="mb-3">
                    <label for="online_link" class="form-label">Online Class Link</label>
                    <input type="text" class="form-control" id="online_link" name="online_link">
                </div>
                <div class="mb-3">
                    <label for="schedule_time" class="form-label">Schedule Time</label>
                    <input type="datetime-local" class="form-control" id="schedule_time" name="schedule_time">
                </div>
            </div>
            
            <!-- Quiz Questions -->
            <h3>Quiz Questions</h3>
            <div id="quizQuestions">
                <!-- Initial question will be added here -->
            </div>
            <button type="button" class="btn btn-secondary mb-3" id="addQuestion">Add Question</button>
            
            <button type="submit" name="add_class" class="btn btn-primary">Add Class</button>
        </form>

        <!-- Classes List -->
        <h2 class="mt-5">Your Classes</h2>
        <?php if (empty($classes)): ?>
            <p>You haven't created any classes yet.</p>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Class Name</th>
                        <th>Description</th>
                        <th>Video</th>
                        <th>Online Class</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($classes as $class): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($class['course_title']); ?></td>
                        <td><?php echo htmlspecialchars($class['class_name']); ?></td>
                        <td><?php echo htmlspecialchars($class['description']); ?></td>
                        <td>
                            <?php if (!empty($class['video_path'])): ?>
                                <a href="<?php echo $class['video_path']; ?>" target="_blank">View Video</a>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($class['is_online']): ?>
                                Yes - <?php echo htmlspecialchars($class['schedule_time']); ?>
                            <?php else: ?>
                                No
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_class.php?id=<?php echo $class['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="view_quiz.php?class_id=<?php echo $class['id']; ?>" class="btn btn-sm btn-info">View Quiz</a>
                            <a href="delete_class.php?id=<?php echo $class['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this class?');">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isOnlineCheckbox = document.getElementById('is_online');
            const onlineClassDetails = document.getElementById('onlineClassDetails');
            const addQuestionBtn = document.getElementById('addQuestion');
            const quizQuestionsContainer = document.getElementById('quizQuestions');
            let questionCount = 0;

            isOnlineCheckbox.addEventListener('change', function() {
                onlineClassDetails.style.display = this.checked ? 'block' : 'none';
            });

            function createQuestionTemplate(index) {
                return `
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Question ${index + 1}</h5>
                            <div class="mb-3">
                                <label for="question${index}" class="form-label">Question</label>
                                <input type="text" class="form-control" id="question${index}" name="questions[]" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Answers</label>
                                ${[0, 1, 2, 3].map(answerIndex => `
                                    <div class="mb-3">
                                        <div class="input-group mb-2">
                                            <div class="input-group-text">
                                                <input type="radio" name="correct_answers[${index}]" value="${answerIndex}" required>
                                            </div>
                                            <input type="text" class="form-control" name="answers[${index}][]" placeholder="Answer option" required>
                                        </div>
                                        <input type="text" class="form-control" name="feedback[${index}][]" placeholder="Feedback for this answer" required>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                `;
            }

            // Add initial question
            quizQuestionsContainer.insertAdjacentHTML('beforeend', createQuestionTemplate(questionCount));
            questionCount++;

            // Add event listener for adding more questions
            addQuestionBtn.addEventListener('click', function() {
                quizQuestionsContainer.insertAdjacentHTML('beforeend', createQuestionTemplate(questionCount));
                questionCount++;
            });
        });
    </script>
</body>
</html>