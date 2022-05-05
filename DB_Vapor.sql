-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2022 at 07:25 PM
-- Server version: 10.5.13-MariaDB-log
-- PHP Version: 8.0.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;

CREATE SCHEMA IF NOT EXISTS `DB_Vapor` DEFAULT CHARACTER SET utf8 ;
USE `DB_Vapor` ;
SET time_zone = "+00:00";

--
-- Database: `DB_Vapor`
--


DELIMITER $$
--
-- Procedures
--
CREATE PROCEDURE `GetAchievementsFromAccount` (IN `AccountId` INT)  SELECT
    achievement_tbl.*
FROM
    achievementsPerAccount_tbl
JOIN achievement_tbl ON achievement_tbl.Id = achievementsPerAccount_tbl.achievement_Id
WHERE
    achievementsPerAccount_tbl.account_Id = AccountId$$

CREATE PROCEDURE `GetAchievementsFromGame` (IN `GameId` INT)  Select * FROM achievement_tbl where game_id = GameId$$

CREATE PROCEDURE `GetGamesFromAccount` (IN `AccountId` INT)  SELECT game_tbl.* FROM gamesPerAccount_tbl JOIN game_tbl ON game_tbl.Id = gamesPerAccount_tbl.game_Id WHERE gamesPerAccount_tbl.account_Id = AccountId$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `account_tbl`
--

