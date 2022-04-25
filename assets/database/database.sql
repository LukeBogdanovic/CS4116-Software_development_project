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
    `Username` varchar(32) NOT NULL,
    `Firstname` varchar(32) NOT NULL,
    `Surname` varchar(32) NOT NULL,
    `DateOfBirth` DATE NOT NULL,
    `Password` varchar(256) NOT NULL COMMENT 'hashed password',
    `Email` varchar(52) NOT NULL,
    `Admin` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Is the user an admin',
    `Banned` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Has the user been banned by an admin?',
    PRIMARY KEY (UserID) ON DELETE CASCADE,
    UNIQUE (Email),
    UNIQUE (Username)
  ) ENGINE = InnoDB DEFAULT CHARSET = latin1 COMMENT = 'Store personal information about the user. ';
-- --------------------------------------------------------
  --
  -- Table structure for table `availableinterests`
  --
  CREATE TABLE `availableinterests` (
    `InterestID` int(2) NOT NULL AUTO_INCREMENT,
    `InterestName` varchar(32) NOT NULL COMMENT 'The name of the interest',
    PRIMARY KEY (InterestID)
  ) ENGINE = InnoDB DEFAULT CHARSET = latin1 COMMENT = 'Show a list of available interests for registration search';
-- --------------------------------------------------------
  --
  -- Table structure for table `Connections`
  --
  CREATE TABLE `connections` (
    `ConnectionID` int(11) NOT NULL AUTO_INCREMENT,
    `userID1` int(11) NOT NULL COMMENT 'Which user initiated the connection?',
    `userID2` int(11) NOT NULL COMMENT 'Which user received the connection',
    `ConnectionDate` date NOT NULL COMMENT 'When was the connection made?',
    PRIMARY KEY (ConnectionID),
    CONSTRAINT `Connection_ibfk_1` FOREIGN KEY (userID1) REFERENCES user(UserID) ON DELETE CASCADE,
    CONSTRAINT `Connection_ibfk_2` FOREIGN KEY (userID2) REFERENCES user(UserID) ON DELETE CASCADE
  ) ENGINE = InnoDB DEFAULT CHARSET = latin1;
-- --------------------------------------------------------
  --
  -- Table structure for table `Interests`
  --
  CREATE TABLE `interests` (
    `UserID` int(11) NOT NULL COMMENT 'Which user is this?',
    `InterestID` int(3) NOT NULL COMMENT 'Which interest do they have?',
    Constraint PRIMARY KEY (UserID, InterestID),
    CONSTRAINT `Interests_ibfk_1` FOREIGN KEY (UserID) REFERENCES user(UserID) ON DELETE CASCADE,
    CONSTRAINT `Interests_ibfk_2` FOREIGN KEY (InterestID) REFERENCES availableinterests(InterestID)
  ) ENGINE = InnoDB DEFAULT CHARSET = latin1 COMMENT = 'Interests of ALL users';
-- --------------------------------------------------------
  --
  -- Table structure for table `profile`
  --
  CREATE TABLE `profile` (
    `UserID` int(11) NOT NULL,
    `Smoker` enum('Smoker', 'Social Smoker', 'Non Smoker') NOT NULL COMMENT 'enum type because people can be social smokers',
    `Drinker` enum(
      'Constantly',
      'Most days',
      'Social Drinker',
      'No'
    ) NOT NULL COMMENT 'Enumerated type because there are several answers, but the available answers won''t change',
    `Gender` enum(
      'Female',
      'Male',
      'Non-Binary',
      'Other',
      'Prefer not to say'
    ) NOT NULL COMMENT 'See Drinker comment',
    `Seeking` enum('Female', 'Male', 'All') NOT NULL COMMENT 'See Drinker comment',
    `Description` varchar(512) NOT NULL COMMENT 'Store description as varchar, limit user to 512 characters',
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
    `Town` varchar(32),
    `Employment` VARCHAR(32) DEFAULT 'Unemployed',
    `Student` tinyint(1) NOT NULL DEFAULT 0,
    `College` VARCHAR(32),
    `Degree` VARCHAR(32),
    UNIQUE (userID),
    CONSTRAINT `profile_ibfk_1` FOREIGN KEY (UserID) REFERENCES user(UserID) ON DELETE CASCADE
  ) ENGINE = InnoDB DEFAULT CHARSET = latin1;
