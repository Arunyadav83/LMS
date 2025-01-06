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
                                        <p class="card-text">
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

    #addTutorOffCanvas {
        width: 700px;
    }

    @media (max-width: 768px) {
        .card-title {
            font-size: 1rem;
        }

        .card-text {
            font-size: 0.9rem;
        }

        .table th,
        .table td {
            font-size: 0.85rem;
        }
    }

    @media (max-width: 576px) {
        .card-title {
            font-size: 0.9rem;
        }

        .card-text {
            font-size: 0.8rem;
        }

        .dropdown-menu {
            font-size: 0.85rem;
        }

        .table th,
        .table td {
            font-size: 0.75rem;
        }

        .navbar-brand {
            font-size: 1rem;
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
                        <button type="button" class="btn btn-navy" data-bs-toggle="offcanvas" data-bs-target="#addTutorOffCanvas" aria-controls="addTutorOffCanvas">
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
                    <div class="mb-3" style="margin-left: 840px; margin-top: -7%;">
                        <a href="?view=grid" class="btn btn-primary">
                            <i class="fa fa-th-large"></i>
                        </a>
                        <a href="?view=list" class="btn btn-secondary">
                            <i class="fa fa-list"></i>
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