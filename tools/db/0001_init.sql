-- @1 tabelka na userów
CREATE TABLE `users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` enum('active','inactive','deleted') COLLATE utf8_roman_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_roman_ci NOT NULL,
  `passwd` char(40) COLLATE utf8_roman_ci NOT NULL,
  `salt` char(40) COLLATE utf8_roman_ci NOT NULL,
  `name` varchar(20) COLLATE utf8_roman_ci NOT NULL,
  `surname` varchar(30) COLLATE utf8_roman_ci NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_roman_ci AUTO_INCREMENT=1 ;

-- @2 tabelka na projekty
CREATE TABLE `projects` (
  `project_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `author_id` int(10) unsigned DEFAULT NULL,
  `creation_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`project_id`),
  KEY `author_id` (`author_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;


ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE NO ACTION;

-- @3 tabelka na grupy
CREATE TABLE `groups` (
  `group_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8_polish_ci NOT NULL,
  `project_id` int(10) unsigned DEFAULT NULL,
  `desc` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`group_id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- @4 tabelka na userów przypiętych do grup
CREATE TABLE `user_groups` (
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

ALTER TABLE `user_groups`
  ADD CONSTRAINT `user_groups_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_groups_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- @5 tabelka na uprawnienia dodawane do grupy
CREATE TABLE `group_privileges` (
  `group_id` int(10) unsigned NOT NULL,
  `privilege` enum('adm','proj_crate','proj_adm') COLLATE utf8_polish_ci NOT NULL,
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;


ALTER TABLE `group_privileges`
  ADD CONSTRAINT `group_privileges_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE;


-- @revert 

--brak reverta ;-)
