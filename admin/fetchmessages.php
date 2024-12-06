<?php
// fetchmessages.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Messages</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .message {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
        .message h2 {
            margin: 0;
            font-size: 1.5em;
            color: #333;
        }
        .message p {
            margin: 5px 0;
            color: #555;
        }
        .timestamp {
            font-size: 0.9em;
            color: #999;
        }
        .message:last-child {
            border-bottom: none;
        }
        .navbar {
            background-color: #333;
            overflow: hidden;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            padding: 10px 20px;
            
        }
        .navbar a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }
        .container h1 {
            margin-bottom: 20px;
        }
        .message {
        position: relative; /* Added for positioning delete icon */
    }
    .delete-icon {
        display: none; /* Initially hidden */
        position: absolute; /* Positioning */
        right: 10px; /* Adjust as needed */
        top: 10px; /* Adjust as needed */
        cursor: pointer;
        color: black;
        font-size: 1.5em; /* Increased font size */
         /* Default color */
    }
    .message:hover .delete-icon {
        display: block; /* Show on hover */
    }
    .delete-icon:hover {
        color: red; /* Change color on hover */
    }
    .message-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    </style>
</head>
<body>

<div class="navbar">
    <a href="index.php">Dashboard</a>
    <a href="fetchmessages.php">Messages</a>
    <a href="settings.php">Settings</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container">
    <h1>Messages</h1>
    <?php
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "lms";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch messages
    $sql = "SELECT *, created_at FROM messages ORDER BY created_at DESC";
    $result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<div class='message'>";
        echo "<h2>" . htmlspecialchars($row['name']) . "</h2>";
        echo "<p>" . htmlspecialchars($row['message']) . "</p>";
        echo "<div class='message-footer'>";
        echo "<span class='timestamp'>" . htmlspecialchars($row['email']) . "</span>";
        echo "<span class='timestamp created-at'>" . htmlspecialchars($row['created_at']) . "</span>";
        echo "<span class='delete-icon' onclick='deleteMessage(" . $row['id'] . ")'>&#128465;</span>";
        echo "</div>";
        echo "</div>";
    }
} else {
    echo "No messages found.";
}

    $conn->close();
    ?>
</div>

<script>
    function deleteMessage(id) {
        if (confirm("Are you sure you want to delete this message?")) {
            // Make an AJAX request to delete the message
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_message.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Optionally, refresh the page or remove the message from the DOM
                    location.reload(); // Reload the page
                }
            };
            xhr.send("id=" + id);
        }
    }
</script>
</body>
</html>