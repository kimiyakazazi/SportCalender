-- Adminer 4.7.8 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP DATABASE IF EXISTS `SportsCalendar`;
CREATE DATABASE `SportsCalendar` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `SportsCalendar`;

DROP TABLE IF EXISTS `Category`;
CREATE TABLE `Category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `Category` (`id`, `name`) VALUES
(1,	'Football'),
(2,	'Ice hockey'),
(3,	'Basketball'),
(4,	'Ski'),
(5,	'Tennis');

DROP TABLE IF EXISTS `Event`;
CREATE TABLE `Event` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `_id_first_team` int NOT NULL,
  `_id_second_team` int NOT NULL,
  `_id_Category` int NOT NULL,
  `_id_location` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `_id_first_team` (`_id_first_team`),
  KEY `_id_second_team` (`_id_second_team`),
  KEY `_id_Category` (`_id_Category`),
  KEY `_id_location` (`_id_location`),
  CONSTRAINT `Event_ibfk_1` FOREIGN KEY (`_id_first_team`) REFERENCES `Team` (`id`),
  CONSTRAINT `Event_ibfk_2` FOREIGN KEY (`_id_second_team`) REFERENCES `Team` (`id`),
  CONSTRAINT `Event_ibfk_3` FOREIGN KEY (`_id_Category`) REFERENCES `Category` (`id`),
  CONSTRAINT `Event_ibfk_4` FOREIGN KEY (`_id_location`) REFERENCES `Location` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `Event` (`id`, `date`, `time`, `_id_first_team`, `_id_second_team`, `_id_Category`, `_id_location`) VALUES
(1,	'2021-01-01',	'17:04:00',	3,	4,	2,	3),
(2,	'2021-01-02',	'16:44:00',	3,	5,	2,	2),
(3,	'2021-01-03',	'17:48:00',	2,	5,	1,	3),
(4,	'2021-01-03',	'20:03:00',	5,	3,	2,	3),
(5,	'2021-01-04',	'19:10:00',	1,	9,	4,	2);

DROP VIEW IF EXISTS `EventView`;
CREATE TABLE `EventView` (`ID` int, `_id_category` int, `category` varchar(50), `_id_location` int, `location` varchar(50), `_id_first_team` int, `first_team` varchar(50), `_id_second_team` int, `second_team` varchar(50), `Date` date, `Time` time);


DROP TABLE IF EXISTS `Location`;
CREATE TABLE `Location` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `Location` (`id`, `name`) VALUES
(1,	'Vienna'),
(2,	'Iran'),
(3,	'Los Angeles');

DROP TABLE IF EXISTS `Team`;
CREATE TABLE `Team` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `Team` (`id`, `name`) VALUES
(1,	' FC'),
(2,	' Fire SC'),
(3,	'Houston'),
(4,	'Esteghlal'),
(5,	'Perspolis'),
(6,	'KAC'),
(7,	'Strum'),
(8,	'Salzburg'),
(9,	'Capital');

DROP TABLE IF EXISTS `EventView`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `EventView` AS select `Event`.`id` AS `ID`,`Category`.`id` AS `_id_category`,`Category`.`name` AS `category`,`Location`.`id` AS `_id_location`,`Location`.`name` AS `location`,`Team_1`.`id` AS `_id_first_team`,`Team_1`.`name` AS `first_team`,`Team`.`id` AS `_id_second_team`,`Team`.`name` AS `second_team`,`Event`.`date` AS `Date`,`Event`.`time` AS `Time` from ((((`Event` left join `Team` on((`Team`.`id` = `Event`.`_id_second_team`))) left join `Category` on((`Event`.`_id_Category` = `Category`.`id`))) left join `Location` on((`Event`.`_id_location` = `Location`.`id`))) left join `Team` `Team_1` on((`Event`.`_id_first_team` = `Team_1`.`id`)));

-- 2021-01-01 16:13:05
