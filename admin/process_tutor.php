<?php
// Include your database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lms";  // or the name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_tutor'])) {
    // Retrieve form data
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

    // Build SQL query dynamically
    $query = "UPDATE tutors SET username = ?, email = ?, full_name = ?, bio = ?, specialization = ?";
    $params = [$username, $email, $full_name, $bio, $specialization];

    if ($resume_path) {
        $query .= ", resume_path = ?";
        $params[] = $resume_path;
    }
    if ($certificate_path) {
        $query .= ", certificate_path = ?";
        $params[] = $certificate_path;
    }

    $query .= " WHERE id = ?";
    $params[] = $id;

    // Prepare and execute the query
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, str_repeat('s', count($params) - 1) . 'i', ...$params);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: users.php?success=Tutor updated successfully");
        exit();
    } else {
        header("Location: users.php?error=Error updating tutor: " . mysqli_error($conn));
        exit();
    }
}
?>
