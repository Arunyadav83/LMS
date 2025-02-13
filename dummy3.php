<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Course Layout</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Base styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
        }

        /* Banner Styles */
        .banner {
            background: linear-gradient(135deg, #45B5AA 0%, #367c76 100%);
            color: white;
            text-align: center;
            padding: 60px 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 300px;
        }

        .banner h1 {
            font-size: clamp(2rem, 5vw, 3.5rem);
            margin-bottom: 20px;
            font-weight: 700;
        }

        .banner p {
            font-size: clamp(1rem, 2.5vw, 1.5rem);
            max-width: 800px;
            margin: 0 auto;
        }

        /* Container Layout */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            gap: 20px;
        }

        /* Left Section */
        .left-section {
            flex: 1;
            max-width: 1000px;
        }

        /* Card Styles */
        .course-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 30px;
            margin-bottom: 30px;
            width: 100%;
        }

        /* Course Content Section */
        .course-content {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 25px;
            margin: 20px 0;
            width: 100%;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }

        .course-content h2 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 10px;
        }

        .course-content > p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        /* Content Details */
        .content details {
            width: 100%;
            border: 1px solid #eee;
            border-radius: 8px;
            margin-bottom: 12px;
        }

        .content summary {
            padding: 15px 20px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
        }

        /* Right Section */
        .right-section {
            width: 280px;
            flex-shrink: 0;
        }

        /* Laptop View Specific Adjustments */
        @media (min-width: 1024px) and (max-width: 1366px) {
            .container {
                max-width: 900px;
                gap: 15px;
                padding: 0 15px;
            }

            .left-section {
                max-width: 650px;
            }

            .right-section {
                width: 260px;
            }

            .course-card,
            .course-content {
                padding: 20px;
                margin-bottom: 15px;
            }

            .course-content {
                max-width: 850px;
            }

            .content summary {
                padding: 12px 15px;
            }

            .section-info {
                font-size: 0.9rem;
            }
        }

        /* Tablet View */
        @media (max-width: 1023px) {
            .container {
                max-width: 900px;
                flex-direction: column;
            }

            .right-section {
                width: 100%;
                margin-top: 20px;
            }

            .course-card,
            .course-content {
                padding: 20px;
            }

            .course-content {
                padding: 15px;
                margin: 10px 0;
            }
        }

        /* Mobile View */
        @media (max-width: 768px) {
            .container {
                padding: 0 10px;
            }

            .course-card,
            .course-content {
                padding: 15px;
                margin-bottom: 15px;
            }

            .right-section {
                display: none;
            }

            .course-content {
                padding: 15px;
                margin: 10px 0;
            }

            .content summary {
                padding: 10px;
            }
        }

        /* Dark Mode Support */
        @media (prefers-color-scheme: dark) {
            .course-card,
            .course-content {
                background: #2d2d2d;
            }

            .course-content h2 {
                color: #fff;
            }

            .content details {
                background: #2d2d2d;
                border-color: #444;
            }

            .content summary {
                background: #333;
                color: #fff;
            }
        }

        /* What You'll Learn Section */
        .what-you-learn {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .what-you-learn h2 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: #333;
        }

        .what-you-learn ul {
            list-style: none;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }

        .what-you-learn li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 0.95rem;
            color: #555;
        }

        .what-you-learn li:before {
            content: '✓';
            color: #45B5AA;
            font-weight: bold;
        }

        /* Course Content Section */
        .course-content > p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        /* Content Details */
        .content {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .section-info {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.95rem;
            color: #333;
        }

        .lecture-count {
            color: #666;
            font-size: 0.85rem;
        }

        /* Lecture list styles */
        .content ul {
            list-style: none;
        }

        .content li {
            padding: 12px 20px;
            border-top: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
        }

        .lecture-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .lecture-info a {
            color: #333;
            text-decoration: none;
        }

        .preview-link {
            color: #45B5AA;
            font-size: 13px;
        }

        .duration {
            color: #666;
            font-size: 13px;
        }

        /* See more button styles */
        .see-more-container {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
        }

        .see-more-btn {
            background: transparent;
            color: #45B5AA;
            border: 1px solid #45B5AA;
            padding: 10px 25px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .see-more-btn:hover {
            background: #45B5AA;
            color: white;
        }

        /* Price and Title */
        .right-section h2 {
            font-size: 1.4rem;
            color: #333;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .right-section .price {
            font-size: 2.2rem;
            font-weight: bold;
            color: #45B5AA;
            margin: 15px 0;
        }

        /* Guarantee Text */
        .right-section > p {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 20px;
        }

        /* Coupon Section */
        .coupon {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 15px 0;
            width: 100%;
        }

        .coupon input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.9rem;
            width: calc(100% - 100px); /* Adjust based on button width */
        }

        .coupon button {
            padding: 10px 20px;
            background: #45B5AA;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            white-space: nowrap;
            min-width: 80px;
        }

        /* Divider */
        hr {
            border: none;
            border-top: 1px solid #eee;
            margin: 20px 0;
        }

        /* Subscribe Section */
        .subscribe {
            margin: 20px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .subscribe input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #45B5AA;
        }

        .subscribe label {
            font-size: 0.95rem;
            color: #555;
            line-height: 1.4;
        }

        /* Enroll Button */
        .enroll-button {
            width: 100%;
            padding: 16px;
            background: #ff6b6b;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.2);
        }

        .enroll-button:hover {
            background: #ff5252;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 107, 0.25);
        }

        /* Container styles */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Grid system */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: -15px;
        }

        .col-9, .col-3 {
            padding: 15px;
        }

        .col-9 {
            flex: 0 0 75%;
            max-width: 75%;
        }

        .col-3 {
            flex: 0 0 25%;
            max-width: 25%;
        }

        /* Course content styles */
        .course-content {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 25px;
            margin-bottom: 20px;
        }

        .course-content h2 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
        }

        .course-content p {
            color: #666;
            margin-bottom: 20px;
            font-size: 14px;
        }

        /* Details/Summary styles */
        .content details {
            border: 1px solid #e0e0e0;
            margin-bottom: 2px;
            border-radius: 4px;
        }

        .content summary {
            padding: 15px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
            font-size: 14px;
        }

        .section-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .lecture-count {
            font-size: 13px;
            color: #666;
        }

        /* Lecture list styles */
        .content ul {
            list-style: none;
        }

        .content li {
            padding: 12px 15px;
            border-top: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
        }

        .lecture-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .lecture-info a {
            color: #333;
            text-decoration: none;
        }

        .preview-link {
            color: #45B5AA;
            font-size: 13px;
        }

        .duration {
            color: #666;
            font-size: 13px;
        }

        /* See more button styles */
        .see-more-container {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
        }

        .see-more-btn {
            background: transparent;
            color: #45B5AA;
            border: 1px solid #45B5AA;
            padding: 10px 25px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .see-more-btn:hover {
            background: #45B5AA;
            color: white;
        }

        /* Responsive styles */
        @media (max-width: 992px) {
            .col-9, .col-3 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .course-content {
                padding: 20px;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .course-content {
                padding: 15px;
            }

            .content summary {
                padding: 12px;
                font-size: 13px;
            }

            .content li {
                padding: 10px 12px;
                font-size: 13px;
                flex-wrap: wrap;
            }

            .lecture-info {
                flex: 0 0 100%;
                margin-bottom: 5px;
            }

            .duration {
                width: 100%;
                text-align: left;
                margin-left: 34px; /* Aligns with lecture title */
            }
        }

        @media (max-width: 576px) {
            .container {
                padding: 10px;
            }

            .course-content h2 {
                font-size: 20px;
            }

            .course-content p {
                font-size: 13px;
            }

            .section-info {
                flex: 1;
                min-width: 0; /* Prevents text overflow */
            }

            .section-info span {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .lecture-count {
                font-size: 12px;
                white-space: nowrap;
            }

            .see-more-btn {
                width: 100%;
                justify-content: center;
                padding: 12px;
            }

            /* Improve touch targets for mobile */
            .content summary,
            .content li,
            .lecture-info a,
            .preview-link {
                padding: 12px;
                min-height: 44px; /* Minimum touch target size */
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            body {
                background-color: #1a1a1a;
                color: #fff;
            }

            .course-content {
                background: #2d2d2d;
            }

            .content details {
                border-color: #404040;
            }

            .content summary {
                background: #333;
                color: #fff;
            }

            .content li {
                border-color: #404040;
            }

            .lecture-info a {
                color: #fff;
            }
        }

        /* Container-topics */
        .container-topics {
            width: 90%;
            margin: auto;
            max-width: 1200px;
        }

        /* Section Headings */
        h2 {
            margin-bottom: 15px;
            font-size: 1.5rem;
            margin-top: 30px;
        }

        /* Related Topics Section */
        .related-topics {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin: 30px 0;
        }

        .related-topics h2 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 20px;
        }

        .buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .buttons button {
            padding: 12px 24px;
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 25px;
            color: #444;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .buttons button:hover {
            background: #45B5AA;
            color: white;
            border-color: #45B5AA;
        }

        /* Course Includes Section */
        .course-includes {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin: 30px 0;
        }

        .course-includes h2 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 20px;
        }

        .course-includes ul {
            list-style: none;
            padding: 0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }

        .course-includes li {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            font-size: 0.95rem;
            color: #444;
        }

        .course-includes .icon {
            font-size: 1.2rem;
            color: #45B5AA;
            min-width: 24px;
            text-align: center;
        }

        /* Tablet Responsive (768px - 1024px) */
        @media (max-width: 1024px) {
            .related-topics,
            .course-includes {
                padding: 20px;
                margin: 20px 0;
            }

            .buttons button {
                padding: 10px 20px;
                font-size: 0.9rem;
            }

            .course-includes ul {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }

            .course-includes li {
                font-size: 0.9rem;
            }
        }

        /* Mobile Responsive (< 768px) */
        @media (max-width: 768px) {
            .related-topics,
            .course-includes {
                padding: 15px;
                margin: 15px 0;
            }

            .related-topics h2,
            .course-includes h2 {
                font-size: 1.3rem;
                margin-bottom: 15px;
            }

            .buttons {
                gap: 8px;
            }

            .buttons button {
                padding: 8px 16px;
                font-size: 0.85rem;
                flex: 1 1 calc(50% - 8px); /* Two buttons per row with gap */
                min-width: 120px;
            }

            .course-includes ul {
                grid-template-columns: 1fr; /* Single column for mobile */
                gap: 10px;
            }

            .course-includes li {
                padding: 12px;
                font-size: 0.85rem;
            }

            .course-includes .icon {
                font-size: 1.1rem;
                min-width: 20px;
            }
        }

        /* Small Mobile Screens (< 375px) */
        @media (max-width: 375px) {
            .related-topics,
            .course-includes {
                padding: 12px;
                margin: 12px 0;
            }

            .buttons button {
                padding: 8px 12px;
                font-size: 0.8rem;
                width: 100%; /* Full width buttons for very small screens */
            }

            .course-includes li {
                padding: 10px;
                font-size: 0.8rem;
            }
        }

        /* Dark Mode Support */
        @media (prefers-color-scheme: dark) {
            .related-topics,
            .course-includes {
                background: #2d2d2d;
            }

            .related-topics h2,
            .course-includes h2 {
                color: #fff;
            }

            .buttons button {
                background: #333;
                border-color: #444;
                color: #fff;
            }

            .buttons button:hover {
                background: #45B5AA;
                color: white;
            }

            .course-includes li {
                background: #333;
                color: #fff;
            }
        }

        /* Top Companies Section Base Styles */
        .top-companies {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin: 30px 0;
        }

        .top-companies h2 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 15px;
        }

        .top-companies p {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .top-companies #learn-more {
            color: #45B5AA;
            text-decoration: none;
            font-weight: 500;
        }

        .top-companies #learn-more:hover {
            text-decoration: underline;
        }

        /* Company Logos Container */
        .company-logos {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
            align-items: center;
        }

        .company-logos img {
            max-width: 100%;
            height: auto;
            filter: grayscale(100%);
            opacity: 0.7;
            transition: all 0.3s ease;
        }

        .company-logos img:hover {
            filter: grayscale(0%);
            opacity: 1;
        }

        /* Large Tablet Responsive (992px - 1200px) */
        @media (max-width: 1200px) {
            .top-companies {
                padding: 25px;
            }

            .company-logos {
                gap: 15px;
            }
        }

        /* Tablet Responsive (768px - 991px) */
        @media (max-width: 991px) {
            .top-companies {
                padding: 20px;
            }

            .top-companies h2 {
                font-size: 1.3rem;
            }

            .company-logos {
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
            }

            .company-logos img {
                max-width: 90%;
                margin: 0 auto;
                display: block;
            }
        }

        /* Mobile Responsive (576px - 767px) */
        @media (max-width: 767px) {
            .top-companies {
                padding: 20px 15px;
                margin: 20px 0;
            }

            .top-companies h2 {
                font-size: 1.2rem;
                margin-bottom: 12px;
            }

            .top-companies p {
                font-size: 0.9rem;
                margin-bottom: 15px;
            }

            .company-logos {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }
        }

        /* Small Mobile Responsive (< 576px) */
        @media (max-width: 575px) {
            .top-companies {
                padding: 15px;
                margin: 15px 0;
            }

            .top-companies h2 {
                font-size: 1.1rem;
            }

            .top-companies p {
                font-size: 0.85rem;
            }

            .company-logos {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .company-logos img {
                max-width: 100%;
            }
        }

        /* Dark Mode Support */
        @media (prefers-color-scheme: dark) {
            .top-companies {
                background: #2d2d2d;
            }

            .top-companies h2 {
                color: #fff;
            }

            .top-companies p {
                color: #ddd;
            }

            .company-logos img {
                filter: grayscale(100%) brightness(1.2);
            }

            .company-logos img:hover {
                filter: grayscale(0%) brightness(1.2);
            }
        }

        /*start-button*/
        .enroll-button {
            display: inline-block;
            padding: 12px 24px;
            font-size: 18px;
            font-weight: bold;
            color: white;
            background: linear-gradient(135deg, #ff7e5f, #ff3f81);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 250px;
        }

        /* Hover Effect */
        .enroll-button:hover {
            background: linear-gradient(135deg, #ff3f81, #ff7e5f);
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .enroll-button {
                font-size: 16px;
                padding: 10px 20px;
                max-width: 100%;
            }
        }
        /*en-button*/
        .right-section {
    position: absolute;
    top: 20px; /* Initial position */
    right: 20px;
    width: 300px;
    background: white;
    padding: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    transition: top 0.2s ease-in-out; /* Smooth movement */
}
/*en-button*/

.students-table {
      width: 100%;
      max-width: 1200px;
      margin: auto;
      border-collapse: collapse;
      background: #fff;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .students-table th,
    .students-table td {
      padding: 16px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    .students-table th {
      background-color: #f4f4f4;
      font-size: 1rem;
      color: #333;
    }

    .students-table td {
      font-size: 0.9rem;
      color: #555;
      vertical-align: middle;
    }

    .course-image {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 4px;
    }

    .course-title {
      font-weight: bold;
      color: #333;
      margin: 0;
      font-size: 1rem;
    }

    .badge {
      display: inline-block;
      background: #ffc107;
      color: #fff;
      font-size: 0.8rem;
      padding: 0.2rem 0.5rem;
      border-radius: 4px;
      margin-left: 0.5rem;
    }

    .rating {
      color: #ff9800;
    }

    .price {
      font-weight: bold;
      color: #333;
    }

    .price del {
      color: #999;
      margin-left: 0.5rem;
    }

    .wishlist-button {
      background: none;
      border: 1px solid #ddd;
      color: #555;
      padding: 0.5rem;
      border-radius: 50%;
      cursor: pointer;
    }

    .wishlist-button:hover {
      background: #f4f4f4;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .students-table {
        display: block;
        overflow-x: auto;
      }

      .students-table th,
      .students-table td {
        white-space: nowrap;
      }
    }
.students-also-bought {
  max-width: 80%;
}

/* Students Also Bought Section */
.students-also-bought {
    padding: 40px 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 15px;
    margin: 40px 0;
}

.students-also-bought h2 {
    font-size: 2rem;
    color: #333;
    text-align: center;
    margin-bottom: 30px;
    position: relative;
    padding-bottom: 15px;
}

.students-also-bought h2::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, #45B5AA, #367c76);
    border-radius: 2px;
}

/* Table Styles */
.students-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 15px;
    margin-top: 20px;
}

.students-table tr {
    background: white;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 12px;
}

.students-table tr:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.students-table th {
    background: #45B5AA;
    color: white;
    padding: 15px;
    font-size: 1rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.students-table th:first-child {
    border-top-left-radius: 12px;
    border-bottom-left-radius: 12px;
}

.students-table th:last-child {
    border-top-right-radius: 12px;
    border-bottom-right-radius: 12px;
}

.students-table td {
    padding: 20px 15px;
    vertical-align: middle;
}

/* Course Image */
.course-image {
    width: 120px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

/* Course Details */
.course-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
    line-height: 1.4;
}

.badge {
    background: linear-gradient(135deg, #FFD700, #FFA500);
    color: #fff;
    padding: 4px 8px;
    border-radius: 20px;
    font-size: 0.75rem;
    margin-left: 10px;
    text-transform: uppercase;
    font-weight: bold;
}

/* Rating Stars */
.rating {
    color: #FFD700;
    font-weight: bold;
}

/* Price Styling */
.price {
    font-weight: bold;
    color: #45B5AA;
}

.price del {
    color: #999;
    margin-left: 5px;
    font-weight: normal;
}

/* Wishlist Button */
.wishlist-button {
    background: none;
    border: 2px solid #45B5AA;
    color: #45B5AA;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 1.2rem;
}

.wishlist-button:hover {
    background: #45B5AA;
    color: white;
    transform: scale(1.1);
}

/* Tablet Responsive */
@media (max-width: 1024px) {
    .students-table thead {
        display: none;
    }

    .students-table tr {
        display: grid;
        grid-template-columns: 120px 1fr;
        gap: 15px;
        margin-bottom: 20px;
        padding: 15px;
    }

    .students-table td {
        padding: 5px 0;
    }

    .students-table td:not(:first-child):not(:nth-child(2)) {
        grid-column: 2;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .students-table td:not(:first-child):not(:nth-child(2))::before {
        content: attr(data-label);
        font-weight: 600;
        min-width: 80px;
    }

    .course-image {
        width: 100%;
        height: 100px;
    }
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .students-also-bought {
        padding: 20px 15px;
    }

    .students-also-bought h2 {
        font-size: 1.5rem;
    }

    .students-table tr {
        grid-template-columns: 1fr;
        gap: 10px;
    }

    .course-image {
        width: 100%;
        height: 150px;
    }

    .course-title {
        font-size: 1rem;
    }

    .badge {
        display: inline-block;
        margin: 5px 0;
    }

    .wishlist-button {
        width: 35px;
        height: 35px;
        font-size: 1rem;
    }
}
@media (max-width: 1024px) {
.course-content {
    
    margin-left: 120px;
}
}
/* Dark Mode Support */
@media (prefers-color-scheme: dark) {
    .students-also-bought {
        background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%);
    }

    .students-also-bought h2 {
        color: #fff;
    }

    .students-table tr {
        background: #2d2d2d;
    }

    .course-title {
        color: #fff;
    }

    .students-table td {
        color: #ddd;
    }
}
@media (max-width: 375px) {
    .students-also-bought {
        margin: 20px 30px;
    }
}
/* Add data-label attributes to table cells for mobile view */
.left-section {
    flex: 0 0 70%;
    max-width: 90%;
}
@media (max-width: 1024px) {
    .course-content {
        margin-left: 0px;
        margin-right: 336px;
    }
}
@media (max-width: 1440px) {
    .course-content {
        margin-left: 0px;
        margin-right: 336px;
    }
}
    </style>
   
</head>
<body>
<?php include 'header.php';?>
<!-- <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 mb-4 animate__animated animate__fadeInDown" >Couse details</h1>
            <!- <p class="lead animate__animated animate__fadeInUp">Empowering minds through innovation</p> ->
        </div>
    </section> -->
    <div class="banner">
    <h1 class="display-4 mb-4 animate__animated animate__fadeInDown" >Class Details</h1>
        <!-- <p>Empowering learners worldwide with quality education and innovative learning solutions.</p> -->
    </div>
<div class="container">
  <div class="row">
        <!-- Left Section -->
        <div class="col-9">
            <div class="left-section">
                <h2>What you'll learn</h2>
                <div class="what-you-learn">
                    <ul>
                        <li>Learn Complete Data Science skillset</li>
                        <li>Master Python Programming</li>
                        <li>Analyze data visualization techniques</li>
                        <li>Become an expert in Statistics</li>
                        <li>Perform data processing with Pandas and SciKitLearn</li>
                    </ul>
                    <div class="show-more">
                        <ul>
                            <li>Master Regression techniques</li>
                            <li>Learn Time Series Analysis</li>
                            <li>Understand Neural Networks</li>
                        </ul>
                    </div>
                    <button class="show-more-button" onclick="toggleShowMore()">Show More</button>
                </div>
            </div>
        </div>

 <!-- Right Section -->
      <div class="right-section" id="scrollingCard"> 
          <h2>Personal</h2>
          <p class="price">₹3,999</p>
          <p>30-Day Money-Back Guarantee</p>
          <div class="coupon">
              <input type="text" placeholder="Enter Coupon">
              <button>Apply</button>
          </div><br /><hr />
            <h2>Subscribe to LMS top courses</h2>
            <div class="subscribe">
              <input type="checkbox" id="subscribe" />
              <label for="subscribe">Yes, I want to subscribe to LMS top courses</label>
            </div>
            <button class="enroll-button">Enroll Now</button>
        </div> 

        
<!-- Related Topics Section -->
<section class="related-topics" id="related-topics">
        <h2>Explore related topics</h2>
        <div class="buttons">
            <button id="topic-data-science">Data Science</button>
            <button id="topic-development">Development</button>
        </div>
    </section>
<!-- Course Includes Section -->
<section class="course-includes" id="course-includes">
        <h2>This course includes:</h2>
        <ul>
            <li><span class="icon"></span> 26 hours on-demand video</li>
            <li><span class="icon">📄</span> 9 articles</li>
            <li><span class="icon">📥</span> 53 downloadable resources</li>
            <li><span class="icon">📱</span> Access on mobile and TV</li>
            <li><span class="icon"></span> Certificate of completion</li>
        </ul>
    </section>

     <!-- Top Companies Section -->
     <section class="top-companies" id="top-companies">
        <h2>Top companies offer this course to their employees</h2>
        <p>
            This course was selected for our collection of top-rated courses trusted by businesses worldwide.
            <a href="#" id="learn-more">Learn more</a>
        </p>
        <div class="company-logos">
            <img src="https://via.placeholder.com/100x40" alt="Company 1" id="logo-nasdaq" />
            <img src="https://via.placeholder.com/100x40" alt="Company 2" id="logo-vw" />
            <img src="https://via.placeholder.com/100x40" alt="Company 3" id="logo-box" />
            <img src="https://via.placeholder.com/100x40" alt="Company 4" id="logo-netapp" />
            <img src="https://via.placeholder.com/100x40" alt="Company 5" id="logo-eventbrite" />
        </div>
    </section><br>


    <!-- Course Content Section -->
    <section class="course-content">
        <h2>Course content</h2>
        <p>30 sections • 281 lectures • 25h 48m total length</p>
        
        <div class="content">
            <details>
                <summary>
                    <div class="section-info">
                        <i class="fas fa-chevron-right"></i>
                        Introduction
                    </div>
                    <span class="lecture-count">4 lectures • 13min</span>
                </summary>
                <ul>
                    <li>
                        <div class="lecture-info">
                            <i class="fas fa-play-circle"></i>
                            <a href="#">Course Introduction</a>
                            <a href="#" class="preview-link">Preview</a>
                        </div>
                        <span class="duration">04:34</span>
                    </li>
                    <li>
                        <div class="lecture-info">
                            <i class="fas fa-play-circle"></i>
                            <a href="#">How to Claim your FREE Gift</a>
                            <a href="#" class="preview-link">Preview</a>
                        </div>
                        <span class="duration">02:04</span>
                    </li>
                    <li>
                        <div class="lecture-info">
                            <i class="fas fa-file-download"></i>
                            <a href="#">Download Course Material</a>
                        </div>
                        <span class="duration">02:25</span>
                    </li>
                    <li>
                        <div class="lecture-info">
                            <i class="fas fa-play-circle"></i>
                            <a href="#">LMS Reviews - Important Message</a>
                        </div>
                        <span class="duration">03:33</span>
                    </li>
                </ul>
            </details>

            <details>
                <summary>
                    <div class="section-info">
                        <i class="fas fa-chevron-right"></i>
                        -- Part 1: Essential Python Programming --
                    </div>
                    <span class="lecture-count">22 lectures • 1hr 43min</span>
                </summary>
                <!-- Add lectures here -->
            </details>
            <details>
                <summary>
                    <div class="section-info">
                        <i class="fas fa-chevron-right"></i>
                        Introduction2
                    </div>
                    <span class="lecture-count">4 lectures • 13min</span>
                </summary>
                <ul>
                    <li>
                        <div class="lecture-info">
                            <i class="fas fa-play-circle"></i>
                            <a href="#">Course Introduction</a>
                            <a href="#" class="preview-link">Preview</a>
                        </div>
                        <span class="duration">04:34</span>
                    </li>
                    <li>
                        <div class="lecture-info">
                            <i class="fas fa-play-circle"></i>
                            <a href="#">How to Claim your FREE Gift</a>
                            <a href="#" class="preview-link">Preview</a>
                        </div>
                        <span class="duration">02:04</span>
                    </li>
                    <li>
                        <div class="lecture-info">
                            <i class="fas fa-file-download"></i>
                            <a href="#">Download Course Material</a>
                        </div>
                        <span class="duration">02:25</span>
                    </li>
                    <li>
                        <div class="lecture-info">
                            <i class="fas fa-play-circle"></i>
                            <a href="#">LMS Reviews - Important Message</a>
                        </div>
                        <span class="duration">03:33</span>
                    </li>
                </ul>
            </details>

            <!-- Add more sections following the same pattern -->
        </div>
    </section>
 <section class="students-also-bought">
    <h2 style="text-align: center; margin: 20px 0;">Students also bought</h2>
  <table class="students-table">
    <thead>
      <tr>
        <th>Course</th>
        <th>Details</th>
        <th>Rating</th>
        <th>Students</th>
        <th>Price</th>
        <th>Wishlist</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
          <img src="assets/images/java.jpg" alt="Course Image" class="course-image">
        </td>
        <td>
          <p class="course-title">Data Science Methods and Algorithms [2025] <span class="badge">Highest Rated</span></p>
          <p>46 total hours • Updated 2/2025</p>
        </td>
        <td data-label="Rating" class="rating">4.9 ★</td>
        <td data-label="Students">974</td>
        <td data-label="Price">
          <span class="price">₹549 <del>₹2,699</del></span>
        </td>
        <td>
          <button class="wishlist-button">♥</button>
        </td>
      </tr>
      <tr>
        <td>
          <img src="assets/images/php.jpg" alt="Course Image" class="course-image">
        </td>
        <td>
          <p class="course-title">Data Science & Machine Learning (Theory+Projects) A-Z</p>
          <p>94 total hours • Updated 2/2025</p>
        </td>
        <td data-label="Rating" class="rating">4.2 ★</td>
        <td data-label="Students">7,200</td>
        <td data-label="Price">
          <span class="price">₹599 <del>₹3,299</del></span>
        </td>
        <td>
          <button class="wishlist-button">♥</button>
        </td>
      </tr>
      <!-- Repeat rows for other courses -->
    </tbody>
  </table>
</section>

</div>   
</div>


<?php include 'footer.php'; ?>
<script>
        function toggleShowMore() {
            const showMoreSection = document.querySelector('.show-more');
            const button = document.querySelector('.show-more-button');
            
            if (showMoreSection.style.display === 'none' || !showMoreSection.style.display) {
                showMoreSection.style.display = 'block';
                button.textContent = 'Show Less';
            } else {
                showMoreSection.style.display = 'none';
                button.textContent = 'Show More';
            }
        }

        window.addEventListener("scroll", function () {
    let card = document.getElementById("scrollingCard");
    let scrollY = window.scrollY; 
    card.style.top = 20 + scrollY + "px";
});
/*sticky*/
window.addEventListener("scroll", function () {
    let card = document.getElementById("scrollingCard");
    let scrollY = window.scrollY; 
    card.style.top = 20 + scrollY + "px"; // Moves the card down as you scroll
});


    </script>
    
</body>
</html>
