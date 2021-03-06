-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql302.epizy.com
-- Generation Time: Apr 25, 2022 at 10:08 PM
-- Server version: 10.3.27-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

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
    PRIMARY KEY (UserID),
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
    CONSTRAINT `Connection_unique` UNIQUE (UserID1, UserID2),
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
    `PhotoID` varchar (27) NOT NULL COMMENT 'long enough to store the output of php''suniqid("",true) +the .jpeg/.png', 
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
INSERT INTO `user` (`UserID`, `Username`, `Firstname`, `Surname`, `DateOfBirth`, `Password`, `Email`, `Admin`, `Banned`) VALUES
(1, 'Lukabog', 'Luke', 'Boggie', '2001-12-27', '$2y$10$5djR4GUfSLIgz20jixUCfOLNzygFGQJ87CUo2RbG2ZjmRwO4fu0WS', 'asdfg@gmail.com', 1, 0),
(2, 'Jackcon', 'Jack', 'Murphy', '2000-07-15', '$2y$10$jFzSOlINTfW.YN1.Rewp5uN55b2.IDvu5MvIgLlsONi0TmUNyhEye', 'asdfasdaqrdf@gmail.com', 1, 0),
(3, 'Mindygirl', 'Mindy', 'Dwyer', '1999-11-24', '$2y$10$/n5dcTHz3ch1WX912z1GuO5Vx2TL1kiU.vx0UpfMT/GpvBXDUZXa6', 'asdfasdvcxz@gmail.com', 0, 0),
(4, 'jackryan', 'Jack', 'Ryan', '2000-09-20', '$2y$10$jFzSOlINTfW.YN1.Rewp5uN55b2.IDvu5MvIgLlsONi0TmUNyhEye', 'asdfasdcvbnm@gmail.com', 0, 0),
(5, 'luke420', 'Luka', 'Kelly', '1998-03-17', '$2y$10$5djR4GUfSLIgz20jixUCfOLNzygFGQJ87CUo2RbG2ZjmRwO4fu0WS', 'asdfasdqwert@gmail.com', 0, 0),
(6, 'mintysally', 'Sally', 'Brennan', '2000-08-07', '$2y$10$ysUqUNxtSFBhJdDJl.Lok.EHhBQFdlwfhy2CKEsDcPy1dVZ4GyuPq', 'minty@gmail.com', 0, 0),
(7, 'Caoimhe123', 'Caoimhe', 'Boyle', '1999-06-11', '$2y$10$ysUqUNxtSFBhJdDJl.Lok.EHhBQFdlwfhy2CKEsDcPy1dVZ4GyuPq', 'qweertyy@gmail.com', 0, 0),
(8, 'Kelly123', 'Kelly', 'Carroll', '2001-02-21', '$2y$10$ysUqUNxtSFBhJdDJl.Lok.EHhBQFdlwfhy2CKEsDcPy1dVZ4GyuPq', 'pofgdh@gmail.com', 0, 0),
(9, 'Johnboy', 'John', 'Farrell', '2000-03-24', '$2y$10$ysUqUNxtSFBhJdDJl.Lok.EHhBQFdlwfhy2CKEsDcPy1dVZ4GyuPq', 'mohjdda@gmail.com', 0, 0),
(10, 'agonisw', 'Adelyn', 'Jack', '1998-02-02', '$2y$10$B5D7YsY8xHu5QqzKwyozaeUAxSN9fx3Jfo7GlSeoB6NvYrpOeyJEa', '123@gmail.com', 0, 0),
(11, 'Mr B', 'Barry', 'Benson', '1998-11-05', '$2y$10$8ycRm4aJn.jz.l5e3MjGreovDQmiYNtReRoTTu2g9Sj5zQJr5qmuy', 'marytease40@gmail.com', 0, 0),
(12, 'jack', 'jack', 'busher', '2000-09-25', '$2y$10$.RCBT3D9vt4n8D1gRPH1AeTYEOTBl/qnZdr44YrjYjAWeuMlFpcK6', 'jack@gmail.com', 0, 0),
(13, 'test', 'test', 'test', '2000-09-20', '$2y$10$ObGUg9OIzD8YAsdJPR8Lz.KnXf.fOWb2FVJ8EqXzaGbv5AIJ9BA/.', 'test1@gmail.com', 0, 0),
(14, 'PMX1121', 'asdf', 'sdad', '2000-12-29', '$2y$10$CcXwuUMbh.9p7JT/oG3Ra.cgUCx63PqqN1VcNTTWPN/FChrTzfwoO', 'asdf@gmail.com', 0, 0),
(15, 'PMX1122', 'asdf', 'sdad', '1999-03-03', '$2y$10$GrBrE8pUr6hZ03nv0gS/r.SrDy1y0uNI1SDHG2qS68GphPZNc9V7K', 'john.smith@email.com', 0, 0),
(16, 'PMX112222', 'test', 'test', '2000-03-01', '$2y$10$KnUbaZL5s5ELtsUweICo/etR181AjBV97bAUrbYhgzndSNqx7afiu', 'test.@com', 0, 0),
(17, '123456789101123456', 'John', 'Doe', '2000-01-01', '$2y$10$jZjHMhPHMegfW/y4evz5mu0pp4YWEW7PHZXbmC7phYP.Oc91TdYQm', 'john@gmail.com', 0, 1),
(18, 'harrypotter12', 'Harry', 'Potter', '2000-01-03', '$2y$10$ZoAQMNvOavX48jSJmR0tq.I46C3Fxi2nyAGIc7Fqix55oCDrVwhYO', 'h@p.com', 0, 0),
(19, 'william123', 'William', 'Smith', '1996-02-21', '$2y$10$Of7.JIcFy2J8gXQx278aH.JlqiCPeHrE7RZE352/m67YNv0FbiDOW', 'william.smith42@gmail.com', 0, 0),
(20, 'emmetb', 'Emmet', 'Browne', '2000-02-07', '$2y$10$uQ5OEKFUR/JdG7Fkw4e8ae3SOriRyOBGGaTWI35Sc9KLdC4CTSdAy', 'e@gmial.com', 0, 0),
(21, 'hank', 'test', 'acc', '2000-11-11', '$2y$10$sdr4bh5EDKK12ZDvZqGSn.yrpsZeJBylUyYdFHH8yb2EoD1zIGZhG', 'hank@gmail.com', 0, 0),
(24, 'abcd', 'efgh', 'ijkl', '1992-01-01', '$2y$10$OOTjq1zA9lRDh1VbCC3ohu.sCVKlebwW1jV1gNkv02OlIQSi2IQxG', '1@2.com', 0, 0),
(25, 'JaneDoe09', 'Jane', 'Doe', '2000-01-01', '$2y$10$YZsmVDC7DbJL2hSoHotLD.cdgTMBZG.ZBhgsdm6isTesdcw4YLqU2', 'jane@email.com', 0, 0),
(26, 'Barry', 'Barry', 'O''Brien', '1986-02-12', '$2y$10$H7HDi7QeIOVItuSSAYQlaOf.4Ez5GC3xfK5Ouj.nvZAOEhrGl8xxG', 'barry@email.com', 0, 0),
(27, 'TestingInterests', 'Testing', 'Interests', '2000-05-30', '$2y$10$lvURieBcprsh3AFff5QVfO5wmRKThhLcu2kcaDb58ftGIt78O2TVe', 'blahinterests@gmail.com', 0, 0),
(28, 'wetestin', 'Blah', 'Blah', '2000-05-30', '$2y$10$czTzFnKsHMKczjDIKNsCeOPxYOZysff3OHs2175vcIw.Bktrg5uAS', 'Apple@gmail.com', 0, 0),
(29, 'Jackconor', 'Honathon', 'Malachy', '1978-08-15', '$2y$10$ho2MBLyZA043regM8Cf2JucgcyehkZ6XbKpi.nDTXVvX9ypXfPE7q', 'malchy@gmail.com', 0, 0),
(30, 'Gingergorl', 'Aisling', 'Ryan', '1995-10-16', '$2y$10$V3mrYnDh6DD66fG7DT5BCeiYzkX.nkWX0PuIxdh9mEo0v9jsz18ke', 'looking4love@gmail.com', 0, 0),
(31, 'stuff', 'naimh', 'roche', '1995-11-19', '$2y$10$XieSo5bRcVjVG5p2wAWWrOL2rgYz0pnAW5F.BbG4CDIOTabnMr546', 'niamh@gmail.com', 0, 0),
(32, 'Mobiletest', 'Jack', 'Ryan', '2000-04-18', '$2y$10$ls1ajKw6FkJfwDr3w/GQaORLA67WkgIg0Z4bphnapEHDx0rOzK.Uq', 'mobiletest@gmail.com', 0, 0),
(33, 'Jimmyboy', 'Jim', 'Ramsbottom', '2001-04-18', '$2y$10$h3CoNd49HG19n5PmctnhnODAuIuhiWIIH85ZTxx.mHQxqQ8P0Ac5S', 'Jimmy@gmail.com', 0, 0),
(34, 'obi_wan', 'Obi-Wan', 'Kenobi', '1967-02-15', '$2y$10$Fu3D2HtLkHER7RLYUd7r9OmXzc.aVipQbZqH7Y7yesKWm/3bJycpa', 'obiwan@gmail.com', 0, 0),
(35, 'anakin_skywalker', 'Anakin', 'Skywalker', '1990-12-25', '$2y$10$mNtLDFqjy5wFApTZna1sZu/OAo45bE7g.vyhkFyGbrf1RXyCgNzEW', 'anakin@gmail.com', 0, 0),
(36, 'ahsoka_tano', 'Ahsoka', 'Tano', '2001-10-17', '$2y$10$Kl2.foDYBNGnkaH8ZQPgDuEGUZ3Y8cO0UjzSOG09HIxA4GJMYrZP2', 'ahsoka@gmail.com', 0, 0),
(37, 'Padme', 'Padm????', 'Amidala', '1987-08-14', '$2y$10$Nc3a6zIpID9LhLTCUIPwieFv5aFdv.OiM1UrIxMNMJ4Kj/ApneHJu', 'padme@gmail.com', 0, 0),
(38, 'r2d2', 'R2', 'D2', '1925-12-24', '$2y$10$HJkWbnlzodYfHO9bUzHImuM5VJTBLxFVD9ErrvDKNDpKgsvYYYK2S', 'r2d2@gmail.com', 0, 0),
(39, 'c3p0', 'C', '3P0', '1990-12-31', '$2y$10$oV/NcQOqci/PGbj2BvKDIO7tI29A9LLQHY/Am4yRlzSq7WVLZfkOu', 'c3p0@gmail.com', 0, 0),
(40, 'rex', 'Captain', 'Rex', '1998-10-12', '$2y$10$vLmxCL1eMiYj8V0UO/3MOeStJPKkHvP/FYr8pWB765.3JYZqFtTsu', 'rex@gmail.com', 0, 0),
(41, 'yoda', 'Yoda', 'Green', '1907-11-15', '$2y$10$X6AUwAx24pZwdXFKerNZcO9uRifVzfwgXoN2W2qnO.bW6mOzOPcX6', 'yoda@gmail.com', 0, 0),
(42, 'windu', 'Mace', 'Windu', '1960-05-04', '$2y$10$ZfXiiwVUyD8gtVOPg0Oh6OMpzQY.1n8kleBPSlufPwoJccQRupS6u', 'mace@gmail.com', 0, 0),
(43, 'plokoon', 'Plo', 'Koon', '1950-03-05', '$2y$10$j9NA0LVWa51IyctOaYw8IeWJcVCJJPcvJtB6W/x9z5bNUXD7TfKtW', 'plokoon@gmail.com', 0, 0),
(44, 'vader', 'Darth', 'Vader', '2002-01-03', '$2y$10$eVOxml4FHoPiigym4iCIx.HJMxS8s8bkfa1sxFTtbt1DXJg2F/uPK', 'vader@gmail.com', 0, 0),
(45, 'luke', 'Luke', 'Skywalker', '2000-12-11', '$2y$10$9qjM2UA/M/khMDIg2pkfouiubM/JZXPtInFmrSYAoLqS9heh56iqC', 'luke@gmail.com', 0, 0),
(46, 'solohan', 'Han', 'Solo', '1999-04-06', '$2y$10$aJr9OwuoRepm0ZGXOK6nTuuX6/wFVvRH14lh5nWL8xSji8Y0le.i.', 'solo@gmail.com', 0, 0),
(47, 'princessleia', 'Princess', 'Leia', '2000-02-22', '$2y$10$yRMuX7vH3eWMGr2zUKGP/.9U1dJEt70Hy327aVpHefRKPF.ri9Viu', 'leia@gmail.com', 0, 0),
(48, 'chewy', 'Chewbacca', 'Wookie', '1900-08-07', '$2y$10$DjGLJJLF1LjjOrXKPaP4MuWzGo3R2TThAhUytD93cG6BeyrErsGQG', 'chewbacca@gmail.com', 0, 0),
(49, 'bobafett', 'Boba', 'Fett', '1990-06-07', '$2y$10$nuro9U563WgqE3BcbE3Rdu9sJWi7ESQ0JtSwZZ5rqt3dU8bUt67yO', 'bobafett@gmail.com', 0, 0),
(50, 'jango', 'Jango', 'Fett', '1969-02-06', '$2y$10$emmFwF0Ehgz8ETplNBmZh.IvUQejaWmzev7ZWqlqnYGYNOdYoxhDS', 'jango@gmail.com', 0, 0),
(51, 'kyloren', 'Kylo', 'Ren', '2003-12-24', '$2y$10$3lZcwVAsob.w.Xcoza.BcOKQy3aM6273htSR8AglnHQHGAGimNfti', 'kylo@gmail.com', 0, 0),
(52, 'blah', 'asdasd', 'asdasda', '2001-11-11', '$2y$10$zzmREKLWCLNl.NkAgrwPjudYAF9EglgNQWQz4HbzqgcgIl9AkCBim', 'blahblahblah01@gmail.com', 0, 0),
(53, 'calrissian', 'Lando', 'Calrissian', '1992-12-12', '$2y$10$8BQEQ12LTXFNSGSkNrTNfuJDHRyquc8ZT4S3XItQlJkTBFXYbrM.m', 'calrissian@gmail.com', 0, 0),
(54, 'maul', 'Darth', 'Maul', '1955-07-31', '$2y$10$vbe2yx72E/LaKZN2M3vIbu1BBUUK8PCTKWmJ6hNTWH2nX0ejuDsVm', 'maul@gmail.com', 0, 0),
(55, 'Qui_Gon', 'Qui_Gon', 'Jinn', '1945-04-30', '$2y$10$VyzBd81TvKZj7Xe0hp5Az.CzFFttQ7nwbDo3KCJMA6p5fzKlwzl8a', 'quigon@gmail.com', 0, 0),
(56, 'dooku', 'Count', 'Dooku', '1949-05-22', '$2y$10$ZPouXrlxjc.xYNX99isKh.WJC0QGmnD9eCW4p5n5.Lbrtu5YqL2kO', 'dooku@gmail.com', 0, 0),
(57, 'grievous', 'General', 'Grievous', '1940-12-30', '$2y$10$Wlks.wlCbz.5qf.Is3FpbO7D4O2tpT790xtM8XxLPKQmzjpWj4KPq', 'grievous@gmail.com', 0, 0),
(58, 'mikescott', 'Michael', 'Scott', '1999-06-05', '$2y$10$xFYeSkb5hJQyrkjXyxPuD.Azry0H14snRMbEHSOPghM4C/RCiSHkC', 'Michael@gmail.com', 0, 0),
(59, 'dwightschrute', 'Dwight', 'Schrute', '1995-08-07', '$2y$10$IeFGh2jjrV01vZc.kloaoew1SEb96XCSov7CQvvXk.NKMflgXfjVe', 'dwight@gmail.com', 0, 0),
(60, 'jimmyhalpert', 'Jim', 'Halpert', '2000-04-01', '$2y$10$dbGl0Rm6aL6nh9RT4hakg.UxjMuQOlbs50K.tXobTFkiDreNf0rQ.', 'halpert@gmail.com', 0, 0),
(61, 'pammyb', 'Pam', 'Beasley', '1998-09-20', '$2y$10$4qgzljH207l6WyS7nzE3euRF2k/KUdJ5Unt/oAInwNTKjiDsV5I0e', 'pam@gmail.com', 0, 0),
(62, 'narddog', 'Andy', 'Bernard', '2001-05-24', '$2y$10$Hh8wdINs6hF2os1BJ9WmU.o//ZOBJEZ7x161hqRcRwXOcRwX0NhFm', 'andy@gmail.com', 0, 0),
(63, 'kevinM', 'Kevin', 'Malone', '1994-10-31', '$2y$10$/0g0x9gGoo1sWkyBg3Wj.uXFWQ4ukYCHJMn2N6hF4o/ee6EPDP/Yu', 'kevin@gmail.com', 0, 0),
(64, 'angelaM', 'Angela', 'Martin', '1990-10-10', '$2y$10$xMVgAvHNww2Ltnv3Ln77iOahmkjmvIu0Rc6m/idmNqVxUJ5gzC1rK', 'angela@gmail.com', 0, 0),
(65, 'tobyF', 'Toby', 'Flenderson', '2000-05-31', '$2y$10$umMGinPUDwyaM0BN.LlKXehKKB/c4cXIGzosyJB8tvbdS.N7eyUNy', 'toby@gmail.com', 0, 0),
(66, 'ryanH', 'Ryan', 'Howard', '1997-02-22', '$2y$10$q2vLlSwMau.dqC0GurIfUeMi1zAGJ91.7HZ5o0xsi/GL/k5KmzHPy', 'ryan@gmail.com', 0, 0),
(67, 'stanhud', 'Stanley', 'Hudson', '2002-03-19', '$2y$10$DKSHvQYF/SwYISY9REQLcudOr2CpuNVQ45QgIu3k5jR1Fx5/2WdUe', 'stanley@gmail.com', 0, 0),
(68, 'robcali', 'Robert', 'California', '1988-10-10', '$2y$10$qafM2M.nd.gauYmhQm1oq.AjLc86nPkYDddkKZexWoTc//IDBz1gq', 'robert@gmail.com', 0, 0),
(69, 'kellyk', 'Kelly', 'Kapoor', '1999-12-31', '$2y$10$yv99e7bcBg9I1f3x2BlgoefspKCfrcriZbyDIisQ3aApMFyBB8diO', 'kelly@gmail.com', 0, 0),
(70, 'merpal', 'Meredith', 'Palmer', '1993-10-22', '$2y$10$f8gZklxVgcBvjFZwyEQaKeURhVaGUTi.VabdRgN418xP9q49gCXOe', 'meredith@gmail.com', 0, 0),
(71, 'darrylphil', 'Darryl', 'Philbin', '2000-09-19', '$2y$10$gZoAyQU00Ixc4TlqA5OHJuEa66eKlA.ApBY3.eeWurme3CDcJ9VFe', 'darryl@gmail.com', 0, 0),
(72, 'nelliebert', 'Nellie', 'Bertram', '1999-03-31', '$2y$10$hIqEG4qYrvd0fhaLOGWTR.UaNe99/bSSdQ3hySXA5kbTyiwE.pDy2', 'nellie@gmail.com', 0, 0),
(73, 'moseschrute', 'Mose', 'Schrute', '1995-05-05', '$2y$10$VXDU9uHTBY7qOBg7B4LTa.35uDaPMYMfnJ3APaMslNledt0xvOVIq', 'mose@gmail.com', 0, 0),
(74, 'oscarM', 'Oscar', 'Martinez', '2001-04-04', '$2y$10$C5kp3ipUEfYbH9JxIgOP7.RzXn2DDZujZhbJZpTAnzxKnI.2i1XU6', 'oscar@gmail.com', 0, 0),
(75, 'creedb', 'Creed', 'Bratton', '1991-11-11', '$2y$10$tDgG1fY0tQloegkncYmDjuL7ssdsY2o.9ACtD6Wk6lioSufVeg/Nq', 'creed@gmail.com', 0, 0),
(76, 'ronald', 'Ron', 'Swanson', '1996-03-30', '$2y$10$zS/jJL4gOImSAVuLGgKaEu3N6SaRfPctrbwlL3/6SpMKKoSmwhqAC', 'ron@gmail.com', 0, 0),
(77, 'aprillud', 'April', 'Ludgate', '2001-04-04', '$2y$10$a3MnQ0Ak.kGRuSsazx4L/OvSMz6dWinuedWsb4KrZ7vIgeFhudEz6', 'april@gmail.com', 0, 0),
(78, 'leslieknope', 'Leslie', 'Knope', '1999-03-31', '$2y$10$wZ3QQRoz5N5NCrvmw54anu/4S4yGdDj.kOYPu/Q5RxF3.GZLV3Vxy', 'leslie@gmail.com', 0, 0),
(79, 'andydwyer', 'Andy', 'Dwyer', '1995-08-12', '$2y$10$nOqAsZI8AntHjDX.RwNL7.S7pwNI.7uq13BNQO3dfXNok3iM.0wvG', 'dwyer@gmail.com', 0, 0),
(80, 'benwyatt', 'Ben', 'Wyatt', '2002-12-04', '$2y$10$vjoQM0Frg.yXodcQ6rxA8OqpWlPsKLBgoSKzllSEQHUWuVqCHqdKK', 'wyatt@gmail.com', 0, 0),
(81, 'annper', 'Ann', 'Perkins', '1999-07-07', '$2y$10$5ikzqZrGdrK2N1PkDCL7rev/twBDTWV6g9U2MnpT2aEgm.AHktyci', 'ann@gmail.com', 0, 0),
(82, 'tommyhav', 'Tom', 'Haverford', '1998-09-04', '$2y$10$/4IuRW.RLtchPjXkdcm8g./iYjpR6.bIYFg7KQc6gMQQNh/ZuiPOe', 'tom@gmail.com', 0, 0),
(83, 'jerryg', 'Jerry', 'Gergich', '1990-08-08', '$2y$10$g3AyzPBY9En8N.30zKUSh.9rbWYIYvOTEvqyKX8g7L.y2sEFEuURe', 'jerry@gmail.com', 0, 0),
(84, 'christopher', 'Chris', 'Traeger', '2000-06-06', '$2y$10$IOxo5x/r8NfmtGhni.Tyzuwosl3TrUo84RnpM8zCS4PffxivVSg.a', 'chris@gmail.com', 0, 0),
(85, 'donnam', 'Donna', 'Meagle', '1999-09-07', '$2y$10$Ond9gmQ2j1ZIBy5q4EIfke6DomyyEsHJn2U.qNUCx.OOK3KgcaZfO', 'donna@gmail.com', 0, 0),
(86, 'test2', 'Test', 'User', '2000-02-22', '$2y$10$zIRytJCJ5Gtn4.Fpv1SS3.9rbnHEHpyVPs/THPRoethHNYel7lLa.', 'tes@gmail.com', 0, 0),
(87, 'Wetestnostudent', 'we', 'trest', '1992-08-17', '$2y$10$.kt3q8zKCIwb.VrzSQmLC.AqAU.COvJA2eMapkmG3vgXtxNlIHhH.', 'jacktestno@gmail.com', 0, 0),
(88, 'testinagain', 'test', 'again', '1991-04-20', '$2y$10$gQXvk1N7hvmbc16dUt7EZ.VN86ttOQLCCu6LzjPP2yS7aNWOzJx/.', 'a@gmail.com', 0, 0);

