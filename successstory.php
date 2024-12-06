<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horizontal Card Layout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: forestgreen;
            font-size: 30px;
        }
        h1:hover {
            color: black;
            transition: 0.5s;
            cursor: pointer;
            font-size: 35px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            font-weight: bold;
        }
        .card-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        .card {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 16px;
            width: 300px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            text-align: center;
        }
        .card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 16px;
        }
        .card p {
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>
    
    <?php 
    include 'header.php'; 
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = ""; // Adjust your DB password
    $dbname = "lms"; // Replace with your database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch success stories
    $sql = "SELECT id, name, successtory, image_path FROM student_success_stories";
    $result = $conn->query($sql);
    ?>

    <h1>Success Stories</h1>
    <div class="card-container">
        <?php
        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo '<div class="card">';
                echo '<img src="' . htmlspecialchars($row['image_path']) . '" alt="' . htmlspecialchars($row['name']) . '\'s Story">';
                echo '<p><strong>' . htmlspecialchars($row['name']) . '</strong></p>';
                echo '<p>' . htmlspecialchars($row['successtory']) . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>No success stories found.</p>';
        }
        $conn->close();
        ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
