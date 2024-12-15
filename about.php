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
        // Use the same database connection as above
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
        echo "<h2 class='section-title'>Tutors</h2>";
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
            echo "<div class='course-item '>";
            echo "<img src='assets/images/{$image_name}' alt='{$course['title']}' class='course-image' d-flex>";
            echo "<p class='course-title'>{$course['title']}</p>";
            echo "</div>";
        }

        echo "</div>";
    }

    public function displayWelcomeMessage()
    {
        echo "<div class='welcome-section'>";
        echo "<h1 style='color: #2980b9; font-weight: bold; text-align: center; margin: 0 auto;'>About Us</h1>";
        echo "<p>Welcome to Ultrakey, your trusted partner in academic success and personal growth!</p>";
        echo "<p>At Ultrakey, we believe that education is more than just acquiring knowledge—it's about inspiring minds and igniting the passion to achieve greatness.</p>";

        echo "<h2 class='section-title'>Why Choose Us?</h2>";
        echo "<ul class='why-choose-us'>";
        echo "<li><strong>Expert Educators:</strong> Innovative teaching methodologies to ensure student success.</li>";
        echo "<li><strong>Motivating Environment:</strong> Encouraging growth and consistent progress.</li>";
        echo "<li><strong>Outstanding Students:</strong> A dynamic learning ecosystem for thriving students.</li>";
        echo "</ul>";
        echo "</div>";
    }
}

// Usage
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
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            color: #2c3e50;
            background-color: #f4f4f4;
        }

        .section-title {
            text-align: center;
            font-size: 1.8em;
            margin: 20px 0;
            color: #2980b9;
            font-weight: bold;
        }

        .welcome-section,
        .tutors-section,
        .course-slider {
            padding: 20px;
        }

        .tutors-section {
            display: flex;
          flex-wrap: wrap; 
            justify-content: space-around;
            margin-bottom: 15px;
        } 
       
      
        .tutor-card {
            background: #ecf0f1;
            padding: 5px;
            margin: 5px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .tutor-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .course-slider {
            display: flex;
            overflow-x: auto;
            padding: 10px;
            gap: 10px;
            cursor: pointer;
        }

        .course-item {
            flex: 0 0 auto;
            margin: 0 10px;
            text-align: center;
            transition: transform 0.3s;
        }

        .course-item:hover {
            transform: translateX(-10px);
        }

        .course-image {
            width: 80px;
            height: 80px;
            margin-bottom: 10px;
        }

        .course-title {
            font-weight: bold;
            color: #34495e;
        }

        .why-choose-us {
            background: rgba(173, 216, 230, 0.1);
            padding: 15px;
            border-radius: 8px;
            list-style: none;
            color: #2c3e50;
            margin: 10px auto;
        }

        .why-choose-us li {
            margin: 10px 0;
            font-weight: bold;
            padding: 10px;
            background: lightgray;
            border-radius: 5px;
            color: #2980b9;
        }

        .why-choose-us li:hover {
            background: #2980b9;
            /* Light blue background on hover */
            color: white;
            /* White text color on hover */
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
                            slidesToScroll: 1,
                        }
                    }
                ]
            });

            $('.tutors-section').slick({
                slidesToShow: 2,
                slidesToScroll: 1,
                autoplay:true,
                autoplaySpeed: 2000,
                responsive: [{
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    } ,
                    
                } ,
                {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1,
                        }
                    }]

            });
        });

        function showBio(tutorId) {
            window.open('get_tutor_bio.php?id=' + tutorId, '_blank');
        }
    </script>
</body>

</html>

