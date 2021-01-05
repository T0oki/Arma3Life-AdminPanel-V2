-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  ven. 03 juil. 2020 à 23:55
-- Version du serveur :  10.3.22-MariaDB-0+deb10u1
-- Version de PHP :  7.3.14-1~deb10u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `AdminPanel`
--

-- --------------------------------------------------------

--
-- Structure de la table `Logs`
--

CREATE TABLE `Logs` (
  `id` int(11) NOT NULL,
  `Type` varchar(255) NOT NULL,
  `Time` varchar(255) NOT NULL,
  `User` varchar(255) NOT NULL,
  `Content` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `Rank`
--

CREATE TABLE `Rank` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `isAdmin` tinyint(1) NOT NULL DEFAULT 0,
  `view_player` tinyint(1) NOT NULL DEFAULT 0,
  `view_vehicle` tinyint(1) NOT NULL DEFAULT 0,
  `view_home` tinyint(1) NOT NULL DEFAULT 0,
  `view_gang` tinyint(1) NOT NULL DEFAULT 0,
  `view_container` tinyint(1) NOT NULL DEFAULT 0,
  `edit_player` tinyint(1) NOT NULL DEFAULT 0,
  `edit_player_license` tinyint(1) NOT NULL DEFAULT 0,
  `edit_player_money` tinyint(1) NOT NULL DEFAULT 0,
  `edit_player_cop` tinyint(1) NOT NULL DEFAULT 0,
  `edit_player_med` tinyint(1) NOT NULL DEFAULT 0,
  `edit_player_admin` tinyint(1) NOT NULL DEFAULT 0,
  `edit_player_delete` tinyint(1) NOT NULL DEFAULT 0,
  `edit_vehicle` tinyint(1) NOT NULL DEFAULT 0,
  `edit_vehicle_delete` tinyint(1) NOT NULL DEFAULT 0,
  `edit_home` tinyint(1) NOT NULL DEFAULT 0,
  `edit_home_delete` tinyint(1) NOT NULL DEFAULT 0,
  `edit_gang` tinyint(1) NOT NULL DEFAULT 0,
  `edit_gang_members` tinyint(1) NOT NULL DEFAULT 0,
  `edit_container` tinyint(1) NOT NULL DEFAULT 0,
  `staff_manage` tinyint(1) NOT NULL DEFAULT 0,
  `staff_logs` tinyint(1) NOT NULL DEFAULT 0,
  `staff_refund` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `Rank`
--

INSERT INTO `Rank` (`id`, `name`, `isAdmin`, `view_player`, `view_vehicle`, `view_home`, `view_gang`, `view_container`, `edit_player`, `edit_player_license`, `edit_player_money`, `edit_player_cop`, `edit_player_med`, `edit_player_admin`, `edit_player_delete`, `edit_vehicle`, `edit_vehicle_delete`, `edit_home`, `edit_home_delete`, `edit_gang`, `edit_gang_members`, `edit_container`, `staff_manage`, `staff_logs`, `staff_refund`) VALUES
(0, 'Joueur', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(1, 'Fondateur', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(2, 'Développeur', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(3, 'Observateur', 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `Refund`
--

CREATE TABLE `Refund` (
  `id` int(11) NOT NULL,
  `date` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `target` varchar(255) NOT NULL,
  `raison` text NOT NULL,
  `amount` int(11) NOT NULL,
  `url` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `sanction`
--

CREATE TABLE `sanction` (
  `id` int(11) NOT NULL,
  `date` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `target` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `Stats`
--

CREATE TABLE `Stats` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `valeur` varchar(255) NOT NULL,
  `val` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `Stats`
--

-- --------------------------------------------------------

--
-- Structure de la table `Utilisateurs`
--

CREATE TABLE `Utilisateurs` (
  `ID` int(11) NOT NULL,
  `UID` varchar(17) NOT NULL,
  `Email` varchar(250) NOT NULL,
  `Nom` varchar(255) NOT NULL,
  `Password` varchar(250) NOT NULL,
  `insert_time` varchar(255) NOT NULL,
  `LastActivity` int(11) DEFAULT NULL,
  `IP` varchar(255) DEFAULT NULL,
  `SafeCode` varchar(255) NOT NULL DEFAULT 'SUPERSAFECODE#0124',
  `rank` int(11) NOT NULL DEFAULT 0,
  `activate` tinyint(1) NOT NULL DEFAULT 1,
  `steamurl` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `steamcreate` varchar(255) DEFAULT NULL,
  `realname` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `Utilisateurs`
--

INSERT INTO `Utilisateurs` (`ID`, `UID`, `Email`, `Nom`, `Password`, `insert_time`, `LastActivity`, `IP`, `SafeCode`, `rank`, `activate`, `steamurl`, `avatar`, `steamcreate`, `realname`) VALUES
(1, '76561198075514290', 'test@test.fr', 'Test User', '$2y$10$zrp3W8FnHZhBNq1aqObu5eOgXX836Bpi.bA5g5Vb1p7dNakOKmpsa', '2019-09-29 16:39:49', 1589538893, '::1', 'SUPERSAFECODE#0124', 2, 1, 'https://steamcommunity.com/profiles/76561198075514290/', 'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/29/294bdbfa9cab8c954d66a87a58f00769fdcc6fd2_full.jpg', '1352563113', 'Real name not given');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Logs`
--
ALTER TABLE `Logs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `Rank`
--
ALTER TABLE `Rank`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `Refund`
--
ALTER TABLE `Refund`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `sanction`
--
ALTER TABLE `sanction`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `Stats`
--
ALTER TABLE `Stats`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `Utilisateurs`
--
ALTER TABLE `Utilisateurs`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Logs`
--
ALTER TABLE `Logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3969;

--
-- AUTO_INCREMENT pour la table `Refund`
--
ALTER TABLE `Refund`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `sanction`
--
ALTER TABLE `sanction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Stats`
--
ALTER TABLE `Stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `Utilisateurs`
--
ALTER TABLE `Utilisateurs`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
