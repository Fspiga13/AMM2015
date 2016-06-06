-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: Giu 06, 2016 alle 15:34
-- Versione del server: 5.5.41-0ubuntu0.14.04.1
-- Versione PHP: 5.5.9-1ubuntu4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `amm15_spigaFederico`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `prenotazioni`
--

CREATE TABLE IF NOT EXISTS `prenotazioni` (
  `id_user` bigint(20) unsigned NOT NULL,
  `id_viaggio` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id_user`,`id_viaggio`),
  KEY `id_viaggio` (`id_viaggio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `prenotazioni`
--

INSERT INTO `prenotazioni` (`id_user`, `id_viaggio`) VALUES
(2, 2),
(3, 2),
(3, 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `sedi`
--

CREATE TABLE IF NOT EXISTS `sedi` (
  `id_sede` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nazione` varchar(100) NOT NULL,
  `citta` varchar(100) NOT NULL,
  PRIMARY KEY (`id_sede`),
  UNIQUE KEY `id_sede` (`id_sede`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dump dei dati per la tabella `sedi`
--

INSERT INTO `sedi` (`id_sede`, `nazione`, `citta`) VALUES
(1, 'Indonesia (Bali)', 'Kuta'),
(2, 'Portogallo', 'Nazare'),
(3, 'Spagna (Canarie)', 'Fuerteventura');

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id_user` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tipo` enum('Organizzatore','Utente') NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cognome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `via` varchar(100) NOT NULL,
  `numero_civico` int(10) NOT NULL,
  `citta` varchar(100) NOT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `id_utente` (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`id_user`, `tipo`, `username`, `password`, `nome`, `cognome`, `email`, `via`, `numero_civico`, `citta`) VALUES
(1, 'Organizzatore', 'organizzatore', 'spiga', 'Federico', 'Spiga', 'fede@gmail.com', 'Via Roma', 13, 'Cagliari'),
(2, 'Utente', 'utente', 'spiga', 'Pinco', 'Pallino', 'pinco@gmail.com', 'Via Sonnino', 11, 'Cagliari'),
(3, 'Utente', 'Mario', 'roSSi', 'Mario', 'Rossi', 'mros@gmail.com', 'Via Paoli', 31, 'Cagliari'),
(4, 'Organizzatore', 'organizzatoree', 'spiga', 'Lorenzo', 'Spiga', 'l@gmail.com', 'Via Cagliari', 2, 'Quartu Sant Elena');

-- --------------------------------------------------------

--
-- Struttura della tabella `viaggi`
--

CREATE TABLE IF NOT EXISTS `viaggi` (
  `id_viaggio` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `data_partenza` date NOT NULL,
  `data_ritorno` date NOT NULL,
  `posti` int(5) NOT NULL,
  `prezzo` float NOT NULL,
  `id_organizzatore` bigint(20) unsigned NOT NULL,
  `id_sede` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id_viaggio`),
  UNIQUE KEY `id_viaggio` (`id_viaggio`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dump dei dati per la tabella `viaggi`
--

INSERT INTO `viaggi` (`id_viaggio`, `data_partenza`, `data_ritorno`, `posti`, `prezzo`, `id_organizzatore`, `id_sede`) VALUES
(1, '2016-09-25', '2016-10-03', 15, 1250, 4, 1),
(2, '2016-08-05', '2016-08-11', 10, 1000, 1, 1),
(3, '2016-10-01', '2016-10-10', 20, 650, 1, 3),
(4, '2016-08-05', '2016-08-10', 5, 700, 1, 2);

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `prenotazioni`
--
ALTER TABLE `prenotazioni`
  ADD CONSTRAINT `prenotazioni_ibfk_2` FOREIGN KEY (`id_viaggio`) REFERENCES `viaggi` (`id_viaggio`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prenotazioni_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