-- --------------------------------------------------------
  --
  -- Insert data for users into profile table
  --
INSERT INTO `profile` (`UserID`, `Smoker`, `Drinker`, `Gender`, `Seeking`, `Description`, `County`, `Town`, `Employment`, `Student`, `College`, `Degree`) VALUES
(1, 'Non Smoker', 'Constantly', 'Male', 'Male', 'Hi, Im luke Boggie I like smoking', 'Clare', NULL, NULL, 1, NULL, NULL),
(2, 'Social Smoker', 'Most days', 'Male', 'Female', 'Hi, Im Jack Murphy I like games', 'Limerick', NULL, NULL, 1, NULL, NULL),
(3, 'Smoker', 'Constantly', 'Female', 'Male', 'Hi, Im Mindy I like trains', 'Tipperary', NULL, NULL, 0, NULL, NULL),
(4, 'Non Smoker', 'No', 'Male', 'Female', 'Hi, Im Jack Ryan I like nothing o.O', 'Waterford', NULL, NULL, 1, NULL, NULL),
(5, 'Social Smoker', 'Social Drinker', 'Male', 'Male', 'Hi, Im Luka Kelly I like Code', 'Dublin', NULL, NULL, 1, NULL, NULL),
(6, 'Non Smoker', 'Social Drinker', 'Female', 'Female', 'Hi, Im Sally Murphy I like trains too', 'Galway', NULL, NULL, 1, NULL, NULL),
(7, 'Smoker', 'Constantly', 'Female', 'Female', 'Hi, Im Caoimhe I like myself', 'Galway', NULL, NULL, 1, NULL, NULL),
(8, 'Smoker', 'No', 'Female', 'Male', 'Hi, Im Kelly I like galway', 'Galway', NULL, NULL, 1, NULL, NULL),
(9, 'Non Smoker', 'Social Drinker', 'Male', 'Female', 'Hi, Im John I like drink too', 'Galway', NULL, NULL, 0, NULL, NULL),
(27, 'Social Smoker', 'Most days', 'Male', 'Female', 'Hi I\'m here to test interests and only to test interests don\'t talk to me I don\'t reply, I cease to exist when testing is completed, I hope testing carries on for a while ', 'Tipperary', 'Cashel town bai', NULL, 1, 'UL ', 'College stuff'),
(29, 'Non Smoker', 'No', 'Male', 'Female', 'Bla', 'Tipperary', 'Cashel', 'Farmer', 1, 'UL', 'compooter science'),
(30, 'Non Smoker', 'Social Drinker', 'Female', 'Male', 'Looking for fun friendship and maybe more ', 'Dublin', 'Blackrock', 'Engineer', 1, 'UCC', 'Chemistry'),
(31, 'Social Smoker', 'No', 'Female', 'All', 'blah', 'Louth', NULL, 'compooter', 1, 'UL', 'compooter science'),
(32, 'Social Smoker', 'No', 'Male', 'Female', 'Hi ', 'Cork', NULL, 'compooter job', 1, NULL, NULL),
(34, 'Non Smoker', 'No', 'Male', 'Female', 'Hello there', 'Galway', 'Tatooine', 'Jedi', 1, NULL, NULL),
(35, 'Social Smoker', 'Social Drinker', 'Male', 'Female', 'Likes:\n-Pod-racing\n-Lightsabers\n-Padm????\n\nDislikes:\n-Sand\n-Younglings\n-Padm????', 'Dublin', 'Tatooine', 'Jedi', 1, NULL, NULL),
(36, 'Non Smoker', 'Constantly', 'Female', 'Female', 'Anakin > Vader', 'Laois', 'Coruscant', 'Jedi', 1, NULL, NULL),
(37, 'Non Smoker', 'Most days', 'Female', 'Male', '#youngesteverqueenofnaboo', 'Kerry', 'Naboo', 'Senator', 1, NULL, NULL),
(38, 'Non Smoker', 'No', 'Non-Binary', 'All', '*beep* *boop*', 'Mayo', 'Factory', 'Droid', 1, NULL, NULL),
(39, 'Non Smoker', 'No', 'Non-Binary', 'All', 'Sometimes, I just don\'t understand human behaviour.', 'Kilkenny', 'Tatooine', 'Droid', 1, NULL, NULL),
(40, 'Social Smoker', 'Most days', 'Male', 'Male', 'Most unique looking lad out there', 'Kildare', 'Kamino', 'Clone', 1, NULL, NULL),
(41, 'Smoker', 'No', 'Male', 'Female', 'My bio, this is', 'Leitrim', 'Dagobah', 'Jedi', 1, NULL, NULL),
(42, 'Non Smoker', 'Social Drinker', 'Male', 'Female', 'You are on this dating site, but I do not grant you the rank of my girl', 'Fermanagh', 'Coruscant', 'Jedi', 1, NULL, NULL),
(43, 'Non Smoker', 'No', 'Male', 'Female', 'I don\'t believe in chance', 'Clare', 'Coruscant', 'Jedi', 1, NULL, NULL),
(44, 'Smoker', 'No', 'Male', 'Female', '*Heavy Breathing*', 'Wicklow', 'Death Star', 'Sith Lord', 1, NULL, NULL),
(45, 'Non Smoker', 'No', 'Male', 'Female', 'Alabama born \'n\' raised', 'Donegal', 'Tatooine', 'Jedi', 1, NULL, NULL),
(46, 'Smoker', 'Constantly', 'Male', 'Female', 'Mr. Steal your sister', 'Down', 'Tatooine', 'Bounty Hunter/Pilot', 1, NULL, NULL),
(47, 'Non Smoker', 'Social Drinker', 'Female', 'Male', 'Just looking to marry someone with a cool last name', 'Cavan', 'Naboo', 'Princess', 1, NULL, NULL),
(48, 'Non Smoker', 'No', 'Male', 'All', '7\'2\"\nWon\'t shave for you', 'Sligo', 'Kashyyyk', 'Co-Pilot', 1, NULL, NULL),
(49, 'Non Smoker', 'No', 'Male', 'Female', 'Really hope I don\'t run into my dad on this thing', 'Donegal', 'Kamino', 'Bounty Hunter', 1, NULL, NULL),
(50, 'Non Smoker', 'No', 'Male', 'Female', 'Really hope I don\'t run into my son on this', 'Offaly', 'Kamino', 'Bounty Hunter', 1, NULL, NULL),
(51, 'Non Smoker', 'Most days', 'Male', 'Female', 'My aesthetic is goth', 'Longford', 'Naboo', 'Sith', 1, NULL, NULL),
(52, 'Non Smoker', 'Social Drinker', 'Male', 'Female', 'Hi I like reading', 'Laois', NULL, 'compooter', 1, 'UL', 'compooter science'),
(53, 'Smoker', 'Most days', 'Male', 'Female', 'You might want to buckle up, baby', 'Derry', 'Pasaana', 'Businessman', 1, NULL, NULL),
(54, 'Social Smoker', 'No', 'Male', 'Female', 'I have an extra-long blade', 'Cork', 'Dathomir', 'Sith', 1, NULL, NULL),
(55, 'Non Smoker', 'No', 'Male', 'Female', 'There\'s always a bigger fish ;)', 'Monaghan', 'Coruscant', 'Jedi', 1, NULL, NULL),
(56, 'Non Smoker', 'Social Drinker', 'Male', 'Female', '*signature look of superiority*', 'Antrim', 'Serenno', 'Sith Lord', 1, NULL, NULL),
(57, 'Smoker', 'No', 'Male', 'All', 'General Kenobi', 'Tyrone', 'Kalee', 'General', 1, NULL, NULL),
(58, 'Non Smoker', 'Social Drinker', 'Male', 'Female', 'I talk a lot, so I\'ve learned to tune myself out', 'Roscommon', 'Scranton', 'Regional Manager', 1, 'Scotts Totts', NULL),
(59, 'Non Smoker', 'Social Drinker', 'Male', 'Female', 'Whenever I\'m about to do something, I think \'Would an idiot do that?', 'Wexford', 'Scranton', 'Assistant to the Regional Manage', 1, 'Schrute Farms', 'Beet Farming'),
(60, 'Non Smoker', 'Social Drinker', 'Male', 'Female', 'I am about to do something very bold in this job that I????????ve never done before: try.', 'Tipperary', 'Scranton', 'Salesman', 1, NULL, NULL),
(61, 'Non Smoker', 'Social Drinker', 'Female', 'Male', 'Oh God no, Dwight isn????????t my friend??????? Oh my God! Dwight????????s kind of my friend!', 'Longford', 'Scranton', 'Receptionist', 1, 'New York', 'Art'),
(62, 'Non Smoker', 'No', 'Male', 'Female', 'I went to cornell', 'Carlow', 'Scranton', 'Salesman', 1, 'Cornell', NULL),
(63, 'Non Smoker', 'Most days', 'Male', 'Male', 'Me think, why waste time say lot word, when few word do trick?', 'Meath', 'Scranton', 'Accountant', 1, NULL, NULL),
(64, 'Non Smoker', 'No', 'Female', 'Male', 'If you pray enough you can change yourself into a cat person', 'Armagh', 'Scranton', 'Accountant', 1, NULL, NULL),
(65, 'Non Smoker', 'Social Drinker', 'Male', 'Female', 'I have six roommates, which are better than friends because they have to give you one month\'s notice before they leave', 'Louth', 'Scranton', 'HR', 1, NULL, NULL),
(66, 'Social Smoker', 'Constantly', 'Male', 'Female', 'I\'m such a perfectionist that I\'d kinda rather not do it at all than do a crappy version', 'Monaghan', 'Scranton', 'Temp', 1, NULL, NULL),
(67, 'Non Smoker', 'Constantly', 'Male', 'Female', 'If I don\'t have some cake soon, I might die', 'Roscommon', 'Scranton', 'Salesman', 1, 'University of Shove it up your b', NULL),
(68, 'Social Smoker', 'Most days', 'Male', 'All', 'I\'m the *effing* Lizard King', 'Cork', 'Scranton', 'CEO', 1, NULL, NULL),
(69, 'Non Smoker', 'Social Drinker', 'Female', 'Male', 'Who am I? I????????m Kelly Kapoor, the business b*tch', 'Kilkenny', 'Scranton', 'Customer Services', 1, NULL, NULL),
(70, 'Smoker', 'Constantly', 'Female', 'All', 'I just feel lucky that I got a chance to share my crummy story with anyone out there who thinks they\'re the only one to take a dump in a paper shredder. You\'re not alone sister. Let\'s get a beer sometime', 'Down', 'Scranton', 'Supplier Relations', 1, NULL, NULL),
(71, 'Non Smoker', 'Social Drinker', 'Male', 'Female', 'You Need To Access Your Uncrazy Side', 'Derry', 'Scranton', 'Warehouse Manager', 1, NULL, NULL),
(72, 'Social Smoker', 'Most days', 'Female', 'Male', 'When you use a ridiculous font, no one thinks you have a plan', 'Fermanagh', 'Scranton', 'Special Projects Manager', 1, NULL, NULL),
(73, 'Non Smoker', 'No', 'Male', 'Female', 'I\'m Mose', 'Kilkenny', 'Scranton', 'Farmer', 1, NULL, NULL),
(74, 'Non Smoker', 'Social Drinker', 'Male', 'Male', 'I just want you to know you can\'t just say the word bankruptcy and expect anything to happen', 'Leitrim', 'Scranton', 'Accountant', 1, NULL, NULL),
(75, 'Smoker', 'Constantly', 'Male', 'All', 'The only difference between me and a homeless man is this job', 'Tipperary', 'Scranton', 'Quality Assurance', 1, NULL, NULL),
(76, 'Non Smoker', 'Most days', 'Male', 'Female', 'There\'s only one thing I hate more than lying: skim milk', 'Donegal', 'Pawnee', 'Department Director', 1, NULL, NULL),
(77, 'Social Smoker', 'Most days', 'Female', 'Male', 'Prom is nothing but a huge party full of smiling, dancing people enjoying themselves. It\'s literally my worst nightmare. And I hate punch', 'Dublin', 'Pawnee', 'Intern', 1, NULL, NULL),
(78, 'Non Smoker', 'Social Drinker', 'Female', 'Male', 'I don\'t want to be overdramatic, but today felt like a hundred years in hell and the absolute worst day of my life', 'Leitrim', 'Pawnee', 'Councilwoman', 1, NULL, NULL),
(79, 'Non Smoker', 'Social Drinker', 'Male', 'Female', 'I take my shirt off because the bad feelings make me feel sweaty', 'Roscommon', 'Pawnee', 'Show Shiner', 1, NULL, NULL),
(80, 'Non Smoker', 'Social Drinker', 'Male', 'Female', 'They call me the Swiss Army accountant', 'Kerry', 'Pawnee', 'Auditor/Accountant', 1, NULL, NULL),
(81, 'Non Smoker', 'Social Drinker', 'Female', 'Male', 'Okay, because now I have everyone in city hall sending me pictures of their junk asking me if they have mumps', 'Armagh', 'Pawnee', 'Nurse', 1, NULL, NULL),
(82, 'Non Smoker', 'Social Drinker', 'Male', 'Female', 'Zerts are what I call desserts. ???????Tr????e-tr????es???????? are entr????es. I call sandwiches ???????sammies???????? ???????sandoozles???????? or ???????Adam Sandlers???????? Air conditioners are ???????cool blasterz???????? with a ???????z????????. I don????????t know where that came from. I call cakes ???????big ol???????? cookies.???????? I call noodles ???????long-ass rice???????? Fried chicken is ???????fry-fry chicky-chick.???????? Chicken parm is ???????chicky-chicky-parm-parm???????? Chicken cacciatore? ???????Chicky-cacc???????? I call eggs ???????pre-birds???????? or ???????future birds???????? Root beer is ???????super water?????', 'Tyrone', 'Pawnee', 'Businessman', 1, NULL, NULL),
(83, 'Non Smoker', 'No', 'Male', 'Female', 'Well, you know, it????????s like I always say. It ain????????t government work if you don????????t have to do it twice', 'Tipperary', 'Pawnee', 'Government Worker', 1, NULL, NULL),
(84, 'Non Smoker', 'No', 'Male', 'Female', 'If I keep my body moving and my mind occupied at all times, I will avoid falling into a bottomless pit of despair', 'Waterford', 'Pawnee', 'City Manager', 1, NULL, NULL),
(85, 'Social Smoker', 'Most days', 'Female', 'Male', 'Treat yo self', 'Limerick', 'Pawnee', 'Government Worker', 1, NULL, NULL),
(86, 'Smoker', 'No', 'Male', 'Female', 'test', 'Galway', 'Galway', 'Student', 1, NULL, NULL),
(87, 'Smoker', 'Social Drinker', 'Male', 'Female', 'heyo I like student being no', 'Tipperary', NULL, 'I like being employed', 0, NULL, NULL);
-- --------------------------------------------------------
  --
  -- Insert data for Connections into Connections table
  --
