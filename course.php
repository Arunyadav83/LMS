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
function isPreviousQuizCompletedAndTimeElapsed($conn, $class_id, $user_id)
{
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

    // Check if the statement preparation was successful
    if ($stmt) {
        $stmt->bind_param("ii", $tutor_id, $course_id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Tutor assigned successfully."; // Success message
        } else {
            $_SESSION['error'] = "Failed to assign tutor: " . $stmt->error; // Error message
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Failed to prepare statement: " . $conn->error; // Error message
    }
}

?>
<div class="container mt-4">
    <h1 class="text-center text-primary"><?php echo htmlspecialchars($course['title']); ?></h1>
    <div class="row align-items-center">
        <!-- Tutor Image -->
        <?php
        $tutor_image_path = 'assets/images/' . strtolower(str_replace(' ', '_', $course['title'])) . '.jpg';
        ?>
        <div class="col-md-4">
            <?php if (file_exists($tutor_image_path)): ?>
                <img
                    src="<?php echo $tutor_image_path; ?>"
                    class="img-fluid rounded-square"
                    style="max-height: 150px; object-fit: cover;"
                    alt="<?php echo htmlspecialchars($course['title']); ?>">
            <?php else: ?>
                <img
                    src="assets/images/default_tutor.jpg"
                    class="img-fluid rounded-circle"
                    style="max-height: 150px; object-fit: cover;"
                    alt="Default Tutor">
            <?php endif; ?>
        </div>
        <div class="col-md-8">
            <p class="lead"><strong>Tutor:</strong> <?php echo htmlspecialchars($course['tutor_name']); ?></p>
            <p class="text-secondary"><?php echo htmlspecialchars($course['description']); ?></p>
        </div>
    </div>

    <h2 class="mt-5 mb-4 text-center text-info">Course Content</h2>

<?php if (mysqli_num_rows($classes_result) > 0): ?>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php
        $lesson_number = 1;
        while ($class = mysqli_fetch_assoc($classes_result)):
            $is_unlocked = ($lesson_number === 1);
        ?>
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-success">
                            Lesson <?php echo $lesson_number; ?>: <?php echo htmlspecialchars($class['class_name']); ?>
                        </h5>
                        <?php if ($is_unlocked): ?>
                            <?php if (!empty($class['video_path'])): ?>
                                <?php
                                $video_path = htmlspecialchars($class['video_path']);
                                $video_url = 'serve_video.php?video=' . urlencode($video_path);
                                ?>
                                <div class="video-container">
                                    <video
                                        id="video_<?php echo $class['id']; ?>"
                                        class="rounded mb-3"
                                        controlsList="nodownload"
                                        style="width: 100%;"
                                    >
                                        <source src="<?php echo $video_url; ?>" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                    <div class="custom-controls mt-3 d-flex align-items-center justify-content-between">
                                        <button id="play_pause_<?php echo $class['id']; ?>" class="btn btn-primary btn-sm">
                                            <i class="bi bi-play-circle"></i> Play
                                        </button>
                                        <button id="mute_unmute_<?php echo $class['id']; ?>" class="btn btn-secondary btn-sm">
                                            <i class="bi bi-volume-up-fill"></i>
                                        </button>
                                        <div class="volume-container d-flex align-items-center">
                                            <span id="volume_label_<?php echo $class['id']; ?>" class="text-muted me-2">100%</span>
                                            <input
                                                type="range"
                                                id="volume_<?php echo $class['id']; ?>"
                                                min="0"
                                                max="1"
                                                step="0.1"
                                                value="1"
                                                class="volume-control"
                                            />
                                        </div>
                                        <button id="fullscreen_<?php echo $class['id']; ?>" class="btn btn-success btn-sm">
                                            <i class="bi bi-fullscreen"></i>
                                        </button>
                                    </div>
                                </div>
                                <a href="take_quiz.php?class_id=<?php echo $class['id']; ?>" class="btn btn-primary btn-block mt-3">
                                    Take Quiz
                                </a>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        const video = document.getElementById('video_<?php echo $class['id']; ?>');
                                        const playPauseButton = document.getElementById('play_pause_<?php echo $class['id']; ?>');
                                        const muteUnmuteButton = document.getElementById('mute_unmute_<?php echo $class['id']; ?>');
                                        const volumeControl = document.getElementById('volume_<?php echo $class['id']; ?>');
                                        const volumeLabel = document.getElementById('volume_label_<?php echo $class['id']; ?>');
                                        const fullscreenButton = document.getElementById('fullscreen_<?php echo $class['id']; ?>');

                                        // Play/Pause functionality
                                        playPauseButton.addEventListener('click', function () {
                                            if (video.paused) {
                                                video.play();
                                                playPauseButton.innerHTML = '<i class="bi bi-pause-circle"></i> Pause';
                                            } else {
                                                video.pause();
                                                playPauseButton.innerHTML = '<i class="bi bi-play-circle"></i> Play';
                                            }
                                        });

                                        // Mute/Unmute functionality
                                        muteUnmuteButton.addEventListener('click', function () {
                                            if (video.muted) {
                                                video.muted = false;
                                                muteUnmuteButton.innerHTML = '<i class="bi bi-volume-up-fill"></i>';
                                            } else {
                                                video.muted = true;
                                                muteUnmuteButton.innerHTML = '<i class="bi bi-volume-mute-fill"></i>';
                                            }
                                        });

                                        // Volume control
                                        volumeControl.addEventListener('input', function () {
                                            video.volume = volumeControl.value;
                                            volumeLabel.textContent = Math.round(volumeControl.value * 100) + '%';
                                        });

                                        // Fullscreen functionality
                                        fullscreenButton.addEventListener('click', function () {
                                            if (video.requestFullscreen) {
                                                video.requestFullscreen();
                                            } else if (video.webkitRequestFullscreen) { /* Safari */
                                                video.webkitRequestFullscreen();
                                            } else if (video.msRequestFullscreen) { /* IE11 */
                                                video.msRequestFullscreen();
                                            }
                                        });
                                    });
                                </script>
                            <?php else: ?>
                                <p class="text-danger">No video available for this class.</p>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="text-muted">This lesson is locked. Complete the previous lesson and quiz to unlock.</p>
                            <form method="POST" action="unlock_class.php">
                                <input type="hidden" name="class_id" value="<?php echo $class['id']; ?>">
                                <button type="submit" class="btn btn-warning">Unlock</button>
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
    <p class="text-center text-danger">No classes available for this course yet.</p>
<?php endif; ?>

</div>


<?php include 'footer.php'; ?>