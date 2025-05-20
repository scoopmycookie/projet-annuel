-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mar. 20 mai 2025 à 21:30
-- Version du serveur : 5.7.24
-- Version de PHP : 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `businesscare`
--

-- --------------------------------------------------------

--
-- Structure de la table `chatbot_faq`
--

CREATE TABLE `chatbot_faq` (
  `id` int(11) NOT NULL,
  `question` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `response` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `chatbot_faq`
--

INSERT INTO `chatbot_faq` (`id`, `question`, `response`) VALUES
(1, 'congé', 'Les demandes de congés se font depuis votre espace personnel dans l’onglet \"Absences\".'),
(2, 'arrêt maladie', 'En cas d’arrêt maladie, prévenez votre manager et envoyez votre justificatif à RH.'),
(3, 'horaires', 'Nos horaires sont du lundi au vendredi de 9h à 18h.');

-- --------------------------------------------------------

--
-- Structure de la table `chat_logs`
--

CREATE TABLE `chat_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `chat_logs`
--

INSERT INTO `chat_logs` (`id`, `user_id`, `question`, `created_at`) VALUES
(1, 10, 'comment faire', '2025-05-09 01:31:07'),
(2, 10, 'comment faire', '2025-05-09 01:34:43'),
(3, 10, '[BOT] Je n’ai pas compris votre question. Veuillez reformuler.', '2025-05-09 01:34:43'),
(4, 10, 'comment je fait pour m\'inscrire', '2025-05-09 01:34:58'),
(5, 10, '[BOT] Je n’ai pas compris votre question. Veuillez reformuler.', '2025-05-09 01:34:58'),
(6, 10, 'comment je fait pour me deconnecter', '2025-05-09 01:35:20'),
(7, 10, '[BOT] Je n’ai pas compris votre question. Veuillez reformuler.', '2025-05-09 01:35:20'),
(8, 10, 'horaire', '2025-05-09 01:35:47'),
(9, 10, '[BOT] Les horaires varient selon les services. Merci de consulter votre tableau de bord.', '2025-05-09 01:35:47'),
(10, 10, 'Quels sont mes horaires ?', '2025-05-09 01:39:13'),
(11, 10, '[BOT] Les horaires sont disponibles sur la page Services.', '2025-05-09 01:39:13'),
(12, 10, 'Comment payer ma facture ?', '2025-05-09 01:39:23'),
(13, 10, '[BOT] Je n’ai pas compris votre question. Essayez un mot comme “horaire”, “paiement”, “aide”...', '2025-05-09 01:39:23');

-- --------------------------------------------------------

--
-- Structure de la table `community_comments`
--

CREATE TABLE `community_comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `community_comments`
--

INSERT INTO `community_comments` (`id`, `post_id`, `user_id`, `comment`, `created_at`) VALUES
(2, 2, 10, 'blabla\r\n', '2025-05-20 20:19:38'),
(3, 1, 10, 'sympa\r\n', '2025-05-20 20:19:49');

-- --------------------------------------------------------

--
-- Structure de la table `community_posts`
--

CREATE TABLE `community_posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `community_posts`
--

INSERT INTO `community_posts` (`id`, `user_id`, `title`, `content`, `created_at`) VALUES
(1, 10, 'fournisseur 2', 'il est trés bien \r\n', '2025-05-20 17:38:21'),
(2, 10, 'le Site ', 'il est bien fait \r\n', '2025-05-20 20:14:55');

-- --------------------------------------------------------

--
-- Structure de la table `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `siret` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `industry` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_street` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_postal_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_country` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `representative_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `employees` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `companies`
--

INSERT INTO `companies` (`id`, `name`, `siret`, `industry`, `email`, `phone`, `website`, `address_street`, `address_city`, `address_postal_code`, `address_country`, `representative_name`, `created_at`, `employees`) VALUES
(1, 'fournicorp', '12345678901234567809', NULL, 'rayangoolamhossen@gmail.com', '0659953647', 'https://wekkkkbsite.com', 'erard', 'paris', '75012', 'France', 'rayan goolamhossen', '2025-05-06 14:33:51', 1),
(4, 'ZenCorp', NULL, NULL, 'contact@zencorp.fr', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-07 00:12:27', 25),
(9, 'busi corp', '12345678901234567890', NULL, 'rayangoolamhossen@gmail.com', '0659953647', 'https://website.com', 'erard', 'paris', '75012', 'France', 'rayan goolamhossen', '2025-05-06 14:49:39', 1),
(11, 'InfiniPlus	', '23456789012345678901', NULL, 'karim.benali@infiniplus.com', '0623456789', 'www.infiniplus.com', '8 allée Orme', 'Marseille	', '13001', 'France', 'Karim Benali', '2025-05-06 15:15:15', 1),
(12, 'NovaCorp', NULL, NULL, 'contact@novacorp.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-07 00:50:19', 20),
(23, 'Helios Group', NULL, NULL, 'admin@helios.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-07 00:50:19', 80),
(31, 'OrionTech', NULL, NULL, 'hello@oriontech.fr', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-07 00:50:19', 320),
(32, 'busi corpza', '12345678901234567891', NULL, 'joe@gmail.com', '0659953632', 'https://wekkkkbsite.com', 'erard', 'paris', '75012', 'France', 'joe', '2025-05-09 01:59:38', 1),
(33, 'ti', '12345678901234567990', NULL, 'TI@gmail.com', '0659953641', 'https://wekkkkbsite.coms', 'erard2', 'pa', '75012', 'France', 'TI', '2025-05-09 01:59:40', 1),
(34, 'car', '10191817161514131211', '', 'joo@gmail.com', '0659953648', 'https://wsssssssssssssebsite.com', 'erardss', 'Marseille', '75014', 'France', 'joo', '2025-05-09 01:59:41', 4);

-- --------------------------------------------------------

--
-- Structure de la table `contracts`
--

CREATE TABLE `contracts` (
  `id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `plan` enum('starter','basic','premium') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','active','expired') COLLATE utf8mb4_unicode_ci DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `contracts`
--

INSERT INTO `contracts` (`id`, `company_id`, `plan`, `start_date`, `end_date`, `amount`, `status`) VALUES
(3, 34, 'basic', '2025-05-16', '2026-05-16', '1800.00', 'pending');

-- --------------------------------------------------------

--
-- Structure de la table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `position` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `quote_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('paid','unpaid') COLLATE utf8mb4_unicode_ci DEFAULT 'unpaid',
  `due_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `end_date` date DEFAULT NULL,
  `sent` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `invoices`
--

INSERT INTO `invoices` (`id`, `company_id`, `quote_id`, `amount`, `status`, `due_date`, `created_at`, `end_date`, `sent`) VALUES
(1, 1, NULL, '500.00', 'paid', '2025-06-01', '2025-05-07 02:11:18', NULL, 0),
(2, 4, NULL, '4500.00', 'unpaid', '2025-06-01', '2025-05-07 02:12:27', NULL, 0),
(8, 33, NULL, '180.00', 'unpaid', NULL, '2025-05-09 03:59:40', NULL, 0),
(9, 9, NULL, '180.00', 'paid', NULL, '2025-05-09 03:59:41', NULL, 0),
(11, 11, NULL, '100.00', 'paid', NULL, '2025-05-09 02:16:45', NULL, 0);

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_read` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `recipient_id`, `message`, `created_at`, `is_read`) VALUES
(1, 8, 3, 'test', '2025-05-09 00:38:55', 0),
(2, 8, 3, 'test', '2025-05-09 00:40:13', 0),
(3, 3, 8, 'test toi meme', '2025-05-09 00:44:18', 0),
(4, 3, 10, 'test', '2025-05-09 00:46:51', 0);

-- --------------------------------------------------------

--
-- Structure de la table `pricing`
--

CREATE TABLE `pricing` (
  `id` int(11) NOT NULL,
  `pack` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_min` int(11) NOT NULL,
  `employee_max` int(11) DEFAULT NULL,
  `tarif` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `pricing`
--

INSERT INTO `pricing` (`id`, `pack`, `employee_min`, `employee_max`, `tarif`) VALUES
(1, 'Starter', 0, 30, 180),
(2, 'Basic', 31, 250, 150),
(3, 'Premium', 251, NULL, 100);

-- --------------------------------------------------------

--
-- Structure de la table `provider_availability`
--

CREATE TABLE `provider_availability` (
  `id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `available_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `provider_availability`
--

INSERT INTO `provider_availability` (`id`, `provider_id`, `available_date`, `start_time`, `end_time`) VALUES
(3, 11, '2025-05-24', '11:11:00', '12:12:00');

-- --------------------------------------------------------

--
-- Structure de la table `provider_invoices`
--

CREATE TABLE `provider_invoices` (
  `id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('paid','unpaid') COLLATE utf8mb4_unicode_ci DEFAULT 'unpaid',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `quotes`
--

CREATE TABLE `quotes` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `quotes`
--

INSERT INTO `quotes` (`id`, `company_id`, `amount`, `status`, `created_at`) VALUES
(1, 1, '3600.00', 'approved', '2025-05-07 02:50:39'),
(4, 1, '1800.00', 'rejected', '2025-05-04 02:50:39');

-- --------------------------------------------------------

--
-- Structure de la table `registration_requests`
--

CREATE TABLE `registration_requests` (
  `id` int(11) NOT NULL,
  `company_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `siret` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `representative_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_street` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_postal_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_country` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('client','provider') COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `employees` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `service_date` date NOT NULL,
  `service_time` time NOT NULL,
  `capacity` int(11) DEFAULT '0',
  `duration` int(11) DEFAULT '60',
  `provider_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `services`
--

INSERT INTO `services` (`id`, `title`, `description`, `category`, `price`, `service_date`, `service_time`, `capacity`, `duration`, `provider_id`) VALUES
(1, 'Yoga matin', 'Séance de yoga relaxante', 'bien-être', '15.00', '2025-05-10', '10:00:00', 20, 60, 11),
(3, 'parc', 'pique nique ', 'detante', '5.50', '2025-05-08', '09:23:00', 50, 180, 11),
(7, 'a', 'a', 'a', '1.00', '2025-05-24', '11:11:00', 1, 60, 11),
(8, 'yoga', 'i', 'bien etre', '20.00', '2025-05-22', '15:10:00', 30, 90, 11);

-- --------------------------------------------------------

--
-- Structure de la table `service_registrations`
--

CREATE TABLE `service_registrations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `service_registrations`
--

INSERT INTO `service_registrations` (`id`, `user_id`, `service_id`, `created_at`) VALUES
(5, 10, 1, '2025-05-09 01:19:48');

-- --------------------------------------------------------

--
-- Structure de la table `service_reviews`
--

CREATE TABLE `service_reviews` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `rating` tinyint(4) DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `service_reviews`
--

INSERT INTO `service_reviews` (`id`, `service_id`, `user_id`, `provider_id`, `rating`, `comment`, `created_at`) VALUES
(1, 1, 10, 11, 3, 'sympa', '2025-05-20 17:08:44');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','client','employee','provider') COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `archive_reason` text COLLATE utf8mb4_unicode_ci,
  `first_login` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `company_id`, `name`, `firstname`, `email`, `phone`, `password`, `role`, `position`, `status`, `created_at`, `archive_reason`, `first_login`) VALUES
(3, NULL, 'Admin Principal', NULL, 'admin@businesscare.com', NULL, 'admin123', 'admin', NULL, 'approved', '2025-05-06 14:19:34', NULL, 1),
(4, 1, 'rayan goolamhossen', NULL, 'rayangoolamhossen@gmail.com', NULL, '$2y$10$GGLmG1BT2KUUtPBQ1yCCS.sVaShiIU2CxwZuj3lPCXV0srpggb.ci', 'provider', NULL, 'approved', '2025-05-06 14:33:51', NULL, 1),
(8, 11, 'Karim Benali', NULL, 'karim.benali@infiniplus.com', NULL, 'moi', 'client', NULL, 'approved', '2025-05-06 15:15:15', NULL, 1),
(9, 9, 'Goolam Hossen', NULL, 'olive@gmail.com', NULL, '$2y$10$P9cxN8J9Uw8oJDR0FLcX5ezj1ahJ02GtzgcZmu7llYmkDtDR.uhNO', 'employee', NULL, 'approved', '2025-05-06 23:02:27', NULL, 1),
(10, 9, 'zackk', 'zack', 'zack@gmail.com', '0610121314', 'moi', 'employee', 'responsable', 'approved', '2025-05-07 01:02:57', NULL, 0),
(11, 1, 'o', 'o', 'o@gmail.com', '0623456789', 'moi', 'provider', 'responsable', 'approved', '2025-05-07 13:49:15', NULL, 1),
(12, 11, 'h', 'h', 'h@gmail.com', '0656473829', 'h', 'employee', 'directeur adjoint ', 'approved', '2025-05-08 23:57:51', NULL, 1),
(13, 32, 'joe', NULL, 'joe@gmail.com', NULL, 'moi', 'client', NULL, 'approved', '2025-05-09 01:59:38', NULL, 1),
(14, 33, 'TI', NULL, 'TI@gmail.com', NULL, '$2y$10$cpHn9YZiP0HoiRwxlF3H2OTNoU92E5xpeZmhCi3d85IKuOI47liue', 'client', NULL, 'approved', '2025-05-09 01:59:40', NULL, 1),
(15, 34, 'joo', NULL, 'joo@gmail.com', NULL, '$2y$10$IciZlL1i4whoZ/wcLpI.1uEKwAKduGdO8mxe58PuRRXYipFGYUMiy', 'provider', NULL, 'approved', '2025-05-09 01:59:41', NULL, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `chatbot_faq`
--
ALTER TABLE `chatbot_faq`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `chat_logs`
--
ALTER TABLE `chat_logs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `community_comments`
--
ALTER TABLE `community_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `community_posts`
--
ALTER TABLE `community_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `siret` (`siret`);

--
-- Index pour la table `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`);

--
-- Index pour la table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `quote_id` (`quote_id`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pricing`
--
ALTER TABLE `pricing`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `provider_availability`
--
ALTER TABLE `provider_availability`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_id` (`provider_id`);

--
-- Index pour la table `provider_invoices`
--
ALTER TABLE `provider_invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `provider_id` (`provider_id`,`year`);

--
-- Index pour la table `quotes`
--
ALTER TABLE `quotes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`);

--
-- Index pour la table `registration_requests`
--
ALTER TABLE `registration_requests`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Index pour la table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `service_registrations`
--
ALTER TABLE `service_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_registration` (`user_id`,`service_id`);

--
-- Index pour la table `service_reviews`
--
ALTER TABLE `service_reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `service_id` (`service_id`,`user_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `provider_id` (`provider_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `company_id` (`company_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `chatbot_faq`
--
ALTER TABLE `chatbot_faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `chat_logs`
--
ALTER TABLE `chat_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `community_comments`
--
ALTER TABLE `community_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `community_posts`
--
ALTER TABLE `community_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT pour la table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `pricing`
--
ALTER TABLE `pricing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `provider_availability`
--
ALTER TABLE `provider_availability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `provider_invoices`
--
ALTER TABLE `provider_invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `quotes`
--
ALTER TABLE `quotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `registration_requests`
--
ALTER TABLE `registration_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `service_registrations`
--
ALTER TABLE `service_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `service_reviews`
--
ALTER TABLE `service_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `community_comments`
--
ALTER TABLE `community_comments`
  ADD CONSTRAINT `community_comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `community_posts` (`id`),
  ADD CONSTRAINT `community_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `community_posts`
--
ALTER TABLE `community_posts`
  ADD CONSTRAINT `community_posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `contracts_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`);

--
-- Contraintes pour la table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoices_ibfk_2` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `provider_availability`
--
ALTER TABLE `provider_availability`
  ADD CONSTRAINT `provider_availability_ibfk_1` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `provider_invoices`
--
ALTER TABLE `provider_invoices`
  ADD CONSTRAINT `provider_invoices_ibfk_1` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `quotes`
--
ALTER TABLE `quotes`
  ADD CONSTRAINT `quotes_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `service_reviews`
--
ALTER TABLE `service_reviews`
  ADD CONSTRAINT `service_reviews_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_reviews_ibfk_3` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
