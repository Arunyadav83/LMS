<?php
include 'header.php';

class About
{
    private $courses;
    private $tutors;

    public function __construct()
    {
        $this->courses = $this->fetchCoursesFromDatabase();
        $this->tutors = $this->fetchTutorsFromDatabase();
    }

    private function fetchCoursesFromDatabase()
    {
        // Database connection parameters
        $host = 'localhost';
        $db = 'lms';
        $user = 'root';
        $pass = '';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT id, title FROM courses");
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return [];
        }
    }

    private function fetchTutorsFromDatabase()
    {
        $host = 'localhost';
        $db = 'lms';
        $user = 'root';
        $pass = '';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT id, full_name, email, role, bio, specialization, resume_path, certificate_path, created_at FROM tutors");
            $stmt->execute();

            $tutors = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($tutors as &$tutor) {
                $image_name = strtolower(str_replace(' ', '_', $tutor['full_name'])) . '.jpg';
                $tutor['image'] = 'assets/images/' . $image_name;
            }

            return $tutors;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return [];
        }
    }
    public function displayAbout()
    {
        echo "<h2 class='section-title'>
                <span class='subtitle'>World-Class Instructors</span><br>
                Classes Taught By Real Creators
              </h2>";
        echo "<div class='tutors-section'>";

        foreach ($this->tutors as $tutor) {
            echo "<div class='tutor-card'>
                    <div class='card'>
                        <div class='card-body'>
                            <div class='d-flex flex-column align-items-center'>
                                <img src='" . htmlspecialchars($tutor['image']) . "' alt='" . htmlspecialchars($tutor['full_name']) . "' class='tutor-image mb-4'>
                                <h3 class='tutor-name'>" . htmlspecialchars($tutor['full_name']) . "</h3>
                                <p class='tutor-specialization'>" . htmlspecialchars($tutor['specialization']) . "</p>
                                <button class='btn btn-primary bio-btn' onclick='showBio(" . $tutor['id'] . ")'>View Bio</button>
                            </div>
                        </div>
                    </div>
                </div>";
        }

        echo "</div>";

        echo "<h2 class='section-title'>Courses Offered</h2>";
        echo "<div class='course-slider'>";

        foreach ($this->courses as $course) {
            $image_name = strtolower(str_replace(' ', '_', $course['title'])) . '.jpg';
            echo "<div class='course-item'>
                    <img src='assets/images/{$image_name}' alt='" . htmlspecialchars($course['title']) . "' class='course-image'>
                    <p class='course-title'>" . htmlspecialchars($course['title']) . "</p>
                  </div>";
        }

        echo "</div>";  // Close courses slider
    }

    public function displayWelcomeMessage()
    {
        echo "<div class='welcome-section'>";

        echo "<h1>About Us</h1>";

        echo "<p>Welcome to Ultrakey, your trusted partner in academic success and personal growth!</p>";

        echo '<h2 style="text-align: center; margin-top:4%">Why Choose Us?</h2>';



        echo "<div class='why-choose-us-cards'>";

        echo "<div class='why-card'>
                <img src='assets/images/expert_educators.png' alt='Expert Educators'>
                <h3>Expert Educators</h3>
                <p>Innovative teaching methodologies to ensure student success.</p>
              </div>";

        echo "<div class='why-card'>
                <img src='assets/images/motivators.png' alt='Motivators'>
                <h3>Motivating Environment</h3>
                <p>Encouraging growth and consistent progress.</p>
              </div>";

        echo "<div class='why-card'>
                <img src='assets/images/outstanding_students.png' alt='Outstanding Students'>
                <h3>Outstanding Students</h3>
                <p>A dynamic learning ecosystem for thriving students.</p>
              </div>";

        echo "</div>";
        echo "</div>";
    }
}

$about = new About();
$about->displayWelcomeMessage();
$about->displayAbout();

