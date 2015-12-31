-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Jeu 31 Décembre 2015 à 10:54
-- Version du serveur :  5.6.24
-- Version de PHP :  5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `radius`
--

-- --------------------------------------------------------

--
-- Structure de la table `radmacadd`
--

CREATE TABLE IF NOT EXISTS `radmacadd` (
  `id` int(11) NOT NULL,
  `hostname` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `add_mac` varchar(255) DEFAULT NULL,
  `add_ip` varchar(255) DEFAULT NULL,
  `o1` int(3) DEFAULT NULL,
  `o2` int(3) DEFAULT NULL,
  `o3` int(3) DEFAULT NULL,
  `o4` int(3) DEFAULT NULL,
  `proprietaire` varchar(255) DEFAULT NULL,
  `proprietaire_classe` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `radmacadd`
--

INSERT INTO `radmacadd` (`id`, `hostname`, `description`, `type`, `add_mac`, `add_ip`, `o1`, `o2`, `o3`, `o4`, `proprietaire`, `proprietaire_classe`) VALUES
(1, 'Hostname de test', 'Description de testttt', 'smartphone', '22:FF:EA:AE:EE:55', '10.6.1.5', 10, 6, 1, 5, 'eleve', NULL),
(2, 'dqzdzqd', 'zdqdzqdzqdqd', 'ordinateur', 'EE:EE:EE:EE:EE:EE', '10.2.1.1', 10, 2, 1, 1, 'test', 'SIOTP 2');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `radmacadd`
--
ALTER TABLE `radmacadd`
  ADD PRIMARY KEY (`id`) COMMENT 'comentaire';

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `radmacadd`
--
ALTER TABLE `radmacadd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
