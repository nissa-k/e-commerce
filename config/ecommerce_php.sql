-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 05 fév. 2026 à 07:50
-- Version du serveur : 11.3.2-MariaDB
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ecommerce_php`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `slug`, `name`, `image`) VALUES
(1, 'dev', 'Développement', 'dev.jpg'),
(2, 'cyber', 'Cybersécurité', 'cybersecurite.jpg'),
(3, 'reseau', 'Réseau', 'reseau.jpg'),
(4, 'programmation', 'Programmation', 'programmation.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `courses`
--

DROP TABLE IF EXISTS `courses`;
CREATE TABLE IF NOT EXISTS `courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(220) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `level` enum('debutant','intermediaire','avance') NOT NULL DEFAULT 'debutant',
  `published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `courses`
--

INSERT INTO `courses` (`id`, `category_id`, `title`, `slug`, `description`, `price`, `level`, `published`, `created_at`, `image`) VALUES
(1, 1, 'PHP – Site dynamique (PDO + Sessions)', 'dev-php-pdo-sessions', 'PDO, requêtes préparées, sessions, login/register, pages dynamiques.', 49.90, 'debutant', 1, '2026-01-13 10:09:12', 'php_site-dynamique.jpg'),
(2, 1, 'PHP – CRUD Admin (Back-office)', 'dev-php-crud-admin', 'Créer un back-office : ajouter/modifier/supprimer, validation, sécurité.', 54.90, 'intermediaire', 1, '2026-01-13 10:09:12', 'PHP_CRUD_Admin.jpg'),
(3, 1, 'HTML/CSS – Site responsive', 'dev-html-css-responsive', 'Mise en page, flexbox, grid, responsive, bonnes pratiques.', 49.90, 'debutant', 1, '2026-01-13 10:09:12', 'html-css.jpg'),
(4, 1, 'JavaScript – DOM & Formulaires', 'dev-js-dom-formulaires', 'Events, DOM, validation, UX simple, mini-projets.', 49.90, 'debutant', 1, '2026-01-13 10:09:12', 'js.jpg'),
(5, 1, 'API REST – Bases (HTTP/JSON)', 'dev-api-rest-bases', 'Méthodes HTTP, routes, JSON, statuts, tests avec Postman.', 59.90, 'intermediaire', 1, '2026-01-13 10:09:12', 'api_rest.jpg'),
(6, 4, 'Python – Bases + exercices', 'prog-python-bases-exos', 'Variables, conditions, boucles, fonctions + exercices corrigés.', 49.90, 'debutant', 1, '2026-01-13 10:09:12', 'python_bases.jpg'),
(7, 4, 'Python – POO & fichiers', 'prog-python-poo-fichiers', 'Classes, héritage, fichiers, modules + mini-projet.', 69.90, 'intermediaire', 1, '2026-01-13 10:09:12', 'python_poo.jpg'),
(8, 4, 'C# – POO (bases solides)', 'prog-csharp-poo-bases', 'Classes, interfaces, exceptions, collections + pratique.', 49.90, 'intermediaire', 1, '2026-01-13 10:09:12', 'pooc.jpg'),
(9, 4, 'Java – POO & Collections', 'prog-java-poo-collections', 'POO, collections, exceptions, exercices progressifs.', 55.90, 'intermediaire', 1, '2026-01-13 10:09:12', 'java_poo.jpg'),
(10, 4, 'Algorithmes – Tri/Recherche (bases)', 'prog-algo-tri-recherche', 'Complexité, tris simples, recherche, structures de base.', 34.90, 'debutant', 1, '2026-01-13 10:09:12', 'algo_tri.jpg'),
(11, 2, 'Wireshark – Analyse de trafic (défensif)', 'cyber-wireshark-analyse', 'Filtres, TCP/UDP, DNS/HTTP, lecture de captures + cas pratiques.', 49.90, 'intermediaire', 1, '2026-01-13 10:09:12', 'wireshark.jpg'),
(12, 2, 'Analyse de logs – Windows/Linux (bases)', 'cyber-analyse-logs', 'Comprendre logs, authentification, erreurs, corrélation simple.', 44.90, 'debutant', 1, '2026-01-13 10:09:12', 'analyse_de_log.jpg'),
(13, 2, 'Nmap – Découverte réseau (cadre légal)', 'cyber-nmap-legal', 'Découverte d’hôtes/services, lecture des résultats, éthique.', 34.90, 'debutant', 1, '2026-01-13 10:09:12', 'nmap.jpg'),
(14, 2, 'VPN – Principes & usages', 'cyber-vpn-principes', 'Tunnel, chiffrement, scénarios, bonnes pratiques.', 59.90, 'intermediaire', 1, '2026-01-13 10:09:12', 'vpn.jpg'),
(15, 2, 'Sécurité Web – OWASP (bases)', 'cyber-owasp-bases', 'Injections, XSS, CSRF, sessions, bonnes pratiques côté dev.', 34.90, 'intermediaire', 1, '2026-01-13 10:09:12', 'owasp.jpg'),
(16, 3, 'Adressage IP & Subnetting', 'reseaux-ip-subnetting', 'IPv4, CIDR, calcul de sous-réseaux + exercices corrigés.', 29.90, 'debutant', 1, '2026-01-13 10:09:12', 'ip.jpg'),
(17, 3, 'Bases TCP/IP & OSI', 'reseaux-tcpip-osi', 'Modèles OSI/TCP-IP, ports, DNS/DHCP, ARP, ping/traceroute.', 29.90, 'debutant', 1, '2026-01-13 10:09:12', 'tcp_ip.jpg'),
(18, 3, 'Cisco – VLAN & Switching (Packet Tracer)', 'reseaux-cisco-vlan-switch', 'VLAN, trunk, inter-VLAN (bases), config et vérifications.', 49.90, 'intermediaire', 1, '2026-01-13 10:09:12', 'vlan_cisco.jpg'),
(19, 3, 'Cisco – Routage statique (lab)', 'reseaux-cisco-routing-statique', 'Routes statiques, passerelles, tests, dépannage simple.', 44.90, 'intermediaire', 1, '2026-01-13 10:09:12', 'cisco_routage.jpg'),
(20, 3, 'Dépannage réseau – Méthode', 'reseaux-depannage-methode', 'Méthode de diagnostic, commandes, symptômes, cas pratiques.', 49.90, 'intermediaire', 1, '2026-01-13 10:09:12', 'depannage_reseau.jpg'),
(24, 2, 'osint', 'recherche', 'Apprends a pister tes voisins en toute discrétion !!!', 49.90, 'avance', 1, '2026-02-04 12:24:28', 'course_69832c6c9355c8.28846227.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `course_lessons`
--

DROP TABLE IF EXISTS `course_lessons`;
CREATE TABLE IF NOT EXISTS `course_lessons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` longtext NOT NULL,
  `resource_path` varchar(255) DEFAULT NULL,
  `position` int(11) NOT NULL DEFAULT 1,
  `is_preview` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `section_id` (`section_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `course_sections`
