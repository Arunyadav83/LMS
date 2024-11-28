<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQs</title>
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
        .container {
            max-width: 800px;
            margin: 1rem auto;
            padding: 1rem;
            /* background-color: white; */
            /* box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); */
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            margin-bottom: 1rem;
        }
        .faq-item {
            margin-bottom: 1.5rem;
        }
        .faq-item h3 {
            font-size: 1.2rem;
            color: #4CAF50;
            margin-bottom: 0.5rem;
            cursor: pointer;
        }
        .faq-item p {
            font-size: 1rem;
            line-height: 1.6;
            margin: 0;
            display: none; /* Hide by default */
        }
        /* Accordion Toggle Effect */
        .faq-item.active p {
            display: block; /* Show the content when active */
        }
        @media (max-width: 768px) {
            .container {
                width: 90%;
            }
        }
    </style>
    <script>
        // JavaScript to toggle FAQ items
        document.addEventListener('DOMContentLoaded', () => {
            const faqItems = document.querySelectorAll('.faq-item h3');
            faqItems.forEach(item => {
                item.addEventListener('click', () => {
                    const parent = item.parentElement;
                    parent.classList.toggle('active'); // Toggle active class
                });
            });
        });
    </script>
</head>
<body>
    <!-- Include Header -->
    <?php include 'header.php'; ?>

    <!-- FAQ Section -->
    <div class="container">
        <h1>Frequently Asked Questions</h1>
        <div class="faq-item">
            <h3>What is the purpose of this FAQ section?</h3>
            <p>This FAQ section is designed to address the most common questions and provide helpful answers to users.</p>
        </div>
        <div class="faq-item">
            <h3>How can I contact support?</h3>
            <p>You can contact support by visiting our <a href="contact.php">Contact Us</a> page and filling out the support form.</p>
        </div>
        <div class="faq-item">
            <h3>Can I contribute to the Knowledge Base?</h3>
            <p>Yes, you can contribute by submitting your articles or suggesting updates through the contribution section in your profile.</p>
        </div>
        <div class="faq-item">
            <h3>Where can I find more detailed guides?</h3>
            <p>More detailed guides are available in the <a href="knowledge-base.php">Knowledge Base</a> section.</p>
        </div>
    </div>

    <!-- Include Footer -->
    <?php include 'footer.php'; ?>
</body>
</html>
