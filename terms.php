<?php
// terms.php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Conditions</title>
    <style>
        body {
            position: relative;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        body::before {
            content: '';
            position: fixed; /* Fixed position for the background */
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('assets/images/city-background.jpg'); /* Ensure this path is correct */
            background-size: cover; /* Cover the entire body */
            background-position: center; /* Center the background */
            opacity: 0.03; /* Decreased opacity for more subtle visibility */
            z-index: -1; /* Place behind other content */
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.5); /* Black with 50% transparency */
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            position: relative; /* Ensure the container is positioned relative to the body */
        }

        h2 {
            margin-left: 30px; /* Adjust the right margin as needed */
            color: rgba(144, 238, 144, 1); /* Light green color */
        }

        .contact-info {
            flex: 1;
            margin-right: 20px; /* Space between info and form */
            color: white; /* Change text color for visibility */
        }

        .contact-form {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .contact-form input, .contact-form textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        h1{
            margin-left: 30px;
            color: rgba(255, 99, 71, 0.8); /* Light red color */
            font-size: 45px;
            text-align: center;
        }

        .contact-form button {
            padding: 12px 24px; /* Increased padding for thickness */
            background-color: #4a90e2; /* Light blue button color */
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s; /* Added transform for hover effect */
            font-size: 16px; /* Increased font size for better visibility */
        }

        .contact-form button:hover {
            background-color: #357ab8; /* Darker blue color on hover */
            transform: scale(1.05); /* Slightly enlarge button on hover */
        }

        p {
            margin-left: 20px; /* Add left margin for better spacing */
            margin-bottom: 15px; /* Add bottom margin for spacing between paragraphs */
            line-height: 1.5; /* Improve readability with line height */
            color: black; /* Ensure text color is visible against the background */
        }
    </style>
</head>
<body>
    
   <div class="container-fluid">
    <h1>Terms and Conditions</h1>
    <h2>Privacy Policy</h2>
    <p>
        We value your trust. In order to honour that trust, ultrakey adheres to ethical standards in gathering, using, and safeguarding any information you provide. 
        Think and Learn Private Limited (operating under the brand name ultrakey), is a leading edtech company, incorporated in India, for imparting learning. 
        This privacy policy governs your use of the application ‘ultrakey – The Learning App’ (‘Application’), www.ultrakey.com (‘Website’) and the other associated 
        applications, products, websites and services managed by the Company. Please read this privacy policy (‘Policy’) carefully before using the Application, 
        Website, our services and products, along with the Terms of Use (‘ToU’) provided on the Application and the Website. Your use of the Website, Application, or 
        services in connection with the Application, Website or products (‘Services’), or registrations with us through any modes or usage of any products including 
        through SD cards, tablets or other storage/transmitting device shall signify your acceptance of this Policy and your agreement to be legally bound by the same. 
        If you do not agree with the terms of this Policy, do not use the Website, Application our products or avail any of our Services.
    </p>
    <h2>User Provided Information</h2>
    <p>
        The Application/Website/Services/products obtains the information you provide when you download and register for the Application or Services or products. When 
        you register with us, you generally provide:
        <ul>
            <li>Your name, age, email address, location, phone number, password and your ward’s educational interests;</li>
            <li>Transaction-related information, such as when you make purchases, respond to any offers, or download or use applications from us;</li>
            <li>Information you provide us when you contact us for help;</li>
            <li>Information you enter into our system when using the Application/Services/products, such as while asking doubts, participating in discussions and taking tests.</li>
        </ul>
        <p>
        The said information collected from the users could be categorized as “Personal Information”, “Sensitive Personal Information” and “Associated Information”. 
        Personal Information, Sensitive Personal Information and Associated Information (each as individually defined under this Information Technology (Reasonable 
        security practices and procedures and sensitive personal data or information) Rules, 2011 (the “Data Protection Rules”)) shall collectively be referred to as 
        ‘Information’ in this Policy. We may use the Information to contact you from time to time, to provide you with the Services, important information, required 
        notices and marketing promotions. We will ask you when we need more information that personally identifies you (personal information) or allows us to contact 
        you. We will not differentiate between who is using the device to access the Application, Website or Services or products, so long as the log in/access credentials 
        match with yours. In order to make the best use of the Application/Website/Services/products and enable your Information to be captured accurately on the 
        Application/Website/Services/products, it is essential that you have logged in using your own credentials. We will, at all times, provide the option to you to 
        not provide the Personal Information or Sensitive Personal Information, which we seek from you. Further, you shall, at any time while using the 
        Application/Services/products, also have an option to withdraw your consent given earlier to us to use such Personal Information or Sensitive Personal Information. 
        Such withdrawal of the consent is required to be sent in writing to us at the contact details provided in this Policy below. In such event, however, the Company 
        fully reserves the right not to allow further usage of the Application or provide any Services/products thereunder to you.
    </p>
    </p>
    </div>
    
</body>
</html>
