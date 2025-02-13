<?php
include 'header.php';
require_once 'config.php';
require_once 'functions.php';


// // Fetch counts from the database
// $students_count_result = mysqli_query($conn, "SELECT COUNT(DISTINCT user_id) as count FROM enrollments WHERE user_id IS NOT NULL");
// if (!$students_count_result) {
//     die('Query Error: ' . mysqli_error($conn)); // Error handling
// }
// $students_count = mysqli_fetch_assoc($students_count_result)['count'];

// $courses_count_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM courses");
// if (!$courses_count_result) {
//     die('Query Error: ' . mysqli_error($conn)); // Error handling
// }
// $courses_count = mysqli_fetch_assoc($courses_count_result)['count'];

// $tutors_count_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM tutors");
// if (!$tutors_count_result) {
//     die('Query Error: ' . mysqli_error($conn)); // Error handling
// }
// $tutors_count = mysqli_fetch_assoc($tutors_count_result)['count'];
// 
?>

<!-- Hero Section with Parallax Effect -->
<!-- <div class="hero-section text-center py-5 mb-5 parallax-window" data-parallax="scroll" data-image-src="assets/images/hero-bg.jpg" style="background-size: cover; background-position: center;">
    <div class="container">
        <h1 class="display-4 mb-4 animate__animated animate__fadeInDown">Welcome to Ultrakey Learning</h1>
        <p class="lead mb-4 animate__animated animate__fadeInUp">Empower your future with our cutting-edge online courses</p>
        <php if (!is_logged_in()): ?>
            <a href="register.php" class="btn btn-primary btn-lg me-2 animate__animated animate__fadeInLeft">Get Started</a>
            <a href="login.php" class="btn btn-outline-light btn-lg animate__animated animate__fadeInRight">Login</a>
        <php endif; ?>
    </div>