-- --------------------------------------------------------
  --
  -- Table structure for table `Reports`
  --
  CREATE TABLE `reports` (
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
    CONSTRAINT `Reports_ibfk_1` FOREIGN KEY (UserID) REFERENCES user(UserID) ON DELETE CASCADE,
    CONSTRAINT `Reports_ibfk_2` FOREIGN KEY (ReporterID) REFERENCES user(UserID) ON DELETE CASCADE
  ) ENGINE = InnoDB DEFAULT CHARSET = latin1;
-- --------------------------------------------------------
  --
  -- Table structure for table `BannedUsers`
  --
  CREATE TABLE `bannedusers` (
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
    CONSTRAINT `BannedUsers_ibfk_1` FOREIGN KEY (UserID) REFERENCES user(UserID) ON DELETE CASCADE,
    CONSTRAINT `BannedUsers_ibfk_2` FOREIGN KEY (BannedByID) REFERENCES user(UserID) ON DELETE CASCADE
  ) ENGINE = InnoDB DEFAULT CHARSET = latin1;
-- --------------------------------------------------------
  --
  -- Table structure for table `SecurityAnswers`
  --
  CREATE TABLE `securityqa` (
    `UserID` int(11) NOT NULL,
    `SecurityQuestion` enum(
      'Mothers maiden name',
      'First pets name',
      'First school',
      'Best friends name',
      'Favourite teacher'
    ) NOT NULL COMMENT 'Users select which security question they are answering need to decide on these',
    `SecurityAnswer` varchar(256) NOT NULL COMMENT 'Users answer to their selected question',
    CONSTRAINT PRIMARY KEY (UserID, SecurityQuestion),
    CONSTRAINT `SecurityAnswers_ibfk_1` FOREIGN KEY (UserID) REFERENCES user(UserID) ON DELETE CASCADE
  ) ENGINE = InnoDB DEFAULT CHARSET = latin1 COMMENT = 'Store account recovery questions and answers for each user';
-- --------------------------------------------------------
  --
  -- Table structure for table `Photos`
  --
  CREATE Table `photos` (
    `UserID` int(11) NOT NULL,
    `PhotoID` int (11) NOT NULL AUTO_INCREMENT,
    `Type` enum('primaryPhoto', 'coverPhoto', 'additionalPhoto') NOT NULL,
    PRIMARY KEY (PhotoID),
    CONSTRAINT `Photos_ibfk_1` FOREIGN KEY (UserID) REFERENCES user(UserID) ON DELETE CASCADE
  ) ENGINE = INNODB DEFAULT CHARSET = latin1 COMMENT = 'Store each Photo of Users';
-- --------------------------------------------------------
  --
  -- Table structure for table `Liked`
  --
  CREATE Table `liked` (
    `UserID1` int(11) NOT NULL COMMENT 'User that has liked another user',
    `UserID2` int(11) NOT NULL COMMENT 'User that has been liked by another user',
    `LikedDate` DATE NOT NULL COMMENT 'When was the user liked?',
    PRIMARY KEY (UserID1, UserID2),
    CONSTRAINT `Liked_ibfk_1` FOREIGN KEY (UserID1) REFERENCES user(UserID) ON DELETE CASCADE,
    CONSTRAINT `Liked_ibfk_2` FOREIGN KEY (UserID2) REFERENCES user(UserID) ON DELETE CASCADE
  ) ENGINE = INNODB DEFAULT CHARSET = latin1 COMMENT = 'Store the likes made between users';
