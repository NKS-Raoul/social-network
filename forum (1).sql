-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 14 juin 2021 à 23:59
-- Version du serveur :  10.4.17-MariaDB
-- Version de PHP : 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `forum`
--

-- --------------------------------------------------------

--
-- Structure de la table `answers`
--

CREATE TABLE `answers` (
  `id_answer` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `answer` text NOT NULL,
  `post_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `answers`
--

INSERT INTO `answers` (`id_answer`, `user_id`, `to_user_id`, `comment_id`, `answer`, `post_at`) VALUES
(1, 1, 2, 3, '🙋🏾‍♂️🙋🏾‍♂️🙋🏾‍♂️🙋🏾‍♂️🙋🏾‍♂️', '2021-06-11 10:56:52'),
(2, 8, 8, 6, '😁😁😁😁', '2021-06-12 09:02:37');

-- --------------------------------------------------------

--
-- Structure de la table `authtokens`
--

CREATE TABLE `authtokens` (
  `user_id` int(11) NOT NULL,
  `user_tokens` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `authtokens`
--

INSERT INTO `authtokens` (`user_id`, `user_tokens`) VALUES
(1, '7aebb2f65dbed5d7e0e62a02e826127c6209d02e'),
(2, 'd454b4b670a256e2d968dce9f9ef9f5a365e82e6'),
(3, 'a648a2f90876337f10c99ecad80c5a6fdd7cad55'),
(4, 'd49d4a4a63cb55da74194af52a3c443b36a8ca03'),
(5, '819cb0df039e3b20515999dbcd96cb9dccb6bdbb'),
(6, '1a01c545d49d86731f81252f56caed07ff2a81d2'),
(7, '7397da35ad85e639744a8b583c19fb312d25b566'),
(8, 'd2025e068498e51160e123fca542ee3ae41dbf1f');

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `publication_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_at` datetime NOT NULL DEFAULT current_timestamp(),
  `comment_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`comment_id`, `publication_id`, `user_id`, `post_at`, `comment_text`) VALUES
(1, 2, 2, '2021-06-11 10:53:17', 'dva;kldvm;akv 😎😎😎'),
(2, 1, 1, '2021-06-11 10:56:10', '321321'),
(3, 1, 2, '2021-06-11 10:56:32', 'lol'),
(4, 3, 1, '2021-06-11 10:58:25', '😘😘😘'),
(5, 4, 4, '2021-06-11 12:30:31', 'tryt'),
(6, 5, 8, '2021-06-12 09:02:02', 'C\'est raoul !');

-- --------------------------------------------------------

--
-- Structure de la table `follow`
--

CREATE TABLE `follow` (
  `id` int(11) NOT NULL,
  `followers_id` int(11) NOT NULL,
  `followings_id` int(11) NOT NULL,
  `follow_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `follow`
--

INSERT INTO `follow` (`id`, `followers_id`, `followings_id`, `follow_time`) VALUES
(1, 2, 1, '2021-06-11 10:46:23'),
(2, 1, 2, '2021-06-11 10:47:21'),
(3, 4, 2, '2021-06-11 12:30:00'),
(4, 1, 8, '2021-06-14 23:11:39');

-- --------------------------------------------------------

--
-- Structure de la table `hashtags`
--

CREATE TABLE `hashtags` (
  `id` int(11) NOT NULL,
  `hashTags` varchar(255) NOT NULL,
  `subject_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `publication_id` int(11) NOT NULL,
  `like_value` enum('1','2') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `publication_id`, `like_value`) VALUES