</div> -->
<!-- Hero Section with Parallax Effect -->
<style>
    @media (max-width: 768px) {
        .course-card {
            width: 100%;
            margin-bottom: 20px;
        }

        .course-nav-btn {
            width: 30px;
            height: 30px;
            font-size: 14px;
        }
    }

    /* Adjust font sizes for smaller screens */
    @media (max-width: 576px) {
        h1 {
            font-size: 2rem;
        }

        h2 {
            font-size: 1.75rem;
        }

        p {
            font-size: 0.9rem;
        }
    }

    @media (max-width: 768px) {
        .course-nav-btn {
            width: 30px;
            height: 30px;
            font-size: 14px;
        }
    }

    @media (max-width: 768px) {
        .hero-section {
            padding: 50px 0;
        }

        .hero-section h1 {
            font-size: 2rem;
        }

        .hero-section p {
            font-size: 1rem;
        }
    }

    @media (max-width: 768px) {
        .testimonials .col-md-4 {
            margin-bottom: 20px;
        }
    }

    @media (max-width: 768px) {
        .footer {
            text-align: center;
        }

        .footer .col-md-4 {
            margin-bottom: 20px;
        }
    }

    #explore {
        background-color: rgb(180, 218, 228);
        /* Light Cyan */
        padding: 20px;
        border-radius: 10px;
        width: 100%;
    }

    .hero-section {
        background-image: url('assets/images/hero-bg.jpg');
        background-size: cover;
        background-position: center;
        position: relative;
    }

    .testimonials {
        box-shadow: 0px 2px 10px grey;
    }

    .stats-counter {
        background: linear-gradient(135deg, rgb(166, 208, 250), rgb(124, 244, 224));
        /* Blue and Green Gradient */
        color: black;
        /* Ensures text remains readable */
        padding: 50px 0;
        border-radius: 10px;
        /* Optional: Adds rounded corners for a modern touch */
    }


    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(100, 113, 122, 0.5);
        /* Adjust opacity here */
        z-index: 1;
    }

    .container {
        position: relative;
        z-index: 2;

        /* Ensure content appears above the overlay */
    }

    .img-fluid {
        max-width: 100%;
        height: auto;
        /* Maintain aspect ratio */
        display: block;
        /* Removes extra space below the image */
        margin: 0 auto;
        /* Center the image */
    }

    
    /* General styles */
    .a {
        margin-right: 23%;
        /* Apply the margin-right for larger screens */
    }

    /* Adjustments for Mobile View */
    @media (max-width: 768px) {
        .a {
            margin-right: 5%;
            /* Reduce the margin-right on smaller screens */
        }
    }

    :root {
        --primary-color: #2563eb;
        --secondary-color: #1e40af;
        --text-color: #1f2937;
        --light-bg: #f3f4f6;
        --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: var(--light-bg);
        color: var(--text-color);
    }

    .container-fluid {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }

    .section-title {
        text-align: center;
        font-size: 2.5rem;
        margin-bottom: 2rem;
        color: var(--text-color);
    }

    /* Course Tabs */
    .course-tabs {
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 2rem;
    }

    .tab-btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 0.5rem;
        background-color: white;
        color: var(--text-color);
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .tab-btn i {
        font-size: 1.1rem;
    }

    .tab-btn.active {
        background-color: var(--primary-color);
        color: white;
    }

    /* Course Slider */
    .course-slider {
        position: relative;
        padding: 0 3rem;
    }

    .slider-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        background-color: white;
        border: none;
        box-shadow: var(--card-shadow);
        cursor: pointer;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .slider-btn.prev {
        left: 0;
    }

    .slider-btn.next {
        right: 0;
    }

    .course-container {
        display: flex;
        gap: 1.5rem;
        overflow-x: hidden;
        scroll-behavior: smooth;
        padding: 1rem 0;
    }

    /* Course Card */
    .course-card {
        min-width: 300px;
        background: white;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: var(--card-shadow);
        transition: transform 0.3s ease;
    }

    .course-card:hover {
        transform: translateY(-5px);
    }

    .card-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .card-content {
        padding: 1.5rem;
    }

    .card-title {
        font-size: 1.25rem;
        margin-bottom: 0.75rem;
        color: var(--text-color);
    }

    .card-description {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 1rem;
    }

    .card-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
    }

    .course-rating {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .stars {
        color: #fbbf24;
    }

    .rating-count {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .course-price {
        font-weight: bold;
        color: var(--primary-color);
    }

    .enroll-btn {
        width: 100%;
        padding: 0.75rem;
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .enroll-btn:hover {
        background-color: var(--secondary-color);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 1rem;
        }

        .section-title {
            font-size: 2rem;
        }

        .course-tabs {
            gap: 0.5rem;
        }

        .tab-btn {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .course-slider {
            padding: 0 2rem;
        }

        .slider-btn {
            width: 2rem;
            height: 2rem;
        }

        .course-card {
            min-width: 250px;
        }
    }

    @media (max-width: 480px) {
        .section-title {
            font-size: 1.75rem;
        }

        .course-tabs {
            flex-direction: column;
            align-items: stretch;
        }

        .tab-btn {
            width: 100%;
            justify-content: center;
        }

        .course-slider {
            padding: 0 1.5rem;
        }

        .course-card {
            min-width: 200px;
        }
    }

    .card course-car {
        width: 200px;
        height: 100px;
        object-fit: cover;
    }

    .course-tabs {
        margin-bottom: 2rem;
    }

    .course-tabs .nav-link1 {
        padding: 0.8rem 1.5rem;
        border-radius: 25px;
        margin: 0 0.5rem;
        color: #495057;
        font-weight: 500;
        transition: all 0.3s ease;
        background-color: black;
    }

    .course-tabs .nav-link1:hover {
        background-color: #3498db;
        transform: translateY(-2px);

    }

    .course-tabs .nav-link1.active {
        background: linear-gradient(45deg, #007bff, #00c6ff);
        color: white;
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.2);
    }

    .course-tabs .nav-link1 i {
        margin-right: 8px;
    }

    /* Card Styles */
    .course-card {
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        margin-bottom: 2rem;
        border: none;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .course-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }

    .course-card img {
        border-radius: 10px;
        height: 200px;
        object-fit: cover;
        width: 100%;
    }

    .course-card .card-body {
        padding: 1.5rem;
    }

    .course-card .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #2d3436;
    }

    .course-card .card-text {
        color: #636e72;
        font-size: 0.9rem;
        line-height: 1.6;
    }

    /* Tab Content Animation */
    .tab-pane {
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    

    /* Enhanced Tab Styles */
    .course-tabs .nav-link1 {
        position: relative;
        overflow: hidden;
    }

    .course-tabs .nav-link1::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, #007bff, #00c6ff);
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: -1;
        border-radius: 25px;
    }

    /* .course-tabs .nav-link:hover::before {
    opacity: 0.1;
} */

    /* .course-tabs .nav-link.active::before {
    opacity: 1;
} */

    /* Enhanced Card Styles */
    .course-card {
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .course-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, rgba(0, 123, 255, 0.1), rgba(0, 198, 255, 0.1));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    /* .course-card:hover::after {
    opacity: 1;
} */

    /* Ripple Effect */
    /* .ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255,255,255,0.7);
    transform: scale(0);
    animation: ripple 0.6s linear;
    pointer-events: none;
} */

    /* @keyframes ripple {
    to {
        transform: scale(4);
        opacity: 0;
    }
} */

    /* Tab Content Transition */
    /* .tab-pane {
    transition: opacity 0.3s ease-in-out;
}

.tab-pane.fade {
    opacity: 0;
}

.tab-pane.fade.show {
    opacity: 1;
} */

    /* Course Card Image Hover Effect */
    /* .course-card img {
    transition: transform 0.5s ease;
} */

    /* .course-card:hover img {
    transform: scale(1.05);
} */

    /* Course Card Content Hover Effect */
    .course-card .card-body {
        position: relative;
        z-index: 1;
    }

    /* .course-card:hover .card-title {
    color: #007bff;
} */

    /* Loading Spinner */
    .spinner-border {
        width: 3rem;
        height: 3rem;
    }

    /* Navigation Buttons */
    .course-nav-buttons {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 100%;
        z-index: 10;
        pointer-events: none;
        display: flex;
        justify-content: space-between;
        padding: 0 10px;
    }

    .course-nav-btn {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: rgba(0, 123, 255, 0.9);
        border: 2px solid white;
        color: white;
        font-size: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        pointer-events: auto;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        opacity: 0.7;
        margin-right: 50px;
    }

    .course-nav-btn:hover {
        background: rgba(0, 123, 255, 1);
        transform: scale(1.1);
        opacity: 1;
    }

    .course-nav-btn:disabled {
        background: rgba(0, 0, 0, 0.2);
        cursor: not-allowed;
        opacity: 0.5;
    }

    .course-nav-btn.prev {
        left: 10px;
        padding-right: 3px;
    }

    .course-nav-btn.next {
        right: 10px;
        padding-left: 3px;
    }

    .course-container {
        position: relative;
        overflow: hidden;
        padding: 0 50px;
        margin: 20px 0;
    }

    .course-wrapper {
        display: flex;
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        gap: 20px;
        padding: 10px 0;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .course-nav-btn {
            width: 35px;
            height: 35px;
            font-size: 16px;
        }

        .course-container {
            padding: 0 20px;
        }

        .course-nav-btn.prev {
            left: 5px;
        }

        .course-nav-btn.next {
            right: 5px;
        }
    }

    /* Card Flip Effect */
    .course-card {
        perspective: 1000px;
        height: 450px;
        margin-bottom: 30px;
    }

    .card-inner {
        position: relative;
        width: 100%;
        height: 100%;
        text-align: center;
        transition: transform 0.6s;
        transform-style: preserve-3d;
        cursor: pointer;
    }

    .course-card:hover .card-inner {
        transform: rotateY(180deg);
    }

    .card-front,
    .card-back {
        position: absolute;
        width: 100%;
        height: 100%;
        backface-visibility: hidden;
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .card-front {
        background: white;
    }

    .card-back {
        background: #f8f9fa;
        transform: rotateY(180deg);
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .course-details {
        text-align: left;
    }

    .course-price {
        font-size: 24px;
        color: #28a745;
        font-weight: bold;
        margin: 15px 0;
    }

    .course-features {
        list-style: none;
        padding: 0;
        margin: 15px 0;
    }

    .course-features li {
        margin: 10px 0;
        color: #495057;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .course-features i {
        color: #28a745;
    }

    .enroll-btn {
        background: #007bff;
        color: white;
        border: none;
        padding: 12px 15px;
        border-radius: 25px;
        margin-top: 20px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;

    }

    .enroll-btn:hover {
        background: #0056b3;
        transform: scale(1.05);
    }

    .card-front .card-body {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .card-front img {
        height: 200px;
        object-fit: cover;
        border-radius: 15px 15px 0 0;
    }

    .course-meta {
        margin-top: auto;
        padding-top: 15px;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
    }

    .stars i {
        color: #FFD700;
        /* Changed to gold color */
        font-size: 14px;
    }

    .btn-enroll {
        background: linear-gradient(45deg, #007bff, #00c6ff);
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        border: none;
        padding: 10px 25px;
        border-radius: 25px;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        border: none;
    }

    .btn-enroll:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        background: linear-gradient(45deg, #2ec57f, #007bff);
        color: white;
    }

    .card-footer .btn {
        padding: 10px 25px;
        border-radius: 25px;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        border: none;
    }

    .btn-long-in {
        background: linear-gradient(45deg, #007bff, #00c6ff);
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        border: none;
        padding: 10px 25px;
        border-radius: 25px;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        border: none;
    }

    .btn-long-in:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        background: linear-gradient(45deg, #2ec57f, #007bff);
        color: white;
    }

    .card-footer .btn-primary {
        background: #3498db;
        color: white;
        min-width: 140px;
        text-align: center;
    }

    .card-footer .btn-success {
        background: #2ecc71;
        color: white;
        min-width: 140px;
        text-align: center;
    }

    .card-footer .btn-warning {
        background: #f1c40f;
        color: white;
        min-width: 140px;
        text-align: center;
    }

    .card-footer .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .featured-courses {
        padding: 60px 0;
        background: #f8f9fa;
    }

    .course-card-new {
        border: none;
        border-radius: 20px;
        transition: all 0.4s ease;
        background: white;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
    }

    .course-card-new::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(90deg, #2099cf, #15755f);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.4s ease;
    }

    .course-card-new:hover::before {
        transform: scaleX(1);
    }

    .course-card-new:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(32, 153, 207, 0.15);
    }

    .course-card-new img {
        height: 220px;
        width: 100%;
        object-fit: cover;
        border-radius: 20px 20px 0 0;
        transition: transform 0.4s ease;
    }

    .course-card-new:hover img {
        transform: scale(1.05);
    }

    .course-card-new .card-body {
        padding: 25px;
    }

    .course-card-new .card-title {
        font-size: 1.4rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 15px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .course-card-new .card-text {
        color: #666;
        margin-bottom: 20px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.6;
    }

    .course-card-new .tutor-info {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .course-card-new .tutor-info i {
        font-size: 1.2rem;
        color: #2099cf;
        margin-right: 10px;
    }

    .course-card-new .tutor-info span {
        color: #555;
        font-size: 0.95rem;
        font-weight: 500;
    }

    .course-card-new .card-footer {
        background: none;
        border-top: 1px solid #eee;
        padding: 20px;
    }

    .course-card-new .btn-group {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .course-card-new .btn {
        padding: 10px 20px;
        border-radius: 12px;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .course-card-new .btn-primary {
        background: #2099cf;
        border: none;
    }

    .course-card-new .btn-success {
        background: #15755f;
        border: none;
    }

    .course-card-new .btn-warning {
        background: #ffc107;
        border: none;
        color: #000;
    }

    .course-card-new .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .view-all-courses {
        display: inline-block;
        padding: 15px 40px;
        font-size: 1.1rem;
        font-weight: 500;
        color: white;
        background: linear-gradient(45deg, #2099cf, #15755f);
        border-radius: 30px;
        transition: all 0.3s ease;
        text-decoration: none;
        margin-top: 40px;
        border: none;
    }

    .view-all-courses:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(32, 153, 207, 0.2);
        color: white;
    }

    @media (max-width: 1200px) {
        .course-card-new .card-title {
            font-size: 1.3rem;
        }
    }

    @media (max-width: 991px) {
        .course-card-new img {
            height: 200px;
        }

        .course-card-new .btn-group {
            flex-direction: column;
        }

        .course-card-new .btn {
            width: 100%;
        }
    }

    @media (max-width: 768px) {
        .course-card-new {
            margin-bottom: 30px;
        }

        .course-card-new img {
            height: 180px;
        }

        .course-card-new .card-title {
            font-size: 1.2rem;
        }
    }

    @media (max-width: 576px) {
        .course-card-new img {
            height: 160px;
        }

        .course-card-new .card-body {
            padding: 20px;
        }

        .course-card-new .btn {
            padding: 8px 15px;
            font-size: 0.9rem;
        }
    }

    .featured-section-2023 {
        padding: 60px 0;
        background: #f8f9fa;
    }

    .featured-section-2023 h2 {
        color: #333;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 3rem;
        text-align: center;
        position: relative;
    }

    .featured-section-2023 h2:after {
        content: '';
        position: absolute;
        bottom: -15px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 4px;
        background: linear-gradient(45deg, #FF6B6B, #4ECDC4);
        border-radius: 2px;
    }

    .course-card-2023 {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.4s ease;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .course-card-2023:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .course-card-2023 .ribbon {
        position: absolute;
        top: 20px;
        right: -35px;
        transform: rotate(45deg);
        background: #FF6B6B;
        color: white;
        padding: 5px 40px;
        font-size: 0.8rem;
        font-weight: 500;
        z-index: 1;
    }

    .course-card-2023 .course-image {
        position: relative;
        height: 200px;
        overflow: hidden;
    }

    .course-card-2023 .course-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .course-card-2023:hover .course-image img {
        transform: scale(1.15);
    }

    .course-card-2023 .course-content {
        padding: 25px;
    }

    .course-card-2023 .course-title {
        font-size: 1.4rem;
        color: #2d3436;
        font-weight: 600;
        margin-bottom: 15px;
        line-height: 1.4;
    }

    .course-card-2023 .course-desc {
        color: #636e72;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .course-card-2023 .instructor-info {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
        padding: 12px 15px;
        background: #f8f9fa;
        border-radius: 12px;
    }

    .course-card-2023 .instructor-info i {
        font-size: 1.2rem;
        color: #FF6B6B;
        margin-right: 10px;
    }

    .course-card-2023 .instructor-name {
        font-size: 0.9rem;
        color: #2d3436;
        font-weight: 500;
    }

    .course-card-2023 .card-actions {
        display: flex;
        gap: 10px;
        padding: 20px 25px;
        background: #f8f9fa;
        border-top: 1px solid #eee;
    }

    .course-card-2023 .btn-custom {
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .course-card-2023 .btn-view {
        background: #4ECDC4;
        color: white;
        border: none;
    }

    .course-card-2023 .btn-enroll {
        background: #FF6B6B;
        color: white;
        border: none;
    }

    .course-card-2023 .btn-cart {
        background: #FFE66D;
        color: #2d3436;
        border: none;
    }

    .course-card-2023 .btn-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .course-card-2023 .btn-custom i {
        font-size: 1rem;
    }

    @media (max-width: 1200px) {
        .course-card-2023 .card-actions {
            flex-direction: column;
        }

        .course-card-2023 .btn-custom {
            width: 100%;
        }
    }

    @media (max-width: 768px) {
        .course-card-2023 {
            margin-bottom: 30px;
        }
    }

    @media (max-width: 576px) {
        .course-card-2023 .course-image {
            height: 180px;
        }

        .course-card-2023 .course-title {
            font-size: 1.2rem;
        }
    }

    .btn-view-all-courses {
        background: linear-gradient(45deg, #007bff, #2ec57f);
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        border: none;
        padding: 10px 25px;
        border-radius: 25px;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        border: none;
    }

    .btn-view-all-courses:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        background: linear-gradient(45deg, #2ec57f, #007bff);
        color: white;
    }

    .featured-card {
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .featured-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .featured-card .card-img-top {
        height: 200px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .featured-card:hover .card-img-top {
        transform: scale(1.05);
    }

    .featured-card .card-body {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .featured-card .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
        color: #2c3e50;
    }

    .featured-card .card-text {
        color: #666;
        font-size: 0.95rem;
        margin-bottom: 1rem;
    }

    .featured-card .tutor-info {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }

    .featured-card .card-footer {
        background: transparent;
        border-top: none;
        padding: 1rem 1.5rem;
        display: flex;
        gap: 10px;
        justify-content: space-between;
    }

    /* Button Styles */
    .btn-featured {
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        flex: 1;
        white-space: nowrap;
    }

    .btn-view {
        background: linear-gradient(45deg, #2099cf, #15755f);
        color: white;
        border: none;
    }

    .btn-view:hover {
        background: linear-gradient(45deg, #15755f, #2099cf);
        transform: translateY(-2px);
        color: white;
    }

    .btn-enroll {
        background: linear-gradient(45deg, #50c878, #2ec57f);
        color: white;
        border: none;
    }

    .btn-enroll:hover {
        background: linear-gradient(45deg, #2ec57f, #50c878);
        transform: translateY(-2px);
        color: white;
    }

    .btn-cart {
        background: linear-gradient(45deg, #ffc107, #ff9800);
        color: white;
        border: none;
    }

    .btn-cart:hover {
        background: linear-gradient(45deg, #ff9800, #ffc107);
        transform: translateY(-2px);
        color: white;
    }

    /* Responsive button styles */
    @media (max-width: 400px) {
        .featured-card .card-footer {
            flex-direction: column;
        }

        .btn-featured {
            width: 100%;
        }
    }

    .course-img-link {
        display: block;
        overflow: hidden;
        position: relative;
    }

    .course-img-link::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.1);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .course-img-link:hover::after {
        opacity: 1;
    }

    .course-img-link:hover .card-img-top {
        transform: scale(1.05);
    }

    .testimonials {
        padding: 60px 0;
        background: #f8f9fa;
        margin-bottom: 3rem;
    }

    .testimonials .section-title {
        padding-top: 20px;
        margin-bottom: 40px;
        color: #333;
        font-weight: 600;
    }

    .testimonials .card {
        height: 100%;
        border: none;
        background: white;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
    }

    .testimonials .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }

    .testimonials .card-body {
        padding: 2rem;
    }

    .testimonials .rounded-circle {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border: 3px solid #fff;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        margin-right: 15px;
        transition: all 0.3s ease;
    }

    .testimonials .card-text {
        margin-top: 20px;
        color: #555;
        font-size: 1rem;
        line-height: 1.6;
    }

    .testimonials strong {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        font-weight: 600;
        color: #333;
    }

    /* Slider Container */
.course-slider-wrapper {
    display: flex;
    align-items: center;
    position: relative;
    width: 100%;
    overflow: hidden;
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 10px;
}

/* Slider Buttons */
.slider-nav-btn {
    background-color: #007bff;
    border: none;
    color: white;
    padding: 10px;
    cursor: pointer;
    border-radius: 50%;
    font-size: 18px;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1;
}

.slider-nav-btn:hover {
    background-color: #0056b3;
}

.slider-nav-btn.prev-btn {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
}

.slider-nav-btn.next-btn {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
}

/* Slider Tabs */
.course-slider {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    scroll-behavior: smooth;
    padding: 5px 0;
    list-style: none;
    flex-grow: 1;
}

.course-slider::-webkit-scrollbar {
    display: none;
}

.nav-item {
    white-space: nowrap;
}

/* Slider Buttons in Tabs */
.nav-link1 {
    display: inline-block;
    padding: 8px 16px;
    background-color: #e9ecef;
    color: #495057;
    border-radius: 20px;
    text-align: center;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s, color 0.3s;
}

.nav-link1.active {
    background-color: #007bff;
    color: white;
}

.nav-link1:hover {
    background-color: #0056b3;
    color: white;
}

/* Icon Styling */
.nav-link1 i {
    margin-right: 5px;
}


    /* Large Screen Styles */
    @media (min-width: 992px) {
        .testimonials .row {
            margin: 0 -15px;
        }

        .testimonials .col-md-4 {
            padding: 0 15px;
        }

        .testimonials .card {
            margin: 0;
        }

        .testimonials .rounded-circle {
            width: 100px;
            height: 100px;
        }

        .testimonials .card-text {
            font-size: 1.1rem;
        }

        .testimonials .card-body {
            padding: 2.5rem;
        }
    }

    /* Medium Screen Styles */
    @media (max-width: 991px) {
        .testimonials .mb-3 {
            margin-bottom: 2rem !important;
        }

        .testimonials .card-body {
            padding: 2rem;
        }
    }

    /* Small Screen Styles */
    @media (max-width: 576px) {
        .testimonials {
            padding: 40px 0;
        }

        .testimonials .card-body {
            padding: 1.5rem;
        }

        .testimonials .rounded-circle {
            width: 60px;
            height: 60px;
        }

        .testimonials .card-text {
            font-size: 0.95rem;
        }
    }
</style>
<div
    class="hero-section text-center py-5 mb-5 parallax-window"
    data-parallax="scroll"
    style="background-size: cover; background-position: center; margin-bottom: 10%; position: relative; top: -20px; overflow: hidden;">
    <!-- Overlay for opacity -->
    <div class="hero-overlay"></div>

    <div class="container position-relative">
        <h4 class="display-4 mb-4 animate__animated animate__fadeInDown" style="margin-top: 97px;">Welcome to Ultrakey Learning</h4>
        <p class="lead mb-4 animate__animated animate__fadeInUp">Empower your future with our cutting-edge online courses</p>
        <?php if (!is_logged_in()): ?>
            <a href="register.php" class="btn btn-primary btn-lg me-2 animate__animated animate__fadeInLeft">Get Started</a>
            <a href="login.php" class="btn btn-outline-light btn-lg animate__animated animate__fadeInRight">Login</a>
        <?php endif; ?>
    </div>
</div>
<!-- <div class="container-fluid">
    <div class="row">
        <h2 class="text-center mb-4">Our Popular Courses</h2>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body ">
                    <img src="assets/images/php.jpg" alt="courses1" class="img-fluid">
                    <h5 class="card-title">courses1</h5>
                    <p class="card-text">Ultrakey Learning is an online learning platform that offers a wide range of courses in various fields. Our platform is designed to provide users with access to high-quality educational content, making it easy for them to learn and grow.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <img src="assets/images/java.jpg" alt="courses2" class="img-fluid">
                    <h5 class="card-title">courses2</h5>
                    <p class="card-text">Ultrakey Learning is an online learning platform that offers a wide range of courses in various fields. Our platform is designed to provide users with access to high-quality educational content, making it easy for them to learn and grow.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <img src="assets/images/python.jpg" alt="courses3" class="img-fluid">
                    <h5 class="card-title">courses3</h5>
                    <p class="card-text">Ultrakey Learning is an online learning platform that offers a wide range of courses in various fields. Our platform is designed to provide users with access to high-quality educational content, making it easy for them to learn and grow.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <img src="assets/images/React.jpg" alt="courses4" class="img-fluid">
                    <h5 class="card-title">courses4</h5>
                    <!-- <p class="card-text">Ultrakey Learning is an online learning platform that offers a wide range of courses in various fields. Our platform is designed to provide users with access to high-quality educational content, making it easy for them to learn and grow.</p> -->
                </div>
            </div>

    </div>
</div>
 </div> 
<div class="container-fluid mt-5" style="background-color: rgb(242 242 242);">
    <h2 class="text-center section-title">Our Courses</h2>

   <div class="course-slider-wrapper">
    <button class="slider-nav-btn prev-btn" id="sliderPrevBtn">
        <i class="fas fa-chevron-left"></i>
    </button>

    <ul class="nav nav-pills course-slider" id="courseSliderTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link1 active" id="all-courses-tab" data-bs-toggle="tab" data-bs-target="#all-courses" type="button" role="tab" data-category="all">
                <i class="fas fa-th"></i> All Courses
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link1" id="programming-courses-tab" data-bs-toggle="tab" data-bs-target="#programming-courses" type="button" role="tab" data-category="programming">
                <i class="fas fa-code"></i> Programming
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link1" id="web-courses-tab" data-bs-toggle="tab" data-bs-target="#web-courses" type="button" role="tab" data-category="web">
                <i class="fas fa-globe"></i> Web Development
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link1" id="popular-courses-tab" data-bs-toggle="tab" data-bs-target="#popular-courses" type="button" role="tab" data-category="popular">
                <i class="fas fa-fire"></i> Popular
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link1" id="data-science-courses-tab" data-bs-toggle="tab" data-bs-target="#data-science-courses" type="button" role="tab" data-category="data-science">
                <i class="fas fa-database"></i> Data Science
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link1" id="ai-ml-courses-tab" data-bs-toggle="tab" data-bs-target="#ai-ml-courses" type="button" role="tab" data-category="ai-ml">
                <i class="fas fa-brain"></i> AI & ML
            </button>
        </li>
    </ul>

    <button class="slider-nav-btn next-btn" id="sliderNextBtn">
        <i class="fas fa-chevron-right"></i>
    </button>
</div>



    <!-- Tab Content -->
    <div class="tab-content" id="courseTabsContent">
        <!-- All Courses Tab -->
        <div class="tab-pane fade show active" id="all" role="tabpanel">
            <div class="course-container">
                <div class="course-nav-buttons">
                    <button class="course-nav-btn prev" onclick="slideCards('all', 'prev')">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="course-nav-btn next" onclick="slideCards('all', 'next')">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <div class="course-wrapper" id="allCourses">
                    <div class="col-md-3">
                        <div class="card course-card">
                            <div class="card-inner">
                                <div class="card-front">
                                    <div class="card-body">
                                        <img src="assets/images/sap.jpg" alt="SAP Course" class="img-fluid">
                                        <h5 class="card-title mt-3">SAP Development</h5>
                                        <p class="card-text">Learn SAP development from scratch. Master backend development with SAP and build dynamic web applications.</p>
                                        <div class="course-meta">
                                            <div class="course-rating">
                                                <div class="stars">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star-half-alt"></i>
                                                </div>
                                                <span class="rating-count">(2,145)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-back">
                                    <div class="course-details">
                                        <h5>SAP Development</h5>
                                        <div class="course-price">₹599</div>
                                        <ul class="course-features">
                                            <li><i class="fas fa-clock"></i> 20 hours of content</li>
                                            <li><i class="fas fa-certificate"></i> Certificate of Completion</li>
                                            <li><i class="fas fa-infinity"></i> Lifetime Access</li>
                                            <li><i class="fas fa-headset"></i> 24/7 Support</li>
                                            <li><i class="fas fa-mobile-alt"></i> Access on Mobile & TV</li>
                                        </ul>
                                        <div style="display: flex; align-items: center;">
                                            <button class="enroll-btn">
                                                <i class="fas fa-shopping-cart"></i>
                                                Add to Cart
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card course-card">
                            <div class="card-inner">
                                <div class="card-front">
                                    <div class="card-body">
                                        <img src="assets/images/devops.jpg" alt="devops Course" class="img-fluid">
                                        <h5 class="card-title mt-3">Devops</h5>
                                        <p class="card-text">Master Devops programming with our comprehensive course. Learn OOP concepts and build robust applications.</p>
                                        <div class="course-meta">
                                            <div class="course-rating">
                                                <div class="stars">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star-half-alt"></i>
                                                </div>
                                                <span class="rating-count">(2,145)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-back">
                                    <div class="course-details">
                                        <h5>Devops</h5>
                                        <div class="course-price">₹599</div>
                                        <ul class="course-features">
                                            <li><i class="fas fa-clock"></i> 20 hours of content</li>
                                            <li><i class="fas fa-certificate"></i> Certificate of Completion</li>
                                            <li><i class="fas fa-infinity"></i> Lifetime Access</li>
                                            <li><i class="fas fa-headset"></i> 24/7 Support</li>
                                            <li><i class="fas fa-mobile-alt"></i> Access on Mobile & TV</li>
                                        </ul>
                                        <button class="enroll-btn">
                                            <i class="fas fa-shopping-cart"></i>
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card course-card">
                            <div class="card-inner">
                                <div class="card-front">
                                    <div class="card-body">
                                        <img src="assets/images/python.jpg" alt="Python Course" class="img-fluid">
                                        <h5 class="card-title mt-3">Python Programming</h5>
                                        <p class="card-text">Start your programming journey with Python. Learn data structures, algorithms, and practical applications.</p>
                                        <div class="course-meta">
                                            <div class="course-rating">
                                                <div class="stars">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star-half-alt"></i>
                                                </div>
                                                <span class="rating-count">(2,145)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-back">
                                    <div class="course-details">
                                        <h5>Python Programming</h5>
                                        <div class="course-price">₹599</div>
                                        <ul class="course-features">
                                            <li><i class="fas fa-clock"></i> 20 hours of content</li>
                                            <li><i class="fas fa-certificate"></i> Certificate of Completion</li>
                                            <li><i class="fas fa-infinity"></i> Lifetime Access</li>
                                            <li><i class="fas fa-headset"></i> 24/7 Support</li>
                                            <li><i class="fas fa-mobile-alt"></i> Access on Mobile & TV</li>
                                        </ul>
                                        <button class="enroll-btn">
                                            <i class="fas fa-shopping-cart"></i>
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card course-card">
                            <div class="card-inner">
                                <div class="card-front">
                                    <div class="card-body">
                                        <img src="assets/images/React.jpg" alt="React Course" class="img-fluid">
                                        <h5 class="card-title mt-3">React Development</h5>
                                        <p class="card-text">Build modern user interfaces with React. Master component-based architecture and state management.</p>
                                        <div class="course-meta">
                                            <div class="course-rating">
                                                <div class="stars">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star-half-alt"></i>
                                                </div>
                                                <span class="rating-count">(2,145)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-back">
                                    <div class="course-details">
                                        <h5>React Development</h5>
                                        <div class="course-price">₹599</div>
                                        <ul class="course-features">
                                            <li><i class="fas fa-clock"></i> 20 hours of content</li>
                                            <li><i class="fas fa-certificate"></i> Certificate of Completion</li>
                                            <li><i class="fas fa-infinity"></i> Lifetime Access</li>
                                            <li><i class="fas fa-headset"></i> 24/7 Support</li>
                                            <li><i class="fas fa-mobile-alt"></i> Access on Mobile & TV</li>
                                        </ul>
                                        <button class="enroll-btn">
                                            <i class="fas fa-shopping-cart"></i>
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card course-card">
                            <div class="card-inner">
                                <div class="card-front">
                                    <div class="card-body">
                                        <img src="assets/images/React.jpg" alt="React Course" class="img-fluid">
                                        <h5 class="card-title mt-3">React Development</h5>
                                        <p class="card-text">Build modern user interfaces with React. Master component-based architecture and state management.</p>
                                        <div class="course-meta">
                                            <div class="course-rating">
                                                <div class="stars">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star-half-alt"></i>
                                                </div>
                                                <span class="rating-count">(2,145)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-back">
                                    <div class="course-details">
                                        <h5>React Development</h5>
                                        <div class="course-price">₹599</div>
                                        <ul class="course-features">
                                            <li><i class="fas fa-clock"></i> 20 hours of content</li>
                                            <li><i class="fas fa-certificate"></i> Certificate of Completion</li>
                                            <li><i class="fas fa-infinity"></i> Lifetime Access</li>
                                            <li><i class="fas fa-headset"></i> 24/7 Support</li>
                                            <li><i class="fas fa-mobile-alt"></i> Access on Mobile & TV</li>
                                        </ul>
                                        <button class="enroll-btn">
                                            <i class="fas fa-shopping-cart"></i>
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Programming Tab -->
        <div class="tab-pane fade" id="programming" role="tabpanel">
            <div class="course-container">
                <div class="course-nav-buttons">
                    <button class="course-nav-btn prev" onclick="slideCards('programming', 'prev')">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="course-nav-btn next" onclick="slideCards('programming', 'next')">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <div class="course-wrapper" id="programmingCourses">
                    <div class="col-md-4">
                        <div class="card course-card">
                            <div class="card-inner">
                                <div class="card-front">
                                    <div class="card-body">
                                        <img src="assets/images/java.jpg" alt="Java Course" class="img-fluid">
                                        <h5 class="card-title mt-3">Java Programming</h5>
                                        <p class="card-text">Master Java programming with our comprehensive course. Learn OOP concepts and build robust applications.</p>
                                        <div class="course-meta">
                                            <div class="course-rating">
                                                <div class="stars">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star-half-alt"></i>
                                                </div>
                                                <span class="rating-count">(2,145)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-back">
                                    <div class="course-details">
                                        <h5>Java Programming</h5>
                                        <div class="course-price">₹599</div>
                                        <ul class="course-features">
                                            <li><i class="fas fa-clock"></i> 20 hours of content</li>
                                            <li><i class="fas fa-certificate"></i> Certificate of Completion</li>
                                            <li><i class="fas fa-infinity"></i> Lifetime Access</li>
                                            <li><i class="fas fa-headset"></i> 24/7 Support</li>
                                            <li><i class="fas fa-mobile-alt"></i> Access on Mobile & TV</li>
                                        </ul>
                                        <button class="enroll-btn">
                                            <i class="fas fa-shopping-cart"></i>
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card course-card">
                            <div class="card-inner">
                                <div class="card-front">
                                    <div class="card-body">
                                        <img src="assets/images/python.jpg" alt="Python Course" class="img-fluid">
                                        <h5 class="card-title mt-3">Python Programming</h5>
                                        <p class="card-text">Start your programming journey with Python. Learn data structures, algorithms, and practical applications.</p>
                                        <div class="course-meta">
                                            <div class="course-rating">
                                                <div class="stars">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star-half-alt"></i>
                                                </div>
                                                <span class="rating-count">(2,145)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-back">
                                    <div class="course-details">
                                        <h5>Python Programming</h5>
                                        <div class="course-price">₹599</div>
                                        <ul class="course-features">
                                            <li><i class="fas fa-clock"></i> 20 hours of content</li>
                                            <li><i class="fas fa-certificate"></i> Certificate of Completion</li>
                                            <li><i class="fas fa-infinity"></i> Lifetime Access</li>
                                            <li><i class="fas fa-headset"></i> 24/7 Support</li>
                                            <li><i class="fas fa-mobile-alt"></i> Access on Mobile & TV</li>
                                        </ul>
                                        <button class="enroll-btn">
                                            <i class="fas fa-shopping-cart"></i>
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Web Development Tab -->
        <div class="tab-pane fade" id="web" role="tabpanel">
            <div class="course-container">
                <div class="course-nav-buttons">
                    <button class="course-nav-btn prev" onclick="slideCards('web', 'prev')">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="course-nav-btn next" onclick="slideCards('web', 'next')">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <div class="course-wrapper" id="webCourses">
                    <div class="col-md-4">
                        <div class="card course-card">
                            <div class="card-inner">
                                <div class="card-front">
                                    <div class="card-body">
                                        <img src="assets/images/php.jpg" alt="PHP Course" class="img-fluid">
                                        <h5 class="card-title mt-3">PHP Development</h5>
                                        <p class="card-text">Learn PHP development from scratch. Master backend development with PHP and build dynamic web applications.</p>
                                        <div class="course-meta">
                                            <div class="course-rating">
                                                <div class="stars">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star-half-alt"></i>
                                                </div>
                                                <span class="rating-count">(2,145)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-back">
                                    <div class="course-details">
                                        <h5>PHP Development</h5>
                                        <div class="course-price">₹599</div>
                                        <ul class="course-features">
                                            <li><i class="fas fa-clock"></i> 20 hours of content</li>
                                            <li><i class="fas fa-certificate"></i> Certificate of Completion</li>
                                            <li><i class="fas fa-infinity"></i> Lifetime Access</li>
                                            <li><i class="fas fa-headset"></i> 24/7 Support</li>
                                            <li><i class="fas fa-mobile-alt"></i> Access on Mobile & TV</li>
                                        </ul>
                                        <button class="enroll-btn">
                                            <i class="fas fa-shopping-cart"></i>
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card course-card">
                            <div class="card-inner">
                                <div class="card-front">
                                    <div class="card-body">
                                        <img src="assets/images/React.jpg" alt="React Course" class="img-fluid">
                                        <h5 class="card-title mt-3">React Development</h5>
                                        <p class="card-text">Build modern user interfaces with React. Master component-based architecture and state management.</p>
                                        <div class="course-meta">
                                            <div class="course-rating">
                                                <div class="stars">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star-half-alt"></i>
                                                </div>
                                                <span class="rating-count">(2,145)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-back">
                                    <div class="course-details">
                                        <h5>React Development</h5>
                                        <div class="course-price">₹599</div>
                                        <ul class="course-features">
                                            <li><i class="fas fa-clock"></i> 20 hours of content</li>
                                            <li><i class="fas fa-certificate"></i> Certificate of Completion</li>
                                            <li><i class="fas fa-infinity"></i> Lifetime Access</li>
                                            <li><i class="fas fa-headset"></i> 24/7 Support</li>
                                            <li><i class="fas fa-mobile-alt"></i> Access on Mobile & TV</li>
                                        </ul>
                                        <button class="enroll-btn">
                                            <i class="fas fa-shopping-cart"></i>
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Popular Tab -->
        <div class="tab-pane fade" id="popular" role="tabpanel">
            <div class="course-container">
                <div class="course-nav-buttons">
                    <button class="course-nav-btn prev" onclick="slideCards('popular', 'prev')">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="course-nav-btn next" onclick="slideCards('popular', 'next')">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <div class="course-wrapper" id="popularCourses">
                    <div class="col-md-4">
                        <div class="card course-card">
                            <div class="card-inner">
                                <div class="card-front">
                                    <div class="card-body">
                                        <img src="assets/images/python.jpg" alt="Python Course" class="img-fluid">
                                        <h5 class="card-title mt-3">Python Programming</h5>
                                        <p class="card-text">Start your programming journey with Python. Learn data structures, algorithms, and practical applications.</p>
                                        <div class="course-meta">
                                            <div class="course-rating">
                                                <div class="stars">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star-half-alt"></i>
                                                </div>
                                                <span class="rating-count">(2,145)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-back">
                                    <div class="course-details">
                                        <h5>Python Programming</h5>
                                        <div class="course-price">₹599</div>
                                        <ul class="course-features">
                                            <li><i class="fas fa-clock"></i> 20 hours of content</li>
                                            <li><i class="fas fa-certificate"></i> Certificate of Completion</li>
                                            <li><i class="fas fa-infinity"></i> Lifetime Access</li>
                                            <li><i class="fas fa-headset"></i> 24/7 Support</li>
                                            <li><i class="fas fa-mobile-alt"></i> Access on Mobile & TV</li>
                                        </ul>
                                        <button class="enroll-btn">
                                            <i class="fas fa-shopping-cart"></i>
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card course-card">
                            <div class="card-inner">
                                <div class="card-front">
                                    <div class="card-body">
                                        <img src="assets/images/React.jpg" alt="React Course" class="img-fluid">
                                        <h5 class="card-title mt-3">React Development</h5>
                                        <p class="card-text">Build modern user interfaces with React. Master component-based architecture and state management.</p>
                                        <div class="course-meta">
                                            <div class="course-rating">
                                                <div class="stars">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star-half-alt"></i>
                                                </div>
                                                <span class="rating-count">(2,145)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-back">
                                    <div class="course-details">
                                        <h5>React Development</h5>
                                        <div class="course-price">₹599</div>
                                        <ul class="course-features">
                                            <li><i class="fas fa-clock"></i> 20 hours of content</li>
                                            <li><i class="fas fa-certificate"></i> Certificate of Completion</li>
                                            <li><i class="fas fa-infinity"></i> Lifetime Access</li>
                                            <li><i class="fas fa-headset"></i> 24/7 Support</li>
                                            <li><i class="fas fa-mobile-alt"></i> Access on Mobile & TV</li>
                                        </ul>
                                        <button class="enroll-btn">
                                            <i class="fas fa-shopping-cart"></i>
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card course-card">
                            <div class="card-inner">
                                <div class="card-front">
                                    <div class="card-body">
                                        <img src="assets/images/java.jpg" alt="Java Course" class="img-fluid">
                                        <h5 class="card-title mt-3">Java Programming</h5>
                                        <p class="card-text">Master Java programming with our comprehensive course. Learn OOP concepts and build robust applications.</p>
                                        <div class="course-meta">
                                            <div class="course-rating">
                                                <div class="stars">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star-half-alt"></i>
                                                </div>
                                                <span class="rating-count">(2,145)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-back">
                                    <div class="course-details">
                                        <h5>Java Programming</h5>
                                        <div class="course-price">₹599</div>
                                        <ul class="course-features">
                                            <li><i class="fas fa-clock"></i> 20 hours of content</li>
                                            <li><i class="fas fa-certificate"></i> Certificate of Completion</li>
                                            <li><i class="fas fa-infinity"></i> Lifetime Access</li>
                                            <li><i class="fas fa-headset"></i> 24/7 Support</li>
                                            <li><i class="fas fa-mobile-alt"></i> Access on Mobile & TV</li>
                                        </ul>
                                        <button class="enroll-btn">
                                            <i class="fas fa-shopping-cart"></i>
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt-5">
    <!-- <php if (is_logged_in()): ?>
        <div class="welcome-back mb-5 animate__animated animate__fadeIn">
            <h2>Welcome back, <php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            <p>Ready to continue your learning journey? Check out your dashboard or explore new courses below.</p>
            <!-- <a href="index.php" class="btn btn-primary">Go to Dashboard</a> ->
        </div>

    <!-- Features Section with Hover Effects -->
    <section class="features mb-5">
        <h2 class="text-center mb-4">Why Choose Ultrakey Learning?</h2>
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card h-100 animate__animated animate__fadeInUp hover-card">
                    <div class="card-body text-center">
                        <i class="fas fa-laptop-code fa-3x mb-3 text-primary"></i>
                        <h5 class="card-title">Learn Anywhere</h5>
                        <p class="card-text">Access our courses from any device, anytime, anywhere.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                    <div class="card-body text-center">
                        <i class="fas fa-certificate fa-3x mb-3 text-primary"></i>
                        <h5 class="card-title">Certified Courses</h5>
                        <p class="card-text">Earn certificates recognized by top industries.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100 animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-3x mb-3 text-primary"></i>
                        <h5 class="card-title">Expert Instructors</h5>
                        <p class="card-text">Learn from industry professionals and thought leaders.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="explore">
        <section class="course-categories mb-5">
            <h2 class="text-center mb-4">Explore Our Course Categories</h2>
            <p class="text-center mb-5" style="color: #ffffff;">Discover a wide range of courses to suit your interests and career goals.</p>
            <div class="row">
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="category-card">
                        <img src="assets/images/programming.jpg" alt="Programming" class="img-fluid">
                        <div class="category-overlay">
                            <h3>Programming</h3>
                            <a href="courses.php?category=programming" class="btn btn-light">Explore</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="category-card">
                        <img src="assets/images/design.jpg" alt="Design" class="img-fluid">
                        <div class="category-overlay">
                            <h3>Design</h3>
                            <a href="courses.php?category=design" class="btn btn-light">Explore</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="category-card">
                        <img src="assets/images/business.jpg" alt="Business" class="img-fluid">
                        <div class="category-overlay">
                            <h3>Business</h3>
                            <a href="courses.php?category=business" class="btn btn-light">Explore</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="category-card">
                        <img src="assets/images/language.jpg" alt="Language" class="img-fluid">
                        <div class="category-overlay">
                            <h3>Language</h3>
                            <a href="courses.php?category=language" class="btn btn-light">Explore</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <section class="featured-courses mb-5" style="margin: 43px;">
        <h2 class="text-center mb-4">Featured Courses</h2>
        <?php
        // Fetch featured courses
        $query = "SELECT c.id, c.title, c.description, t.full_name AS tutor_name
                  FROM courses c
                  LEFT JOIN tutors t ON c.tutor_id = t.id
                  ORDER BY c.created_at DESC LIMIT 4";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0):
        ?>
            <div class="row">
                <?php while ($course = mysqli_fetch_assoc($result)): ?>
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                        <div class="card featured-card animate__animated animate__fadeIn">
                            <a href="course.php?id=<?php echo $course['id']; ?>" class="course-img-link">
                                <img src="assets/images/<?php echo htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8'); ?>.jpg"
                                    class="card-img-top"
                                    alt="<?php echo htmlspecialchars($course['title']); ?>">
                            </a>
                            <div class="card-body">
                                <h3 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h3>
                                <p class="card-text"><?php echo htmlspecialchars(substr($course['description'], 0, 100)) . '...'; ?></p>
                                <p class="tutor-info">
                                    <i class="fas fa-user-tie me-2"></i>
                                    <?php echo htmlspecialchars($course['tutor_name']); ?>
                                </p>
                            </div>
                            <div class="card-footer">
                                <?php if (is_logged_in()): ?>
                                    <a href="courses.php" class="btn-featured btn-enroll">
                                        <i class="fas fa-graduation-cap"></i> Enroll
                                    </a>
                                    <button class="btn-featured btn-cart" onclick="addToCart(<?php echo $course['id']; ?>)">
                                        <i class="fas fa-shopping-cart"></i> Cart
                                    </button>
                                <?php else: ?>
                                    <a href="login.php" class="btn-featured btn-view">
                                        <i class="fas fa-sign-in-alt"></i> Login to Enroll
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </section>

    <section class="stats-counter mb-5 py-5 bg-light">
        <div class="container">
            <div class="row text-center">
                <?php
                // Fetch counts from the database
                $students_count_result = mysqli_query($conn, "SELECT COUNT(DISTINCT user_id) as count FROM enrollments WHERE user_id IS NOT NULL");
                if (!$students_count_result) {
                    die('Query Error: ' . mysqli_error($conn)); // Error handling
                }
                $students_count = mysqli_fetch_assoc($students_count_result)['count'];
                // echo "Students Count: " . $students_count; // Debugging output

                $courses_count_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM courses");
                if (!$courses_count_result) {
                    die('Query Error: ' . mysqli_error($conn)); // Error handling
                }
                $courses_count = mysqli_fetch_assoc($courses_count_result)['count'];

                $tutors_count_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM tutors");
                if (!$tutors_count_result) {
                    die('Query Error: ' . mysqli_error($conn)); // Error handling
                }
                $tutors_count = mysqli_fetch_assoc($tutors_count_result)['count'];
                ?>

                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="counter">
                        <i class="fas fa-users fa-3x mb-3 text-primary"></i>
                        <h2><span class="count" data-count="<?php echo $students_count; ?>">0</span><span style="font-size: 1.2em;">+</span></h2>
                        <p>Students Enrolled</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="counter">
                        <i class="fas fa-book fa-3x mb-3 text-primary"></i>
                        <h2><span class="count" data-count="<?php echo $courses_count; ?>">0</span><span style="font-size: 1.2em;">+</span></h2>
                        <p>Courses Available</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="counter">
                        <i class="fas fa-chalkboard-teacher fa-3x mb-3 text-primary"></i>
                        <h2><span class="count" data-count="<?php echo $tutors_count; ?>">0</span><span style="font-size: 1.2em;">+</span></h2>
                        <p>Expert Instructors</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="counter">
                        <i class="fas fa-globe fa-3x mb-3 text-primary"></i>

                        <h2><span id="countries-count" class="count" data-count="50">0</span><span style="font-size: 1.2em;">+</span></h2>
                        <p>Countries Reached</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="testimonials mb-5">
        <h2 class="text-center section-title">What Our Students Say</h2>
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card h-100 animate__animated animate__fadeIn">
                    <div class="card-body">
                        <p class="card-text"><strong><img src="assets/images/student1.jpg" alt="John Doe" class="rounded-circle"> - Sandhya, Web Developer</strong></p>
                        <p class="card-text">"Ultrakey Learning has transformed my career. The courses are top-notch and the instructors are amazing!"</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100 animate__animated animate__fadeIn" style="animation-delay: 0.2s;">
                    <div class="card-body">
                        <p class="card-text"><strong><img src="assets/images/student2.jpg" alt="Jane Smith" class="rounded-circle"> - Neha, Android Developer</strong></p>
                        <p class="card-text">"I've learned more in 3 months with Ultrakey Learning than I did in a year at university. Highly recommended!"</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100 animate__animated animate__fadeIn" style="animation-delay: 0.4s;">
                    <div class="card-body">
                        <p class="card-text"><strong><img src="assets/images/student3.jpg" alt="Mike Johnson" class="rounded-circle"> - Arun, Business Analyst</strong></p>
                        <p class="card-text">"The flexibility of online learning combined with the quality of content makes Ultrakey Learning unbeatable."</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta text-center py-5 mb-5">
        <h2 class="mb-3">Ready to Start Your Learning Journey?</h2>
        <p class="lead mb-4">Join thousands of students already learning on Ultrakey Learning</p>
        <a href="register.php" class="btn btn-primary btn-lg animate__animated animate__pulse animate__infinite">Get Started Now</a>
    </section>

    <section class="lms-showcase mb-5">
        <div class="container">
            <h2 class="text-center mb-5">Experience the Power of Ultrakey Learning</h2>
            <div class="row align-items-center mb-5">
                <div class="col-md-6">
                    <img src="assets/images/interactive-lessons.jpg" alt="Interactive Lessons" class="img-fluid rounded shadow-lg responsive-img" style="max-width: 100%; height: 400px; margin-left: 15%">
                </div>
                <div class="col-md-6">
                    <h3>Interactive Lessons</h3>
                    <p>Engage with our cutting-edge interactive lessons that make learning fun and effective. Our platform offers a variety of multimedia content, quizzes, and hands-on exercises to keep you motivated and ensure better retention of knowledge.</p>
                    <a href="#" class="btn btn-outline-primary">Learn More</a>
                </div>
            </div>

            <div class="row align-items-center mb-5">
                <div class="col-md-6 order-md-2">
                    <img src="assets/images/progress-tracking.jpg" alt="Progress Tracking" class="img-fluid rounded shadow-lg">
                </div>
                <div class="col-md-6 order-md-1">
                    <h3>Comprehensive Progress Tracking</h3>
                    <p style="text-align: justify ; margin-right:15%">Stay on top of your learning journey with our detailed progress tracking system. Monitor your course completion, quiz scores, and skill development in real-time. Set personal goals and watch as you achieve them step by step.</p>
                    <a href="#" class="btn btn-outline-primary">Explore Features</a>
                </div>
            </div>
            <div class="row align-items-center mb-5">
                <div class="col-md-6">
                    <img src="assets/images/community-forums.jpg" alt="Community Forums" class="img-fluid rounded shadow-lg">
                </div>
                <div class="col-md-6">
                    <h3 style="margin-left:30px ;" class="a">Vibrant Learning Community</h3>
                    <p style="margin-left: 28px">Join our thriving community of learners and educators. Participate in discussions, share knowledge, and get support from peers and instructors.</p>
                    <a href="#" class="btn btn-outline-primary" style="margin-left: 34px;">Join the Community</a>
                </div>
            </div>
        </div>
    </section>

</div>

<script>
    function addToCart(courseId) {
        window.location.href = `courses.php?addToCart=${courseId}`;

    }

    function animateCounter(element, start, end, duration) {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            element.innerText = Math.floor(progress * (end - start) + start);
            if (progress < 1) {
                requestAnimationFrame(step);
            }
        };
        requestAnimationFrame(step);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const studentsCountElement = document.querySelector('.count[data-count="<?php echo $students_count; ?>"]');
        animateCounter(studentsCountElement, 0, <?php echo $students_count; ?>, 2000); // Animate from 0 to the actual count
        const countriesCountElement = document.getElementById('countries-count');
        animateCounter(countriesCountElement, 0, 12, 2000); // Animate from 0 to 12 over 2 seconds
        const coursesCountElement = document.querySelector('.count[data-count="<?php echo $courses_count; ?>"]');
        animateCounter(coursesCountElement, 0, <?php echo $courses_count; ?>, 2000); // Animate from 0 to the actual count
        const tutorsCountElement = document.querySelector('.count[data-count="<?php echo $tutors_count; ?>"]');
        animateCounter(tutorsCountElement, 0, <?php echo $tutors_count; ?>, 2000); // Animate from 0 to the actual count
    });
    document.addEventListener('DOMContentLoaded', function () {
    const sliderTabsContainer = document.querySelector('.course-slider');
    const prevSliderBtn = document.getElementById('sliderPrevBtn');
    const nextSliderBtn = document.getElementById('sliderNextBtn');

    let scrollPosition = 0;
    const scrollStepValue = 200;

    function updateSliderButtonVisibility() {
        const maxScrollValue = sliderTabsContainer.scrollWidth - sliderTabsContainer.clientWidth;

        prevSliderBtn.style.display = scrollPosition <= 0 ? 'none' : 'flex';
        nextSliderBtn.style.display = scrollPosition >= maxScrollValue ? 'none' : 'flex';
    }

    prevSliderBtn.addEventListener('click', () => {
        scrollPosition = Math.max(scrollPosition - scrollStepValue, 0);
        sliderTabsContainer.scrollTo({
            left: scrollPosition,
            behavior: 'smooth',
        });
        updateSliderButtonVisibility();
    });

    nextSliderBtn.addEventListener('click', () => {
        const maxScrollValue = sliderTabsContainer.scrollWidth - sliderTabsContainer.clientWidth;
        scrollPosition = Math.min(scrollPosition + scrollStepValue, maxScrollValue);
        sliderTabsContainer.scrollTo({
            left: scrollPosition,
            behavior: 'smooth',
        });
        updateSliderButtonVisibility();
    });

    sliderTabsContainer.addEventListener('scroll', () => {
        scrollPosition = sliderTabsContainer.scrollLeft;
        updateSliderButtonVisibility();
    });

    window.addEventListener('resize', () => {
        scrollPosition = 0;
        sliderTabsContainer.scrollTo({
            left: 0,
            behavior: 'smooth',
        });
        updateSliderButtonVisibility();
    });

    updateSliderButtonVisibility();

    const sliderTabButtons = document.querySelectorAll('.nav-link1');
    sliderTabButtons.forEach((button) => {
        button.addEventListener('click', (e) => {
            sliderTabButtons.forEach((btn) => btn.classList.remove('active'));
            e.target.classList.add('active');
            const category = e.target.getAttribute('data-category');
            fetchCoursesByCategory(category); // Fetch courses based on category
        });
    });

    function fetchCoursesByCategory(category) {
        // Sample course data (replace with your API call or actual data source)
        const coursesData = {
            all: ['Course 1', 'Course 2', 'Course 3'],
            programming: ['JavaScript', 'Java', 'C++'],
            web: ['HTML', 'CSS', 'ReactJS'],
            popular: ['Python', 'Machine Learning'],
            'data-science': ['R', 'Big Data', 'AI'],
            'ai-ml': ['AI Basics', 'Deep Learning'],
        };

        const courses = coursesData[category] || [];
        displayCourses(courses);
    }

    function displayCourses(courses) {
        // This function should update the UI with the fetched courses
        // Example: 
        const courseContainer = document.querySelector('#course-list');
        courseContainer.innerHTML = ''; // Clear previous content

        courses.forEach(course => {
            const courseElement = document.createElement('div');
            courseElement.classList.add('course-item');
            courseElement.innerText = course;
            courseContainer.appendChild(courseElement);
        });
    }
});

</script>
<script>
    // Tab functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips if using Bootstrap
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Add hover animation to course cards
        const courseCards = document.querySelectorAll('.course-card');
        courseCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px)';
                this.style.boxShadow = '0 15px 30px rgba(0,0,0,0.15)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.1)';
            });
        });

        // Add smooth scrolling for tab clicks
        const tabLinks = document.querySelectorAll('[data-bs-toggle="tab"]');
        tabLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const targetId = this.getAttribute('data-bs-target');
                const targetPane = document.querySelector(targetId);

                // Add fade-in animation
                targetPane.style.opacity = '0';
                setTimeout(() => {
                    targetPane.style.opacity = '1';
                }, 150);
            });
        });

        // Add active class to current tab
        const hash = window.location.hash;
        if (hash) {
            const activeTab = document.querySelector(`[data-bs-target="${hash}"]`);
            if (activeTab) {
                activeTab.click();
            }
        }
    });

    // Enhanced card navigation functionality
    const cardPositions = {
        all: 0,
        programming: 0,
        web: 0,
        popular: 0
    };

    function getVisibleCards() {
        return window.innerWidth <= 768 ? 1 :
            window.innerWidth <= 992 ? 2 :
            window.innerWidth <= 1200 ? 3 : 4;
    }

    function updateCardWidth() {
        const containerWidth = document.querySelector('.course-container').offsetWidth;
        const visibleCards = getVisibleCards();
        const gap = 20;
        return (containerWidth - (gap * (visibleCards - 1))) / visibleCards;
    }

    function slideCards(section, direction) {
        const wrapper = document.getElementById(`${section}Courses`);
        if (!wrapper) return;

        const cards = wrapper.children.length;
        const visibleCards = getVisibleCards();
        const maxPosition = Math.max(0, cards - visibleCards);
        const currentPos = cardPositions[section];

        if (direction === 'next' && currentPos < maxPosition) {
            cardPositions[section]++;
        } else if (direction === 'prev' && currentPos > 0) {
            cardPositions[section]--;
        }

        const cardWidth = updateCardWidth();
        wrapper.style.transform = `translateX(-${cardPositions[section] * (cardWidth + 20)}px)`;
        updateNavButtons(section);
    }

    function updateNavButtons(section) {
        const container = document.getElementById(`${section}Courses`).parentElement;
        const prevBtn = container.querySelector('.prev');
        const nextBtn = container.querySelector('.next');
        const cards = document.getElementById(`${section}Courses`).children.length;
        const visibleCards = getVisibleCards();
        const maxPosition = Math.max(0, cards - visibleCards);

        // Update previous button
        if (cardPositions[section] === 0) {
            prevBtn.setAttribute('disabled', 'true');
            prevBtn.style.opacity = '0.5';
        } else {
            prevBtn.removeAttribute('disabled');
            prevBtn.style.opacity = '1';
        }

        // Update next button
        if (cardPositions[section] >= maxPosition) {
            nextBtn.setAttribute('disabled', 'true');
            nextBtn.style.opacity = '0.5';
        } else {
            nextBtn.removeAttribute('disabled');
            nextBtn.style.opacity = '1';
        }
    }

    // Initialize navigation
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all sections
        Object.keys(cardPositions).forEach(section => {
            updateNavButtons(section);
        });

        // Handle tab changes
        const tabs = document.querySelectorAll('[data-bs-toggle="tab"]');
        tabs.forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(e) {
                const section = e.target.getAttribute('data-bs-target').replace('#', '');
                cardPositions[section] = 0;
                const wrapper = document.getElementById(`${section}Courses`);
                if (wrapper) {
                    wrapper.style.transform = 'translateX(0)';
                    updateNavButtons(section);
                }
            });
        });
    });

    // Handle window resize with debouncing
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            Object.keys(cardPositions).forEach(section => {
                const wrapper = document.getElementById(`${section}Courses`);
                if (wrapper) {
                    const cardWidth = updateCardWidth();
                    wrapper.style.transform = `translateX(-${cardPositions[section] * (cardWidth + 20)}px)`;
                    updateNavButtons(section);
                }
            });
        }, 250);
    });

    // Add touch support for mobile devices
    let touchStartX = 0;
    let touchEndX = 0;

    document.querySelectorAll('.course-wrapper').forEach(wrapper => {
        wrapper.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        }, false);

        wrapper.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe(wrapper);
        }, false);
    });

    function handleSwipe(wrapper) {
        const section = wrapper.id.replace('Courses', '');
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;

        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                slideCards(section, 'next');
            } else {
                slideCards(section, 'prev');
            }
        }
    }
</script>



<?php include 'footer.php'; ?>