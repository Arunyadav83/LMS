<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        a {
            text-decoration: none;
        }
        
        footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: white;
            font-size: 14px;
            position: relative;
            height: 360px;
        }

        footer a:hover {
            text-decoration: underline;
        }

        /* Media Query for Column View */
        @media (max-width: 768px) {
            footer {
                height: 1070px; /* Increase height for smaller screens */
            }

            .col-md-4, .col-md-2 {
                margin-bottom: 20px; /* Add spacing between columns */
            }

            .social-icons {
                display: flex;
                justify-content: center;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <footer class="bg-dark text-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>About Ultrakey Learning</h5>
                    <p>Empowering learners worldwide with cutting-edge online education. Join us on a journey of knowledge and growth.</p>
                    <div class="social-icons mt-3">
                        <a href="#" class="text-light me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-light me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-light me-2"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-light">Home</a></li>
                        <li><a href="courses.php" class="text-light">Courses</a></li>
                        <li><a href="about.php" class="text-light">About Us</a></li>
                        <li><a href="contact.php" class="text-light">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4">
                    <h5>Support</h5>
                    <ul class="list-unstyled">
                        <li><a href="faq.php" class="text-light">FAQ</a></li>
                        <li><a href="help-center.php" class="text-light">Help Center</a></li>
                        <li><a href="terms.php" class="text-light">Terms of Service</a></li>
                        <li><a href="privacy.php" class="text-light">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Subscribe to Our Newsletter</h5>
                    <p>Stay updated with our latest courses and educational tips.</p>
                    <form action="subscribe.php" method="post" class="mt-3">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Enter your email" required>
                            <button class="btn btn-primary" type="submit">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="mt-4 mb-3">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p>&copy; <?php echo date('Y'); ?> Ultrakey Learning. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="terms.php" class="text-light me-2">Terms</a>
                    <a href="privacy.php" class="text-light me-2">Privacy</a>
                    <a href="cookies.php" class="text-light">Cookies</a>
                    <a href="disclaimer.php" class="text-light">Disclaimer</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit-code.js" crossorigin="anonymous"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
