<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success Stories</title>
    <style>
        /* Add your styles here */
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .card-container {
            display: flex;
            flex-wrap: wrap; /* Allow wrapping to the next line */
            justify-content: center; /* Center the cards */
            margin: 20px;
        }
        .card {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 16px;
            width: 300px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            text-align: center;
            margin: 10px; /* Add margin for spacing between cards */
        }
        .card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>

    <h1>Success Stories</h1>
    <div class="card-container">
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
        $sql = "SELECT id, name, successtory, image_path, image_path FROM student_success_stories";
        $result = $conn->query($sql);

        // Check for SQL errors
        if (!$result) {
            die("SQL Error: " . $conn->error); // Display the SQL error
        }

        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                // Construct the full image path
                $imagePath = 'uploaded_images/' . htmlspecialchars($row['image_path']); // Assuming image_path contains just the filename
                $assetImagePath = htmlspecialchars($row['image_path']); // Fetch asset image path from the database
                echo '<div class="card">';
                echo '<img src="' . $imagePath . '" alt="' . htmlspecialchars($row['name']) . '\'s Story">';
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
