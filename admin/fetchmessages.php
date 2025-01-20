<?php
// fetchmessages.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Messages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #1a237e;
            --secondary-color: #f5f5f5;
            --accent-color: #304ffe;
            --text-color: #333;
            --shadow-color: rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--secondary-color);
            margin: 0;
            padding: 0;
            color: var(--text-color);
        }

        .navbar {
            background-color: var(--primary-color);
            padding: 1rem;
            box-shadow: 0 2px 4px var(--shadow-color);
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .navbar a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .messages-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px var(--shadow-color);
            padding: 2rem;
            margin-top: 2rem;
        }

        h1 {
            color: var(--primary-color);
            font-size: 2rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .message {
            background: var(--secondary-color);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
            position: relative;
            border-left: 4px solid var(--accent-color);
        }

        .message:hover {
            transform: translateX(5px);
            box-shadow: 0 2px 4px var(--shadow-color);
        }

        .message h2 {
            color: var(--primary-color);
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }

        .message p {
            color: var(--text-color);
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .message-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
            color: #666;
        }

        .timestamp {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .delete-icon {
            cursor: pointer;
            color: #dc3545;
            opacity: 0;
            transition: opacity 0.3s ease;
            font-size: 1.2rem;
        }

        .message:hover .delete-icon {
            opacity: 1;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0.5rem;
            }

            .messages-container {
                padding: 1rem;
            }

            .message {
                padding: 1rem;
            }

            .message-footer {
                flex-direction: column;
                gap: 0.5rem;
            }

            .delete-icon {
                opacity: 1;
                position: absolute;
                top: 1rem;
                right: 1rem;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 1.5rem;
            }

            .message h2 {
                font-size: 1.1rem;
            }

            .navbar {
                padding: 0.5rem;
            }

            .navbar a {
                padding: 0.3rem 0.6rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="container-fluid">
        <div>
            <a href="index.php"><i class="fas fa-home"></i> Dashboard</a>
            <a href="fetchmessages.php" class="active"><i class="fas fa-envelope"></i> Messages</a>
            <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
        </div>
        <div>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="messages-container">
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