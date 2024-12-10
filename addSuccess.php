<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Success Story</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }
        .form-container {
            text-align: center;
            margin: 40px auto;
        }
        form {
            display: inline-block;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        form input, form textarea {
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        form button {
            background-color: forestgreen;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        form button:hover {
            background-color: darkgreen;
        }
        .image-preview {
            margin-top: 15px;
            max-width: 100%;
            max-height: 300px;
            display: none; /* Initially hidden */
        }
    </style>
    <script>
        function previewImage(event) {
            const imagePreview = document.getElementById('imagePreview');
            imagePreview.src = URL.createObjectURL(event.target.files[0]);
            imagePreview.style.display = 'block'; // Show the image
        }
    </script>
</head>
<body>
    <div class="form-container">
        <form method="POST" action="article-success-stories.php" enctype="multipart/form-data">
            <h3>Add Your Success Story</h3>
            <input type="text" name="name" placeholder="Your Name" required>
            <textarea name="successtory" placeholder="Your Success Story" rows="5" required></textarea>
            <input type="file" name="image_path" placeholder="Select an image" required onchange="previewImage(event)">
            <img id="imagePreview" class="image-preview" alt="Image Preview">
            <input type="text" name="image_alt" placeholder="Alt text for the image" required>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
