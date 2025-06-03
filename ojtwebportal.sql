-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2024 at 06:45 PM
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
-- Database: `ojtwebportal`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_account`
--

CREATE TABLE `admin_account` (
  `id` int(11) NOT NULL,
  `uniqueID` varchar(150) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `id_number` varchar(50) NOT NULL,
  `position` varchar(100) NOT NULL,
  `address` varchar(250) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `admin_profile_picture` varchar(200) NOT NULL,
  `admin_email` varchar(100) NOT NULL,
  `admin_password` varchar(100) NOT NULL,
  `verification_code` int(8) NOT NULL,
  `verify_status` varchar(50) NOT NULL DEFAULT 'Not Verified',
  `online_offlineStatus` varchar(50) NOT NULL DEFAULT 'Offline'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_account`
--

INSERT INTO `admin_account` (`id`, `uniqueID`, `first_name`, `middle_name`, `last_name`, `id_number`, `position`, `address`, `phone_number`, `admin_profile_picture`, `admin_email`, `admin_password`, `verification_code`, `verify_status`, `online_offlineStatus`) VALUES
(1, '662513a17a25e3248', 'test 1', 'A', 'BulSU', 1, 'College of Engineering', 'Gutad', '09457200737', '../student_file_images/COE.png', 'test1@gmail.com', '202cb962ac59075b964b07152d234b70', 710511, 'Verified', 'Offline'),
(2, '662513a17a26a3284', 'test 2', 'B', 'BulSU', 2, 'College of Education', 'Apalit', '09455169235', '../student_file_images/COED.png', 'test2@gmail.com', '202cb962ac59075b964b07152d234b70', 306649, 'Verified', 'Offline'),
(3, '662513a17a26b7452', 'test 3', 'C', 'BulSU', 3, 'College of Arts', 'San Enrique', '09453643105', '../student_file_images/CAL.png', 'test3@gmail.com', '202cb962ac59075b964b07152d234b70', 830404, 'Verified', 'Offline'),
(4, '662513a17a26c4423', 'test 4', 'D', 'BulSU', 4, 'College of Science', 'Ibaan', '09455511874', '../student_file_images/CS.png', 'test4@gmail.com', '202cb962ac59075b964b07152d234b70', 247453, 'Verified', 'Offline');


-- --------------------------------------------------------

--
-- Table structure for table `admin_system_notification`
--

CREATE TABLE `admin_system_notification` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `logs` varchar(150) NOT NULL,
  `logs_date` varchar(50) NOT NULL,
  `logs_time` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Unread'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_system`
--

