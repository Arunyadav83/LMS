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
            echo "<h1> Name: " . htmlspecialchars($tutor['full_name']) . "</h1>";
            echo "<h2> Specialization: " . htmlspecialchars($tutor['specialization']) . "</h2>";

            // Clean up the bio text (remove unwanted \r characters and manage newlines)
            $cleanBio = str_replace("\r", "", $tutor['bio']); // Remove all \r characters
            $cleanBio = str_replace('\r\n\r\n', "\n", $cleanBio); // Replace '\r\n\r\n' with a single newline
            $cleanBio = preg_replace('/\n{3,}/', "\n\n", $cleanBio); // Replace 3 or more consecutive newlines with 2
            $cleanBio = trim($cleanBio); // Remove leading and trailing whitespace

            // Display the cleaned bio, ensuring newlines are maintained for readability
            echo "<p>" . nl2br(htmlspecialchars($cleanBio)) . "</p>";
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
