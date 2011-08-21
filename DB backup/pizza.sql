-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 13, 2011 at 08:02 
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `pizza`
--

-- --------------------------------------------------------

--
-- Table structure for table `belag`
--

CREATE TABLE IF NOT EXISTS `belag` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kat_ID` int(10) unsigned NOT NULL,
  `value` tinyint(1) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `kat_ID` (`kat_ID`),
  KEY `value` (`value`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `belag`
--

INSERT INTO `belag` (`ID`, `kat_ID`, `value`, `name`) VALUES
(1, 1, 1, 'Salami'),
(2, 2, 3, 'Gorgonzola'),
(3, 2, 2, 'Extra Käse'),
(4, 1, 1, 'Bacon'),
(5, 1, 3, 'Beef'),
(6, 4, 1, 'Mais'),
(7, 3, 1, 'Barbecue-Sauce');

-- --------------------------------------------------------

--
-- Table structure for table `belagkat`
--

CREATE TABLE IF NOT EXISTS `belagkat` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `belagkat`
--

INSERT INTO `belagkat` (`ID`, `name`) VALUES
(1, 'Fleisch'),
(2, 'Käse'),
(3, 'Saucen'),
(4, 'Obst & Gemüse'),
(5, 'Dressing'),
(6, 'Dips');

-- --------------------------------------------------------

--
-- Table structure for table `belagkatzuproduktkat`
--

CREATE TABLE IF NOT EXISTS `belagkatzuproduktkat` (
  `produktkat_ID` int(10) unsigned NOT NULL,
  `belagkat_ID` int(10) unsigned NOT NULL,
  KEY `produktkat_ID` (`produktkat_ID`,`belagkat_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `belagkatzuproduktkat`
--

