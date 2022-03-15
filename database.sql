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
  -- Database: `epiz_31123825_group13`
  --
  -- --------------------------------------------------------
  --
  -- Table structure for table `user`
  --
  CREATE TABLE `user` (
    `UserID` int(11) NOT NULL AUTO_INCREMENT,
    `Username` varchar(26) NOT NULL,
    `Firstname` varchar(26) NOT NULL,
    `Surname` varchar(26) NOT NULL,
    `Password` varchar(256) NOT NULL COMMENT 'See video for information on how to encrypt password BEFORE storing it. Never store the user''s actual password.',
    `Email` varchar(52) NOT NULL,
    `Admin` binary(1) NOT NULL DEFAULT 0 COMMENT 'Is the user an admin',
    `Banned` binary(1) NOT NULL DEFAULT 0 COMMENT 'Has the user been banned by an admin?',
    PRIMARY KEY (UserID),
    UNIQUE (Email),
    UNIQUE (Username)
  ) ENGINE = InnoDB DEFAULT CHARSET = latin1 COMMENT = 'Store personal information about the user. ';
-- --------------------------------------------------------
  --
  -- Table structure for table `AvailableInterests`
  --
  CREATE TABLE `AvailableInterests` (
    `InterestID` int(3) NOT NULL AUTO_INCREMENT,
    `InterestName` varchar(26) NOT NULL COMMENT 'The name of the interest',
    PRIMARY KEY (InterestID)
  ) ENGINE = InnoDB DEFAULT CHARSET = latin1 COMMENT = 'Show a list of available interests for registration search';
-- --------------------------------------------------------
  --
  -- Table structure for table `Connections`
  --
  CREATE TABLE `Connections` (
    `ConnectionID` int(11) NOT NULL AUTO_INCREMENT,
    `userID1` int(11) NOT NULL COMMENT 'Which user initiated the connection?',
    `userID2` int(11) NOT NULL COMMENT 'Which user received the connection',
    `ConnectionDate` date NOT NULL COMMENT 'When was the connection made?',
    PRIMARY KEY (ConnectionID),
    CONSTRAINT `Connection_ibfk_1` FOREIGN KEY (userID1) REFERENCES user(UserID),
    CONSTRAINT `Connection_ibfk_2` FOREIGN KEY (userID2) REFERENCES user(UserID)
  ) ENGINE = InnoDB DEFAULT CHARSET = latin1;
-- --------------------------------------------------------
  --
  -- Table structure for table `Interests`
  --
  CREATE TABLE `Interests` (
    `UserID` int(11) NOT NULL COMMENT 'Which user is this?',
    `InterestID` int(3) NOT NULL COMMENT 'Which interest do they have?',
    CONSTRAINT `Interests_ibfk_1` FOREIGN KEY (UserID) REFERENCES user(UserID),
    CONSTRAINT `Interests_ibfk_2` FOREIGN KEY (InterestID) REFERENCES AvailableInterests(InterestID)
  ) ENGINE = InnoDB DEFAULT CHARSET = latin1 COMMENT = 'Interests of ALL users';
-- --------------------------------------------------------
  --
  -- Table structure for table `profile`
  --
  CREATE TABLE `profile` (
    `UserID` int(11) NOT NULL,
    `Age` int(2) NOT NULL,
    `Smoker` enum('Smoker', 'Social Smoker', 'Non Smoker') NOT NULL COMMENT 'enum type because people can be social smokers',
    `Drinker` enum(
      'Constantly',
      'Most days',
      'Social Drinker',
      'No'
    ) NOT NULL COMMENT 'Enumerated type because there are several answers, but the available answers won''t change',
    `Gender` enum('Female', 'Male', 'Other') NOT NULL COMMENT 'See Drinker comment',
    `Seeking` enum('Female', 'Male', 'Other') NOT NULL COMMENT 'See Drinker comment',
    `Description` blob NOT NULL COMMENT 'Blob type because this will contain a free text description of the person',
    `County` enum(
      'Antrim',
      'Armagh',
      'Carlow',
      'Cavan',
      'Clare',
      'Cork',
      'Donegal',
      'Down',
      'Dublin',
      'Fermanagh',
      'Galway',
      'Kerry',
      'Kildare',
      'Kilkenny',
      'Laois',
      'Leitrim',
      'Limerick',
      'Derry',
      'Longford',
      'Louth',
      'Mayo',
      'Meath',
      'Monaghan',
      'Offaly',
      'Roscommon',
      'Sligo',
      'Tipperary',
      'Tyrone',
      'Waterford',
      'Westmeath',
      'Wexford',
      'Wicklow'
    ) NOT NULL,
    `Town` varchar(26),
    `Employment` VARCHAR(26) DEFAULT 'Unemployed',
    `Student` binary(1) NOT NULL DEFAULT 0,
    `College` VARCHAR(26),
    `Degree` VARCHAR(26),
    CONSTRAINT `profile_ibfk_1` FOREIGN KEY (UserID) REFERENCES user(UserID)
  ) ENGINE = InnoDB DEFAULT CHARSET = latin1;
