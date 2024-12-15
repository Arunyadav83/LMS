<?php
// add_live_class.php

$host = "localhost";
$username = "root";
$password = "";
$database = "lms";

$conn = new mysqli($host, $username, $password, $database);

// Get POST data
$class_id = $_POST['class_id'];
$online_link = $_POST['online_link'];

if (isset($class_id) && isset($online_link)) {
    // Update the online link for the class
    $query = "UPDATE classes SET online_link = ? WHERE id = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("si", $online_link, $class_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Failed to update the live class link']);
        }
        $stmt->close();
    } else {
        echo json_encode(['error' => 'Database query failed']);
    }
} else {
    echo json_encode(['error' => 'Invalid input']);
}

$conn->close();
?>
<section id="add-live-class">
    <h2>Add Live Class Link</h2>
    <form id="add-live-class-form" action="classes.php" method="post">
        <label for="class_id">Class ID:</label>
        <input type="text" id="class_id" name="class_id" required><br><br>
        <label for="online_link">Online Link (Zoom/Meeting URL):</label>
        <input type="url" id="online_link" name="online_link" required><br><br>
        <button type="submit">Save Live Class Link</button>
    </form>
    <p id="form-message"></p> <!-- Display success or error messages here -->
</section>

<script>
    // JavaScript to handle form submission
    document.getElementById('add-live-class-form').addEventListener('submit', function(event) {
        event.preventDefault();

        const classId = document.getElementById('class_id').value;
        const onlineLink = document.getElementById('online_link').value;

        fetch('add_live_class.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'class_id=' + classId + '&online_link=' + encodeURIComponent(onlineLink)
            })
            .then(response => response.json())
            .then(data => {
                const messageElement = document.getElementById('form-message');
                if (data.success) {
                    messageElement.textContent = 'Live class link added successfully!';
                    messageElement.style.color = 'green';
                } else {
                    messageElement.textContent = 'Error: ' + (data.error || 'Failed to add live class link');
                    messageElement.style.color = 'red';
                }
            })
            .catch(error => {
                console.error('Error adding live class link:', error);
            });
    });
</script>