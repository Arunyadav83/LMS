-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2024 at 03:25 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-10-02 06:57:23'),
(3, 'admin2', 'admin2@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-10-03 03:55:30');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `tutor_id` int(11) DEFAULT NULL,
  `class_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `video_path` varchar(255) DEFAULT NULL,
  `is_online` tinyint(1) DEFAULT 0,
  `online_link` varchar(255) DEFAULT NULL,
  `schedule_time` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `video_id` int(11) DEFAULT NULL,
  `is_unlocked` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `tutor_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `course_prize` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `title`, `description`, `tutor_id`, `created_at`, `updated_at`, `course_prize`) VALUES
(12, 'AWS', 'NA', 1, '2024-12-08 09:06:39', '2024-12-08 14:15:32', 2400.00),
(17, 'SAP', 'NA', 6, '2024-12-08 09:09:58', '2024-12-08 11:13:38', 1300.00),
(27, 'AWS', 'What is the Devops?', 2, '2024-12-08 11:18:52', '2024-12-08 12:26:48', 2000.00),
(28, 'AWS', 'Html is used to Desined for webpages', 1, '2024-12-08 12:24:23', '2024-12-08 14:18:29', 1000.00),
(29, 'AWS', 'python used to develop web applications', 1, '2024-12-08 12:26:03', '2024-12-08 14:16:55', 2000.00);

-- --------------------------------------------------------

--
-- Table structure for table `course_topics`
--

CREATE TABLE `course_topics` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `topic_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_topics`
--

INSERT INTO `course_topics` (`id`, `course_id`, `topic_name`) VALUES
(24, 12, 'Introduction about clouds \r'),
(26, 27, 'euiop');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `tutor_id` int(11) NOT NULL,
  `enrolled_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `user_id`, `course_id`, `course_name`, `tutor_id`, `enrolled_at`) VALUES
(13, 7, 17, 'SAP', 1, '2024-12-08 14:48:46'),
(14, 7, 27, 'AWS', 2, '2024-12-08 19:12:10'),
(15, 7, 12, 'AWS', 2, '2024-12-08 19:38:54'),
(16, 7, 29, 'AWS', 1, '2024-12-08 19:47:24'),
(17, 7, 28, 'AWS', 1, '2024-12-08 19:49:12');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'unread'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `message`, `created_at`, `status`) VALUES
(1, 'Rahul', 'rahul@gmail.com', 'Hey i would like to join in your program', '2024-12-08 11:39:52', 'unread');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_answers`
--

