-- phpMyAdmin SQL Dump
-- version 4.1.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 07. Apr 2014 um 06:52
-- Server Version: 5.5.34
-- PHP-Version: 5.5.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: 'gymnetz'
--
CREATE DATABASE IF NOT EXISTS gymnetz DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE gymnetz;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'attachment'
--

CREATE TABLE IF NOT EXISTS attachment (
  id int(11) NOT NULL AUTO_INCREMENT,
  contextid int(11) NOT NULL,
  contextkind int(11) NOT NULL,
  userid int(11) NOT NULL,
  path varchar(200) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'klasse'
--

CREATE TABLE IF NOT EXISTS klasse (
  id int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  klassenlehrer int(11) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'klassenmitglieder'
--

CREATE TABLE IF NOT EXISTS klassenmitglieder (
  id int(11) NOT NULL AUTO_INCREMENT,
  klasse int(11) NOT NULL,
  userid int(11) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'kurs'
--

CREATE TABLE IF NOT EXISTS kurs (
  id int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  klasse int(11) NOT NULL,
  lehrer int(11) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'kursteilnehmer'
--

CREATE TABLE IF NOT EXISTS kursteilnehmer (
  id int(11) NOT NULL AUTO_INCREMENT,
  kursid int(11) NOT NULL,
  klasse int(11) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'nachricht'
--

CREATE TABLE IF NOT EXISTS nachricht (
  id int(11) NOT NULL AUTO_INCREMENT,
  replyid int(11) DEFAULT NULL,
  message text NOT NULL,
  betreff varchar(32) NOT NULL,
  fromid int(11) NOT NULL,
  toid int(11) NOT NULL,
  zeit datetime NOT NULL,
  kind int(11) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'nachrichtremove'
--

CREATE TABLE IF NOT EXISTS nachrichtremove (
  id int(11) NOT NULL AUTO_INCREMENT,
  mid int(11) NOT NULL,
  uid int(11) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'note'
--

CREATE TABLE IF NOT EXISTS note (
  id int(11) NOT NULL AUTO_INCREMENT,
  note double NOT NULL,
  userid int(11) NOT NULL,
  testid int(11) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'test'
--

CREATE TABLE IF NOT EXISTS test (
  id int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  kursid int(11) NOT NULL,
  datum date NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'user'
--

CREATE TABLE IF NOT EXISTS `user` (
  id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  rights int(11) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
