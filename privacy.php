<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - UltraKey Learning</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom animations */
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

        /* Button animations */
        .whatsapp-btn, .call-btn {
            position: fixed;
            bottom: 5%;
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
            right: 5%;
            background-color: #25D366;
        }

        .call-btn {
            left: 5%;
            background-color: #2598d3;
        }

        .whatsapp-btn i, .call-btn i {
            color: #fff;
            font-size: 24px;
            animation: beat 2s ease-in-out infinite;
        }
    </style>
    <link rel="icon" type="image/x-icon" href="assets/images/apple-touch-icon.png">
</head>

<body class="bg-gray-50 text-gray-900 font-sans">

    <?php include 'header.php'; ?>

    <!-- Hero Image Section -->
    <!-- Hero Image Section -->
<div class="relative w-full">
    <img src="assets/images/privacypolicy.jpg" alt="Privacy Policy" class="w-full h-auto max-h-96 object-cover shadow-lg rounded-lg" style="margin-top: 77px;">
</div>


    <!-- Content Section -->
    <div class="container mx-auto mt-10 mb-20 px-4">
        <h2 class="text-xl font-semibold text-indigo-600">Embarking on a Secure Learning Adventure</h2>
        <p class="mt-4 text-lg leading-relaxed text-gray-700">
            Welcome to UltraKey Learning, where your privacy is our north star. As you navigate through our
            Learning Management System (LMS), we want you to feel confident that your personal information
            is protected with the utmost care. This privacy policy is your map to understanding how we
            collect, use, and safeguard your data. For more about our commitment to your privacy, visit
            <a href="https://www.ultrakeylearning.com" class="text-blue-600 hover:underline" target="_blank" rel="noopener noreferrer">www.ultrakeylearning.com</a>.
        </p>

        <h2 class="text-xl font-semibold mt-10 text-blue-600">The Information We Gather</h2>
        <p class="mt-4 text-lg text-gray-700">Your journey with UltraKey Learning involves sharing certain information with us:</p>
        <ul class="list-disc pl-6 mt-4 text-lg text-gray-700 space-y-3">
            <li>Your digital identity: name, email, and contact details upon registration.</li>
            <li>Your learning footprints: course progress, assignments, and test results.</li>
            <li>Technical breadcrumbs: IP address and device information for system security.</li>
            <li>Financial details: payment information for purchases, handled with bank-grade security.</li>
        </ul>

        <h2 class="text-xl font-semibold mt-10 text-cyan-600">How We Utilize Your Information</h2>
        <ul class="list-disc pl-6 mt-4 text-lg text-gray-700 space-y-3">
            <li>Unlocking knowledge: Providing access to learning materials and tracking your educational journey.</li>
            <li>Tailoring your experience: Personalizing the learning path based on your unique needs and preferences.</li>
            <li>Keeping you in the loop: Communicating updates about courses, events, and important changes.</li>
            <li>Evolving our platform: Improving our services and developing new features to enhance your learning.</li>
            <li>Fortifying our digital walls: Ensuring the security and integrity of our platform.</li>
        </ul>

        <h2 class="text-xl font-semibold mt-10 text-green-600">Shielding Your Data</h2>
        <p class="mt-4 text-lg text-gray-700">
            Your data is a treasure, and we treat it as such. We employ industry-standard security measures,
            including state-of-the-art encryption, rigorous access controls, and vigilant system monitoring.
            Your information resides on secure servers, shared with third parties only when explicitly
            stated or required by law.
        </p>

        <h2 class="text-xl font-semibold mt-10 text-yellow-600">Your Rights in the Digital Realm</h2>
        <ul class="list-disc pl-6 mt-4 text-lg text-gray-700 space-y-3">
            <li>Access your personal information vault at any time.</li>
            <li>Request modifications or deletions to keep your data accurate and relevant.</li>
            <li>Revoke consent for data processing whenever you choose.</li>
            <li>Obtain a portable copy of your data for your records.</li>
            <li>Voice concerns to supervisory authorities if you feel your rights have been compromised.</li>
        </ul>

        <h2 class="text-xl font-semibold mt-10 text-red-600">Navigating Cookies and Tracking</h2>
        <p class="mt-4 text-lg text-gray-700">
            We use cookies and similar technologies to enhance your journey through our platform.
            Think of them as friendly guides helping you navigate. You're in control - manage your
            cookie preferences through your browser settings at any time.
        </p>

        <h2 class="text-xl font-semibold mt-10 text-indigo-600">Evolving with You</h2>
        <p class="mt-4 text-lg text-gray-700">
            As we grow and evolve, so might this privacy policy. We'll keep you informed of any
            significant changes via email or through prominent notices on our website. Your privacy
            journey with us is always transparent and up-to-date.
        </p>
    </div>

    <?php include 'footer.php'; ?>

    <!-- WhatsApp Button -->
    <a href="https://wa.me/9490072008?text=hello+123" target="_blank" class="whatsapp-btn animate-pulse">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Call Button -->
    <a href="tel:91 9490072008" class="call-btn animate-pulse">
        <i class="fas fa-phone"></i>
    </a>

</body>

</html>