--

DROP TABLE IF EXISTS `course_sections`;
CREATE TABLE IF NOT EXISTS `course_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `position` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `enrollments`
--

DROP TABLE IF EXISTS `enrollments`;
CREATE TABLE IF NOT EXISTS `enrollments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_enroll` (`user_id`,`course_id`),
  KEY `course_id` (`course_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `enrollments`
--

INSERT INTO `enrollments` (`id`, `user_id`, `course_id`, `created_at`) VALUES
(1, 2, 20, '2026-01-23 21:38:23'),
(2, 3, 2, '2026-01-31 18:28:09'),
(3, 3, 1, '2026-01-31 19:28:13'),
(4, 4, 17, '2026-01-31 21:09:52'),
(7, 3, 24, '2026-02-04 14:04:28'),
(8, 3, 11, '2026-02-04 18:41:19');

-- --------------------------------------------------------

--
-- Structure de la table `invoice`
--

DROP TABLE IF EXISTS `invoice`;
CREATE TABLE IF NOT EXISTS `invoice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `transaction_date` datetime NOT NULL DEFAULT current_timestamp(),
  `amount` decimal(10,2) NOT NULL,
  `billing_address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_invoice_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `invoice`
--

INSERT INTO `invoice` (`id`, `user_id`, `transaction_date`, `amount`, `billing_address`, `city`, `postal_code`) VALUES
(1, 2, '2026-01-23 21:38:22', 49.90, 'x', 'x', 'x'),
(2, 3, '2026-01-31 18:28:09', 54.90, 'paule valery', 'creil', '60100'),
(3, 3, '2026-01-31 19:28:13', 49.90, 'paule valery', 'creil', '60100'),
(4, 4, '2026-01-31 21:09:52', 29.90, 'paule valery', 'creil', '60100'),
(5, 3, '2026-02-04 14:04:28', 154.70, 'paule valery', 'creil', '60100'),
(6, 3, '2026-02-04 18:41:19', 49.90, 'paule valery', 'creil', '60100');