(1, 1, 1, '1'),
(2, 2, 2, '2'),
(4, 1, 2, '1'),
(6, 1, 3, '1'),
(7, 4, 4, '1'),
(8, 2, 4, '1'),
(9, 8, 5, '1'),
(10, 1, 6, '1'),
(11, 1, 4, '1');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `message` longtext NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `seen` enum('0','1') NOT NULL,
  `already_receive` enum('0','1') NOT NULL,
  `send_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `message`, `sender_id`, `receiver_id`, `seen`, `already_receive`, `send_time`) VALUES
(1, '123', 2, 1, '1', '1', '2021-06-11 10:46:41'),
(2, 'echo echo', 1, 2, '1', '1', '2021-06-11 10:47:01'),
(3, '😒😒😒😒', 2, 1, '1', '0', '2021-06-11 10:47:14'),
(4, '', 1, 2, '1', '0', '2021-06-11 10:58:46'),
(5, 'ccmalskcalskmc', 2, 1, '1', '0', '2021-06-11 10:59:09'),
(6, '12345', 2, 4, '1', '0', '2021-06-11 14:07:07'),
(7, 'jnlk,', 4, 2, '1', '0', '2021-06-11 14:08:22'),
(8, 'jjjjjjjjjjjj', 2, 4, '1', '0', '2021-06-11 14:08:34'),
(9, 'J\'vais mettre les emojis apres', 1, 2, '1', '0', '2021-06-12 11:43:39'),
(10, 'okay ! Eneo c\'est le dem', 2, 1, '1', '0', '2021-06-12 11:44:03'),
(11, '😒 Vraiment !', 1, 2, '1', '0', '2021-06-12 11:44:28'),
(12, 'Gar a plus ! je pars faire la lessive 😢', 2, 1, '1', '0', '2021-06-12 11:45:51'),
(13, 'okay ! 😪😪😪', 1, 2, '1', '0', '2021-06-12 11:46:34'),
(14, '🚶🏾‍♂️🚶🏾‍♂️🚶🏾‍♂️🚶🏾‍♂️', 2, 1, '1', '0', '2021-06-12 11:53:47'),
(15, 'xxx', 2, 1, '1', '0', '2021-06-12 12:14:49'),
(16, 'yo', 2, 1, '1', '1', '2021-06-13 06:44:27'),
(17, '123', 2, 1, '1', '1', '2021-06-13 06:46:15'),
(18, 'ss', 2, 1, '1', '1', '2021-06-13 06:47:30'),
(19, 'Ca marche ?', 2, 1, '1', '1', '2021-06-13 06:53:29'),
(20, 'echo echo', 2, 4, '0', '0', '2021-06-13 06:55:12'),
(21, 'echo echo', 2, 1, '1', '1', '2021-06-13 06:56:37'),
(22, '👍👍👍👍', 1, 2, '1', '0', '2021-06-13 06:57:57'),
(23, '😁', 2, 1, '1', '0', '2021-06-13 07:02:55'),
(24, 'e', 2, 1, '1', '1', '2021-06-13 07:05:45'),
(25, 's', 1, 2, '1', '0', '2021-06-13 20:14:51'),
(26, '123', 1, 2, '1', '0', '2021-06-13 22:25:34'),
(27, 'vdjskdjvnskdv', 1, 2, '1', '0', '2021-06-13 22:30:55'),
(37, '<img src=\"http://localhost/forum/public/assets/smileys/Colere_49.gif\">', 1, 2, '1', '0', '2021-06-14 01:03:03'),
(38, '<img src=\"http://localhost/forum/public/assets/smileys/Anniversaire_15.gif\"><img src=\"http://localhost/forum/public/assets/smileys/Divers_60.gif\">', 1, 2, '1', '0', '2021-06-14 01:03:46'),
(39, '<img src=\"http://localhost/forum/public/assets/smileys/Dormir_41.gif\"><img src=\"http://localhost/forum/public/assets/smileys/Dormir_40.gif\">', 1, 2, '1', '0', '2021-06-14 01:04:15'),
(40, 'raoul&nbsp;<img src=\"http://localhost/forum/public/assets/smileys/Dormir_40.gif\">&nbsp;oui oui<img src=\"http://localhost/forum/public/assets/smileys/Dormir_41.gif\">', 1, 2, '1', '0', '2021-06-14 01:04:50'),
(41, '<img src=\"http://localhost/forum/public/assets/smileys/Dormir_4.gif\">', 1, 2, '1', '0', '2021-06-14 01:10:52'),
(42, '123<img src=\"http://localhost/forum/public/assets/smileys/Anniversaire_15.gif\">', 1, 2, '1', '0', '2021-06-14 01:11:32'),
(43, '123<img src=\"http://localhost/forum/public/assets/smileys/Anniversaire_15.gif\">', 1, 2, '1', '0', '2021-06-14 01:11:43'),
(44, 'llllllllll', 1, 2, '1', '0', '2021-06-14 01:12:31'),
(45, 'llllllllll', 1, 2, '1', '0', '2021-06-14 01:12:37'),
(46, 'llllllllll', 1, 2, '1', '0', '2021-06-14 01:12:40'),
(47, 'llllllllll', 1, 2, '1', '0', '2021-06-14 01:12:40'),
(48, 'llllllllll', 1, 2, '1', '0', '2021-06-14 01:12:42'),
(49, '', 1, 2, '1', '0', '2021-06-14 01:14:23'),
(50, 'm', 1, 2, '1', '0', '2021-06-14 01:14:36'),
(51, 'mmmmmmmmmmm', 1, 2, '1', '0', '2021-06-14 01:15:32'),
(52, 'mmmmmmmmmmm<img src=\"http://localhost/forum/public/assets/smileys/Anniversaire_16.gif\">', 1, 2, '1', '0', '2021-06-14 01:15:42'),
(53, '<img src=\"http://localhost/forum/public/assets/smileys/Anniversaire_17.gif\">', 1, 2, '1', '0', '2021-06-14 01:25:31'),
(54, '<img src=\"http://localhost/forum/public/assets/smileys/Amour_30.gif\">', 1, 2, '1', '0', '2021-06-14 01:26:38'),
(55, '<img src=\"http://localhost/forum/public/assets/smileys/Anniversaire_15.gif\">', 1, 2, '1', '0', '2021-06-14 01:35:18'),
(56, '<img src=\"http://localhost/forum/public/assets/smileys/Anniversaire_15.gif\">', 1, 2, '1', '0', '2021-06-14 01:36:20'),
(57, '<img src=\"http://localhost/forum/public/assets/smileys/Anniversaire_14.gif\">', 1, 2, '1', '0', '2021-06-14 01:37:27'),
(58, '<img src=\"http://localhost/forum/public/assets/smileys/Amour_12.gif\">', 1, 2, '1', '0', '2021-06-14 01:38:08'),
(59, '<img src=\"http://localhost/forum/public/assets/smileys/Amour_9.gif\">', 1, 2, '1', '0', '2021-06-14 01:38:36'),
(60, '<img src=\"http://localhost/forum/public/assets/smileys/Amour_22.gif\">', 1, 2, '1', '0', '2021-06-14 01:53:11'),
(61, 'ccascasc<img src=\"imgSmileys2021smileys/Anniversaire_16.gif\">ascascascacs<img src=\"http://localhost/forum/public/assets/smileys/Anniversaire_18.gif\">', 1, 2, '1', '0', '2021-06-14 02:03:11'),
(62, '[object NodeList]', 1, 2, '1', '0', '2021-06-14 02:11:55'),
(63, '<img src=\"imgSmileys2021smileys/Anniversaire_6.gif\">&nbsp;kjnlklnkl lnl kl l lk lk&nbsp;<img src=\"imgSmileys2021smileys/Anniversaire_17.gif\">', 1, 2, '1', '0', '2021-06-14 02:12:40'),
(64, '<img src=\"imgSmileys2021smileys/Anniversaire_15.gif\">', 1, 2, '1', '0', '2021-06-14 02:46:32'),
(65, '&nbsp;n<img src=\"imgSmileys2021smileys/Anniversaire_14.gif\">', 1, 2, '1', '0', '2021-06-14 03:18:15'),
(66, '<img src=\"imgSmileys2021smileys/Amour_3.gif\">', 1, 2, '1', '1', '2021-06-14 03:41:17'),
(67, '<img src=\"imgSmileys2021smileys/Amour_13.gif\">', 2, 1, '1', '0', '2021-06-14 03:48:22'),
(68, '<img src=\"imgSmileys2021smileys/Violence_31.gif\">', 2, 1, '1', '0', '2021-06-14 03:57:21'),
(69, '<img src=\"imgSmileys2021smileys/Violence_11.gif\"><img src=\"imgSmileys2021smileys/Violence_32.gif\">', 1, 2, '1', '0', '2021-06-14 03:57:43'),
(70, '<img src=\"imgSmileys2021smileys/triste_11.gif\"><img src=\"imgSmileys2021smileys/triste_11.gif\">', 1, 2, '1', '0', '2021-06-14 04:01:51'),
(71, '<img src=\"imgSmileys2021smileys/Amour_27.gif\"><img src=\"imgSmileys2021smileys/Amour_11.gif\"><img src=\"imgSmileys2021smileys/Amour_13.gif\">', 2, 1, '1', '0', '2021-06-14 04:02:56'),
(72, '', 1, 2, '1', '0', '2021-06-14 04:03:55'),
(73, '<img src=\"imgSmileys2021smileys/Amour_3.gif\">', 1, 2, '1', '0', '2021-06-14 10:03:08'),
(74, '<img src=\"imgSmileys2021smileys/Anniversaire_21.gif\">', 1, 2, '1', '1', '2021-06-14 14:14:55'),
(75, '<br><img src=\"imgSmileys2021smileys/Divers_58.gif\">', 2, 1, '1', '1', '2021-06-14 22:21:17'),
(76, '<img src=\"imgSmileys2021smileys/Amour_22.gif\">', 1, 2, '1', '1', '2021-06-14 22:21:55'),
(77, '<img src=\"imgSmileys2021smileys/triste_9.gif\">', 2, 4, '0', '0', '2021-06-14 22:22:39'),
(78, 'n', 1, 2, '1', '1', '2021-06-14 23:23:13'),
(79, 'nnnnnnnnnnnn', 1, 8, '0', '0', '2021-06-14 23:23:28'),
(80, '<img src=\"imgSmileys2021smileys/Amour_30.gif\">', 1, 8, '0', '0', '2021-06-14 23:24:47'),
(81, '<img src=\"imgSmileys2021smileys/Amour_14.gif\">', 1, 8, '0', '0', '2021-06-14 23:42:17'),
(82, '<img src=\"imgSmileys2021smileys/Amour.gif\">', 1, 2, '1', '0', '2021-06-14 23:43:06');

-- --------------------------------------------------------

--
-- Structure de la table `messagesattachment`
--

CREATE TABLE `messagesattachment` (
  `id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `media` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `messagesattachment`
