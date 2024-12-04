<?php
require_once 'config.php';
require_once 'functions.php';
include 'header.php';

// Check if course ID is provided
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "No course selected.";
    header("Location: index.php");
    exit();
}

$course_id = (int)$_GET['id'];

// Fetch course details
$course_query = "SELECT c.id, c.title, c.description, t.full_name AS tutor_name 
                 FROM courses c
                 LEFT JOIN tutors t ON c.tutor_id = t.id
                 WHERE c.id = ?";
$course_stmt = mysqli_prepare($conn, $course_query);
if (!$course_stmt) {
    die("Database query preparation failed: " . mysqli_error($conn));
}

// Check if binding parameters is successful
if (!mysqli_stmt_bind_param($course_stmt, "i", $course_id)) {
    die("Parameter binding failed: " . mysqli_error($conn));
}

mysqli_stmt_execute($course_stmt);
$course_result = mysqli_stmt_get_result($course_stmt);
$course = mysqli_fetch_assoc($course_result);

if (!$course) {
    $_SESSION['error'] = "Invalid course selected.";
    header("Location: index.php");
    exit();
}

// Fetch classes (videos) for this course
$classes_query = "SELECT id, class_name, description, video_path, is_online, online_link, schedule_time
                  FROM classes
                  WHERE course_id = ?
                  ORDER BY created_at ASC";
$classes_stmt = mysqli_prepare($conn, $classes_query);
if (!$classes_stmt) {
    die("Database query preparation failed: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($classes_stmt, "i", $course_id);
mysqli_stmt_execute($classes_stmt);
$classes_result = mysqli_stmt_get_result($classes_stmt);

// Function to check if the previous quiz was completed and if enough time has passed
function isPreviousQuizCompletedAndTimeElapsed($conn, $class_id, $user_id) {
    $query = "SELECT completed_at FROM quiz_completions 
              WHERE class_id = ? AND user_id = ? 
              ORDER BY completed_at DESC LIMIT 1";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $class_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $completion = mysqli_fetch_assoc($result);
    
    if ($completion) {
        $completed_time = strtotime($completion['completed_at']);
        $current_time = time();
        return ($current_time - $completed_time) >= 60; // 60 seconds = 1 minute
    }
    return false;
}

// Assuming you have a form submission to assign a tutor to a course
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id']) && isset($_POST['tutor_id'])) {
    $course_id = $_POST['course_id'];
    $tutor_id = $_POST['tutor_id'];

    // Prepare the SQL statement to update the course with the selected tutor
    $query_assign_tutor = "UPDATE courses SET tutor_id = ? WHERE id = ?";
    $stmt = $conn->prepare($query_assign_tutor);
    $stmt->bind_param("ii", $tutor_id, $course_id);
    $stmt->execute();
    $stmt->close();
}

?>
<div class="container mt-4">
    <h1><?php echo htmlspecialchars($course['title']); ?></h1>
    <p><strong>Tutor:</strong> <?php echo htmlspecialchars($course['tutor_name']); ?></p>
    <p><?php echo htmlspecialchars($course['description']); ?></p>

    <h2 class="mt-4">Course Content</h2>

    <?php if (mysqli_num_rows($classes_result) > 0): ?>
        <div class="row">
            <?php 
            $lesson_number = 1;
            while ($class = mysqli_fetch_assoc($classes_result)): 
                $is_unlocked = ($lesson_number === 1);
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Lesson <?php echo $lesson_number; ?>: <?php echo htmlspecialchars($class['class_name']); ?></h5>
                            <?php if ($is_unlocked): ?>
                                <?php if (!empty($class['video_path'])): ?>
                                    <?php
                                    $video_path = htmlspecialchars($class['video_path']);
                                    $video_url = 'serve_video.php?video=' . urlencode($video_path);
                                    ?>
                                    <div id="video_container_<?php echo $class['id']; ?>" class="video-container">
                                        <video width="100%" id="video_<?php echo $class['id']; ?>" controlsList="nodownload">
                                            <source src="<?php echo $video_url; ?>" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                        <div id="custom_controls_<?php echo $class['id']; ?>" class="custom-controls">
                                            <button id="play_pause_<?php echo $class['id']; ?>" class="btn btn-primary btn-sm">Play/Pause</button>
                                            <input type="range" id="seek_bar_<?php echo $class['id']; ?>" value="0" class="seek-bar">
                                            <input type="range" id="volume_<?php echo $class['id']; ?>" min="0" max="1" step="0.1" value="1" class="volume-control">
                                        </div>
                                    </div>
                                    <div id="quiz_option_<?php echo $class['id']; ?>" style="display: none;">
                                        <a href="take_quiz.php?class_id=<?php echo $class['id']; ?>" class="btn btn-primary">Take Quiz</a>
                                    </div>
                                    <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        var video = document.getElementById('video_<?php echo $class['id']; ?>');
                                        var playPauseButton = document.getElementById('play_pause_<?php echo $class['id']; ?>');
                                        var seekBar = document.getElementById('seek_bar_<?php echo $class['id']; ?>');
                                        var volumeControl = document.getElementById('volume_<?php echo $class['id']; ?>');
                                        var quizOption = document.getElementById('quiz_option_<?php echo $class['id']; ?>');
                                        var hasWatched = false;
                                        var maxTime = 0;

                                        // Remove default controls
                                        video.removeAttribute('controls');

                                        playPauseButton.addEventListener('click', function() {
                                            if (video.paused) {
                                                video.play();
                                            } else {
                                                video.pause();
                                            }
                                        });

                                        video.addEventListener('timeupdate', function() {
                                            var value = (100 / video.duration) * video.currentTime;
                                            seekBar.value = value;
                                            if (video.currentTime > maxTime) {
                                                maxTime = video.currentTime;
                                            }
                                        });

                                        seekBar.addEventListener('change', function() {
                                            var time = video.duration * (seekBar.value / 100);
                                            if (time <= maxTime) {
                                                video.currentTime = time;
                                            } else {
                                                seekBar.value = (100 / video.duration) * maxTime;
                                            }
                                        });

                                        volumeControl.addEventListener('change', function() {
                                            video.volume = volumeControl.value;
                                        });

                                        video.addEventListener('ended', function() {
                                            hasWatched = true;
                                            quizOption.style.display = 'block';
                                        });
                                    });
                                    </script>
                                <?php else: ?>
                                    <p>No video available for this class.</p>
                                <?php endif; ?>
                            <?php else: ?>
                                <p>This lesson is locked. Complete the previous lesson and quiz to unlock.</p>
                                <form method="POST" action="unlock_class.php">
                                    <input type="hidden" name="class_id" value="<?php echo $class['id']; ?>">
                                    <button type="submit" class="btn btn-primary">Unlock</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php 
            $lesson_number++;
            endwhile; 
            ?>
        </div>
    <?php else: ?>
        <p>No classes available for this course yet.</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
