
<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Learning Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        .contact-section {
            padding: 0px 0;
            background: linear-gradient(135deg, var(--navbar-bg-start), var(--navbar-bg-end));
            min-height: calc(100vh - 100px);
            margin-top: 60px;
        }

        .contact-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
            transition: transform var(--transition-speed) ease;
        }

        .contact-card:hover {
            transform: translateY(-5px);
        }

        .contact-info {
            background: var(--primary-color);
            color: white;
            border-radius: 15px;
            padding: 40px;
        }

        .contact-info i {
            font-size: 24px;
            margin-right: 15px;
            color: var(--secondary-color);
        }

        .contact-form input,
        .contact-form textarea {
            border: 2px solid #eee;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
            transition: all var(--transition-speed) ease;
        }

        .contact-form input:focus,
        .contact-form textarea:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
        }

        .contact-form button {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all var(--transition-speed) ease;
        }

        .contact-form button:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }

        .map-container {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-top: 40px;
        }

        .map-container iframe {
            width: 100%;
            height: 300px;
            border: none;
        }

        .contact-info-item {
            margin-bottom: 25px;
            display: flex;
            align-items: center;
        }

        .success-message {
            background: var(--secondary-color);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-top: 20px;
            display: none;
        }

        @media (max-width: 768px) {
            .contact-section {
                padding: 40px 0;
            }
            .contact-card {
                padding: 20px;
            }
            .contact-info {
                margin-bottom: 30px;
            }
        }
        .page-banner {
    background: linear-gradient(45deg, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('assets/images/banner-bg.jpg');
    background-size: cover;
    background-position: center;
    padding: 100px 0 50px;
    margin-bottom: 50px;
    text-align: center;
    color: white;
}

.page-banner h1 {
    font-size: 2.5rem;
    margin-bottom: 15px;
    color: white;
}

.breadcrumb {
    background: transparent;
    justify-content: center;
    margin: 0;
    padding: 0;
}

.breadcrumb-item, .breadcrumb-item a {
    color: white;
}

.breadcrumb-item.active {
    color: var(--secondary-color);
}

.breadcrumb-item + .breadcrumb-item::before {
    color: white;
}

.contact-section {
    padding: 0px 0;
    min-height: calc(100vh - 100px);
    margin-top: 0;
    background: transparent;
}

@media (max-width: 768px) {
    .page-banner {
        padding: 80px 0 30px;
    }
    
    .page-banner h1 {
        font-size: 2rem;
    }
}
.map-container {
    border-radius: 0;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    margin-top: 40px;
    width: 100vw;
    position: relative;
    left: 50%;
    right: 50%;
    margin-left: -50vw;
    margin-right: -50vw;
}

.map-container iframe {
    width: 100%;
    height: 450px;
    border: none;
    display: block;
}

@media (max-width: 768px) {
    .map-container iframe {
        height: 300px;
    }
}
   
.contact-section-card {
            padding: 60px 20px;
            background: linear-gradient(135deg, #e7e7e7, #e7e7e7);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .contact-container-card {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .contact-cards {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
            width: 300px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 250px;
        }

        .contact-cards h2 {
            margin-bottom: 15px;
            font-size: 20px;
            color: #333;
        }

        .contact-cards i {
            font-size: 40px;
            color: #007bff;
            margin-bottom: 10px;
        }

        .contact-cards p, .contact-card a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        .contact-cards a:hover {
            color: #007bff;
        }

        @media (max-width: 768px) {
            .contact-cards {
                width: 100%;
                max-width: 300px;
            }
        }

        /* Base styles */
.contact-section {
    padding: 40px 0;
    min-height: auto;
    margin-top: 0;
}

/* Large devices (desktops) - default styles */
@media screen and (min-width: 1200px) {
    .contact-container-card {
        max-width: 1140px;
        margin: 0 auto;
    }
    
    .contact-cards {
        width: 350px;
    }
    
    .map-container iframe {
        height: 450px;
    }
}

/* Medium devices (tablets, less than 1200px) */
@media screen and (max-width: 1199.98px) {
    .contact-container-card {
        padding: 0 20px;
    }
    
    .contact-cards {
        width: 300px;
    }
    
    .contact-card {
        padding: 30px;
    }
}

/* Small devices (landscape tablets, less than 992px) */
@media screen and (max-width: 991.98px) {
    .hero-section h1 {
        font-size: 2.5rem;
    }
    
    .contact-section-card {
        padding: 40px 15px;
    }
    
    .contact-container-card {
        gap: 15px;
    }
    
    .contact-cards {
        min-height: 220px;
        padding: 25px;
    }
    
    .map-container iframe {
        height: 400px;
    }
}

/* Extra small devices (portrait tablets and phones, less than 768px) */
@media screen and (max-width: 767.98px) {
    .hero-section h1 {
        font-size: 2rem;
        margin-bottom: 20px;
    }
    
    .contact-section-card {
        padding: 30px 10px;
    }
    
    .contact-cards {
        width: 100%;
        max-width: 100%;
        min-height: 200px;
        margin-bottom: 15px;
    }
    
    .contact-card {
        padding: 20px;
    }
    
    .contact-info {
        padding: 25px;
        margin-bottom: 20px;
    }
    
    .contact-info-item {
        margin-bottom: 20px;
    }
    
    .contact-form {
        padding: 0;
    }
    
    .contact-form input,
    .contact-form textarea {
        margin-bottom: 15px;
        padding: 10px;
    }
    
    .map-container {
        margin-top: 30px;
    }
    
    .map-container iframe {
        height: 300px;
    }
}

/* Very small devices (phones, less than 576px) */
@media screen and (max-width: 575.98px) {
    .hero-section {
        padding: 40px 0 20px;
    }
    
    .hero-section h1 {
        font-size: 1.75rem;
    }
    
    .contact-section-card {
        padding: 20px 10px;
    }
    
    .contact-cards {
        padding: 20px;
        min-height: 180px;
    }
    
    .contact-cards i {
        font-size: 32px;
    }
    
    .contact-cards h2 {
        font-size: 18px;
        margin-bottom: 10px;
    }
    
    .contact-info {
        padding: 20px;
    }
    
    .contact-info i {
        font-size: 20px;
        margin-right: 10px;
    }
    
    .contact-info-item h5 {
        font-size: 16px;
    }
    
    .contact-form h3 {
        font-size: 20px;
        margin-bottom: 15px;
    }
    
    .contact-form button {
        width: 100%;
        padding: 10px;
    }
    
    .map-container iframe {
        height: 250px;
    }
}

/* Fix for devices with very small height */
@media screen and (max-height: 600px) {
    .contact-section {
        min-height: auto;
        padding: 20px 0;
    }
    
    .map-container iframe {
        height: 200px;
    }
}

/* Ensure proper display on landscape orientation */
@media screen and (max-width: 991.98px) and (orientation: landscape) {
    .contact-container-card {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
    }
    
    .contact-cards {
        min-height: 180px;
    }
}

/* High-resolution screens (Retina displays) */
@media screen and (-webkit-min-device-pixel-ratio: 2),
       screen and (min-resolution: 192dpi) {
    .contact-cards,
    .contact-card {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }
}
   
    </style>
</head>

<body>
   
    <!-- Add this right after the header.php include -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 mb-4 animate__animated animate__fadeInDown" >Contact Us</h1>
            <!-- <p class="lead animate__animated animate__fadeInUp">Empowering minds through innovation</p> -->
        </div>
    </section>
    <section class="contact-section-card">
        <div class="contact-container-card">
            <div class="contact-cards">
                <i class="fas fa-map-marker-alt"></i>
                <h2>Visit Us</h2>
                <p>Street No2 <br>Gachibowli , Cyber Residency</p>
                <a href="#">Get Directions →</a>
            </div>
            <div class="contact-cards">
                <i class="fas fa-phone"></i>
                <h2>Call Us</h2>
                <p>+91 234-567-8900<br>Mon-Fri: 9:00 AM - 6:00 PM</p>
                <a href="tel:+12345678900">Call Now →</a>
            </div>
            <div class="contact-cards">
                <i class="fas fa-envelope"></i>
                <h2>Email Us</h2>
                <p>info@ultrakeyit.com<br>support@ultrakeyit.com</p>
                <a href="mailto:info@organicfarm.com">Send Email →</a>
            </div>
        </div>
    </section>
    <section class="contact-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="contact-card">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="contact-info">
                                    <h2 class="mb-4">Get in Touch</h2>
                                    <div class="contact-info-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <div>
                                            <h5 class="mb-0">Our Location</h5>
                                            <p class="mb-0">Flat No: 204, 2nd Floor,<br>Cyber Residency, Gachibowli,<br>Hyderabad</p>
                                        </div>
                                    </div>
                                    <div class="contact-info-item">
                                        <i class="fas fa-phone"></i>
                                        <div>
                                            <h5 class="mb-0">Phone</h5>
                                            <p class="mb-0">+91 6300440316</p>
                                        </div>
                                    </div>
                                    <div class="contact-info-item">
                                        <i class="fas fa-envelope"></i>
                                        <div>
                                            <h5 class="mb-0">Email</h5>
                                            <a href="mailto:support@ultrakeyIt.com" class="text-white text-decoration-none">support@ultrakeyIt.com</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <form id="contactForm" class="contact-form" action="submite_message.php" method="POST" >
                                    <h3 class="mb-4">Send Message</h3>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="name" placeholder="Your Name" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control" name="email" placeholder="Your Email" required>
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control" name="message" rows="5" placeholder="Your Message" required></textarea>
                                    </div>
                                    <button type="submit" class="btn">Send Message</button>
                                </form>
                                <div id="successMessage" class="success-message">
                                    Thank you for your message! We'll get back to you soon.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="map-container">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3806.3760464279485!2d78.35711507369068!3d17.441706501240915!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4f7a26b96d6c0b61%3A0x6b3acb732ef5e3!2sUltrakey%20IT%20Solutions%20Private%20Limited!5e0!3m2!1sen!2sin!4v1734329910448!5m2!1sen!2sin" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#contactForm').on('submit', function(e) {
                e.preventDefault();
                
                $.ajax({
                    type: 'POST',
                    url: 'submit_message.php',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if(response.success) {
                            $('#contactForm').hide();
                            $('#successMessage').fadeIn();
                        } else {
                            alert(response.message || 'Something went wrong. Please try again.');
                        }
                    },
                    error: function() {
                        alert('Something went wrong. Please try again.');
                    }
                });
            });
        });
    </script>
</body>
</html>