-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Jan 15, 2024 at 09:01 AM
-- Server version: 8.2.0
-- PHP Version: 8.2.13

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
-- Table structure for table `cours`
--

CREATE TABLE `cours` (
  `id` int NOT NULL,
  `student_id` int DEFAULT NULL,
  `teacher_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cours`
--

INSERT INTO `cours` (`id`, `student_id`, `teacher_id`) VALUES
(2, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cours_app`
--

CREATE TABLE `cours_app` (
  `id` int NOT NULL,
  `instrument_id` int DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `difficulty` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `cours_app_user` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `cours_app_id` int DEFAULT NULL,
  `is_finished` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL
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
('DoctrineMigrations\\Version20231215092043', '2023-12-15 09:20:54', 265),
('DoctrineMigrations\\Version20231219073223', '2023-12-19 07:33:16', 443),
('DoctrineMigrations\\Version20231219103644', '2023-12-19 10:37:11', 213),
('DoctrineMigrations\\Version20231220134143', '2023-12-20 13:42:01', 752),
('DoctrineMigrations\\Version20231221095917', '2023-12-21 09:59:28', 556),
('DoctrineMigrations\\Version20231221100049', '2023-12-21 10:00:58', 47),
('DoctrineMigrations\\Version20240112093312', '2024-01-12 09:33:34', 713),
('DoctrineMigrations\\Version20240115075721', '2024-01-15 07:57:35', 87);

-- --------------------------------------------------------

--
-- Table structure for table `exercice_app`
--

CREATE TABLE `exercice_app` (
  `id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `exercice_app_user` (
  `id` int NOT NULL,
  `exercice_app_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `is_finished` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exercice_app_user`
--

INSERT INTO `exercice_app_user` (`id`, `exercice_app_id`, `user_id`, `is_finished`) VALUES
(1, 1, 10, 1),
(2, 3, 9, 0);

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int NOT NULL,
  `cours_id` int DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `file_size` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `cours_id`, `file_path`, `updated_at`, `file_size`) VALUES
(1, 2, '65a4e954a77cc_[Free-scores.com]_bach-johann-sebastian-cello-suite-no-1-4069.pdf', '2024-01-15 08:14:12', 92761);

-- --------------------------------------------------------

--
-- Table structure for table `instrument`
--

CREATE TABLE `instrument` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int NOT NULL,
  `cours_id` int DEFAULT NULL,
  `sender_id` int DEFAULT NULL,
  `receiver_id` int DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `cours_id`, `sender_id`, `receiver_id`, `content`) VALUES
(2, 2, 9, 10, 'Salut Michel, en raison de fort brouillard je serai en distanciel ce matin.'),
(3, 2, 9, 10, 'Merci pour ta compréhension.');

-- --------------------------------------------------------

--
-- Table structure for table `seance`
--

CREATE TABLE `seance` (
  `id` int NOT NULL,
  `cours_id` int DEFAULT NULL,
  `start_at` datetime DEFAULT NULL,
  `end_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `seance`
--

INSERT INTO `seance` (`id`, `cours_id`, `start_at`, `end_at`) VALUES
(1, 2, '2024-01-20 14:00:00', '2024-01-20 15:00:00'),
(3, NULL, '2023-12-21 14:00:00', '2023-12-21 15:00:00'),
(4, 2, '2023-12-21 14:00:00', '2023-12-21 15:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `sheet`
--

CREATE TABLE `sheet` (
  `id` int NOT NULL,
  `instrument_id` int DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `user_id`) VALUES
(1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`id`, `user_id`) VALUES
(1, 9);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `email` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `user_instrument` (
  `id` int NOT NULL,
  `instrument_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_instrument`
--

INSERT INTO `user_instrument` (`id`, `instrument_id`, `user_id`) VALUES
(1, 41, 10),
(4, 45, 9);

-- --------------------------------------------------------

--
-- Table structure for table `vault_sheet`
--

CREATE TABLE `vault_sheet` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `sheet_id` int DEFAULT NULL,
  `is_favorite` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cours`
--
ALTER TABLE `cours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_FDCA8C9CCB944F1A` (`student_id`),
  ADD KEY `IDX_FDCA8C9C41807E1D` (`teacher_id`);

--
-- Indexes for table `cours_app`
--
ALTER TABLE `cours_app`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_69A564A8CF11D9C` (`instrument_id`);

--
-- Indexes for table `cours_app_user`
--
ALTER TABLE `cours_app_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_4F295D34A76ED395` (`user_id`),
  ADD KEY `IDX_4F295D3416073045` (`cours_app_id`);

--
-- Indexes for table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `exercice_app`
--
ALTER TABLE `exercice_app`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exercice_app_user`
--
ALTER TABLE `exercice_app_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_202797CAA008F637` (`exercice_app_id`),
  ADD KEY `IDX_202797CAA76ED395` (`user_id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_63540597ECF78B0` (`cours_id`);

--
-- Indexes for table `instrument`
--
ALTER TABLE `instrument`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_DB021E967ECF78B0` (`cours_id`),
  ADD KEY `IDX_DB021E96F624B39D` (`sender_id`),
  ADD KEY `IDX_DB021E96CD53EDB6` (`receiver_id`);

--
-- Indexes for table `seance`
--
ALTER TABLE `seance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_DF7DFD0E7ECF78B0` (`cours_id`);

--
-- Indexes for table `sheet`
--
ALTER TABLE `sheet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_873C91E2CF11D9C` (`instrument_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B723AF33A76ED395` (`user_id`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B0F6A6D5A76ED395` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- Indexes for table `user_instrument`
--
ALTER TABLE `user_instrument`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9BD8AF31CF11D9C` (`instrument_id`),
  ADD KEY `IDX_9BD8AF31A76ED395` (`user_id`);

--
-- Indexes for table `vault_sheet`
--
ALTER TABLE `vault_sheet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_DAA45D99A76ED395` (`user_id`),
  ADD KEY `IDX_DAA45D998B1206A5` (`sheet_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cours`
--
ALTER TABLE `cours`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cours_app`
--
ALTER TABLE `cours_app`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `cours_app_user`
--
ALTER TABLE `cours_app_user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `exercice_app`
--
ALTER TABLE `exercice_app`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `exercice_app_user`
--
ALTER TABLE `exercice_app_user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `instrument`
--
ALTER TABLE `instrument`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `seance`
--
ALTER TABLE `seance`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sheet`
--
ALTER TABLE `sheet`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `teacher`
--
ALTER TABLE `teacher`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_instrument`
--
ALTER TABLE `user_instrument`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vault_sheet`
--
ALTER TABLE `vault_sheet`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cours`
--
ALTER TABLE `cours`
  ADD CONSTRAINT `FK_FDCA8C9C41807E1D` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`id`),
  ADD CONSTRAINT `FK_FDCA8C9CCB944F1A` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`);

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
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `FK_63540597ECF78B0` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `FK_DB021E967ECF78B0` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`),
  ADD CONSTRAINT `FK_DB021E96CD53EDB6` FOREIGN KEY (`receiver_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_DB021E96F624B39D` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `seance`
--
ALTER TABLE `seance`
  ADD CONSTRAINT `FK_DF7DFD0E7ECF78B0` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`);

--
-- Constraints for table `sheet`
--
ALTER TABLE `sheet`
  ADD CONSTRAINT `FK_873C91E2CF11D9C` FOREIGN KEY (`instrument_id`) REFERENCES `instrument` (`id`);

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

--
-- Constraints for table `vault_sheet`
--
ALTER TABLE `vault_sheet`
  ADD CONSTRAINT `FK_DAA45D998B1206A5` FOREIGN KEY (`sheet_id`) REFERENCES `sheet` (`id`),
  ADD CONSTRAINT `FK_DAA45D99A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
