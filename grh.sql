-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 10 mai 2024 à 10:03
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `grh`
--

-- --------------------------------------------------------

--
-- Structure de la table `congés`
--

CREATE TABLE `congés` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(25) NOT NULL,
  `DateDebut` date NOT NULL,
  `DateFin` date NOT NULL,
  `NbrJour` int(11) NOT NULL,
  `CauseConge` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `congés`
--

INSERT INTO `congés` (`id`, `user_id`, `type`, `DateDebut`, `DateFin`, `NbrJour`, `CauseConge`, `photo`, `created_at`, `status`) VALUES
(8, 2, 'simple', '2024-05-29', '2024-06-08', 10, 'vacance', '', '2024-05-10 07:59:25', 'accepter');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nom` varchar(25) NOT NULL,
  `prenom` varchar(25) NOT NULL,
  `tel` varchar(155) NOT NULL,
  `email` varchar(25) NOT NULL,
  `password` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `role` varchar(25) NOT NULL DEFAULT 'employee',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `NbJourConge` int(11) NOT NULL DEFAULT 45,
  `RaisonSociale` varchar(11) NOT NULL DEFAULT 'dhahbi_web',
  `code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `prenom`, `tel`, `email`, `password`, `photo`, `role`, `status`, `NbJourConge`, `RaisonSociale`, `code`) VALUES
(1, 'omar', 'dhahbi', '98427463', 'omardhahbi68@gmail.com', '202cb962ac59075b964b07152d234b70', 'téléchargement.jpg', 'admin', 1, 45, 'dhahbi_web', NULL),
(2, 'ali', 'ali ', '98427461', 'odhahbi31@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'UseCaseDiagram1.jpg', 'employee', 1, 35, 'dhahbi_web', 'NULL'),
(21, 'ali', 'ali', '111111', 'dhahbiomar62@gmail.com', '202cb962ac59075b964b07152d234b70', 'UseCaseDiagram1.jpg', 'employee', 1, 45, 'dhahbi_web', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `congés`
--
ALTER TABLE `congés`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `congés`
--
ALTER TABLE `congés`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `congés`
--
ALTER TABLE `congés`
  ADD CONSTRAINT `congés_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
