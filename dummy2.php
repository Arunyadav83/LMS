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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ultrakey Learning - Transform Your Future</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --primary-color: #45B5AA;
            --secondary-color: #1f2937;
            --accent-color: #0d6efd;
            --text-color: #333;
            --light-gray: #f8f9fa;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(31, 41, 55, 0.8), rgba(31, 41, 55, 0.9)),
                        url('assets/images/hero-bg.jpg') center/cover;
            min-height: 80vh;
            display: flex;
            align-items: center;
            padding: 100px 20px;
            position: relative;
        }

        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
            color: white;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            animation: fadeInUp 1s ease;
        }

        .hero p {
            font-size: 1.25rem;
            margin-bottom: 30px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            animation: fadeInUp 1s ease 0.2s;
            opacity: 0.9;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            animation: fadeInUp 1s ease 0.4s;
        }

        .cta-primary {
            padding: 15px 35px;
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .cta-secondary {
            padding: 15px 35px;
            background-color: transparent;
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 600;
            border: 2px solid white;
            transition: all 0.3s ease;
        }

        .cta-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(69, 181, 170, 0.3);
        }

        .cta-secondary:hover {
            background-color: white;
            color: var(--secondary-color);
            transform: translateY(-3px);
        }

        /* Stats Section */
        .stats {
            padding: 80px 20px;
            background-color: white;
        }

        .stats-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
        }

        .stat-item {
            text-align: center;
            padding: 30px;
            border-radius: 10px;
            background: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        /* Features Section */
        .features {
            padding: 100px 20px;
            background-color: var(--light-gray);
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-title h2 {
            font-size: 2.5rem;
            color: var(--secondary-color);
            margin-bottom: 20px;
        }

        .section-title p {
            color: #666;
            max-width: 600px;
            margin: 0 auto;
        }

        .features-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .feature-card {
            background: white;
            padding: 40px 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
        }

        .feature-icon i {
            font-size: 30px;
            color: white;
        }

        /* Courses Section */
        .courses {
            padding: 100px 20px;
            background: white;
        }

        .courses-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .course-card {
          background-color: #f8f9fa; 
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .course-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .course-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .course-content {
            padding: 25px;
        }

        .course-tag {
            background: var(--primary-color);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            display: inline-block;
            margin-bottom: 15px;
        }

        .course-title {
            font-size: 1.25rem;
            margin-bottom: 15px;
            color: var(--secondary-color);
        }

        .course-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid #eee;
            margin-top: 15px;
        }

        .course-price {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.25rem;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }

            .features-grid,
            .courses-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1.1rem;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            .features-grid,
            .courses-grid {
                grid-template-columns: 1fr;
                max-width: 500px;
            }
        }

        @media (max-width: 576px) {
            .stats-container {
                grid-template-columns: 1fr;
            }

            .section-title h2 {
                font-size: 2rem;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Slider Container Styles */
        .courses-slider-container {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 50px;
        }

        .courses-grid {
            display: flex;
            overflow-x: hidden;
            scroll-behavior: smooth;
            gap: 30px;
            padding: 20px 0;
        }

        .course-card {
            min-width: calc(33.333% - 20px);
            flex: 0 0 calc(33.333% - 20px);
        }

        /* Navigation Buttons */
        .slider-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #45B5AA;
            border: none;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            z-index: 10;
        }

        .slider-nav-btn:hover {
            background: #3a9990;
            transform: translateY(-50%) scale(1.1);
        }

        .slider-nav-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            opacity: 0.5;
        }

        .prev-btn {
            left: 0;
        }

        .next-btn {
            right: 0;
        }

        /* Responsive Design for Slider */
        @media (max-width: 1024px) {
            .course-card {
                min-width: calc(50% - 15px);
                flex: 0 0 calc(50% - 15px);
            }
        }

        @media (max-width: 768px) {
            .courses-slider-container {
                padding: 0 40px;
            }

            .course-card {
                min-width: calc(100% - 30px);
                flex: 0 0 calc(100% - 30px);
            }

            .slider-nav-btn {
                width: 35px;
                height: 35px;
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .courses-slider-container {
                padding: 0 30px;
            }
        }
        @media (max-width: 320px) {
    .courses-slider-container {
        padding: -2px 32px;
    }
}
        /* Category Cards Slider */
        .category-slider-container {
            position: relative;
            max-width: 1200px;
            margin: 0 auto 50px;
            padding: 0 50px;
        }

        .category-slider {
            display: flex;
            overflow-x: hidden;
            scroll-behavior: smooth;
            gap: 20px;
            padding: 20px 0;
        }

        .category-card {
            min-width: 200px;
            flex: 0 0 200px;
            background: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .category-card:hover {
            transform: translateY(-5px);
            border-color: #45B5AA;
        }

        .category-card.active {
            background: #45B5AA;
            color: white;
        }

        .category-icon {
            font-size: 2rem;
            margin-bottom: 15px;
            color: #45B5AA;
        }

        .category-card.active .category-icon {
            color: white;
        }

        .category-name {
            font-weight: 600;
            font-size: 1rem;
        }

        /* Category Navigation Buttons */
        .category-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #45B5AA;
            border: none;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            z-index: 10;
        }

        .category-nav-btn:hover {
            background: #3a9990;
            transform: translateY(-50%) scale(1.1);
        }

        .category-nav-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            opacity: 0.5;
        }

        .category-prev-btn {
            left: 0;
        }

        .category-next-btn {
            right: 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .category-slider-container {
                padding: 0 40px;
            }

            .category-card {
                min-width: 160px;
                flex: 0 0 160px;
            }
        }

        @media (max-width: 576px) {
            .category-slider-container {
                padding: 0 30px;
            }

            .category-card {
                min-width: 140px;
                flex: 0 0 140px;
                padding: 15px;
            }

            .category-icon {
                font-size: 1.5rem;
            }

            .category-name {
                font-size: 0.9rem;
            }
        }

        /* Section Container */
        #courseCategories {
            background-color:#b4dae4;
            padding: 80px 0;
        }

        .category-section__header {
            text-align: center;
            margin-bottom: 50px;
        }

        .category-section__title {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .category-section__description {
            color: #666;
            font-size: 1.1rem;
            max-width: 800px;
            margin: 0 auto;
        }

        /* Grid Container */
        .category-grid {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .category-grid__row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
        }

        /* Card Styles */
        .category-item {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .category-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .category-item__image-wrapper {
            position: relative;
            width: 100%;
            height: 220px; /* Fixed height for all images */
            overflow: hidden;
        }

        .category-item__image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .category-item:hover .category-item__image {
            transform: scale(1.1);
        }

        .category-item__overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
            padding: 20px;
            text-align: center;
        }

        .category-item__title {
            color: white;
            font-size: 1.25rem;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .category-item__button {
            display: inline-block;
            padding: 8px 25px;
            background-color: #45B5AA;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .category-item__button:hover {
            background-color: white;
            color: #45B5AA;
            border-color: #45B5AA;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .category-grid__row {
                gap: 20px;
            }
        }

        @media (max-width: 992px) {
            .category-grid__row {
                grid-template-columns: repeat(2, 1fr);
            }

            .category-section__title {
                font-size: 2.2rem;
            }
        }

        @media (max-width: 768px) {
            #courseCategories {
                padding: 60px 0;
            }

            .category-item__image-wrapper {
                height: 200px;
            }
        }

        @media (max-width: 576px) {
            .category-grid__row {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .category-section__title {
                font-size: 1.8rem;
            }

            .category-section__description {
                font-size: 1rem;
            }

            .category-item__image-wrapper {
                height: 180px;
            }
        }

        /* Testimonials Section */
        .testimonials-section {
            padding: 80px 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .testimonials-title {
            font-size: 2.5rem;
            color: #333;
            text-align: center;
            margin-bottom: 50px;
            font-weight: 600;
        }

        .testimonials-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        /* Testimonial Card */
        .testimonial-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            position: relative;
        }

        .testimonial-card:hover {
            transform: translateY(-10px);
        }

        /* Quote Icon */
        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: -15px;
            left: 20px;
            font-size: 80px;
            color: #45B5AA;
            font-family: serif;
            opacity: 0.1;
        }

        /* Student Info */
        .student-info {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            gap: 15px;
        }

        .student-image {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #45B5AA;
        }

        .student-details {
            flex: 1;
        }

        .student-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .student-role {
            font-size: 0.9rem;
            color: #45B5AA;
        }

        /* Testimonial Content */
        .testimonial-text {
            font-size: 1rem;
            line-height: 1.6;
            color: #666;
            position: relative;
            z-index: 1;
        }

        /* Animation Classes */
        .fade-in {
            opacity: 0;
            animation: fadeIn 0.8s ease forwards;
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

        /* Responsive Design */
        @media (max-width: 1200px) {
            .testimonials-grid {
                gap: 20px;
            }
        }

        @media (max-width: 992px) {
            .testimonials-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .testimonials-title {
                font-size: 2.2rem;
            }
        }

        @media (max-width: 768px) {
            .testimonials-section {
                padding: 60px 15px;
            }

            .testimonial-card {
                padding: 25px;
            }
        }

        @media (max-width: 576px) {
            .testimonials-grid {
                grid-template-columns: 1fr;
            }

            .testimonials-title {
                font-size: 2rem;
                margin-bottom: 30px;
            }

            .student-image {
                width: 50px;
                height: 50px;
            }
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, #45B5AA 0%, #367c76 100%);
            padding: 2px 20px;
            position: relative;
            overflow: hidden;
        }

        /* Animated Background */
        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('assets/images/pattern.png') repeat;
            opacity: 0.1;
            animation: slideBackground 20s linear infinite;
        }

        @keyframes slideBackground {
            from { background-position: 0 0; }
            to { background-position: 100% 100%; }
        }

        .cta-container {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .cta-title {
            color: white;
            font-size: clamp(2rem, 3vw, 2rem);
            font-weight: 200;
            margin-bottom: 20px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .cta-description {
            color: rgba(255, 255, 255, 0.9);
            font-size: clamp(1.1rem, 2vw, 1.3rem);
            margin-bottom: 40px;
            line-height: 1.6;
            text-align: center;
        }

        .cta-button {
            display: inline-block;
            padding: 15px 30px;
            background: white;
            color: #45B5AA;
            font-size: 1.1rem;
            font-weight: 500;
            text-decoration: none;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            /* position:center; */
            overflow: hidden;
            text-align: center;
           
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            color: #45B5AA;
        }

        /* Pulse Animation */
        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }

        /* Floating Shapes */
        .shape {
            position: absolute;
            opacity: 0.1;
            pointer-events: none;
        }

        .shape-1 {
            top: 20%;
            left: 10%;
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .shape-2 {
            top: 40%;
            right: 15%;
            width: 40px;
            height: 40px;
            background: white;
            transform: rotate(45deg);
            animation: float 8s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .cta-section {
                padding: 80px 20px;
            }

            .cta-button {
                padding: 15px 35px;
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .cta-section {
                padding: 60px 15px;
            }

            .cta-container {
                padding: 0 10px;
            }

            .shape {
                display: none; /* Hide floating shapes on mobile */
            }
        }

        /* High-contrast mode support */
        @media (prefers-contrast: high) {
            .cta-section {
                background: #45B5AA;
            }

            .cta-button {
                border: 2px solid #333;
            }
        }

        /* Reduced motion preference */
        @media (prefers-reduced-motion: reduce) {
            .cta-button,
            .shape,
            .pulse {
                animation: none;
            }
        }

        /* Showcase Section */
        .showcase-section {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .showcase-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .showcase-title {
            font-size: clamp(2rem, 4vw, 2.5rem);
            color: #333;
            text-align: center;
            margin-bottom: 60px;
            font-weight: 700;
            position: relative;
        }

        .showcase-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: #45B5AA;
        }

        /* Feature Row */
        .feature-row {
            display: flex;
            align-items: center;
            margin-bottom: 100px;
            gap: 50px;
        }

        .feature-row:last-child {
            margin-bottom: 0;
        }

        .feature-row.reverse {
            flex-direction: row-reverse;
        }

        /* Image Container */
        .feature-image-container {
            flex: 1;
            position: relative;
        }

        .feature-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .feature-image:hover {
            transform: translateY(-10px);
        }

        /* Content Container */
        .feature-content {
            flex: 1;
            padding: 20px;
        }

        .feature-title {
            font-size: clamp(1.5rem, 3vw, 2rem);
            color: #333;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .feature-description {
            font-size: 1.1rem;
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .feature-button {
            display: inline-block;
            padding: 12px 30px;
            background: transparent;
            color: #45B5AA;
            border: 2px solid #45B5AA;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .feature-button:hover {
            background: #45B5AA;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(69, 181, 170, 0.3);
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .showcase-section {
                padding: 60px 0;
            }

            .feature-row {
                flex-direction: column;
                margin-bottom: 60px;
                gap: 30px;
            }

            .feature-row.reverse {
                flex-direction: column;
            }

            .feature-image {
                height: 350px;
            }

            .feature-content {
                text-align: center;
                padding: 0 20px;
            }
        }

        @media (max-width: 768px) {
            .showcase-section {
                padding: 40px 0;
            }

            .showcase-title {
                margin-bottom: 40px;
            }

            .feature-image {
                height: 300px;
            }

            .feature-description {
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .feature-image {
                height: 250px;
            }

            .feature-button {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-content">
                <h1>Transform Your Future with Online Learning</h1>
                <p>Join millions of learners worldwide and explore top-quality courses taught by expert instructors. Start your learning journey today!</p>
                <div class="cta-buttons">
                    <a href="/courses" class="cta-primary">Explore Courses</a>
                    <a href="/about" class="cta-secondary">Learn More</a>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
      

        

        <!-- Courses Section -->
        <section class="courses">
            <div class="section-title">
                <h2>Popular Courses</h2>
                <p>Explore our most sought-after courses and start learning today</p>
            </div>
            <section class="category-section">
            <div class="category-slider-container">
                <button class="category-nav-btn category-prev-btn" id="categoryPrevBtn">
                    <i class="fas fa-chevron-left"></i>
                </button>
                
                <div class="category-slider" id="categorySlider">
                    <div class="category-card active">
                        <div class="category-icon">
                            <i class="fas fa-th"></i>
                        </div>
                        <div class="category-name">All Courses</div>
                    </div>
                    
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-code"></i>
                        </div>
                        <div class="category-name">Programming</div>
                    </div>
                    
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                        <div class="category-name">Web Development</div>
                    </div>
                    
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-database"></i>
                        </div>
                        <div class="category-name">Database</div>
                    </div>
                    
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <div class="category-name">Mobile Dev</div>
                    </div>
                    
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-cloud"></i>
                        </div>
                        <div class="category-name">Cloud Computing</div>
                    </div>
                </div>

                <button class="category-nav-btn category-next-btn" id="categoryNextBtn">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </section>

            <div class="courses-slider-container">
                <button class="slider-nav-btn prev-btn" id="prevBtn">
                    <i class="fas fa-chevron-left"></i>
                </button>
                
                <div class="courses-grid" id="coursesSlider">
                    <!-- SAP Course -->
                    <div class="course-card">
                        <img src="assets/images/sap.jpg" alt="SAP Development" class="course-image">
                        <div class="course-content">
                            <h3 class="course-title">SAP Development</h3>
                            <p class="course-description">Learn SAP development from scratch. Master core concepts and advanced techniques.</p>
                        </div>
                    </div>

                    <!-- DevOps Course -->
                    <div class="course-card">
                        <img src="assets/images/devops.jpg" alt="DevOps" class="course-image">
                        <div class="course-content">
                            <h3 class="course-title">DevOps</h3>
                            <p class="course-description">Master DevOps programming with our comprehensive course.</p>
                        </div>
                    </div>

                    <!-- Python Course -->
                    <div class="course-card">
                        <img src="assets/images/python.jpg" alt="Python Programming" class="course-image">
                        <div class="course-content">
                            <h3 class="course-title">Python Programming</h3>
                            <p class="course-description">Start your programming journey with Python. Learn from basics to advanced.</p>
                        </div>
                    </div>

                    <!-- React Course -->
                    <div class="course-card">
                        <img src="assets/images/react.jpg" alt="React Development" class="course-image">
                        <div class="course-content">
                            <h3 class="course-title">React Development</h3>
                            <p class="course-description">Build modern user interfaces with React. Master frontend development.</p>
                        </div>
                    </div>
                </div>

                <button class="slider-nav-btn next-btn" id="nextBtn">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </section>
        <!-- End of Courses Section -->
        <section id="courseCategories">
            <div class="category-section__header">
                <h2 class="category-section__title">Explore Our Course Categories</h2>
                <p class="category-section__description">
                    Discover a wide range of courses to suit your interests and career goals.
                </p>
            </div>

            <div class="category-grid">
                <div class="category-grid__row">
                    <!-- Programming Category -->
                    <div class="category-item">
                        <div class="category-item__image-wrapper">
                            <img src="assets/images/programming.jpg" 
                                 alt="Programming Courses" 
                                 class="category-item__image">
                            <div class="category-item__overlay">
                                <h3 class="category-item__title">Programming</h3>
                                <a href="courses.php?category=programming" 
                                   class="category-item__button">Explore</a>
                            </div>
                        </div>
                    </div>

                    <!-- Design Category -->
                    <div class="category-item">
                        <div class="category-item__image-wrapper">
                            <img src="assets/images/design.jpg" 
                                 alt="Design Courses" 
                                 class="category-item__image">
                            <div class="category-item__overlay">
                                <h3 class="category-item__title">Design</h3>
                                <a href="courses.php?category=design" 
                                   class="category-item__button">Explore</a>
                            </div>
                        </div>
                    </div>

                    <!-- Business Category -->
                    <div class="category-item">
                        <div class="category-item__image-wrapper">
                            <img src="assets/images/business.jpg" 
                                 alt="Business Courses" 
                                 class="category-item__image">
                            <div class="category-item__overlay">
                                <h3 class="category-item__title">Business</h3>
                                <a href="courses.php?category=business" 
                                   class="category-item__button">Explore</a>
                            </div>
                        </div>
                    </div>

                    <!-- Language Category -->
                    <div class="category-item">
                        <div class="category-item__image-wrapper">
                            <img src="assets/images/language.jpg" 
                                 alt="Language Courses" 
                                 class="category-item__image">
                            <div class="category-item__overlay">
                                <h3 class="category-item__title">Language</h3>
                                <a href="courses.php?category=language" 
                                   class="category-item__button">Explore</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Features Section -->
        <section class="features">
            <div class="section-title">
                <h2>Why Choose Ultrakey Learning</h2>
                <p>Discover the features that make our platform the perfect choice for your learning journey</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <h3>Learn Anywhere</h3>
                    <p>Access your courses anytime, anywhere, on any device</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <h3>Certified Learning</h3>
                    <p>Earn recognized certificates upon course completion</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Expert Instructors</h3>
                    <p>Learn from industry professionals and experts</p>
                </div>
            </div>
        </section>
        <section class="stats">
            <div class="stats-container">
                <div class="stat-item">
                    <div class="stat-number">100K+</div>
                    <div class="stat-label">Active Students</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Expert Instructors</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">1000+</div>
                    <div class="stat-label">Online Courses</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">95%</div>
                    <div class="stat-label">Success Rate</div>
                </div>
            </div>
        </section>
         <section class="testimonials-section">
        <h2 class="testimonials-title">What Our Students Say</h2>
        
        <div class="testimonials-container">
            <div class="testimonials-grid">
                <!-- Testimonial 1 -->
                <div class="testimonial-card fade-in" style="animation-delay: 0s;">
                    <div class="student-info">
                        <img src="assets/images/student1.jpg" 
                             alt="Sandhya" 
                             class="student-image">
                        <div class="student-details">
                            <div class="student-name">Sandhya</div>
                            <div class="student-role">Web Developer</div>
                        </div>
                    </div>
                    <p class="testimonial-text">
                        "Ultrakey Learning has transformed my career. The courses are top-notch and the instructors are amazing!"
                    </p>
                </div>

                <!-- Testimonial 2 -->
                <div class="testimonial-card fade-in" style="animation-delay: 0.2s;">
                    <div class="student-info">
                        <img src="assets/images/student2.jpg" 
                             alt="Neha" 
                             class="student-image">
                        <div class="student-details">
                            <div class="student-name">Neha</div>
                            <div class="student-role">Android Developer</div>
                        </div>
                    </div>
                    <p class="testimonial-text">
                        "I've learned more in 3 months with Ultrakey Learning than I did in a year at university. Highly recommended!"
                    </p>
                </div>

                <!-- Testimonial 3 -->
                <div class="testimonial-card fade-in" style="animation-delay: 0.4s;">
                    <div class="student-info">
                        <img src="assets/images/student3.jpg" 
                             alt="Arun" 
                             class="student-image">
                        <div class="student-details">
                            <div class="student-name">Arun</div>
                            <div class="student-role">Business Analyst</div>
                        </div>
                    </div>
                    <p class="testimonial-text">
                        "The flexibility of online learning combined with the quality of content makes Ultrakey Learning unbeatable."
                    </p>
                </div>
            </div>
        </div>
    </section>
    
<section class="cta-section">
    <!-- Decorative Shapes -->
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>

    <div class="cta-container">
        <h2 class="cta-title">Ready to Start Your Learning Journey?</h2>
        <p class="cta-description">Join thousands of students already learning on Ultrakey Learning</p>
        <div style="display: flex; justify-content: center;"><a href="register.php" class="cta-button pulse">
            Get Started Now
            <span class="btn-shine"></span>
        </a></div>
    </div>
</section>
<section class="showcase-section">
    <div class="showcase-container">
        <h2 class="showcase-title">Experience the Power of Ultrakey Learning</h2>

        <!-- Interactive Lessons -->
        <div class="feature-row">
            <div class="feature-image-container">
                <img src="assets/images/interactive-lessons.jpg" 
                     alt="Interactive Lessons" 
                     class="feature-image">
            </div>
            <div class="feature-content">
                <h3 class="feature-title">Interactive Lessons</h3>
                <p class="feature-description">
                    Engage with our cutting-edge interactive lessons that make learning fun and effective. 
                    Our platform offers a variety of multimedia content, quizzes, and hands-on exercises 
                    to keep you motivated and ensure better retention of knowledge.
                </p>
                <a href="#" class="feature-button">Learn More</a>
            </div>
        </div>

        <!-- Progress Tracking -->
        <div class="feature-row reverse">
            <div class="feature-image-container">
                <img src="assets/images/progress-tracking.jpg" 
                     alt="Progress Tracking" 
                     class="feature-image">
            </div>
            <div class="feature-content">
                <h3 class="feature-title">Comprehensive Progress Tracking</h3>
                <p class="feature-description">
                    Stay on top of your learning journey with our detailed progress tracking system. 
                    Monitor your course completion, quiz scores, and skill development in real-time. 
                    Set personal goals and watch as you achieve them step by step.
                </p>
                <a href="#" class="feature-button">Explore Features</a>
            </div>
        </div>

        <!-- Community Forums -->
        <div class="feature-row">
            <div class="feature-image-container">
                <img src="assets/images/community-forums.jpg" 
                     alt="Community Forums" 
                     class="feature-image">
            </div>
            <div class="feature-content">
                <h3 class="feature-title">Vibrant Learning Community</h3>
                <p class="feature-description">
                    Join our thriving community of learners and educators. Participate in discussions, 
                    share knowledge, and get support from peers and instructors.
                </p>
                <a href="#" class="feature-button">Join the Community</a>
            </div>
        </div>
    </div>
</section>
      
    </main>

    <?php include 'footer.php'; ?>

    <!-- Add this JavaScript for slider functionality -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('coursesSlider');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const cardWidth = slider.querySelector('.course-card').offsetWidth + 30; // Include gap
        let currentPosition = 0;

        // Function to update button states
        function updateButtonStates() {
            prevBtn.disabled = currentPosition <= 0;
            nextBtn.disabled = currentPosition >= slider.scrollWidth - slider.clientWidth;
        }

        // Initialize button states
        updateButtonStates();

        // Previous button click handler
        prevBtn.addEventListener('click', () => {
            currentPosition = Math.max(currentPosition - cardWidth, 0);
            slider.scrollTo({
                left: currentPosition,
                behavior: 'smooth'
            });
            setTimeout(updateButtonStates, 100);
        });

        // Next button click handler
        nextBtn.addEventListener('click', () => {
            currentPosition = Math.min(
                currentPosition + cardWidth,
                slider.scrollWidth - slider.clientWidth
            );
            slider.scrollTo({
                left: currentPosition,
                behavior: 'smooth'
            });
            setTimeout(updateButtonStates, 100);
        });

        // Update button states on scroll
        slider.addEventListener('scroll', () => {
            currentPosition = slider.scrollLeft;
            updateButtonStates();
        });

        // Update on window resize
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                currentPosition = slider.scrollLeft;
                updateButtonStates();
            }, 250);
        });

        // Touch swipe functionality
        let touchStartX = 0;
        let touchEndX = 0;

        slider.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        }, false);

        slider.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, false);

        function handleSwipe() {
            const swipeThreshold = 50;
            const difference = touchStartX - touchEndX;

            if (Math.abs(difference) > swipeThreshold) {
                if (difference > 0) {
                    // Swipe left - go next
                    nextBtn.click();
                } else {
                    // Swipe right - go prev
                    prevBtn.click();
                }
            }
        }
    });
    </script>

    <!-- Add this JavaScript for category slider functionality -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const categorySlider = document.getElementById('categorySlider');
        const categoryPrevBtn = document.getElementById('categoryPrevBtn');
        const categoryNextBtn = document.getElementById('categoryNextBtn');
        const cardWidth = categorySlider.querySelector('.category-card').offsetWidth + 20; // Include gap
        let currentPosition = 0;

        // Function to update button states
        function updateCategoryButtonStates() {
            categoryPrevBtn.disabled = currentPosition <= 0;
            categoryNextBtn.disabled = currentPosition >= categorySlider.scrollWidth - categorySlider.clientWidth;
        }

        // Initialize button states
        updateCategoryButtonStates();

        // Previous button click handler
        categoryPrevBtn.addEventListener('click', () => {
            currentPosition = Math.max(currentPosition - cardWidth, 0);
            categorySlider.scrollTo({
                left: currentPosition,
                behavior: 'smooth'
            });
            setTimeout(updateCategoryButtonStates, 100);
        });

        // Next button click handler
        categoryNextBtn.addEventListener('click', () => {
            currentPosition = Math.min(
                currentPosition + cardWidth,
                categorySlider.scrollWidth - categorySlider.clientWidth
            );
            categorySlider.scrollTo({
                left: currentPosition,
                behavior: 'smooth'
            });
            setTimeout(updateCategoryButtonStates, 100);
        });

        // Category card click handler
        const categoryCards = document.querySelectorAll('.category-card');
        categoryCards.forEach(card => {
            card.addEventListener('click', () => {
                categoryCards.forEach(c => c.classList.remove('active'));
                card.classList.add('active');
            });
        });

        // Touch swipe functionality
        let touchStartX = 0;
        let touchEndX = 0;

        categorySlider.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        }, false);

        categorySlider.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleCategorySwipe();
        }, false);

        function handleCategorySwipe() {
            const swipeThreshold = 50;
            const difference = touchStartX - touchEndX;

            if (Math.abs(difference) > swipeThreshold) {
                if (difference > 0) {
                    categoryNextBtn.click();
                } else {
                    categoryPrevBtn.click();
                }
            }
        }

        // Update on window resize
        window.addEventListener('resize', () => {
            currentPosition = categorySlider.scrollLeft;
            updateCategoryButtonStates();
        });
    });
    </script>

   
</body>
</html>