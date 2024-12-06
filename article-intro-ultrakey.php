<!-- path/to/ultrakey-ui.html -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ultrakey Online Learning Platform</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
     /* path/to/styles.css */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

header {
    background: #007bff;
    color: white;
    padding: 10px 0;
    text-align: center;
}

nav ul {
    list-style: none;
    padding: 0;
}

nav ul li {
    display: inline;
    margin: 0 15px;
}

nav ul li a {
    color: white;
    text-decoration: none;
}

main {
    padding: 20px;
}

section {
    margin: 20px 0;
    padding: 15px;
    background: white;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

footer {
    text-align: center;
    padding: 10px 0;
    background: #007bff;
    color: white;
    position: relative;
    bottom: 0;
    width: 100%;
}
@media (max-width: 768px) {
        nav ul li {
            display: block;
            margin: 10px 0;
        }

        main {
            padding: 10px;
        }

        section {
            margin: 10px 0;
            padding: 10px;
        }
    }

    @media (max-width: 480px) {
        header h1 {
            font-size: 1.5em;
        }

        nav ul li {
            font-size: 0.9em;
        }
    }
</style>
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
            <h2>Live Classes</h2>
            <p>Join our live classes and interact with instructors in real-time.</p>
            <button onclick="joinLiveClass()">Join Now</button>
        </section>

        <section id="recorded-classes">
            <h2>Recorded Classes</h2>
            <p>Access our library of recorded classes anytime, anywhere.</p>
            <button onclick="viewRecordedClasses()">View Classes</button>
            <div id="recorded-classes-list"></div>
        </section>

        <section id="student-interaction">
            <h2>Student Interaction</h2>
            <p>Engage with your peers and instructors through discussions and Q&A sessions.</p>
        </section>

        <section id="motivation">
            <h2>Motivational Tools</h2>
            <p>Stay motivated with challenges and rewards for your achievements.</p>
        </section>
    </main>
    <script >
        // path/to/script.js
function joinLiveClass() {
    alert("Joining live class...");
    window.location.href = 'https://ultrakey.in/live-classes';
}

function viewRecordedClasses() {
    alert("Fetching recorded classes...");
    fetch('fetch_recorded_classes.php') // Path to your server-side script
        .then(response => response.json())
        .then(data => {
            const classesList = document.getElementById('recorded-classes-list');
            classesList.innerHTML = ''; // Clear previous content

            if (data.message) {
                classesList.innerHTML = `<p>${data.message}</p>`;
                return;
            }

            const videoContainer = document.createElement('div');
            videoContainer.style.display = 'flex'; // Horizontal layout
            videoContainer.style.flexWrap = 'wrap'; // Allow wrapping

            data.forEach(classItem => {
                const classElement = document.createElement('div');
                classElement.style.margin = '10px'; // Spacing between videos
                classElement.innerHTML = `
                    <h3>${classItem.class_name}</h3>
                    <video controls width="300">
                        <source src="${classItem.video_path}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                `;
                videoContainer.appendChild(classElement);
            });

            classesList.appendChild(videoContainer);
        })
        .catch(error => {
            console.error('Error fetching recorded classes:', error);
        });
}
    </script>
    <?php include 'footer.php'; ?>
</body>
</html>
