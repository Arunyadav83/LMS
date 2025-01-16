<?php
session_start();

require_once '../config.php';
require_once '../functions.php';

if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    // Destroy the session
    session_unset();
    session_destroy();

    // Redirect to index.php after logout
    header("Location: index.php");
    exit();
}

// Ensure the directory exists (useful if you're working with file uploads or logs)
function ensure_directory_exists($path)
{
    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }
}

// Check if the user is logging out (optional second check, should be handled by first condition)
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();

    // Redirect to index.php after logging out
    header("Location: index.php");
    exit();
}

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

            // Trigger SweetAlert for successful login
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Login Successful!',
                        text: 'Welcome, " . $tutor['full_name'] . "!',
                        icon: 'success',
                        confirmButtonText: 'Okay'
                    }).then(function() {
                        window.location = '" . $_SERVER['PHP_SELF'] . "';
                    });
                });
            </script>";
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
                color: #0433c3;
                font-size: 35px;
                margin: 20px;
            }

            .form-label {
                color: blue
            }
        </style>
    </head>

    <body>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6" style="max-width: 700px; height: 60vh; background-color: #f8f9fa; border-radius: 2%; text-align: center;">
                    <h2 class="mb-4">Tutor Login</h2>
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <form method="post" action="" style="width: 300px; margin-top: 30px; margin-left: 28%;"> <!-- Adjusted margin -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary w-60">Login</button>
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

// Handle form submissions for adding classes
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
                $video_path = str_replace('../', '', $video_path); // Remove the '../' from the beginning for database storage
            } else {
                $error = "Failed to upload video. Error: " . $_FILES['class_video']['error'];
            }
        }

        // Handle online class scheduling
        $is_online = isset($_POST['is_online']) ? 1 : 0;
        $online_link = mysqli_real_escape_string($conn, $_POST['online_link'] ?? '');
        $schedule_time = mysqli_real_escape_string($conn, $_POST['schedule_time'] ?? '');

        // Insert class data into the database
        $query = "INSERT INTO classes (course_id, tutor_id, class_name, description, video_path, is_online, online_link, schedule_time) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "iisssiis", $course_id, $tutor_id, $class_name, $description, $video_path, $is_online, $online_link, $schedule_time);
        mysqli_stmt_execute($stmt);
        $class_id = mysqli_insert_id($conn);

        // Handle quiz questions
        if (isset($_POST['questions'])) {
            foreach ($_POST['questions'] as $index => $question) {
                $question_text = trim($question);
                $correct_answer = trim($_POST['correct_answers'][$index] ?? '');
                $video_id = isset($_POST['video_id'][$index]) ? mysqli_real_escape_string($conn, $_POST['video_id'][$index]) : null;

                // Validate required fields
                if (empty($question_text) || empty($correct_answer)) {
                    echo "Error: Question or correct answer is empty!";
                    continue;
                }

                $query = "INSERT INTO quiz_questions (class_id, question_text, correct_answer, video_id) VALUES (?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $query);

                if (!$stmt) {
                    echo "Error preparing statement for quiz question: " . mysqli_error($conn);
                    continue;
                }

                mysqli_stmt_bind_param($stmt, "isss", $class_id, $question_text, $correct_answer, $video_id);

                if (mysqli_stmt_execute($stmt)) {
                    $question_id = mysqli_insert_id($conn);
                    
                    if (isset($_POST['answers'][$index]) && isset($_POST['feedback'][$index])) {
                        foreach ($_POST['answers'][$index] as $answer_index => $answer) {
                            $answer_text = mysqli_real_escape_string($conn, $answer);
                            $feedback_text = mysqli_real_escape_string($conn, $_POST['feedback'][$index][$answer_index]);
                            
                            if (empty($answer_text) || empty($feedback_text)) {
                                echo "Error: Answer or feedback is empty!";
                                continue;
                            }
                            
                            // Set 'is_correct' dynamically based on the selected correct answer
                            $is_correct = ($_POST['correct_answers'][$index] == $answer_index) ? 1 : 0;
                            
                            $query = "INSERT INTO quiz_answers (question_id, answer_text, feedback, is_correct) VALUES (?, ?, ?, ?)";
                            $stmt = mysqli_prepare($conn, $query);
                            
                            if (!$stmt) {
                                echo "Error preparing statement for quiz answer: " . mysqli_error($conn);
                                continue;
                            }
                            
                            mysqli_stmt_bind_param($stmt, "issi", $question_id, $answer_text, $feedback_text, $is_correct);
                            
                            if (!mysqli_stmt_execute($stmt)) {
                                echo "Error executing query for quiz answer: " . mysqli_error($conn);
                            }
                        }
                    }
                } else {
                    echo "Error inserting quiz question: " . mysqli_error($conn);
                }
            }  // <-- Close the foreach loop
        }  // <-- Close the if for checking 'questions'
    }  // <-- Close the if for 'add_class'
}  // <-- Close the if for checking POST request


