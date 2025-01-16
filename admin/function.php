<?php
function is_admin_logged_in() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    
}
function ensure_directory_exists($directory) {
    if (!file_exists($directory)) {
        mkdir($directory, 0777, true);  // Create directory if it doesn't exist
    }
}

?>