CREATE TABLE `chat_system` (
  `id` int(11) NOT NULL,
  `sender_id` varchar(200) NOT NULL,
  `receiver_id` varchar(200) NOT NULL,
  `messages` text DEFAULT NULL,
  `images` varchar(500) DEFAULT NULL,
  `date_only` varchar(100) NOT NULL,
  `time_only` varchar(100) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Sent'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company_skills_requirements`
--

CREATE TABLE `company_skills_requirements` (
  `id` int(11) NOT NULL,
  `company_name` varchar(150) NOT NULL,
  `skills_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coordinatorsystemnotification`
--

CREATE TABLE `coordinatorsystemnotification` (
  `id` int(11) NOT NULL,
  `coordinator_id` int(12) NOT NULL,
  `logs` varchar(200) NOT NULL,
  `logs_date` varchar(50) NOT NULL,
  `logs_time` varchar(50) NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'Unread'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coordinators_account`
--

CREATE TABLE `coordinators_account` (
  `id` int(11) NOT NULL,
  `uniqueID` varchar(200) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `faculty_id` varchar(20) NOT NULL,
  `coor_dept` varchar(200) NOT NULL,
  `course_handled` varchar(200) NOT NULL,
  `complete_address` varchar(300) NOT NULL,
  `phone_number` varchar(13) NOT NULL,
  `coordinators_email` varchar(200) NOT NULL,
  `coordinators_password` varchar(200) NOT NULL,
  `coordinators_profile_picture` varchar(500) NOT NULL,
  `verification_code` int(8) NOT NULL,
  `verify_status` varchar(80) NOT NULL DEFAULT 'Not Verified',
  `online_offlineStatus` varchar(50) NOT NULL DEFAULT 'Offline'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coordinators_account`
--

INSERT INTO `coordinators_account` (`id`, `uniqueID`, `first_name`, `middle_name`, `last_name`, `faculty_id`, `coor_dept`, `course_handled`, `complete_address`, `phone_number`, `coordinators_email`, `coordinators_password`, `coordinators_profile_picture`, `verification_code`, `verify_status`, `online_offlineStatus`) VALUES
(1, '662517f7a16b25026', 'CE 1', 'A', 'BulSU', 1, 'College of Engineering', 'Bachelor of Science in Civil Engineering', 'Palagao Norte', '09685607684', 'test1@gmail.com', '202cb962ac59075b964b07152d234b70', '../student_file_images/coor.jpg', 985232, 'Verified', 'Offline'),
(2, '662517f7a16bd3010', 'CpE 1', 'B', 'BulSU', 2, 'College of Engineering', 'Bachelor of Science in Computer Engineering', 'Punay', '09686522915', 'test2@gmail.com', '202cb962ac59075b964b07152d234b70', '../student_file_images/coor.jpg', 582458, 'Verified', 'Offline'),
(3, '662517f7a16be6633', 'CpE 2', 'C', 'BulSU', 3, 'College of Engineering', 'Bachelor of Science in Computer Engineering', 'Koronadal', '09689304768', 'test3@gmail.com', '202cb962ac59075b964b07152d234b70', '../student_file_images/coor.jpg', 514148, 'Verified', 'Offline'),
(4, '662517f7a16bf9002', 'CpE 3', 'D', 'BulSU', 4, 'College of Engineering', 'Bachelor of Science in Computer Engineering', 'Janiuay', '09688522899', 'test4@gmail.com', '202cb962ac59075b964b07152d234b70', '../student_file_images/coor.jpg', 859603, 'Verified', 'Offline'),
(5, '662517f7a16c05801', 'EE 1', 'E', 'BulSU', 5, 'College of Engineering', 'Bachelor of Science in Electrical Engineering', 'Sarrat', '09682583022', 'test5@gmail.com', '202cb962ac59075b964b07152d234b70', '../student_file_images/coor.jpg', 180615, 'Verified', 'Offline'),
(6, '662517f7a16c11814', 'EE 2', 'F', 'BulSU', 6, 'College of Engineering', 'Bachelor of Science in Electrical Engineering', 'San Benito', '09688168368', 'test6@gmail.com', '202cb962ac59075b964b07152d234b70', '../student_file_images/coor.jpg', 549676, 'Verified', 'Offline'),
(7, '662517f7a16c24914', 'ECE 1', 'G', 'BulSU', 7, 'College of Engineering', 'Bachelor of Science in Electronics Engineering', 'Tambalan', '09683346982', 'test7@gmail.com', '202cb962ac59075b964b07152d234b70', '../student_file_images/coor.jpg', 709250, 'Verified', 'Offline'),
(8, '662517f7a16c34249', 'ECE 2', 'H', 'BulSU', 8, 'College of Engineering', 'Bachelor of Science in Electronics Engineering', 'Pagsanghan', '09685795062', 'test8@gmail.com', '202cb962ac59075b964b07152d234b70', '../student_file_images/coor.jpg', 953026, 'Verified', 'Offline'),
(9, '662517f7a16c43746', 'ECE 3', 'I', 'BulSU', 9, 'College of Engineering', 'Bachelor of Science in Electronics Engineering', 'Sibulan', '09685588666', 'test9@gmail.com', '202cb962ac59075b964b07152d234b70', '../student_file_images/coor.jpg', 629490, 'Verified', 'Offline'),
(10, '662517f7a16c51754', 'IE 1', 'J', 'BulSU', 10, 'College of Engineering', 'Bachelor of Science in Industrial Engineering', 'Alilem', '09683130374', 'test10@gmail.com', '202cb962ac59075b964b07152d234b70', '../student_file_images/coor.jpg', 113418, 'Verified', 'Offline'),
(11, '662517f7a16c63159', 'IE 2', 'K', 'BulSU', 11, 'College of Engineering', 'Bachelor of Science in Industrial Engineering', 'Masapang', '09682502479', 'test11@gmail.com', '202cb962ac59075b964b07152d234b70', '../student_file_images/coor.jpg', 237093, 'Verified', 'Offline'),
(12, '662517f7a16c74883', 'MfE 1', 'L', 'BulSU', 12, 'College of Engineering', 'Bachelor of Science in Manufacturing Engineering', 'Bacarra', '09686602985', 'test12@gmail.com', '202cb962ac59075b964b07152d234b70', '../student_file_images/coor.jpg', 327777, 'Verified', 'Offline'),
(13, '662517f7a16c87171', 'MfE 2', 'M', 'BulSU', 13, 'College of Engineering', 'Bachelor of Science in Manufacturing Engineering', 'Lianga', '09687527948', 'test13@gmail.com', '202cb962ac59075b964b07152d234b70', '../student_file_images/coor.jpg', 794257, 'Verified', 'Offline'),
(14, '662517f7a16c96743', 'ME 1', 'N', 'BulSU', 14, 'College of Engineering', 'Bachelor of Science in Mechanical Engineering', 'Lila', '09688775041', 'test14@gmail.com', '202cb962ac59075b964b07152d234b70', '../student_file_images/coor.jpg', 387294, 'Verified', 'Offline'),
(15, '662517f7a16ca2753', 'MEE 1', 'O', 'BulSU', 15, 'College of Engineering', 'Bachelor of Science in Mechatronics Engineering', 'Siclong', '09683739666', 'test15@gmail.com', '202cb962ac59075b964b07152d234b70', '../student_file_images/coor.jpg', 602372, 'Verified', 'Offline'),
(16, '662517f7a16cb7771', 'MEE 2', 'P', 'BulSU', 16, 'College of Engineering', 'Bachelor of Science in Mechatronics Engineering', 'Magsaysay', '09688067586', 'test16@gmail.com', '202cb962ac59075b964b07152d234b70', '../student_file_images/coor.jpg', 654385, 'Verified', 'Offline');

-- --------------------------------------------------------

--
-- Table structure for table `deployed_students`
--

CREATE TABLE `deployed_students` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `company_name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `narrative_reports`
--

CREATE TABLE `narrative_reports` (
  `id` int(11) NOT NULL,
  `student_id` int(12) NOT NULL,
  `dateOFSubmit` varchar(50) NOT NULL,
  `objectives` varchar(2000) NOT NULL,
  `accomplishments` varchar(2000) NOT NULL,
  `reflections` varchar(2000) NOT NULL,
  `realizations` varchar(2000) NOT NULL,
  `knowledge` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ojt_hours`
--

CREATE TABLE `ojt_hours` (
  `id` int(11) NOT NULL,
  `total_hours` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ojt_hours`
--

INSERT INTO `ojt_hours` (`id`, `total_hours`) VALUES
(1, 250.00);

-- --------------------------------------------------------

--
-- Table structure for table `ojt_requirements`
--

CREATE TABLE `ojt_requirements` (
  `id` int(11) NOT NULL,
  `student_id` int(12) NOT NULL,
  `document_name` varchar(500) NOT NULL,
  `document_fileName` varchar(500) NOT NULL,
  `document_location` varchar(1000) NOT NULL,
  `status` varchar(80) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students_data`
--

CREATE TABLE `students_data` (
  `id` int(11) NOT NULL,
  `uniqueID` varchar(200) NOT NULL,
  `first_name` varchar(70) NOT NULL,
  `middle_name` varchar(70) NOT NULL,
  `last_name` varchar(70) NOT NULL,
  `student_ID` varchar(50) NOT NULL,
  `stud_dept` varchar(150) NOT NULL,
  `stud_course` varchar(150) NOT NULL,
  `stud_section` varchar(150) NOT NULL,
  `complete_address` varchar(200) NOT NULL,
  `stud_gender` varchar(100) NOT NULL,
  `phone_number` varchar(12) NOT NULL,
  `stud_email` varchar(200) NOT NULL,
  `stud_password` varchar(200) NOT NULL,
  `guardians_name` varchar(200) NOT NULL,
  `guardians_cpNumber` varchar(20) NOT NULL,
  `profile_picture` varchar(200) NOT NULL,
  `verification_code` int(8) NOT NULL,
  `verify_status` varchar(80) NOT NULL DEFAULT 'Not Verified',
  `online_offlineStatus` varchar(80) NOT NULL DEFAULT 'Offline',
  `ojt_status` varchar(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students_data`
--

INSERT INTO `students_data` (`id`, `uniqueID`, `first_name`, `middle_name`, `last_name`, `student_ID`, `stud_dept`, `stud_course`, `stud_section`, `complete_address`, `stud_gender`, `phone_number`, `stud_email`, `stud_password`, `guardians_name`, `guardians_cpNumber`, `profile_picture`, `verification_code`, `verify_status`, `online_offlineStatus`, `ojt_status`) VALUES
(1, '6625205cd60bf4782', 'CE 1', 'A', 'BulSU', 2020657942, 'College of Engineering', 'Bachelor of Science in Civil Engineering', '3A - CE', 'B02 L60 Sapphire Road, Powell Grove, Mabini, 3639 Laguna', 'Male', '09684773850', 'test1@gmail.com', '202cb962ac59075b964b07152d234b70', 'Riley Narciso', '09354213946', '../student_file_images/Student.jpg', 187622, 'Verified', 'Offline', NULL),
(2, '6625205cd60cc1159', 'CE 2', 'B', 'BulSU', 2020812969, 'College of Engineering', 'Bachelor of Science in Civil Engineering', '3B - CE', '8360F Torres Extension, San Sebastian, 6798 Cebu', 'Female', '09689174672', 'test2@gmail.com', '202cb962ac59075b964b07152d234b70', 'Albert Pablo', '09359029741', '../student_file_images/girl.jpg', 264399, 'Verified', 'Offline', NULL),
(3, '6625205cd60cd7278', 'CpE 1', 'C', 'BulSU', 2020733545, 'College of Engineering', 'Bachelor of Science in Computer Engineering', '3A - CpE', '2790 Tabayoc Road, Squash Estates, Liliw, 8130 Davao del Sur', 'Male', '09688513110', 'test3@gmail.com', '202cb962ac59075b964b07152d234b70', 'Fergus Laurente', '09355892148', '../student_file_images/Student.jpg', 970213, 'Verified', 'Offline', NULL),
(4, '6625205cd60ce3907', 'CpE 2', 'D', 'BulSU', 2020871477, 'College of Engineering', 'Bachelor of Science in Computer Engineering', '3A - CpE', 'Block 05 Lot 90 Samat Road, Jones Homes 8, Pateros, 1685 Metro Manila', 'Female', '09689372981', 'test4@gmail.com', '202cb962ac59075b964b07152d234b70', 'Daniel Guzman', '09357215948', '../student_file_images/girl.jpg', 130605, 'Verified', 'Offline', NULL),
(5, '6625205cd60cf4759', 'EE 1', 'E', 'BulSU', 2020476141, 'College of Engineering', 'Bachelor of Science in Electrical Engineering', '3A - EE', '17th Floor Faulkner Place, 8592 Mariveles Road, Ungkaya Pukan, 6374 Antique', 'Male', '09684444804', 'test5@gmail.com', '202cb962ac59075b964b07152d234b70', 'Elton Rico', '09353672827', '../student_file_images/Student.jpg', 795766, 'Verified', 'Offline', NULL),
(6, '6625205cd60d01403', 'EE 2', 'F', 'BulSU', 2020558713, 'College of Engineering', 'Bachelor of Science in Electrical Engineering', '3B - EE', '9519 Pinatubo Street, Cattleya Homes Phase 6, Aguilar, 5104 Tarlac', 'Female', '09684402162', 'test6@gmail.com', '202cb962ac59075b964b07152d234b70', 'Arvin dela Cruz', '09358869638', '../student_file_images/girl.jpg', 617952, 'Verified', 'Offline', NULL),
(7, '6625205cd60d16348', 'ECE 1', 'G', 'BulSU', 2020765226, 'College of Engineering', 'Bachelor of Science in Electronics Engineering', '3A - ECE', 'Room 3634 Sapphire Building Tower 6, 7083 Banahaw Street, Mandaon, 3367 Masbate', 'Male', '09685905243', 'test7@gmail.com', '202cb962ac59075b964b07152d234b70', 'Ernie Sotto', '09359022462', '../student_file_images/Student.jpg', 648993, 'Verified', 'Offline', NULL),
(8, '6625205cd60d27110', 'ECE 2', 'H', 'BulSU', 2020444786, 'College of Engineering', 'Bachelor of Science in Electronics Engineering', '3B - ECE', '8040 Amethyst Highway, Socorro, 9568 Lanao del Sur', 'Female', '09688153078', 'test8@gmail.com', '202cb962ac59075b964b07152d234b70', 'Jacob Vasquez', '09356941071', '../student_file_images/girl.jpg', 808163, 'Verified', 'Offline', NULL),
(9, '6625205cd60d34910', 'IE 1', 'I', 'BulSU', 2020834027, 'College of Engineering', 'Bachelor of Science in Industrial Engineering', '3A - IE', 'Block 07 Lot 77 Balimbing Estates Phase 4, Jade Avenue, San Antonio, 9470 Sulu', 'Male', '09684142123', 'test9@gmail.com', '202cb962ac59075b964b07152d234b70', 'Aintza Alonzo', '09355917172', '../student_file_images/Student.jpg', 613007, 'Verified', 'Offline', NULL),
(10, '6625205cd60d48477', 'IE 2', 'J', 'BulSU', 2020684522, 'College of Engineering', 'Bachelor of Science in Industrial Engineering', '3B - IE', '4283 Pao Drive, Balanga, 6736 Siquijor', 'Female', '09682416255', 'test10@gmail.com', '202cb962ac59075b964b07152d234b70', 'Charlene Sabado', '09357049575', '../student_file_images/girl.jpg', 577511, 'Verified', 'Offline', NULL),
(11, '6625205cd60d55377', 'MfE 1', 'K', 'BulSU', 2020993242, 'College of Engineering', 'Bachelor of Science in Manufacturing Engineering', '3A - MfE', 'Room 3431 Melon Place, 9836 Caraballo Street, Cabarroguis, 2811 Occidental Mindoro', 'Male', '09681572630', 'test11@gmail.com', '202cb962ac59075b964b07152d234b70', 'Gracelyn Rosales', '09357145440', '../student_file_images/Student.jpg', 328206, 'Verified', 'Offline', NULL),
(12, '6625205cd60d62431', 'MfE 2', 'L', 'BulSU', 2020553259, 'College of Engineering', 'Bachelor of Science in Manufacturing Engineering', '3B - MfE', '9492 Bouganvilla Drive, Nunungan, 3571 Quirino', 'Female', '09688181872', 'test12@gmail.com', '202cb962ac59075b964b07152d234b70', 'Gillian Basa', '09352033579', '../student_file_images/girl.jpg', 598221, 'Verified', 'Offline', NULL),
(13, '6625205cd60d73820', 'ME 1', 'M', 'BulSU', 2020319835, 'College of Engineering', 'Bachelor of Science in Mechanical Engineering', '3A - ME', '8073 Halcon Street, Watkins Village 3, Valderrama, 2964 Tarlac', 'Male', '09685288503', 'test13@gmail.com', '202cb962ac59075b964b07152d234b70', 'Louvel Bernardino', '09351699379', '../student_file_images/Student.jpg', 918642, 'Verified', 'Offline', NULL),
(14, '6625205cd60d83062', 'ME 2', 'N', 'BulSU', 2020216265, 'College of Engineering', 'Bachelor of Science in Mechanical Engineering', '3B - ME', 'B02 L60 Malinao Street, Williams Homes Phase 1, Valenzuela, 1156 Metro Manila', 'Female', '09686818073', 'test14@gmail.com', '202cb962ac59075b964b07152d234b70', 'Angel Bravo', '09358076065', '../student_file_images/girl.jpg', 801842, 'Verified', 'Offline', NULL),
(15, '6625205cd60d92302', 'MEE 1', 'O', 'BulSU', 2020753426, 'College of Engineering', 'Bachelor of Science in Mechatronics Engineering', '3A - MEE', '3153 Zircon Street, Mangatarem, 4249 Oriental Mindoro', 'Male', '09681723513', 'test15@gmail.com', '202cb962ac59075b964b07152d234b70', 'Blessica Benitez', '09355021463', '../student_file_images/Student.jpg', 952884, 'Verified', 'Offline', NULL),
(16, '6625205cd60da7290', 'MEE 2', 'P', 'BulSU', 2020269587, 'College of Engineering', 'Bachelor of Science in Mechatronics Engineering', '3B - MEE', '242 Batino Street, Pagadian, 9284 Misamis Occidental', 'Female', '09684489229', 'test16@gmail.com', '202cb962ac59075b964b07152d234b70', 'Aaliyah Marcelo', '09358296505', '../student_file_images/girl.jpg', 282979, 'Verified', 'Offline', NULL);
-- --------------------------------------------------------

--
-- Table structure for table `stud_daily_time_records`
--

CREATE TABLE `stud_daily_time_records` (
  `id` int(11) NOT NULL,
  `stud_id` int(13) NOT NULL,
  `recordDate` date NOT NULL,
  `AM_time_IN` time NOT NULL,
  `AM_time_OUT` time NOT NULL,
  `PM_time_IN` time NOT NULL,
  `PM_time_OUT` time NOT NULL,
  `total_working_hours` decimal(10,2) NOT NULL,
  `recordStatus` varchar(50) NOT NULL DEFAULT 'Pending',
  `AM_time_IN_pic` varchar(255) DEFAULT NULL,
  `AM_time_OUT_pic` varchar(255) DEFAULT NULL,
  `PM_time_IN_pic` varchar(255) DEFAULT NULL,
  `PM_time_OUT_pic` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stud_evaluation`
--

CREATE TABLE `stud_evaluation` (
  `id` int(15) NOT NULL,
  `stud_id` int(15) NOT NULL,
  `week` varchar(30) NOT NULL,
  `job_knowledge` int(15) NOT NULL,
  `dependability` int(15) NOT NULL,
  `communication_skills` int(15) NOT NULL,
  `conduct` int(15) NOT NULL,
  `initiative_and_creativity` int(15) NOT NULL,
  `cooperatives_and_relationship` int(15) NOT NULL,
  `attendance_and_punctuality` int(15) NOT NULL,
  `total_points` int(12) NOT NULL,
  `comments_suggestions` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stud_skills`
--

CREATE TABLE `stud_skills` (
  `id` int(11) NOT NULL,
  `stud_id` int(13) NOT NULL,
  `skills_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stud_task_list`
--

CREATE TABLE `stud_task_list` (
  `id` int(11) NOT NULL,
  `stud_id` int(13) NOT NULL,
  `task_date_of_deployed` date NOT NULL,
  `task_name` varchar(100) NOT NULL,
  `TASK_description` text NOT NULL,
  `task_date` date NOT NULL,
  `task_priority` varchar(100) NOT NULL,
  `task_status` varchar(100) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supervisor`
--

CREATE TABLE `supervisor` (
  `id` int(11) NOT NULL,
  `uniqueID` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `company_name` varchar(150) NOT NULL,
  `business_nature` varchar(150) NOT NULL,
  `position` varchar(200) NOT NULL,
  `company_address` varchar(150) NOT NULL,
  `date_notarized` varchar(150) NOT NULL,
  `moa_validity` varchar(150) NOT NULL,
  `link_to_moa` varchar(150) NOT NULL,
  `supervisor_email` varchar(150) NOT NULL,
  `supervisor_password` varchar(150) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `supervisor_profile_picture` varchar(150) NOT NULL,
  `verification_code` int(8) NOT NULL,
  `verify_status` varchar(150) NOT NULL DEFAULT 'Not Verified',
  `online_offlineStatus` varchar(150) NOT NULL DEFAULT 'Offline'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supervisor`
--

INSERT INTO `supervisor` (`id`, `uniqueID`, `first_name`, `middle_name`, `last_name`, `company_name`, `position`, `company_address`, `supervisor_email`, `supervisor_password`, `phone_number`, `supervisor_profile_picture`, `verification_code`, `verify_status`, `online_offlineStatus`) VALUES
(1, '66252d8f8c95a7624', 'Dawn', 'A', ' Edwards', 'ADIMRE Construction ', 'CEO', '135 Old Cagayan Valley Road, Sto. Cristo, Pulilan, Bulacan', 'test1@gmail.com', '202cb962ac59075b964b07152d234b70', '09636891723', '../student_file_images/coor.jpg', 788441, 'Verified', 'Offline'),
(2, '66252d8f8c9649173', 'Devin', 'B', ' Garcia', 'Alcatrem Construction Corporation', 'Founder', 'WGC Bldg. Unit D. J.P. Rizal St. Poblacion, Pulilan, Bulacan', 'test2@gmail.com', '202cb962ac59075b964b07152d234b70', '09102140215', '../student_file_images/coor.jpg', 957348, 'Verified', 'Offline'),
(3, '66252d8f8c9652162', 'Latasha', 'C', ' Cole', 'Allied Metals', 'Manager', '2000 Governer F. Halili Highway Muzon City of San Jose del Monte, Bulacan', 'test3@gmail.com', '202cb962ac59075b964b07152d234b70', '09883643854', '../student_file_images/coor.jpg', 402868, 'Verified', 'Offline'),
(4, '66252d8f8c9669130', 'Alexander', 'D', ' Luna', 'AppCase Inc.', 'Manager', 'Rm 303, Holy Cross Savings and Cooperative Bldg., Maysan Road, Malinta, Valenzuela City', 'test4@gmail.com', '202cb962ac59075b964b07152d234b70', '09966972783', '../student_file_images/coor.jpg', 566765, 'Verified', 'Offline'),
(5, '66252d8f8c9671490', 'Heather', 'E', ' Smith', 'Asia Technical Gas Co. Pte. Ltd.', 'Director', '21 Tuas Ave 3 Singapore', 'test5@gmail.com', '202cb962ac59075b964b07152d234b70', '09376654615', '../student_file_images/coor.jpg', 263270, 'Verified', 'Offline'),
(6, '66252d8f8c9685054', 'Matthew', 'F', ' Perry', 'Astrotech Automation', 'Manager', 'PH4 B 101-21 Sta Clara Estate, Sta. rita Guiguinto, Bulacan', 'test6@gmail.com', '202cb962ac59075b964b07152d234b70', '09107366015', '../student_file_images/coor.jpg', 708232, 'Verified', 'Offline'),
(7, '66252d8f8c9693493', 'David', 'G', ' Jones', 'AV Make Industries', 'Adin Executive', '22 Garnet Capitol View Park Subdivision, Bulihan, Malolos, Bulacan', 'test7@gmail.com', '202cb962ac59075b964b07152d234b70', '09266946372', '../student_file_images/coor.jpg', 715179, 'Verified', 'Offline'),
(8, '66252d8f8c96a9961', 'Michael', 'H', ' Wells', 'AVECS Corporation', 'Manager', '#21 Don Gregorio St. Don Antonio Heights,', 'test8@gmail.com', '202cb962ac59075b964b07152d234b70', '09414571059', '../student_file_images/coor.jpg', 669831, 'Verified', 'Offline'),
(9, '66252d8f8c96b6342', 'Anthony', 'I', ' Summers', 'Baliwag Planning and Development Office', 'Vice President', 'B.S. Aquino Avenue, Bagong Nayon, Baliwag, Bulacan', 'test9@gmail.com', '202cb962ac59075b964b07152d234b70', '09606419054', '../student_file_images/coor.jpg', 754554, 'Verified', 'Offline'),
(10, '66252d8f8c96c2939', 'Robert', 'J', ' Carter', 'Bestmens Telecommunication Services', 'Coordinator', 'Agila Cpmpound, Fausta Road Malolos City. Bulacan', 'test10@gmail.com', '202cb962ac59075b964b07152d234b70', '09818077760', '../student_file_images/coor.jpg', 724360, 'Verified', 'Offline'),
(11, '66252d8f8c96d6156', 'Eric', 'L', ' Weaver', 'BJM Construction Corporation', 'Owner', 'Lot 8 Block 16 street, Sterling Business Park Brgy. Patubig, Marilao, Bulacan ', 'test11@gmail.com', '202cb962ac59075b964b07152d234b70', '09266904109', '../student_file_images/coor.jpg', 369548, 'Verified', 'Offline'),
(12, '66252d8f8c96e3806', 'Patricia', 'M', ' Gonzalez', 'Bricolage Distributer Inc.', 'President', 'Warehouse 10, Welborne Industrial Park, Governors Drive, Brgy. Bangkal, Carmona, Cavite', 'test12@gmail.com', '202cb962ac59075b964b07152d234b70', '09597763207', '../student_file_images/coor.jpg', 143642, 'Verified', 'Offline'),
(13, '66252d8f8c96f2360', 'Molly', 'N', ' Hicks', 'Centro Manufacturing Corporation', 'Manager', '91 Villarica Road, Loma de Gato, Marilao, Bulacan', 'test13@gmail.com', '202cb962ac59075b964b07152d234b70', '09975761815', '../student_file_images/coor.jpg', 469657, 'Verified', 'Offline'),
(14, '66252d8f8c9701951', 'Christopher', 'O', ' Walsh', 'Circa Logica Group', 'Manager', 'Clark Gateway Commercial Complex, Gil Puyat Mabalacat, Pampanga Ave..', 'test14@gmail.com', '202cb962ac59075b964b07152d234b70', '09681433073', '../student_file_images/coor.jpg', 499527, 'Verified', 'Offline'),
(15, '66252d8f8c9719149', 'Mallory', 'P', ' Mcgee', 'CM Pancho Construction Inc.', 'CEO', '71 A Borromeo, Sct. Diliman Quezon City', 'test15@gmail.com', '202cb962ac59075b964b07152d234b70', '09513862249', '../student_file_images/coor.jpg', 857117, 'Verified', 'Offline'),
(16, '66252d8f8c9722568', 'Michael', 'Q', ' Hall', 'Coca Cola Beverages Philippine Inc.', 'Manager', '28F Net Lima Building, 5th Avenue corner 26th Street,BGC', 'test16@gmail.com', '202cb962ac59075b964b07152d234b70', '09973055085', '../student_file_images/coor.jpg', 580756, 'Verified', 'Offline'),
(17, '66252d8f8c9737179', 'Susan', 'R', ' Sullivan', 'Controlgear Corporation Electric', 'Manager', '117 RE Chico St. Sto. Cristo, Baliuag, Bulacan', 'test17@gmail.com', '202cb962ac59075b964b07152d234b70', '09628147325', '../student_file_images/coor.jpg', 157568, 'Verified', 'Offline'),
(18, '66252d8f8c9746933', 'David', 'S', ' Kane', 'Coppa Storia Studios', 'Manager', '718 Rizal Ave. Ext., Tanong. Brgy. Malabon City', 'test18@gmail.com', '202cb962ac59075b964b07152d234b70', '09202306433', '../student_file_images/coor.jpg', 716786, 'Verified', 'Offline'),
(19, '66252d8f8c9756470', 'Melissa', 'T', ' Andersen', 'D3 Construction Services Corp.', 'Head Accountant', 'Mc Arthur Highway, Sampalaoc, Apalit, Pampanga', 'test19@gmail.com', '202cb962ac59075b964b07152d234b70', '09228167672', '../student_file_images/coor.jpg', 258266, 'Verified', 'Offline'),
(20, '66252d8f8c9761115', 'Cindy', 'U', ' Thornton', 'Department Information Communication of and Technology', 'Manager', '3/F Building Marison Cagayan Valley Road, Sta. Rita, Guiguinto, Bulacan', 'test20@gmail.com', '202cb962ac59075b964b07152d234b70', '09658586345', '../student_file_images/coor.jpg', 368515, 'Verified', 'Offline'),
(21, '66252d8f8c9774476', 'James', 'V', ' Moore', 'DM Consunji, Inc.', 'Director', 'DMCI Plaza Bldg.. #2281 Chino Roces Ave. Ext., Bgry. Magallanes, Makati, 1231 Metro Manila', 'test21@gmail.com', '202cb962ac59075b964b07152d234b70', '09793994782', '../student_file_images/coor.jpg', 307397, 'Verified', 'Offline'),
(22, '66252d8f8c9789386', 'Howard', 'W', ' Wilson', 'DPWH Bulacan 2nd District Office Engineering', 'HR Dept. Head', 'Pulong Buhangin, Sta. Maria, Bulacan', 'test22@gmail.com', '202cb962ac59075b964b07152d234b70', '09534202358', '../student_file_images/coor.jpg', 455349, 'Verified', 'Offline'),
(23, '66252d8f8c9798704', 'Carol', 'X', ' Brown', 'DyiPay', 'District Engineer', 'Blk 5. Lot 3, Bayani Road, Serata Homes, San Jose Del Monte, Bulacan ', 'test23@gmail.com', '202cb962ac59075b964b07152d234b70', '09162331668', '../student_file_images/coor.jpg', 166694, 'Verified', 'Offline'),
(24, '66252d8f8c97a8267', 'Kristen', 'Y', ' Nixon', 'Eastgate.tech Software Engineering', 'CEO', 'Malolos Bulacan City,', 'test24@gmail.com', '202cb962ac59075b964b07152d234b70', '09301254622', '../student_file_images/coor.jpg', 200086, 'Verified', 'Offline'),
(25, '66252d8f8c97b6620', 'Dennis', 'Z', ' Salazar', 'Eastgroup Corporation', 'Manager', 'Arcade #63 Malakas St.', 'test25@gmail.com', '202cb962ac59075b964b07152d234b70', '09152080083', '../student_file_images/coor.jpg', 961565, 'Verified', 'Offline'),
(26, '66252d8f8c97c7638', 'Nathaniel', 'A', ' Carlson', 'Electroline Corporation', 'Director', 'NIA ROAD, Barangay Pinagbarilan, Baliuag, Bulacan ', 'test26@gmail.com', '202cb962ac59075b964b07152d234b70', '09508610593', '../student_file_images/coor.jpg', 306583, 'Verified', 'Offline'),
(27, '66252d8f8c97d9474', 'Mitchell', 'B', ' Flynn', 'ErSol Wealth Phils Engineering Services', 'CEO', '13 National Road, Paltao. Pulilan, Bulacan ', 'test27@gmail.com', '202cb962ac59075b964b07152d234b70', '09792553519', '../student_file_images/coor.jpg', 518282, 'Verified', 'Offline'),
(28, '66252d8f8c97e4766', 'Thomas', 'C', ' Wilson', 'Enertech Systems Industries', 'CEO', '3855 Technology Road, Prenza II, Marilao, Bulacan ', 'test28@gmail.com', '202cb962ac59075b964b07152d234b70', '09142493501', '../student_file_images/coor.jpg', 387344, 'Verified', 'Offline'),
(29, '66252d8f8c97f9275', 'Kari', 'D', ' Gomez', 'Essential Vet Laboratories Inc', 'Vice President', 'Rm 408 Columbian Building 160 West Avenue Quezon City, and Plant address at Matimyas SL Santa Cruz Santa Maria, Bulacan', 'test29@gmail.com', '202cb962ac59075b964b07152d234b70', '09153733801', '../student_file_images/coor.jpg', 163893, 'Verified', 'Offline'),
(30, '66252d8f8c9802451', 'Joshua', 'E', ' Good', 'Estatoora Consulting Firm', 'CEO', '183 Zone 6, Gugo, Calumpit, Bulacan', 'test30@gmail.com', '202cb962ac59075b964b07152d234b70', '09969480202', '../student_file_images/coor.jpg', 551223, 'Verified', 'Offline');

-- --------------------------------------------------------

--
-- Table structure for table `supervisor_system_notification`
--

CREATE TABLE `supervisor_system_notification` (
  `id` int(11) NOT NULL,
  `supervisor_id` int(13) NOT NULL,
  `logs` varchar(200) NOT NULL,
  `logs_date` varchar(50) NOT NULL,
  `logs_time` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Unread'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_notification`
--

CREATE TABLE `system_notification` (
  `id` int(11) NOT NULL,
  `student_id` int(12) NOT NULL,
  `logs` varchar(200) NOT NULL,
  `logs_date` varchar(50) NOT NULL,
  `logs_time` varchar(50) NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'Unread'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_account`
--
ALTER TABLE `admin_account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_system_notification`
--
ALTER TABLE `admin_system_notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_system`
--
ALTER TABLE `chat_system`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_skills_requirements`
--
ALTER TABLE `company_skills_requirements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coordinatorsystemnotification`
--
ALTER TABLE `coordinatorsystemnotification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coordinators_account`
--
ALTER TABLE `coordinators_account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deployed_students`
--
ALTER TABLE `deployed_students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `narrative_reports`
--
ALTER TABLE `narrative_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ojt_hours`
--
ALTER TABLE `ojt_hours`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ojt_requirements`
--
ALTER TABLE `ojt_requirements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students_data`
--
ALTER TABLE `students_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stud_daily_time_records`
--
ALTER TABLE `stud_daily_time_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stud_evaluation`
--
ALTER TABLE `stud_evaluation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stud_skills`
--
ALTER TABLE `stud_skills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stud_task_list`
--
ALTER TABLE `stud_task_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supervisor`
--
ALTER TABLE `supervisor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supervisor_system_notification`
--
ALTER TABLE `supervisor_system_notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_notification`
--
ALTER TABLE `system_notification`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_account`
--
ALTER TABLE `admin_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `admin_system_notification`
--
ALTER TABLE `admin_system_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_system`
--
ALTER TABLE `chat_system`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_skills_requirements`
--
ALTER TABLE `company_skills_requirements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coordinatorsystemnotification`
--
ALTER TABLE `coordinatorsystemnotification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coordinators_account`
--
ALTER TABLE `coordinators_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `deployed_students`
--
ALTER TABLE `deployed_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `narrative_reports`
--
ALTER TABLE `narrative_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ojt_hours`
--
ALTER TABLE `ojt_hours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ojt_requirements`
--
ALTER TABLE `ojt_requirements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `students_data`
--
ALTER TABLE `students_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `stud_daily_time_records`
--
ALTER TABLE `stud_daily_time_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stud_evaluation`
--
ALTER TABLE `stud_evaluation`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stud_skills`
--
ALTER TABLE `stud_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stud_task_list`
--
ALTER TABLE `stud_task_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supervisor`
--
ALTER TABLE `supervisor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `supervisor_system_notification`
--
ALTER TABLE `supervisor_system_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_notification`
--
ALTER TABLE `system_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
