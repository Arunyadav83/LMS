<?php
session_start();
require_once '../config.php';
require_once '../functions.php';

// Check if the user is logged in as an admin
if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

$current_page = 'users';

// Function to check if a user exists
function user_exists($conn, $username, $email)
{
    $query = "SELECT * FROM tutors WHERE username = ? OR email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_num_rows($result) > 0;
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Common variables
    $success = '';
    $error = '';

    // Add tutor
    if (isset($_POST['add_tutor'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $full_name = mysqli_real_escape_string($conn, $_POST['full_name'] ?? '');
        $bio = mysqli_real_escape_string($conn, $_POST['bio'] ?? '');
        $specialization = mysqli_real_escape_string($conn, $_POST['specialization'] ?? '');

        // Handle file uploads
        $resume_path = '';
        $certificate_path = '';
        if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
            $resume_path = 'uploads/resumes/' . time() . '_' . $_FILES['resume']['name'];
            move_uploaded_file($_FILES['resume']['tmp_name'], $resume_path);
        }
        if (isset($_FILES['certificate']) && $_FILES['certificate']['error'] == 0) {
            $certificate_path = 'uploads/certificates/' . time() . '_' . $_FILES['certificate']['name'];
            move_uploaded_file($_FILES['certificate']['tmp_name'], $certificate_path);
        }

        if (user_exists($conn, $username, $email)) {
            $error = "Username or email already exists.";
        } else {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $query = "INSERT INTO tutors (username, email, password, full_name, bio, specialization, resume_path, certificate_path) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ssssssss", $username, $email, $password, $full_name, $bio, $specialization, $resume_path, $certificate_path);

            if (mysqli_stmt_execute($stmt)) {
                $success = "Tutor added successfully.";
            } else {
                $error = "Error adding tutor: " . mysqli_error($conn);
            }
        }
    }

    // Edit tutor
    elseif (isset($_POST['edit_tutor'])) {
        $id = intval($_POST['id']);
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
        $bio = mysqli_real_escape_string($conn, $_POST['bio']);
        $specialization = mysqli_real_escape_string($conn, $_POST['specialization']);

        // Handle file uploads
        $resume_path = '';
        $certificate_path = '';
        if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
            $resume_path = 'uploads/resumes/' . time() . '_' . $_FILES['resume']['name'];
            move_uploaded_file($_FILES['resume']['tmp_name'], $resume_path);
        }
        if (isset($_FILES['certificate']) && $_FILES['certificate']['error'] == 0) {
            $certificate_path = 'uploads/certificates/' . time() . '_' . $_FILES['certificate']['name'];
            move_uploaded_file($_FILES['certificate']['tmp_name'], $certificate_path);
        }

        // Build query dynamically
        $query = "UPDATE tutors SET username = ?, email = ?, full_name = ?, bio = ?, specialization = ?";
        $params = [$username, $email, $full_name, $bio, $specialization];
        $types = "sssss";

        if ($resume_path) {
            $query .= ", resume_path = ?";
            $params[] = $resume_path;
            $types .= "s";
        }
        if ($certificate_path) {
            $query .= ", certificate_path = ?";
            $params[] = $certificate_path;
            $types .= "s";
        }

        $query .= " WHERE id = ?";
        $params[] = $id;
        $types .= "i";

        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, $types, ...$params);

        if (mysqli_stmt_execute($stmt)) {
            $success = "Tutor updated successfully.";
        } else {
            $error = "Error updating tutor: " . mysqli_error($conn);
        }
    }

    // Delete tutor
    elseif (isset($_POST['delete_tutor'])) {
        $id = intval($_POST['id']);
        error_log("Processing delete for tutor ID: $id");
    
        // Delete enrollments first
        $enrollment_query = "DELETE FROM enrollments WHERE tutor_id = ?";
        $enrollment_stmt = mysqli_prepare($conn, $enrollment_query);
        mysqli_stmt_bind_param($enrollment_stmt, "i", $id);
        if (!mysqli_stmt_execute($enrollment_stmt)) {
            error_log("Error deleting enrollments: " . mysqli_error($conn));
            header("Location: users.php?error=Failed to delete enrollments");
            exit();
        }
    
        // Delete the tutor
        $query = "DELETE FROM tutors WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: users.php?success=Tutor and related enrollments deleted successfully");
        } else {
            error_log("Error deleting tutor: " . mysqli_error($conn));
            header("Location: users.php?error=Failed to delete tutor");
        }
        exit();
    }
    
}

