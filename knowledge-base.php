<?php
// Sample data for articles
$articles = [
    [
        "title" => "Introduction to UltraKey Tools",
        "description" => "Learn the basics of UltraKey tools and how they help improve typing skills efficiently.",
        "link" => "article-intro-ultrakey.php",
        "image" => "assets/images/intro-ultrakey.jpg",
    ],
    [
        "title" => "Success Stories from UltraKey Users",
        "description" => "Read testimonials and stories of how UltraKey transformed students' skills.",
        "link" => "successstory.php",
        "image" => "assets/images/success-stories.jpg",
    ],
    [
        "title" => "Setting Up UltraKey in Classrooms",
        "description" => "A step-by-step guide to deploying UltraKey tools in a learning environment.",
        "link" => "article-setup-classroom.php",
        "image" => "assets/images/setup-classroom.jpg",
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knowledge Base - UltraKey Tools</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f9;
    color: #333;
}

.header h1 {
    margin: 0;
    font-size: 2.5rem;
}

.header p {
    font-size: 1.2rem;
    margin-top: 10px;
}

.container {
    width: 90%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 6px;
}

.articles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.article-card {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    width: 100%;
    height: 400px; /* Reduced height */
    display: flex;
    flex-direction: column;
}

.article-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
}

.article-card img {
    width: 100%;
    height: 150px; /* Reduced image height */
    object-fit: contain; /* Ensures the image fits well */
}

.article-content {
    padding: 15px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    justify-content: space-between;
}

.article-content h3 {
    font-size: 1.2rem; /* Adjusted size */
    margin: 0 0 10px;
}

.article-content p {
    font-size: 0.9rem; /* Adjusted size */
    color: #555;
    margin-bottom: auto;
}

.read-more {
    display: inline-block;
    margin-top: 10px;
    padding: 8px 16px;
    color: white;
    background-color: #007BFF;
    text-decoration: none;
    border-radius: 4px;
    font-size: 0.9rem;
    transition: background-color 0.3s ease;
    align-self: flex-start;
}

.read-more:hover {
    background-color: #0056b3;
}

.footer {
    text-align: center;
    padding: 20px 0;
    background-color: #007BFF;
    color: white;
    margin-top: 20px;
}

/* Responsive Design */
@media screen and (max-width: 768px) {
    .articles-grid {
        grid-template-columns: 1fr; /* One column on smaller screens */
    }

    .article-card {
        height: auto; /* Allow height to adjust dynamically */
    }

    .article-card img {
        height: 180px; /* Slightly larger image for smaller screens */
    }
}
</style>

<body>
   
<?php include 'header.php'; ?>

    <main class="container">
        <div class="articles-grid">
            <?php foreach ($articles as $article): ?>
                <div class="article-card">
                    <img src="<?= $article['image'] ?>" alt="<?= $article['title'] ?>">
                    <div class="article-content">
                        <h3><?= $article['title'] ?></h3>
                        <p><?= $article['description'] ?></p>
                        <a href="<?= $article['link'] ?>" class="read-more">Read More</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

   <?php include 'footer.php'; ?>
</body>
</html>
