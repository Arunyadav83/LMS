
<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Ultrakey</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
    <style>
        .hero-section {
            background: linear-gradient(rgba(52, 152, 219, 0.8), rgba(46, 204, 113, 0.8));
            overflow: hidden;
            color: white;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('assets/images/pattern.png') repeat;
            opacity: 0.1;
            animation: moveBackground 20s linear infinite;
        }

        @keyframes moveBackground {
            from { background-position: 0 0; }
            to { background-position: 100% 100%; }
        }

        .hero-section .display-4 {
            font-size: 3.5rem;
            font-weight: 300;
            margin-bottom: 1.5rem;
            background: linear-gradient(90deg, #fff, #e0e0e0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .hero-section .lead {
            font-size: 1.5rem;
            color: rgba(255, 255, 255, 0.9);
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .story-section {
            padding: 100px 0;
            background: linear-gradient(135deg, #f5f7ff 0%, #ffffff 100%);
        }

        .story-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            position: relative;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .story-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .story-content {
            padding: 40px;
            position: relative;
            z-index: 1;
        }

        .story-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(33, 150, 243, 0.05), rgba(0, 188, 212, 0.05));
            z-index: -1;
        }

        .story-content h2 {
            font-size: 2.5rem;
            color: #1a237e;
            margin-bottom: 25px;
            position: relative;
            display: inline-block;
        }

        .story-content h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #00bcd4, #2196f3);
            border-radius: 2px;
            transition: width 0.3s ease;
        }

        .story-card:hover .story-content h2::after {
            width: 80%;
        }

        .story-content p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #555;
            margin-bottom: 0;
            position: relative;
            padding-left: 20px;
            border-left: 3px solid #2196f3;
        }

        .story-image-wrapper {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            margin: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .story-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .story-image-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(0, 188, 212, 0.2), rgba(33, 150, 243, 0.2));
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 1;
        }

        .story-card:hover .story-image-wrapper img {
            transform: scale(1.05);
        }

        .story-card:hover .story-image-wrapper::before {
            opacity: 1;
        }

        @media (max-width: 768px) {
            .story-section {
                padding: 60px 0;
            }

            .story-content {
                padding: 30px;
            }

            .story-content h2 {
                font-size: 2rem;
            }

            .story-image-wrapper {
                margin: 15px;
            }
        }

        .about-section {
            padding: 100px 0;
            background: #0a0a1a;
            position: relative;
            overflow: hidden;
        }

        .about-section::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, #1a1a3a 0%, transparent 50%);
            animation: rotate 30s linear infinite;
            top: -50%;
            left: -50%;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .about-section .section-title {
            color: #fff;
            font-size: 3rem;
            margin-bottom: 60px;
            position: relative;
            z-index: 1;
        }

        .about-section .section-title::after {
            background: linear-gradient(90deg, #00ff87, #60efff);
            height: 4px;
            width: 100px;
        }

        .about-card {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 40px 30px;
            position: relative;
            overflow: hidden;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1;
            backdrop-filter: blur(10px);
            min-height: 350px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .about-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.05), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s;
        }

        .about-card:hover::before {
            transform: translateX(100%);
        }

        .about-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at var(--mouse-x, 0) var(--mouse-y, 0), 
                        rgba(255, 255, 255, 0.1) 0%, 
                        transparent 50%);
            opacity: 0;
            transition: opacity 0.3s;
            z-index: -1;
        }

        .about-card:hover::after {
            opacity: 1;
        }

        .feature-icon {
            font-size: 3.5rem;
            margin-bottom: 25px;
            position: relative;
            z-index: 2;
            background: linear-gradient(135deg, #00ff87, #60efff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            transition: transform 0.3s ease;
        }

        .about-card:hover .feature-icon {
            transform: scale(1.2) rotate(5deg);
        }

        .about-card h3 {
            color: #fff;
            font-size: 1.8rem;
            margin-bottom: 20px;
            position: relative;
            z-index: 2;
        }

        .about-card p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.1rem;
            line-height: 1.6;
            position: relative;
            z-index: 2;
        }

        .about-card .glow {
            position: absolute;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(96, 239, 255, 0.3), transparent 70%);
            border-radius: 50%;
            filter: blur(20px);
            opacity: 0;
            transition: opacity 0.3s;
            pointer-events: none;
        }

        .about-card:hover .glow {
            opacity: 1;
        }

        .about-card .card-number {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 4rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.03);
            z-index: 1;
        }

        @media (max-width: 991px) {
            .about-section {
                padding: 70px 0;
            }
            
            .about-card {
                margin-bottom: 30px;
                min-height: 300px;
            }
        }

        @media (max-width: 768px) {
            .about-section .section-title {
                font-size: 2.5rem;
            }

            .about-card {
                padding: 30px 20px;
                min-height: 280px;
            }

            .feature-icon {
                font-size: 3rem;
            }

            .about-card h3 {
                font-size: 1.5rem;
            }

            .about-card p {
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .about-section {
                padding: 50px 0;
            }

            .about-section .section-title {
                font-size: 2rem;
            }
        }

        .welcome-section {
            padding: 60px 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 20px;
            overflow: hidden;
            position: relative;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('assets/images/pattern.png');
            opacity: 0.1;
            z-index: 0;
        }

        .welcome-content {
            position: relative;
            z-index: 1;
        }

        .welcome-title {
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 30px;
            position: relative;
            
        }

        .welcome-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #0d6efd 0%, #07b7ba 100%);
            border-radius: 2px;
        }

        .welcome-text {
            font-size: 1.2rem;
            line-height: 1.8;
            color: #495057;
            max-width: 800px;
            margin: 0 auto 50px;
        }

        .why-choose-title {
            color: #2c3e50;
            margin-bottom: 40px;
            font-weight: 600;
        }

        .why-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .why-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #0d6efd, #0dcaf0);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .why-card:hover {
            transform: translateY(-10px);
        }

        .why-card:hover::before {
            transform: scaleX(1);
        }

        .why-card img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 60px;
            margin-bottom: 20px;
            border: 5px solid #f8f9fa;
            transition: all 0.3s ease;
        }

        .why-card:hover img {
            transform: scale(1.1);
            border-color: #e9ecef;
        }

        .why-card h3 {
            color: #2c3e50;
            font-size: 1.5rem;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .why-card p {
            color: #6c757d;
            font-size: 1rem;
            line-height: 1.6;
            margin: 0;
        }

        @media (max-width: 768px) {
            .welcome-title {
                font-size: 2rem;
            }
            
            .welcome-text {
                font-size: 1.1rem;
                padding: 0 20px;
            }

            .why-card {
                margin: 10px;
                padding: 20px;
            }

            .why-card img {
                width: 100px;
                height: 100px;
            }
        }

        @media (max-width: 576px) {
            .welcome-section {
                padding: 40px 0;
            }

            .welcome-title {
                font-size: 1.8rem;
            }

            .why-card {
                margin: 10px 0;
            }
        }

        /* Add animation classes */
        .fade-up {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s ease;
        }

        .fade-up.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* Add these new styles for courses section */
        .courses-section {
            background: linear-gradient(135deg, #2099cf 0%, #15755f 100%);
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }
        

        .courses-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('assets/images/pattern-dots.png');
            opacity: 0.1;
            animation: moveBackground 20s linear infinite;
        }

        @keyframes moveBackground {
            from { background-position: 0 0; }
            to { background-position: 100% 100%; }
        }

        .courses-section .section-title {
            color: #fff;
            font-size: 2.5rem;
            margin-bottom: 50px;
        }

        .courses-section .section-title::after {
            background: linear-gradient(90deg, #00bcd4, #2196f3);
        }

        .course-container {
            position: relative;
            padding: 40px 0;
        }

        .course-scroll {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            gap: 30px;
            padding: 20px 40px;
            -ms-overflow-style: none;
            scrollbar-width: none;
            scroll-behavior: smooth;
        }

        .course-scroll::-webkit-scrollbar {
            display: none;
        }

        .course-card {
            flex: 0 0 350px;
            scroll-snap-align: start;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            transition: all 0.4s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transform-origin: center center;
            height: 400px; /* Fixed height */
        }

        .course-image-wrapper {
            height: 200px; /* Fixed height for image */
            overflow: hidden;
            position: relative;
        }

        .course-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .course-content {
            padding: 20px;
            height: 200px; /* Fixed height for content */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .course-title {
            color: #fff;
            font-size: 1.2rem;
            margin-bottom: 10px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .course-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .course-meta i {
            color: #00bcd4;
            margin-right: 5px;
        }

        .course-btn {
            background: linear-gradient(90deg, #00bcd4, #2196f3);
            color: #fff;
            text-decoration: none;
            padding: 8px 20px;
            border-radius: 20px;
            display: inline-block;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .course-btn:hover {
            background: linear-gradient(90deg, #2196f3, #00bcd4);
            transform: translateX(5px);
        }

        .scroll-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .scroll-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-50%) scale(1.1);
        }

        .scroll-btn.prev {
            left: -40px;
        }
        @media (max-width: 350px) {
            .scroll-btn.prev {
                left: -30px;
            }
        }
       
        .scroll-btn.next {
            right: -40px;
        }
        @media (max-width: 350px) {
            .scroll-btn.next {
            right: -30px;
        }
        }

        .scroll-indicator {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
        }

        .scroll-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .scroll-dot.active {
            background: #fff;
            transform: scale(1.2);
        }

        @media (max-width: 768px) {
            .course-card {
                flex: 0 0 300px;
                height: 380px;
            }

            .course-image-wrapper {
                height: 180px;
            }

            .course-content {
                height: 200px;
                padding: 15px;
            }
        }

        @media (max-width: 576px) {
            .course-card {
                flex: 0 0 280px;
                height: 360px;
            }

            .course-image-wrapper {
                height: 160px;
            }

            .course-content {
                height: 200px;
                padding: 15px;
            }
        }

        /* Add these new styles for tutors section */
        .tutors-section {
            background: linear-gradient(135deg, #dfdfe7 0%, #ffffff 100%);
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }

        .tutors-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('assets/images/pattern-dots.png');
            opacity: 0.1;
            animation: moveBackground 20s linear infinite reverse;
        }

        .tutors-section .section-title {
            color: #040404;
            font-size: 2.5rem;
            margin-bottom: 50px;
        }

        .tutors-section .section-title::after {
            background: linear-gradient(90deg, #ff4081, #ff6e40);
        }

        .tutor-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        @media (min-width: 992px) {
            .tutor-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .tutor-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            /* transition: all 0.4s ease; */
            border: 1px solid rgba(255, 255, 255, 0.1);
            height: 100%;
            background-color:  #2fc481;
        }

        .tutor-card:hover {
            transform: translateY(-10px) rotate(2deg);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .tutor-image-wrapper {
            position: relative;
            padding-top: 100%;
            overflow: hidden;
        }

        .tutor-image-wrapper img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .tutor-card:hover .tutor-image-wrapper img {
            transform: scale(1.1);
        }

        .tutor-image-wrapper::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 50%;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        }

        .tutor-content {
            padding: 25px;
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .tutor-name {
            color: #fff;
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .tutor-specialization {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1rem;
            margin-bottom: 20px;
            font-style: italic;
        }

        .tutor-social {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .social-icon {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            background: #ff4081;
            transform: translateY(-3px);
        }

        .view-profile-btn {
            background: linear-gradient(90deg, #ff4081, #ff6e40);
            color: #fff;
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .view-profile-btn:hover {
            background: linear-gradient(90deg, #ff6e40, #ff4081);
            transform: translateX(5px);
            color: #fff;
        }

        .tutor-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: linear-gradient(90deg, #ff4081, #ff6e40);
            color: #fff;
            padding: 5px 15px;
            border-radius: 15px;
            font-size: 0.8rem;
            z-index: 2;
        }

        @media (max-width: 768px) {
            .tutors-section {
                padding: 60px 0;
            }

            .tutor-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 20px;
                padding: 15px;
            }

            .tutor-name {
                font-size: 1.2rem;
            }
        }

        @media (max-width: 576px) {
            .tutors-section .section-title {
                font-size: 2rem;
            }

            .tutor-grid {
                grid-template-columns: 1fr;
                padding: 10px;
            }
        }

        .view-all-btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(90deg, #00bcd4, #2196f3);
            color: #fff;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .view-all-btn:hover {
            background: linear-gradient(90deg, #2196f3, #00bcd4);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(33, 150, 243, 0.3);
            color: #fff;
        }

        .view-all-btn i {
            margin-left: 8px;
            transition: transform 0.3s ease;
        }

        .view-all-btn:hover i {
            transform: translateX(5px);
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 mb-4 animate__animated animate__fadeInDown" >About Us</h1>
            <p class="lead animate__animated animate__fadeInUp">Empowering minds through innovative learning solutions</p>
        </div>
    </section>

    <!-- Our Story Section -->
    <section class="story-section">
        <div class="container">
            <div class="story-card">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="story-content">
                            <h2>Our Story</h2>
                            <p>Founded in 2023, our Learning Management System was created with a mission to democratize education and make high-quality learning accessible to everyone, everywhere. We believe that knowledge should know no boundaries.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="story-image-wrapper">
                            <img src="assets/images/innovative-teaching.jpg" alt="Our Story" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission and Vision Section -->
    <section class="mission-vision">
        <div class="container">
            <div class="row">
                <div class="col-md-4 text-center">
                    <i class="fas fa-bullseye feature-icon"></i>
                    <h3>Our Mission</h3>
                    <p>To provide transformative learning experiences that empower individuals to achieve their personal and professional goals.</p>
                </div>
                <div class="col-md-4 text-center">
                    <i class="fas fa-eye feature-icon"></i>
                    <h3>Our Vision</h3>
                    <p>To become the world's most accessible and innovative online learning platform, connecting learners with expert knowledge.</p>
                </div>
                <div class="col-md-4 text-center">
                    <i class="fas fa-globe feature-icon"></i>
                    <h3>Our Values</h3>
                    <p>Integrity, Innovation, Accessibility, Continuous Learning, and Community-Driven Education.</p>
                </div>
            </div>
        </div><br>
    </section></br>

    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <h2 class="text-center section-title">Discover Excellence in Education</h2>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="about-card text-center">
                        <div class="glow"></div>
                        <span class="card-number">01</span>
                        <i class="fas fa-graduation-cap feature-icon"></i>
                        <h3>Quality Education</h3>
                        <p>Experience top-tier education with our industry experts and comprehensive learning materials designed for your success.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="about-card text-center">
                        <div class="glow"></div>
                        <span class="card-number">02</span>
                        <i class="fas fa-users feature-icon"></i>
                        <h3>Expert Instructors</h3>
                        <p>Learn from passionate professionals who bring years of industry experience to transform your educational journey.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="about-card text-center">
                        <div class="glow"></div>
                        <span class="card-number">03</span>
                        <i class="fas fa-laptop-code feature-icon"></i>
                        <h3>Modern Learning</h3>
                        <p>Access cutting-edge tools and resources in our innovative learning environment designed for the digital age.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Welcome Message Section -->
    <section class="container mb-5">
        <div class="welcome-section">
            <div class="welcome-content">
                <h2 class="welcome-title fade-up" style="text-align: center;">Why Choose Us?</h2>
                <p class="welcome-text fade-up">
                    Your trusted partner in academic success and personal growth! At Ultrakey Learning, 
                    we're dedicated to providing quality online courses across diverse fields. Whether you're 
                    passionate about upskilling, learning a new language, or diving into programming, 
                    we have the perfect courses to fuel your success journey.
                </p>
                <div class="row justify-content-center">
                    <div class="col-md-4 col-sm-6 fade-up" style="transition-delay: 0.2s;">
                        <div class="why-card">
                            <div class="card-icon">
                                <img src='assets/images/expert_educators.png' alt='Expert Educators' class="img-fluid">
                            </div>
                            <h3>Expert Educators</h3>
                            <p>Learn from industry professionals who bring innovative teaching methodologies 
                            and real-world experience to ensure your success.</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 fade-up" style="transition-delay: 0.4s;">
                        <div class="why-card">
                            <div class="card-icon">
                                <img src='assets/images/motivators.png' alt='Motivators' class="img-fluid">
                            </div>
                            <h3>Motivating Environment</h3>
                            <p>Experience a supportive learning atmosphere that encourages continuous growth 
                            and celebrates your progress every step of the way.</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 fade-up" style="transition-delay: 0.6s;">
                        <div class="why-card">
                            <div class="card-icon">
                                <img src='assets/images/outstanding_students.png' alt='Outstanding Students' class="img-fluid">
                            </div>
                            <h3>Outstanding Results</h3>
                            <p>Join a dynamic learning ecosystem where students consistently achieve 
                            exceptional results and reach their full potential.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Courses Section -->
    <section class="courses-section">
        <div class="container">
            <h2 class="text-center section-title">Explore Our Courses</h2>
            <div class="course-container">
                <button class="scroll-btn prev" onclick="scrollCourses('prev')">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="scroll-btn next" onclick="scrollCourses('next')">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <div class="course-scroll" id="courseScroll">
                    <?php
                    class About
                    {
                        private $courses;
                        private $tutors;

                        public function __construct()
                        {
                            $this->courses = $this->fetchCoursesFromDatabase();
                            $this->tutors = $this->fetchTutorsFromDatabase();
                        }

                        private function fetchCoursesFromDatabase()
                        {
                            // Database connection parameters
                            $host = 'localhost';
                            $db = 'lms';
                            $user = 'root';
                            $pass = '';

                            try {
                                $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                $stmt = $pdo->prepare("SELECT id, title FROM courses");
                                $stmt->execute();

                                return $stmt->fetchAll(PDO::FETCH_ASSOC);
                            } catch (PDOException $e) {
                                echo "Connection failed: " . $e->getMessage();
                                return [];
                            }
                        }

                        private function fetchTutorsFromDatabase()
                        {
                            $host = 'localhost';
                            $db = 'lms';
                            $user = 'root';
                            $pass = '';

                            try {
                                $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                $stmt = $pdo->prepare("SELECT id, full_name, email, role, bio, specialization, resume_path, certificate_path, created_at FROM tutors");
                                $stmt->execute();

                                $tutors = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($tutors as &$tutor) {
                                    $image_name = strtolower(str_replace(' ', '_', $tutor['full_name'])) . '.jpg';
                                    $tutor['image'] = 'assets/images/' . $image_name;
                                }

                                return $tutors;
                            } catch (PDOException $e) {
                                echo "Connection failed: " . $e->getMessage();
                                return [];
                            }
                        }

                        public function displayCourses()
                        {
                            if ($this->courses) {
                                foreach ($this->courses as $course) {
                                    $image_name = strtolower(str_replace(' ', '_', $course['title'])) . '.jpg';
                                    echo '<div class="course-card">
                                            <div class="course-image-wrapper">
                                                <img src="assets/images/' . $image_name . '" alt="' . $course['title'] . '">
                                            </div>
                                            <div class="course-content">
                                                <h3 class="course-title">' . $course['title'] . '</h3>
                                                <div class="course-meta">
                                                    <span><i class="fas fa-clock"></i> 8 weeks</span>
                                                    <span><i class="fas fa-users"></i> 50 students</span>
                                                </div>
                                                <div class="course-action">
                                                    <a href="course.php?id=' . $course['id'] . '" class="course-btn">
                                                        Learn More <i class="fas fa-arrow-right"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>';
                                }
                            }
                        }

                        public function displayTutors()
                        {
                            if ($this->tutors) {
                                // Randomly shuffle the tutors array
                                $shuffled_tutors = $this->tutors;
                                shuffle($shuffled_tutors);
                                
                                // Take only the first 6 tutors
                                $display_tutors = array_slice($shuffled_tutors, 0, 6);

                                foreach ($display_tutors as $tutor) {
                                    echo '<div class="tutor-card">
                                            <span class="tutor-badge">Expert</span>
                                            <div class="tutor-image-wrapper">
                                                <img src="' . $tutor['image'] . '" alt="' . $tutor['full_name'] . '">
                                            </div>
                                            <div class="tutor-content">
                                                <h3 class="tutor-name">' . $tutor['full_name'] . '</h3>
                                                <p class="tutor-specialization">' . $tutor['specialization'] . '</p>
                                                <div class="tutor-social">
                                                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                                                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                                                    <a href="#" class="social-icon"><i class="fab fa-github"></i></a>
                                                </div>
                                                <button onclick="showBio(' . $tutor['id'] . ')" class="view-profile-btn">
                                                    View Profile <i class="fas fa-arrow-right"></i>
                                                </button>
                                            </div>
                                        </div>';
                                }
                            }
                        }
                    }

                    $about = new About();
                    $about->displayCourses();
                    ?>
                </div>
                <div class="scroll-indicator" id="scrollIndicator"></div>
            </div>
        </div>
    </section>

    <!-- Tutors Section -->
    <section class="tutors-section">
        <div class="container">
            <h2 class="text-center section-title">Meet Our Expert Tutors</h2>
            <div class="tutor-grid">
                <?php
                $about->displayTutors();
                ?>
            </div>
          
        </div>
    </section>

    <!-- Bootstrap and other Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.course-slider').slick({
                slidesToShow: 3,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 2000,
                arrows: true,
                dots: true,
                responsive: [{
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                    }
                }]
            });

            $('.tutors-slider').slick({
                slidesToShow: 3,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 2500,
                arrows: true,
                dots: true,
                responsive: [{
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                    }
                }]
            });
        });

        function showBio(tutorId) {
            window.open('get_tutor_bio.php?id=' + tutorId, '_blank');
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fadeElements = document.querySelectorAll('.fade-up');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                    }
                });
            }, {
                threshold: 0.1
            });

            fadeElements.forEach(element => {
                observer.observe(element);
            });
        });
    </script>

    <script>
        // Add this to your existing scripts
        document.querySelectorAll('.about-card').forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                card.style.setProperty('--mouse-x', `${x}px`);
                card.style.setProperty('--mouse-y', `${y}px`);
                
                const glow = card.querySelector('.glow');
                if (glow) {
                    glow.style.left = `${x - 50}px`;
                    glow.style.top = `${y - 50}px`;
                }
            });
        });
    </script>

    <script>
        // Add this to your existing scripts
        const courseScroll = document.getElementById('courseScroll');
        const scrollIndicator = document.getElementById('scrollIndicator');
        let isDragging = false;
        let startX;
        let scrollLeft;

        // Create scroll indicators
        function createScrollDots() {
            const cards = courseScroll.children;
            const totalDots = Math.ceil(cards.length / 3);
            
            for (let i = 0; i < totalDots; i++) {
                const dot = document.createElement('div');
                dot.className = 'scroll-dot' + (i === 0 ? ' active' : '');
                dot.onclick = () => scrollToPosition(i);
                scrollIndicator.appendChild(dot);
            }
        }

        // Scroll to specific position
        function scrollToPosition(index) {
            const cardWidth = courseScroll.children[0].offsetWidth;
            const scrollPosition = index * (cardWidth * 3 + 90);
            courseScroll.scrollTo({
                left: scrollPosition,
                behavior: 'smooth'
            });
            updateScrollDots(index);
        }

        // Update active scroll dot
        function updateScrollDots(activeIndex) {
            const dots = scrollIndicator.children;
            Array.from(dots).forEach((dot, index) => {
                dot.classList.toggle('active', index === activeIndex);
            });
        }

        // Scroll courses with buttons
        function scrollCourses(direction) {
            const scrollAmount = courseScroll.children[0].offsetWidth * 3 + 90;
            const scrollPosition = direction === 'next' 
                ? courseScroll.scrollLeft + scrollAmount 
                : courseScroll.scrollLeft - scrollAmount;
            
            courseScroll.scrollTo({
                left: scrollPosition,
                behavior: 'smooth'
            });
        }

        // Mouse drag scrolling
        courseScroll.addEventListener('mousedown', (e) => {
            isDragging = true;
            startX = e.pageX - courseScroll.offsetLeft;
            scrollLeft = courseScroll.scrollLeft;
        });

        courseScroll.addEventListener('mouseleave', () => {
            isDragging = false;
        });

        courseScroll.addEventListener('mouseup', () => {
            isDragging = false;
        });

        courseScroll.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            e.preventDefault();
            const x = e.pageX - courseScroll.offsetLeft;
            const walk = (x - startX) * 2;
            courseScroll.scrollLeft = scrollLeft - walk;
        });

        // Update scroll indicators on scroll
        courseScroll.addEventListener('scroll', () => {
            const cardWidth = courseScroll.children[0].offsetWidth;
            const currentIndex = Math.round(courseScroll.scrollLeft / (cardWidth * 3 + 90));
            updateScrollDots(currentIndex);
        });

        // Initialize scroll indicators
        createScrollDots();
    </script>
</body>
</html>

<?php
include 'footer.php';
?>
