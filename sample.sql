-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: May 30, 2018 at 09:00 AM
-- Server version: 5.7.22-0ubuntu0.16.04.1
-- PHP Version: 7.0.30-0ubuntu0.16.04.1
SET
  SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET
  time_zone = "+00:00";
  /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
  /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
  /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
  /*!40101 SET NAMES utf8mb4 */;
--
  -- Database: `ET4243_Sample`
  --
  -- --------------------------------------------------------
  --
  -- Table structure for table `AvailableInterests`
  --
  CREATE TABLE `AvailableInterests` (
    `InterestID` int(3) NOT NULL,
    `InterestName` varchar(26) NOT NULL COMMENT 'The name of the interest'
  ) ENGINE = InnoDB DEFAULT CHARSET = latin1 COMMENT = 'Show a list of available interests for registration/search';
-- --------------------------------------------------------
  --
  -- Table structure for table `Connections`
  --
  CREATE TABLE `Connections` (
    `ConnectionID` int(11) NOT NULL,
    `userID1` int(11) NOT NULL COMMENT 'Which user initiated the connection?',
    `userID2` int(11) NOT NULL COMMENT 'Which user received the connection',
    `ConnectionDate` date NOT NULL COMMENT 'When was the connection made?',
    `Superlike` binary(1) NOT NULL
  ) ENGINE = InnoDB DEFAULT CHARSET = latin1;
-- --------------------------------------------------------
  --
  -- Table structure for table `Interests`
  --
  CREATE TABLE `Interests` (
    `UserID` int(11) NOT NULL COMMENT 'Which user is this?',
    `InterestID` int(3) NOT NULL COMMENT 'Which interest do they have?'
  ) ENGINE = InnoDB DEFAULT CHARSET = latin1 COMMENT = 'Interests of ALL users';
-- --------------------------------------------------------
  --
  -- Table structure for table `profile`
  --
  CREATE TABLE `profile` (
    `UserID` int(11) NOT NULL,
    `Age` int(2) NOT NULL,
    `Smoker` binary(1) NOT NULL COMMENT 'Binary type because this is yes or no',
    `Drinker` enum(
      'Constantly',
      'Most days',
      'Social Drinker',
      'No'
    ) NOT NULL COMMENT 'Enumerated type because there are several answers, but the available answers won''t change',
    `Gender` enum('Female', 'Male', 'Other') NOT NULL COMMENT 'See Drinker comment',
    `Seeking` enum('Female', 'Male', 'Other') NOT NULL COMMENT 'See Drinker comment',
    `Description` blob NOT NULL COMMENT 'Blob type because this will contain a free text description of the person',
    `Banned` binary(1) NOT NULL COMMENT 'Has the user been banned by an admin?',
    `Photo1` varchar(26) NOT NULL COMMENT 'We should allow users to upload photos to the site; this field contains the name of the photo they have uploaded',
    `Photo2` varchar(26) COMMENT 'Users can upload optional multiple photos',
    `Photo3` varchar(26) COMMENT 'Users can upload optional multiple photos',
    `County` enum('Limerick', 'Tipperary', 'Cork') NOT NULL,
    `Town` varchar(26)
  ) ENGINE = InnoDB DEFAULT CHARSET = latin1;
-- --------------------------------------------------------
  --
  -- Table structure for table `user`
  --
  CREATE TABLE `user` (
    `UserID` int(11) NOT NULL,
    `Handle` varchar(26) NOT NULL,
    `Firstname` varchar(26) NOT NULL,
    `Surname` varchar(26) NOT NULL,
    `Password` varchar(256) NOT NULL COMMENT 'See video for information on how to encrypt password BEFORE storing it. Never store the user''s actual password.',
    `Email` varchar(52) NOT NULL,
    `SecurityQuestion1` enum('Q1', 'Q2', 'Q3', 'Q4', 'Q5') NOT NULL COMMENT 'Users select which security question they are answering need to decide on these',
    `SecurityQuestion2` enum('Q1', 'Q2', 'Q3', 'Q4', 'Q5') NOT NULL COMMENT 'Users select which security question they are answering'
  ) ENGINE = InnoDB DEFAULT CHARSET = latin1 COMMENT = 'Store personal information about the user. ';
