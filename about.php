<?php
include 'header.php';
class About {
    private $courses;
    private $tutors;

    public function __construct() {
        // Fetch courses from the database (placeholder)
        $this->courses = $this->fetchCoursesFromDatabase();

        // Tutor data with images
        $this->tutors = [
            ["name" => "John Doe", "specialization" => "Expert in Programming", "image" => "assets/images/john_doe.jpg"],
            ["name" => "Jane Smith", "specialization" => "Web Development Specialist", "image" => "assets/images/jane_smith.jpg"],
            ["name" => "Emily Johnson", "specialization" => "Data Science Enthusiast", "image" => "assets/images/emily_johnson.jpg"],
            // ... add more tutors as needed
        ];
    }

    private function fetchCoursesFromDatabase() {
        // Database connection parameters
        $host = 'localhost'; // Change as needed
        $db = 'lms'; // Change to your database name
        $user = 'root'; // Change to your database username
        $pass = ''; // Change to your database password

        // Create a new PDO instance
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare and execute the SQL query
            $stmt = $pdo->prepare("SELECT title FROM courses"); // Assuming your table is named 'courses'
            $stmt->execute();

            // Fetch all course names
            $courses = $stmt->fetchAll(PDO::FETCH_COLUMN);

            return $courses;

        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return []; // Return an empty array on failure
        }
    }

    public function displayAbout() {
        echo "<h2 class='section-title'>Tutors</h2>";
        echo "<div class='tutors-section'>";
        
        foreach ($this->tutors as $tutor) {
            echo "<div class='tutor-card'>";
            echo "<img src='{$tutor['image']}' alt='{$tutor['name']}' class='tutor-image'>";
            echo "<h3>{$tutor['name']}</h3>";
            echo "<p>{$tutor['specialization']}</p>";
            echo "</div>";
        }
        
        echo "</div>";

        echo "<h2 class='section-title'>Courses Offered</h2>";
        echo "<div class='course-slider'>";

        $coursesWithImages = [
            ["name" => "Java", "image" => "assets/images/-12.jpg"],
            ["name" => "Python", "image" => "assets/images/-58.jpg"],
            ["name" => "SAP", "image" => "assets/images/-70.jpg"],
            ["name" => "React", "image" => "assets/images/-81.jpg"],
            ["name" => "AWS", "image" => "assets/images/-1.jpg"],
            ["name" => "HTML", "image" => "assets/images/-7.jpg"],
            ["name" => "JEE Advanced", "image" => "assets/images/-14.jpg"],
            ["name" => "PHP", "image" => "assets/images/-24.jpg"],
            ["name" => "Microsoft Azure", "image" => "assets/images/-64.jpg"],
            ["name" => "Physics", "image" => "assets/images/-75.jpg"],
            ["name" => "WordPress", "image" => "assets/images/-78.jpg"],
            ["name" => "Hindi", "image" => "assets/images/-82.jpg"],
            ["name" => "English", "image" => "assets/images/-83.jpg"],
            ["name" => "Maths", "image" => "assets/images/-91.jpg"],
            ["name" => "Ruby", "image" => "assets/images/-87.jpg"],
            ["name" => "Devops", "image" => "assets/images/-89.jpg"],
            ["name" => "C#", "image" => "assets/images/-92.jpg"],

            // ["name" => "", "image" => "assets/images/english.jpg"],
            // ["name" => "New Course", "image" => "assets/images/new_course.jpg"],
        ];

        foreach ($coursesWithImages as $course) {
            echo "<div class='course-item'>";
            echo "<img src='{$course['image']}' alt='{$course['name']}' class='course-image'>";
            echo "<p class='course-title'>{$course['name']}</p>";
            // echo "<div class='in-cart'>In Cart</div>";
            echo "</div>";
        }
        
        echo "</div>";
    }

    public function displayWelcomeMessage() {
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
<!-- Include Slick Slider CSS -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"/>

<!-- Include jQuery (required for Slick Slider) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include Slick Slider JS -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
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
    .welcome-section, .tutors-section, .course-slider {
        padding: 20px;
    }
    .tutors-section {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        margin-bottom: 30px;
    }
    .tutor-card {
        background: #ecf0f1;
        padding: 15px;
        margin: 10px;
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
        overflow-x: auto; /* Enable horizontal scrolling */
        padding: 10px;
        gap: 10px;
        cursor: pointer; /* Indicate that the user can scroll */
        /* Add this to prevent empty space on mobile */
        width: 100%; /* Ensure it takes full width */
        box-sizing: border-box; /* Include padding in width calculation */
    }
    .course-item {
        flex: 0 0 auto;
        margin: 0 10px;
        text-align: center;
        transition: transform 0.3s; /* Smooth transition for hover effect */
    }
    .course-item:hover {
        transform: translateX(-10px); /* Move left on hover */
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
    .site-footer {
        text-align: center;
        background: #34495e;
        padding: 10px;
        color: white; /* Footer text color */
        margin-top: 10px;
        font-size: 0.9em;
        height:4vh
    }
    @media (max-width: 768px) {
        .tutors-section, .course-slider {
            flex-direction: column;
            align-items: center;
        }
        .tutor-card {
            width: 100%;
            margin-bottom: 20px;
        }
        .course-item {
            margin: 10px 0;
        }
    }
    .active {
        color: white; /* Set light blue color for active link */
    }
    /* Add this CSS to your existing styles */
.course-item {
    flex: 0 0 auto;
    margin: 0 10px;
    text-align: center;
    transition: transform 0.3s; /* Smooth transition for hover effect */
}

.course-item:hover {
    transform: translateX(-10px); 
}

.course-container {
    display: flex;
    flex-wrap: wrap; /* Ensures items wrap to the next line if there's not enough space */
    gap: 20px; /* Space between course items */
    justify-content: flex-start; /* Align items to the start */
    padding: 20px;
}
.course-item {
    flex: 0 0 calc(33.33% - 20px); /* 3 items per row with space adjustments */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    padding: 10px;
    text-align: center;
    background-color: #fff;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s ease; /* Adding transition for hover effect */
}

.course-image {
    width: 120px; /* Increased image size */
    height: 120px; /* Increased image size */
    margin-bottom: 10px;
}

.course-slider {
    display: flex;
    overflow-x: scroll; /* Enable horizontal scrolling */
    padding: 10px;
    gap: 10px;
    cursor: pointer; /* Indicate that the user can scroll */
}

.course-item:hover {
    transform: translateX(-10px); /* Move left on hover */
}

.course-container {
    display: flex;
    flex-wrap: wrap; /* Ensures items wrap to the next line if there's not enough space */
    gap: 20px; /* Space between course items */
    justify-content: flex-start; /* Align items to the start */
    padding: 20px;
    overflow-x: auto; /* Allow scrolling horizontally */
    scrollbar-width: thin; /* Optional: make the scrollbar thinner */
}

.course-slider::-webkit-scrollbar {
    height: 6px; /* Optional: customize the scrollbar height */
}

.course-slider::-webkit-scrollbar-thumb {
    background-color: rgba(0,0,0,0.5); /* Custom color for scrollbar */
}

@media (max-width: 1024px) {
    .course-item {
        flex: 0 0 calc(50% - 20px); /* 2 items per row on medium screens */
    }
}

@media (max-width: 768px) {
    .course-item {
        flex: 0 0 calc(100% - 20px); /* 1 item per row on small screens */
    }
}

.why-choose-us li:hover {
    background-color: #2980b9; /* Change background color on hover */
    color: white; /* Change text color on hover */
    transition: background-color 0.3s, color 0.3s; /* Smooth transition */
}

</style>
<script type="text/javascript">
    $(document).ready(function(){
        // Initialize the Slick Slider with updated settings
        $('.course-slider').slick({
            slidesToShow: 3, // Number of visible slides
            slidesToScroll: 1, // Number of slides to scroll at once
            autoplay: true, // Enable auto-slide
            autoplaySpeed: 2000, // Auto-slide interval (2 seconds)
            arrows: false, // Hide previous/next arrows
            dots: false, // Remove navigation dots
            responsive: [
                {
                    breakpoint: 768, // For screens smaller than 768px
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 1024, // For screens larger than 768px but smaller than 1024px
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                    }
                }
            ]
        });

        // Enable smooth scrolling when hovering over the course section
        $('.course-container').hover(function(){
            $(this).css('animation-play-state', 'paused'); // Pause scroll on hover
        }, function(){
            $(this).css('animation-play-state', 'running'); // Resume scroll when mouse leaves
        });
    });
</script>

