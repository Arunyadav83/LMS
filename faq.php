<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQs - UltraKey Learning</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Styling -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f4f8;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
        }
        h1 {
            text-align: center;
            margin-bottom: 2rem;
            color: #2c3e50;
            font-size: 2.5rem;
        }
        .faq-list {
            display: grid;
            gap: 1.5rem;
        }
        .faq-item {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .faq-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .faq-question {
            font-size: 1.1rem;
            color: #3498db;
            padding: 1rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
        }
        .faq-question i {
            transition: transform 0.3s ease;
        }
        .faq-answer {
            font-size: 1rem;
            line-height: 1.6;
            padding: 0 1rem;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease, padding 0.5s ease;
        }
        .faq-item.active .faq-question {
            color: #2980b9;
        }
        .faq-item.active .faq-question i {
            transform: rotate(180deg);
        }
        .faq-item.active .faq-answer {
            max-height: 1000px;
            padding: 1rem;
        }
        @media (max-width: 768px) {
            .container {
                width: 90%;
                padding: 1rem;
            }
            h1 {
                font-size: 2rem;
            }
        }
    </style>
    <link rel="icon" type="image/x-icon" href="assets/images/apple-touch-icon.png">

</head>
<body>
    <!-- Include Header -->
    <?php include 'header.php'; ?>

    <!-- FAQ Section -->
    <div class="container">
        <h1>Frequently Asked Questions</h1>
        <div class="faq-list">
            <div class="faq-item">
                <div class="faq-question">
                    <span>What is the purpose of this FAQ section?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>This FAQ section is designed to address the most common questions and provide helpful answers to users. It serves as a quick reference guide to help you navigate our platform and find solutions to common inquiries.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <span>How can I contact support?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>You can contact support by visiting our <a href="contact.php">Contact Us</a> page and filling out the support form. Our dedicated team is always ready to assist you with any questions or concerns you may have.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <span>Can I contribute to the Knowledge Base?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Yes, you can contribute by submitting your articles or suggesting updates through the contribution section in your profile. We value community input and encourage users to share their expertise to enrich our knowledge base.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <span>Where can I find more detailed guides?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>More detailed guides are available in the <a href="knowledge-base.php">Knowledge Base</a> section. This comprehensive resource contains in-depth articles, tutorials, and step-by-step instructions on various topics related to our platform.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Footer -->
    <?php include 'footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const faqItems = document.querySelectorAll('.faq-item');
            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question');
                question.addEventListener('click', () => {
                    item.classList.toggle('active');
                    
                    // Close other open items
                    faqItems.forEach(otherItem => {
                        if (otherItem !== item && otherItem.classList.contains('active')) {
                            otherItem.classList.remove('active');
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>