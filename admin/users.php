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


// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'lms');
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

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

        // First, delete the related enrollments
        $enrollment_query = "DELETE FROM enrollments WHERE tutor_id = ?";
        $enrollment_stmt = mysqli_prepare($conn, $enrollment_query);
        mysqli_stmt_bind_param($enrollment_stmt, "i", $id);
        mysqli_stmt_execute($enrollment_stmt);

        // Now, delete the tutor
        $query = "DELETE FROM tutors WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: users.php?success=Tutor and related enrollments deleted successfully");
            exit();
        } else {
            header("Location: users.php?error=Error deleting tutor: " . mysqli_error($conn));
            exit();
        }
    }
}

// Fetch all tutors
$query = "SELECT * FROM tutors";
$result = mysqli_query($conn, $query);
$tutors = mysqli_fetch_all($result, MYSQLI_ASSOC);


function renderTutorsGrid($tutors)
{
    $output = '<div class="row">';

    foreach ($tutors as $tutor) {
        $output .= '<div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="card-title">' . htmlspecialchars($tutor['full_name'] ?? 'N/A') . '</h5>
                                        <p class="card-text" style="margin-inline:6%">
                                            <strong>Username:</strong> ' . htmlspecialchars($tutor['username']) . '<br>
                                            <strong>Email:</strong> ' . htmlspecialchars($tutor['email']) . '<br>
                                            <strong>Specialization:</strong> ' . htmlspecialchars($tutor['specialization'] ?? 'N/A') . '<br>
                                            <strong>Resume:</strong> ' . (!empty($tutor['resume_path']) ? '<a href="' . htmlspecialchars($tutor['resume_path']) . '" target="_blank" class="btn btn-info">View Resume</a>' : 'N/A') . '
                                        </p>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-link" type="button" id="dropdownMenuButton' . $tutor['id'] . '" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $tutor['id'] . '">
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editTutor' . $tutor['id'] . '">
                                                    <i class="fas fa-pencil-alt"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <form action="" method="post" onsubmit="return confirm(\'Are you sure you want to delete this tutor?\')">
                                                    <input type="hidden" name="id" value="' . $tutor['id'] . '">
                                                    <button type="submit" name="delete_tutor" class="dropdown-item">
                                                        <i class="fas fa-trash-alt"></i> Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';

        // Add modal for editing the tutor (inside the loop)
        $output .= '<div class="modal fade" id="editTutor' . $tutor['id'] . '" tabindex="-1" aria-labelledby="editTutorLabel' . $tutor['id'] . '" aria-hidden="true">
   <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="editTutorLabel' . $tutor['id'] . '">Edit Tutor</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="process_tutor.php" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" name="id" value="' . htmlspecialchars($tutor['id']) . '">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="' . htmlspecialchars($tutor['username']) . '" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="' . htmlspecialchars($tutor['email']) . '" required>
                </div>
                <div class="mb-3">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" value="' . htmlspecialchars($tutor['full_name']) . '" required>
                </div>
                <div class="mb-3">
                    <label for="bio" class="form-label">Bio</label>
                    <textarea class="form-control" id="bio" name="bio" rows="3" required>' . htmlspecialchars($tutor['bio'] ?? '') . '</textarea>
                </div>
                <div class="mb-3">
                    <label for="specialization" class="form-label">Specialization</label>
                    <input type="text" class="form-control" id="specialization" name="specialization" value="' . htmlspecialchars($tutor['specialization'] ?? '') . '" required>
                </div>
                <div class="mb-3">
                    <label for="resume" class="form-label">Resume (PDF)</label> 
                    <input type="file" class="form-control" id="resume" name="resume" accept=".pdf">
                </div>
                <div class="mb-3">
                    <label for="certificate" class="form-label">Certificate (Image)</label>
                    <input type="file" class="form-control" id="certificate" name="certificate" accept="image/*">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="edit_tutor" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>
</div>';
    }

    $output .= '</div>';
    return $output;
}





