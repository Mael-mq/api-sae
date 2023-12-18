-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 18, 2023 at 08:21 AM
-- Server version: 8.0.31
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `harmonize`
--

-- --------------------------------------------------------

--
-- Table structure for table `cours_app`
--

DROP TABLE IF EXISTS `cours_app`;
CREATE TABLE IF NOT EXISTS `cours_app` (
  `id` int NOT NULL AUTO_INCREMENT,
  `instrument_id` int DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `difficulty` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_69A564A8CF11D9C` (`instrument_id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cours_app`
--

INSERT INTO `cours_app` (`id`, `instrument_id`, `title`, `difficulty`) VALUES
(52, 52, 'Ut perspiciatis quaerat quia esse natus aliquam autem.', 'Moyen'),
(53, 54, 'Mollitia nihil odio aspernatur dolores corrupti voluptatem soluta.', 'Moyen'),
(54, 54, 'Et non commodi perspiciatis ut.', 'Difficile'),
(55, 43, 'Aliquid quos est laudantium illo recusandae earum.', 'Difficile'),
(56, 44, 'Modi voluptate corporis qui unde placeat nesciunt voluptas a.', 'Facile'),
(57, 53, 'Eos alias ipsum ducimus non.', 'Facile'),
(58, 53, 'Consequatur et cumque tempore.', 'Difficile'),
(59, 49, 'Animi esse earum consequuntur.', 'Facile'),
(60, 53, 'Quaerat aliquam debitis nostrum autem.', 'Difficile'),
(61, 46, 'Fugiat accusamus necessitatibus id voluptatibus architecto omnis eos.', 'Moyen'),
(62, 41, 'Cum dignissimos modi corporis.', 'Facile'),
(63, 55, 'Cupiditate consequatur quas ratione debitis unde.', 'Moyen'),
(64, 45, 'Dolor sunt sed voluptas hic nulla.', 'Moyen'),
(65, 42, 'Un cours difficile', 'Difficile'),
(66, 51, 'Sequi quia et a nisi ab quia ut.', 'Difficile');

-- --------------------------------------------------------

--
-- Table structure for table `cours_app_user`
--

DROP TABLE IF EXISTS `cours_app_user`;
CREATE TABLE IF NOT EXISTS `cours_app_user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `cours_app_id` int DEFAULT NULL,
  `is_finished` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4F295D34A76ED395` (`user_id`),
  KEY `IDX_4F295D3416073045` (`cours_app_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cours_app_user`
--

INSERT INTO `cours_app_user` (`id`, `user_id`, `cours_app_id`, `is_finished`) VALUES
(1, 9, 65, 0),
(2, 9, NULL, 0),
(3, 9, 66, 1);

-- --------------------------------------------------------

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20231207103302', '2023-12-07 10:33:12', 26),
('DoctrineMigrations\\Version20231207145500', '2023-12-07 14:58:35', 199),
('DoctrineMigrations\\Version20231211105723', '2023-12-11 10:57:42', 80),
('DoctrineMigrations\\Version20231212103821', '2023-12-12 10:38:34', 235),
('DoctrineMigrations\\Version20231213133125', '2023-12-13 13:33:52', 458),
('DoctrineMigrations\\Version20231215092043', '2023-12-15 09:20:54', 265);

-- --------------------------------------------------------

--
-- Table structure for table `exercice_app`
--

DROP TABLE IF EXISTS `exercice_app`;
CREATE TABLE IF NOT EXISTS `exercice_app` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exercice_app`
--

INSERT INTO `exercice_app` (`id`, `title`) VALUES
(1, 'Un exercice moyen'),
(2, 'Exercice intéressant'),
(3, 'Un exercice amusant');

-- --------------------------------------------------------

--
-- Table structure for table `exercice_app_user`
--

DROP TABLE IF EXISTS `exercice_app_user`;
CREATE TABLE IF NOT EXISTS `exercice_app_user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `exercice_app_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `is_finished` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_202797CAA008F637` (`exercice_app_id`),
  KEY `IDX_202797CAA76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exercice_app_user`
--

INSERT INTO `exercice_app_user` (`id`, `exercice_app_id`, `user_id`, `is_finished`) VALUES
(1, 1, 10, 1),
(2, 3, 9, 0);

-- --------------------------------------------------------

--
-- Table structure for table `instrument`
--

DROP TABLE IF EXISTS `instrument`;
CREATE TABLE IF NOT EXISTS `instrument` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `instrument`
--

INSERT INTO `instrument` (`id`, `name`) VALUES
(41, 'Guitare'),
(42, 'Basse'),
(43, 'Batterie'),
(44, 'Piano'),
(45, 'Violon'),
(46, 'Violoncelle'),
(47, 'Flûte'),
(48, 'Saxophone'),
(49, 'Trompette'),
(50, 'Trombone'),
(51, 'Harpe'),
(52, 'Accordéon'),
(53, 'Orgue'),
(54, 'Synthétiseur'),
(55, 'Chant');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
CREATE TABLE IF NOT EXISTS `student` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B723AF33A76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `user_id`) VALUES
(1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

DROP TABLE IF EXISTS `teacher`;
CREATE TABLE IF NOT EXISTS `teacher` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B0F6A6D5A76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`id`, `user_id`) VALUES
(1, 9);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`) VALUES
(7, 'user@mmi.fr', '[\"ROLE_USER\"]', '$2y$13$bquL0oKzHVYUUk8ZnhrnwOpVdOXrWm.qjnzjewQCQgq/tndfXIcHy'),
(8, 'admin@mmi.fr', '[\"ROLE_ADMIN\"]', '$2y$13$bzJCuAYmdd/Bfld1a6LINeS0aUPcKH5uB.7w9Jj0TIeXkctSq6uOq'),
(9, 'teacher@mmi.fr', '[\"ROLE_TEACHER\"]', '$2y$13$vNypRvz37oqYMEKSD/MCjO6gQ5RewYh15tjNNfQP4XYADG/hIyxg6'),
(10, 'student@mmi.fr', '[\"ROLE_STUDENT\"]', '$2y$13$PCi/NSnh6BkFqKmIbzzO6ej5qmbtlA/TOKA4UqqAc8dc5gdtDprwK');