INSERT INTO
  connections
VALUES
  (1, 1, 7, '2022-03-22'),
  (2, 1, 2, '2022-02-14'),
  (3, 1, 8, '2022-03-18'),
  (4, 1, 5, '2022-02-08'),
  (5, 1, 9, '2022-03-06'),
  (6, 2, 5, '2022-03-14'),
  (7, 2, 7, '2022-03-18'),
  (8, 2, 9, '2022-03-02'),
  (9, 3, 5, '2022-02-17'),
  (10, 3, 8, '2022-03-23'),
  (11, 3, 6, '2022-03-16'),
  (12, 4, 7, '2022-02-23'),
  (13, 4, 9, '2022-03-02'),
  (14, 4, 5, '2022-03-21');
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
INSERT INTO `interests` (`UserID`, `InterestID`) VALUES
(1, 2),
(1, 5),
(1, 8),
(1, 9),
(2, 4),
(2, 12),
(2, 18),
(3, 4),
(3, 7),
(3, 16),
(3, 17),
(4, 4),
(4, 12),
(4, 17),
(4, 19),
(5, 6),
(5, 8),
(5, 9),
(5, 19),
(6, 12),
(6, 15),
(6, 16),
(6, 18),
(7, 4),
(7, 12),
(7, 15),
(7, 19),
(8, 7),
(8, 15),
(8, 18),
(8, 21),
(27, 1),
(27, 5),
(27, 9),
(27, 13),
(29, 1),
(29, 2),
(29, 11),
(29, 12),
(30, 5),
(30, 9),
(30, 13),
(30, 14),
(31, 1),
(31, 2),
(31, 18),
(31, 22),
(32, 2),
(32, 8),
(32, 15),
(32, 16),
(34, 1),
(34, 4),
(34, 12),
(34, 21),
(35, 6),
(35, 8),
(35, 17),
(35, 18),
(36, 9),
(36, 11),
(36, 12),
(36, 15),
(37, 7),
(37, 11),
(37, 13),
(37, 19),
(38, 2),
(38, 5),
(38, 16),
(38, 20),
(39, 3),
(39, 7),
(39, 8),
(39, 16),
(40, 4),
(40, 5),
(40, 14),
(40, 21),
(41, 4),
(41, 21),
(41, 22),
(41, 23),
(42, 5),
(42, 14),
(42, 21),
(42, 23),
(43, 1),
(43, 3),
(43, 9),
(43, 15),
(44, 1),
(44, 8),
(44, 11),
(44, 17),
(45, 3),
(45, 8),
(45, 13),
(45, 17),
(46, 5),
(46, 9),
(46, 11),
(46, 20),
(47, 3),
(47, 5),
(47, 8),
(47, 19),
(48, 1),
(48, 2),
(48, 10),
(48, 11),
(49, 8),
(49, 14),
(49, 19),
(49, 21),
(50, 1),
(50, 6),
(50, 13),
(50, 17),
(51, 14),
(51, 15),
(51, 18),
(51, 21),
(52, 15),
(53, 1),
(53, 4),
(53, 11),
(53, 22),
(54, 12),
(54, 16),
(54, 18),
(54, 23),
(55, 3),
(55, 19),
(55, 20),
(55, 21),
(56, 10),
(56, 16),
(56, 17),
(56, 22),
(57, 5),
(57, 8),
(57, 12),
(57, 19),
(58, 4),
(58, 14),
(58, 18),
(58, 23),
(59, 1),
(59, 5),
(59, 9),
(59, 13),
(60, 1),
(60, 9),
(60, 17),
(60, 19),
(61, 5),
(61, 11),
(61, 15),
(61, 19),
(62, 1),
(62, 3),
(62, 4),
(62, 22),
(63, 12),
(63, 14),
(63, 21),
(63, 23),
(64, 9),
(64, 15),
(64, 18),
(64, 20),
(65, 8),
(65, 15),
(65, 17),
(65, 20),
(66, 6),
(66, 7),
(66, 13),
(66, 15),
(67, 4),
(67, 15),
(67, 17),
(67, 18),
(68, 4),
(68, 17),
(68, 22),
(69, 5),
(69, 8),
(69, 13),
(69, 18),
(70, 5),
(70, 15),
(70, 17),
(70, 20),
(71, 5),
(71, 7),
(71, 9),
(71, 16),
(72, 1),
(72, 4),
(72, 5),
(72, 13),
(73, 2),
(73, 8),
(73, 17),
(74, 1),
(74, 15),
(74, 20),
(74, 22),
(75, 1),
(75, 6),
(75, 16),
(75, 22),
(76, 4),
(76, 21),
(76, 22),
(76, 23),
(77, 9),
(77, 14),
(77, 19),
(77, 20),
(78, 10),
(78, 18),
(78, 20),
(78, 22),
(79, 6),
(79, 10),
(79, 16),
(79, 18),
(80, 9),
(80, 10),
(80, 16),
(81, 5),
(81, 17),
(81, 18),
(81, 19),
(82, 7),
(82, 11),
(82, 15),
(82, 18),
(83, 7),
(83, 8),
(83, 16),
(83, 22),
(84, 2),
(84, 5),
(84, 12),
(84, 23),
(85, 6),
(85, 8),
(85, 13),
(85, 19),
(86, 9),
(86, 13),
(86, 15),
(86, 21),
(87, 4),
(87, 8),
(87, 9),
(87, 11),
(88, 1),
(88, 4),
(88, 11),
(88, 15);

