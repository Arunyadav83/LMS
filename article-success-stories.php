<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horizontal Card Layout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color:forestgreen;
            font-size: 30px;

            /* font-weight: bold; */
            /* border-bottom: 10px solid black; */
        }
        h1:hover{
            color:black;
            transition: 0.5s;
            cursor: pointer;
            font-size: 35px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            font-weight: bold;
        }
        .card-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        .card {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 16px;
            width: 300px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            text-align: center;
        }
        .card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 16px;
        }
        .card p {
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>
    
   <?php include 'header.php'; ?>
   <h1>Success Stories</h1>
   <div class="card-container">
       <div class="card">
           <img src="assets/images/sneha.jpg" alt="Sneha's Story">
           <p>
               "Hello, I’m Sneha, a class 10 student. Education is not just about textbooks; it’s about learning skills that shape our future. The quality of guidance I’ve received has opened new doors for me, helping me dream bigger and work harder. I feel more confident in pursuing my goals, and I’m deeply thankful for the supportive teachers and resources that have made this journey possible."
           </p>
       </div>

       <div class="card">
           <img src="assets/images/karthik.jpg" alt="Karthik's Story">
           <p>
               "I’m Karthik, currently in my first year of college. Education has been the cornerstone of my personal growth. The focus on quality learning and practical skills has not only helped me excel academically but also prepared me for real-world challenges. I believe that when students are given the right opportunities and encouragement, they can achieve extraordinary things. I’m grateful to my mentors for always inspiring me to reach for the stars."
           </p>
       </div>

       <div class="card">
           <img src="assets/images/ananya.jpg" alt="Ananya's Story">
           <p>
               "Hi, I’m Ananya, a high school student. The journey of education is about more than just grades—it’s about discovering our strengths and passions. Thanks to the incredible support of my teachers, I’ve learned to think critically, solve problems, and approach life with a positive mindset. Education is the key to unlocking our potential, and I’m excited about what the future holds."
           </p>
       </div>

       <div class="card">
           <img src="assets/images/rahul.jpg" alt="Rahul's Story">
           <p>
               "I’m Rahul, a student pursuing higher studies. Quality education is a transformative experience. It’s not just about learning facts but about understanding concepts and applying them to make a difference. The guidance and resources I’ve received have encouraged me to explore new ideas and challenge myself every day. I truly believe education is the foundation for building a brighter tomorrow."
           </p>
       </div>
   </div>

   <?php include 'footer.php'; ?>
</body>
</html>
