<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS</title>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            margin: 0;
            background-color: #f7f9fc;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #1e90ff;
            font-size: 36px;
            margin-bottom: 30px;
            margin: 99px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
            align-items: center;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .section img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .section img:hover {
            transform: scale(1.05);
        }

        .section h2 {
            color: #34495e;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .section p {
            font-size: 16px;
            line-height: 1.6;
            color: #555;
        }

        @media (max-width: 768px) {
            header .navbar {
                flex-direction: column;
                
            }

            .container p{
                width: 95%;

            }
            header .navbar a {
                margin: 5px 0;
            }
        }
    </style>
</head>
<body>
    <!-- Include Header -->
    <?php include 'header.php'; ?>

    <h1 style="margin: 89px;">How UltraKey IT Solutions Pvt Ltd Sets Up Classes</h1>

    <div class="container">
        <div class="section">
            <img src="assets/images/interactive-learning.jpg" alt="Interactive Learning Environment">
            <div>
                <h2>Interactive Learning Environment</h2>
                <p>
                    At UltraKey IT Solutions Pvt Ltd, classrooms are designed to foster interactive and collaborative learning. The setup includes smartboards, projectors, and high-speed internet to ensure seamless delivery of content. Students are encouraged to participate actively in discussions and problem-solving sessions.
                </p>
            </div>
        </div>

        <div class="section">
            <img src="assets/images/practical-training.jpg" alt="Hands-On Practical Training">
            <div>
                <h2>Hands-On Practical Training</h2>
                <p>
                    Practical application of concepts is a cornerstone of UltraKey's teaching methodology. Each classroom is equipped with state-of-the-art computers and software, allowing students to practice in real-time. This approach ensures students are job-ready by the time they complete their courses.
                </p>
            </div>
        </div>

        <div class="section">
            <img src="assets/images/personalized-guidance.jpg" alt="Personalized Guidance">
            <div>
                <h2>Personalized Attention and Guidance</h2>
                <p>
                    UltraKey IT Solutions maintains a low student-to-teacher ratio, enabling instructors to provide personalized attention. Regular one-on-one mentoring sessions are conducted to address individual learning needs, ensuring every student achieves their full potential.
                </p>
            </div>
        </div>

        <div class="section">
            <img src="assets/images/innovative-teaching.jpg" alt="Innovative Teaching Techniques">
            <div>
                <h2>Innovative Teaching Techniques</h2>
                <p>
                    The faculty at UltraKey incorporates innovative teaching techniques, such as flipped classrooms and gamified learning. These methods not only make learning enjoyable but also enhance the retention of concepts. Feedback is continuously collected from students to adapt and improve the teaching style.
                </p>
            </div>
        </div>

        <div class="section">
            <img src="assets/images/conducive-environment.jpg" alt="Comfortable Classroom Environment">
            <div>
                <h2>Comfortable and Conducive Environment</h2>
                <p>
                    UltraKey IT Solutions Pvt Ltd ensures that classrooms are comfortable, well-lit, and air-conditioned, creating a conducive learning environment. Ergonomic seating and a welcoming atmosphere help students stay focused and motivated throughout the sessions.
                </p>
            </div>
        </div>
    </div>

    <!-- Include Footer -->
    <?php include 'footer.php'; ?>
</body>
</html>
