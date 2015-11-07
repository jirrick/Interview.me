-- Adminer 4.2.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `names`;
CREATE TABLE `names` (
  `name` varchar(250) COLLATE utf8_czech_ci NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `names` (`name`, `id`) VALUES
('Jedna',	1),
('Dva',	2),
('Tři',	3);

-- 2015-11-07 10:26:23