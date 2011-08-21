-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 21. August 2011 um 20:12
-- Server Version: 5.5.8
-- PHP-Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `mafia`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `belag`
--

CREATE TABLE IF NOT EXISTS `belag` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kat_ID` int(10) unsigned NOT NULL,
  `value` tinyint(1) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `kat_ID` (`kat_ID`),
  KEY `value` (`value`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Daten für Tabelle `belag`
--

INSERT INTO `belag` (`ID`, `kat_ID`, `value`, `name`) VALUES
(1, 1, 1, 'Salamii'),
(2, 2, 3, 'Gorgonzola'),
(3, 2, 2, 'Extra Käse'),
(4, 1, 1, 'Bacon'),
(5, 1, 3, 'Beef'),
(6, 4, 1, 'Mais'),
(7, 3, 1, 'Barbecue-Sauce'),
(11, 4, 1, 'Blattspinat');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `belagkat`
--

CREATE TABLE IF NOT EXISTS `belagkat` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Daten für Tabelle `belagkat`
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
-- Tabellenstruktur für Tabelle `belagkatzuproduktkat`
--

CREATE TABLE IF NOT EXISTS `belagkatzuproduktkat` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `produktkat_ID` int(10) unsigned NOT NULL,
  `belagkat_ID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `produktkat_ID` (`produktkat_ID`,`belagkat_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Daten für Tabelle `belagkatzuproduktkat`
--

INSERT INTO `belagkatzuproduktkat` (`ID`, `produktkat_ID`, `belagkat_ID`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `belagpreis`
--

CREATE TABLE IF NOT EXISTS `belagpreis` (
  `size` int(10) unsigned NOT NULL,
  `value` tinyint(4) NOT NULL,
  `preis` float NOT NULL,
  KEY `size` (`size`),
  KEY `value` (`value`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `belagpreis`
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
-- Tabellenstruktur für Tabelle `belagzubestellung`
--

CREATE TABLE IF NOT EXISTS `belagzubestellung` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `produktzubestell_ID` int(10) unsigned NOT NULL,
  `belag_ID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `produktzubestell_ID` (`produktzubestell_ID`,`belag_ID`),
  KEY `belag_ID` (`belag_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Daten für Tabelle `belagzubestellung`
--

INSERT INTO `belagzubestellung` (`ID`, `produktzubestell_ID`, `belag_ID`) VALUES
(1, 1, 3),
(2, 1, 4),
(3, 1, 6);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `belagzuprodukt`
--

CREATE TABLE IF NOT EXISTS `belagzuprodukt` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `belag_ID` int(10) unsigned NOT NULL,
  `produkt_ID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `belag_ID` (`belag_ID`),
  KEY `produkt_ID` (`produkt_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Daten für Tabelle `belagzuprodukt`
--

INSERT INTO `belagzuprodukt` (`ID`, `belag_ID`, `produkt_ID`) VALUES
(1, 4, 7),
(2, 5, 7),
(3, 6, 7),
(4, 7, 7);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bestellung`
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
-- Daten für Tabelle `bestellung`
--

INSERT INTO `bestellung` (`ID`, `kunde_ID`, `datum`, `done`) VALUES
(1, 1, '2011-08-08', 1),
(2, 2, '2011-08-16', 0),
(3, 1, '2011-08-22', 1),
(4, 1, '2011-08-23', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kunde`
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
-- Daten für Tabelle `kunde`
--

INSERT INTO `kunde` (`ID`, `anrede`, `vorname`, `nachname`, `strasse`, `hausnummer`, `plz`, `stadt`, `telefon`) VALUES
(1, 'Herr', 'Max', 'Muster', 'Musterstrasse', '12B', 22111, 'Hamburg', 40256542),
(2, 'Frau', 'Anna', 'Mininmi', 'blablastrasse', '33', 27651, 'Hamburg', 6523424);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `produkt`
--

CREATE TABLE IF NOT EXISTS `produkt` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kat_ID` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `comment` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `kat_ID` (`kat_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Daten für Tabelle `produkt`
--

INSERT INTO `produkt` (`ID`, `kat_ID`, `name`, `comment`) VALUES
(1, 1, 'Salamiii', 'blubbbb'),
(2, 1, 'Hawai', 'exotisch!'),
(3, 2, 'Croque-Madame', 'Würzig!'),
(4, 2, 'Croque-Veggi', 'Ohne Fleisch'),
(5, 6, 'Cola', ''),
(6, 4, 'Flensburger', ''),
(7, 1, 'Texas', 'Neu!'),
(10, 1, 'kuck', ''),
(11, 1, 'erster', ''),
(12, 2, 'zweiter', 'ggg'),
(13, 1, 'Test2', 'nuz'),
(14, 2, 'blubba', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `produktkat`
--

CREATE TABLE IF NOT EXISTS `produktkat` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `top_ID` int(10) unsigned DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Daten für Tabelle `produktkat`
--

INSERT INTO `produktkat` (`ID`, `top_ID`, `name`) VALUES
(1, NULL, 'Pizza'),
(2, NULL, 'Croque'),
(3, NULL, 'Getränke'),
(4, 3, 'Biere'),
(5, NULL, 'Salate'),
(6, 3, 'Softdrinks'),
(14, 2, 'Hirohu');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `produktpreis`
--

CREATE TABLE IF NOT EXISTS `produktpreis` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `produkt_ID` int(10) unsigned NOT NULL,
  `size` int(10) unsigned NOT NULL,
  `preis` float NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `produkt_ID` (`produkt_ID`),
  KEY `size` (`size`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Daten für Tabelle `produktpreis`
--

INSERT INTO `produktpreis` (`ID`, `produkt_ID`, `size`, `preis`) VALUES
(1, 1, 1, 5.6),
(2, 1, 2, 8.91),
(3, 1, 3, 9.91),
(4, 2, 3, 10.55),
(5, 3, 5, 12.88),
(6, 4, 6, 18),
(7, 5, 7, 2.5),
(8, 5, 8, 4),
(9, 7, 1, 9.1),
(10, 7, 2, 11.55),
(11, 7, 3, 16),
(12, 11, 0, 0),
(13, 11, 0, 0),
(14, 11, 0, 0),
(15, 12, 0, 0),
(16, 12, 0, 0),
(17, 13, 1, 9),
(18, 13, 2, 12),
(21, 14, 5, 0),
(22, 14, 6, 0),
(23, 13, 3, 16.5);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `produktzubestellung`
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
-- Daten für Tabelle `produktzubestellung`
--

INSERT INTO `produktzubestellung` (`ID`, `bestell_ID`, `produkt_ID`, `size`) VALUES
(1, 1, 1, 2),
(2, 1, 5, 7);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `size`
--

CREATE TABLE IF NOT EXISTS `size` (
  `size` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `produktkat_ID` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `comment` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`size`),
  KEY `produktkat_ID` (`produktkat_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Daten für Tabelle `size`
--

INSERT INTO `size` (`size`, `produktkat_ID`, `name`, `comment`) VALUES
(1, 1, 'small', '27cm'),
(2, 1, 'medium', '32cm'),
(3, 1, 'large', '38cm'),
(4, 1, 'family', '500cm'),
(5, 2, 'Halbes', ''),
(6, 2, 'Ganzes', ''),
(7, 3, '0.5 L', ''),
(8, 3, '1.0 L', '');
