-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 25. August 2011 um 11:45
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Daten für Tabelle `belag`
--

INSERT INTO `belag` (`ID`, `kat_ID`, `value`, `name`) VALUES
(12, 8, 1, 'Bacon'),
(13, 8, 1, 'Ei'),
(14, 8, 2, 'Hänchenbrustfilet'),
(15, 9, 1, 'Ananas'),
(16, 10, 3, 'Gorgonzola'),
(17, 8, 2, 'Schinken'),
(18, 12, 0, 'Chili'),
(19, 9, 1, 'Mais'),
(20, 9, 1, 'Oliven'),
(21, 9, 1, 'Zwiebeln'),
(22, 10, 3, 'Extra Käse'),
(23, 10, 3, 'Mozzarella'),
(24, 11, 1, 'Asia-Sauce (süß-sauer)'),
(25, 11, 1, 'Honig-Senf-sauce'),
(26, 13, 1, 'Italian'),
(27, 13, 1, 'American'),
(28, 14, 0, 'Eisbergsalat'),
(29, 14, 0, 'Tomate'),
(30, 14, 0, 'Gurke'),
(31, 8, 3, 'Beef'),
(32, 11, 1, 'Barbecue-Sauce');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `belagkat`
--

CREATE TABLE IF NOT EXISTS `belagkat` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Daten für Tabelle `belagkat`
--

INSERT INTO `belagkat` (`ID`, `name`) VALUES
(8, 'Fisch & Fleisch'),
(9, 'Obst & Gemüse'),
(10, 'Käse'),
(11, 'Saucen'),
(12, 'Gewürze'),
(13, 'Dressings'),
(14, 'Salat');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `belagkatzuproduktkat`
--

