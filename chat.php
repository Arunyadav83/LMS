<?php
require 'vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class ChatServer implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection
        $this->clients->attach($conn);
        echo "New connection: ({$conn->resourceId})\n";
    }

    public function onClose(ConnectionInterface $conn) {
        // Remove the connection
        $this->clients->detach($conn);
        echo "Connection closed: ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        // Forward the message to all connected clients
        foreach ($this->clients as $client) {
            // Send the message to everyone except the sender
            if ($from !== $client) {
                $client->send($msg);
            }
        }
        echo "Received message: {$msg} from ({$from->resourceId})\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        // Log the error and close connection
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }
}

// Create WebSocket server on port 8080
$server = new Ratchet\App('localhost', 8080, '0.0.0.0');
$server->route('/chat', new ChatServer(), ['*']);
echo "WebSocket server started on ws://localhost:8080/chat\n";
$server->run();
?>

 <!-- #endregion -->