<?php
// Set a cookie
if (isset($_POST['course'])) {
    $course = $_POST['course'];
    setcookie("user_course", $course, time() + (86400 * 30), "/"); // Expires in 30 days
    echo '<span style="color: black;">Cookie for course preference has been set!</span>';
}

// Check if the cookie is already set
$selectedCourse = isset($_COOKIE['user_course']) ? $_COOKIE['user_course'] : null;

// Database connection
$servername = "localhost"; // Update with your server name
$username = "root"; // Update with your database username
$password = ""; // Update with your database password
$dbname = "lms"; // Update with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch courses from the database
$courses = [];
$sql = "SELECT title FROM courses"; // Adjust the query as per your table structure
$result = $conn->query($sql);

if ($result === false) {
    // Query failed, output the error
    echo "Error: " . $conn->error;
} elseif ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $courses[] = $row['title'];
    }
} else {
    echo "No courses found.";
}
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ultrakey Learning Institute - Cookies</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #333;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        select, input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background-color: #5cb85c;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #4cae4c;
        }
        h1{
          text-align: center;
          color: #87CEEB;
        }
        p
        {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Welcome to Ultrakey Learning Institute</h1>

    <?php if ($selectedCourse): ?>
        <p>Your last selected course is: <strong><?php echo htmlspecialchars($selectedCourse); ?></strong></p>
    <?php else: ?>
        <p>You haven't selected a course yet.</p>
    <?php endif; ?>

    <form method="post" action="">
        <label for="course">Select a course:</label>
        <select name="course" id="course">
            <?php foreach ($courses as $course): ?>
                <option value="<?php echo htmlspecialchars($course); ?>"><?php echo htmlspecialchars($course); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Save Preference</button>
    </form>
</body>
</html>
