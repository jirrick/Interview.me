SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `zprava`;
CREATE TABLE `zprava` (
  `id_zprava` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(2000) COLLATE utf8_czech_ci NOT NULL,
  `id_uzivatel` int(11) NOT NULL,
  `id_kandidat` int(11) NOT NULL,
  `datum_vytvoreni` datetime NOT NULL,
  PRIMARY KEY (`id_zprava`),
  KEY `id_uzivatel` (`id_uzivatel`),
  KEY `id_kandidat` (`id_kandidat`),
  CONSTRAINT `zprava_ibfk_2` FOREIGN KEY (`id_uzivatel`) REFERENCES `uzivatel` (`id_uzivatel`),
  CONSTRAINT `zprava_ibfk_3` FOREIGN KEY (`id_kandidat`) REFERENCES `kandidat` (`id_kandidat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

