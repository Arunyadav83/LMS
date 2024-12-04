<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UltraKey Help Center</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            min-height:120vh
        }

        .help-center {
            text-align: center;
            padding: 50px 20px;
            background-color: #eef7ff;
        }

        .help-center h1 {
            font-size: 28px;
            font-weight: bold;
            color: #2b7dff;
            margin-bottom: 20px;
        }

        .help-center input[type="text"] {
            width: 40%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin: 50px auto;
            max-width: 1200px;
        }
        @media (max-width: 768px) {
    .grid-container {
        grid-template-columns: repeat(2, 1fr);
    }
} 
@media (max-width: 480px) {
    .grid-container {
        grid-template-columns: 1fr;
    }
} 

        .grid-item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            text-align: center;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.3s, background-color 0.3s;
        }

        .grid-item:hover {
            transform: scale(1.05);
            background-color: #e6f2ff;
        }

        .grid-item img {
            width: 100px;
            height: 100px;
            margin-bottom: 10px;
        }

        .grid-item h2 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .grid-item p {
            font-size: 14px;
            color: #666;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #2b7dff;
            color: white;
            font-size: 14px;
        }
    </style>
</head>
<?php include 'header.php'; ?>
<body>
    <div class="help-center">
        <h1>Ultrakey Help Center</h1>
        <input
            type="text"
            id="search"
            placeholder="Search all help and learning resources"
            onkeyup="searchFunction()"
        >
        <div class="grid-container">
            <div class="grid-item" onclick="navigateTo('community')">
                <img src="assets/images/community.png" alt="Community">
                <h2>Community</h2>
                <p>Start a discussion, browse solutions, and get tips from UltraKey experts.</p>
            </div>
            <div class="grid-item" onclick="navigateTo('knowledge-base')">
                <img src="assets/images/knowledge-base.png" alt="Knowledge Base">
                <h2>Knowledge Base</h2>
                <p>Read how-to articles and learn all about UltraKey tools.</p>
            </div>
            <div class="grid-item" onclick="navigateTo('academy')">
                <img src="assets/images/academy.png" alt="Academy">
                <h2>Academy</h2>
                <p>Watch video trainings and get certified in UltraKey.</p>
            </div>
            <div class="grid-item" onclick="navigateTo('live-support')">
        <img src="assets/images/live-support.png" alt="Live Support">
        <h2>Live Support</h2>
        <p>Chat with our support team for instant help and guidance.</p>
    </div>

        </div>
    </div>

   <?php include 'footer.php'; ?>

    <script>
        function navigateTo(section) {
            switch (section) {
                case 'community':
                    window.location.href = 'community.php'; // Replace with actual URL
                    break;
                case 'knowledge-base':
                    window.location.href = 'knowledge-base.php'; // Replace with actual URL
                    break;
                case 'academy':
                    window.location.href = 'academy.php'; // Replace with actual URL
                    break;
                case 'live-support':
                    window.location.href = 'live-support.php'; // Replace with actual URL
                    break;
                default:
                    console.log('Section not found');
                    break;
            }
        }

        function searchFunction() {
            let input = document.getElementById('search').value.toLowerCase();
            let items = document.querySelectorAll('.grid-item');

            items.forEach((item) => {
                const text = item.innerText.toLowerCase();
                if (text.includes(input)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
