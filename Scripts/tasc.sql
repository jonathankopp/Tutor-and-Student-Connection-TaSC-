-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2018 at 03:25 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tasc`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `postid` int(10) UNSIGNED NOT NULL,
  `comment` varchar(1000) NOT NULL,
  `commentdate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`postid`, `comment`, `commentdate`) VALUES
(2, 'Just use std::list and pretend you know what you\'re doing', '2018-04-30');

-- --------------------------------------------------------

--
-- Table structure for table `connections`
--

CREATE TABLE `connections` (
  `tutorid` int(10) UNSIGNED NOT NULL,
  `studentid` int(10) UNSIGNED NOT NULL,
  `subject` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `connections`
--

INSERT INTO `connections` (`tutorid`, `studentid`, `subject`) VALUES
(4, 5, 'Data structures');

-- --------------------------------------------------------

--
-- Table structure for table `forum`
--

CREATE TABLE `forum` (
  `postid` int(10) UNSIGNED NOT NULL,
  `courseid` int(10) UNSIGNED NOT NULL,
  `topic` varchar(1000) NOT NULL,
  `post` varchar(1000) NOT NULL,
  `postdate` date NOT NULL,
  `userid` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `forum`
--

INSERT INTO `forum` (`postid`, `courseid`, `topic`, `post`, `postdate`, `userid`) VALUES
(2, 1, 'What is a linked list?', 'I don\'t like pointers', '2018-04-30', 5),
(3, 8, 'Opportunity cost', 'Was this project worth the opportunity cost?', '2018-04-30', 6);

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subjectid` int(10) UNSIGNED NOT NULL,
  `course` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subjectid`, `course`) VALUES
(1, 'Data Structures'),
(2, 'Physics 1'),
(3, 'Physics 2'),
(4, 'Calculus 1'),
(5, 'Calculus 2'),
(6, 'Computer Science 1'),
(7, 'Intro to ITWS'),
(8, 'Economics'),
(9, 'Biology'),
(10, 'Physcology'),
(11, 'Being Matt 101');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(10) UNSIGNED NOT NULL,
  `first_names` varchar(100) NOT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `year` char(4) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `tutor` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `first_names`, `last_name`, `year`, `email`, `password`, `description`, `tutor`) VALUES
(1, 'Jason', 'Smith', '2021', 'smithj@rpi.edu', 'leg', 'I am a first year struggling in all of my courses', 0),
(2, 'Grace', 'Conway', '2020', 'conwag@rpi.edu', 'leg', 'I study really hard, but my test grades don\'t show it.', 0),
(3, 'Charlie', 'Brown', '2020', 'brownc@rpi.edu', 'leg', 'I lost my dog Snoopy', 0),
(4, 'Patrick', 'Star', '2018', 'starp@rpi.edu', 'leg', 'The inner mechanations of my mind are an enigma', 1),
(5, 'Queena', 'Wang', '2021', 'wangq20@rpi.edu', 'leg', 'I have a bad memory', 0),
(6, 'Alicia', 'Greene', '2020', 'greena@rpi.edu', 'leg', 'I\'m an engineer why do I need humanities...', 0),
(7, 'Tony', 'Stark', '2020', 'starkt@rpi.edu', 'leg', 'Have you seen Infinity War yet?', 1),
(8, 'Jon', 'Snow', '2020', 'snowj@rpi.edu', 'leg', 'I know nothing', 1),
(9, 'Andrew', 'Leaf', '2020', 'leafa@rpi.edu', 'leg', 'They call me the php master', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_subjects`
--

CREATE TABLE `user_subjects` (
  `userid` int(10) UNSIGNED NOT NULL,
  `course` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_subjects`
--

INSERT INTO `user_subjects` (`userid`, `course`) VALUES
(1, 'Data Structures'),
(1, 'Physics 1'),
(1, 'Intro to ITWS'),
(1, 'Economics'),
(2, 'Physics 2'),
(2, 'Biology'),
(2, 'Calculus 2'),
(3, 'Computer Science 1'),
(3, 'Psychology'),
(3, 'Intro to ITWS'),
(4, 'Data Structures'),
(4, 'Intro to ITWS'),
(4, 'Physics 1'),
(4, 'Physics 2'),
(4, 'Calculus 1'),
(4, 'Calculus 2'),
(4, 'Biology'),
(4, 'Psychology'),
(5, 'Data Structures'),
(5, 'Intro to ITWS'),
(5, 'Physics 2'),
(6, 'Economics'),
(6, 'Psychology'),
(7, 'Data Structures'),
(7, 'Intro to ITWS'),
(7, 'Biology'),
(7, 'Physics 2'),
(8, 'Economics'),
(8, 'Psychology'),
(8, 'Physics 1'),
(8, 'Calculus 1'),
(8, 'Calculus 2'),
(8, 'Data Structures'),
(8, 'Intro to ITWS'),
(9, 'Intro to ITWS'),
(9, 'Data Structures'),
(9, 'Computer Science 1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `forum`
--
ALTER TABLE `forum`
  ADD PRIMARY KEY (`postid`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subjectid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `forum`
--
ALTER TABLE `forum`
  MODIFY `postid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `subjectid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
