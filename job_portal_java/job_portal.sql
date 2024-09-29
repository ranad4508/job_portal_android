-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 13, 2024 at 01:12 PM
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
-- Database: `job_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `a_id` int(11) NOT NULL,
  `a_Name` varchar(255) NOT NULL,
  `a_Email` varchar(255) NOT NULL,
  `a_Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`a_id`, `a_Name`, `a_Email`, `a_Password`) VALUES
(1, 'Dinesh Rana', 'ranad4508@gmail.com', '2cabf71633c22f3a0cac919347cf1708e676535a]');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `job_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `job_type` varchar(100) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `posted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `job_img` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`job_id`, `title`, `description`, `salary`, `job_type`, `location`, `posted_at`, `job_img`, `company_name`, `category_id`) VALUES
(1, 'Junior Software Developer', 'Responsible for developing new software and maintaining existing applications.', 60000.00, 'Full-Time', 'New York, NY', '2024-08-31 18:15:00', 'jobImage/smartwatch-6.png', 'TechCorp', 1),
(2, 'Data Analyst', 'Analyze data to help guide business decisions.', 55000.00, 'Full-Time', 'San Francisco, CA', '2024-09-01 18:15:00', 'jobImage/headphone-1.jpg', 'DataSolutions', 2),
(3, 'Graphic Designer', 'Create visual concepts and designs for digital and print media.', 45000.00, 'Part-Time', 'Austin, TX', '2024-09-02 18:15:00', 'jobImage/coder.webp', 'CreativeWorks', 3),
(4, 'Marketing Specialist', 'Develop and implement marketing strategies to increase brand awareness.', 52000.00, 'Full-Time', 'Los Angeles, CA', '2024-09-03 18:15:00', 'jobImage/google.png', 'MarketPros', 4),
(5, 'Financial Analyst', 'Provide financial guidance and support to clients or businesses.', 70000.00, 'Full-Time', 'Chicago, IL', '2024-09-04 18:15:00', 'jobImage/coding.jpg', 'FinCorp', 5),
(6, 'HR Coordinator', 'Assist in the recruitment process and manage employee relations.', 48000.00, 'Part-Time', 'Miami, FL', '2024-09-05 18:15:00', 'jobImage/geeky-profile-icon.jpg', 'HRExperts', 6),
(7, 'Registered Nurse', 'Provide healthcare services and support to patients.', 65000.00, 'Full-Time', 'Houston, TX', '2024-09-06 18:15:00', 'jobImage/krypton_ai_image.jpeg', 'HealthCarePlus', 7),
(8, 'Teacher', 'Provide educational instruction to students in a classroom setting.', 40000.00, 'Full-Time', 'Seattle, WA', '2024-09-07 18:15:00', 'jobImage/luffy.jpg', 'EduWorld', 8),
(9, 'Sales Executive', 'Generate leads and drive sales for company products or services.', 50000.00, 'Full-Time', 'Boston, MA', '2024-09-08 18:15:00', 'jobImage/roanldo-messi-neymar.jpg', 'SalesGurus', 9),
(10, 'Customer Service Representative', 'Assist customers by answering questions and resolving issues.', 35000.00, 'Part-Time', 'Phoenix, AZ', '2024-09-09 18:15:00', 'jobImage/google.png', 'SupportHub', 10);

-- --------------------------------------------------------

--
-- Table structure for table `job_applications`
--

