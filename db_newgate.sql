-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 22, 2018 at 05:27 PM
-- Server version: 5.7.21
-- PHP Version: 7.0.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_newgate`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_patients`
--

DROP TABLE IF EXISTS `tbl_patients`;
CREATE TABLE IF NOT EXISTS `tbl_patients` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_num` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `height` float DEFAULT NULL,
  `weight` float DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_patients`
--

INSERT INTO `tbl_patients` (`ID`, `firstname`, `lastname`, `email`, `phone_num`, `dob`, `height`, `weight`) VALUES
(1, 'deji', 'akande', 'dejiakande33@gmail.com', '08143671138', '1998-11-06', 34, 50),
(2, 'isaac', 'olawale', 'isaac@olawale.com', '', '1998-05-13', 99, 99),
(3, 'jedidiah', 'enikuomehin', 'jedidiah@jed.com', '08012345678', '2001-02-03', 59, 34);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_roles`
--

DROP TABLE IF EXISTS `tbl_roles`;
CREATE TABLE IF NOT EXISTS `tbl_roles` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `role` enum('ADMIN','DOCTOR','SUPPORT') NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `userID` (`userID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_roles`
--

INSERT INTO `tbl_roles` (`ID`, `userID`, `role`) VALUES
(1, 1, 'ADMIN'),
(2, 3, 'DOCTOR'),
(3, 1, 'DOCTOR');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

DROP TABLE IF EXISTS `tbl_users`;
CREATE TABLE IF NOT EXISTS `tbl_users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`ID`, `email`, `firstname`, `lastname`, `password`) VALUES
(1, 'admin1@newgate.ho', 'admin', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997'),
(3, 'doc1@newgate.ho', 'donald', 'doc', 'a5beb9d1b0e50129affe6e13e42d9e5f5942cda7');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_roles`
--
ALTER TABLE `tbl_roles`
  ADD CONSTRAINT `tbl_roles_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `tbl_users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
