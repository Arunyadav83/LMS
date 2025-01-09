<?php
include 'header.php';

if (isset($_GET['id'])) {
    $tutorId = $_GET['id'];

    // Database connection parameters
    $host = 'localhost';
    $db = 'lms';
    $user = 'root';
    $pass = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT full_name, bio, specialization FROM tutors WHERE id = ?");
        $stmt->execute([$tutorId]);
        $tutor = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($tutor) {
            $fullName = htmlspecialchars($tutor['full_name']);
            $specialization = htmlspecialchars($tutor['specialization']);

            // Assume image file is named based on the tutor's full name (e.g., "John_Doe.jpg")
            $imagePath = "assets/images/" . str_replace(' ', '_', $fullName) . ".jpg";

            // Check if the image file exists
            if (!file_exists($imagePath)) {
                $imagePath = "assets/images/default.jpg"; // Use a default image if none found
            }

            // Clean up the bio text (remove unwanted \r characters and manage newlines)
           $cleanBio = str_replace(["\r\n\r\n", "\r"], "\n", $tutor['bio']); // Replace '\r\n\r\n' with '\n' and remove '\r'
            $cleanBio = preg_replace('/\n{3,}/', "\n\n", $cleanBio); // Replace 3 or more consecutive newlines with 2
            $cleanBio = trim($cleanBio); // Remove leading and trailing whitespace

?>
            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Tutor Details</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        line-height: 1.6;
                        margin: 20px;
                        /* background-color: #f9f9f9; */
                    }

                    .container {
                        max-width: 800px;
                        border-radius: 10px;
                    }

                    .heading {
                        font-size: 2rem;
                        /* color: #333; */
                        margin-bottom: 20px;
                        text-align: center;
                    }

                    .tutor-details {
                        display: flex;
                        align-items: flex-start;
                        gap: 20px;
                    }

                    .tutor-info {
                        flex: 1;
                    }

                    .tutor-info h1,
                    .tutor-info h2 {
                        margin: 0;
                        color: lightseagreen;
                    }

                    .tutor-info h2 {
                        margin-top: 10px;
                        font-size: 1.2rem;
                        color: lightgrey;
                    }

                    .tutor-info p {
                        margin-top: 15px;
                        white-space: pre-wrap;
                        color: #555;
                    }

                    .tutor-image {
                        flex-shrink: 0;
                        text-align: center;
                    }

                    .tutor-image img {
                        max-width: 200px;
                        border-radius: 10px;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                    }
                </style>
            </head>

            <body>
                <div class="container">
                    <div class="heading">Tutor Bio</div>
                    <div class="tutor-details">
                        <div class="tutor-info">
                            <h1>Name: <?php echo $fullName; ?></h1>
                            <h2>Specialization: <?php echo $specialization; ?></h2>
                            <p><?php echo nl2br(htmlspecialchars($cleanBio)); ?></p>
                        </div>
                        <div class="tutor-image">
                            <img src="<?php echo $imagePath; ?>" alt="<?php echo $fullName; ?>">
                        </div>
                    </div>
                </div>
            </body>

            </html>
<?php
        } else {
            echo "<p>Tutor not found.</p>";
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
} else {
    echo "<p>No tutor ID provided.</p>";
}

include 'footer.php';
?>