-- phpMyAdmin SQL Dump
-- version 3.4.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Czas wygenerowania: 06 Sty 2012, 14:12
-- Wersja serwera: 5.1.58
-- Wersja PHP: 5.3.8-1~dotdeb.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Baza danych: `tinab`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `environments`
--

CREATE TABLE IF NOT EXISTS `environments` (
  `env_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `env_pos` int(11) NOT NULL,
  `name` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`env_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=5 ;

--
-- Zrzut danych tabeli `environments`
--

INSERT INTO `environments` (`env_id`, `env_pos`, `name`) VALUES
(1, 1, 'dev'),
(2, 2, 'staging'),
(3, 3, 'pre-production'),
(4, 4, 'production');

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `features`
--

CREATE TABLE IF NOT EXISTS `features` (
  `feature_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `feature_pos` int(11) NOT NULL,
  `feature_status` enum('avtive','finished') COLLATE utf8_polish_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `desc` text COLLATE utf8_polish_ci NOT NULL,
  `project_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`feature_id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `feature_tasks`
--

CREATE TABLE IF NOT EXISTS `feature_tasks` (
  `feature_id` int(10) unsigned NOT NULL,
  `tesk_id` int(10) unsigned NOT NULL,
  KEY `feature_id` (`feature_id`),
  KEY `tesk_id` (`tesk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `group_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8_polish_ci NOT NULL,
  `project_id` int(10) unsigned DEFAULT NULL,
  `is_main` tinyint(1) NOT NULL DEFAULT '0',
  `desc` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`group_id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=8 ;

--
-- Zrzut danych tabeli `groups`
--

INSERT INTO `groups` (`group_id`, `name`, `project_id`, `is_main`, `desc`) VALUES
(1, 'Administratorzy', NULL, 0, 'grupa administratorów serwisu'),
(3, 'Project managers', NULL, 0, 'Grupa managerów projektów.'),
(6, 'Administratorzy', 2, 1, 'grupa administratorów projektu'),
(7, 'Uczestnicy', 2, 0, 'standardowi uczestnicy projektu');

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `group_privileges`
--

CREATE TABLE IF NOT EXISTS `group_privileges` (
  `group_id` int(10) unsigned NOT NULL,
  `privilege` enum('adm','proj_crate','proj_adm','proj_tasklist','proj_task','proj_task_aloc') COLLATE utf8_polish_ci NOT NULL,
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `group_privileges`
--

INSERT INTO `group_privileges` (`group_id`, `privilege`) VALUES
(1, 'adm'),
(3, 'adm'),
(3, 'proj_crate'),
(6, 'proj_adm'),
(7, 'proj_tasklist'),
(7, 'proj_task'),
(7, 'proj_task_aloc');

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
  `project_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `desc` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `author_id` int(10) unsigned DEFAULT NULL,
  `creation_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`project_id`),
  KEY `author_id` (`author_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=3 ;

--
-- Zrzut danych tabeli `projects`
--

INSERT INTO `projects` (`project_id`, `name`, `desc`, `author_id`, `creation_time`) VALUES
(2, 'Testowy projekt', 'Nowy projekt testowy - całkiem nowy', 1, 1324900014);

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `tasks`
--

CREATE TABLE IF NOT EXISTS `tasks` (
  `task_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) unsigned NOT NULL,
  `resp_user` int(10) unsigned DEFAULT NULL,
  `env_id` int(10) unsigned DEFAULT NULL,
  `pos` int(11) NOT NULL,
  `task` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `task_status` enum('new','suspend','active','test','code_review','to_accept','accepted','finished') COLLATE utf8_polish_ci NOT NULL,
  `tags` text COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`task_id`),
  KEY `resp_user` (`resp_user`),
  KEY `env_id` (`env_id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=7 ;

--
-- Zrzut danych tabeli `tasks`
--

INSERT INTO `tasks` (`task_id`, `project_id`, `resp_user`, `env_id`, `pos`, `task`, `task_status`, `tags`) VALUES
(1, 2, 1, 2, 0, 'nowy task22', 'to_accept', 'adminka2|deny_test2'),
(4, 2, 1, 1, 0, 'Zadanie 1', 'code_review', 'adminka|deny'),
(6, 2, NULL, NULL, 0, 'Zadanie 2', 'new', 'portal');

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `task_descriptions`
--

CREATE TABLE IF NOT EXISTS `task_descriptions` (
  `task_id` int(10) unsigned NOT NULL,
  `entry_date` int(11) NOT NULL,
  `author_id` int(10) unsigned DEFAULT NULL,
  `text` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  KEY `task_id` (`task_id`,`author_id`),
  KEY `author_id` (`author_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` enum('active','inactive','deleted') COLLATE utf8_roman_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_roman_ci NOT NULL,
  `passwd` char(40) COLLATE utf8_roman_ci NOT NULL,
  `salt` char(40) COLLATE utf8_roman_ci NOT NULL,
  `name` varchar(20) COLLATE utf8_roman_ci NOT NULL,
  `surname` varchar(30) COLLATE utf8_roman_ci NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_roman_ci AUTO_INCREMENT=6 ;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`user_id`, `status`, `email`, `passwd`, `salt`, `name`, `surname`) VALUES
(1, 'active', 'admin@example.com', 'ccd1a5bbc4415b4711a9ebf8680f265e46ff5c0a', 'e64e4cfdf35639c3bf64634f4cfd18a79bfe1e71', 'Default', 'Admin'),
(4, 'active', 'deny1@example.com', 'f3ae80bdf63748740dbe6ad5f57767e33213b77d', 'f096ee2f3e56f5138126ca70eaac5e26b24f4844', 'Deny', 'Test1'),
(5, 'deleted', 'deny1@test.pl', '9d2d1a00640410436a70f933eb5e2c8d0170f698', 'af2eb584b4a33876929d792ab7191a4b3c573a54', 'Deny', 'Admin2');

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `user_groups`
--

CREATE TABLE IF NOT EXISTS `user_groups` (
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `user_groups`
--

INSERT INTO `user_groups` (`user_id`, `group_id`) VALUES
(1, 1),
(1, 3),
(5, 1),
(1, 6),
(1, 7);

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `features`
--
ALTER TABLE `features`
  ADD CONSTRAINT `features_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `feature_tasks`
--
ALTER TABLE `feature_tasks`
  ADD CONSTRAINT `feature_tasks_ibfk_2` FOREIGN KEY (`tesk_id`) REFERENCES `tasks` (`task_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `feature_tasks_ibfk_1` FOREIGN KEY (`feature_id`) REFERENCES `features` (`feature_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `group_privileges`
--
ALTER TABLE `group_privileges`
  ADD CONSTRAINT `group_privileges_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Ograniczenia dla tabeli `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`resp_user`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tasks_ibfk_3` FOREIGN KEY (`env_id`) REFERENCES `environments` (`env_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `task_descriptions`
--
ALTER TABLE `task_descriptions`
  ADD CONSTRAINT `task_descriptions_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `task_descriptions_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`task_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `user_groups`
--
ALTER TABLE `user_groups`
  ADD CONSTRAINT `user_groups_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_groups_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE;

