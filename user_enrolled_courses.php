<?php
include 'header.php';
// Start session and include necessary files
// session_start();
// ob_start();
require_once 'config.php'; // Database connection file

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Fetch enrolled courses for the user
$query = "
    SELECT e.course_id, e.status, e.enrolled_at, c.title, c.duration, 
           t.full_name AS tutor_name
    FROM enrollments e
    JOIN courses c ON e.course_id = c.id
    JOIN tutors t ON c.tutor_id = t.id
    WHERE e.user_id = ?"; // Use placeholder

// Prepare the statement
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Query Preparation Failed: " . $conn->error);
}

// Bind the parameter
$stmt->bind_param("i", $user_id); // 'i' indicates integer

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

// Close the statement
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Enrolled Courses</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50 font-sans">
<?php include 'header.php'; ?>

<!-- Banner Section -->
<section class="bg-gradient-to-r from-blue-500 to-teal-500 py-16">
    <div class="container mx-auto text-center text-white">
        <h1 class="text-4xl font-bold mb-4">Welcome to Ultrakey Learning</h1>
        <p class="text-lg mb-6">Your journey towards mastering new skills begins here. Stay ahead with our interactive and engaging courses.</p>
        <a href="courses.php" class="inline-block bg-white text-blue-600 py-2 px-6 rounded-lg text-xl font-semibold hover:bg-gray-100 transition">Browse All Courses</a>
    </div>
</section>

<!-- Enrolled Courses Section -->
<div class="container mx-auto px-4 py-8">
    <h2 class="text-center text-3xl font-semibold text-gray-800 mb-6 mt-12">My Enrolled Courses</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php if (!empty($courses)) : ?>
            <?php foreach ($courses as $course) : ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <img
                        src="assets/images/<?php echo htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8'); ?>.jpg"
                        alt="<?php echo htmlspecialchars($course['title']); ?>"
                        class="w-full h-48 object-cover"
                    />
                    <div class="p-6">
                        <h5 class="text-lg font-semibold text-gray-800 mb-2"><?php echo htmlspecialchars($course['title']); ?></h5>
                        <p class="text-gray-600">
                            <strong>Duration:</strong> <?php echo htmlspecialchars($course['duration']); ?><br>
                            <strong>Tutor:</strong> <?php echo htmlspecialchars($course['tutor_name']); ?><br>
                            <strong>Enrolled At:</strong> <?php echo htmlspecialchars($course['enrolled_at']); ?><br>
                        </p>
                        <a href="courses.php?course_id=<?php echo $course['course_id']; ?>"
                           class="mt-4 block w-full py-2 px-4 text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">View
                           Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="text-center text-gray-600 col-span-full">You haven't enrolled in any courses yet.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>

</html>