function renderTutorsList($tutors)
{
    $output = '<table class="table table-striped">';
    $output .= '<thead>
                    <tr>
                        <th>Tutor Name</th>
                        <th>ID</th> 
                        <th>Email</th>
                        <th>Specialization</th>
                        <th>Actions</th>
                    </tr>
                </thead>';
    $output .= '<tbody>';
    foreach ($tutors as $tutor) {
        $output .= '<tr>
                        <td>' . htmlspecialchars($tutor['full_name'] ?? 'N/A') . '</td>
                        <td>' . htmlspecialchars($tutor['id']) . '</td>
                        <td>' . htmlspecialchars($tutor['email']) . '</td>
                        <td>' . htmlspecialchars($tutor['specialization'] ?? 'N/A') . '</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary" type="button" id="dropdownMenuButton' . $tutor['id'] . '" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $tutor['id'] . '">
                                    <li>
                                        <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editTutor' . $tutor['id'] . '">
                                            <i class="fas fa-pencil-alt"></i> Edit
                                        </button>
                                    </li>
                                    <li>
                                        <form action="" method="post" class="d-inline" onsubmit="return confirm(\'Are you sure you want to delete this tutor?\')">
                                            <input type="hidden" name="id" value="' . $tutor['id'] . '">
                                            <button type="submit" name="delete_tutor" class="dropdown-item">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>';

        // Add modal for editing the tutor
        $output .= '<div class="modal fade" id="editTutor' . $tutor['id'] . '" tabindex="-1" aria-labelledby="editTutorLabel' . $tutor['id'] . '" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editTutorLabel' . $tutor['id'] . '">Edit Tutor</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                              <form action="process_tutor.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id" value="' . htmlspecialchars($tutor['id']) . '">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="' . htmlspecialchars($tutor['username']) . '" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="' . htmlspecialchars($tutor['email']) . '" required>
                    </div>
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" value="' . htmlspecialchars($tutor['full_name']) . '" required>
                    </div>
                    <div class="mb-3">
                        <label for="bio" class="form-label">Bio</label>
                        <textarea class="form-control" id="bio" name="bio" rows="3" required>' . htmlspecialchars($tutor['bio'] ?? '') . '</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="specialization" class="form-label">Specialization</label>
                        <input type="text" class="form-control" id="specialization" name="specialization" value="' . htmlspecialchars($tutor['specialization'] ?? '') . '" required>
                    </div>
                    <div class="mb-3">
                        <label for="resume" class="form-label">Resume (PDF)</label>
                        <input type="file" class="form-control" id="resume" name="resume" accept=".pdf">
                    </div>
                    <div class="mb-3">
                        <label for="certificate" class="form-label">Certificate (Image)</label>
                        <input type="file" class="form-control" id="certificate" name="certificate" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="edit_tutor" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
                            </div>
                        </div>
                    </div>';
    }

    $output .= '</tbody>';
    $output .= '</table>';
    return $output;
}



// Toggle view
$view = isset($_GET['view']) ? $_GET['view'] : 'grid';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutors - LMS Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">



</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
        margin: 0;
        padding: 0px;
    }

    /* .row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    } */

    /* Default styling for larger screens */
   /* Default styling for larger screens */
.responsive-buttons {
    margin-left: 840px;
    margin-top: -7%;
    text-align: right; /* Align to the right for larger screens */
}

/* Button spacing and styling */
.responsive-buttons a {
    margin: 5px; /* Add spacing between buttons */
    padding: 10px 20px; /* Increase clickable area */
    font-size: 14px; /* Adjust text size */
}

/* For screens between 468px and 768px */
@media (max-width: 768px) and (min-width: 468px) {
    .responsive-buttons {
        margin-left: auto; /* Center the buttons horizontally */
        margin-right: auto;
        margin-top: 10px;
        text-align: center; /* Align center for medium screens */
    }

    .responsive-buttons a {
        margin: 5px; /* Spacing between buttons */
        font-size: 16px; /* Slightly larger text */
        padding: 12px 25px; /* Adjust padding for better appearance */
    }
}

