<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knowledge Base</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!-- Styling -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f9fc; 
             color: #333;
        }
        header {
            background-color: #4CAF50; 
            color: black; 
            padding: 1rem;
            text-align: center;
        }
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0.8rem;
            /* box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); */
            border-radius: 8px;
        }
        h1, h2 {
            text-align: center;
        }
        .article {
            margin-bottom: 1.5rem;
        }
        .article h3 {
            color: #4CAF50;
            font-size: 1.5rem;
        }
        .article p {
            font-size: 1rem;
            line-height: 1.6;
        }
        footer {
            text-align: center;
            padding: 1rem;
            background-color: #333;
            color: black;
            margin-top: 2rem;
        }
        a {
            color: #4CAF50;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- <header>
        <h1>Welcome to Our Knowledge Base</h1>
        <p>Your go-to guide for learning and troubleshooting</p>
    </header> -->
    <?php include 'header.php'; ?>

    <div class="container">
        <h2>Popular Articles</h2>
        <div class="article">
            <h3>What is a Knowledge Base?</h3>
            <p>A knowledge base is a centralized repository for information, which allows users to find answers to common questions, explore guides, and learn about a system, product, or service.</p>
        </div>
        <div class="article">
            <h3>How to Use This Knowledge Base</h3>
            <p>Navigate through categories or use the search bar to find specific topics. Each article is designed to be concise and helpful, providing the information you need.</p>
        </div>
        <div class="article">
            <h3>FAQs About Our System</h3>
            <p>Check out our <a href="faq.php">FAQ section</a> to find answers to the most frequently asked questions.</p>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
