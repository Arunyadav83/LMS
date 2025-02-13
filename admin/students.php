<?php
session_start(); // Make sure this is at the very top
require_once '../config.php';
require_once '../functions.php';

// Check if the user is logged in as an admin
if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

// Handle status toggle
if (isset($_POST['toggle_status'])) {
    $student_id = (int)$_POST['student_id'];
    $new_status = $_POST['new_status'] === 'active' ? 1 : 0;
    
    $update_query = "UPDATE users SET is_active = $new_status WHERE id = $student_id AND role = 'student'";
    mysqli_query($conn, $update_query);
}

// Fetch all students from the database
$query = "SELECT id, username, email, created_at, is_active FROM users WHERE role = 'student'";
$result = mysqli_query($conn, $query);

// Check for query errors
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$students = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students - LMS Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation Bar -->
        <nav class="bg-blue-800 text-white shadow-md">
            <div class="container mx-auto px-4 py-3 flex justify-between items-center">
                <h1 class="text-lg font-bold">LMS Admin</h1>
                <div class="flex space-x-4">
                    <a href="#" class="text-white hover:underline">Profile</a>
                    <a href="logout.php" class="text-white hover:underline">Logout</a>
                </div>
            </div>
        </nav>

        <div class="flex flex-col lg:flex-row">
            <!-- Sidebar -->
            <aside class="bg-gray-800 text-white w-full lg:w-1/5 h-screen p-4">
                <?php include 'sidebar.php'; ?>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-6">
                <div class="container mx-auto">
                    <h1 class="text-2xl font-bold text-blue-900 mb-6">Registered Students</h1>
                    <?php if (count($students) > 0): ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-300 shadow-md">
                                <thead>
                                    <tr class="bg-blue-800 text-white">
                                        <th class="px-4 py-2 text-left">ID</th>
                                        <th class="px-4 py-2 text-left">Username</th>
                                        <th class="px-4 py-2 text-left">Email</th>
                                        <th class="px-4 py-2 text-left">Registration Date</th>
                                        <th class="px-4 py-2 text-left">Status</th>
                                        <th class="px-4 py-2 text-left">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($students as $student): ?>
                                    <tr class="border-b">
                                        <td class="px-4 py-2"><?php echo htmlspecialchars($student['id']); ?></td>
                                        <td class="px-4 py-2"><?php echo htmlspecialchars($student['username']); ?></td>
                                        <td class="px-4 py-2"><?php echo htmlspecialchars($student['email']); ?></td>
                                        <td class="px-4 py-2"><?php echo htmlspecialchars($student['created_at']); ?></td>
                                        <td class="px-4 py-2">
                                            <span class="px-2 py-1 rounded text-white <?php echo $student['is_active'] ? 'bg-green-500' : 'bg-red-500'; ?>">
                                                <?php echo $student['is_active'] ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-2">
                                            <form action="" method="post">
                                                <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                                                <input type="hidden" name="new_status" value="<?php echo $student['is_active'] ? 'inactive' : 'active'; ?>">
                                                <button type="submit" name="toggle_status" class="px-4 py-2 text-white rounded <?php echo $student['is_active'] ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600'; ?>">
                                                    <?php echo $student['is_active'] ? 'Deactivate' : 'Activate'; ?>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-red-600">No students found.</p>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.tailwindcss.com"></script>
</body>
</html>
