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
            background-color: #f0f8ff;
            color: #333;
        }

        .help-center {
            text-align: center;
            padding: 60px 20px;
            margin-top: 78PX;
            background: linear-gradient(to right,rgb(68, 90, 107),rgb(151, 234, 239));
            color: white;
        }

        .help-center h1 {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .help-center input[type="text"] {
            width: 60%;
            max-width: 500px;
            padding: 15px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            outline: none;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin: 40px auto;
            padding: 20px;
            max-width: 1200px;
        }

        .grid-item {
            background: white;
            border-radius: 15px;
            text-align: center;
            padding: 25px 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .grid-item:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .grid-item img {
            width: 80px;
            height: 80px;
            margin-bottom: 15px;
        }

        .grid-item h2 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #4facfe;
        }

        .grid-item p {
            font-size: 14px;
            color: #555;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: white;
            font-size: 14px;
            position: relative;
        }

        footer a {
            color: #4facfe;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .help-center input[type="text"] {
                width: 80%;
            }
        }
    </style>
</head>
<?php include 'header.php'; ?>
<body>
    <div class="help-center">
        <h1>Welcome to the UltraKey Help Center</h1>
        <input
            type="text"
            id="search"
            placeholder="Search help topics, guides, and resources"
            onkeyup="searchFunction()"
        >
    </div>

    <div class="grid-container">
        <div class="grid-item" onclick="navigateTo('community')">
            <img src="assets/images/community.png" alt="Community">
            <h2>Community</h2>
            <p>Join discussions, share solutions, and gain insights from experts.</p>
        </div>
        <div class="grid-item" onclick="navigateTo('knowledge-base')">
            <img src="assets/images/knowledge-base.png" alt="Knowledge Base">
            <h2>Knowledge Base</h2>
            <p>Find articles and tutorials about UltraKey tools and features.</p>
        </div>
        <div class="grid-item" onclick="navigateTo('academy')">
            <img src="assets/images/academy.png" alt="Academy">
            <h2>Academy</h2>
            <p>Access video training and certifications to boost your knowledge.</p>
        </div>
        <div class="grid-item" onclick="navigateTo('live-support')">
            <img src="assets/images/live-support.png" alt="Live Support">
            <h2>Live Support</h2>
            <p>Get instant assistance from our professional support team.</p>
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
            const input = document.getElementById('search').value.toLowerCase();
            const items = document.querySelectorAll('.grid-item');

            items.forEach((item) => {
                const text = item.innerText.toLowerCase();
                item.style.display = text.includes(input) ? '' : 'none';
            });
        }
    </script>
</body>
</html>
