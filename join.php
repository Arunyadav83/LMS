<?php include 'header.php'; ?>

<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lms"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Ultrakey Academy</title>
    <style>
        /* Global Styles */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Ensure page takes up at least full height */
            background-color: #f3f8fc;
        }

        /* Main Container */
        .main-content {
            flex: 1; /* Push footer to bottom */
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* Form Styles */
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        h1 {
            text-align: center;
            color: #6a11cb;
            margin-bottom: 20px;
        }

        fieldset {
            border: 2px solid #6a11cb;
            border-radius: 8px;
            padding: 20px;
        }

        legend {
            color: #6a11cb;
            font-size: 1.2em;
            font-weight: bold;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            background: #6a11cb;
            color: white;
            border: none;
            padding: 10px;
            margin-top: 15px;
            border-radius: 5px;
            font-size: 1em;
            width: 100%;
            cursor: pointer;
        }

        button:hover {
            background: #2575fc;
        }

        /* Footer */
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            margin-top: auto; /* Push footer to bottom */
        }

        footer a {
            color: #6a11cb;
            text-decoration: none;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .form-container {
                width: 90%;
            }

            fieldset {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Main Content -->
    <div class="main-content">
        <div class="form-container">
            <h1>Join Ultrakey Academy</h1>
            <form method="POST" action="join.php">
                <fieldset>
                    <legend>Sign Up</legend>

                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" placeholder="Enter your name" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Create a password" required>

                    <button type="submit">Join Now</button>
                </fieldset>
            </form>
        </div>
    </div>

<?php include 'footer.php'; ?>
</body>
</html>