// Fetch courses assigned to the logged-in tutor
$query = "SELECT * FROM courses WHERE tutor_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $tutor_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$courses = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Fetch classes created by the tutor
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
    <title>Classes - LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Bundle with Popper --><!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>



</head>

<style>
    #addClassOffcanvas {
        width: 700px;
        /* Set your desired width */
    }

    .btn-primary {
        margin-right: 36%;
    }

    h2 {
        position: relative;
        /* Ensure proper stacking of elements */
        z-index: 1;
    }

    #cardsContainer {
        margin-top: 50px;
        /* Adds spacing between the heading and cards */
    }

    .btn-info {
        z-index: 2;
        /* Ensure buttons appear above other elements */
    }

    form {
        background-color: #f8f9fa;
        /* Light background for contrast */
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        /* margin-left: 13%; */
        /* margin-right: 4%; */
    }

    input.form-control {
        height: 40px;
        /* Adjust input height */
        margin-right: 4%;
    }

    .card {
        border: 1px solid #ccc;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .col-md-6 {
        max-width: 740px;
        height: 80vh;
        border-radius: 3%;
        box-shadow: 2px 3px 10px grey;
        background-color: rgb(130, 142, 181);
    }

    .custom-table th {
        background-color: #343a40;
        color: white;
        text-align: center;
    }

    .custom-table td {
        vertical-align: middle;
        text-align: center;
    }

    .btn-custom {
        border-radius: 20px;
        font-size: 14px;
    }

    body {
        background-color: rgb(213, 221, 245);
    }

    .table-container {
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .add-button {
        position: relative;
        left: 994px;
        bottom: 65px;
        padding-inline: 30px;
        font-weight: bolder;
        text-decoration: none;
        color: #0433c3;
        /* background-color: white; */
        padding-block: 10px;
        border-radius: 30px;
        transition: all 0.3s ease;
    }

    .add-button:hover {
        color: white;
        background-color: #0433c3;
        border-radius: 30px;
    }
</style>
<script>
    document.getElementById('addQuestion').addEventListener('click', function() {
        const quizContainer = document.getElementById('quizQuestions');
        const questionHTML = `
        <div class="mb-3">
            <label for="question" class="form-label">Question</label>
            <input type="text" class="form-control" name="questions[]" required>
        </div>
        <div class="mb-3">
            <label for="correct_answer" class="form-label">Correct Answer</label>
            <input type="text" class="form-control" name="correct_answers[]" required>
        </div>`;
        quizContainer.insertAdjacentHTML('beforeend', questionHTML);
    });
</script>

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
                            <li>
                                <a class="dropdown-item" href="?logout=1">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </li>
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
        <h1 class="mb-4" style="color: #1a237e;">Classes - Tutor: <?php echo htmlspecialchars($tutor_name); ?></h1>
        <!-- Button to Open the Offcanvas -->
        <button class="add-button" type="button" data-bs-toggle="offcanvas" data-bs-target="#addClassOffcanvas" aria-controls="addClassOffcanvas">
            Add New Class
        </button>

        <!-- Offcanvas for Adding a New Class -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="addClassOffcanvas" aria-labelledby="addClassOffcanvasLabel">
            <div class="offcanvas-header">
                <h5 id="addClassOffcanvasLabel">Add New Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
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
                    <h3>Quiz Questions</h3>
                    <div id="quizQuestions">

                    </div>
                    <button type="button" class="btn btn-secondary mb-3" id="addQuestion">Add Question</button>
                    <button type="submit" name="add_class" class="btn btn-primary">Add Class</button>
                </form>
            </div>
        </div>

        <h2 class="mt-4">Your Classes</h2>

        <!-- Include Font Awesome for Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

        <div class="mb-4" style="margin-left: 1100px;">
            <button id="listViewBtn" class="btn btn-info me-2">
                <i class="fas fa-list"></i> <!-- List View Icon -->
            </button>
            <button id="gridViewBtn" class="btn btn-info">
                <i class="fas fa-th"></i> <!-- Grid View Icon -->
            </button>
        </div>



        <!-- Include SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


        <!-- List View -->
        <div id="listView" class="table-container">
            <?php if (empty($classes)): ?>
                <div class="alert alert-warning text-center">
                    <p>You haven't created any classes yet.</p>
                </div>
            <?php else: ?>
                <table class="table table-striped table-hover custom-table">
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
                                <td>
                                    <span class="text-truncate d-inline-block" style="max-width: 150px;" title="<?php echo htmlspecialchars($class['description']); ?>">
                                        <?php echo htmlspecialchars($class['description']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (!empty($class['video_path'])): ?>
                                        <a href="<?php echo $class['video_path']; ?>" target="_blank" class="btn btn-sm btn-success btn-custom">View Video</a>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($class['is_online']): ?>
                                        <span class="badge bg-primary">Yes</span><br>
                                        <small><?php echo htmlspecialchars($class['schedule_time']); ?></small>
                                    <?php else: ?>
                                        <span class="badge bg-danger">No</span>
                                    <?php endif; ?>
                                </td>
                                <td class="d-flex justify-content-center">
                                    <a href="edit_class.php?id=<?php echo $class['id']; ?>" class="btn btn-sm btn-primary btn-custom me-2">Edit</a>
                                    <a href="view_quiz.php?class_id=<?php echo $class['id']; ?>" class="btn btn-sm btn-info btn-custom me-2">View Quiz</a>
                                    <button class="btn btn-sm btn-danger btn-custom" onclick="confirmDelete(<?php echo $class['id']; ?>)">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <script>
            function confirmDelete(classId) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to delete this class?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Proceed with the deletion
                        window.location.href = 'delete_class.php?id=' + classId;
                    }
                });
            }
            document.getElementById("is_online").addEventListener("change", function() {
                const details = document.getElementById("onlineClassDetails");
                if (this.checked) {
                    details.style.display = "block";
                } else {
                    details.style.display = "none";
                }
            });
        </script>

        <?php
        // Initialize success flags
        $delete_success = false;
        $update_success = false;
        // After deletion is successful in your delete_class.php file

        if (isset($_GET['action'])) {
            if ($_GET['action'] === 'deleted') {
                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Class Deleted Successfully!',
                    showConfirmButton: false,
                    timer: 1500
                });
                </script>";
            } elseif ($_GET['action'] === 'updated') {
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
        }
        ?>

        <!-- Grid View -->
        <div id="gridView" class="row d-none">
            <?php if (empty($classes)): ?>
                <p>You haven't created any classes yet.</p>
            <?php else: ?>
                <?php foreach ($classes as $class): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($class['class_name']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($class['description']); ?></p>
                                <p><strong>Course:</strong> <?php echo htmlspecialchars($class['course_title']); ?></p>
                                <p><strong>Online Class:</strong> <?php echo $class['is_online'] ? 'Yes - ' . htmlspecialchars($class['schedule_time']) : 'No'; ?></p>
                                <?php if (!empty($class['video_path'])): ?>
                                    <a href="<?php echo $class['video_path']; ?>" target="_blank" class="btn btn-info btn-sm mb-2">View Video</a>
                                <?php endif; ?>
                                <div class="d-flex justify-content-start">
                                    <a href="edit_class.php?id=<?php echo $class['id']; ?>" class="btn btn-sm btn-primary me-2">Edit</a>
                                    <a href="view_quiz.php?class_id=<?php echo $class['id']; ?>" class="btn btn-sm btn-info me-2">View Quiz</a>
                                    <a href="delete_class.php?id=<?php echo $class['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this class?');">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- JS to Toggle Views -->
        <script>
            document.getElementById('listViewBtn').addEventListener('click', function() {
                document.getElementById('listView').classList.remove('d-none');
                document.getElementById('gridView').classList.add('d-none');
            });

            document.getElementById('gridViewBtn').addEventListener('click', function() {
                document.getElementById('gridView').classList.remove('d-none');
                document.getElementById('listView').classList.add('d-none');
            });
        </script>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
    const quizForm = document.getElementById('quizForm'); // Ensure your form has this ID
    const addQuestionBtn = document.getElementById('addQuestion');
    const quizQuestionsContainer = document.getElementById('quizQuestions');
    let questionCount = 0;

    function createQuestionTemplate(index) {
        return `
        <div class="card mb-3" data-question-index="${index}">
            <div class="card-body">
                <h5 class="card-title">Question ${index + 1}</h5>
                <div class="mb-3">
                    <label for="question${index}" class="form-label">Question</label>
                    <input type="text" class="form-control" id="question${index}" name="questions[${index}]" required>
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
        </div>`;
    }

    // Add event listener for adding more questions
    addQuestionBtn.addEventListener('click', function () {
        quizQuestionsContainer.insertAdjacentHTML('beforeend', createQuestionTemplate(questionCount));
        questionCount++;
    });

    // Validate form on submit
    quizForm.addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent default form submission

        const formData = new FormData(quizForm);
        const payload = [];

        // Loop through questions to construct payload
        for (let i = 0; i < questionCount; i++) {
            const questionText = formData.get(`questions[${i}]`);
            const answers = formData.getAll(`answers[${i}][]`);
            const feedbacks = formData.getAll(`feedback[${i}][]`);
            const correctAnswerIndex = formData.get(`correct_answers[${i}]`);

            if (!questionText || correctAnswerIndex === null) {
                alert(`Error: Question ${i + 1} or its correct answer is not properly filled.`);
                return;
            }

            answers.forEach((answer, idx) => {
                payload.push({
                    question_id: i + 1,
                    answer_text: answer,
                    is_correct: idx == correctAnswerIndex ? 1 : 0, // Mark correct answer as 1
                    feedback: feedbacks[idx],
                });
            });
        }

        // Submit data via fetch
        fetch('/submit-quiz', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(payload),
        })
            .then((response) => response.json())
            .then((data) => {
                console.log('Quiz submitted successfully:', data);
                alert('Quiz submitted successfully!');
            })
            .catch((error) => {
                console.error('Error submitting quiz:', error);
                alert('An error occurred while submitting the quiz.');
            });
    });
});
    </script>

</body>

</html>