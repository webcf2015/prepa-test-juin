-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Lun 22 Juin 2015 à 13:33
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `prepatestjuin`
--

-- --------------------------------------------------------

--
-- Structure de la table `droit`
--

CREATE TABLE IF NOT EXISTS `droit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lenom` varchar(45) DEFAULT NULL,
  `ladesc` varchar(255) DEFAULT NULL,
  `laperm` tinyint(3) unsigned DEFAULT '2',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `droit`
--

INSERT INTO `droit` (`id`, `lenom`, `ladesc`, `laperm`) VALUES
(1, 'Administrateur', 'Administrateur du site - Tous les droits.', 0),
(2, 'Modérateur', 'Modération du site - Vous pouvez désactiver un Utilisateur', 1),
(3, 'Utilisateur', 'Utilisateur du site', 2);

-- --------------------------------------------------------

--
-- Structure de la table `photo`
--

CREATE TABLE IF NOT EXISTS `photo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lenom` char(25) DEFAULT NULL,
  `letype` char(3) DEFAULT NULL,
  `lepoids` int(10) unsigned DEFAULT NULL,
  `lahauteur` smallint(5) unsigned DEFAULT NULL,
  `lalargeur` smallint(5) unsigned DEFAULT NULL,
  `letitre` varchar(60) DEFAULT NULL,
  `ladesc` varchar(255) DEFAULT NULL,
  `utilisateur_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lenom_UNIQUE` (`lenom`),
  KEY `fk_photo_utilisateur1_idx` (`utilisateur_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `photo`
--

INSERT INTO `photo` (`id`, `lenom`, `letype`, `lepoids`, `lahauteur`, `lalargeur`, `letitre`, `ladesc`, `utilisateur_id`) VALUES
(2, '2lbanb8d197all6cqbarfpjan', 'jpg', 845941, 768, 1024, 'D&eacute;sert', 'D&eacute;sert rouge', 1),
(3, 'aacorckep0inbdk7f9mbk1l2b', 'jpg', 2315860, 1536, 1920, 'soleil', 'lev&eacute; de soleil', 2),
(4, 'n2920fn6enf8i7oki83h1irjh', 'jpg', 458951, 1080, 1920, 'mauve', 'ciel mauve', 2),
(5, 'gdkrbm99hp028678nge2hm1l7', 'jpg', 26250, 296, 211, 'No&euml;l', 'P&egrave;re No&euml;l', 1);

-- --------------------------------------------------------

--
-- Structure de la table `photo_has_rubrique`
--

CREATE TABLE IF NOT EXISTS `photo_has_rubrique` (
  `photo_id` int(10) unsigned NOT NULL,
  `rubrique_id` int(10) unsigned NOT NULL,
  KEY `fk_photo_has_rubrique_rubrique1_idx` (`rubrique_id`),
  KEY `fk_photo_has_rubrique_photo1_idx` (`photo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `rubrique`
--

CREATE TABLE IF NOT EXISTS `rubrique` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lintitle` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `rubrique`
--

INSERT INTO `rubrique` (`id`, `lintitle`) VALUES
(1, 'Un'),
(2, 'Deux'),
(3, 'Trois'),
(4, 'Quatre'),
(5, 'Cinq');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lelogin` varchar(45) DEFAULT NULL,
  `lemdp` varchar(45) DEFAULT NULL,
  `lemail` varchar(150) DEFAULT NULL,
  `lenom` varchar(160) DEFAULT NULL,
  `lactif` tinyint(1) DEFAULT '0',
  `ladate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `laclef` varchar(32) DEFAULT NULL,
  `droit_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lelogin_UNIQUE` (`lelogin`),
  KEY `fk_utilisateur_droit_idx` (`droit_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `lelogin`, `lemdp`, `lemail`, `lenom`, `lactif`, `ladate`, `laclef`, `droit_id`) VALUES
(1, 'Admin', 'admin', 'michaeljpitz@gmail.com', 'Pitz Michaël', 1, '2015-06-12 09:32:52', '21232f297a57a5a743894a0e4a801fc3', 1),
(2, 'util1', 'util1', 'moi@util.com', 'utilisateur Un', 1, '2015-06-16 14:18:19', '983j79n9j5l01rggql75jjhq8m7f7m3q', 3);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `photo`
--
ALTER TABLE `photo`
  ADD CONSTRAINT `fk_photo_utilisateur1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `photo_has_rubrique`
--
ALTER TABLE `photo_has_rubrique`
  ADD CONSTRAINT `fk_photo_has_rubrique_photo1` FOREIGN KEY (`photo_id`) REFERENCES `photo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_photo_has_rubrique_rubrique1` FOREIGN KEY (`rubrique_id`) REFERENCES `rubrique` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `fk_utilisateur_droit` FOREIGN KEY (`droit_id`) REFERENCES `droit` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