INSERT INTO `belagkatzuproduktkat` (`produktkat_ID`, `belagkat_ID`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `belagpreis`
--

CREATE TABLE IF NOT EXISTS `belagpreis` (
  `size` int(10) unsigned NOT NULL,
  `value` tinyint(4) NOT NULL,
  `preis` float NOT NULL,
  KEY `size` (`size`),
  KEY `value` (`value`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `belagpreis`
--

INSERT INTO `belagpreis` (`size`, `value`, `preis`) VALUES
(1, 1, 0.85),
(1, 2, 1.4),
(1, 3, 1.9),
(2, 1, 1.05),
(2, 2, 1.65),
(2, 3, 2.2),
(3, 1, 1.4),
(3, 2, 2.5),
(3, 3, 3.05);

-- --------------------------------------------------------

--
-- Table structure for table `belagzubestellung`
--

CREATE TABLE IF NOT EXISTS `belagzubestellung` (
  `produktzubestell_ID` int(10) unsigned NOT NULL,
  `belag_ID` int(10) unsigned NOT NULL,
  KEY `produktzubestell_ID` (`produktzubestell_ID`,`belag_ID`),
  KEY `belag_ID` (`belag_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `belagzubestellung`
--

INSERT INTO `belagzubestellung` (`produktzubestell_ID`, `belag_ID`) VALUES
(1, 3),
(1, 4),
(1, 6);

-- --------------------------------------------------------

--
-- Table structure for table `belagzuprodukt`
--

CREATE TABLE IF NOT EXISTS `belagzuprodukt` (
  `belag_ID` int(10) unsigned NOT NULL,
  `produkt_ID` int(10) unsigned NOT NULL,
  KEY `belag_ID` (`belag_ID`),
  KEY `produkt_ID` (`produkt_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `belagzuprodukt`
--

INSERT INTO `belagzuprodukt` (`belag_ID`, `produkt_ID`) VALUES
(4, 7),
(5, 7),
(6, 7),
(7, 7);

-- --------------------------------------------------------

--
-- Table structure for table `bestellung`
--

CREATE TABLE IF NOT EXISTS `bestellung` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kunde_ID` int(11) NOT NULL,
  `datum` date NOT NULL,
  `done` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `kunde_ID` (`kunde_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `bestellung`
--

INSERT INTO `bestellung` (`ID`, `kunde_ID`, `datum`, `done`) VALUES
(1, 1, '2011-08-08', 1),
(2, 2, '2011-08-16', 0),
(3, 1, '2011-08-22', 1),
(4, 1, '2011-08-23', 0);

-- --------------------------------------------------------

--
-- Table structure for table `kunde`
--

CREATE TABLE IF NOT EXISTS `kunde` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `anrede` varchar(10) NOT NULL,
  `vorname` varchar(50) NOT NULL,
  `nachname` varchar(50) NOT NULL,
  `strasse` varchar(100) NOT NULL,
  `hausnummer` varchar(10) NOT NULL,
  `plz` int(5) NOT NULL,
  `stadt` varchar(50) NOT NULL,
  `telefon` int(20) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `kunde`
--

INSERT INTO `kunde` (`ID`, `anrede`, `vorname`, `nachname`, `strasse`, `hausnummer`, `plz`, `stadt`, `telefon`) VALUES
(1, 'Herr', 'Max', 'Muster', 'Musterstrasse', '12B', 22111, 'Hamburg', 40256542),
(2, 'Frau', 'Anna', 'Mininmi', 'blablastrasse', '33', 27651, 'Hamburg', 6523424);

-- --------------------------------------------------------

--
-- Table structure for table `produkt`
--

CREATE TABLE IF NOT EXISTS `produkt` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kat_ID` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `comment` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `kat_ID` (`kat_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `produkt`
--

INSERT INTO `produkt` (`ID`, `kat_ID`, `name`, `comment`) VALUES
(1, 1, 'Salami', ''),
(2, 1, 'Hawai', 'exotisch!'),
(3, 2, 'Croque-Madame', 'Würzig!'),
(4, 2, 'Croque-Veggi', 'Ohne Fleisch'),
(5, 3, 'Cola', ''),
(6, 4, 'Flensburger', ''),
(7, 1, 'Texas', 'Neu!');

-- --------------------------------------------------------

--
-- Table structure for table `produktkat`
--

CREATE TABLE IF NOT EXISTS `produktkat` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `top_ID` int(10) unsigned DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `produktkat`
--

INSERT INTO `produktkat` (`ID`, `top_ID`, `name`) VALUES
(1, NULL, 'Pizza'),
(2, NULL, 'Croque'),
(3, NULL, 'Getränke'),
(4, 3, 'Biere');

-- --------------------------------------------------------

--
-- Table structure for table `produktpreis`
--

CREATE TABLE IF NOT EXISTS `produktpreis` (
  `produkt_ID` int(10) unsigned NOT NULL,
  `size` int(10) unsigned NOT NULL,
  `preis` float NOT NULL,
  KEY `produkt_ID` (`produkt_ID`),
  KEY `size` (`size`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `produktpreis`
--

INSERT INTO `produktpreis` (`produkt_ID`, `size`, `preis`) VALUES
(1, 1, 5.59),
(1, 2, 7.99),
(1, 3, 9.89),
(2, 3, 10.55),
(3, 5, 12.88),
(4, 6, 18),
(5, 7, 2.5),
(5, 8, 4),
(7, 1, 9.1),
(7, 2, 11.55),
(7, 3, 16);

-- --------------------------------------------------------

--
-- Table structure for table `produktzubestellung`
--

CREATE TABLE IF NOT EXISTS `produktzubestellung` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bestell_ID` int(10) NOT NULL,
  `produkt_ID` int(10) NOT NULL,
  `size` int(10) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `bestell_ID` (`bestell_ID`),
  KEY `produkt_ID` (`produkt_ID`),
  KEY `size` (`size`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `produktzubestellung`
--

INSERT INTO `produktzubestellung` (`ID`, `bestell_ID`, `produkt_ID`, `size`) VALUES
(1, 1, 1, 2),
(2, 1, 5, 7);

-- --------------------------------------------------------

--
-- Table structure for table `size`
--

CREATE TABLE IF NOT EXISTS `size` (
  `size` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `comment` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`size`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `size`
--

INSERT INTO `size` (`size`, `name`, `comment`) VALUES
(1, 'small', '24cm'),
(2, 'medium', '32cm'),
(3, 'large', '38cm'),
(4, 'family', '500cm'),
(5, 'Halbes', NULL),
(6, 'Ganzes', NULL),
(7, '0.5 L', NULL),
(8, '1.0 L', NULL);
