
<?php include 'header.php';
require_once 'config.php';
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

        /* Container fluid styles */
        .container-fluid {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
            max-width: none !important;
        }

        /* Zoom level adjustments */
        @media screen and (min-width: 1200px) {
            body {
                zoom: 1; /* Default zoom */
            }
        }

        /* Adjust for different zoom levels */
        @media screen and (max-width: 1199px) {
            .container-fluid {
                width: 100vw;
                overflow-x: hidden;
            }
            
            .row {
                margin-left: 0;
                margin-right: 0;
            }
        }

        /* Support for 25% zoom */
        @media screen and (min-width: 2400px) {
            .container-fluid {
                max-width: 100vw;
            }
            
            .welcome-section,
            .tutors-section,
            .why-choose-us-cards {
                transform-origin: top left;
                transform: scale(1);
            }
        }

        /* Ensure content stays readable at different zoom levels */
        @media (max-width: 768px) {
            .container-fluid {
                padding-left: 10px;
                padding-right: 10px;
            }
            
            .welcome-section h1 {
                font-size: calc(2vw + 20px);
                margin-top: 84px;
            }
            
            .welcome-section p {
                font-size: calc(1vw + 12px);
            }
        }

        h1,
        h2 {
            color: rgb(227, 234, 239);
        }

        h3 {
            color: rgb(153, 202, 251);
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
            color: rgb(163, 187, 207);
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
            margin-top: 0px;
            padding: 50px 20px;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
                url('assets/images/about_us_background.jpg') no-repeat center center;
            background-size: cover;
            background-attachment: fixed;
            animation: slideIn 1.5s ease-out forwards;
        }

        /* Ensure proper rendering on smaller screens */
        @media (max-width: 768px) {
            .welcome-section {
                background-attachment: scroll;
                /* Avoid fixed issues on mobile */
                background-size: cover;
                /* Ensure the image scales properly */
                background-position: center center;
                /* Keep the image centered */
                background-repeat: no-repeat;
                /* Prevent tiling */
            }
        }

        /* Additional support for very small screens */
        @media (max-width: 480px) {
            .welcome-section {
                padding: 30px 15px;
                /* Adjust padding for smaller devices */
                font-size: 1rem;
                /* Scale down text size */
            }
        }


        .welcome-section h1 {
            text-align: center;
            font-size: 40px;
            margin-top: 81px;
        }

        .welcome-section p {
            text-align: center;
            font-size: 0.9em;
            margin-top: 20px;
            color: khaki;

        }

        .welcome-section .section-title {
            text-align: center;

            margin-top: 40px;


        }

        .tutors-section {
            width: 100%;
            overflow-x: hidden;
            position: relative;
            padding: 20px 0;
        }

        .tutors-slider {
            display: flex;
            gap: 20px;
            animation: infinite-scroll 30s linear infinite;
            width: max-content;
        }

        /* Add hover pause effect */
        .tutors-section:hover .tutors-slider {
            animation-play-state: paused;
        }

        @keyframes infinite-scroll {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(calc(-300px * var(--total-cards))); /* Will be set by JavaScript */
            }
        }

        .tutor-card {
            flex: 0 0 300px;
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            margin: 0;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        /* Add smooth transition for hover effect */
        .tutor-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 768px) {
            .tutors-slider {
                gap: 15px;
            }
            
            .tutor-card {
                flex: 0 0 250px;
            }
        }

        /* Adjust for different zoom levels */
        @media (max-width: 1199px) {
            .container-fluid {
                width: 100vw;
                overflow-x: hidden;
            }
            
            .tutors-section {
                padding: 5px;
                gap: 10px;
            }
        }

        /* Support for very small screens and extreme zoom out */
        @media screen and (max-width: 480px), screen and (zoom: 0.25) {
            .tutors-section {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 10px;
            }
            
            .tutor-card {
                margin: 0;
                padding: 10px;
            }
            
            .tutor-image {
                width: 100px;
                height: 100px;
            }
            
            .container-fluid {
                padding: 5px;
            }
        }

        /* Ensure content stays readable at different zoom levels */
        @media (max-width: 768px) {
            .container-fluid {
                padding-left: 5px;
                padding-right: 5px;
            }
            
            .tutors-section {
                margin-top: 15px;
            }
        }


        /* .tutor-card {
            background: #ffffff;
            margin: 0 15px;
            border-radius: 8px;
            max-width: 300px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        } */

        .tutor-card {
            background-color: #ffffff;
             margin: 0 15px  ;
             max-width: 300px;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
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
            font-size: 13px;
            font-weight: bold;
            color: rgb(10, 171, 241);
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
            font-size: 1.5rem;
            /* Use rem for scalability */
            color: #2c3e50;
            font-weight: 300;
            letter-spacing: 0.1em;
            line-height: 1.2;
            /* Adjusted line height to prevent overlap */
            margin: 40px 0;
            font-family: 'Montserrat', sans-serif;
            margin-bottom: 23px;
        }

        .subtitle {
            color: #e74c3c;
            font-size: 1rem;
            /* Increased size for better readability */
            text-transform: uppercase;
            letter-spacing: 0.15em;
            margin-bottom: 20px;
            /* Added space to separate elements */
            display: block;
            margin-top: 65px;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .section-title {
                font-size: 1.8rem;
                /* Reduced size for smaller screens */
                line-height: 1.3;
                /* Adjusted line height */
            }

            .subtitle {
                font-size: 0.9rem;
                /* Adjusted subtitle size */
                margin-bottom: 15px;
            }
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
            object-fit: contain;
            margin-bottom: 15px;


        }

        /* .course-title {
            font-size: 1.1em;
            font-weight: bold;
            color: #34495e;
        } */

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
            /* Fixed width */
            height: 100px;
            /* Fixed height */
            object-fit: cover;
            /* Ensures the image scales proportionally and fills the dimensions */
            margin-bottom: 15px;
            /* Adds spacing below the image */
            border-radius: 8px;
            /* Optional: Adds rounded corners */
            display: block;
            /* Ensures the image is treated as a block element */
            margin-left: auto;
            /* Centers the image horizontally */
            margin-right: auto;
        }


        .why-card h3 {
            font-size: 0.8em;
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
        });

        document.addEventListener('DOMContentLoaded', function() {
            const tutorsSlider = document.querySelector('.tutors-slider');
            if (tutorsSlider) {
                // Count the number of original cards
                const totalCards = tutorsSlider.children.length;
                
                // Set CSS variable for animation
                tutorsSlider.style.setProperty('--total-cards', totalCards);
                
                // Clone the cards for seamless scrolling
                const cards = [...tutorsSlider.children];
                cards.forEach(card => {
                    const clone = card.cloneNode(true);
                    tutorsSlider.appendChild(clone);
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const tutorsSlider = document.querySelector('.tutors-slider');
            if (tutorsSlider) {
                // Count the number of original cards
                const totalCards = tutorsSlider.children.length;
                
                // Set CSS variable for animation
                tutorsSlider.style.setProperty('--total-cards', totalCards);
                
                // Clone the cards for seamless scrolling
                const cards = [...tutorsSlider.children];
                cards.forEach(card => {
                    const clone = card.cloneNode(true);
                    tutorsSlider.appendChild(clone);
                });
            }
        });

        function showBio(tutorId) {
            window.open('get_tutor_bio.php?id=' + tutorId, '_blank');
        }
    </script>
</body>

</html>



<?php
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
        require_once 'config.php'; // Include database connection
        global $conn; // Use the global MySQLi connection
    
        $query = "SELECT id, title FROM courses";
        $result = mysqli_query($conn, $query);
    
        $courses = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $courses[] = $row;
            }
        }
    
        return $courses;
    }
    

    private function fetchTutorsFromDatabase()
    {
        require_once 'config.php'; // Include database connection
        global $conn; // Use the global MySQLi connection
    
        $query = "SELECT id, full_name, email, role, bio, specialization, resume_path, certificate_path, created_at FROM tutors";
        $result = mysqli_query($conn, $query);
    
        $tutors = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Generate image path
                $image_name = strtolower(str_replace(' ', '_', $row['full_name'])) . '.jpg';
                $row['image'] = 'assets/images/' . $image_name;
    
                $tutors[] = $row;
            }
        }
    
        return $tutors;
    }
    


    public function displayAbout()
    {
        echo "<div class='container-fluid p-0'>";
        echo "<div class='row g-0'>";
        echo "<h2 class='section-title'>
                <span class='subtitle'>World-Class Instructors</span><br>
                Classes Taught By Real Creators
              </h2>";
              echo "<div class='tutors-section'>";
              echo "<div class='tutors-slider'>";

        // First set of cards
        foreach ($this->tutors as $tutor) {
            echo "<div class='tutor-card'>
                    <div class='card'>
                        <div class='card-body'>
                            <div class='d-flex flex-column align-items-center'>
                                <img src='" . htmlspecialchars($tutor['image']) . "' 
                                     alt='" . htmlspecialchars($tutor['full_name']) . "' 
                                     class='tutor-image mb-4'>
                                <h3 class='tutor-name'>" . htmlspecialchars($tutor['full_name']) . "</h3>
                                <p class='tutor-specialization'>" . htmlspecialchars($tutor['specialization']) . "</p>
                                <button class='btn btn-primary bio-btn' onclick='showBio(" . $tutor['id'] . ")'>View Bio</button>
                            </div>
                        </div>
                    </div>
                  </div>";
        }

        // Duplicate cards for seamless scrolling
        foreach ($this->tutors as $tutor) {
            echo "<div class='tutor-card'>
                    <div class='card'>
                        <div class='card-body'>
                            <div class='d-flex flex-column align-items-center'>
                                <img src='" . htmlspecialchars($tutor['image']) . "' 
                                     alt='" . htmlspecialchars($tutor['full_name']) . "' 
                                     class='tutor-image mb-4'>
                                <h3 class='tutor-name'>" . htmlspecialchars($tutor['full_name']) . "</h3>
                                <p class='tutor-specialization'>" . htmlspecialchars($tutor['specialization']) . "</p>
                                <button class='btn btn-primary bio-btn' onclick='showBio(" . $tutor['id'] . ")'>View Bio</button>
                            </div>
                        </div>
                    </div>
                  </div>";
        }

        echo "</div>"; // Close tutors-slider

        echo "</div>"; // Close tutors section

        echo "<h2 class='section-title'>Courses Offered</h2>";
        echo "<div class='course-slider'>";

        foreach ($this->courses as $course) {
            $image_name = strtolower(str_replace(' ', '_', $course['title'])) . '.jpg';
            echo "<div class='course-item'>
                    <img src='assets/images/{$image_name}' alt='" . htmlspecialchars($course['title']) . "' class='course-image' >
                </div>";
        }

        echo "</div>"; // Close courses slider
        echo "</div>"; // Close row
        echo "</div>"; // Close container-fluid
    }

    public function displayWelcomeMessage()
    {
        echo "<div class='container-fluid p-0'>";
        echo "<div class='row g-0'>";
        echo "<div class='welcome-section'>";
        echo "<h1>About Us</h1>";

        echo "<p>Welcome to Ultrakey, your trusted partner in academic success and personal growth!<br>
        Ultrakey Learning is your one-stop destination for quality online courses in a variety of fields. Whether you're looking to upskill, learn a new language, or explore programming, we have the courses you need to succeed.</p>";

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

        echo "</div>"; // Close why-choose-us-cards
        echo "</div>"; // Close welcome-section
        echo "</div>"; // Close row
        echo "</div>"; // Close container-fluid
    }
}

$about = new About();
$about->displayWelcomeMessage();
$about->displayAbout();

include 'footer.php';
?>