/* For screens below 468px */
@media (max-width: 467px) {
    .responsive-buttons {
        margin-left: auto;
        margin-right: auto;
        margin-top: 15px;
        text-align: center; /* Center buttons */
    }

    .responsive-buttons a {
        display: block; /* Stack buttons vertically */
        margin: 10px auto; /* Add vertical spacing */
        width: 90%; /* Full width with some margin */
        font-size: 16px; /* Increase text size for readability */
        padding: 15px; /* Larger padding for smaller screens */
    }
}


    .card {
        background-color: #ffffff;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin: 6%;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .card-body {
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;

    }

    .card-title {
        font-size: 18px;
        font-weight: bold;
        color: #333;
        margin: 0;
    }

    .card-text {
        font-size: 14px;
        color: #666;
        margin: 0;
    }

    .btn {
        font-size: 14px;
        padding: 6px 12px;
        border-radius: 5px;
        transition: background-color 0.2s, color 0.2s;
        background-color: #0433c3;
        color: white;
    }

    .btn-info {
        background-color: #17a2b8;
        color: #fff;
        text-decoration: none;
    }

    .btn-info:hover {
        background-color: #138496;
        color: #fff;
    }

    /* .dropdown {
        position: relative;
    } */
    /* 
    .dropdown .dropdown-menu {
        position: absolute;
        top: 100%;
        right: 0;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 10px 0;
        width: 200px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        display: none;
    } */
    /*                 

    /* .dropdown-item {
        padding: 10px 20px;
        font-size: 14px;
        color: #333;
        text-decoration: none;
        display: block;
    } */

    /* .dropdown-item:hover {
        background-color: #f1f1f1;
    } */

    .modal-content {
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .form-label {
        font-size: 14px;
        font-weight: bold;
        color: #333;
    }

    .form-control {
        font-size: 14px;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ddd;
    }

    .btn-primary {
        background-color: #007bff;
        color: #fff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }


    .navbar-brand {
        font-size: 20px;
        /* margin-inline-start: 20px; */
        color: white;


    }

    .nav-link {
        color: white;
        padding-inline: 20px;
        text-decoration: underline;

    }

    .nav-link:hover {
        color: white;
        /* text-decoration: underline; */
    }

    .navbar {
        background-color: #16308b;
        margin: auto;
        padding: 3px;
        /* Adjust top-bottom and left-right padding to reduce height */
        line-height: 1.2;
        /* Reduce line height for inner elements */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        /* Optional: Adds a subtle shadow for depth */
    }


    .button {
        padding-inline: 10px;
        text-decoration: none;
        color: #0433c3;
        padding-block: 10px;

    }

    .button:hover {
        background-color: #0433c3;
        color: white;
        border-radius: 30px !important;
    }
</style>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg custom-navbar" style="background-color: #1a237e;padding: 0px 3px; !important">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="container-fluid">
            <a class="navbar-brand text-light fw-bold" href="index.php">LMS Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item">
                        <a class="nav-link text-light d-flex align-items-center" href="#">
                            <i class="fas fa-user me-2"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light d-flex align-items-center" href="logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include 'sidebar.php'; ?>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="container mt-4">
                    <h1 class="mb-4" style="color:#16308b ;">Tutors</h1>

                    <!-- Add Tutor Button -->
                    <div class="mb-3">
                        <button type="button" class="btn btn-navy" data-bs-toggle="offcanvas" data-bs-target="#addTutorOffCanvas" aria-controls="addTutorOffCanvas" style="margin-bottom: 3%;">
                            Add Tutor
                        </button>
                    </div>

                    <!-- Off-Canvas for Add Tutor -->
                    <div class="offcanvas offcanvas-end" tabindex="-1" id="addTutorOffCanvas" aria-labelledby="addTutorOffCanvasLabel">
                        <div class="offcanvas-header">
                            <h5 id="addTutorOffCanvasLabel">Add New Tutor</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="bio" class="form-label">Bio</label>
                                    <textarea class="form-control" id="bio" name="bio" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="specialization" class="form-label">Specialization</label>
                                    <input type="text" class="form-control" id="specialization" name="specialization">
                                </div>
                                <div class="mb-3">
                                    <label for="resume" class="form-label">Resume</label>
                                    <input type="file" class="form-control" id="resume" name="resume">
                                </div>
                                <div class="mb-3">
                                    <label for="certificate" class="form-label">Certificate</label>
                                    <input type="file" class="form-control" id="certificate" name="certificate">
                                </div>
                                <button type="submit" name="add_tutor" class="btn btn-navy">Add Tutor</button>
                            </form>
                        </div>
                    </div>


                    <!-- Add View Toggle Buttons -->
                    <div class="mb-3 responsive-buttons">
                        <a href="?view=grid" class="btn btn-primary">
                            <i class="fa fa-th-large"></i> Grid
                        </a>
                        <a href="?view=list" class="btn btn-secondary">
                            <i class="fa fa-list"></i> List
                        </a>
                    </div>





                    <!-- Render Tutors Based on Selected View -->
                    <div class="container mt-4">
                        <!-- <h2>Tutor List</h2> -->
                        <?php
                        if ($view === 'list') {
                            echo renderTutorsList($tutors);
                        } else {
                            echo renderTutorsGrid($tutors);
                        }
                        ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>