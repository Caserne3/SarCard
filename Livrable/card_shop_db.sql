-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 14 avr. 2026 à 00:09
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
-- Base de données : `card_shop_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `cards`
--

CREATE TABLE `cards` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `rarity` enum('commune','rare','legendaire') NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cards`
--

INSERT INTO `cards` (`id`, `name`, `image_url`, `rarity`, `description`) VALUES
(1, 'Carte Tempo 1', 'assets/img/cards/1.png', 'commune', 'Une carte basique en attendant les vrais visuels.'),
(2, 'Carte Tempo 2', 'assets/img/cards/2.png', 'commune', 'Une carte basique en attendant les vrais visuels.'),
(3, 'Carte Tempo 3', 'assets/img/cards/3.png', 'commune', 'Une carte basique en attendant les vrais visuels.'),
(4, 'Le Dauphin Surfeur', 'assets/img/cards/DauphinSurfeur.png', 'rare', 'Le roi des vagues, toujours à la cool.'),
(5, 'Ours Polaire Chilleur', 'assets/img/cards/OursChilleur.png', 'rare', 'Détente maximale sous les tropiques.'),
(6, 'Le Serpent Gambleur', 'assets/img/cards/SerpentGambleur.png', 'rare', 'Il a toujours un as dans sa manche.'),
(7, 'Le Marseillais Blindé', 'assets/img/cards/Marseillais.png', 'legendaire', 'Trop de flow, trop de billets.'),
(8, 'Le Style de Miamibui', 'assets/img/cards/MiamiBui.png', 'legendaire', 'La classe internationale à l\'état pur.');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `credits` int(11) DEFAULT 500,
  `created_at` datetime DEFAULT current_timestamp(),
  `last_reward_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `credits`, `created_at`, `last_reward_date`) VALUES
(2, 'Suicide', 'Suicide@suicide.com', '$2y$10$bFNvZBNqnSQ6tEYTRBiqQeiqyLr9tj/I9L1k4D0BJYbJ8kl0l.2RO', 1000906, '2026-03-03 12:11:32', '2026-03-17 12:02:16'),
(3, 'Kys', 'Kys@Kys', '$2y$10$grI4pzRaVs6/xc1qmn./Uuq5qZYJoclnW71PTqWt.6WKIUHNlb4zu', 93, '2026-03-10 16:23:48', '2026-03-17 11:32:33'),
(4, 'Test', 'test@testme', '$2y$10$VX1YULjYN3pbZg/nZvbp3.mawlbRezBAvJ.6ZlyOzvmOMFJGyY/pm', 150, '2026-04-13 20:31:16', '2026-04-13 20:31:29');

-- --------------------------------------------------------

--
-- Structure de la table `user_cards`
--

CREATE TABLE `user_cards` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `card_id` int(11) NOT NULL,
  `is_for_sale` tinyint(1) DEFAULT 0,
  `sale_price` int(11) DEFAULT 0,
  `obtained_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user_cards`
--

INSERT INTO `user_cards` (`id`, `user_id`, `card_id`, `is_for_sale`, `sale_price`, `obtained_date`) VALUES
(5, 2, 3, 0, 0, '2026-03-03 12:19:29'),
(6, 2, 4, 0, 0, '2026-03-03 12:19:32'),
(7, 2, 8, 0, 0, '2026-03-03 12:19:35'),
(8, 2, 8, 0, 0, '2026-03-03 12:19:37'),
(9, 2, 5, 0, 0, '2026-03-03 12:19:40'),
(10, 2, 2, 0, 0, '2026-03-03 12:19:41'),
(11, 2, 3, 0, 0, '2026-03-03 12:19:42'),
(12, 2, 7, 0, 0, '2026-03-03 12:19:42'),
(13, 2, 8, 0, 0, '2026-03-03 12:19:52'),
(14, 2, 5, 0, 0, '2026-03-03 12:20:11'),
(15, 2, 7, 0, 0, '2026-03-10 15:41:40'),
(16, 2, 2, 0, 0, '2026-03-10 15:41:49'),
(17, 2, 6, 0, NULL, '2026-03-10 15:41:50'),
(18, 2, 1, 1, 10, '2026-03-10 15:41:54'),
(19, 2, 7, 0, 0, '2026-03-10 15:41:55'),
(20, 2, 3, 0, 0, '2026-03-10 15:41:57'),
(21, 3, 1, 0, NULL, '2026-03-10 15:41:58'),
(22, 2, 4, 0, 0, '2026-03-10 15:42:57'),
(23, 2, 3, 0, 0, '2026-03-10 15:43:00'),
(24, 2, 7, 0, 0, '2026-03-10 15:43:00'),
(25, 2, 4, 0, 0, '2026-03-10 15:43:01'),
(26, 2, 4, 0, 0, '2026-03-10 15:43:02'),
(27, 2, 7, 0, 0, '2026-03-10 15:43:02'),
(28, 2, 2, 0, 0, '2026-03-10 15:43:03'),
(29, 2, 4, 0, 0, '2026-03-10 15:43:04'),
(30, 2, 7, 0, 0, '2026-03-10 15:43:05'),
(31, 2, 5, 0, 0, '2026-03-10 15:43:05'),
(32, 2, 2, 0, 0, '2026-03-10 15:43:06'),
(33, 2, 3, 0, 0, '2026-03-10 15:43:07'),
(34, 2, 8, 0, 0, '2026-03-10 15:43:07'),
(35, 2, 3, 0, 0, '2026-03-10 15:44:34'),
(36, 2, 3, 0, 0, '2026-03-10 15:44:35'),
(37, 2, 7, 0, 0, '2026-03-10 15:44:36'),
(38, 2, 3, 0, 0, '2026-03-10 15:44:38'),
(39, 2, 2, 0, 0, '2026-03-10 15:46:26'),
(40, 2, 8, 0, 0, '2026-03-10 15:46:27'),
(41, 2, 3, 0, 0, '2026-03-10 16:06:03'),
(42, 2, 6, 0, NULL, '2026-03-10 16:24:28'),
(44, 3, 8, 1, 2147483647, '2026-03-10 16:33:11'),
(45, 3, 1, 0, 0, '2026-03-10 16:59:19'),
(46, 2, 2, 0, 0, '2026-03-11 13:00:22'),
(47, 2, 1, 1, 2147483647, '2026-03-11 13:00:23'),
(48, 2, 2, 0, 0, '2026-03-11 13:00:24'),
(49, 2, 8, 0, 0, '2026-03-11 13:00:26'),
(50, 2, 3, 0, 0, '2026-03-11 13:00:27'),
(51, 2, 1, 0, 0, '2026-03-11 13:00:28'),
(52, 2, 3, 0, 0, '2026-03-11 13:00:29'),
(53, 2, 1, 0, 0, '2026-03-11 13:00:30'),
(54, 2, 2, 0, 0, '2026-03-11 13:00:31'),
(55, 2, 6, 0, 0, '2026-03-11 13:00:32'),
(56, 2, 4, 0, 0, '2026-03-11 13:00:33'),
(57, 2, 5, 0, 0, '2026-03-11 13:00:34'),
(58, 2, 1, 0, 0, '2026-03-11 13:00:35'),
(59, 2, 3, 0, 0, '2026-03-11 13:00:35'),
(60, 2, 7, 0, 0, '2026-03-11 13:00:36'),
(61, 3, 8, 0, 0, '2026-03-17 11:32:21'),
(62, 3, 8, 0, 0, '2026-03-17 11:32:25'),
(63, 3, 5, 0, 0, '2026-03-17 11:32:26'),
(64, 3, 5, 0, 0, '2026-03-17 11:32:27'),
(65, 3, 4, 0, 0, '2026-03-17 11:32:28'),
(66, 3, 6, 0, 0, '2026-03-17 11:32:29'),
(67, 2, 7, 0, NULL, '2026-03-17 11:44:54'),
(68, 2, 2, 0, 0, '2026-03-17 12:04:39'),
(69, 2, 3, 0, 0, '2026-03-17 12:04:40'),
(70, 2, 3, 0, 0, '2026-03-17 12:04:41'),
(71, 2, 7, 0, 0, '2026-03-17 12:04:41'),
(72, 2, 5, 0, 0, '2026-03-17 12:04:42'),
(73, 2, 6, 0, 0, '2026-03-17 12:04:43'),
(74, 2, 1, 0, 0, '2026-03-17 12:04:45'),
(75, 2, 3, 0, 0, '2026-03-17 12:13:20'),
(76, 2, 2, 0, 0, '2026-03-17 12:26:01'),
(77, 2, 6, 0, 0, '2026-03-17 12:26:03'),
(78, 2, 1, 0, 0, '2026-03-17 12:26:06'),
(79, 2, 1, 0, 0, '2026-03-17 12:26:36'),
(80, 2, 1, 0, 0, '2026-03-17 12:26:56'),
(81, 2, 8, 0, 0, '2026-03-17 13:50:58'),
(82, 2, 2, 0, 0, '2026-03-17 13:51:06'),
(83, 2, 7, 0, 0, '2026-03-17 13:51:07'),
(84, 2, 2, 0, 0, '2026-03-17 13:51:15'),
(85, 2, 5, 0, 0, '2026-03-31 16:23:24'),
(86, 2, 6, 0, 0, '2026-03-31 16:23:29'),
(87, 2, 6, 1, 10, '2026-03-31 16:23:30'),
(88, 2, 1, 0, 0, '2026-03-31 16:23:31'),
(89, 2, 4, 0, 0, '2026-03-31 16:23:31'),
(90, 2, 4, 0, 0, '2026-03-31 16:23:32'),
(91, 2, 8, 0, 0, '2026-03-31 16:23:33'),
(92, 2, 1, 0, 0, '2026-03-31 16:23:33'),
(93, 2, 3, 0, 0, '2026-03-31 16:23:34'),
(94, 2, 5, 0, 0, '2026-03-31 16:23:35'),
(95, 2, 5, 0, 0, '2026-03-31 16:23:36'),
(96, 2, 3, 0, 0, '2026-03-31 16:23:36'),
(97, 2, 2, 0, 0, '2026-03-31 16:23:37'),
(98, 2, 7, 1, 5000, '2026-03-31 16:23:38'),
(99, 2, 6, 0, 0, '2026-04-03 19:05:22'),
(100, 2, 2, 0, 0, '2026-04-13 20:12:23');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `user_cards`
--
ALTER TABLE `user_cards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`),
  ADD KEY `fk_card` (`card_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `user_cards`
--
ALTER TABLE `user_cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `user_cards`
--
ALTER TABLE `user_cards`
  ADD CONSTRAINT `fk_card` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