CREATE TABLE IF NOT EXISTS `belagkatzuproduktkat` (
  `produktkat_ID` int(10) unsigned NOT NULL,
  `belagkat_ID` int(10) unsigned NOT NULL,
  KEY `produktkat_ID` (`produktkat_ID`,`belagkat_ID`),
  KEY `belagkat_ID` (`belagkat_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `belagkatzuproduktkat`
--

INSERT INTO `belagkatzuproduktkat` (`produktkat_ID`, `belagkat_ID`) VALUES
(19, 8),
(19, 9),
(19, 10),
(19, 11),
(19, 12),
(20, 13),
(20, 14);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `belagpreis`
--

CREATE TABLE IF NOT EXISTS `belagpreis` (
  `size` int(10) unsigned NOT NULL,
  `value` tinyint(4) NOT NULL,
  `preis` float(3,2) NOT NULL,
  KEY `size` (`size`),
  KEY `value` (`value`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `belagpreis`
--

INSERT INTO `belagpreis` (`size`, `value`, `preis`) VALUES
(54, 1, 0.85),
(54, 2, 1.40),
(54, 3, 1.90),
(55, 1, 1.05),
(55, 2, 1.65),
(55, 3, 2.20),
(56, 1, 1.40),
(56, 2, 2.50),
(56, 3, 3.05);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `belagzubestellung`
--

INSERT INTO `belagzubestellung` (`ID`, `produktzubestell_ID`, `belag_ID`) VALUES
(1, 1, 17),
(2, 1, 17);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `belagzuprodukt`
--

CREATE TABLE IF NOT EXISTS `belagzuprodukt` (
  `belag_ID` int(10) unsigned NOT NULL,
  `produkt_ID` int(10) unsigned NOT NULL,
  KEY `belag_ID` (`belag_ID`),
  KEY `produkt_ID` (`produkt_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `belagzuprodukt`
--

INSERT INTO `belagzuprodukt` (`belag_ID`, `produkt_ID`) VALUES
(15, 20),
(17, 20),
(14, 32),
(15, 32),
(24, 32),
(18, 32),
(12, 25),
(28, 22),
(29, 22),
(30, 22),
(31, 25),
(19, 25),
(32, 25);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bestellung`
--

CREATE TABLE IF NOT EXISTS `bestellung` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kunde_ID` int(11) unsigned NOT NULL,
  `datum` date NOT NULL,
  `wish` varchar(100) DEFAULT NULL,
  `done` tinyint(1) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`ID`),
  KEY `kunde_ID` (`kunde_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `bestellung`
--

INSERT INTO `bestellung` (`ID`, `kunde_ID`, `datum`, `wish`, `done`) VALUES
(1, 1, '2011-08-24', NULL, 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `kunde`
--

INSERT INTO `kunde` (`ID`, `anrede`, `vorname`, `nachname`, `strasse`, `hausnummer`, `plz`, `stadt`, `telefon`) VALUES
(1, 'Herr', 'Max', 'Muster', '', '', 0, '', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `produkt`
--

CREATE TABLE IF NOT EXISTS `produkt` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kat_ID` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `comment` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `kat_ID` (`kat_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;

--
-- Daten für Tabelle `produkt`
--

INSERT INTO `produkt` (`ID`, `kat_ID`, `name`, `comment`) VALUES
(20, 19, 'Hawai', ''),
(21, 22, 'Pizzabrötchen', 'mit Käse überbacken'),
(22, 20, 'Chef-Salat', ''),
(24, 22, 'Pizzadip', 'Portionsbecher 100ml'),
(25, 19, 'Texas', 'commentblubb'),
(28, 34, 'Flensburger', ''),
(32, 19, 'China Town', ''),
(34, 20, 'gdfg', ''),
(37, 33, 'Cola', ''),
(38, 33, 'Fanta', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `produktkat`
--

CREATE TABLE IF NOT EXISTS `produktkat` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `top_ID` int(10) unsigned DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;

--
-- Daten für Tabelle `produktkat`
--

INSERT INTO `produktkat` (`ID`, `top_ID`, `name`) VALUES
(19, NULL, 'Pizza'),
(20, NULL, 'Salat'),
(22, NULL, 'Extras'),
(32, NULL, 'Getränke'),
(33, 32, 'Softdrinks'),
(34, 32, 'Bier');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `produktpreis`
--

CREATE TABLE IF NOT EXISTS `produktpreis` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `produkt_ID` int(10) unsigned NOT NULL,
  `size` int(10) unsigned NOT NULL,
  `preis` float(3,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`ID`),
  KEY `produkt_ID` (`produkt_ID`),
  KEY `size` (`size`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=117 ;

--
-- Daten für Tabelle `produktpreis`
--

INSERT INTO `produktpreis` (`ID`, `produkt_ID`, `size`, `preis`) VALUES
(43, 22, 18, 4.65),
(44, 22, 19, 134.80),
(71, 28, 69, 2.00),
(82, 21, 51, 4.00),
(87, 21, 52, 6.00),
(112, 37, 68, 2.00),
(113, 37, 67, 3.00),
(115, 38, 68, 2.00),
(116, 24, 70, 1.00);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `produktzubestellung`
--

CREATE TABLE IF NOT EXISTS `produktzubestellung` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bestell_ID` int(10) unsigned NOT NULL,
  `produkt_ID` int(10) unsigned NOT NULL,
  `size` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `bestell_ID` (`bestell_ID`),
  KEY `produkt_ID` (`produkt_ID`),
  KEY `size` (`size`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `produktzubestellung`
--

INSERT INTO `produktzubestellung` (`ID`, `bestell_ID`, `produkt_ID`, `size`) VALUES
(1, 1, 21, 53);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `size`
--

CREATE TABLE IF NOT EXISTS `size` (
  `size` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `produktkat_ID` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `comment` varchar(50) DEFAULT NULL,
  `def_preis` float(3,2) unsigned DEFAULT NULL,
  PRIMARY KEY (`size`),
  KEY `produktkat_ID` (`produktkat_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=71 ;

--
-- Daten für Tabelle `size`
--

INSERT INTO `size` (`size`, `produktkat_ID`, `name`, `comment`, `def_preis`) VALUES
(18, 20, 'small', '', NULL),
(19, 20, 'mega', '', NULL),
(51, 22, '4 Stück', '', NULL),
(52, 22, '6 Stück', '', NULL),
(53, 22, '', '', NULL),
(54, 19, 'small', '24cm', 4.65),
(55, 19, 'medium', '32cm', 6.20),
(56, 19, 'large', '38cm', 8.75),
(67, 33, '0.5L', '', NULL),
(68, 33, '0.3L', '', NULL),
(69, 34, '0.5L', '', NULL),
(70, 22, '100ml', '', NULL);

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `belag`
--
ALTER TABLE `belag`
  ADD CONSTRAINT `belag_ibfk_1` FOREIGN KEY (`kat_ID`) REFERENCES `belagkat` (`ID`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `belagkatzuproduktkat`
--
ALTER TABLE `belagkatzuproduktkat`
  ADD CONSTRAINT `belagkatzuproduktkat_ibfk_1` FOREIGN KEY (`produktkat_ID`) REFERENCES `produktkat` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `belagkatzuproduktkat_ibfk_2` FOREIGN KEY (`belagkat_ID`) REFERENCES `belagkat` (`ID`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `belagpreis`
--
ALTER TABLE `belagpreis`
  ADD CONSTRAINT `belagpreis_ibfk_1` FOREIGN KEY (`size`) REFERENCES `size` (`size`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `belagzubestellung`
--
ALTER TABLE `belagzubestellung`
  ADD CONSTRAINT `belagzubestellung_ibfk_1` FOREIGN KEY (`produktzubestell_ID`) REFERENCES `produktzubestellung` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `belagzubestellung_ibfk_2` FOREIGN KEY (`belag_ID`) REFERENCES `belag` (`ID`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `belagzuprodukt`
--
ALTER TABLE `belagzuprodukt`
  ADD CONSTRAINT `belagzuprodukt_ibfk_1` FOREIGN KEY (`produkt_ID`) REFERENCES `produkt` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `belagzuprodukt_ibfk_2` FOREIGN KEY (`belag_ID`) REFERENCES `belag` (`ID`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `bestellung`
--
ALTER TABLE `bestellung`
  ADD CONSTRAINT `bestellung_ibfk_1` FOREIGN KEY (`kunde_ID`) REFERENCES `kunde` (`ID`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `produkt`
--
ALTER TABLE `produkt`
  ADD CONSTRAINT `produkt_ibfk_1` FOREIGN KEY (`kat_ID`) REFERENCES `produktkat` (`ID`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `produktpreis`
--
ALTER TABLE `produktpreis`
  ADD CONSTRAINT `produktpreis_ibfk_1` FOREIGN KEY (`produkt_ID`) REFERENCES `produkt` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `produktpreis_ibfk_2` FOREIGN KEY (`size`) REFERENCES `size` (`size`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `produktzubestellung`
--
ALTER TABLE `produktzubestellung`
  ADD CONSTRAINT `produktzubestellung_ibfk_1` FOREIGN KEY (`bestell_ID`) REFERENCES `bestellung` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `produktzubestellung_ibfk_2` FOREIGN KEY (`produkt_ID`) REFERENCES `produkt` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `produktzubestellung_ibfk_3` FOREIGN KEY (`size`) REFERENCES `size` (`size`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `size`
--
ALTER TABLE `size`
  ADD CONSTRAINT `size_ibfk_1` FOREIGN KEY (`produktkat_ID`) REFERENCES `produktkat` (`ID`) ON DELETE CASCADE;