// Fetch all tutors
$query = "SELECT * FROM tutors";
$result = mysqli_query($conn, $query);
$tutors = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutors - Ultrakey LMS</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/sidebar.css">
    <style>
        /* Common Action Button Styles */
        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        
        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .edit-btn {
            background-color: #ffc107;
            color: #000;
        }
        
        .delete-btn {
            background-color: #dc3545;
            color: #fff;
        }
        
        .action-btn:hover {
            opacity: 0.8;
            transform: translateY(-1px);
        }

        /* View Toggle Buttons */
        .view-toggle {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            margin:20px
        }

        .view-btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .view-btn.active {
            background: #1a69a5;
            color: white;
        }

        /* Grid View Styles */
        .grid-view {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 15px 0;
        }

        .tutor-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .tutor-card:hover {
            transform: translateY(-5px);
        }

        .tutor-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 20px auto;
            display: block;
            object-fit: cover;
            background: #f0f0f0;
        }

        .tutor-content {
            padding: 20px;
            text-align: center;
        }

        .tutor-name {
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: #1a69a5;
        }

        .tutor-info {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 15px;
        }

        .specialization-badge {
            background: #e9ecef;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            color: #495057;
            display: inline-block;
            margin-bottom: 15px;
        }

        .card-actions {
            display: flex;
            justify-content: center;
            gap: 10px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        /* Add Tutor Button */
        .add-tutor-btn {
            background: #2674b7;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .add-tutor-btn:hover {
            background: #1a5c8f;
            color: white;
        }

        /* Table Styles */
        .table th {
            background-color: #1a69a5;
            color: white;
        }

        .table-responsive {
            margin-top: 20px;
        }

        /* Hide/Show Views */
        .view-container {
            display: none;
        }

        .view-container.active {
            display: block;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .grid-view {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
            
            .action-buttons {
                flex-direction: row;
                justify-content: center;
            }
            
            .action-btn {
                padding: 8px 12px;
            }
        }

        @media (max-width: 576px) {
            .grid-view {
                grid-template-columns: 1fr;
            }
        }

        .main-content {
            padding-top: 10px; /* Reduced from default */
        }

        .container.mt-4 {
            margin-top: 0.5rem !important; /* Reduced from 1.5rem */
        }

        .mb-4 {
            margin-bottom: 0.5rem !important; /* Reduced from 1.5rem */
        }

        /* Modal Styles */
        .modal-lg {
            max-width: 900px;
        }

        .modal-content {
            border-radius: 10px;
            overflow: hidden;
        }

        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 1rem 1.5rem;
        }

        .modal-title {
            color: #1a69a5;
            font-weight: 500;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
        }

        .input-group .form-control {
            border-left: none;
        }

        .input-group .form-control:focus {
            border-color: #dee2e6;
            box-shadow: none;
        }

        .input-group-text i {
            width: 16px;
            text-align: center;
            color: #6c757d;
        }

        .modal-footer {
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
            padding: 1rem 1.5rem;
        }

        @media (max-width: 768px) {
            .modal-lg {
                max-width: 95%;
                margin: 1rem auto;
            }
            
            .col-md-6.border-end {
                border-right: none !important;
                border-bottom: 1px solid #dee2e6;
                padding-bottom: 1.5rem;
                margin-bottom: 1.5rem;
            }
        }

        /* Action Dropdown Styles */
        .dropdown-toggle::after {
            display: none;
        }

        .action-dropdown {
            position: relative;
            display: inline-block;
        }

        .btn-actions {
            background: none;
            border: none;
            padding: 8px;
            cursor: pointer;
            color: #6c757d;
            border-radius: 4px;
        }

        .btn-actions:hover {
            background-color: #f8f9fa;
            color: #0d6efd;
        }

        .dropdown-menu {
            position: absolute;
            right: 0;
            min-width: 160px;
            padding: 8px 0;
            margin: 8px 0;
            background-color: #fff;
            border: 1px solid rgba(0,0,0,.15);
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,.15);
            z-index: 1000;
            display: none;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            color: #212529;
            text-decoration: none;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .dropdown-item.delete {
            color: #dc3545;
        }

        .dropdown-item.delete:hover {
            background-color: #dc354520;
        }

        .dropdown-item i {
            width: 16px;
        }
        
        /* Add styles for clickable elements */
        .tutor-name:hover {
            color: #0d6efd;
            text-decoration: underline;
        }

        /* Card Styles */
        .card {
            border: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            margin-bottom: 24px;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(26, 35, 126, 0.15);
        }

        .card-header {
            background: linear-gradient(45deg, #1a237e, #283593);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 1.2rem 1.5rem;
            border: none;
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-title {
            font-weight: 600;
            margin-bottom: 0;
            color: #ffffff;
            font-size: 1.25rem;
        }

        /* Table inside card */
        .table {
            margin-bottom: 0;
        }

        .table thead th {
            border-top: none;
            background-color: #f8f9fa;
            color: #1a237e;
            font-weight: 600;
        }

        .table td, .table th {
            padding: 1rem;
            vertical-align: middle;
        }

        /* Buttons and actions */
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Search input styling */
        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #e0e0e0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
        }

        .form-control:focus {
            border-color: #1a237e;
            box-shadow: 0 0 0 0.2rem rgba(26, 35, 126, 0.15);
        }

        /* Grid view specific styles */
        .user-grid .card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .user-grid .card-body {
            flex: 1;
        }

        /* Animation for card appearance */
        @keyframes cardAppear {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            animation: cardAppear 0.3s ease-out forwards;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0">Tutors</h2>
                <div class="d-flex gap-3 align-items-center">
                    <div class="view-toggle">
                        <button class="view-btn active" data-view="list">
                            <i class="fas fa-list"></i> List
                        </button>
                        <button class="view-btn" data-view="grid">
                            <i class="fas fa-th"></i> Grid
                        </button>
                    </div>
                    <button class="add-tutor-btn" data-bs-toggle="modal" data-bs-target="#addTutorModal">
                        <i class="fas fa-plus"></i> Add Tutor
                    </button>
                </div>
            </div>

            <!-- Success/Error Messages -->
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- List View -->
            <div id="listView" class="view-container active">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Specialization</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tutors as $tutor): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($tutor['id']); ?></td>
                                            <td>
                                                <a href="get_tutorBio.php?id=<?php echo $tutor['id']; ?>" class="text-decoration-none text-dark">
                                                    <?php echo htmlspecialchars($tutor['full_name']); ?>
                                                </a>
                                            </td>
                                            <td><?php echo htmlspecialchars($tutor['email']); ?></td>
                                            <td><?php echo htmlspecialchars($tutor['specialization']); ?></td>
                                            <td>
                                                <div class="action-dropdown">
                                                    <button class="btn-actions" onclick="toggleDropdown(this)">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editTutorModal<?php echo $tutor['id']; ?>">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </button>
                                                        <button class="dropdown-item delete" onclick="deleteTutor(<?php echo $tutor['id']; ?>)">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grid View -->
            <div id="gridView" class="view-container">
                <div class="grid-view">
                    <?php foreach ($tutors as $tutor): 
                        // Create image path using tutor's name
                        $imageName = strtolower(str_replace(' ', '_', $tutor['full_name'])) . '.jpg';
                        $imagePath = "../assets/images/" . $imageName;
                        // Check if image exists, otherwise use default
                        $displayImage = file_exists($imagePath) ? $imagePath : "../assets/images/default-avatar.jpg";
                    ?>
                        <div class="tutor-card">
                            <div style="position: relative;">
                                <img src="<?php echo htmlspecialchars($displayImage); ?>" 
                                     alt="<?php echo htmlspecialchars($tutor['full_name']); ?>" 
                                     class="tutor-image">
                                <div class="action-dropdown" style="position: absolute; top: 10px; right: 10px;">
                                    <button class="btn-actions" onclick="toggleDropdown(this)" style="background-color: rgba(255, 255, 255, 0.9); border-radius: 50%;">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editTutorModal<?php echo $tutor['id']; ?>">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="dropdown-item delete" onclick="deleteTutor(<?php echo $tutor['id']; ?>)">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="tutor-content">
                                <a href="get_tutorBio.php?id=<?php echo $tutor['id']; ?>" class="text-decoration-none">
                                    <h3 class="tutor-name"><?php echo htmlspecialchars($tutor['full_name']); ?></h3>
                                </a>
                                <p class="tutor-info"><?php echo htmlspecialchars($tutor['email']); ?></p>
                                <span class="specialization-badge">
                                    <i class="fas fa-graduation-cap"></i>
                                    <?php echo htmlspecialchars($tutor['specialization']); ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <?php 
    // Reset the result pointer to create modals
    mysqli_data_seek($result, 0);
    while ($tutor = mysqli_fetch_assoc($result)): 
        // Create image path using tutor's name
        $imageName = strtolower(str_replace(' ', '_', $tutor['full_name'])) . '.jpg';
        $imagePath = "../assets/images/" . $imageName;
        $displayImage = file_exists($imagePath) ? $imagePath : "../assets/images/default-avatar.jpg";
    ?>
        <div class="modal fade" id="editTutorModal<?php echo $tutor['id']; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title">
                            <i class="fas fa-user-edit me-2"></i>
                            Edit Tutor: <?php echo htmlspecialchars($tutor['full_name']); ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $tutor['id']; ?>">
                            <div class="row">
                                <!-- Left Column -->
                                <div class="col-md-6 border-end">
                                    <div class="d-flex flex-column align-items-center mb-4">
                                        <img src="<?php echo $displayImage; ?>" 
                                             alt="<?php echo htmlspecialchars($tutor['full_name']); ?>" 
                                             class="rounded-circle mb-3" 
                                             style="width: 120px; height: 120px; object-fit: cover;">
                                        <h5 class="mb-0"><?php echo htmlspecialchars($tutor['full_name']); ?></h5>
                                        <small class="text-muted"><?php echo htmlspecialchars($tutor['email']); ?></small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Username</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($tutor['username']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($tutor['email']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Full Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                            <input type="text" class="form-control" name="full_name" value="<?php echo htmlspecialchars($tutor['full_name']); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Right Column -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Specialization</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-graduation-cap"></i></span>
                                            <input type="text" class="form-control" name="specialization" value="<?php echo htmlspecialchars($tutor['specialization']); ?>">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Bio</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                            <textarea class="form-control" name="bio" rows="3"><?php echo htmlspecialchars($tutor['bio']); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Resume</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-file-pdf"></i></span>
                                            <input type="file" class="form-control" name="resume">
                                        </div>
                                        <?php if (!empty($tutor['resume_path'])): ?>
                                            <div class="mt-1 d-flex align-items-center">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                <small class="text-muted">Current: <?php echo htmlspecialchars(basename($tutor['resume_path'])); ?></small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Certificate</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-certificate"></i></span>
                                            <input type="file" class="form-control" name="certificate">
                                        </div>
                                        <?php if (!empty($tutor['certificate_path'])): ?>
                                            <div class="mt-1 d-flex align-items-center">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                <small class="text-muted">Current: <?php echo htmlspecialchars(basename($tutor['certificate_path'])); ?></small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-top mt-4">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i> Cancel
                                </button>
                                <button type="submit" name="edit_tutor" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>

    <!-- Add Tutor Modal -->
    <div class="modal fade" id="addTutorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Tutor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="full_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Specialization</label>
                            <input type="text" class="form-control" name="specialization">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bio</label>
                            <textarea class="form-control" name="bio" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Resume</label>
                            <input type="file" class="form-control" name="resume">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Certificate</label>
                            <input type="file" class="form-control" name="certificate">
                        </div>
                        <button type="submit" name="add_tutor" class="btn btn-primary">Add Tutor</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and its dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // View Toggle Functionality
        const viewButtons = document.querySelectorAll('.view-btn');
        const viewContainers = document.querySelectorAll('.view-container');

        viewButtons.forEach(button => {
            button.addEventListener('click', () => {
                const view = button.dataset.view;
                
                // Update buttons
                viewButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                // Update views
                viewContainers.forEach(container => {
                    container.classList.remove('active');
                    if (container.id === view + 'View') {
                        container.classList.add('active');
                    }
                });
            });
        });

        function deleteTutor(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.innerHTML = `
                        <input type="hidden" name="delete_tutor" value="1">
                        <input type="hidden" name="id" value="${id}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Add this JavaScript function for the dropdown toggle
        function toggleDropdown(button) {
            const dropdownMenu = button.nextElementSibling;
            const allDropdowns = document.querySelectorAll('.dropdown-menu');
            
            // Close all other dropdowns
            allDropdowns.forEach(menu => {
                if (menu !== dropdownMenu) {
                    menu.classList.remove('show');
                }
            });
            
            // Toggle current dropdown
            dropdownMenu.classList.toggle('show');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.matches('.btn-actions') && !event.target.matches('.fa-ellipsis-v')) {
                const dropdowns = document.querySelectorAll('.dropdown-menu');
                dropdowns.forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
            }
        });
    </script>
</body>
</html>