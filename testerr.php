<?php
// Enable error reporting
ini_set('display_errors', 0); // Disable display errors
ini_set('log_errors', 1);     // Enable log errors
ini_set('error_log', 'C:\xampp\php\logs\php_errors.log');

// Generate an intentional error
trigger_error("Test error for PHP error log", E_USER_WARNING);

echo "Check the error log file.";
?>
