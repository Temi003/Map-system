-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 17, 2024 at 10:46 PM
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
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `Course Name` varchar(255) NOT NULL,
  `Lecturer Name` varchar(50) NOT NULL,
  `Class Year` varchar(20) NOT NULL,
  `Added At` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Resource` varchar(50) NOT NULL,
  `Start Time` time NOT NULL,
  `End Time` time NOT NULL,
  `ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `First Name` varchar(30) NOT NULL,
  `Last Name` varchar(30) NOT NULL,
  `Employee_ID` int(10) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `Password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`First Name`, `Last Name`, `Employee_ID`, `Email`, `Password`) VALUES
('Temidayo', 'Agbelusi', 4567, 'temidudu2003@gmail.com', '12345');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `First Name` varchar(30) NOT NULL,
  `Last Name` varchar(30) NOT NULL,
  `DOB` date NOT NULL,
  `Roll Number` int(10) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `Password` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`First Name`, `Last Name`, `DOB`, `Roll Number`, `Email`, `Password`) VALUES
('Temidayo', 'Agbelusi', '2024-08-28', 11, 'temidudu2003@gmail.com', '0000');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`Employee_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Roll Number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `Employee_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4568;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `Roll Number` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202111069;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
