<?php
session_start();
require_once '../config.php';
require_once '../functions.php';

include 'sidebar.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lms";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get tutor ID from URL
$tutor_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch tutor details
$query = "SELECT * FROM tutors WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $tutor_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$tutor = mysqli_fetch_assoc($result);

// Check if tutor exists
if (!$tutor) {
    echo "<script>alert('Tutor not found!'); window.location.href='users.php';</script>";
    exit;
}

// Handle form submission for additional details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $achievements = mysqli_real_escape_string($conn, $_POST['achievements']);
    $experience = mysqli_real_escape_string($conn, $_POST['experience']);
    $education = mysqli_real_escape_string($conn, $_POST['education']);
    $skills = mysqli_real_escape_string($conn, $_POST['skills']);
    
    $update_query = "UPDATE tutors SET 
                    achievements = ?, 
                    experience = ?,
                    education = ?,
                    skills = ?
                    WHERE id = ?";
    
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "ssssi", $achievements, $experience, $education, $skills, $tutor_id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Tutor details updated successfully!');</script>";
        // Refresh tutor data
        $result = mysqli_query($conn, "SELECT * FROM tutors WHERE id = $tutor_id");
        $tutor = mysqli_fetch_assoc($result);
    } else {
        echo "<script>alert('Error updating tutor details!');</script>";
    }
}

// Create image path
$imageName = strtolower(str_replace(' ', '_', $tutor['full_name'])) . '.jpg';
$imagePath = "../assets/images/" . $imageName;
$displayImage = file_exists($imagePath) ? $imagePath : "../assets/images/default-avatar.jpg";
?>

<style>
.main-content {
    padding: 2rem;
    background-color: #f0f4f8;
}

.profile-header {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    color: #1a237e;
    padding: 3rem 0;
    margin-bottom: 2rem;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.profile-image {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    border: 5px solid #fff;
    box-shadow: 0 4px 15px rgba(187, 222, 251, 0.4);
    cursor: pointer;
    transition: transform 0.3s ease;
}

.profile-image:hover {
    transform: scale(1.05);
}

.profile-name {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 1rem 0;
    color: #1a237e;
}

.profile-info {
    font-size: 1.1rem;
    color: #3949ab;
}

.info-card {
    background: #ffffff;
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    border: 1px solid #e3f2fd;
    transition: transform 0.3s ease;
}

.info-card:hover {
    transform: translateY(-5px);
    border-color: #bbdefb;
}

.section-title {
    color: #1976d2;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section-title i {
    color: #64b5f6;
}

.form-control {
    border: 2px solid #e3f2fd;
    border-radius: 10px;
    padding: 0.75rem;
    transition: all 0.3s ease;
    color: #37474f;
}

.form-control:focus {
    border-color: #64b5f6;
    box-shadow: 0 0 0 0.2rem rgba(100, 181, 246, 0.25);
}

.form-control::placeholder {
    color: #90a4ae;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #64b5f6 0%, #42a5f5 100%);
    border: none;
    color: #fff;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(100, 181, 246, 0.3);
    background: linear-gradient(135deg, #42a5f5 0%, #2196f3 100%);
}

.btn-secondary {
    background: #eceff1;
    border: none;
    color: #546e7a;
}

.btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(236, 239, 241, 0.3);
    background: #cfd8dc;
    color: #37474f;
}

.document-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: rgba(255, 255, 255, 0.9);
    border: 2px solid #e3f2fd;
    border-radius: 10px;
    color: #1976d2;
    text-decoration: none;
    transition: all 0.3s ease;
}

.document-link:hover {
    background: #e3f2fd;
    border-color: #64b5f6;
    color: #1565c0;
    transform: translateY(-2px);
}

.modal-content {
    border-radius: 15px;
    overflow: hidden;
    border: none;
}

.modal-header {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    color: #1a237e;
    border: none;
    padding: 1.5rem;
}

.modal-body {
    padding: 2rem;
    background: #fff;
}

.btn-close {
    color: #1a237e;
}

@media (max-width: 768px) {
    .profile-header {
        text-align: center;
        padding: 2rem 0;
    }
    
    .profile-image {
        width: 150px;
        height: 150px;
    }
    
    .profile-name {
        font-size: 2rem;
    }
}
</style>

<div class="main-content">
    <div class="container-fluid">
        <!-- Profile Header -->
        <div class="profile-header text-center">
            <img src="<?php echo htmlspecialchars($displayImage); ?>" 
                 alt="<?php echo htmlspecialchars($tutor['full_name']); ?>"
                 class="profile-image mb-3"
                 onclick="openImageModal(this.src)">
            <h1 class="profile-name"><?php echo htmlspecialchars($tutor['full_name']); ?></h1>
            <p class="profile-info mb-2">
                <i class="fas fa-graduation-cap me-2"></i>
                <?php echo htmlspecialchars($tutor['specialization']); ?>
            </p>
            <p class="profile-info">
                <i class="fas fa-envelope me-2"></i>
                <?php echo htmlspecialchars($tutor['email']); ?>
            </p>
            
            <div class="mt-4">
                <?php if (!empty($tutor['resume_path'])): ?>
                    <a href="<?php echo htmlspecialchars($tutor['resume_path']); ?>" class="document-link me-3" target="_blank">
                        <i class="fas fa-file-pdf"></i>
                        View Resume
                    </a>
                <?php endif; ?>
                
                <?php if (!empty($tutor['certificate_path'])): ?>
                    <a href="<?php echo htmlspecialchars($tutor['certificate_path']); ?>" class="document-link" target="_blank">
                        <i class="fas fa-certificate"></i>
                        View Certificate
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Profile Details Form -->
        <form method="post">
            <div class="row">
                <div class="col-md-6">
                    <div class="info-card">
                        <h3 class="section-title">
                            <i class="fas fa-trophy"></i>
                            Achievements
                        </h3>
                        <textarea class="form-control" name="achievements" rows="4" placeholder="List your achievements..."><?php echo htmlspecialchars($tutor['achievements'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="info-card">
                        <h3 class="section-title">
                            <i class="fas fa-briefcase"></i>
                            Experience
                        </h3>
                        <textarea class="form-control" name="experience" rows="4" placeholder="Share your work experience..."><?php echo htmlspecialchars($tutor['experience'] ?? ''); ?></textarea>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="info-card">
                        <h3 class="section-title">
                            <i class="fas fa-graduation-cap"></i>
                            Education
                        </h3>
                        <textarea class="form-control" name="education" rows="4" placeholder="Enter your educational background..."><?php echo htmlspecialchars($tutor['education'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="info-card">
                        <h3 class="section-title">
                            <i class="fas fa-tools"></i>
                            Skills
                        </h3>
                        <textarea class="form-control" name="skills" rows="4" placeholder="List your key skills..."><?php echo htmlspecialchars($tutor['skills'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="users.php" class="btn btn-secondary me-3">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Profile Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="modalImage" class="img-fluid" alt="Profile Image">
            </div>
        </div>
    </div>
</div>

<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}
</script>