CREATE TABLE `quiz_answers` (
  `id` int(11) NOT NULL,
  `question_id` int(11) DEFAULT NULL,
  `answer_text` text NOT NULL,
  `is_correct` tinyint(1) DEFAULT 0,
  `feedback` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_completions`
--

CREATE TABLE `quiz_completions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `question_text` text NOT NULL,
  `correct_answer` int(11) NOT NULL,
  `video_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_results`
--

CREATE TABLE `quiz_results` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `tutor_name` varchar(255) NOT NULL,
  `score` int(11) NOT NULL,
  `total_questions` int(11) NOT NULL,
  `percentage` decimal(5,2) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_success_stories`
--

CREATE TABLE `student_success_stories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `successtory` text NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `image_alt` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_success_stories`
--

INSERT INTO `student_success_stories` (`id`, `name`, `successtory`, `image_path`, `image_alt`, `created_at`, `updated_at`) VALUES
(1, 'Arun Bhairi', 'qwertr', 'C:xampphtdocsLMSLMS/uploaded_images/Balu and Me.jpg', 'Balu', '2024-12-08 08:23:52', '2024-12-08 08:23:52'),
(2, 'Anitha', 'they will help a lot for increase my knowledge', '/uploaded_images/Anithaphoto.jpg', 'Anitha', '2024-12-08 08:28:56', '2024-12-08 08:28:56');

-- --------------------------------------------------------

--
-- Table structure for table `tutors`
--

CREATE TABLE `tutors` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('instructor') NOT NULL DEFAULT 'instructor',
  `full_name` varchar(100) NOT NULL,
  `bio` text DEFAULT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `resume_path` varchar(255) DEFAULT NULL,
  `certificate_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutors`
--

INSERT INTO `tutors` (`id`, `username`, `email`, `password`, `role`, `full_name`, `bio`, `specialization`, `resume_path`, `certificate_path`, `created_at`) VALUES
(1, 'john_doe', 'john.doe@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'instructor', 'John Doe', 'Experienced math tutor with 10 years of teaching experience.', 'Mathematics', 'uploads/resumes/john_doe_resume.pdf', 'uploads/certificates/john_doe_certificate.pdf', '2024-10-03 04:54:40'),
(2, 'jane_smith', 'jane.smith@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'instructor', 'Jane Smith', 'Passionate about teaching literature and creative writing.', 'English Literature', 'uploads/resumes/jane_smith_resume.pdf', 'uploads/certificates/jane_smith_certificate.pdf', '2024-10-03 04:54:40'),
(3, 'bob_johnson', 'bob.johnson@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'instructor', 'Bob Johnson', 'Computer science expert with a focus on web development.', 'Computer Science', 'uploads/resumes/bob_johnson_resume.pdf', 'uploads/certificates/bob_johnson_certificate.pdf', '2024-10-03 04:54:40'),
(4, 'alice_williams', 'alice.williams@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'instructor', 'Alice Williams', 'Experienced in teaching various science subjects at high school level.', 'Science', 'uploads/resumes/alice_williams_resume.pdf', 'uploads/certificates/alice_williams_certificate.pdf', '2024-10-03 04:54:40'),
(6, 'Gopi', 'gopichand93667@gmail.com', '$2y$10$R3sca5bU/G4Gr1Vc9BhVnOW75DJqnHuGDeklXbgXM7Wf1XZpqVQyu', 'instructor', 'Tayi Gopi Chand', 'na', 'na', '', '', '2024-10-03 05:40:02'),
(8, 'superadmin', 'admin@gmail.com', '$2y$10$DF8u3OgJFv7dbQ3ahq4gAeOoSVu9NA8iQoh7OjEZiw.OBnxygo1JC', 'instructor', 'Nandy', 'na', 'na', '', '', '2024-10-03 06:03:39'),
(9, 'Mahendar ', 'mahenderthotla661@gmail.com', '$2y$10$ngjCI2wAkY2J33GIYRUFpOkEhPgastZcSqHxVKjrz5o.u/f/.bqO.', 'instructor', 'Mahender Thotla', 'Mahender Thotla\\r\\nTutor | IT Professional | Full-Stack Java Expert\\r\\n\\r\\nMahender Thotla is a seasoned IT professional and dedicated tutor with over 10 years of experience in the ever-evolving IT industry. With a deep understanding of real-world challenges and emerging technologies, he specializes in teaching Java Full-Stack Development.\\r\\n\\r\\nThroughout his career, Mahender has worked on diverse projects, gaining expertise in front-end and back-end development, frameworks, and industry best practices. His teaching approach combines practical insights with clear, easy-to-understand concepts, making him a favorite among aspiring developers.\\r\\n\\r\\nWhether you\\\'re a beginner taking your first steps in coding or an experienced developer looking to advance your skills, Mahender\\\'s engaging teaching style and vast industry experience ensure you’re well-equipped to excel in the IT field.\\r\\n\\r\\nExpertise:\\r\\n\\r\\nFull-Stack Java Development\\r\\nFront-End and Back-End Technologies\\r\\nIT Industry Trends and Practical Implementation\\r\\n', 'Java', 'uploads/resumes/1733647299_mysql-initt.txt', '', '2024-12-08 08:41:39'),
(10, 'Sanjay Lakkam', 'sanjay@gmail.com', '$2y$10$4FiujsP.W4egF2Y8nyK7dOVmgYai/E6DNfUDAE/U4Ayqt4lj.8cee', 'instructor', 'Sanjay Akula', 'Lakkam Sanjay is a highly skilled tutor with 5 years of experience in the fields of Production and DevOps. Over the years, he has honed his expertise in optimizing production environments and implementing cutting-edge DevOps practices.\\r\\n\\r\\nWith a strong focus on Production Management, Sanjay specializes in streamlining production workflows, ensuring smooth operational efficiency, and tackling complex challenges related to system scalability, reliability, and performance. In DevOps, his expertise lies in building robust CI/CD pipelines, automating software deployment processes, and fostering a culture of collaboration between development and operations teams.\\r\\n\\r\\nHis hands-on experience and real-world knowledge make him an effective mentor, as he uses practical examples to teach his students how to solve industry-specific problems. Sanjay is dedicated to helping learners and professionals understand the intricacies of modern production systems and DevOps methodologies, preparing them for success in the fast-paced tech world.', 'Devops', 'uploads/resumes/1733656145_Resume Arun Bhairi _ Jobseeker.unknown', '', '2024-12-08 11:09:05'),
(11, '', '', '', 'instructor', 'root', NULL, NULL, NULL, NULL, '2024-12-08 13:26:10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','instructor','admin') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`, `is_active`) VALUES
(1, 'test_student', 'test_student@example.com', '$2y$10$ZhV3Ju.rGn1ki8PukFnQJOhF1s9qP1ncDIthk2/9C3pweUI6YlQCi', 'student', '2024-10-02 05:28:13', 1),
(2, 'test_instructor', 'test_instructor@example.com', '$2y$10$jgMopBLASK44uVGVVd/nJ.QZRF01AfXUxllOecOkcCFyx0412upL.', 'instructor', '2024-10-02 05:28:13', 1),
(6, 'superadmin', 'admin2@example.com', '$2y$10$CDBWGskYIBR0JbNyc7ctXusTQhgGpi98e3uk0kznIqkR7zOxYAoCC', 'student', '2024-10-03 11:24:39', 1),
(7, 'Rohith Patel', 'rohithpatel@gmail.com', '$2y$10$5ocvwYPG66d2kQRtfYn9sOxekzx5jrhELm1p0n7y3yB5yS4h/Viq2', 'student', '2024-12-08 08:20:41', 1);

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `video_id` (`video_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `tutor_id` (`tutor_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tutor_id` (`tutor_id`);

--
-- Indexes for table `course_topics`
--
ALTER TABLE `course_topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`course_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `tutor_id` (`tutor_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quiz_answers`
--
ALTER TABLE `quiz_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `quiz_completions`
--
ALTER TABLE `quiz_completions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `video_id` (`video_id`);

--
-- Indexes for table `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `student_success_stories`
--
ALTER TABLE `student_success_stories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tutors`
--
ALTER TABLE `tutors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `course_topics`
--
ALTER TABLE `course_topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `quiz_answers`
--
ALTER TABLE `quiz_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `quiz_completions`
--
ALTER TABLE `quiz_completions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `quiz_results`
--
ALTER TABLE `quiz_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `student_success_stories`
--
ALTER TABLE `student_success_stories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tutors`
--
ALTER TABLE `tutors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `classes_ibfk_2` FOREIGN KEY (`tutor_id`) REFERENCES `tutors` (`id`);

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`tutor_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `course_topics`
--
ALTER TABLE `course_topics`
  ADD CONSTRAINT `course_topics_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `enrollments_ibfk_3` FOREIGN KEY (`tutor_id`) REFERENCES `tutors` (`id`);

--
-- Constraints for table `quiz_answers`
--
ALTER TABLE `quiz_answers`
  ADD CONSTRAINT `quiz_answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `quiz_questions` (`id`);

--
-- Constraints for table `quiz_completions`
--
ALTER TABLE `quiz_completions`
  ADD CONSTRAINT `quiz_completions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `quiz_completions_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`);

--
-- Constraints for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD CONSTRAINT `quiz_questions_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `quiz_questions_ibfk_2` FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`);

--
-- Constraints for table `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD CONSTRAINT `quiz_results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `quiz_results_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