-- --------------------------------------------------------

--
-- Structure de la table `items`
--

DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `published_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_orders_user` (`user_id`),
  KEY `fk_orders_item` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `orders_shop`
--

DROP TABLE IF EXISTS `orders_shop`;
CREATE TABLE IF NOT EXISTS `orders_shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `status` enum('pending','paid','failed','cancelled') NOT NULL DEFAULT 'pending',
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `orders_shop`
--

INSERT INTO `orders_shop` (`id`, `user_id`, `status`, `total_amount`, `created_at`) VALUES
(1, 2, 'paid', 49.90, '2026-01-23 21:38:23'),
(2, 3, 'paid', 54.90, '2026-01-31 18:28:09'),
(3, 3, 'paid', 49.90, '2026-01-31 19:28:13'),
(4, 4, 'paid', 29.90, '2026-01-31 21:09:52'),
(5, 3, 'paid', 154.70, '2026-02-04 14:04:28'),
(6, 3, 'paid', 49.90, '2026-02-04 18:41:19');

-- --------------------------------------------------------

--
-- Structure de la table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `course_id` (`course_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `course_id`, `unit_price`) VALUES
(1, 1, 20, 49.90),
(2, 2, 2, 54.90),
(3, 3, 1, 49.90),
(4, 4, 17, 29.90),
(5, 5, 1, 49.90),
(6, 5, 2, 54.90),
(7, 5, 24, 49.90),
(8, 6, 11, 49.90);

-- --------------------------------------------------------

--
-- Structure de la table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `provider` enum('stripe','paypal','fake') NOT NULL DEFAULT 'fake',
  `provider_ref` varchar(255) DEFAULT NULL,
  `status` enum('pending','succeeded','failed') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `provider`, `provider_ref`, `status`, `created_at`) VALUES
(1, 1, 'fake', NULL, 'succeeded', '2026-01-23 21:38:23'),
(2, 2, 'fake', NULL, 'succeeded', '2026-01-31 18:28:09'),
(3, 3, 'fake', NULL, 'succeeded', '2026-01-31 19:28:13'),
(4, 4, 'fake', NULL, 'succeeded', '2026-01-31 21:09:52'),
(5, 5, 'fake', NULL, 'succeeded', '2026-02-04 14:04:28'),
(6, 6, 'fake', NULL, 'succeeded', '2026-02-04 18:41:19');

-- --------------------------------------------------------

--
-- Structure de la table `stock`
--

DROP TABLE IF EXISTS `stock`;
CREATE TABLE IF NOT EXISTS `stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(2, 'Dupont', 'Dupont@gmail.com', '$2y$10$4ij5SqgL1jafWUi/dIgIbeG9ubXW131GTm63jLAnD08xhzGTxMAEi', 'user', '2026-01-14 10:03:25'),
(3, 'daniel', 'daniel13@gmail.com', '$2y$10$thzBltVECxHApxikxbOwp.Lf678aGBuK25TK6mzrxSTEAXOx7oZUu', 'admin', '2026-01-31 18:27:26'),
(4, 'martiel', 'martiel@gmail.com', '$2y$10$AevLEyY44umwSONVvQQ4WOu0zrZ5ey7jq2nmAJ33bWebgUmDMFwJC', 'user', '2026-01-31 20:55:18');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Contraintes pour la table `course_lessons`
--
ALTER TABLE `course_lessons`
  ADD CONSTRAINT `course_lessons_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `course_sections` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `course_sections`
--
ALTER TABLE `course_sections`
  ADD CONSTRAINT `course_sections_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `fk_invoice_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_item` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `orders_shop`
--
ALTER TABLE `orders_shop`
  ADD CONSTRAINT `orders_shop_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders_shop` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);

--
-- Contraintes pour la table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders_shop` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `fk_stock_item` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