--
  -- AUTO_INCREMENT values for tables
  --
Alter Table
  `AvailableInterests` change `InterestID` `InterestID` int(3) NOT NULL AUTO_INCREMENT;
ALTER TABLE
  `Connections` change `ConnectionID` `ConnectionID` int(11) NOT NULL AUTO_INCREMENT;
ALTER Table
  `user` change `UserID` `UserID` int(11) NOT NULL AUTO_INCREMENT;
--
  -- Add Uniques to user table
  --
ALTER TABLE
  `user`
ADD
  UNIQUE (Handle);
ALTER TABLE
  `user`
ADD
  UNIQUE (Email);
--
  -- --------------------------------------------------------
  --
  -- Table structure for table `Reports`
  --
  CREATE TABLE `Report` (
    `UserID` int(11) NOT NULL,
    `ReportID` int(11) NOT NULL,
    `ReportReason` enum('Harassment','Disrespectful behaviour','Hate Speech','Catfish','Bot account')
    `ReporterID` int(11) NOT NULL,
  ) ENGINE = InnoDB DEFAULT CHARSET = latin1;
-- --------------------------------------------------------
  --
  -- Table structure for table `SecurityAnswers`
  --
  CREATE TABLE `SecurityAnswers` (
    `UserID` int(11) NOT NULL,
    `SecurityAnswer1` varchar(256) NOT NULL,
    `SecurityAnswer2` varchar(256) NOT NULL
  ) ENGINE = InnoDB DEFAULT CHARSET = latin1 COMMENT = 'Store account recovery answers for each user';
-- Indexes for dumped tables
  --
  --
  -- Indexes for table `AvailableInterests`
  --
ALTER TABLE
  `AvailableInterests`
ADD
  PRIMARY KEY (`InterestID`);
--
  -- Indexes for table `Connections`
  --
ALTER TABLE
  `Connections`
ADD
  PRIMARY KEY (`ConnectionID`),
ADD
  KEY `userID1` (`userID1`),
ADD
  KEY `userID2` (`userID2`);
--
  -- Indexes for table `Interests`
  --
ALTER TABLE
  `Interests`
ADD
  PRIMARY KEY (`UserID`, `InterestID`),
ADD
  KEY `UserID` (`UserID`),
ADD
  KEY `InterestID` (`InterestID`);
--
  -- Indexes for table `profile`
  --
ALTER TABLE
  `profile`
ADD
  PRIMARY KEY (`UserID`);
--
  -- Indexes for table `user`
  --
ALTER TABLE
  `user`
ADD
  PRIMARY KEY (`UserID`);
--
  -- Constraints for dumped tables
  --
  --
  -- Constraints for table `Connections`
  --
ALTER TABLE
  `Connections`
ADD
  CONSTRAINT `Connections_ibfk_1` FOREIGN KEY (`userID1`) REFERENCES `user` (`UserID`),
ADD
  CONSTRAINT `Connections_ibfk_2` FOREIGN KEY (`userID2`) REFERENCES `user` (`UserID`);
--
  -- Constraints for table `SecurityAnswers`
  --
ALTER TABLE
  `SecurityAnswers`
ADD
  --
  CONSTRAINT `SecurityAnswers_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);
--
  -- Constraints for table `Interests`
  --
ALTER TABLE
  `Interests`
ADD
  CONSTRAINT `Interests_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`),
ADD
  CONSTRAINT `Interests_ibfk_2` FOREIGN KEY (`InterestID`) REFERENCES `AvailableInterests` (`InterestID`);
--
  -- Constraints for table `Reports`
  --
ALTER TABLE
  `Reports`
ADD
  CONSTRAINT `Reports_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`),
ADD
  CONSTRAINT `Reports_ibfk_2` FOREIGN KEY (`ReporterID`) REFERENCES `user` (`UserID`);
--
  -- Constraints for table `profile`
  --
ALTER TABLE
  `profile`
ADD
  CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);
  /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
  /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
  /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;