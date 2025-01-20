<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Join Ultrakey Online Learning Platform for interactive live and recorded classes, peer discussions, and motivational tools.">
    <meta name="keywords" content="online learning, live classes, recorded classes, student interaction, achievements">
    <meta name="author" content="Ultrakey Team">

    <title>Ultrakey Online Learning Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f4f8;
            color: #333;
            line-height: 1.6;
        }

        header {
            background: linear-gradient(135deg, #3498db, #8e44ad);
            color: white;
            padding: 20px 0;
            text-align: center;
            margin: 20px;
            margin-top: 90px;
        }

        header h1 {
            font-size: 1rem;
            margin-bottom: 10px;
        }

        nav ul {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        nav ul li {
            margin: 0 15px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        nav ul li a:hover {
            color: #ffd700;
        }

        main {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        section {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
            transition: transform 0.3s ease;
        }

        section:hover {
            transform: translateY(-5px);
        }

        h3 {
            color: #3498db;
            margin-bottom: 20px;
            font-size: smaller;
            
        }

        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2980b9;
        }

        .class-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
            padding: 10px;
        }

        .class-card {
            background-color: #ffffff;
            border-radius: 12px;
            /* overflow: hidden; */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .class-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .class-card img {
            width: auto;
            height: 300px;
            object-fit: cover;
            border-bottom: 2px solid #f1f1f1;
        }

        .class-card-content {
            /* padding: 0px; */
            text-align: center;
        }

        .class-card h3 {
            margin-bottom: 12px;
            /* font-size: 20px; */
            color: #34495e;
            font-weight: bold;
        }

        .class-card button {
            background-color: #3498db;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .class-card button:hover {
            background-color: #2980b9;
        }

        .view-classes-btn {
            margin-top: 10px;
            padding: 10px 25px;
            background-color: #2ecc71;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .view-classes-btn:hover {
            background-color: #27ae60;
            transform: scale(1.05);
        }

        /* footer {
            text-align: center;
            padding: 20px 0;
            background: linear-gradient(135deg, #3498db, #8e44ad);
            color: white;
        } */
        /* Modal Styles */

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        /* Timeline Styles */
        .timeline {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 0;
        }

        .timeline::after {
            content: '';
            position: absolute;
            width: 6px;
            background-color: #3498db;
            top: 0;
            bottom: 0;
            left: 50%;
            margin-left: -3px;
        }

        .timeline-item {
            padding: 10px 40px;
            position: relative;
            background-color: inherit;
            width: 50%;
        }

        .timeline-item::after {
            content: '';
            position: absolute;
            width: 25px;
            height: 25px;
            right: -17px;
            background-color: white;
            border: 4px solid #3498db;
            top: 15px;
            border-radius: 50%;
            z-index: 1;
        }

        .left {
            left: 0;
        }

        .right {
            left: 50%;
        }

        .right::after {
            left: -16px;
        }

        .timeline-content {
            padding: 20px 30px;
            background-color: white;
            position: relative;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Forum Styles */
        .forum-list {
            list-style-type: none;
            padding: 0;
        }

        .forum-item {
            background-color: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 10px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }
        video{
            width: auto;
            height: 300px;
        }

        .forum-item:hover {
            background-color: #e9ecef;
        }

        .forum-item h3 {
            margin: 0 0 10px 0;
            color: #3498db;
        }

        .forum-item p {
            margin: 0;
            color: #666;
        }
        .recorded-classes-list{
            width: auto;
            height: 140px;
            border: solid;
        }

        @media (max-width: 768px) {
            header h1 {
                font-size: 2rem;
            }


            .class-card img {
                height: 264px;
                /* Adjust image size */
            }

            .modal-content {
                width: 90%;
                margin: 10% auto;
                padding: 15px;
            }

            nav ul li {
                margin: 5px 10px;
            }

            .timeline::after {
                left: 31px;
            }

            .timeline-item {
                width: 100%;
                padding-left: 70px;
                padding-right: 25px;
            }

            .timeline-item::after {
                left: 15px;
            }

            section {
                padding: 20px;
                margin: 10px 0;
            }

            .left::after,
            .right::after {
                left: 15px;
            }

            .right {
                left: 0%;
            }
        }
      
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <header>
        <h1>Welcome to Ultrakey</h1>
        <nav>
            <ul>
                <li><a href="#live-classes">Live Classes</a></li>
                <li><a href="#recorded-classes">Recorded Classes</a></li>
                <li><a href="#student-interaction">Student Interaction</a></li>
                <li><a href="#motivation">Motivational Tools</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="live-classes">
            <h3 >Live Classes</h3>
            <p>Join our interactive live classes and learn directly from expert instructors.</p>
            <button onclick="joinLiveClass()">Join Now</button>
            <p id="live-class-message"></p>
        </section>
        <section id="recorded-classes">
            <h3>Recorded Classes</h3>
            <p>Access our extensive library of recorded classes at your convenience.</p>
            <button onclick="viewRecordedClasses()" class="view-classes-btn">View Classes</button>
            <div id="recorded-classes-list" class="class-grid"></div>
        </section>
        <section id="student-interaction">
            <h3>Student Interaction</h3>
            <p>Connect with peers, participate in discussions, and collaborate on projects.</p>
            <button onclick="openForum()">Open Forum</button>
        </section>

        <section id="motivation">
            <h3>Motivational Tools</h3>
            <p>Track your progress, earn badges, and stay motivated throughout your learning journey.</p>
            <button onclick="viewAchievements()">View Achievements</button>
        </section>
    </main>

    <div id="achievementsModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Ultrakey's Journey and Achievements</h3>
            <div class="timeline">
                <div class="timeline-item left">
                    <div class="timeline-content">
                        <h3>2015</h3>
                        <p>Ultrakey founded with a vision to revolutionize online learning</p>
                    </div>
                </div>
                <div class="timeline-item right">
                    <div class="timeline-content">
                        <h3>2017</h3>
                        <p>Launched our first comprehensive IT training program</p>
                    </div>
                </div>
                <div class="timeline-item left">
                    <div class="timeline-content">
                        <h3>2019</h3>
                        <p>Received "Best E-Learning Platform" award at EdTech Summit</p>
                    </div>
                </div>
                <div class="timeline-item right">
                    <div class="timeline-content">
                        <h3>2021</h3>
                        <p>Expanded our course offerings to cover emerging technologies</p>
                    </div>
                </div>
                <div class="timeline-item left">
                    <div class="timeline-content">
                        <h3>2023</h3>
                        <p>Celebrating 8 years of excellence in IT education</p>
                    </div>
                </div>
            </div>
            <h3>Our Achievements</h3>
            <ul>
                <li>Over 100,000 students trained</li>
                <li>95% job placement rate for our graduates</li>
                <li>Partnerships with 50+ leading tech companies</li>
                <li>Recognized as a top 10 online learning platform by TechEdu Magazine</li>
            </ul>
        </div>
    </div>

    <div id="forumModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Student Forum</h3>
            <ul class="forum-list">
                <li class="forum-item">
                    <h3>Welcome to Ultrakey!</h3>
                    <p>Introduce yourself and meet your fellow learners</p>
                </li>
                <li class="forum-item">
                    <h3>Course Discussions</h3>
                    <p>Ask questions and share insights about your current courses</p>
                </li>
                <li class="forum-item">
                    <h3>Tech Talk</h3>
                    <p>Discuss the latest trends and technologies in the IT industry</p>
                </li>
                <li class="forum-item">
                    <h3>Career Advice</h3>
                    <p>Get tips and guidance for your IT career journey</p>
                </li>
                <li class="forum-item">
                    <h3>Study Groups</h3>
                    <p>Find study partners and form groups for collaborative learning</p>
                </li>
            </ul>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        function joinLiveClass() {
            fetch('fetch_live_class.php')
                .then(response => response.json())
                .then(data => {
                    const messageElement = document.getElementById('live-class-message');
                    if (data.online_link) {
                        window.location.href = data.online_link;
                    } else {
                        messageElement.textContent = data.error || 'No live class available at the moment. Check back later!';
                        messageElement.style.color = '#e74c3c';
                    }
                })
                .catch(error => {
                    console.error('Error fetching live class link:', error);
                });
        }

        function viewRecordedClasses() {
            fetch('fetch_recorded_classes.php')
                .then(response => response.json())
                .then(data => {
                    const classesList = document.getElementById('recorded-classes-list');
                    classesList.innerHTML = ''; // Clear previous content

                    if (data.message) {
                        classesList.innerHTML = `<p>${data.message}</p>`;
                        return;
                    }

                    data.forEach(classItem => {
                        const classElement = document.createElement('div');
                        classElement.classList.add('class-card');
                        classElement.innerHTML = `
                    <img src="assets/images/${classItem.class_name}" alt="${classItem.class_name}">
                    <div class="class-card-content md3">
                        <h3>${classItem.class_name}</h3>
                        <button onclick="watchClass('${classItem.video_path}')">Watch Now</button>
                    </div>
                `;
                        classesList.appendChild(classElement);
                    });
                })
                .catch(error => {
                    console.error('Error fetching recorded classes:', error);
                });
        }

        function watchClass(videoPath) {
            const videoContainer = document.getElementById('recorded-classes-list');
            if (!videoContainer) {
                console.error('Video container not found!');
                return;
            }

            // Create or update the video element
            videoContainer.innerHTML = `
        <video controls autoplay>
            <source src="${videoPath}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    `;
            videoContainer.scrollIntoView({
                behavior: 'smooth'
            }); // Scroll to video section
        }

        function openForum() {
            const modal = document.getElementById('forumModal');
            const span = modal.getElementsByClassName('close')[0];

            modal.style.display = 'block';

            span.onclick = function() {
                modal.style.display = 'none';
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }
        }

        function viewAchievements() {
            const modal = document.getElementById('achievementsModal');
            const span = modal.getElementsByClassName('close')[0];

            modal.style.display = 'block';

            span.onclick = function() {
                modal.style.display = 'none';
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }
        }
    </script>
</body>

</html>