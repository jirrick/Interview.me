-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 15, 2015 at 12:17 PM
-- Server version: 5.1.73
-- PHP Version: 5.3.3-7+squeeze19

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `zs2015_1`
--

-- --------------------------------------------------------

--
-- Table structure for table `fotografie`
--

DROP TABLE IF EXISTS `fotografie`;
CREATE TABLE IF NOT EXISTS `fotografie` (
  `id_foto` int(11) NOT NULL AUTO_INCREMENT,
  `foto` mediumblob NOT NULL,
  `nazev` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`id_foto`),
  UNIQUE KEY `UQ_fotografie_id_foto` (`id_foto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=9 ;

---------------------------------------------------------

--
-- Table structure for table `kandidat`
--

DROP TABLE IF EXISTS `kandidat`;
CREATE TABLE IF NOT EXISTS `kandidat` (
  `id_kandidat` int(11) NOT NULL AUTO_INCREMENT,
  `jmeno` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `prijmeni` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `datum_narozeni` date DEFAULT NULL,
  `id_pozice` int(11) NOT NULL,
  `id_seniorita` int(11) NOT NULL,
  `komentar` text COLLATE utf8_czech_ci,
  `id_status` int(11) NOT NULL,
  `datum_aktualizace` date NOT NULL,
  `datum_pohovoru` date NOT NULL,
  `id_foto` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_kandidat`),
  UNIQUE KEY `UQ_kandidat_id_kandidat` (`id_kandidat`),
  KEY `id_foto` (`id_foto`),
  KEY `id_pozice` (`id_pozice`),
  KEY `id_seniorita` (`id_seniorita`),
  KEY `id_status` (`id_status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `kandidat_priloha`
--

DROP TABLE IF EXISTS `kandidat_priloha`;
CREATE TABLE IF NOT EXISTS `kandidat_priloha` (
  `id_kandidat_priloha` int(11) NOT NULL AUTO_INCREMENT,
  `id_priloha` int(11) NOT NULL,
  `id_kandidat` int(11) NOT NULL,
  PRIMARY KEY (`id_kandidat_priloha`),
  UNIQUE KEY `UQ_kandidat_priloha_id_kandidat_priloha` (`id_kandidat_priloha`),
  KEY `id_kandidat` (`id_kandidat`),
  KEY `id_priloha` (`id_priloha`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `kandidat_technologie`
--

DROP TABLE IF EXISTS `kandidat_technologie`;
CREATE TABLE IF NOT EXISTS `kandidat_technologie` (
  `id_kandidat_technologie` int(11) NOT NULL AUTO_INCREMENT,
  `id_kandidat` int(11) NOT NULL,
  `id_technologie` int(11) NOT NULL,
  PRIMARY KEY (`id_kandidat_technologie`),
  UNIQUE KEY `UQ_kandidat_technologie_id_kandidat_technologie` (`id_kandidat_technologie`),
  KEY `id_kandidat` (`id_kandidat`),
  KEY `id_technologie` (`id_technologie`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=69 ;

-- --------------------------------------------------------

--
-- Table structure for table `moznost`
--

DROP TABLE IF EXISTS `moznost`;
CREATE TABLE IF NOT EXISTS `moznost` (
  `id_moznost` int(11) NOT NULL AUTO_INCREMENT,
  `obsah` text COLLATE utf8_czech_ci NOT NULL,
  `komentar` text COLLATE utf8_czech_ci,
  `id_otazka` int(11) DEFAULT NULL,
  `spravnost` tinyint(1) NOT NULL,
  `revize` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_moznost`),
  UNIQUE KEY `UQ_moznost_id_moznost` (`id_moznost`),
  KEY `id_otazka` (`id_otazka`),
  KEY `FK_moznost_revize` (`revize`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=99 ;

-- --------------------------------------------------------

--
-- Table structure for table `odpoved`
--

DROP TABLE IF EXISTS `odpoved`;
CREATE TABLE IF NOT EXISTS `odpoved` (
  `id_vyplnena_odpoved` int(11) NOT NULL AUTO_INCREMENT,
  `id_prirazeny_test` int(11) NOT NULL,
  `id_moznost` int(11) NOT NULL,
  `vyplneno` tinyint(1) NOT NULL,
  `slovni_odpoved` text COLLATE utf8_czech_ci,
  `id_otazka` int(11) DEFAULT NULL,
  `spravne` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id_vyplnena_odpoved`),
  UNIQUE KEY `UQ_vyplnena_odpoved_id_vyplnena_odpoved` (`id_vyplnena_odpoved`),
  KEY `id_moznost` (`id_moznost`),
  KEY `id_otazka` (`id_otazka`),
  KEY `id_prirazeny_test` (`id_prirazeny_test`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=45 ;

-- --------------------------------------------------------

--
-- Table structure for table `otazka`
--

DROP TABLE IF EXISTS `otazka`;
CREATE TABLE IF NOT EXISTS `otazka` (
  `id_otazka` int(11) NOT NULL AUTO_INCREMENT,
  `obsah` text COLLATE utf8_czech_ci NOT NULL,
  `komentar` text COLLATE utf8_czech_ci,
  `id_test` int(11) DEFAULT NULL,
  `revize` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_otazka`),
  UNIQUE KEY `UQ_otazka_id_otazka` (`id_otazka`),
  KEY `id_test` (`id_test`),
  KEY `FK_otazka_revize` (`revize`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Table structure for table `pozice`
--

DROP TABLE IF EXISTS `pozice`;
CREATE TABLE IF NOT EXISTS `pozice` (
  `id_pozice` int(11) NOT NULL AUTO_INCREMENT,
  `nazev` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `popis` text COLLATE utf8_czech_ci,
  PRIMARY KEY (`id_pozice`),
  UNIQUE KEY `UQ_pozice_id_pozice` (`id_pozice`),
  UNIQUE KEY `UQ_pozice_nazev` (`nazev`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `pozice`
--

INSERT INTO `pozice` (`id_pozice`, `nazev`, `popis`) VALUES
(1, 'Developer', ''),
(2, 'Tester', ''),
(3, 'Manager', ''),
(4, 'Assistant', '');

-- --------------------------------------------------------

--
-- Table structure for table `priloha`
--

DROP TABLE IF EXISTS `priloha`;
CREATE TABLE IF NOT EXISTS `priloha` (
  `id_priloha` int(11) NOT NULL AUTO_INCREMENT,
  `priloha` mediumblob NOT NULL,
  `nazev` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`id_priloha`),
  UNIQUE KEY `UQ_priloha_id_priloha` (`id_priloha`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `prirazeny_test`
--

DROP TABLE IF EXISTS `prirazeny_test`;
CREATE TABLE IF NOT EXISTS `prirazeny_test` (
  `id_prirazeny_test` int(11) NOT NULL AUTO_INCREMENT,
  `id_test` int(11) NOT NULL,
  `id_kandidat` int(11) NOT NULL,
  `odkaz` varchar(24) COLLATE utf8_czech_ci NOT NULL,
  `id_status` int(11) NOT NULL,
  `hodnoceni` int(11) DEFAULT NULL,
  `otevren` tinyint(1) DEFAULT NULL,
  `datum_prirazeni` date NOT NULL,
  `datum_zahajeni` datetime DEFAULT NULL,
  `datum_vyplneni` datetime DEFAULT NULL,
  `komentar` text COLLATE utf8_czech_ci,
  `id_kdo_priradil` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_prirazeny_test`),
  UNIQUE KEY `UQ_prirazeny_test_id_prirazeny_test` (`id_prirazeny_test`),
  KEY `id_kandidat` (`id_kandidat`),
  KEY `id_status` (`id_status`),
  KEY `id_test` (`id_test`),
  KEY `id_kdo_priradil` (`id_kdo_priradil`),
  KEY `odkaz` (`odkaz`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

--
-- Table structure for table `seniorita`
--

DROP TABLE IF EXISTS `seniorita`;
CREATE TABLE IF NOT EXISTS `seniorita` (
  `id_seniorita` int(11) NOT NULL AUTO_INCREMENT,
  `nazev` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `popis` text COLLATE utf8_czech_ci,
  PRIMARY KEY (`id_seniorita`),
  UNIQUE KEY `UQ_seniorita_id_seniorita` (`id_seniorita`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `seniorita`
--

INSERT INTO `seniorita` (`id_seniorita`, `nazev`, `popis`) VALUES
(1, 'Junior', ''),
(2, 'Middle', ''),
(3, 'Senior', '');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
CREATE TABLE IF NOT EXISTS `status` (
  `id_status` int(11) NOT NULL AUTO_INCREMENT,
  `kod` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `nazev` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `popis` text COLLATE utf8_czech_ci,
  PRIMARY KEY (`id_status`),
  UNIQUE KEY `UQ_status_id_status` (`id_status`),
  UNIQUE KEY `UQ_status_kod` (`kod`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id_status`, `kod`, `nazev`, `popis`) VALUES
(1, 'INVITED', 'Invited to interview', ''),
(2, 'WAITING', 'Waiting for response', ''),
(3, 'ACCEPTED', 'Accepted', ''),
(4, 'REJECTED', 'Rejcted', ''),
(5, 'HIRED', 'Hired', ''),
(6, 'DECLINED', 'Offer declined', ''),
(7, 'ASSIGNED', 'Assigned', ''),
(8, 'SUBMITTED', 'Submitted', ''),
(9, 'EVALUATED', 'Evaluated', '');

-- --------------------------------------------------------

--
-- Table structure for table `technologie`
--

DROP TABLE IF EXISTS `technologie`;
CREATE TABLE IF NOT EXISTS `technologie` (
  `id_technologie` int(11) NOT NULL AUTO_INCREMENT,
  `nazev` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `popis` text COLLATE utf8_czech_ci,
  PRIMARY KEY (`id_technologie`),
  UNIQUE KEY `UQ_technologie_id_technologie` (`id_technologie`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=16 ;

--
-- Dumping data for table `technologie`
--

INSERT INTO `technologie` (`id_technologie`, `nazev`, `popis`) VALUES
(1, '.NET', ''),
(2, 'JAVA', ''),
(3, 'Javascript', ''),
(4, 'iOS', ''),
(5, 'Android', ''),
(6, 'C++', ''),
(7, 'PHP', ''),
(8, 'Drupal', ''),
(9, 'CAD', ''),
(10, 'UI/UX', ''),
(11, 'QA', ''),
(12, 'PM', ''),
(13, 'Marketing', ''),
(14, 'Sales', ''),
(15, 'Administration', '');

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

DROP TABLE IF EXISTS `test`;
CREATE TABLE IF NOT EXISTS `test` (
  `id_test` int(11) NOT NULL AUTO_INCREMENT,
  `nazev` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `id_technologie` int(11) NOT NULL,
  `popis` text COLLATE utf8_czech_ci,
  `datum_vytvoreni` date NOT NULL,
  `id_kdo_vytvoril` int(11) NOT NULL,
  `pocet_minut` int(11) NOT NULL,
  `pocet_otazek` int(11) DEFAULT NULL,
  `id_seniorita` int(11) NOT NULL,
  PRIMARY KEY (`id_test`),
  UNIQUE KEY `UQ_test_id_test` (`id_test`),
  KEY `id_technologie` (`id_technologie`),
  KEY `id_kdo_vytvoril` (`id_kdo_vytvoril`),
  KEY `id_seniorita` (`id_seniorita`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `uzivatel`
--

DROP TABLE IF EXISTS `uzivatel`;
CREATE TABLE IF NOT EXISTS `uzivatel` (
  `id_uzivatel` int(11) NOT NULL AUTO_INCREMENT,
  `jmeno` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `prijmeni` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `heslo` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `id_fotografie` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_uzivatel`),
  UNIQUE KEY `UQ_uzivatel_email` (`email`),
  UNIQUE KEY `UQ_uzivatel_id_uzivatel` (`id_uzivatel`),
  KEY `id_fotografie` (`id_fotografie`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `uzivatel`
--

INSERT INTO `uzivatel` (`id_uzivatel`, `jmeno`, `prijmeni`, `email`, `heslo`, `admin`, `id_fotografie`) VALUES
(1, 'Jan', 'Hudák', 'honza.hudak@gmail.com', 'd79ec596994bd5ca1879ba7d6a7cf490f0d9376f', 1, NULL),
(2, 'Anatoliy', 'Kybkalo', 'kybkalo.anatoliy@gmail.com', 'e5d4d60c942b0aa48ac657c0a0129536ef55db92', 1, NULL),
(3, 'Jirka', 'Hudec', 'jirrick@outlook.cz', 'cb8d3a7f8e18c504d1c27939b868fb45b8004692', 1, NULL),
(4, 'Filip', 'Fabišík', 'filip.fabisik@gmail.com', 'f8a6c4744156928c34cc7f295b2bdd28a7548c5a', 1, NULL),
(5, 'j', 'c', 'jcislinsky@gmail.com', '98a54f5ae7ad07e93d060be2e723c774b2b38f80', 1, NULL),
(6, 'Jan', 'Černý', 'test@test.cz', 'd79ec596994bd5ca1879ba7d6a7cf490f0d9376f', 1, NULL),

-- --------------------------------------------------------

--
-- Table structure for table `zprava`
--

DROP TABLE IF EXISTS `zprava`;
CREATE TABLE IF NOT EXISTS `zprava` (
  `id_zprava` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(2000) COLLATE utf8_czech_ci NOT NULL,
  `id_uzivatel` int(11) NOT NULL,
  `id_kandidat` int(11) NOT NULL,
  `datum_vytvoreni` datetime NOT NULL,
  PRIMARY KEY (`id_zprava`),
  KEY `id_uzivatel` (`id_uzivatel`),
  KEY `id_kandidat` (`id_kandidat`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=5 ;

-- -------------------------------------------------------

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kandidat`
--
ALTER TABLE `kandidat`
  ADD CONSTRAINT `FK_kandidat_fotografie` FOREIGN KEY (`id_foto`) REFERENCES `fotografie` (`id_foto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_kandidat_pozice` FOREIGN KEY (`id_pozice`) REFERENCES `pozice` (`id_pozice`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_kandidat_seniorita` FOREIGN KEY (`id_seniorita`) REFERENCES `seniorita` (`id_seniorita`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_kandidat_status` FOREIGN KEY (`id_status`) REFERENCES `status` (`id_status`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kandidat_priloha`
--
ALTER TABLE `kandidat_priloha`
  ADD CONSTRAINT `FK_kandidat_priloha_priloha` FOREIGN KEY (`id_priloha`) REFERENCES `priloha` (`id_priloha`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_kandidat_priloha_kandidat` FOREIGN KEY (`id_kandidat`) REFERENCES `kandidat` (`id_kandidat`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kandidat_technologie`
--
ALTER TABLE `kandidat_technologie`
  ADD CONSTRAINT `FK_kandidat_technologie_technologie` FOREIGN KEY (`id_technologie`) REFERENCES `technologie` (`id_technologie`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_kandidat_technologie_kandidat` FOREIGN KEY (`id_kandidat`) REFERENCES `kandidat` (`id_kandidat`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `moznost`
--
ALTER TABLE `moznost`
  ADD CONSTRAINT `FK_moznost_revize` FOREIGN KEY (`revize`) REFERENCES `moznost` (`id_moznost`),
  ADD CONSTRAINT `FK_moznost_otazka` FOREIGN KEY (`id_otazka`) REFERENCES `otazka` (`id_otazka`);

--
-- Constraints for table `odpoved`
--
ALTER TABLE `odpoved`
  ADD CONSTRAINT `FK_odpoved_prirazeny_test` FOREIGN KEY (`id_prirazeny_test`) REFERENCES `prirazeny_test` (`id_prirazeny_test`),
  ADD CONSTRAINT `FK_odpoved_moznost` FOREIGN KEY (`id_moznost`) REFERENCES `moznost` (`id_moznost`),
  ADD CONSTRAINT `FK_odpoved_otazka` FOREIGN KEY (`id_otazka`) REFERENCES `otazka` (`id_otazka`);

--
-- Constraints for table `otazka`
--
ALTER TABLE `otazka`
  ADD CONSTRAINT `FK_otazka_revize` FOREIGN KEY (`revize`) REFERENCES `otazka` (`id_otazka`),
  ADD CONSTRAINT `FK_otazka_test` FOREIGN KEY (`id_test`) REFERENCES `test` (`id_test`);

--
-- Constraints for table `prirazeny_test`
--
ALTER TABLE `prirazeny_test`
  ADD CONSTRAINT `FK_prirazeny_test_kandidat` FOREIGN KEY (`id_kandidat`) REFERENCES `kandidat` (`id_kandidat`),
  ADD CONSTRAINT `FK_prirazeny_test_status` FOREIGN KEY (`id_status`) REFERENCES `status` (`id_status`),
  ADD CONSTRAINT `FK_prirazeny_test_test` FOREIGN KEY (`id_test`) REFERENCES `test` (`id_test`),
  ADD CONSTRAINT `FK_prirazeny_test_uzivatel` FOREIGN KEY (`id_kdo_priradil`) REFERENCES `uzivatel` (`id_uzivatel`);

--
-- Constraints for table `test`
--
ALTER TABLE `test`
  ADD CONSTRAINT `FK_test_seniorita` FOREIGN KEY (`id_seniorita`) REFERENCES `seniorita` (`id_seniorita`),
  ADD CONSTRAINT `FK_test_technologie` FOREIGN KEY (`id_technologie`) REFERENCES `technologie` (`id_technologie`),
  ADD CONSTRAINT `FK_test_uzivatel` FOREIGN KEY (`id_kdo_vytvoril`) REFERENCES `uzivatel` (`id_uzivatel`);

--
-- Constraints for table `uzivatel`
--
ALTER TABLE `uzivatel`
  ADD CONSTRAINT `FK_uzivatel_fotografie` FOREIGN KEY (`id_fotografie`) REFERENCES `fotografie` (`id_foto`);

--
-- Constraints for table `zprava`
--
ALTER TABLE `zprava`
  ADD CONSTRAINT `zprava_ibfk_2` FOREIGN KEY (`id_uzivatel`) REFERENCES `uzivatel` (`id_uzivatel`),
  ADD CONSTRAINT `zprava_ibfk_3` FOREIGN KEY (`id_kandidat`) REFERENCES `kandidat` (`id_kandidat`);
SET FOREIGN_KEY_CHECKS=1;
