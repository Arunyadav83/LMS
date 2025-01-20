<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ultrakey Learning</title>
    <!-- <link rel="stylesheet" href="style.css"> -->
    <!-- Link to Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Body and Fonts */
    body {
        font-family: 'Roboto', sans-serif;
        line-height: 1.6;
        background-color: #f0f2f5;
        color: #333;
        overflow-x: hidden;
    }

    /* Smooth Scroll */
    html {
        scroll-behavior: smooth;
    }

    /* Navbar */
    nav {
        background-color: #2d3e50;
        padding: 15px 0;
        position: fixed;
        width: 100%;
        top: 0;
        left: 0;
        z-index: 100;
    }

    nav .container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px;
    }

    nav .logo {
        color: white;
        font-size: 24px;
        font-weight: 700;
        text-decoration: none;
    }

    nav .nav-links {
        list-style-type: none;
        display: flex;
    }

    nav .nav-links li {
        margin-left: 20px;
    }

    nav .nav-links li a {
        color: white;
        text-decoration: none;
        font-weight: 500;
    }

    /* Hero Section */
    header {
        background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('assets/images/Landing.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: white;
        position: relative;
        margin: 0;
        padding: 0;
    }

    .hero-content {
        max-width: 800px;
        padding: 20px;
        animation: fadeIn 1s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .hero-content h1 {
        font-size: 50px;
        font-weight: 700;
        margin-top: 0; /* Or reduce this value */
        padding-top: 0;
    }

    .hero-content p {
        font-size: 20px;
        margin-top: 10px;
    }

    .cta-button {
        display: inline-block;
        background-color: #3498db;
        color: white;
        padding: 15px 25px;
        text-decoration: none;
        border-radius: 5px;
        font-weight: 500;
        margin-top: 20px;
    }

    .cta-button:hover {
        background-color: #2980b9;
    }

    /* About Section */
    #about {
        background-color: #fff;
        padding: 50px 0;
        text-align: center;
    }

    #about h2 {
        font-size: 32px;
        margin-bottom: 20px;
    }

    /* Courses Section */
    #courses {
        padding: 80px 0;
        background-color: #ffffff;
        text-align: center;
    }

    #courses .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    #courses h2 {
        font-size: 36px;
        margin-bottom: 40px;
        color: #1a237e;
        position: relative;
    }

    #courses h2:after {
        content: '';
        display: block;
        width: 60px;
        height: 3px;
        background: #304ffe;
        margin: 15px auto;
    }

    .course-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        margin-top: 30px;
    }

    .card {
        background-color: white;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        border: 1px solid #e0e0e0;
    }

    .card:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(to right, #304ffe, #1a237e);
    }

    .card h3 {
        font-size: 24px;
        margin-bottom: 15px;
    }

    .card p {
        font-size: 16px;
        color: #666;
    }

    .card:hover {
        transform: scale(1.05);
        transition: all 0.3s ease;
    }


        /* Testimonial Section */
        #testimonials {
            background-color: #fff;
            padding: 50px 0;
            text-align: center;
        }

        #testimonials h2 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        .testimonial-cards {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
        }

        .testimonial-card {
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            width: 250px;
            text-align: center;
        }

        .testimonial-card h4 {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .testimonial-card p {
            font-size: 14px;
            color: #666;
        }
    /* Responsive Design */
    @media (max-width: 768px) {
        .course-cards {
            grid-template-columns: 1fr;
            padding: 0 20px;
        }

        .card {
            width: 100%;
            margin-bottom: 20px;
        }

        header .hero-content h1 {
            font-size: 36px;
        }

        .hero-content p {
            font-size: 18px;
            padding: 0 20px;
        }

        .cta-button, .login-button {
            font-size: 16px;
            padding: 12px 24px;
            width: 80%;
            max-width: 300px;
            margin: 10px auto;
            display: block;
        }
    }

    @media (max-width: 480px) {
        header .hero-content h1 {
            font-size: 28px;
        }

        .hero-content p {
            font-size: 16px;
        }

        #courses h2 {
            font-size: 28px;
        }
    }

    /* Login Button */
    .login-button {
        display: inline-flex;
        align-items: center;
        background: linear-gradient(45deg, #304ffe, #1a237e);
        color: white;
        padding: 15px 30px;
        text-decoration: none;
        border-radius: 50px;
        font-weight: 600;
        margin-top: 30px;
        font-size: 18px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .login-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        background: white;
        color: #304ffe;
        border: 2px solid #304ffe;
    }

    .login-button .button-text {
        margin-right: 10px;
        /* Space between text and arrow */
    }

    .login-button .arrow {
        font-size: 20px;
        transition: transform 0.3s ease;
    }

    .login-button:hover .arrow {
        transform: translateX(5px);
        /* Arrow moves to the right on hover */
    }

    .login-button:hover {
        background-color: #c0392b;
        /* Darker shade when hovered */
    }


    /* Test Your Knowledge Section */
    #test-knowledge {
        background-color: #fff;
        padding: 50px 0;
        text-align: center;
    }

    #test-knowledge h2 {
        font-size: 32px;
        margin-bottom: 20px;
    }

    .test-button {
        background-color: #27ae60;
        color: white;
        padding: 15px 25px;
        border-radius: 5px;
        font-weight: 500;
        margin-top: 20px;
    }

    .test-button:hover {
        background-color: #2ecc71;
    }

    /* Certificate Section */
    #certificate {
        background-color: #f4f4f4;
        padding: 50px 0;
        text-align: center;
    }

    #certificate h2 {
        font-size: 32px;
        margin-bottom: 20px;
    }

    .certificate-button {
        background-color: #f39c12;
        color: white;
        padding: 15px 25px;
        border-radius: 5px;
        font-weight: 500;
        margin-top: 20px;
    }

    .certificate-button:hover {
        background-color: #e67e22;
    }