include 'footer.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Ultrakey</title>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #2c3e50;
            overflow-x: hidden;
        }

        h1,
        h2 {
            color: #2980b9;
        }

        h3 {
            color: #34495e;
        }

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
        }

        footer a {
            color:rgb(163, 187, 207);
            text-decoration: none;
            font-size: larger;
        }

        footer a:hover {
            text-decoration: underline;
        }

        .welcome-section {
            position: relative;
            color: white;
            text-align: center;
            padding: 50px 20px;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)),
                url('assets/images/about_us_background.jpg') no-repeat center/cover;
            background-attachment: fixed;
            opacity: 0.9;
            animation: slideIn 1.5s ease-out forwards;
        }

        .welcome-section h1 {
            text-align: center;
            font-weight: bold;
        }

        .welcome-section p {
            text-align: center;
            font-size: 1.2em;
            margin-top: 20px;

        }

        .welcome-section .section-title {
            text-align: center;

            margin-top: 40px;


        }

        .tutors-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            /* Space between grid items */
            margin-top: 30px;
            padding: 10px;
        }


        .tutor-card {
            background: #ffffff;
            margin: 0 15px;
            border-radius: 8px;
            max-width: 300px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1)
                /* box-shadow: 0 4
            px 6px rgba(0, 0, 0, 0.1);
            
            text-align: center;
            max-width: 300px;
            transition: transform 0.3s, box-shadow 0.3s; */
        }

        .tutor-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }



        /* Responsive for smaller screens */
        @media (max-width: 768px) {
            .tutors-section {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                /* Adjusted for smaller screens */
                gap: 15px;
                /* Reduced gap for smaller screens */
            }

        }

        @media (max-width: 480px) {
            .welcome-section {
                padding: 0px;
                width: 100%;
            }

            .why-card {
                width: 250px !important;
            }

            .why-choose-us-cards {

                min-width: 0px;


            }
        }

        /* Responsive for very small screens */
        @media (max-width: 480px) {
            .tutors-section {
                grid-template-columns: 1fr;
                /* One column layout on very small screens */
                gap: 10px;
                /* Reduced gap even further for small devices */
            }
        }


        .tutor-card {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .tutor-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }


        .tutor-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 20px;
        }

        .tutor-name {
            font-size: 1.5em;
            font-weight: bold;
            color: #2980b9;
        }

        .tutor-specialization {
            font-size: 1.1em;
            color: #7f8c8d;
            margin-bottom: 20px;
        }

        .bio-btn {
            background-color: #2980b9;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }


        .bio-btn:hover {
            background-color: #3498db;
        }

        /* Course Section */
        .course-slider {
            margin-top: 40px;
        }

        .course-item {
            margin: 0 15px;
            /* Add horizontal margin between the course cards */
        }

        /* Slick Slider Fix to Handle Spacing */
        .slick-slide {
            display: flex;
            /* Ensure proper alignment */
            justify-content: center;
            padding: 0;
            /* Remove padding if any */
        }


        .section-title {
            text-align: center;
            font-size: 2.5em;
            color: #2c3e50;
            font-weight: 900;
            letter-spacing: 0.1em;
            line-height: 0.5;
            margin: 40px 0;
            font-family: 'Montserrat', sans-serif;
        }

        .subtitle {
            color: #e74c3c;
            font-size: 0.8em;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            margin-bottom: 10px;
            display: block;
        }


        .course-item {
            padding: 15px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .course-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .course-item img {
            width: 100%;
            border-radius: 8px;
            max-height: 200px;
            object-fit: cover;
            margin-bottom: 15px;


        }

        .course-title {
            font-size: 1.1em;
            font-weight: bold;
            color: #34495e;
        }

        /* Why Choose Us Section */
        .why-choose-us-cards {
            display: flex;
            gap: 40px;
            min-width: 300px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 40px;

        }


        .why-card {
            background-color: #ecf0f1;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .why-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .why-card img {
            width: 100px;
            margin-bottom: 15px;
        }

        .why-card h3 {
            font-size: 1.3em;
            font-weight: bold;
            color: #2980b9;
        }

        .why-card p {
            font-size: 1em;
            color: #34495e;
        }
    </style>
</head>

<body>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.course-slider').slick({
                slidesToShow: 3,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 2000,
                arrows: false,
                dots: false,
                responsive: [{
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        }
                    },
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2,
                        }
                    }
                ]
            });


            $('.tutors-section').slick({
                slidesToShow: 2,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 2000,
                responsive: [{
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        },

                    },
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1,
                        }
                    }
                ]

            });
        });

        function showBio(tutorId) {
            window.open('get_tutor_bio.php?id=' + tutorId, '_blank');
        }
    </script>
</body>

</html>