CREATE TABLE `account_tbl` (
  `Id` int(11) NOT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(200) NOT NULL,
  `isAdmin` tinyint(4) NOT NULL,
  `firstName` varchar(45) NOT NULL,
  `lastName` varchar(45) NOT NULL,
  `Email` varchar(45) NOT NULL,
  `isBanned` tinyint(1) NOT NULL,
  `biography` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `account_tbl`
--

INSERT INTO `account_tbl` (`Id`, `username`, `password`, `isAdmin`, `firstName`, `lastName`, `Email`, `isBanned`, `biography`) VALUES
(1, 'god', '$argon2i$v=19$m=1024,t=2,p=2$c3J4Yml5Y1cvbmxCYlpTeA$BMK1rfb4L3nbcubpXwoPqx8Zrgfq09zdRFvb4wAi3y0', 1, 'god', 'god', 'god@shitmail.me', 0, 'The one and only gaben'),
(2, 'satan', '$argon2i$v=19$m=1024,t=2,p=2$TE9aOE5YUFppVE1Kc2h4Yw$PR8C8kliT6ORJdGXZw2fsFmt5xImIjMdo/XVhdImsso', 1, 'satan', 'satan', 'satan@shitmail.me', 1, ''),
(3, 'test', '$argon2i$v=19$m=1024,t=2,p=2$VW9lUVRhNXdDVFJEWWF5Nw$Gur9pwruXHhAx+jpjbew/g67Xcmj0iHK57F4i+RPeng', 0, 'test', 'test', 'test@shitmail.me', 0, ''),
(4, 'dog', '$argon2i$v=19$m=1024,t=2,p=2$M2dnaVo1QU9xUWovWUdxbQ$L2D/f/iXaSO/2YUESdmeaPoRf1C9Fub8nBTZ/6XZDLI', 0, 'dog', 'dog', 'dog@shitmail.me', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `achievementsPerAccount_tbl`
--

CREATE TABLE `achievementsPerAccount_tbl` (
  `account_Id` int(11) NOT NULL,
  `achievement_Id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `achievementsPerAccount_tbl`
--

INSERT INTO `achievementsPerAccount_tbl` (`account_Id`, `achievement_Id`) VALUES
(1, 1),
(1, 2),
(3, 1),
(4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `achievement_tbl`
--

CREATE TABLE `achievement_tbl` (
  `Id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `description` varchar(255) NOT NULL,
  `thumbnail` varchar(45) NOT NULL,
  `game_Id` int(11) NOT NULL,
  `isDisabled` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `achievement_tbl`
--

INSERT INTO `achievement_tbl` (`Id`, `name`, `description`, `thumbnail`, `game_Id`, `isDisabled`) VALUES
(1, 'play as spee', 'you are must play as spee to get this', 'spee_thumbnail.jpg', 3, 0),
(2, 'peeros', 'play as peeros', 'peero_thumbnail.jpg', 3, 1),
(8, 'onoi', 'onio', 'onnion.jpg', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gamesPerAccount_tbl`
--

CREATE TABLE `gamesPerAccount_tbl` (
  `account_Id` int(11) NOT NULL,
  `game_Id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gamesPerAccount_tbl`
--

INSERT INTO `gamesPerAccount_tbl` (`account_Id`, `game_Id`) VALUES
(1, 1),
(1, 3),
(2, 3),
(3, 1),
(3, 3),
(4, 1),
(4, 3);

-- --------------------------------------------------------

--
-- Table structure for table `game_tbl`
--

CREATE TABLE `game_tbl` (
  `Id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `price` float NOT NULL,
  `thumbnail` varchar(45) NOT NULL,
  `description` varchar(500) NOT NULL,
  `releaseDate` date NOT NULL,
  `isDisabled` tinyint(4) NOT NULL,
  `downloadLink` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `game_tbl`
--

INSERT INTO `game_tbl` (`Id`, `name`, `price`, `thumbnail`, `description`, `releaseDate`, `isDisabled`, `downloadLink`) VALUES
(1, 'dora 2', 0, 'dora2_thumbnail.jpg', 'Cool game', '2022-04-18', 0, 'https://www.youtube.com/watch?v=VjGSMUep6_4'),
(3, 'team fortnight 2', 40, 'tf2_thumbnail.jpg', 'best game for fortnights', '2022-04-05', 0, 'https://www.merriam-webster.com/dictionary/fortnight'),
(5, 'CS:COME', 200, 'cscome.jpg', 'Counter Strike: Cannot Outrun Musk Elon', '2022-04-04', 0, 'https://www.twitter.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_tbl`
--
ALTER TABLE `account_tbl`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `achievementsPerAccount_tbl`
--
ALTER TABLE `achievementsPerAccount_tbl`
  ADD PRIMARY KEY (`account_Id`,`achievement_Id`),
  ADD KEY `achievement_Id_Idx` (`achievement_Id`),
  ADD KEY `account_achievement_Id_Idx` (`account_Id`);

--
-- Indexes for table `achievement_tbl`
--
ALTER TABLE `achievement_tbl`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `game_achievement_Id_Idx` (`game_Id`);

--
-- Indexes for table `gamesPerAccount_tbl`
--
ALTER TABLE `gamesPerAccount_tbl`
  ADD PRIMARY KEY (`account_Id`,`game_Id`),
  ADD KEY `game_game_Id_Idx` (`game_Id`),
  ADD KEY `account_game_Id_Idx` (`account_Id`);

--
-- Indexes for table `game_tbl`
--
ALTER TABLE `game_tbl`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_tbl`
--
ALTER TABLE `account_tbl`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `achievement_tbl`
--
ALTER TABLE `achievement_tbl`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `game_tbl`
--
ALTER TABLE `game_tbl`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `achievementsPerAccount_tbl`
--
ALTER TABLE `achievementsPerAccount_tbl`
  ADD CONSTRAINT `account_achievement_Id_Idx` FOREIGN KEY (`account_Id`) REFERENCES `account_tbl` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `achievement_Id_Idx` FOREIGN KEY (`achievement_Id`) REFERENCES `achievement_tbl` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `achievement_tbl`
--
ALTER TABLE `achievement_tbl`
  ADD CONSTRAINT `game_achievement_Id_Idx` FOREIGN KEY (`game_Id`) REFERENCES `game_tbl` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `gamesPerAccount_tbl`
--
ALTER TABLE `gamesPerAccount_tbl`
  ADD CONSTRAINT `account_game_Id_Idx` FOREIGN KEY (`account_Id`) REFERENCES `account_tbl` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `game_game_Id_Idx` FOREIGN KEY (`game_Id`) REFERENCES `game_tbl` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;
