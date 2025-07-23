-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 23, 2025 at 07:57 AM
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
(10, 'Eleven (Management)', '2025-07-22 07:56:22'),
(11, 'Twelve (Management)', '2025-07-22 07:56:42');

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
(70, '50230002', 10, 21, 1, 80),
(71, '50230002', 10, 22, 1, 90),
(72, '50230002', 10, 23, 1, 82),
(73, '50230002', 10, 24, 1, 78),
(74, '50230002', 10, 25, 1, 100),
(75, '', 10, 21, 1, 100),
(76, '', 10, 22, 1, 100),
(77, '', 10, 23, 1, 100),
(78, '', 10, 24, 1, 100),
(79, '', 10, 25, 1, 100),
(80, '', 10, 26, 1, 100),
(81, '50230002', 10, 26, 1, 40),
(82, '50230003', 10, 21, 1, 50),
(83, '50230003', 10, 22, 1, 60),
(84, '50230003', 10, 23, 1, 50),
(85, '50230003', 10, 24, 1, 60),
(86, '50230003', 10, 25, 1, 70),
(87, '50230003', 10, 26, 1, 50),
(88, '50230004', 10, 21, 1, 50),
(89, '50230004', 10, 22, 1, 40),
(90, '50230004', 10, 23, 1, 60),
(91, '50230004', 10, 24, 1, 50),
(92, '50230004', 10, 25, 1, 60),
(93, '50230004', 10, 26, 1, 50),
(94, '50230005', 10, 21, 1, 50),
(95, '50230005', 10, 22, 1, 50),
(96, '50230005', 10, 23, 1, 60),
(97, '50230005', 10, 24, 1, 40),
(98, '50230005', 10, 25, 1, 50),
(99, '50230005', 10, 26, 1, 60),
(100, '50230006', 10, 21, 1, 80),
(101, '50230006', 10, 22, 1, 60),
(102, '50230006', 10, 23, 1, 50),
(103, '50230006', 10, 24, 1, 70),
(104, '50230006', 10, 25, 1, 60),
(105, '50230006', 10, 26, 1, 60),
(106, '50230007', 10, 21, 1, 70),
(107, '50230007', 10, 22, 1, 40),
(108, '50230007', 10, 23, 1, 50),
(109, '50230007', 10, 24, 1, 70),
(110, '50230007', 10, 25, 1, 60),
(111, '50230007', 10, 26, 1, 50),
(112, '50230008', 10, 21, 1, 50),
(113, '50230008', 10, 22, 1, 60),
(114, '50230008', 10, 23, 1, 50),
(115, '50230008', 10, 24, 1, 60),
(116, '50230008', 10, 25, 1, 50),
(117, '50230008', 10, 26, 1, 45),
(118, '50230009', 10, 21, 1, 80),
(119, '50230009', 10, 22, 1, 70),
(120, '50230009', 10, 23, 1, 75),
(121, '50230009', 10, 24, 1, 65),
(122, '50230009', 10, 25, 1, 78),
(123, '50230009', 10, 26, 1, 60),
(124, '50230010', 10, 21, 1, 45),
(125, '50230010', 10, 22, 1, 50),
(126, '50230010', 10, 23, 1, 40),
(127, '50230010', 10, 24, 1, 40),
(128, '50230010', 10, 25, 1, 55),
(129, '50230010', 10, 26, 1, 40),
(130, '50230011', 10, 21, 1, 60),
(131, '50230011', 10, 22, 1, 70),
(132, '50230011', 10, 23, 1, 50),
(133, '50230011', 10, 24, 1, 50),
(134, '50230011', 10, 25, 1, 60),
(135, '50230011', 10, 26, 1, 60);

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
(1, 'A+', 90, 100, 'Outstanding'),
(2, 'A', 80, 89, 'Excellent'),
(3, 'B+', 70, 79, 'Very Good'),
(4, 'B', 60, 69, 'Good'),
(5, 'C+', 50, 59, 'Satisfactory'),
(7, 'C', 40, 49, 'Acceptable'),
(8, 'D', 30, 39, 'Partially Acceptable'),
(9, 'NG', 0, 29, 'Failed');

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
('2LF0ECKCTCTLHH7EMZBW', 28, NULL, '::1'),
('NO8LGQTMMTTY58B8OQH7', 26, NULL, '::1');

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
(1, 'ACHS COLLEGE', 'school_logo1753183526.png', 1, 1);

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
(1, 'Bwire', 'Mashauri', 'Male', 'bishal@gmail.com', '$2y$10$l8XYJDrBHTyeZkpupiRhwey6jJihzku0bYXiVtBM5kDRz3sZvSpgC', 0, 1),
(3, 'Govinda', 'Gautam', 'Male', 'govinda@srms.test', '$2y$10$l8XYJDrBHTyeZkpupiRhwey6jJihzku0bYXiVtBM5kDRz3sZvSpgC', 2, 1),
(4, 'COLLINS', 'MPAGAMA', 'Male', 'collins@srms.test', '$2y$10$l8XYJDrBHTyeZkpupiRhwey6jJihzku0bYXiVtBM5kDRz3sZvSpgC', 2, 1),
(5, 'DAVID', 'OMAO', 'Male', 'david@srms.test', '$2y$10$l8XYJDrBHTyeZkpupiRhwey6jJihzku0bYXiVtBM5kDRz3sZvSpgC', 2, 1),
(6, 'DENIS', 'MWAMBUNGU', 'Male', 'denis@srms.test', '$2y$10$l8XYJDrBHTyeZkpupiRhwey6jJihzku0bYXiVtBM5kDRz3sZvSpgC', 2, 1),
(7, 'ERICK', 'LUOGA', 'Male', 'erick@srms.test', '$2y$10$l8XYJDrBHTyeZkpupiRhwey6jJihzku0bYXiVtBM5kDRz3sZvSpgC', 2, 1),
(8, 'FARAJI', 'FARAJI', 'Male', 'faraji@srms.test', '$2y$10$l8XYJDrBHTyeZkpupiRhwey6jJihzku0bYXiVtBM5kDRz3sZvSpgC', 2, 1),
(9, 'FATMA', 'BAHADAD', 'Female', 'fatma@srms.test', '$2y$10$l8XYJDrBHTyeZkpupiRhwey6jJihzku0bYXiVtBM5kDRz3sZvSpgC', 2, 1),
(10, 'FRANCIS', 'MASANJA', 'Male', 'francis@srms.test', '$2y$10$l8XYJDrBHTyeZkpupiRhwey6jJihzku0bYXiVtBM5kDRz3sZvSpgC', 2, 1),
(11, 'GLADNESS ', 'PHILIPO', 'Female', 'gladness@srms.test', '$2y$10$l8XYJDrBHTyeZkpupiRhwey6jJihzku0bYXiVtBM5kDRz3sZvSpgC', 2, 1),
(12, 'GRATION', 'GRATION', 'Male', 'gration@srms.test', '$2y$10$l8XYJDrBHTyeZkpupiRhwey6jJihzku0bYXiVtBM5kDRz3sZvSpgC', 2, 1),
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
(27, 'Rohan', 'Shreshta', '', 'rohan@gmail.com', 'Rohan@123', 0, 1),
(28, 'Rohan', 'Shrestha', 'Male', 'rohansth@gmail.com', '$2y$10$POFRpSvN/5Irib5UErNTzu/eBXfZZ5JmOuSRM6BP5DoiHTg20op66', 1, 1);

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
('', 'Rohan', 'Kumar', 'Shrestha', 'Male', 'rohan2@gmail.com', 10, 'Rohan@123', 3, 'Blank', 1),
('50230002', 'Alisha', '', 'Maharjan', 'Female', 'alisha.maharjan@demo.com', 10, '$2y$10$t5vUVW9tsDzbZlkrGJQyV.ve4zBusZkEB/om1eo5rIgmLEHv.O9ZW', 3, 'DEFAULT', 1),
('50230003', 'Anit', '', 'Sigdel', 'Female', 'anit.sigdel@demo.com', 10, '$2y$10$v7SUZCZr6rd.a86ktMgymeM9L27anCG1LRnbYh8n/eyZHXTbbAmDG', 3, 'DEFAULT', 1),
('50230004', 'Bijina', '', 'Tandukar', 'Male', 'bijina.tandukar@demo.com', 10, '$2y$10$sdzgSJkB.YhkM./b3G0Rau9bBWjn30i26tLzQFZp9Ap7ZnrPyN0ui', 3, 'DEFAULT', 1),
('50230005', 'Carlos', 'Tamang', 'Lama', 'Female', 'carlos.lama@demo.com', 10, '$2y$10$CU1p46NqEMLVx5.cCjsD9u1ZvMNTNGyB.JuC3zO9fZtDmVvNEl7nu', 3, 'DEFAULT', 1),
('50230006', 'Creation', '', 'Ghalan', 'Female', 'creation.ghalan@demo.com', 10, '$2y$10$u0I4OP/Y6F1.0yEPqN8ZTepenNYQY7lHnXjSO20wJNyiyahpyGvne', 3, 'DEFAULT', 1),
('50230007', 'Diben', '', 'Maharjan', 'Female', 'diben.maharjan@demo.com', 10, '$2y$10$CS4.1FGOk7cXwvRToBYLquvOSIFCZ/j4MYgChUjllBiPHBsVWXzmS', 3, 'DEFAULT', 1),
('50230008', 'Gaurab', '', 'Maharjan', 'Female', 'gaurab.maharjan@demo.com', 10, '$2y$10$tUvxlHZnoC18.dJ/wC/LD.FbFg.jsjXBCexP5PrL6IIqSNbmGTe7i', 3, 'DEFAULT', 1),
('50230009', 'Jenisha', '', 'Shrestha', 'Male', 'jenisha.shrestha@demo.com', 10, '$2y$10$cUml/.EiVphS6d//rlmYre.8c60M1qBVXLa.E.IQeVCzkXk2UOrs6', 3, 'DEFAULT', 1),
('50230010', 'Julian', '', 'Maharjan', 'Male', 'julian.maharjan@demo.com', 10, '$2y$10$GT4kZ.l/H2Vilo.WQ/oIfe3HKt06BOkaO1AQsJLkuUbGJloJOVRWG', 3, 'DEFAULT', 1),
('50230011', 'Kalasha', '', 'Maharjan', 'Male', 'kalasha.maharjan@demo.com', 10, '$2y$10$0n3LRtzTQBY9xLVRHwfNoe/uqvlmhDJaEXYJx/dSImWzjtAcPkuP6', 3, 'DEFAULT', 1),
('50230012', 'Krisha', '', 'Khatiwada', 'Male', 'krisha.khatiwada@demo.com', 10, '$2y$10$JzGuucsTjcFxM8/HOYG.7OYBFm6sLO1pZTlFo.xG1d5xoD.9rNoyO', 3, 'DEFAULT', 1),
('50230013', 'Lasta', '', 'Tuladhar', 'Male', 'lasta.tuladhar@demo.com', 10, '$2y$10$J2.bdJ5NwIvauYN/NCCBDe2DiC9GkDndOn0ExCS4pQrjvtRgFvedm', 3, 'DEFAULT', 1),
('50230014', 'Lemek', '', 'Maharjan', 'Female', 'lemek.maharjan@demo.com', 10, '$2y$10$Txh9ih.OX4J6NYniOjVbCONt1CIwYwmL7GA09VSEXiH8iSn9WOvym', 3, 'DEFAULT', 1),
('50230015', 'Maitri', 'Ratna', 'Bajracharya', 'Male', 'maitri.bajracharya@demo.com', 10, '$2y$10$6UN3VunEMSbKVq9jBmS4HOP1f4wqfpoE49vNfePhtVrO3aoB8GCri', 3, 'DEFAULT', 1),
('50230016', 'Manish', '', 'Gautam', 'Female', 'manish.gautam@demo.com', 10, '$2y$10$dLLgzM8zjXQvJ24Rh.LVI.ZRDL3Db3L8Sry5WWPc8kYs2Y8PDeu6S', 3, 'DEFAULT', 1),
('50230017', 'Manthan', '', 'Maharjan', 'Male', 'manthan.maharjan@demo.com', 10, '$2y$10$StghZASDkAwCFc/nnIvXje.VRHt19qgBFZRP5DFFstMhYLkBz3iLS', 3, 'DEFAULT', 1),
('50230018', 'Maikal', '', 'Garbuja', 'Male', 'maikal.garbuja@demo.com', 10, '$2y$10$XzokZh3tFOtAb5/jRboZNeBp1hlD6UYOU/Y29.ul53uQTrQBDkWGC', 3, 'DEFAULT', 1),
('50230019', 'Muskan', '', 'Malakar', 'Female', 'muskan.malakar@demo.com', 10, '$2y$10$ooud2iwXWqwZE6X1MPDl8ewmwWeofSlqMPChITHvi5zDKQGkXiLYe', 3, 'DEFAULT', 1),
('50230020', 'Niraj', '', 'Kumar', 'Male', 'niraj.kumar@demo.com', 10, '$2y$10$oTuE2wkMMRY8NjYGaai6cuqkuyYwCXa/jvZKFMYX0LzaJFaPHT6dW', 3, 'DEFAULT', 1),
('50230021', 'Palistha', '', 'Nakarmi', 'Female', 'palistha.nakarmi@demo.com', 10, '$2y$10$N9FPgzwSmt1pSp/BEsykW.c/ORvq7wyq14D/WCmwDrIC8z4APFriu', 3, 'DEFAULT', 1),
('50230022', 'Palpasa', 'Thapa', 'Magar', 'Male', 'palpasa.magar@demo.com', 10, '$2y$10$Emt3t6ypjNbKobbT3YLdRePDDWVuRYC4y9MCqGCeKzFCKb3F6fT3W', 3, 'DEFAULT', 1),
('50230023', 'Prachita', 'Thapa', 'Magar', 'Male', 'prachita.magar@demo.com', 10, '$2y$10$HGb2Jv3cva5jiOv9IUa9/OaKj53XhWNJyT4.liiBP61YPiXnITQgS', 3, 'DEFAULT', 1),
('50230024', 'Pratik', '', 'Chaudhary', 'Female', 'pratik.chaudhary@demo.com', 10, '$2y$10$sQhhKIfOsrUSjtSbZ01tFOSsbN32naeafy2zteUZ2uWMcH.f2eUDi', 3, 'DEFAULT', 1),
('50230025', 'Prinsha', '', 'Karki', 'Male', 'prinsha.karki@demo.com', 10, '$2y$10$SywXelO9MZcaC8.QLtGZuuOCmRzk8LElcFmJ.9Ed9dVNS51K6BgF.', 3, 'DEFAULT', 1),
('50230026', 'Rakesh', '', 'Maharjan', 'Female', 'rakesh.maharjan@demo.com', 10, '$2y$10$1.VwRIQAYzz.RIsGiXCiteUxe4eXNn9Fz/tGwvG2XYJSK8IkVjCSu', 3, 'DEFAULT', 1),
('50230027', 'Rashik', '', 'Maharjan', 'Male', 'rashik.maharjan@demo.com', 10, '$2y$10$ijkDdwHOK6uJLEZ5snDpHu89o1RwTFZXYgnEEhJKPehB/7Vwm6EcC', 3, 'DEFAULT', 1),
('50230028', 'Robin', '', 'KC', 'Female', 'robin.kc@demo.com', 10, '$2y$10$vgdA2zzvGzOXvRjQRh1dxO7TBx3JYLoHSK9N97qjtx.jegaOtloam', 3, 'DEFAULT', 1),
('50230029', 'Roxan', '', 'Maharjan', 'Female', 'roxan.maharjan@demo.com', 10, '$2y$10$uS1ETYUlje5BLS0ejdOcp.RnCHB4aMHzVCH97sxCgkkXB.jYbmHwi', 3, 'DEFAULT', 1),
('50230030', 'Sakshyam', '', 'Basnet', 'Male', 'sakshyam.basnet@demo.com', 10, '$2y$10$azwK3xrmkBD2sl6eN9JwQuyXR0Qbh6X5Y9rFJWLmN840hD0tL9EnO', 3, 'DEFAULT', 1),
('50230031', 'Samayan', '', 'Pariyar', 'Female', 'samayan.pariyar@demo.com', 10, '$2y$10$fWPB.1bKNbE7a2Oqwb1YO.ENvZKhUwa.Hswq7x5rn77G74mIAVpPK', 3, 'DEFAULT', 1),
('50230032', 'Saugat', '', 'Thapa', 'Female', 'saugat.thapa@demo.com', 10, '$2y$10$.80fBg/OUHErec1WPMgKBOmjwDxerlGkcYWYcIJScETT2FPmyKJri', 3, 'DEFAULT', 1),
('50230033', 'Sohan', '', 'Tamang', 'Female', 'sohan.tamang@demo.com', 10, '$2y$10$zyN6e89sRz8Ch1KGdwOEjOQYX4fP.pL7Hzu3GeE7/LKZKZog9pnmC', 3, 'DEFAULT', 1),
('50230034', 'Sonish', '', 'Maharjan', 'Female', 'sonish.maharjan@demo.com', 10, '$2y$10$JzjGqm5qnRU6Tc0EZXC1TeQsVzDQdHURAjSlNiFak9/LHSLZ8p1te', 3, 'DEFAULT', 1),
('50230035', 'Subha', '', 'Maharjan', 'Female', 'subha.maharjan@demo.com', 10, '$2y$10$tF8I07CzUebaWO1f7eiSReOEfs2LrSxrO3I0laPz9tWYRyC.oqvbm', 3, 'DEFAULT', 1),
('50230036', 'Subham', '', 'Maharjan', 'Female', 'subham.maharjan@demo.com', 10, '$2y$10$4P5c.6kVlRRuy2N8.Q6tpOW1RtsX8LwpUZzKF8IBJH47XeSlqzgWC', 3, 'DEFAULT', 1),
('50230037', 'Subham', '', 'Shrestha', 'Male', 'subham.shrestha@demo.com', 10, '$2y$10$Wgo0FhBWJIQpK36u2vqDjOrezdZnymiNDijEpdhScW/GabUJceOGG', 3, 'DEFAULT', 1),
('50230038', 'Suhan', '', 'Budhathoki', 'Male', 'suhan.budhathoki@demo.com', 10, '$2y$10$A2aJMjkiN0oR9n2k1hej1O4cF98rDB53.i1pqrDick184mY/a9UpC', 3, 'DEFAULT', 1),
('50230039', 'Sujal', '', 'Maharjan', 'Female', 'sujal.maharjan@demo.com', 10, '$2y$10$HFTcj36Ccr4jkc4ZCPReI.XIs4N4Nz89vKPpjNJONFB9D/fo/dRVa', 3, 'DEFAULT', 1),
('50230040', 'Swastika', '', 'Maharjan', 'Female', 'swastika.maharjan@demo.com', 10, '$2y$10$7N6glvrIL4YclGBEcj0CB.XfpqFrhJpUA55DqhvKEKZHRmD5WMs/e', 3, 'DEFAULT', 1),
('50230041', 'Ujeni', '', 'Shrestha', 'Male', 'ujeni.shrestha@demo.com', 10, '$2y$10$rWgfz7vT6F7KvBzatPmEOuAQockLM9ZpYUdUq69ET/WTEYSoM5YAC', 3, 'DEFAULT', 1);

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
(16, 'English'),
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
(21, 'a:1:{i:0;s:2:\"10\";}', 19, 26, '2025-07-22 18:43:29'),
(22, 'a:1:{i:0;s:2:\"10\";}', 21, 3, '2025-07-22 18:43:49'),
(23, 'a:1:{i:0;s:2:\"10\";}', 20, 4, '2025-07-22 18:44:04'),
(24, 'a:1:{i:0;s:2:\"10\";}', 16, 5, '2025-07-22 18:44:16'),
(25, 'a:1:{i:0;s:2:\"10\";}', 18, 21, '2025-07-22 18:44:30'),
(26, 'a:1:{i:0;s:2:\"10\";}', 17, 5, '2025-07-22 18:56:52'),
(27, 'a:1:{i:0;s:2:\"11\";}', 25, 26, '2025-07-23 10:00:39'),
(28, 'a:1:{i:0;s:2:\"11\";}', 27, 6, '2025-07-23 10:01:02');

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
(1, 'First Term March 2024', 1),
(2, 'Second Terminal June 2024', 1),
(3, 'Pre Boards September 2024', 1),
(4, 'Annual November 2024', 1);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_exam_results`
--
ALTER TABLE `tbl_exam_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `tbl_grade_system`
--
ALTER TABLE `tbl_grade_system`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tbl_subjects`
--
ALTER TABLE `tbl_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tbl_subject_combinations`
--
ALTER TABLE `tbl_subject_combinations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tbl_terms`
--
ALTER TABLE `tbl_terms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
