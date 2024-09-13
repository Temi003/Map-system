-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 13, 2024 at 10:19 PM
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
-- Database: `map`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `resource` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `Begin time` datetime DEFAULT NULL,
  `End time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `resource`, `email`, `Begin time`, `End time`) VALUES
(34, 'Lab 2', 'agbelusitomisin@gmail.com', NULL, NULL),
(35, 'Lab 2', 'agbelusitomisi@gmail.com', NULL, NULL),
(37, 'Room 307 3rd floor', 'temidudu2003@gmail.com', '2024-09-10 11:00:00', '2024-09-10 11:01:00'),
(38, 'Lab 2', 'agbelusitomisin@gmail.com', NULL, NULL),
(41, 'Room 305 4th floor', 'agbelusitomisi@gmail.com', NULL, NULL),
(42, 'Room 307 3rd floor', 'temidudu2003@gmail.com', NULL, NULL),
(43, 'Lab 2', 'agbelusitomisin@gmail.com', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `ID` int(11) NOT NULL,
  `Course Name` varchar(255) NOT NULL,
  `Lecturer Name` varchar(50) NOT NULL,
  `Class Year` varchar(20) NOT NULL,
  `Added At` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Resource` varchar(50) NOT NULL,
  `Start Time` time NOT NULL,
  `End Time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`ID`, `Course Name`, `Lecturer Name`, `Class Year`, `Added At`, `Resource`, `Start Time`, `End Time`) VALUES
(3, 'python', 'eric', 'Year 2 software', '2024-09-12 09:53:29', '3rd floor room 365', '08:30:00', '14:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `contact us`
--

CREATE TABLE `contact us` (
  `id` int(30) NOT NULL,
  `First Name` varchar(30) NOT NULL,
  `Last Name` varchar(30) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Message` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact us`
--

INSERT INTO `contact us` (`id`, `First Name`, `Last Name`, `Email`, `Message`) VALUES
(1, 'tomisin', 'Agbelusi', 'agbelusitomisin@gmail.com', 'nn');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `Employee_ID` int(10) NOT NULL,
  `First Name` varchar(30) NOT NULL,
  `Last Name` varchar(30) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `Password` varchar(20) NOT NULL,
  `Role` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`Employee_ID`, `First Name`, `Last Name`, `Email`, `Password`, `Role`) VALUES
(1, 'Temidayo', 'Agbelusi', 'agbelusitemidayo@gmail.com', '12345', '0'),
(2, 'Temidayo', 'Agbelusi', 'temidudu2003@gmail.com', '00000', 'admin'),
(3, 'yemi', 'Agbelusi', 'temi20@gmail.com', '0000', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_email`, `message`, `timestamp`) VALUES
(24, 'temidudu2003@gmail.com', 'The resource \'Room 307 3rd floor\' is now available and has been booked for you from 2024-09-10 10:46:00 to 2024-09-10 10:48:00.', '2024-09-10 20:47:07'),
(25, 'agbelusitomisin@gmail.com', 'The resource \'Lab 2\' is now available and has been booked for you from  to .', '2024-09-10 20:55:09'),
(26, 'agbelusitomisi@gmail.com', 'The resource \'Lab 2\' is now available and has been booked for you from 2024-09-10 06:53:00 to 2024-09-10 06:57:00.', '2024-09-10 20:55:09'),
(27, 'temidudu2003@gmail.com', 'The resource \'Lab 2\' is now available and has been booked for you from 2024-09-10 10:51:00 to 2024-09-10 10:54:00.', '2024-09-10 20:55:09'),
(28, 'temidudu2003@gmail.com', 'Booking successful! The resource \'Room 307 3rd floor\' has been booked for 2024-09-10 11:00.', '2024-09-10 21:00:11'),
(29, 'agbelusitomisin@gmail.com', 'The resource \'Lab 2\' is now available and has been booked for you from  to .', '2024-09-12 09:54:27'),
(30, 'temidudu2003@gmail.com', 'The resource \'Lab 2\' is now available and has been booked for you from 2024-09-12 11:51:00 to 2024-09-12 23:51:00.', '2024-09-12 09:54:28'),
(31, 'temidudu2003@gmail.com', 'Booking successful! The resource \'Room 305 4th floor\' has been booked for 2024-09-12 11:55.', '2024-09-12 09:55:12'),
(32, 'temidudu2003@gmail.com', 'Your ticket has been resolved.', '2024-09-12 19:35:39'),
(33, 'agbelusitomisi@gmail.com', 'The resource \'Room 305 4th floor\' is now available and has been booked for you from  to .', '2024-09-12 22:51:03'),
(34, 'temidudu2003@gmail.com', 'The resource \'Room 307 3rd floor\' is now available and has been booked for you from 2024-09-12 04:58:00 to 2024-09-12 04:58:00.', '2024-09-12 22:51:07'),
(35, 'agbelusitomisin@gmail.com', 'The resource \'Lab 2\' is now available and has been booked for you from  to .', '2024-09-12 22:51:10');

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'room 305 4th floor', '2024-09-12 22:52:57', '2024-09-12 22:52:57');

