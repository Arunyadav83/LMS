-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2024 at 02:56 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
  `video_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `course_id`, `tutor_id`, `class_name`, `description`, `video_path`, `is_online`, `online_link`, `schedule_time`, `created_at`, `video_id`) VALUES
(4, 1, 8, 'AWS', 'AWS', 'uploads/class_videos/1727956226_Day 081 - 🤯Css newsletter signup #coding #frontend #softwaredeveloper #webdevelopment #programming.mp4', 0, '0', '0000-00-00 00:00:00', '2024-10-03 11:50:26', NULL),
(6, 1, 6, 'AWS', 'AWS', 'uploads/class_videos/1727959089_Day 081 - 🤯Css newsletter signup #coding #frontend #softwaredeveloper #webdevelopment #programming.mp4', 0, '0', '0000-00-00 00:00:00', '2024-10-03 12:38:09', NULL),
(7, 1, 6, 'Day-2', 'topic covered', 'uploads/class_videos/1727959947_Day 083 - 😍Css Scroll With Light #coding #frontend #webdevelopment #softwaredeveloper #programming.mp4', 0, '0', '0000-00-00 00:00:00', '2024-10-03 12:52:27', NULL);

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `title`, `description`, `tutor_id`, `created_at`, `updated_at`) VALUES
(1, 'AWS', 'NA', 2, '2024-10-03 04:11:00', '2024-10-03 04:11:00'),
(7, 'HTML', 'NA', 1, '2024-10-03 05:22:25', '2024-10-03 05:23:02');

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
(19, 1, 'Introduction to Programming'),
(21, 7, 'NA');

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
(10, 6, 1, 'AWS', 2, '2024-10-03 16:59:20');

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

--
-- Dumping data for table `quiz_answers`
--

INSERT INTO `quiz_answers` (`id`, `question_id`, `answer_text`, `is_correct`, `feedback`) VALUES
(13, 4, 'To retrieve only unique records from a table.', 0, 'This option is incorrect because retrieving unique records is achieved using the DISTINCT keyword in SQL, not the JOIN clause. DISTINCT eliminates duplicate rows from the result set, whereas JOIN is for combining related rows from two or more tables.'),
(14, 4, 'To combine rows from two or more tables based on a related column.', 0, 'The JOIN clause in SQL is used to retrieve data from two or more tables based on a condition that matches columns from the tables, typically using a common key or relationship. This allows you to combine data stored in separate tables and retrieve it in a single query. For example, a common use of a JOIN is to combine customer data from a customers table with their order data from an orders table based on a shared customer ID. The other options describe other SQL operations but do not relate to the JOIN clause’s purpose.'),
(15, 4, 'To update records in a table.', 0, 'This option is incorrect because updating records is done using the UPDATE statement in SQL, not the JOIN clause. UPDATE modifies existing data in a table, whereas JOIN is used to retrieve and combine data from multiple tables, not to modify it.'),
(16, 4, 'To delete records from multiple tables at once.', 0, 'This option is incorrect because deleting records is done using the DELETE statement, often combined with the WHERE clause. While JOIN may be used within a DELETE query in some cases, the primary function of JOIN is not to delete records but to combine rows from different tables based on related columns.'),
(21, 6, 'To retrieve only unique records from a table.', 0, 'This option is incorrect because retrieving unique records is achieved using the DISTINCT keyword in SQL, not the JOIN clause. DISTINCT eliminates duplicate rows from the result set, whereas JOIN is for combining related rows from two or more tables.'),
(22, 6, 'To combine rows from two or more tables based on a related column.', 0, 'The JOIN clause in SQL is used to retrieve data from two or more tables based on a condition that matches columns from the tables, typically using a common key or relationship. This allows you to combine data stored in separate tables and retrieve it in a single query. For example, a common use of a JOIN is to combine customer data from a customers table with their order data from an orders table based on a shared customer ID. The other options describe other SQL operations but do not relate to the JOIN clause’s purpose.'),
(23, 6, 'To update records in a table.', 1, 'This option is incorrect because updating records is done using the UPDATE statement in SQL, not the JOIN clause. UPDATE modifies existing data in a table, whereas JOIN is used to retrieve and combine data from multiple tables, not to modify it.'),
(24, 6, 'To delete records from multiple tables at once.', 0, 'This option is incorrect because deleting records is done using the DELETE statement, often combined with the WHERE clause. While JOIN may be used within a DELETE query in some cases, the primary function of JOIN is not to delete records but to combine rows from different tables based on related columns.'),
(25, 7, 'To retrieve only unique records from a table.', 0, 'This option is incorrect because retrieving unique records is achieved using the DISTINCT keyword in SQL, not the JOIN clause. DISTINCT eliminates duplicate rows from the result set, whereas JOIN is for combining related rows from two or more tables.'),
(26, 7, 'To combine rows from two or more tables based on a related column.', 0, 'The JOIN clause in SQL is used to retrieve data from two or more tables based on a condition that matches columns from the tables, typically using a common key or relationship. This allows you to combine data stored in separate tables and retrieve it in a single query. For example, a common use of a JOIN is to combine customer data from a customers table with their order data from an orders table based on a shared customer ID. The other options describe other SQL operations but do not relate to the JOIN clause’s purpose.'),
(27, 7, 'To update records in a table.', 0, 'This option is incorrect because updating records is done using the UPDATE statement in SQL, not the JOIN clause. UPDATE modifies existing data in a table, whereas JOIN is used to retrieve and combine data from multiple tables, not to modify it.'),
(28, 7, 'To delete records from multiple tables at once.', 0, 'This option is incorrect because deleting records is done using the DELETE statement, often combined with the WHERE clause. While JOIN may be used within a DELETE query in some cases, the primary function of JOIN is not to delete records but to combine rows from different tables based on related columns.'),
(29, 8, 'To retrieve only unique records from a table.', 0, 'This option is incorrect because retrieving unique records is achieved using the DISTINCT keyword in SQL, not the JOIN clause. DISTINCT eliminates duplicate rows from the result set, whereas JOIN is for combining related rows from two or more tables.'),
(30, 8, 'To retrieve only unique records from a table.', 0, 'This option is incorrect because retrieving unique records is achieved using the DISTINCT keyword in SQL, not the JOIN clause. DISTINCT eliminates duplicate rows from the result set, whereas JOIN is for combining related rows from two or more tables.'),
(31, 8, 'To retrieve only unique records from a table.', 0, 'This option is incorrect because retrieving unique records is achieved using the DISTINCT keyword in SQL, not the JOIN clause. DISTINCT eliminates duplicate rows from the result set, whereas JOIN is for combining related rows from two or more tables.'),
(32, 8, 'To retrieve only unique records from a table.', 0, 'This option is incorrect because retrieving unique records is achieved using the DISTINCT keyword in SQL, not the JOIN clause. DISTINCT eliminates duplicate rows from the result set, whereas JOIN is for combining related rows from two or more tables.');

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

--
-- Dumping data for table `quiz_questions`
--

INSERT INTO `quiz_questions` (`id`, `class_id`, `question_text`, `correct_answer`, `video_id`) VALUES
(4, 4, 'What is the primary purpose of the JOIN clause in SQL?', 1, NULL),
(6, 4, 'What is the primary purpose of the JOIN clause in SQL?', 0, NULL),
(7, 6, 'What is the primary purpose of the JOIN clause in SQL?', 1, NULL),
(8, 7, 'What is the primary purpose of the JOIN clause in SQL?', 3, NULL);

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

--
-- Dumping data for table `quiz_results`
--

INSERT INTO `quiz_results` (`id`, `user_id`, `class_id`, `tutor_name`, `score`, `total_questions`, `percentage`, `submitted_at`) VALUES
(4, 6, 4, '', 1, 2, 50.00, '2024-10-03 12:28:53'),
(5, 6, 4, '', 0, 2, 0.00, '2024-10-03 12:31:18'),
(6, 6, 4, 'Jane Smith', 0, 2, 0.00, '2024-10-03 12:36:54'),
(7, 6, 7, 'Jane Smith', 0, 1, 0.00, '2024-10-03 12:53:31');

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
(8, 'superadmin', 'admin@gmail.com', '$2y$10$DF8u3OgJFv7dbQ3ahq4gAeOoSVu9NA8iQoh7OjEZiw.OBnxygo1JC', 'instructor', 'Nandy', 'na', 'na', '', '', '2024-10-03 06:03:39');

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
(6, 'superadmin', 'admin2@example.com', '$2y$10$CDBWGskYIBR0JbNyc7ctXusTQhgGpi98e3uk0kznIqkR7zOxYAoCC', 'student', '2024-10-03 11:24:39', 1);

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
-- Indexes for table `quiz_answers`
--
ALTER TABLE `quiz_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `course_topics`
--
ALTER TABLE `course_topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `quiz_answers`
--
ALTER TABLE `quiz_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `quiz_results`
--
ALTER TABLE `quiz_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tutors`
--
ALTER TABLE `tutors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
