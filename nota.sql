-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 24. Mai 2018 um 21:46
-- Server-Version: 10.1.26-MariaDB
-- PHP-Version: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `nota`
--
CREATE DATABASE IF NOT EXISTS `nota` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `nota`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `friend`
--

CREATE TABLE `friend` (
  `friendId` int(11) NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `note`
--

CREATE TABLE `note` (
  `noteId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `friendId` int(11) DEFAULT NULL,
  `noteTitel` varchar(50) NOT NULL,
  `noteContent` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `task`
--

CREATE TABLE `task` (
  `taskId` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `friendId` int(11) DEFAULT NULL,
  `taskContent` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `userId` int(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_friend`
--

CREATE TABLE `user_friend` (
  `userId` int(11) NOT NULL,
  `friendId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `friend`
--
ALTER TABLE `friend`
  ADD PRIMARY KEY (`friendId`),
  ADD KEY `fk-userId-friend` (`userId`);

--
-- Indizes für die Tabelle `note`
--
ALTER TABLE `note`
  ADD PRIMARY KEY (`noteId`),
  ADD KEY `fk-userId-note` (`userId`) USING BTREE,
  ADD KEY `fk-friendId-note` (`friendId`);

--
-- Indizes für die Tabelle `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`taskId`),
  ADD KEY `fk-userId-task` (`userId`) USING BTREE;

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indizes für die Tabelle `user_friend`
--
ALTER TABLE `user_friend`
  ADD UNIQUE KEY `unique-userId-friendId` (`userId`,`friendId`),
  ADD KEY `fk-friendId-friend` (`friendId`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `friend`
--
ALTER TABLE `friend`
  MODIFY `friendId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT für Tabelle `note`
--
ALTER TABLE `note`
  MODIFY `noteId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT für Tabelle `task`
--
ALTER TABLE `task`
  MODIFY `taskId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `friend`
--
ALTER TABLE `friend`
  ADD CONSTRAINT `fk-userId-friend` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`);

--
-- Constraints der Tabelle `note`
--
ALTER TABLE `note`
  ADD CONSTRAINT `fk-friendId-note` FOREIGN KEY (`friendId`) REFERENCES `friend` (`friendId`),
  ADD CONSTRAINT `fk-userId-note` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`);

--
-- Constraints der Tabelle `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `fk-userId-task` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`);

--
-- Constraints der Tabelle `user_friend`
--
ALTER TABLE `user_friend`
  ADD CONSTRAINT `fk-friendId-friend` FOREIGN KEY (`friendId`) REFERENCES `friend` (`friendId`),
  ADD CONSTRAINT `fk-userId-user` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