-- --------------------------------------------------------

--
-- Table structure for table `school`
--

CREATE TABLE `school` (
  `Roll number` int(11) NOT NULL,
  `First Name` varchar(255) NOT NULL,
  `Last Name` varchar(255) NOT NULL,
  `Class` varchar(20) NOT NULL,
  `Email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `school`
--

INSERT INTO `school` (`Roll number`, `First Name`, `Last Name`, `Class`, `Email`) VALUES
(202111068, 'Temidayo', 'Agbelusi', 'year 3', 'temidudu2003@gmail.com'),
(202111069, 'Tomisin', 'Agbelusi', 'Year 3', 'Agbelusitomisin@gmail.com\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `support tickets`
--

CREATE TABLE `support tickets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('Open','Resolved') DEFAULT 'Open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `support tickets`
--

INSERT INTO `support tickets` (`id`, `email`, `subject`, `message`, `status`, `created_at`) VALUES
(1, 'agbelusitomisin@gmail.com', 'nn', 'n', 'Resolved', '2024-09-10 19:42:56'),
(2, 'temidudu2003@gmail.com', 'nn', 'm', 'Resolved', '2024-09-10 19:43:02'),
(3, 'temidudu2003@gmail.com', 'nn', 'n', 'Resolved', '2024-09-10 19:44:49'),
(4, 'temidudu2003@gmail.com', 'nn', 'n', 'Resolved', '2024-09-10 20:13:59'),
(5, 'temidudu2003@gmail.com', 'mm', 'mn', 'Resolved', '2024-09-12 14:56:12'),
(6, 'temidudu2003@gmail.com', 'nn', 'm', 'Resolved', '2024-09-12 18:30:31'),
(7, 'temidudu2003@gmail.com', 'nn', 'm', 'Resolved', '2024-09-12 19:03:53'),
(8, 'temidudu2003@gmail.com', 'nn', 'mm', 'Resolved', '2024-09-12 19:34:05'),
(9, 'temidudu2003@gmail.com', 'nn', 'nn', 'Resolved', '2024-09-12 19:35:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Roll Number` int(10) NOT NULL,
  `First Name` varchar(30) NOT NULL,
  `Last Name` varchar(30) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `DOB` date NOT NULL,
  `class` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT '''user''',
  `Password` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Roll Number`, `First Name`, `Last Name`, `Email`, `DOB`, `class`, `role`, `Password`) VALUES
(202111068, 'Temidayo', 'Agbelusi', 'temidudu2003@gmail.com', '2024-09-20', 'Year 3', 'user', '0000');

-- --------------------------------------------------------

--
-- Table structure for table `waitlist`
--

CREATE TABLE `waitlist` (
  `id` int(11) NOT NULL,
  `resource` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `signup_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `Begin time` datetime DEFAULT NULL,
  `End time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `waitlist`
--

INSERT INTO `waitlist` (`id`, `resource`, `email`, `signup_time`, `Begin time`, `End time`) VALUES
(6, 'Lab 2', 'agbelusitomisin@gmail.com', '2024-09-09 17:20:42', NULL, NULL),
(7, 'Room 305 4th floor', 'agbelusitomisi@gmail.com', '2024-09-09 20:22:56', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `unique_class` (`Course Name`,`Lecturer Name`,`Class Year`,`Resource`),
  ADD UNIQUE KEY `unique_class_details` (`Course Name`,`Lecturer Name`,`Class Year`,`Resource`),
  ADD UNIQUE KEY `unique_course_name` (`Course Name`),
  ADD UNIQUE KEY `unique_lecturer_name` (`Lecturer Name`),
  ADD UNIQUE KEY `unique_class_year` (`Class Year`),
  ADD UNIQUE KEY `unique_class_room` (`Resource`);

--
-- Indexes for table `contact us`
--
ALTER TABLE `contact us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`Employee_ID`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `school`
--
ALTER TABLE `school`
  ADD PRIMARY KEY (`Roll number`);

--
-- Indexes for table `support tickets`
--
ALTER TABLE `support tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Roll Number`);

--
-- Indexes for table `waitlist`
--
ALTER TABLE `waitlist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contact us`
--
ALTER TABLE `contact us`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `Employee_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `school`
--
ALTER TABLE `school`
  MODIFY `Roll number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202111070;

--
-- AUTO_INCREMENT for table `support tickets`
--
ALTER TABLE `support tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `Roll Number` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202111069;

--
-- AUTO_INCREMENT for table `waitlist`
--
ALTER TABLE `waitlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
