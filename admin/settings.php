<?php
require_once '../config.php';
require_once '../functions.php';

// Initialize variables for form values
$site_name = $site_email = $max_file_size = $allowed_file_types = $smtp_host = $smtp_port = $smtp_username = $smtp_password = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form data
    $site_name = sanitize_input($_POST['site_name']);
    $site_email = sanitize_input($_POST['site_email']);
    $max_file_size = sanitize_input($_POST['max_file_size']);
    $allowed_file_types = sanitize_input($_POST['allowed_file_types']);
    $smtp_host = sanitize_input($_POST['smtp_host']);
    $smtp_port = sanitize_input($_POST['smtp_port']);
    $smtp_username = sanitize_input($_POST['smtp_username']);
    $smtp_password = sanitize_input($_POST['smtp_password']);

    // TODO: Add code to save settings to database or configuration file
    
    $success_message = "Settings updated successfully!";
}

// TODO: Add code to retrieve current settings from database or configuration file

$current_page = 'settings';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings - LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Admin Settings</h1>
                </div>

                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="">
                    <h3>General Settings</h3>
                    <div class="mb-3">
                        <label for="site_name" class="form-label">Site Name</label>
                        <input type="text" class="form-control" id="site_name" name="site_name" value="<?php echo htmlspecialchars($site_name); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="site_email" class="form-label">Site Email</label>
                        <input type="email" class="form-control" id="site_email" name="site_email" value="<?php echo htmlspecialchars($site_email); ?>" required>
                    </div>

                    <h3>File Upload Settings</h3>
                    <div class="mb-3">
                        <label for="max_file_size" class="form-label">Max File Size (MB)</label>
                        <input type="number" class="form-control" id="max_file_size" name="max_file_size" value="<?php echo htmlspecialchars($max_file_size); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="allowed_file_types" class="form-label">Allowed File Types (comma-separated)</label>
                        <input type="text" class="form-control" id="allowed_file_types" name="allowed_file_types" value="<?php echo htmlspecialchars($allowed_file_types); ?>" required>
                    </div>

                    <h3>Email Settings</h3>
                    <div class="mb-3">
                        <label for="smtp_host" class="form-label">SMTP Host</label>
                        <input type="text" class="form-control" id="smtp_host" name="smtp_host" value="<?php echo htmlspecialchars($smtp_host); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="smtp_port" class="form-label">SMTP Port</label>
                        <input type="number" class="form-control" id="smtp_port" name="smtp_port" value="<?php echo htmlspecialchars($smtp_port); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="smtp_username" class="form-label">SMTP Username</label>
                        <input type="text" class="form-control" id="smtp_username" name="smtp_username" value="<?php echo htmlspecialchars($smtp_username); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="smtp_password" class="form-label">SMTP Password</label>
                        <input type="password" class="form-control" id="smtp_password" name="smtp_password" value="<?php echo htmlspecialchars($smtp_password); ?>">
                    </div>

                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </form>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>