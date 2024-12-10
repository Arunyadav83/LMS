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

function user_exists($conn, $username, $email) {
    $query = "SELECT * FROM tutors WHERE username = ? OR email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_num_rows($result) > 0;
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_tutor']) || isset($_POST['edit_tutor'])) {
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

        if (isset($_POST['add_tutor'])) {
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
        } else {
            // This is the edit case
            $id = (int)$_POST['id'];
            $query = "UPDATE tutors SET username=?, email=?, full_name=?, bio=?, specialization=?";
            $params = [$username, $email, $full_name, $bio, $specialization];

            if ($resume_path) {
                $query .= ", resume_path=?";
                $params[] = $resume_path;
            }
            if ($certificate_path) {
                $query .= ", certificate_path=?";
                $params[] = $certificate_path;
            }
            $query .= " WHERE id=?";
            $params[] = $id;

            // Prepare and execute the update statement
            $stmt = mysqli_prepare($conn, $query);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, str_repeat('s', count($params) - 1) . 'i', ...$params);
                if (mysqli_stmt_execute($stmt)) {
                    $success = "Tutor updated successfully.";
                } else {
                    $error = "Error updating tutor: " . mysqli_error($conn);
                }
            } else {
                $error = "Error preparing statement: " . mysqli_error($conn);
            }
        }
    } elseif (isset($_POST['delete_tutor'])) {
        $id = (int)$_POST['id'];
        $query = "DELETE FROM tutors WHERE id=?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            $success = "Tutor deleted successfully.";
        } else {
            $error = "Error deleting tutor: " . mysqli_error($conn);
        }
    }
}

// Fetch all tutors
$query = "SELECT * FROM tutors";
$result = mysqli_query($conn, $query);
$tutors = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Function to render tutors in grid view
function renderTutorsGrid($tutors) {
    $output = '<div class="row">';
    foreach ($tutors as $tutor) {
        $output .= '<div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="card-title">' . htmlspecialchars($tutor['full_name'] ?? 'N/A') . '</h5>
                                        <p class="card-text">
                                            <strong>Username:</strong> ' . htmlspecialchars($tutor['username']) . '<br>
                                            <strong>Email:</strong> ' . htmlspecialchars($tutor['email']) . '<br>
                                            <strong>Specialization:</strong> ' . htmlspecialchars($tutor['specialization'] ?? 'N/A') . '<br>
                                            <strong>Resume:</strong> 
                                            ' . (!empty($tutor['resume_path']) ? '<a href="' . $tutor['resume_path'] . '" target="_blank" class="btn btn-info">View Resume</a>' : 'N/A') . '
                                        </p>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
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
                                </div>
                            </div>
                        </div>
                    </div>';
    }
    $output .= '</div>';
    return $output;
}

// Function to render tutors in list view as a table
function renderTutorsList($tutors) {
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
                                <button class="btn btn-secondary" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
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
</head>
<style>
    .row {
        margin-left: 0px;
        
    }
    .card {
        height: 200px;
    }
    .btn-navy {
        background-color: navy;
        color: white;
        border-radius: 0.6px;
        border: none;
    }
    .btn-navy:hover {
        background-color: white;
        color: black;
    }
    .table {
        background-color: transparent;
    }
</style>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">LMS Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-user"></i> Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
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
                    <h1 class="mb-4">Tutors</h1>
                    
                    <!-- Add Tutor Button -->
                    <div class="mb-3">
                        <button type="button" class="btn btn-navy" data-bs-toggle="modal" data-bs-target="#addTutorModal">
                            Add Tutor
                        </button>
                    </div>

                    <!-- Add Tutor Modal -->
                    <div class="modal fade" id="addTutorModal" tabindex="-1" aria-labelledby="addTutorModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addTutorModalLabel">Add New Tutor</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
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
                        </div>
                    </div>

                    <!-- Add View Toggle Buttons -->
                    <div class="mb-3">
                        <a href="?view=grid" class="btn btn-primary">Grid View</a>
                        <a href="?view=list" class="btn btn-secondary">List View</a>
                    </div>

                    <!-- Render Tutors Based on Selected View -->
                    <div class="container mt-4">
                        <h2>Tutor List</h2>
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