-- Base de données : `ks`
DROP DATABASE IF EXISTS `ks`;
CREATE DATABASE `ks` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `ks`;

-- Structure de la table `ks_roles`
DROP TABLE IF EXISTS `ks_roles`;
CREATE TABLE IF NOT EXISTS `ks_roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Déchargement des données de la table `ks_roles`
INSERT INTO `ks_roles` (`id`, `role_name`) VALUES
(1, 'Admin'),
(2, 'User');

-- Structure de la table `ks_scrap`
DROP TABLE IF EXISTS `ks_scrap`;
CREATE TABLE IF NOT EXISTS `ks_scrap` (
  `id_scr` int NOT NULL AUTO_INCREMENT,
  `id_eq` int NOT NULL,
  `id_user` int NOT NULL,
  `sc_qte` int NOT NULL,
  `reason` varchar(255) NOT NULL,
  `eq_code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_scr`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Déchargement des données de la table `ks_scrap`
INSERT INTO `ks_scrap` (`id_scr`, `id_eq`, `id_user`, `sc_qte`, `reason`, `eq_code`) VALUES
(30, 22, 3, 22, 'no_longer_usable', NULL),
(31, 77, 3, 77, 'damaged', NULL),
(33, 50, 3, 10, 'outofstock', NULL),
(32, 44, 3, 44, 'outofstock', NULL),
(29, 12, 3, 12, 'outofstock', NULL);

-- Structure de la table `ks_user`
DROP TABLE IF EXISTS `ks_user`;
CREATE TABLE IF NOT EXISTS `ks_user` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Username` varchar(10) NOT NULL,
  `FirstName` varchar(30) NOT NULL,
  `LastName` varchar(30) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `roles_id` int DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `role_id` (`roles_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Déchargement des données de la table `ks_user`
INSERT INTO `ks_user` (`ID`, `Username`, `FirstName`, `LastName`, `Email`, `Password`, `reset_token`, `roles_id`) VALUES
(1, 'Hataz', 'Hasnae', 'Tazi', 'hasnae.tazi@ksma-ke.kroschu.com', 'admin', NULL, 1),
(3, 'kabla', 'karam', 'blal', 'karam.blal@ksma-ke.kroschu.com', 'kabla123.', '644fde3739b3c0cae95781888cb7b1e5bfec9899474ef8b5017997b139c45595', 2),
(4, 'asame', 'Ali', 'Samer', 'asame@ksma-ke.kroschu.com', 'asame123.', NULL, 2),
(10, 'test', 'user', 'user', 'user.test@gmail.com', '123456', NULL, 2);

-- Structure de la table `ks_storage`
DROP TABLE IF EXISTS `ks_storage`;
CREATE TABLE IF NOT EXISTS `ks_storage` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `st-code` varchar(10) NOT NULL,
  `st-name` varchar(100) NOT NULL,
  `st-type` varchar(50) NOT NULL,
  `st-qte` int NOT NULL,
  `st-affectation` varchar(50) NOT NULL,
  `st-status` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Déchargement des données de la table `ks_storage`
INSERT INTO `ks_storage` (`id`, `id_user`, `st-code`, `st-name`, `st-type`, `st-qte`, `st-affectation`, `st-status`) VALUES
(1, 0, '49-002', 'Adaptateurs', 'HDMI to DVI', 57, 'Rep', 'Disponible'),
(2, 0, '49-005', 'HDMI Cables', 'HDMI 5M', 56, 'Rep', 'Disponible'),
(3, 0, '49-006', 'HDMI Cables', 'HDMI 10M', 43, 'IT Storage', 'Disponible'),
(4, 0, '49-007', 'HDMI Cables', 'HDMI 20M', 9, 'Rep', 'Disponible'),
(5, 0, '49-008', 'Network Cables', 'Cables 2M', 33, 'IT Storage', 'Disponible'),
(6, 0, '49-012', 'Terminal', 'Fujitsi', 33, 'IT Storage', 'Disponible'),
(7, 0, '49-011', 'Power Supply Server', 'Black', 34, 'Rep', 'Disponible'),
(8, 0, '49-009', 'Network Cables', 'Cables 20M', 3, 'IT Storage', 'Disponible'),
(9, 0, '49-010', 'Power Supply Server', 'Grey', 40, 'IT Storage', 'Disponible');
(10, 0, '49-0', 'Power Supply Server', 'Grey', 40, 'IT Storage', 'Disponible');


COMMIT;