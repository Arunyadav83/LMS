<!-- <?php
// Assuming this is the page after quiz completion
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

// Logic to check if the quiz is completed
// ...

// Display the unlock button
?>
<form method="POST" action="unlock_next_class.php">
    <input type="hidden" name="class_id" value="<?php echo $current_class_id; ?>">
    <input type="hidden" name="course_id" value="<?php echo $current_course_id; ?>">
    <button type="submit" class="btn btn-primary">Unlock Next Class</button>
</form>  -->