CREATE TABLE `job_applications` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `job_title` varchar(255) NOT NULL,
  `resume_file` varchar(255) NOT NULL,
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Scheduled for interview','Pending','Rejected') DEFAULT 'Pending',
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_applications`
--

INSERT INTO `job_applications` (`id`, `job_id`, `company_name`, `job_title`, `resume_file`, `applied_at`, `status`, `user_id`) VALUES
(3, 3, 'CreativeWorks', 'Graphic Designer', 'content://com.google.android.apps.docs.storage/document/acc%3D1%3Bdoc%3Dencoded%3DUVVFfz1rymrPGqb2%2FEsykvnUkz7RXSA%2BkJjRvQtvfl%2FaG%2FElSDgz', '2024-09-13 10:24:16', 'Scheduled for interview', 2),
(4, 8, 'EduWorld', 'Teacher', 'content://com.google.android.apps.docs.storage/document/acc%3D1%3Bdoc%3Dencoded%3DUVVFfz1rymrPGqb2%2FEsykvnUkz7RXSA%2BkJjRvQtvfl%2FaG%2FElSDgz', '2024-09-13 10:24:36', 'Rejected', 2),
(5, 6, 'HRExperts', 'HR Coordinator', 'content://com.google.android.apps.docs.storage/document/acc%3D1%3Bdoc%3Dencoded%3DUVVFfz1rymrPGqb2%2FEsykvnUkz7RXSA%2BkJjRvQtvfl%2FaG%2FElSDgz', '2024-09-13 10:29:15', 'Rejected', 2),
(6, 6, 'HRExperts', 'HR Coordinator', 'content://com.google.android.apps.docs.storage/document/acc%3D1%3Bdoc%3Dencoded%3DUVVFfz1rymrPGqb2%2FEsykvnUkz7RXSA%2BkJjRvQtvfl%2FaG%2FElSDgz', '2024-09-13 11:00:27', 'Pending', 4);

-- --------------------------------------------------------

--
-- Table structure for table `job_categories`
--

CREATE TABLE `job_categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_categories`
--

INSERT INTO `job_categories` (`category_id`, `category_name`) VALUES
(1, 'Software Development'),
(2, 'Data Science'),
(3, 'Design & Creative'),
(4, 'Marketing'),
(5, 'Finance'),
(6, 'Human Resources'),
(7, 'Healthcare'),
(8, 'Education & Training'),
(9, 'Sales'),
(10, 'Customer Support');

-- --------------------------------------------------------

--
-- Table structure for table `job_details`
--

CREATE TABLE `job_details` (
  `detail_id` int(11) NOT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `job_description` text DEFAULT NULL,
  `job_requirements` text DEFAULT NULL,
  `job_type` varchar(100) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `posted_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `deadline_date` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_details`
--

INSERT INTO `job_details` (`detail_id`, `job_title`, `job_description`, `job_requirements`, `job_type`, `salary`, `location`, `posted_date`, `deadline_date`, `is_active`) VALUES
(1, 'Junior Software Developer', 'Responsible for developing new software and maintaining existing applications.', 'Bachelor’s degree in Computer Science, 1+ years experience, Knowledge of Java, SQL, and Flutter', 'Full-Time', 60000.00, 'New York, NY', '2024-08-31 18:15:00', '2024-10-01', 1),
(2, 'Data Analyst', 'Analyze data to help guide business decisions.', 'Bachelor’s degree in Data Science, Proficiency in SQL and Excel, Strong analytical skills', 'Full-Time', 55000.00, 'San Francisco, CA', '2024-09-01 18:15:00', '2024-10-02', 1),
(3, 'Graphic Designer', 'Create visual concepts and designs for digital and print media.', 'Experience with Adobe Photoshop, Illustrator, Creativity and attention to detail', 'Part-Time', 45000.00, 'Austin, TX', '2024-09-02 18:15:00', '2024-10-03', 1),
(4, 'Marketing Specialist', 'Develop and implement marketing strategies to increase brand awareness.', 'Bachelor’s degree in Marketing, Knowledge of SEO and social media marketing', 'Full-Time', 52000.00, 'Los Angeles, CA', '2024-09-03 18:15:00', '2024-10-04', 1),
(5, 'Financial Analyst', 'Provide financial guidance and support to clients or businesses.', 'Bachelor’s degree in Finance, Strong Excel and financial modeling skills', 'Full-Time', 70000.00, 'Chicago, IL', '2024-09-04 18:15:00', '2024-10-05', 1),
(6, 'HR Coordinator', 'Assist in the recruitment process and manage employee relations.', 'Bachelor’s degree in Human Resources, Strong interpersonal and communication skills', 'Part-Time', 48000.00, 'Miami, FL', '2024-09-05 18:15:00', '2024-10-06', 1),
(7, 'Registered Nurse', 'Provide healthcare services and support to patients.', 'Nursing license, Strong attention to detail and empathy for patients', 'Full-Time', 65000.00, 'Houston, TX', '2024-09-06 18:15:00', '2024-10-07', 1),
(8, 'Teacher', 'Provide educational instruction to students in a classroom setting.', 'Bachelor’s degree in Education, Classroom management skills, Passion for teaching', 'Full-Time', 40000.00, 'Seattle, WA', '2024-09-07 18:15:00', '2024-10-08', 1),
(9, 'Sales Executive', 'Generate leads and drive sales for company products or services.', 'Bachelor’s degree in Business, Strong communication and negotiation skills', 'Full-Time', 50000.00, 'Boston, MA', '2024-09-08 18:15:00', '2024-10-09', 1),
(10, 'Customer Service Representative', 'Assist customers by answering questions and resolving issues.', 'Strong communication skills, Ability to handle customer queries and resolve complaints', 'Part-Time', 35000.00, 'Phoenix, AZ', '2024-09-09 18:15:00', '2024-10-10', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(10) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(80) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `address` varchar(80) NOT NULL,
  `password` varchar(50) NOT NULL,
  `occupation` varchar(50) NOT NULL,
  `date_of_birth` varchar(50) NOT NULL,
  `user_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `firstname`, `lastname`, `email`, `phone`, `address`, `password`, `occupation`, `date_of_birth`, `user_image`) VALUES
(2, 'Rohni', 'Koirala', 'roshni@gmail.com', '9888654321', 'Koteshwor', '02aaf8275c2885af0d601b2ec52250ad8d8c57a8', 'Student', '2001-9-27', NULL),
(4, 'Dinesh Kumar', 'Rana', 'dinesh@gmail.com', '9787654320', 'Kathmandu', '52f1173e2538b8f835fbea1e978342ea7b06d067', 'Student', '2001-10-10', 'profileImage/my_profile.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`job_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `job_categories`
--
ALTER TABLE `job_categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `job_details`
--
ALTER TABLE `job_details`
  ADD PRIMARY KEY (`detail_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `job_applications`
--
ALTER TABLE `job_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `job_categories`
--
ALTER TABLE `job_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `job_categories` (`category_id`) ON DELETE CASCADE;

--
-- Constraints for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD CONSTRAINT `job_applications_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `job_applications_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `job_details`
--
ALTER TABLE `job_details`
  ADD CONSTRAINT `job_details_ibfk_1` FOREIGN KEY (`detail_id`) REFERENCES `jobs` (`job_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
