<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academy - Ultrakey</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General Reset */
        body, h1, h2, h3, p {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        /* Page Wrapper */
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 3px;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            padding: 20px 20px;
            text-align: center;
            margin-top:41px;
        }

        .hero h1 {
            font-size: 2em;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 0.6em;
        }

        /* Features Section */
        .features {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-top: 30px;
        }

        .feature {
            background: #f7f7f7;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 10px;
            flex: 1 1 calc(30% - 20px);
            text-align: center;
        }

        .feature h3 {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        .feature p {
            font-size: 1em;
        }

        /* Call to Action */
        .cta {
            text-align: center;
            margin: 50px 0;
        }

        .cta button {
            background: #2575fc;
            color: white;
            border: none;
            padding: 5px 8px;
            font-size: 1.2em;
            cursor: pointer;
            border-radius: 5px;
            /* width: auto; */
        }

        .cta button:hover {
            background: #6a11cb;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2em;
            }

            .features {
                flex-direction: column;
            }

            .feature {
                flex: 1 1 100%;
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>
    
<?php include 'header.php'; ?>
    <div class="container">
        <!-- Hero Section -->
        <div class="hero">
            <h1>Welcome to Ultrakey Academy</h1>
            <p>Empowering learners to unlock their full potential with cutting-edge learning solutions.</p>
        </div>

        <!-- Features Section -->
        <div class="features">
            <div class="feature">
                <h3>Interactive Courses</h3>
                <p>Engage with hands-on activities and real-world projects tailored for your growth.</p>
            </div>
            <div class="feature">
                <h3>Expert Instructors</h3>
                <p>Learn from industry-leading professionals who guide you every step of the way.</p>
            </div>
            <div class="feature">
                <h3>Flexible Learning</h3>
                <p>Access resources anytime, anywhere, on any device. Your learning, your way.</p>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="cta">
            <button id="joinBtn">Join Now</button>
        </div>
    </div>
    <!-- JavaScript for Join Button -->
<script>
    document.getElementById('joinBtn').addEventListener('click', function () {
        // Redirect to the join page
        window.location.href = 'join.php';
    });
</script>
    <?php include 'footer.php'; ?>
</body>

</html>
