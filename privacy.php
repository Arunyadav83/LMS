<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - UltraKey Learning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            line-height: 1.8;
            background-color: #f8f9fa;
            color: #333;
        }

        /* General styling for the header */
        header {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 0;
            margin: 0;
            background-color: #f5f5f5;
        }

        /* Remove space between header and image */
        header img {
            max-width: 50px;
            height: auto;
            margin-right: 10px;
        }

        header h1 {
            margin: 0;
            font-size: 1.5rem;
            line-height: 1;
            color: #333;
        }

        /* Remove extra space between image and text */
        .hero-image {
            width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: cover;
            margin: 0; /* Remove margin */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        /* Make the image responsive */
        @media (max-width: 768px) {
            .hero-image {
                max-height: 250px;
                margin-top: 55px;
                object-fit: contain;
            }
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .content {
            margin-top: 30px;
            margin-bottom: 60px;
        }

        .content h2 {
            font-weight: 300;
            margin-top: 50px;
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 2px solid;
        }

        .content p,
        .content li {
            font-size: 1.1rem;
            margin-bottom: 20px;
            line-height: 1.8;
        }

        .whatsapp-btn,
        .call-btn {
            position: fixed;
            bottom: 20px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            animation: breathe 2s ease-in-out infinite;
            z-index: 9999;
        }

        .whatsapp-btn {
            right: 20px;
            background-color: #25D366;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: white;
            font-size: 14px;
            position: relative;
        }

        footer a {
            color: #4facfe;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        .call-btn {
            left: 20px;
            background-color: #2598d3;
        }

        .container {
            margin-top: 5%;
        }

        .whatsapp-btn i,
        .call-btn i {
            color: #fff;
            font-size: 24px;
            animation: beat 2s ease-in-out infinite;
        }

        @keyframes breathe {
            0% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.5);
            }

            70% {
                box-shadow: 0 0 0 15px rgba(37, 211, 102, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(0, 0, 0, 0);
            }
        }

        @keyframes beat {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
            }
        }

        img {
            height: 300px;
            width: 70%;
        }
    </style>
</head>

<body>

    <?php include 'header.php'; ?>

    <img src="assets/images/privacypolicy.jpg " alt="Privacy Policy" class="hero-image">

    <div class="content container-fluid" style="margin-bottom: 5%;">
        <h2 style="color: #6ab1e7;">Embarking on a Secure Learning Adventure</h2>
        <p>
            Welcome to UltraKey Learning, where your privacy is our north star. As you navigate through our
            Learning Management System (LMS), we want you to feel confident that your personal information
            is protected with the utmost care. This privacy policy is your map to understanding how we
            collect, use, and safeguard your data. For more about our commitment to your privacy, visit
            <a href="https://www.ultrakeylearning.com" class="text-primary" target="_blank" rel="noopener noreferrer">www.ultrakeylearning.com</a>.
        </p>

        <h2 style="color: #4a90e2;">The Information We Gather</h2>
        <p>Your journey with UltraKey Learning involves sharing certain information with us:</p>
        <ul>
            <li>Your digital identity: name, email, and contact details upon registration.</li>
            <li>Your learning footprints: course progress, assignments, and test results.</li>
            <li>Technical breadcrumbs: IP address and device information for system security.</li>
            <li>Financial details: payment information for purchases, handled with bank-grade security.</li>
        </ul>

        <h2 style="color: #5bc0de;">How We Utilize Your Information</h2>
        <ul>
            <li>Unlocking knowledge: Providing access to learning materials and tracking your educational journey.</li>
            <li>Tailoring your experience: Personalizing the learning path based on your unique needs and preferences.</li>
            <li>Keeping you in the loop: Communicating updates about courses, events, and important changes.</li>
            <li>Evolving our platform: Improving our services and developing new features to enhance your learning.</li>
            <li>Fortifying our digital walls: Ensuring the security and integrity of our platform.</li>
        </ul>

        <h2 style="color: #5cb85c;">Shielding Your Data</h2>
        <p>
            Your data is a treasure, and we treat it as such. We employ industry-standard security measures,
            including state-of-the-art encryption, rigorous access controls, and vigilant system monitoring.
            Your information resides on secure servers, shared with third parties only when explicitly
            stated or required by law.
        </p>

        <h2 style="color: #f0ad4e;">Your Rights in the Digital Realm</h2>
        <p>In our digital kingdom, you wield significant power over your data:</p>
        <ul>
            <li>Access your personal information vault at any time.</li>
            <li>Request modifications or deletions to keep your data accurate and relevant.</li>
            <li>Revoke consent for data processing whenever you choose.</li>
            <li>Obtain a portable copy of your data for your records.</li>
            <li>Voice concerns to supervisory authorities if you feel your rights have been compromised.</li>
        </ul>

        <h2 style="color: #d9534f;">Navigating Cookies and Tracking</h2>
        <p>
            We use cookies and similar technologies to enhance your journey through our platform.
            Think of them as friendly guides helping you navigate. You're in control - manage your
            cookie preferences through your browser settings at any time.
        </p>

        <h2 style="color: #6ab1e7;">Evolving with You</h2>
        <p>
            As we grow and evolve, so might this privacy policy. We'll keep you informed of any
            significant changes via email or through prominent notices on our website. Your privacy
            journey with us is always transparent and up-to-date.
        </p>
    </div>

    <?php include 'footer.php'; ?>

    <a href="https://wa.me/9490072008?text=hello+123" target="_blank" class="whatsapp-btn">
        <i class="fab fa-whatsapp"></i>
    </a>
    <a href="tel:91 9490072008" class="call-btn">
        <i class="fas fa-phone"></i>
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