</style>

<body>
    <!-- Include Header -->
    <?php include('header.php'); ?>

    <!-- Hero Section -->
    <header id="home">
        <div class="hero-content">
            <h1>Welcome to Ultrakey Learning</h1>
            <p>Enhance your skills with our online learning platform.</p>

            <!-- Explore Courses Button -->
            <a href="#courses" class="cta-button">Explore Courses</a>

            <!-- Login Button with Arrow -->
            <a href="login.php" class="login-button">
                <span class="button-text">Login</span>
                <span class="arrow">→</span>
            </a>
        </div>
    </header>


    <!-- About Section -->
    <section id="about">
        <div class="container">
            <h2>About Ultrakey Learning</h2>
            <p>Ultrakey Learning is your one-stop destination for quality online courses in a variety of fields. Whether you're looking to upskill, learn a new language, or explore programming, we have the courses you need to succeed.</p>
        </div>
    </section>

    <!-- Courses Section -->
    <section id="courses">
        <div class="container">
            <h2>Our Courses</h2>
            <div class="course-cards">
                <div class="card">
                    <h3>Programming 101</h3>
                    <p>Learn the basics of programming with Python.</p>
                </div>
                <div class="card">
                    <h3>Data Science</h3>
                    <p>Explore the world of data with hands-on projects.</p>
                </div>
                <div class="card">
                    <h3>Web Development</h3>
                    <p>Build modern websites and applications from scratch.</p>
                </div>
            </div>
            <a href="#contact" class="cta-button">Contact Us</a>
        </div>
    </section>

    <!-- Test Your Knowledge Section -->
    <section id="test-knowledge">
        <div class="container">
            <h2>Test Your Knowledge</h2>
            <p>Take a short quiz to test your skills after completing each course.</p>
            <a href="quiz.php" class="test-button">Take Quiz</a>
        </div>
    </section>

    <!-- Certificate Section -->
    <section id="certificate">
        <div class="container">
            <h2>Earn a Certificate</h2>
            <p>After successfully completing the course, download your certificate.</p>
            <a href="certificate.php" class="certificate-button">Download Certificate</a>
        </div>
    </section>
    <!-- Testimonials Section -->
    <section id="testimonials">
        <div class="container">
            <h2>What Our Students Say</h2>
            <div class="testimonial-cards">
                <div class="testimonial-card">
                    <h4>John Doe</h4>
                    <p>"This platform helped me land my first job in tech!"</p>
                </div>
                <div class="testimonial-card">
                    <h4>Jane Smith</h4>
                    <p>"I love the variety of courses available. Highly recommended!"</p>
                </div>
            </div>
        </div>
    </section>



    <!-- Include Footer -->
    <?php include('footer.php'); ?>

</body>

</html>