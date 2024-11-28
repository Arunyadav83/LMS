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
                    
                    <!-- Add Tutor Form -->
                    <h2>Add New Tutor</h2>
                    <form action="" method="post" enctype="multipart/form-data" class="mb-4">
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
                        <button type="submit" name="add_tutor" class="btn btn-primary">Add Tutor</button>
                    </form>

                    <!-- Tutor List -->
                    <h2>Tutor List</h2>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Full Name</th>
                                <th>Specialization</th>
                                <th>Resume</th>
                                <th>Certificate</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tutors as $tutor): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($tutor['id']); ?></td>
                                <td><?php echo htmlspecialchars($tutor['username']); ?></td>
                                <td><?php echo htmlspecialchars($tutor['email']); ?></td>
                                <td><?php echo htmlspecialchars($tutor['full_name'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($tutor['specialization'] ?? 'N/A'); ?></td>
                                <td>
                                    <?php if (!empty($tutor['resume_path'])): ?>
                                        <a href="<?php echo $tutor['resume_path']; ?>" target="_blank">View Resume</a>
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($tutor['certificate_path'])): ?>
                                        <a href="<?php echo $tutor['certificate_path']; ?>" target="_blank">View Certificate</a>
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editTutor<?php echo $tutor['id']; ?>">
                                        Edit
                                    </button>
                                    <form action="" method="post" class="d-inline">
                                        <input type="hidden" name="id" value="<?php echo $tutor['id']; ?>">
                                        <button type="submit" name="delete_tutor" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this tutor?')">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Tutor Modal -->
                            <div class="modal fade" id="editTutor<?php echo $tutor['id']; ?>" tabindex="-1" aria-labelledby="editTutorLabel<?php echo $tutor['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editTutorLabel<?php echo $tutor['id']; ?>">Edit Tutor</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="" method="post" enctype="multipart/form-data">
                                                <input type="hidden" name="id" value="<?php echo $tutor['id']; ?>">
                                                <div class="mb-3">
                                                    <label for="edit_username<?php echo $tutor['id']; ?>" class="form-label">Username</label>
                                                    <input type="text" class="form-control" id="edit_username<?php echo $tutor['id']; ?>" name="username" value="<?php echo htmlspecialchars($tutor['username']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit_email<?php echo $tutor['id']; ?>" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="edit_email<?php echo $tutor['id']; ?>" name="email" value="<?php echo htmlspecialchars($tutor['email']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit_full_name<?php echo $tutor['id']; ?>" class="form-label">Full Name</label>
                                                    <input type="text" class="form-control" id="edit_full_name<?php echo $tutor['id']; ?>" name="full_name" value="<?php echo htmlspecialchars($tutor['full_name'] ?? ''); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit_bio<?php echo $tutor['id']; ?>" class="form-label">Bio</label>
                                                    <textarea class="form-control" id="edit_bio<?php echo $tutor['id']; ?>" name="bio" rows="3"><?php echo htmlspecialchars($tutor['bio'] ?? ''); ?></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit_specialization<?php echo $tutor['id']; ?>" class="form-label">Specialization</label>
                                                    <input type="text" class="form-control" id="edit_specialization<?php echo $tutor['id']; ?>" name="specialization" value="<?php echo htmlspecialchars($tutor['specialization'] ?? ''); ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit_resume<?php echo $tutor['id']; ?>" class="form-label">Resume</label>
                                                    <input type="file" class="form-control" id="edit_resume<?php echo $tutor['id']; ?>" name="resume">
                                                    <?php if (!empty($tutor['resume_path'])): ?>
                                                        <small>Current resume: <a href="<?php echo $tutor['resume_path']; ?>" target="_blank">View</a></small>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit_certificate<?php echo $tutor['id']; ?>" class="form-label">Certificate</label>
                                                    <input type="file" class="form-control" id="edit_certificate<?php echo $tutor['id']; ?>" name="certificate">
                                                    <?php if (!empty($tutor['certificate_path'])): ?>
                                                        <small>Current certificate: <a href="<?php echo $tutor['certificate_path']; ?>" target="_blank">View</a></small>
                                                    <?php endif; ?>
                                                </div>
                                                <button type="submit" name="edit_tutor" class="btn btn-primary">Save Changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>