--

INSERT INTO `messagesattachment` (`id`, `message_id`, `media`, `type`) VALUES
(1, 4, '1623401926_1.png', 'image'),
(2, 49, '1623626063_1.jpeg', 'image'),
(3, 70, '1623636111_1.jpeg', 'image'),
(4, 71, '1623636176_2.jpeg', 'image'),
(5, 72, '1623636235_1.jpeg', 'image');

-- --------------------------------------------------------

--
-- Structure de la table `publications`
--

CREATE TABLE `publications` (
  `publication_id` int(11) NOT NULL,
  `publication_text` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `publications`
--

INSERT INTO `publications` (`publication_id`, `publication_text`, `user_id`, `post_at`) VALUES
(1, 'Mr Bouhari<br />\n<br />\n<strong class=\"siteColor\">#projet</strong>', 1, '2021-06-11 10:47:57'),
(2, 'My name is <strong class=\"siteColor\">#Kelly</strong> i\'m the best programer of inf2', 2, '2021-06-11 10:52:53'),
(3, 'echo<br />\r\n<br />\r\n<strong class=\"siteColor\">#Raoul</strong>', 1, '2021-06-11 10:58:07'),
(4, 'GENIE LOGICIEL<br />\n<br />\n<strong class=\"siteColor\">#gl</strong>', 2, '2021-06-11 12:28:32'),
(5, '😎😎😎😎😎😎', 8, '2021-06-12 09:00:56');

-- --------------------------------------------------------

--
-- Structure de la table `publicationsattachment`
--

CREATE TABLE `publicationsattachment` (
  `id` int(11) NOT NULL,
  `media` varchar(500) NOT NULL,
  `publication_id` int(11) NOT NULL,
  `type` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `publicationsattachment`
--

INSERT INTO `publicationsattachment` (`id`, `media`, `publication_id`, `type`) VALUES
(1, '1623401887_3.jpeg', 3, 'image'),
(2, '1623407313_4.jpeg', 4, 'image'),
(3, '1623481256_5.jpeg', 5, 'image'),
(4, '1623488011_6.jpeg', 6, 'image'),
(5, '1623488897_7.jpeg', 7, 'image');

-- --------------------------------------------------------

--
-- Structure de la table `userinformation`
--

CREATE TABLE `userinformation` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cover_image` text NOT NULL,
  `profile_image` text NOT NULL,
  `school_at` varchar(500) NOT NULL,
  `sex` enum('female','male') DEFAULT NULL,
  `date_birth` date DEFAULT NULL,
  `country` varchar(250) DEFAULT NULL,
  `phoneNumber` int(11) DEFAULT NULL,
  `descriptionI` text DEFAULT NULL,
  `user_state` enum('0','1') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `userinformation`
--

INSERT INTO `userinformation` (`id`, `user_id`, `cover_image`, `profile_image`, `school_at`, `sex`, `date_birth`, `country`, `phoneNumber`, `descriptionI`, `user_state`) VALUES
(1, 1, '1623672925_1.jpeg', '1623677399_1.jpeg', 'universite de yaounde', 'female', '2021-05-06', 'Ghana', 693177759, '', '0'),
(2, 2, '1623401179_2.jpeg', '1623401179_2.jpeg', 'univ Dschang', 'female', '2001-09-20', 'Morocco', 693175592, '😍', '0'),
(4, 4, '1623407385_4.jpeg', '1623407385_4.jpeg', '', NULL, NULL, NULL, NULL, NULL, '0'),
(8, 8, '1623479675_8.jpeg', '1623479675_8.jpeg', '', NULL, NULL, NULL, NULL, NULL, '0');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(25) NOT NULL,
  `user_nickname` varchar(25) NOT NULL,
  `user_password` text NOT NULL,
  `user_email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_nickname`, `user_password`, `user_email`) VALUES
(1, 'Cabral', 'Assassin', 'Kelly', 'tadass1@gmail.com'),
(2, 'Kelly', 'kel', '12345678', 'kel@gmail.com'),
(4, 'MOUHAMED', 'Didora', '12345678', 'mouhamed@gmail.com'),
(8, 'Darenie', 'dar', '12345678', 'dar@gmail.cm');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id_answer`);

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Index pour la table `follow`
--
ALTER TABLE `follow`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `hashtags`
--
ALTER TABLE `hashtags`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `messagesattachment`
--
ALTER TABLE `messagesattachment`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `publications`
--
ALTER TABLE `publications`
  ADD PRIMARY KEY (`publication_id`);

--
-- Index pour la table `publicationsattachment`
--
ALTER TABLE `publicationsattachment`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `userinformation`
--
ALTER TABLE `userinformation`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `answers`
--
ALTER TABLE `answers`
  MODIFY `id_answer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `follow`
--
ALTER TABLE `follow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `hashtags`
--
ALTER TABLE `hashtags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT pour la table `messagesattachment`
--
ALTER TABLE `messagesattachment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `publications`
--
ALTER TABLE `publications`
  MODIFY `publication_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `publicationsattachment`
--
ALTER TABLE `publicationsattachment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `userinformation`
--
ALTER TABLE `userinformation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