-- --------------------------------------------------------
  --
  -- insert data for table `securityqa`
  --

INSERT INTO `securityqa` (`UserID`, `SecurityQuestion`, `SecurityAnswer`) VALUES
(30, 'Best friends name', 'Grainne'),
(30, 'Mothers maiden name', 'Knightly'),
(34, 'First pets name', 'Anakin'),
(34, 'Favourite teacher', 'Yoda'),
(35, 'First pets name', 'R2D2'),
(35, 'Mothers maiden name', 'Skywalker'),
(36, 'Best friends name', 'Anakin'),
(36, 'Favourite teacher', 'Anakin'),
(37, 'First pets name', 'C-3PO'),
(37, 'Mothers maiden name', 'Amidala'),
(38, 'First pets name', 'C-3P0'),
(38, 'Best friends name', 'C-3P0'),
(39, 'First pets name', 'R2D2'),
(39, 'Best friends name', 'R2D2'),
(40, 'Favourite teacher', 'Anakin'),
(40, 'Best friends name', 'Cody'),
(41, 'Best friends name', 'Luke'),
(41, 'First pets name', 'Anakin'),
(42, 'First pets name', 'Anakin'),
(42, 'Best friends name', 'Yoda'),
(43, 'First school', 'Jedi Training'),
(43, 'Mothers maiden name', 'Koon'),
(44, 'First pets name', 'Obi-Wan'),
(44, 'Mothers maiden name', 'Skywalker'),
(45, 'First pets name', 'R2D2'),
(45, 'Favourite teacher', 'Obi-Wan'),
(46, 'First pets name', 'Chewbacca'),
(46, 'Best friends name', 'Chewbacca'),
(47, 'First pets name', 'R2D2'),
(47, 'Best friends name', 'Han Solo'),
(48, 'Best friends name', 'Han Solo'),
(48, 'Favourite teacher', 'Yoda'),
(49, 'Favourite teacher', 'Jango Fett'),
(50, 'Best friends name', 'Boba Fett'),
(50, 'Mothers maiden name', 'Fett'),
(51, 'Favourite teacher', 'Han Solo'),
(51, 'Mothers maiden name', 'Skywalker'),
(53, 'Best friends name', 'Han Solo'),
(53, 'Mothers maiden name', 'Calrissian'),
(54, 'Best friends name', 'Obi-Wan'),
(54, 'Mothers maiden name', 'Maul'),
(55, 'Favourite teacher', 'Yoda'),
(55, 'Best friends name', 'Obi-Wan'),
(56, 'Favourite teacher', 'Darth Sidious'),
(56, 'First pets name', 'Yoda'),
(57, 'First pets name', 'Obi-Wan'),
(58, 'Best friends name', 'Dwight'),
(58, 'Mothers maiden name', 'Scott'),
(59, 'Mothers maiden name', 'Schrute'),
(60, 'Favourite teacher', 'Michael Scott'),
(60, 'Best friends name', 'Dwight Schrute'),
(61, 'Best friends name', 'Jim Halpert'),
(62, 'Best friends name', 'Darryl'),
(64, 'Best friends name', 'Cats'),
(65, 'Best friends name', 'Pam'),
(65, 'Mothers maiden name', 'Flenderson'),
(73, 'Best friends name', 'Dwight');

-- --------------------------------------------------------
  --
  -- insert data for table `liked`
  --

  INSERT INTO `liked` (`UserID1`, `UserID2`, `LikedDate`) VALUES
  (5, 3, '2022-04-17'),
  (5, 2, '2022-04-17'),
  (5, 7, '2022-04-17'),
  (5, 11, '2022-04-17'),
  (5, 9, '2022-04-17'),
  (5, 14, '2022-04-17'),
  (5, 19, '2022-04-17'),
  (5, 17, '2022-04-17'),
  (52, 36, '2022-04-21');

  /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
  /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
  /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;