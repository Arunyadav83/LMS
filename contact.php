<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Ultrakey</title>
    <style>
        /* Styling same as before */
        body {
            position: relative;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
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

        .main-container {
            width: 100vw;
            padding: 20px 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            backdrop-filter: blur(10px);
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }

        .contact-info {
            flex: 1;
            margin-right: 20px;
            color: white;
        }

        .contact-info div {
            margin-bottom: 20px;
        }

        .contact-form {
            flex: 1;
            padding: 13px;
            color: white;
        }

        .contact-form input, .contact-form textarea {
            width: 70%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .contact-form button {
            padding: 12px 24px;
            background-color: #4a90e2;
            border: none;
            border-radius: 10px;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            font-size: 16px;
            width: 25%;
            margin-left: 25%;
        }

        .locations-section {
            padding: 20px;
        }

        .locations-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .location-card {
            background: #ecf0f1;
            padding: 15px;
            margin: 10px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 30%;
            color: black;
        }

        .map-container {
            margin-top: 10px;
        }
        .section-title{
            color: white;
            text-align: center;
            margin-bottom: 50px;
            /* margin-top: 50px; */
            font-size: 35px;
        }
        h2{
            color: white;
            text-align: center;
            margin-bottom: 50px;
            /* margin-top: 50px; */
            font-size: 35px;
        }
        @media (max-width: 768px) {
            .location-card {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="container">
            <div class="contact-info">
                <h1>Contact Us</h1>
                <div>
                    <i class="fas fa-map-marker-alt"></i>
                    <strong>Address:</strong> Flat No: 204, 2nd Floor, Cyber Residency, above Indian Bank, Indira Nagar, Gachibowli, Hyderabad, Telangana 500032
                </div>
                <div>
                    <i class="fas fa-phone"></i>
                    <strong>Phone:</strong> 6300440316
                </div>
                <div>
                    <i class="fas fa-envelope"></i>
                    <strong>Email:</strong> support@ultrakeyIt.com
                </div>
            </div>
            <div class="contact-form">
                <h2>Send Message</h2>
                <input type="text" placeholder="Full Name" required>
                <input type="email" placeholder="Email" required>
                <textarea placeholder="Type your Message..." rows="4" required></textarea>
                <button type="submit">Send</button>
            </div>
        </div>
    </div>

    <div class="locations-section">
        <h2 class="section-title">Our Locations</h2>
        <div class="locations-container">
            <div class="location-card">
                <h3>Gachibowli</h3>
                <p>Flat No: 204, 2nd Floor, Cyber Residency, above Indian Bank, Indira Nagar, Gachibowli, Hyderabad, Telangana 500032</p>
                <div class="map-container">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3806.3760464279485!2d78.35711507369068!3d17.441706501240915!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4f7a26b96d6c0b61%3A0x6b3acb732ef5e3!2sUltrakey%20IT%20Solutions%20Private%20Limited!5e0!3m2!1sen!2sin!4v1732775159454!5m2!1sen!2sin" 
                        width="300" 
                        height="200" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>
            </div>
            <div class="location-card">
                <h3>KPHB Colony</h3>
                <p>Flat No. 301, 3rd Floor, Manyavar Building, Rd Number 2, Hyderabad, Telangana, 500085</p>
                <div class="map-container">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d315217.9999999999!2d78.3872!3d17.385044!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bcb93e1e1e1e1e1%3A0x3bcb93e1e1e1e1e1!2sKPHB%20Colony%2C%20Hyderabad%2C%20Telangana%2C%20500085!5e0!3m2!1sen!2sin!4v1631234567890&maptype=satellite" 
                        width="300" 
                        height="200" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
