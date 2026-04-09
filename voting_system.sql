-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2025 at 04:58 PM
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
-- Database: `voting_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_name`, `password`) VALUES
('Akshay', '222222'),
('Jotiba', '111111'),
('Prasad', '333333');

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

CREATE TABLE `candidates` (
  `student_id` varchar(20) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `semester` varchar(200) NOT NULL,
  `division` varchar(200) NOT NULL,
  `votes` int(11) DEFAULT 0,
  `password` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `candidates`
--

INSERT INTO `candidates` (`student_id`, `name`, `position`, `semester`, `division`, `votes`, `password`, `image_path`) VALUES
('U15BL22S0066', 'ROHANT PATIL', 'CR', 'THIRD SEM', 'A Div', 0, '$2y$10$xDmfODDKDHJ706O1PnF72O7MOTq0GLmgAuHexSx3/EpgvPVj25ocW', 'uploads/6832d40714c97.jpeg'),
('U15BL22S0136', 'ISHANI PATIL', 'CR', 'THIRD SEM', 'A Div', 0, '$2y$10$obErxeXEBIBKzZckkTdlq.obFMu92hXwXgjk6Dz2/MS3oL3r8StWC', 'uploads/6832d9df14827.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `voters`
--

CREATE TABLE `voters` (
  `student_id` varchar(20) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `approved` tinytext DEFAULT '0',
  `voted_for` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voters`
--

INSERT INTO `voters` (`student_id`, `name`, `password`, `approved`, `voted_for`) VALUES
('U15BL22S001', 'ABHISHEK', 'abhishek', '1', NULL),
('U15BL22S002', 'PUNIT', 'punit', '1', NULL),
('U15BL22S003', 'PRAKASH', 'prakash', '1', NULL),
('U15BL22S004', 'ROHANT', 'rohant', '1', NULL),
('U15BL22S005', 'SUMIT', 'sumit', '1', NULL),
('U15BL22S006', 'OMKAR', 'omkar', '1', NULL),
('U15BL22S007', 'SAMEER', 'sameer', '1', NULL),
('U15BL22S008', 'samarth', '123456', '1', NULL),
('U15BL22S009', 'ketan', 'ketan123', '1', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `student_id` varchar(20) NOT NULL,
  `candidate_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`student_id`, `candidate_id`) VALUES
('U15BL22S001', 'U15BL22S0136'),
('U15BL22S0016', 'U15BL23S0168'),
('U15BL22S0018', 'U15BL22S0185'),
('U15BL22S0019', 'U15BL23S0151'),
('U15BL22S002', 'U15BL22S0011'),
('U15BL22S0021', 'U15BL22S0019'),
('U15BL22S003', 'U15BL22S0136'),
('U15BL22S004', 'U15BL22S0168'),
('U15BL22S0047', 'U15BL22S0112'),
('U15BL22S005', 'U15BL22S0136'),
('U15BL22S0058', 'U15BL23S0150'),
('U15BL22S0059', 'U15BL23S0150'),
('U15BL22S006', 'U15BL22S0136'),
('U15BL22S007', 'U15BL22S0012'),
('U15BL22S008', 'U15BL22S0136'),
('U15BL22S0112', 'U15BL22S0113'),
('U15BL22S0115', 'U15BL22S0047'),
('U15BL22S0124', 'U15BL22S0047'),
('U15BL22S0129', 'U15BL22S0031'),
('U15BL22S0143', 'U15BL22S0047'),
('U15BL22S0185', 'U15BL22S0110'),
('U15BL23S0168', 'U15BL22S0047');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_name`);

--
-- Indexes for table `candidates`
--
ALTER TABLE `candidates`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `voters`
--
ALTER TABLE `voters`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`student_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
