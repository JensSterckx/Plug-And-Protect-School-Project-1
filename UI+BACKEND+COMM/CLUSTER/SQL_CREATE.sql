-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 18 mei 2015 om 13:51
-- Serverversie: 5.5.43
-- PHP-Versie: 5.4.39-0+deb7u2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databank: `plugandprotect`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tbl_logs`
--

CREATE TABLE IF NOT EXISTS `tbl_logs` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(128) NOT NULL,
  `IP` varchar(15) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`),
  UNIQUE KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tbl_mconfig`
--

CREATE TABLE IF NOT EXISTS `tbl_mconfig` (
  `MAC` varchar(17) NOT NULL,
  `type` varchar(45) NOT NULL,
  `file` varchar(512) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`MAC`,`file`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tbl_minfo`
--

CREATE TABLE IF NOT EXISTS `tbl_minfo` (
  `MAC` varchar(17) NOT NULL,
  `NAME` varchar(64) NOT NULL,
  `DESC` varchar(256) NOT NULL,
  `TYPE` varchar(512) NOT NULL,
  PRIMARY KEY (`MAC`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tbl_modules`
--

CREATE TABLE IF NOT EXISTS `tbl_modules` (
  `MAC` varchar(17) NOT NULL,
  `IP` varchar(15) NOT NULL,
  `HOSTNAME` text NOT NULL,
  `status` int(1) NOT NULL,
  `uptime` int(11) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`MAC`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tbl_sensordata`
--

CREATE TABLE IF NOT EXISTS `tbl_sensordata` (
  `sdata_id` int(11) NOT NULL AUTO_INCREMENT,
  `sdata_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `MAC` varchar(17) NOT NULL,
  `sensor_type` varchar(30) NOT NULL,
  `value` int(10) NOT NULL,
  PRIMARY KEY (`sdata_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=241 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tbl_users`
--

CREATE TABLE IF NOT EXISTS `tbl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(45) NOT NULL,
  `prename` varchar(45) NOT NULL,
  `lastname` varchar(45) NOT NULL,
  `password` text NOT NULL,
  `passwordkey` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Beperkingen voor gedumpte tabellen
--

--
-- Beperkingen voor tabel `tbl_minfo`
--
ALTER TABLE `tbl_minfo`
  ADD CONSTRAINT `tbl_minfo_ibfk_1` FOREIGN KEY (`MAC`) REFERENCES `tbl_modules` (`MAC`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