-- --------------------------------------------------------
  --
  -- Table structure for table `Reports`
  --
  CREATE TABLE `Reports` (
    `UserID` int(11) NOT NULL,
    `ReportID` int(11) NOT NULL AUTO_INCREMENT,
    `ReportReason` enum(
      'Harassment',
      'Disrespectful behaviour',
      'Hate Speech',
      'Catfish',
      'Bot account'
    ),
    `ReporterID` int(11) NOT NULL,
    PRIMARY KEY (ReportID),
    CONSTRAINT `Reports_ibfk_1` FOREIGN KEY (UserID) REFERENCES user(UserID),
    CONSTRAINT `Reports_ibfk_2` FOREIGN KEY (ReporterID) REFERENCES user(UserID)
  ) ENGINE = InnoDB DEFAULT CHARSET = latin1;
-- --------------------------------------------------------
  --
  -- Table structure for table `BannedUsers`
  --
  CREATE TABLE `BannedUsers` (
    `UserID` int(11) NOT NULL,
    `BanID` int(11) NOT NULL AUTO_INCREMENT,
    `Date` DATE NOT NULL,
    `BannedByID` int(11) NOT NULL,
    `Reason` enum(
      'Harassment',
      'Disrespectful behaviour',
      'Hate Speech',
      'Catfish',
      'Bot account'
    ),
    `Duration` int(3) NOT NULL DEFAULT 2 COMMENT 'Ban length in weeks. Default = 2. 0 = permabanned',
    PRIMARY KEY (BanID),
    CONSTRAINT `BannedUsers_ibfk_1` FOREIGN KEY (UserID) REFERENCES user(UserID),
    CONSTRAINT `BannedUsers_ibfk_2` FOREIGN KEY (BannedByID) REFERENCES user(UserID)
  ) ENGINE = InnoDB DEFAULT CHARSET = latin1;
-- --------------------------------------------------------
  --
  -- Table structure for table `SecurityAnswers`
  --
  CREATE TABLE `SecurityQA` (
    `UserID` int(11) NOT NULL,
    `SecurityQuestion` enum(
      'Mothers maiden name',
      'First pets name',
      'First school',
      'Best friends name',
      'Favourite teacher'
    ) NOT NULL COMMENT 'Users select which security question they are answering need to decide on these',
    `SecurityAnswer` varchar(256) NOT NULL COMMENT 'Users answer to their selected question',
    CONSTRAINT `SecurityAnswers_ibfk_1` FOREIGN KEY (UserID) REFERENCES user(UserID)
  ) ENGINE = InnoDB DEFAULT CHARSET = latin1 COMMENT = 'Store account recovery questions and answers for each user';
-- --------------------------------------------------------
  --
  -- Table structure for table `Photos`
  --
  CREATE Table `Photos` (
    `UserID` int(11) NOT NULL,
    `PhotoID` int (11) NOT NULL AUTO_INCREMENT,
    `Type` enum('primaryPhoto', 'coverPhoto', 'additionalPhoto') NOT NULL,
    PRIMARY KEY (PhotoID),
    CONSTRAINT `Photos_ibfk_1` FOREIGN KEY (UserID) REFERENCES user(UserID)
  ) ENGINE = INNODB DEFAULT CHARSET = latin1 COMMENT = 'Store each Photo of Users';
-- --------------------------------------------------------
  --
  -- Table structure for table `Liked`
  --
  CREATE Table `Liked` (
    `UserID1` int(11) NOT NULL COMMENT 'User that has liked another user',
    `UserID2` int(11) NOT NULL COMMENT 'User that has been liked by another user',
    `LikedDate` DATE NOT NULL COMMENT 'When was the user liked?',
    CONSTRAINT `Liked_ibfk_1` FOREIGN KEY (UserID1) REFERENCES user(UserID),
    CONSTRAINT `Liked_ibfk_2` FOREIGN KEY (UserID2) REFERENCES user(UserID)
  ) ENGINE = INNODB DEFAULT CHARSET = latin1 COMMENT = 'Store the likes made between users';
  /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
  /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
  /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;