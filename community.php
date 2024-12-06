<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ultrakey Community</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    /* General Styling */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    background: #f9f9f9;
}

/* Header Section */
/* Header Section */
.community-header {
    text-align: center;
    background: #87CEEB; /* Light Pink Color */
    color: #fff;
    padding: 10px;
    margin-bottom:20px;
}


/* Community Section */
.community-section {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    padding: 20px;
}

.community-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    width: calc(25% - 20px);
    margin: 10px;
    overflow: hidden;
    text-align: center;
    transition: transform 0.3s ease-in-out;
}

.community-card:hover {
    transform: translateY(-10px);
}

.community-image {
    width: 100%;
    height: 130px;
    object-fit: cover;
    opacity: 0.85;
    transition: opacity 0.3s ease;
}

.community-card:hover .community-image {
    opacity: 1;
}

h2 {
    margin: 10px 0;
    font-size: 1.5rem;
    color: #333;
}

p {
    color: #666;
    padding: 0 10px 20px;
}

/* Footer */
.community-footer {
    text-align: center;
    background: #333;
    color: #fff;
    padding: 10px 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .community-card {
        width: calc(50% - 20px);
    }
}

@media (max-width: 480px) {
    .community-card {
        width: 100%;
    }
}

</style>
<?php include 'header.php'; ?>
<body>
    <header class="community-header">
        <h1>Welcome to the Ultrakey Community</h1>
        <p>Connect, Collaborate, and Learn with Enthusiasts Worldwide!</p>
    </header>

    <section class="community-section">
        <div class="community-card">
            <img src="assets/images/learning-group.jpg" alt="Learning Group" class="community-image">
            <h2>Learning Groups</h2>
            <p>Join focused groups to discuss topics, share resources, and grow together.</p>
        </div>
        <div class="community-card">
            <img src="assets/images/expert-talks.jpg" alt="Expert Talks" class="community-image">
            <h2>Expert Talks</h2>
            <p>Attend live sessions and Q&A with industry professionals to sharpen your skills.</p>
        </div>
        <div class="community-card">
            <img src="assets/images/projects.jpg" alt="Collaborative Projects" class="community-image">
            <h2>Collaborative Projects</h2>
            <p>Work on real-world projects with like-minded learners and showcase your skills.</p>
        </div>
        <div class="community-card">
            <img src="assets/images/resources.jpg" alt="Shared Resources" class="community-image">
            <h2>Shared Resources</h2>
            <p>Access curated materials shared by learners and mentors to boost your learning.</p>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>