-- --------------------------------------------------------
  --
  -- Fill user table with user info
  --
INSERT INTO
  user
VALUES
  (
    NULL,
    'Lukabog',
    'Luke',
    'Boggie',
    '2001-12-27',
    '$2y$10$5djR4GUfSLIgz20jixUCfOLNzygFGQJ87CUo2RbG2ZjmRwO4fu0WS',
    'asdfg@gmail.com',
    '1',
    DEFAULT
  ),
  (
    NULL,
    'Jackcon',
    'Jack',
    'Murphy',
    '2000-07-15',
    '$2y$10$jFzSOlINTfW.YN1.Rewp5uN55b2.IDvu5MvIgLlsONi0TmUNyhEye',
    'asdfasdaqrdf@gmail.com',
    '1',
    DEFAULT
  ),
  (
    NULL,
    'Mindygirl',
    'Mindy',
    'Dwyer',
    '1999-11-24',
    '$2y$10$/n5dcTHz3ch1WX912z1GuO5Vx2TL1kiU.vx0UpfMT/GpvBXDUZXa6',
    'asdfasdvcxz@gmail.com',
    DEFAULT,
    DEFAULT
  ),
  (
    NULL,
    'jackryan',
    'Jack',
    'Ryan',
    '2000-09-20',
    '$2y$10$jFzSOlINTfW.YN1.Rewp5uN55b2.IDvu5MvIgLlsONi0TmUNyhEye',
    'asdfasdcvbnm@gmail.com',
    DEFAULT,
    DEFAULT
  ),
  (
    NULL,
    'luke420',
    'Luka',
    'Kelly',
    '1998-03-17',
    '$2y$10$5djR4GUfSLIgz20jixUCfOLNzygFGQJ87CUo2RbG2ZjmRwO4fu0WS',
    'asdfasdqwert@gmail.com',
    DEFAULT,
    DEFAULT
  ),
  (
    NULL,
    'mintysally',
    'Sally',
    'Brennan',
    '2000-08-07',
    '$2y$10$ysUqUNxtSFBhJdDJl.Lok.EHhBQFdlwfhy2CKEsDcPy1dVZ4GyuPq',
    'minty@gmail.com',
    DEFAULT,
    DEFAULT
  ),
  (
    NULL,
    'Caoimhe123',
    'Caoimhe',
    'Boyle',
    '1999-06-11',
    '$2y$10$ysUqUNxtSFBhJdDJl.Lok.EHhBQFdlwfhy2CKEsDcPy1dVZ4GyuPq',
    'qweertyy@gmail.com',
    DEFAULT,
    DEFAULT
  ),
  (
    NULL,
    'Kelly123',
    'Kelly',
    'Carroll',
    '2001-02-21',
    '$2y$10$ysUqUNxtSFBhJdDJl.Lok.EHhBQFdlwfhy2CKEsDcPy1dVZ4GyuPq',
    'pofgdh@gmail.com',
    DEFAULT,
    DEFAULT
  ),
  (
    NULL,
    'Johnboy',
    'John',
    'Farrell',
    '2000-03-24',
    '$2y$10$ysUqUNxtSFBhJdDJl.Lok.EHhBQFdlwfhy2CKEsDcPy1dVZ4GyuPq',
    'mohjdda@gmail.com',
    DEFAULT,
    DEFAULT
  );
-- --------------------------------------------------------
  --
  -- Insert data for users into profile table
  --
INSERT INTO
  profile
