<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            /* padding: 20px; */
            background-color: #f9f9f9;
        }
        h1 {
            text-align: center;
            color: forestgreen;
            font-size: 32px;
            margin-bottom: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            /* background: #fff; */
            padding: 4px;
            border-radius: 2px;
            /* box-shadow: 0 2px 7px rgba(0, 0, 0, 0.1); */
        }
        .section {
            margin-bottom: 20px;
        }
        .section img {
            display: block;
            max-width: 100%;
            height: auto;
            margin: 10px 0;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }
        .section img:hover {
            transform: scale(1.05);
        }
        .section h2 {
            font-size: 24px;
            color: #34495e;
            margin-bottom: 10px;
        }
     p{
       flex: 1;
       font-size: 18px;
       width: 100%;
     }
     .image-container {
        width: 300px;
        height: 200px;
        overflow: hidden;
        margin-right: 20px;
    }
    @media (max-width: 768px) {
        .section div {
            flex-direction: column;
        }
    }
    .section-content {
        display: flex;
        align-items: center;
    }
    </style>
</head>

<?php include 'header.php'; ?>
<body>
     <h1>How UltraKey IT Solutions Pvt Ltd Sets Up Classes</h1>
    <div class="container">
        <div class="section">
            <h2>Interactive Learning Environment</h2>
            <div class="section-content">
                <div class="image-container">
                    <img src="assets/images/interactive-learning.jpg" alt="Interactive Learning Environment" style="width: 100%; height: auto;">
                </div>
                <p >
                    At UltraKey IT Solutions Pvt Ltd, classrooms are designed to foster interactive and collaborative learning. The setup includes smartboards, projectors, and high-speed internet to ensure seamless delivery of content. Students are encouraged to participate actively in discussions and problem-solving sessions.
                </p>
            </div>
        </div>

        <div class="section">
            <h2>Hands-On Practical Training</h2>
            <div class="section-content">
                <div class="image-container">
                    <img src="assets/images/practical-training.jpg" alt="Hands-On Practical Training" style="width: 100%; height: auto;">
                </div>
                <p >
                    Practical application of concepts is a cornerstone of UltraKey’s teaching methodology. Each classroom is equipped with state-of-the-art computers and software, allowing students to practice in real-time. This approach ensures students are job-ready by the time they complete their courses.
                </p>
            </div>
        </div>

        <div class="section">
            <h2>Personalized Attention and Guidance</h2>
            <div class="section-content">
                <div class="image-container">
                <img src="assets/images/personalized-guidance.jpg" alt="Personalized Guidance" style="width: 100%; height: auto;">
                </div>
            <p >
                UltraKey IT Solutions maintains a low student-to-teacher ratio, enabling instructors to provide personalized attention. Regular one-on-one mentoring sessions are conducted to address individual learning needs, ensuring every student achieves their full potential.
            </p>
            <!-- <img src="assets/images/personalized-guidance.jpg" alt="Personalized Guidance" style="width: 300px; height: 200px;"> -->
        </div>

        <div class="section">
            <h2>Innovative Teaching Techniques</h2>
            <div class="section-content">
                <div class="image-container">
                <img src="assets/images/innovative-teaching.jpg" alt="Innovative Teaching Techniques" style="width: 100%; height: auto;">
            </div>
            <p >
                The faculty at UltraKey incorporates innovative teaching techniques, such as flipped classrooms and gamified learning. These methods not only make learning enjoyable but also enhance the retention of concepts. Feedback is continuously collected from students to adapt and improve the teaching style.
            </p>
            <!-- <img src="assets/images/innovative-teaching.jpg" alt="Innovative Teaching Techniques" style="width: 300px; height: 200px;"> -->
        </div>

        <div class="section">
            <h2>Comfortable and Conducive Environment</h2>
            <div class="section-content">
                <div class="image-container">
                <img src="assets/images/conducive-environment.jpg" alt="Comfortable Classroom Environment" style="width: 100%; height: auto;">
            </div>
            <p>
                UltraKey IT Solutions Pvt Ltd ensures that classrooms are comfortable, well-lit, and air-conditioned, creating a conducive learning environment. Ergonomic seating and a welcoming atmosphere help students stay focused and motivated throughout the sessions.
            </p>
            <!-- <img src="assets/images/conducive-environment.jpg" alt="Comfortable Classroom Environment" style="width: 300px; height: 200px;"> -->
        </div>
    </div>
    

    
    <?php include 'footer.php'; ?>
</body>

</html>
    
