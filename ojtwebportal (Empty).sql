-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 21, 2024 at 02:53 PM
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
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `image_data` longblob NOT NULL,
  `TIMESTAMP` datetime NOT NULL
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
  `position` varchar(200) NOT NULL,
  `company_address` varchar(150) NOT NULL,
  `supervisor_email` varchar(150) NOT NULL,
  `supervisor_password` varchar(150) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `supervisor_profile_picture` varchar(150) NOT NULL,
  `verification_code` int(8) NOT NULL,
  `verify_status` varchar(150) NOT NULL DEFAULT 'Not Verified',
  `online_offlineStatus` varchar(150) NOT NULL DEFAULT 'Offline'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Indexes for table `images`
--
ALTER TABLE `images`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deployed_students`
--
ALTER TABLE `deployed_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ojt_requirements`
--
ALTER TABLE `ojt_requirements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students_data`
--
ALTER TABLE `students_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
