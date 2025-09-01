-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 30, 2025 at 07:57 PM
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
-- Database: `srms_bishal_rohan`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_announcements`
--

CREATE TABLE `tbl_announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(90) NOT NULL,
  `announcement` longtext NOT NULL,
  `create_date` datetime NOT NULL,
  `level` int(11) NOT NULL COMMENT '0 = Teachers, 1 = Student, 2 = Both'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_announcements`
--

INSERT INTO `tbl_announcements` (`id`, `title`, `announcement`, `create_date`, `level`) VALUES
(3, 'Results are out now', '<p>Results are out now</p>', '2025-07-22 12:38:54', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_classes`
--

CREATE TABLE `tbl_classes` (
  `id` int(11) NOT NULL,
  `name` varchar(90) NOT NULL,
  `registration_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_classes`
--

INSERT INTO `tbl_classes` (`id`, `name`, `registration_date`) VALUES
(11, 'Twelve (Management)', '2025-07-22 07:56:42'),
(12, '	Eleven(Management)', '2025-08-06 17:34:19');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_exam_results`
--

CREATE TABLE `tbl_exam_results` (
  `id` int(11) NOT NULL,
  `student` varchar(20) NOT NULL,
  `class` int(11) NOT NULL,
  `subject_combination` int(11) NOT NULL,
  `term` int(11) NOT NULL,
  `score` double NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_exam_results`
--

INSERT INTO `tbl_exam_results` (`id`, `student`, `class`, `subject_combination`, `term`, `score`) VALUES
(188, '22061002', 12, 28, 1, 68),
(189, '22061002', 12, 28, 2, 66),
(313, '50230091', 11, 28, 1, 80),
(314, '50230091', 11, 28, 2, 60),
(315, '50230091', 11, 28, 3, 70),
(316, '50230111', 12, 30, 1, 0),
(317, '123456789', 11, 28, 1, 80),
(318, '123456789', 11, 28, 2, 60),
(319, '123456789', 11, 28, 3, 75),
(320, '22061024', 12, 30, 1, 77),
(321, '22061024', 12, 30, 2, 65),
(322, '22061024', 12, 30, 3, 80);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_grade_system`
--

CREATE TABLE `tbl_grade_system` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `min` double NOT NULL,
  `max` double NOT NULL,
  `remark` varchar(90) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_grade_system`
--

INSERT INTO `tbl_grade_system` (`id`, `name`, `min`, `max`, `remark`) VALUES
(2, 'A', 80, 89, 'Excellent'),
(3, 'B+', 70, 79, 'Very Good'),
(4, 'B', 60, 69, 'Good'),
(5, 'C+', 50, 59, 'Satisfactory'),
(7, 'C', 40, 49, 'Acceptable'),
(8, 'D', 30, 39, 'Partially Acceptable'),
(9, 'NG', 0, 29, 'Failed'),
(10, 'A+', 90, 100, 'Outstanding');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_login_sessions`
--

CREATE TABLE `tbl_login_sessions` (
  `session_key` varchar(90) NOT NULL,
  `staff` int(11) DEFAULT NULL,
  `student` varchar(20) DEFAULT NULL,
  `ip_address` varchar(90) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_login_sessions`
--

INSERT INTO `tbl_login_sessions` (`session_key`, `staff`, `student`, `ip_address`) VALUES
('106IMDMAGN2YJI83Y928', 32, NULL, '::1'),
('BYP6E33YNEMHA4GA3G8E', NULL, '22061024', '::1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_school`
--

CREATE TABLE `tbl_school` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `logo` varchar(50) NOT NULL,
  `result_system` int(11) NOT NULL COMMENT '0 = Average, 1 = Division',
  `allow_results` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_school`
--

INSERT INTO `tbl_school` (`id`, `name`, `logo`, `result_system`, `allow_results`) VALUES
(1, 'ACHS COLLEGE', 'school_logo1756570397.png', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_smtp`
--

CREATE TABLE `tbl_smtp` (
  `id` int(11) NOT NULL,
  `server` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `port` varchar(255) NOT NULL,
  `encryption` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_smtp`
--

INSERT INTO `tbl_smtp` (`id`, `server`, `username`, `password`, `port`, `encryption`, `status`) VALUES
(1, 'smtp.gmail.com', 'vriasuhn@gmail.com', 'smtp.gmail.com', '587', 'tls', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_staff`
--

CREATE TABLE `tbl_staff` (
  `id` int(11) NOT NULL,
  `fname` varchar(20) NOT NULL,
  `lname` varchar(20) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `email` varchar(90) NOT NULL,
  `password` varchar(90) NOT NULL,
  `level` int(11) NOT NULL COMMENT '0 = Admin, 1 = Academic, 2 = Teacher',
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0 = Blocked, 1 = Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_staff`
--

INSERT INTO `tbl_staff` (`id`, `fname`, `lname`, `gender`, `email`, `password`, `level`, `status`) VALUES
(3, 'Govinda', 'Gautam', 'Male', 'govinda@srms.test', '$2y$10$l8XYJDrBHTyeZkpupiRhwey6jJihzku0bYXiVtBM5kDRz3sZvSpgC', 2, 1),
(6, 'DENIS', 'MWAMBUNGU', 'Male', 'denis@srms.test', '$2y$10$l8XYJDrBHTyeZkpupiRhwey6jJihzku0bYXiVtBM5kDRz3sZvSpgC', 2, 1),
(10, 'FRANCIS', 'MASANJA', 'Male', 'francis@srms.test', '$2y$10$l8XYJDrBHTyeZkpupiRhwey6jJihzku0bYXiVtBM5kDRz3sZvSpgC', 2, 1),
(13, 'HANS', 'UISSO', 'Male', 'hans@srms.test', '$2y$10$l8XYJDrBHTyeZkpupiRhwey6jJihzku0bYXiVtBM5kDRz3sZvSpgC', 2, 1),
(14, 'HANSON', 'MAITA', 'Male', 'hanson@srms.test', '$2y$10$l8XYJDrBHTyeZkpupiRhwey6jJihzku0bYXiVtBM5kDRz3sZvSpgC', 2, 1),
(15, 'HENRY', 'GOWELLE', 'Male', 'henry@srms.test', '$2y$10$l8XYJDrBHTyeZkpupiRhwey6jJihzku0bYXiVtBM5kDRz3sZvSpgC', 2, 1),
(16, 'HILDA', 'KANDAUMA', 'Female', 'hilda@srms.test', '$2y$10$l8XYJDrBHTyeZkpupiRhwey6jJihzku0bYXiVtBM5kDRz3sZvSpgC', 2, 1),
(17, 'INNOCENT', 'MBAWALA', 'Male', 'innocent@srms.test', '$2y$10$l8XYJDrBHTyeZkpupiRhwey6jJihzku0bYXiVtBM5kDRz3sZvSpgC', 2, 1),
(18, 'JAMALI', 'NZOTA', 'Male', 'jamali@srms.test', '$2y$10$l8XYJDrBHTyeZkpupiRhwey6jJihzku0bYXiVtBM5kDRz3sZvSpgC', 2, 1),
(19, 'JAMIL', 'ABDALLAH', 'Male', 'jamil@srms.test', '$2y$10$l8XYJDrBHTyeZkpupiRhwey6jJihzku0bYXiVtBM5kDRz3sZvSpgC', 2, 1),
(20, 'JOAN', 'NKYA', 'Female', 'joan@srms.test', '$2y$10$l8XYJDrBHTyeZkpupiRhwey6jJihzku0bYXiVtBM5kDRz3sZvSpgC', 2, 1),
(21, 'JOSEPH', 'HAMISI', 'Male', 'joseph@srms.test', '$2y$10$l8XYJDrBHTyeZkpupiRhwey6jJihzku0bYXiVtBM5kDRz3sZvSpgC', 2, 1),
(26, 'Saugat', 'Thapa', 'Male', 'saugat@gmail.com', '$2y$10$ZNGU9aRLjyY5ZQKebFMGc.6qyoBLuOM0o4lLJR6Bc3haFhOrEpSL6', 2, 1),
(30, 'gokarna', 'chaudhary', 'Male', 'bca22061002_gokarna@achsnepal.edu.np', '$2y$10$h8Yc0PG/yofw8P3mJ6KcteP426cSfWSPk1WFw1BGOWr4O9yf9m6K.', 0, 1),
(32, 'ACHS', 'Organization', 'Male', 'test@gmail.com', '$2y$10$/xCONkg1oTHsiuMm8qOUrObymLCTdoGR.GTdOGORVOBn4GMM6upD2', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_students`
--

CREATE TABLE `tbl_students` (
  `id` varchar(20) NOT NULL,
  `fname` varchar(70) NOT NULL,
  `mname` varchar(70) NOT NULL,
  `lname` varchar(70) NOT NULL,
  `gender` varchar(7) NOT NULL,
  `email` varchar(90) NOT NULL,
  `class` int(11) NOT NULL,
  `password` varchar(90) NOT NULL,
  `level` int(11) NOT NULL DEFAULT 3 COMMENT '3 = student',
  `display_image` varchar(50) NOT NULL DEFAULT 'Blank',
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0 = Disabled, 1 = Enabled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_students`
--

INSERT INTO `tbl_students` (`id`, `fname`, `mname`, `lname`, `gender`, `email`, `class`, `password`, `level`, `display_image`, `status`) VALUES
('12345678', 'Jarbis', '', 'Singh', 'Male', 'jarbis@gmail.com', 11, '$2y$10$niJD1.msP8bY5tfmAHTtke/yjlM3tmv/YgRSSvk3VhRgSHfLaSOc2', 3, 'avator_1754469743.jpg', 1),
('123456789', 'Andy', '', 'Ruth', 'Male', 'andy28@gmail.com', 11, '$2y$10$82lNtM7.z3QC/eSO.nep9eSIXkpPkQuS7NywcJp9tfMtgJ3F/T3sy', 3, 'avator_1754469636.png', 1),
('22061002', 'Laila', '', 'Majhnu', 'Female', 'laila@gmail.com', 11, '$2y$10$.l5nQxbDz8CD.c9/9M0BgO1e9sSmNg14B0QRqEROeont8GD9e0toO', 3, 'avator_1754469849.png', 1),
('22061024', 'Thanos', '', 'Thanos', 'Male', 'thanos@gmail.com', 12, '$2y$10$7.Kb1xLcep3vZMA3BHe6eusZ2yTw6v.6ZVgyYMBNP28dxk66O..Iy', 3, 'avator_1756574032.jpg', 1),
('50230091', 'Aarav', '', 'Sharma', 'Male', 'aarav.sharma@demo.com', 11, '$2y$10$t5vUVW9tsDzbZlkrGJQyV.ve4zBusZkEB/om1eo5rIgmLEHv.O9ZW', 3, 'DEFAULT', 1),
('50230092', 'Aisha', '', 'Patel', 'Female', 'aisha.patel@demo.com', 11, '$2y$10$v7SUZCZr6rd.a86ktMgymeM9L27anCG1LRnbYh8n/eyZHXTbbAmDG', 3, 'DEFAULT', 1),
('50230093', 'Arjun', '', 'Kumar', 'Male', 'arjun.kumar@demo.com', 11, '$2y$10$sdzgSJkB.YhkM./b3G0Rau9bBWjn30i26tLzQFZp9Ap7ZnrPyN0ui', 3, 'DEFAULT', 1),
('50230094', 'Diya', '', 'Singh', 'Female', 'diya.singh@demo.com', 11, '$2y$10$CU1p46NqEMLVx5.cCjsD9u1ZvMNTNGyB.JuC3zO9fZtDmVvNEl7nu', 3, 'DEFAULT', 1),
('50230095', 'Esha', '', 'Verma', 'Female', 'esha.verma@demo.com', 11, '$2y$10$u0I4OP/Y6F1.0yEPqN8ZTepenNYQY7lHnXjSO20wJNyiyahpyGvne', 3, 'DEFAULT', 1),
('50230096', 'Ishaan', '', 'Gupta', 'Male', 'ishaan.gupta@demo.com', 11, '$2y$10$CS4.1FGOk7cXwvRToBYLquvOSIFCZ/j4MYgChUjllBiPHBsVWXzmS', 3, 'DEFAULT', 1),
('50230097', 'Kavya', '', 'Joshi', 'Female', 'kavya.joshi@demo.com', 11, '$2y$10$tUvxlHZnoC18.dJ/wC/LD.FbFg.jsjXBCexP5PrL6IIqSNbmGTe7i', 3, 'DEFAULT', 1),
('50230098', 'Lakshay', '', 'Malhotra', 'Male', 'lakshay.malhotra@demo.com', 11, '$2y$10$cUml/.EiVphS6d//rlmYre.8c60M1qBVXLa.E.IQeVCzkXk2UOrs6', 3, 'DEFAULT', 1),
('50230099', 'Mira', '', 'Chopra', 'Female', 'mira.chopra@demo.com', 11, '$2y$10$GT4kZ.l/H2Vilo.WQ/oIfe3HKt06BOkaO1AQsJLkuUbGJloJOVRWG', 3, 'DEFAULT', 1),
('50230100', 'Neel', '', 'Reddy', 'Male', 'neel.reddy@demo.com', 11, '$2y$10$0n3LRtzTQBY9xLVRHwfNoe/uqvlmhDJaEXYJx/dSImWzjtAcPkuP6', 3, 'DEFAULT', 1),
('50230101', 'Priya', '', 'Iyer', 'Female', 'priya.iyer@demo.com', 11, '$2y$10$JzGuucsTjcFxM8/HOYG.7OYBFm6sLO1pZTlFo.xG1d5xoD.9rNoyO', 3, 'DEFAULT', 1),
('50230102', 'Rohan', '', 'Mehta', 'Male', 'rohan.mehta@demo.com', 11, '$2y$10$J2.bdJ5NwIvauYN/NCCBDe2DiC9GkDndOn0ExCS4pQrjvtRgFvedm', 3, 'DEFAULT', 1),
('50230103', 'Saanvi', '', 'Kapoor', 'Female', 'saanvi.kapoor@demo.com', 11, '$2y$10$Txh9ih.OX4J6NYniOjVbCONt1CIwYwmL7GA09VSEXiH8iSn9WOvym', 3, 'DEFAULT', 1),
('50230104', 'Tanish', '', 'Bhatt', 'Male', 'tanish.bhatt@demo.com', 11, '$2y$10$6UN3VunEMSbKVq9jBmS4HOP1f4wqfpoE49vNfePhtVrO3aoB8GCri', 3, 'DEFAULT', 1),
('50230105', 'Vanya', '', 'Saxena', 'Female', 'vanya.saxena@demo.com', 11, '$2y$10$dLLgzM8zjXQvJ24Rh.LVI.ZRDL3Db3L8Sry5WWPc8kYs2Y8PDeu6S', 3, 'DEFAULT', 1),
('50230111', 'Aditya', '', 'Rajput', 'Male', 'aditya.rajput@demo.com', 12, '$2y$10$t5vUVW9tsDzbZlkrGJQyV.ve4zBusZkEB/om1eo5rIgmLEHv.O9ZW', 3, 'DEFAULT', 1),
('50230112', 'Ananya', '', 'Desai', 'Female', 'ananya.desai@demo.com', 12, '$2y$10$v7SUZCZr6rd.a86ktMgymeM9L27anCG1LRnbYh8n/eyZHXTbbAmDG', 3, 'DEFAULT', 1),
('50230113', 'Dhruv', '', 'Tiwari', 'Male', 'dhruv.tiwari@demo.com', 12, '$2y$10$sdzgSJkB.YhkM./b3G0Rau9bBWjn30i26tLzQFZp9Ap7ZnrPyN0ui', 3, 'DEFAULT', 1),
('50230114', 'Ira', '', 'Nair', 'Female', 'ira.nair@demo.com', 12, '$2y$10$CU1p46NqEMLVx5.cCjsD9u1ZvMNTNGyB.JuC3zO9fZtDmVvNEl7nu', 3, 'DEFAULT', 1),
('50230115', 'Kabir', '', 'Chauhan', 'Male', 'kabir.chauhan@demo.com', 12, '$2y$10$u0I4OP/Y6F1.0yEPqN8ZTepenNYQY7lHnXjSO20wJNyiyahpyGvne', 3, 'DEFAULT', 1),
('50230116', 'Kiara', '', 'Mishra', 'Female', 'kiara.mishra@demo.com', 12, '$2y$10$CS4.1FGOk7cXwvRToBYLquvOSIFCZ/j4MYgChUjllBiPHBsVWXzmS', 3, 'DEFAULT', 1),
('50230117', 'Laksh', '', 'Yadav', 'Male', 'laksh.yadav@demo.com', 12, '$2y$10$tUvxlHZnoC18.dJ/wC/LD.FbFg.jsjXBCexP5PrL6IIqSNbmGTe7i', 3, 'DEFAULT', 1),
('50230118', 'Maya', '', 'Pandey', 'Female', 'maya.pandey@demo.com', 12, '$2y$10$cUml/.EiVphS6d//rlmYre.8c60M1qBVXLa.E.IQeVCzkXk2UOrs6', 3, 'DEFAULT', 1),
('50230119', 'Nikhil', '', 'Sinha', 'Male', 'nikhil.sinha@demo.com', 12, '$2y$10$GT4kZ.l/H2Vilo.WQ/oIfe3HKt06BOkaO1AQsJLkuUbGJloJOVRWG', 3, 'DEFAULT', 1),
('50230120', 'Pari', '', 'Trivedi', 'Female', 'pari.trivedi@demo.com', 12, '$2y$10$0n3LRtzTQBY9xLVRHwfNoe/uqvlmhDJaEXYJx/dSImWzjtAcPkuP6', 3, 'DEFAULT', 1),
('50230121', 'Rudra', '', 'Verma', 'Male', 'rudra.verma@demo.com', 12, '$2y$10$JzGuucsTjcFxM8/HOYG.7OYBFm6sLO1pZTlFo.xG1d5xoD.9rNoyO', 3, 'DEFAULT', 1),
('50230122', 'Sia', '', 'Kaur', 'Female', 'sia.kaur@demo.com', 12, '$2y$10$J2.bdJ5NwIvauYN/NCCBDe2DiC9GkDndOn0ExCS4pQrjvtRgFvedm', 3, 'DEFAULT', 1),
('50230123', 'Ved', '', 'Shah', 'Male', 'ved.shah@demo.com', 12, '$2y$10$Txh9ih.OX4J6NYniOjVbCONt1CIwYwmL7GA09VSEXiH8iSn9WOvym', 3, 'DEFAULT', 1),
('50230124', 'Zara', '', 'Khan', 'Female', 'zara.khan@demo.com', 12, '$2y$10$6UN3VunEMSbKVq9jBmS4HOP1f4wqfpoE49vNfePhtVrO3aoB8GCri', 3, 'DEFAULT', 1),
('50230125', 'Aryan', '', 'Chopra', 'Male', 'aryan.chopra@demo.com', 12, '$2y$10$dLLgzM8zjXQvJ24Rh.LVI.ZRDL3Db3L8Sry5WWPc8kYs2Y8PDeu6S', 3, 'DEFAULT', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_subjects`
--

CREATE TABLE `tbl_subjects` (
  `id` int(11) NOT NULL,
  `name` varchar(90) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_subjects`
--

INSERT INTO `tbl_subjects` (`id`, `name`) VALUES
(17, 'Nepali'),
(18, 'Mathematics'),
(19, 'Accounting'),
(20, 'Economics'),
(21, 'Computer Sciences'),
(22, 'English-II'),
(23, 'Nepali-II'),
(24, 'Mathematics-II'),
(25, 'Accounting-II'),
(26, 'Economics-II'),
(27, 'Computer Science-II');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_subject_combinations`
--

CREATE TABLE `tbl_subject_combinations` (
  `id` int(11) NOT NULL,
  `class` varchar(100) NOT NULL,
  `subject` int(11) NOT NULL,
  `teacher` int(11) NOT NULL,
  `reg_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_subject_combinations`
--

INSERT INTO `tbl_subject_combinations` (`id`, `class`, `subject`, `teacher`, `reg_date`) VALUES
(28, 'a:1:{i:0;s:2:\"11\";}', 27, 26, '2025-07-23 10:01:02'),
(30, 'a:1:{i:0;s:2:\"12\";}', 19, 6, '2025-08-06 18:19:52');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_terms`
--

CREATE TABLE `tbl_terms` (
  `id` int(11) NOT NULL,
  `name` varchar(90) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '	0 = Disabled , 1 = Enabled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_terms`
--

INSERT INTO `tbl_terms` (`id`, `name`, `status`) VALUES
(1, 'First Term March 2025', 1),
(2, 'Second Terminal June 2024', 1),
(3, 'Third Terminal September 2024', 1),
(6, 'Final', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_announcements`
--
ALTER TABLE `tbl_announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_classes`
--
ALTER TABLE `tbl_classes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_exam_results`
--
ALTER TABLE `tbl_exam_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student` (`student`),
  ADD KEY `class` (`class`),
  ADD KEY `subject_combination` (`subject_combination`),
  ADD KEY `term` (`term`);

--
-- Indexes for table `tbl_grade_system`
--
ALTER TABLE `tbl_grade_system`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_login_sessions`
--
ALTER TABLE `tbl_login_sessions`
  ADD PRIMARY KEY (`session_key`),
  ADD KEY `staff` (`staff`),
  ADD KEY `student` (`student`);

--
-- Indexes for table `tbl_school`
--
ALTER TABLE `tbl_school`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_smtp`
--
ALTER TABLE `tbl_smtp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_staff`
--
ALTER TABLE `tbl_staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_students`
--
ALTER TABLE `tbl_students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class` (`class`);

--
-- Indexes for table `tbl_subjects`
--
ALTER TABLE `tbl_subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_subject_combinations`
--
ALTER TABLE `tbl_subject_combinations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class` (`class`),
  ADD KEY `teacher` (`teacher`),
  ADD KEY `subject` (`subject`);

--
-- Indexes for table `tbl_terms`
--
ALTER TABLE `tbl_terms`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_announcements`
--
ALTER TABLE `tbl_announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_classes`
--
ALTER TABLE `tbl_classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_exam_results`
--
ALTER TABLE `tbl_exam_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=323;

--
-- AUTO_INCREMENT for table `tbl_grade_system`
--
ALTER TABLE `tbl_grade_system`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_school`
--
ALTER TABLE `tbl_school`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_smtp`
--
ALTER TABLE `tbl_smtp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_staff`
--
ALTER TABLE `tbl_staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tbl_subjects`
--
ALTER TABLE `tbl_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tbl_subject_combinations`
--
ALTER TABLE `tbl_subject_combinations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tbl_terms`
--
ALTER TABLE `tbl_terms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_exam_results`
--
ALTER TABLE `tbl_exam_results`
  ADD CONSTRAINT `tbl_exam_results_ibfk_1` FOREIGN KEY (`student`) REFERENCES `tbl_students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_exam_results_ibfk_2` FOREIGN KEY (`class`) REFERENCES `tbl_classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_exam_results_ibfk_3` FOREIGN KEY (`subject_combination`) REFERENCES `tbl_subject_combinations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_exam_results_ibfk_4` FOREIGN KEY (`term`) REFERENCES `tbl_terms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_login_sessions`
--
ALTER TABLE `tbl_login_sessions`
  ADD CONSTRAINT `tbl_login_sessions_ibfk_1` FOREIGN KEY (`staff`) REFERENCES `tbl_staff` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_login_sessions_ibfk_2` FOREIGN KEY (`student`) REFERENCES `tbl_students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_students`
--
ALTER TABLE `tbl_students`
  ADD CONSTRAINT `tbl_students_ibfk_1` FOREIGN KEY (`class`) REFERENCES `tbl_classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_subject_combinations`
--
ALTER TABLE `tbl_subject_combinations`
  ADD CONSTRAINT `tbl_subject_combinations_ibfk_2` FOREIGN KEY (`subject`) REFERENCES `tbl_subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_subject_combinations_ibfk_3` FOREIGN KEY (`teacher`) REFERENCES `tbl_staff` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
