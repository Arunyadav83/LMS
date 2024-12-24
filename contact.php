<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Ultrakey</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            color: #333;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('assets/images/city-background.jpg');
            background-size: cover;
            background-position: center;
            opacity: 0.9;
            z-index: -1;
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

        h1, h2 {
            color: #fff;
            text-align: center;
            margin-bottom: 20px;
        }

        .main-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
        }

        /* Contact Container */
        .container {
            display: flex;
            max-width: 1100px;
            margin: 20px auto;
        }

        .contact-info, .contact-form {
            flex: 1;
            padding: 30px;
            color: #fff;
            width: 100%;
            height: auto;
        }

        .contact-info {
            background: rgba(0, 0, 0, 0.7);
            border-radius: 20px; 
        }

        .contact-info div {
            margin-bottom: 20px;
            font-size: 16px;
            margin-top: 8%;
            margin-left: 70px;
        }

        .contact-form form {
            display: flex;
            flex-direction: column;
            margin-left: 23px;
            
        }

        .contact-form input, .contact-form textarea {
            width: 90%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .contact-form button {
            background-color: #4a90e2;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 50%;
            margin: 10px auto 0;
        }

        .contact-form button:hover {
            background-color: #357ab7;
            transform: scale(1.05);
        }

        /* Locations Section */
        .locations-section {
            margin-top: 20px;
            text-align: center;
        }

        .locations-container {
            display: flex;
            justify-content: space-evenly;
            flex-wrap: wrap;
        }

        .location-card {
            background: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
            margin: 10px;
            width: 45%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .location-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        iframe {
            border-radius: 8px;
            margin-top: 10px;
            width: 100%;
            height: 200px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .contact-form input, .contact-form textarea {
                width: 100%;
                
            }

            .contact-form button {
                width: 100%;
            }

            .location-card {
                width: 90%;
            }
        }
    </style>
    <link rel="icon" type="image/x-icon" href="assets/images/apple-touch-icon.png">

</head>

<body>
<?php include 'header.php'; ?>
    <div class="main-container">
        <div class="container">
            <div class="contact-info">
                <h1>Contact Us</h1>
                <div><i class="fas fa-map-marker-alt"></i> <strong>Address:</strong> Flat No: 204, 2nd Floor, Cyber Residency, Gachibowli, Hyderabad</div>
                <div><i class="fas fa-phone-alt"></i> <strong>Phone:</strong> 6300440316</div>
                <div><i class="fas fa-envelope"></i> <strong>Email:</strong> <a href="mailto:support@ultrakeyIt.com" style="color: #4a90e2;">support@ultrakeyIt.com</a></div>
            </div>
            <div class="contact-form">
                <h2>Send Message</h2>
                <form action="submit_message.php" method="POST">
                    <input type="text" name="name" placeholder="Full Name" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <textarea name="message" placeholder="Type your message..." rows="4" required></textarea>
                    <button type="submit">Send</button>
                </form>
            </div>
        </div>
    </div>

    <div class="locations-section">
        <h2>Our Locations</h2>
        <div class="locations-container">
            <div class="location-card">
                <h3>Gachibowli</h3>
                <p>Flat No: 204, 2nd Floor, Cyber Residency, Hyderabad</p>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3806.3760464279485!2d78.35711507369068!3d17.441706501240915!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4f7a26b96d6c0b61%3A0x6b3acb732ef5e3!2sUltrakey%20IT%20Solutions%20Private%20Limited!5e0!3m2!1sen!2sin!4v1734329910448!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="location-card">
                <h3>KPHB Colony</h3>
                <p>Flat No. 301, 3rd Floor, Manyavar Building, Hyderabad</p>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d30443.899783221506!2d78.37001637262352!3d17.48422837218086!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bcb91b5be1f29c3%3A0xf5af71d23e422328!2sKukatpally%20Housing%20Board%20Colony%2C%20Kukatpally%2C%20Hyderabad%2C%20Telangana!5e0!3m2!1sen!2sin!4v1734329865253!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>

</html>