VALUES
  (
    1,
    'Non Smoker',
    'Constantly',
    'Male',
    'Male',
    'Hi, Im luke Boggie I like smoking',
    'Clare',
    NULL,
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    2,
    'Social Smoker',
    'Most Days',
    'Male',
    'Female',
    'Hi, Im Jack Murphy I like games',
    'Limerick',
    NULL,
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    3,
    'Smoker',
    'Constantly',
    'Female',
    'Male',
    'Hi, Im Mindy I like trains',
    'Tipperary',
    NULL,
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    4,
    'Non Smoker',
    'No',
    'Male',
    'Female',
    'Hi, Im Jack Ryan I like nothing o.O',
    'Waterford',
    NULL,
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    5,
    'Social Smoker',
    'Social Drinker',
    'Male',
    'Male',
    'Hi, Im Luka Kelly I like Code',
    'Dublin',
    NULL,
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    6,
    'Non Smoker',
    'Social Drinker',
    'Female',
    'Female',
    'Hi, Im Sally Murphy I like trains too',
    'Galway',
    NULL,
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    7,
    'Smoker',
    'Constantly',
    'Female',
    'Female',
    'Hi, Im Caoimhe I like myself',
    'Galway',
    NULL,
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    8,
    'Smoker',
    'No',
    'Female',
    'Male',
    'Hi, Im Kelly I like galway',
    'Galway',
    NULL,
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    9,
    'Non Smoker',
    'Social Drinker',
    'Male',
    'Female',
    'Hi, Im John I like drink too',
    'Galway',
    NULL,
    NULL,
    0,
    NULL,
    NULL
  );
-- --------------------------------------------------------
  --
  -- Insert data for Connections into Connections table
  --
INSERT INTO
  connections
VALUES
  (NULL, 1, 7, '2022-03-22'),
  (NULL, 1, 2, '2022-02-14'),
  (NULL, 1, 8, '2022-03-18'),
  (NULL, 1, 5, '2022-02-08'),
  (NULL, 1, 9, '2022-03-06'),
  (NULL, 2, 5, '2022-03-14'),
  (NULL, 2, 7, '2022-03-18'),
  (NULL, 2, 9, '2022-03-02'),
  (NULL, 3, 5, '2022-02-17'),
  (NULL, 3, 8, '2022-03-23'),
  (NULL, 3, 6, '2022-03-16'),
  (NULL, 4, 7, '2022-02-23'),
  (NULL, 4, 9, '2022-03-02'),
  (NULL, 4, 5, '2022-03-21');
-- --------------------------------------------------------
  --
  -- Insert data for avaialble interests into available interest table
  --
INSERT INTO
  availableinterests
VALUES
  (NULL, 'Animals'),
  (NULL, 'Art'),
  (NULL, 'Baking'),
  (NULL, 'Board games'),
  (NULL, 'Carpentry'),
  (NULL, 'Computers'),
  (NULL, 'Cooking'),
  (NULL, 'DIY'),
  (NULL, 'Drinking'),
  (NULL, 'Fitness'),
  (NULL, 'Food'),
  (NULL, 'GAA'),
  (NULL, 'Gardening'),
  (NULL, 'Golf'),
  (NULL, 'Movies'),
  (NULL, 'Music'),
  (NULL, 'Reading'),
  (NULL, 'Role Playing Games'),
  (NULL, 'Rugby'),
  (NULL, 'Soccer'),
  (NULL, 'TV'),
  (NULL, 'Travelling'),
  (NULL, 'Video games');
-- --------------------------------------------------------
  --
  -- Insert data for Interests into the interest table
  --
INSERT INTO
  interests
VALUES
  (1, 5),
  (1, 8),
  (1, 9),
  (1, 2),
  (2, 4),
  (2, 12),
  (2, 18),
  (3, 17),
  (3, 16),
  (3, 4),
  (3, 7),
  (4, 19),
  (4, 17),
  (4, 12),
  (4, 4),
  (5, 5),
  (5, 6),
  (5, 8),
  (5, 9),
  (6, 12),
  (6, 15),
  (6, 16),
  (6, 18),
  (7, 19),
  (7, 15),
  (7, 4),
  (7, 12),
  (8, 15),
  (8, 18),
  (8, 7),
  (8, 21);
  /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
  /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
  /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;