-- --------------------------------------------------------

--
-- Table structure for table `user_instrument`
--

DROP TABLE IF EXISTS `user_instrument`;
CREATE TABLE IF NOT EXISTS `user_instrument` (
  `id` int NOT NULL AUTO_INCREMENT,
  `instrument_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9BD8AF31CF11D9C` (`instrument_id`),
  KEY `IDX_9BD8AF31A76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_instrument`
--

INSERT INTO `user_instrument` (`id`, `instrument_id`, `user_id`) VALUES
(1, 41, 10),
(4, 45, 9);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cours_app`
--
ALTER TABLE `cours_app`
  ADD CONSTRAINT `FK_69A564A8CF11D9C` FOREIGN KEY (`instrument_id`) REFERENCES `instrument` (`id`);

--
-- Constraints for table `cours_app_user`
--
ALTER TABLE `cours_app_user`
  ADD CONSTRAINT `FK_4F295D3416073045` FOREIGN KEY (`cours_app_id`) REFERENCES `cours_app` (`id`),
  ADD CONSTRAINT `FK_4F295D34A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `exercice_app_user`
--
ALTER TABLE `exercice_app_user`
  ADD CONSTRAINT `FK_202797CAA008F637` FOREIGN KEY (`exercice_app_id`) REFERENCES `exercice_app` (`id`),
  ADD CONSTRAINT `FK_202797CAA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `FK_B723AF33A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `teacher`
--
ALTER TABLE `teacher`
  ADD CONSTRAINT `FK_B0F6A6D5A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `user_instrument`
--
ALTER TABLE `user_instrument`
  ADD CONSTRAINT `FK_9BD8AF31A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_9BD8AF31CF11D9C` FOREIGN KEY (`instrument_id`) REFERENCES `instrument` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
