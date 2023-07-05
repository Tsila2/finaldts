-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 04 juil. 2023 à 13:01
-- Version du serveur : 5.7.26
-- Version de PHP : 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `chatigniter`
--

-- --------------------------------------------------------

--
-- Structure de la table `user_messages`
--

DROP TABLE IF EXISTS `user_messages`;
CREATE TABLE IF NOT EXISTS `user_messages` (
  `time` datetime(6) NOT NULL,
  `sender_message_id` varchar(20) CHARACTER SET latin1 NOT NULL,
  `receiver_message_id` varchar(20) CHARACTER SET latin1 NOT NULL,
  `message` varchar(500) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `user_messages`
--

INSERT INTO `user_messages` (`time`, `sender_message_id`, `receiver_message_id`, `message`) VALUES
('2023-07-03 10:48:46.000000', '020ab4', '2bc812', 'test'),
('2023-07-03 10:48:57.000000', '2bc812', '020ab4', 'okay'),
('2023-07-03 10:49:04.000000', '020ab4', '2bc812', 'siora'),
('2023-07-03 10:49:06.000000', '2bc812', '020ab4', 'sdd'),
('2023-07-03 10:49:08.000000', '020ab4', '2bc812', 'fdfvdfbf'),
('2023-07-03 10:49:10.000000', '2bc812', '020ab4', 'fvdvfbvf'),
('2023-07-03 10:49:14.000000', '020ab4', '2bc812', 'dvfddvfdv'),
('2023-07-03 10:49:16.000000', '2bc812', '020ab4', 'vfcdv'),
('2023-07-03 10:49:19.000000', '020ab4', '2bc812', 'cdvfdsdv'),
('2023-07-03 10:49:22.000000', '2bc812', '020ab4', 'dcvfvffv'),
('2023-07-03 10:49:25.000000', '020ab4', '2bc812', 'vfdsvdvf'),
('2023-07-03 10:49:28.000000', '2bc812', '020ab4', 'vffvdsvf'),
('2023-07-03 10:49:31.000000', '020ab4', '2bc812', 'cvfdcdv'),
('2023-07-03 10:49:39.000000', '2bc812', '020ab4', 'cvdw'),
('2023-07-03 10:49:44.000000', '020ab4', '2bc812', 'vcwxv'),
('2023-07-03 11:15:11.000000', '020ab4', '2bc812', 'vdsdfq'),
('2023-07-03 11:17:37.000000', '020ab4', '2bc812', 'c'),
('2023-07-03 11:18:50.000000', '020ab4', '2bc812', 'dfdwf'),
('2023-07-03 11:25:52.000000', '020ab4', '2bc812', 'gbvcfdvsxvqdvqsv'),
('2023-07-03 11:34:39.000000', '2bc812', '020ab4', ''),
('2023-07-03 11:37:30.000000', '2bc812', '020ab4', 'fsgddsg'),
('2023-07-03 11:37:44.000000', '2bc812', '020ab4', '                     t'),
('2023-07-03 11:42:31.000000', '2bc812', '020ab4', 'gfdgsd'),
('2023-07-03 11:42:38.000000', '2bc812', '020ab4', 'b'),
('2023-07-03 11:44:09.000000', '020ab4', '2bc812', ''),
('2023-07-03 11:44:12.000000', '020ab4', '2bc812', ''),
('2023-07-03 11:45:49.000000', '020ab4', '2bc812', 'setrg'),
('2023-07-03 11:48:23.000000', '2bc812', '020ab4', 'l'),
('2023-07-03 11:48:33.000000', '020ab4', '2bc812', 'inna?'),
('2023-07-03 11:48:42.000000', '2bc812', '020ab4', 'tsy aiko'),
('2023-07-03 11:49:08.000000', '2bc812', '020ab4', 'kjk'),
('2023-07-03 14:43:22.000000', '020ab4', '2bc812', 'ydtff'),
('2023-07-03 14:43:30.000000', '020ab4', '2bc812', 'h'),
('2023-07-03 15:27:01.000000', '020ab4', '2bc812', 'df'),
('2023-07-03 15:30:39.000000', '020ab4', '2bc812', 'test'),
('2023-07-03 15:31:12.000000', '020ab4', '2bc812', 'rgf'),
('2023-07-03 15:31:28.000000', '020ab4', '2bc812', 'test'),
('2023-07-04 09:42:39.000000', '2bc812', '4850ff', 'Mahita v?');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
