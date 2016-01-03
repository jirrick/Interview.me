-- Adminer 4.2.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `mena`;
CREATE TABLE `mena` (
  `id_mena` int(11) NOT NULL AUTO_INCREMENT,
  `nazev` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  `kod_meny` varchar(10) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id_mena`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `pokrocile_informace`;
CREATE TABLE `pokrocile_informace` (
  `id_pokrocile_informace` int(11) NOT NULL AUTO_INCREMENT,
  `datum_pohovoru` int(11) DEFAULT NULL,
  `vzdelani` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  `motivace` text COLLATE utf8_czech_ci,
  `preferovana_prace` text COLLATE utf8_czech_ci,
  `ambice` text COLLATE utf8_czech_ci,
  `jazyky` varchar(200) COLLATE utf8_czech_ci DEFAULT NULL,
  `cestovani` varchar(200) COLLATE utf8_czech_ci DEFAULT NULL,
  `plusy_minusy` text COLLATE utf8_czech_ci,
  `zkusenosti_v_tymu` text COLLATE utf8_czech_ci,
  `pracovni_misto` text COLLATE utf8_czech_ci,
  `knowhow` text COLLATE utf8_czech_ci,
  `dalsi_informace` text COLLATE utf8_czech_ci,
  `shrnuti_pohovoru` text COLLATE utf8_czech_ci,
  `idealni_pozice` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  `datum_zahajeni` date DEFAULT NULL,
  `mzda` int(11) DEFAULT NULL,
  `datum_pristiho_kontaktu` int(11) DEFAULT NULL,
  `id_uvazek` int(11) DEFAULT NULL,
  `id_mena` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_pokrocile_informace`),
  KEY `id_uvazek` (`id_uvazek`),
  KEY `id_mena` (`id_mena`),
  CONSTRAINT `pokrocile_informace_ibfk_3` FOREIGN KEY (`id_uvazek`) REFERENCES `uvazek` (`id_uvazek`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `pokrocile_informace_ibfk_4` FOREIGN KEY (`id_mena`) REFERENCES `mena` (`id_mena`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `uvazek`;
CREATE TABLE `uvazek` (
  `id_uvazek` int(11) NOT NULL AUTO_INCREMENT,
  `nazev` varchar(100) CHARACTER SET utf16 COLLATE utf16_czech_ci NOT NULL,
  PRIMARY KEY (`id_uvazek`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `uzivatel_pokrocile_informace`;
CREATE TABLE `uzivatel_pokrocile_informace` (
  `id_uzivatel` int(11) NOT NULL,
  `id_pokrocile_informace` int(11) NOT NULL,
  KEY `id_uzivatel` (`id_uzivatel`),
  KEY `id_pokrocile_informace` (`id_pokrocile_informace`),
  CONSTRAINT `uzivatel_pokrocile_informace_ibfk_3` FOREIGN KEY (`id_uzivatel`) REFERENCES `uzivatel` (`id_uzivatel`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `uzivatel_pokrocile_informace_ibfk_4` FOREIGN KEY (`id_pokrocile_informace`) REFERENCES `pokrocile_informace` (`id_pokrocile_informace`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


ALTER TABLE `kandidat`
ADD `id_pokrocile_informace` int(11) NULL,
ADD FOREIGN KEY (`id_pokrocile_informace`) REFERENCES `pokrocile_informace` (`id_pokrocile_informace`);

ALTER TABLE `uzivatel_pokrocile_informace`
RENAME TO `perzonalista_pokrocile_informace`;

ALTER TABLE `pokrocile_informace`
CHANGE `datum_pohovoru` `datum_pohovoru` date NULL AFTER `id_pokrocile_informace`,
CHANGE `datum_pristiho_kontaktu` `datum_pristiho_kontaktu` date NULL AFTER `mzda`;

ALTER TABLE `perzonalista_pokrocile_informace`
ADD `id_perzonalista_pokrocile_informace` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY;

ALTER TABLE `pokrocile_informace`
CHANGE `vzdelani` `vzdelani` varchar(200) COLLATE 'utf8_czech_ci' NULL AFTER `datum_pohovoru`,
CHANGE `idealni_pozice` `idealni_pozice` varchar(200) COLLATE 'utf8_czech_ci' NULL AFTER `shrnuti_pohovoru`;

-- 2015-12-30 09:19:02