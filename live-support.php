<?php
// Start the session
session_start();

// Database credentials
$servername = "localhost";
$username = "root";  // Adjust based on your setup
$password = "";      // Adjust based on your setup
$dbname = "lms"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Now, your code for session and user query follows
if (!isset($_SESSION['user_id'])) {
    // Ensure the user is logged in
    die(json_encode(['success' => false, 'message' => 'User is not logged in']));
}

// Get the logged-in user's ID from the session
$logged_in_user_id = $_SESSION['user_id'];

// Fetch the logged-in user's details
$user_query = "SELECT username, email FROM users WHERE id = ?";
$user_stmt = $conn->prepare($user_query);
if (!$user_stmt) {
    die(json_encode(['success' => false, 'message' => 'SQL error (user fetch): ' . $conn->error]));
}
$user_stmt->bind_param('i', $logged_in_user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if ($user_result->num_rows === 0) {
    die(json_encode(['success' => false, 'message' => 'Logged-in user not found']));
}

$user = $user_result->fetch_assoc();
$username = $user['username'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Support</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        #support-widget {
            position: fixed;
            bottom: 0;
            right: 20px;
            z-index: 1000;
        }

        #support-button {
            background-color: #25d366;
            color: white;
            padding: 15px;
            border-radius: 50%;
            text-align: center;
            cursor: pointer;
        }

        #support-chat-box {
            width: 300px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: none;
            position: absolute;
            bottom: 60px;
            right: 0;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #ddd;
        }

        #support-header {
            background-color: #25d366;
            color: white;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #chat-area {
            padding: 10px;
            max-height: 300px;
            overflow-y: auto;
        }

        .chat-message {
            margin-bottom: 10px;
        }

        #user-message {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        #send-button {
            background-color: #25d366;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>

    <!-- Live Support Widget -->
    <div id="support-widget">
        <div id="support-chat-box">
            <div id="support-header">
                <h4>Live Support</h4>
                <button id="close-chat" onclick="toggleChat()">X</button>
            </div>
            <div id="chat-area">
                <!-- Chat messages will be dynamically inserted here -->
            </div>
            <input type="text" id="user-message" placeholder="Type your message..." onkeyup="sendMessage(event)">
            <button id="send-button" onclick="sendMessage()">Send</button>
        </div>
        <div id="support-button" onclick="toggleChat()">
            <p>Live Support</p>
        </div>
    </div>

    <script>
        // WebSocket connection
        const socket = new WebSocket('ws://localhost:8080/chat');
        socket.onopen = function() {
            console.log('WebSocket connection established');
        };

        socket.onmessage = function(event) {
            const messageData = event.data;
            const chatArea = document.getElementById('chat-area');
            chatArea.innerHTML += `<div class="chat-message"><p>${messageData}</p></div>`;
            chatArea.scrollTop = chatArea.scrollHeight; // Scroll to the bottom of the chat
        };

        // Function to toggle chat visibility
        function toggleChat() {
            const chatBox = document.getElementById('support-chat-box');
            chatBox.style.display = (chatBox.style.display === 'none' || chatBox.style.display === '') ? 'block' : 'none';
        }

        // Function to send the message to the WebSocket server
        function sendMessage(event) {
            if (event && event.key !== 'Enter') return;

            const userMessage = document.getElementById('user-message').value.trim();
            if (userMessage) {
                const chatArea = document.getElementById('chat-area');
                chatArea.innerHTML += `<div class="chat-message"><p><strong>You: </strong>${userMessage}</p></div>`;
                document.getElementById('user-message').value = '';

                // Send the message through WebSocket
                socket.send(userMessage);
            }
        }

        // Initialize chat on page load by fetching existing messages
        document.addEventListener('DOMContentLoaded', () => {
            // Optionally, you can fetch previous messages from a PHP endpoint here if needed.
        });
    </script>

</body>
</html>
