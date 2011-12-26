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
	  `desc` varchar(255) COLLATE utf8_polish_ci NOT NULL,
	  `author_id` int(10) unsigned DEFAULT NULL,
	  `creation_time` int(10) unsigned NOT NULL,
	  PRIMARY KEY (`project_id`),
	  KEY `author_id` (`author_id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

	ALTER TABLE `projects`
	  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE NO ACTION;

-- @3 tabelka na grupy
	CREATE TABLE `groups` (
	  `group_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `name` varchar(128) COLLATE utf8_polish_ci NOT NULL,
	  `project_id` int(10) unsigned DEFAULT NULL,
	  `is_main` tinyint(1) NOT NULL DEFAULT '0',
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
	  `privilege` enum('adm','proj_crate','proj_adm','proj_tasklist','proj_task','proj_task_aloc') COLLATE utf8_polish_ci NOT NULL,
	  KEY `group_id` (`group_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

	ALTER TABLE `group_privileges`
	  ADD CONSTRAINT `group_privileges_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- @6 tabelka na środowiska produkcyjne
	CREATE TABLE `environments` (
	  `env_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `env_pos` int(11) NOT NULL,
	  `name` varchar(64) COLLATE utf8_polish_ci NOT NULL,
	  PRIMARY KEY (`env_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- @7 tabelka na taski
	CREATE TABLE `tasks` (
	  `task_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `project_id` int(10) unsigned NOT NULL,
	  `resp_user` int(10) unsigned DEFAULT NULL,
	  `env_id` int(10) unsigned DEFAULT NULL,
	  `pos` int(11) NOT NULL,
	  `task` varchar(255) COLLATE utf8_polish_ci NOT NULL,
	  `tags` text COLLATE utf8_polish_ci NOT NULL,
	  PRIMARY KEY (`task_id`),
	  KEY `project_id` (`project_id`,`resp_user`),
	  KEY `resp_user` (`resp_user`),
	  KEY `env_id` (`env_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

	ALTER TABLE `tasks`
	  ADD CONSTRAINT `tasks_ibfk_3` FOREIGN KEY (`env_id`) REFERENCES `environments` (`env_id`) ON DELETE SET NULL ON UPDATE CASCADE,
	  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE,
	  ADD CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`resp_user`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- @8 tabelka na "opisy stanu realizacji" tasków
	CREATE TABLE `task_descriptions` (
	  `task_id` int(10) unsigned NOT NULL,
	  `entry_date` int(11) NOT NULL,
	  `author_id` int(10) unsigned DEFAULT NULL,
	  `text` varchar(255) COLLATE utf8_polish_ci NOT NULL,
	  KEY `task_id` (`task_id`,`author_id`),
	  KEY `author_id` (`author_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

	ALTER TABLE `task_descriptions`
	  ADD CONSTRAINT `task_descriptions_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
	  ADD CONSTRAINT `task_descriptions_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`task_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- @9 tabelka na opisy funkcjonalności
	CREATE TABLE `features` (
	  `feature_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `project_id` int(10) unsigned NOT NULL,
	  `feature_pos` int(11) NOT NULL,
	  `feature_status` enum('avtive','finished') COLLATE utf8_polish_ci NOT NULL,
	  `name` varchar(255) COLLATE utf8_polish_ci NOT NULL,
	  `desc` text COLLATE utf8_polish_ci NOT NULL,
	  PRIMARY KEY (`feature_id`),
	  KEY `project_id` (`project_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

	ALTER TABLE `features`
	  ADD CONSTRAINT `features_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- @10 tabelka na taski przypisane do funkcjonalności
	CREATE TABLE `feature_tasks` (
	  `feature_id` int(10) unsigned NOT NULL,
	  `tesk_id` int(10) unsigned NOT NULL,
	  KEY `feature_id` (`feature_id`),
	  KEY `tesk_id` (`tesk_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

	ALTER TABLE `feature_tasks`
	  ADD CONSTRAINT `feature_tasks_ibfk_2` FOREIGN KEY (`tesk_id`) REFERENCES `tasks` (`task_id`) ON DELETE CASCADE ON UPDATE CASCADE,
	  ADD CONSTRAINT `feature_tasks_ibfk_1` FOREIGN KEY (`feature_id`) REFERENCES `features` (`feature_id`) ON DELETE CASCADE ON UPDATE CASCADE;


-- @revert 

--brak reverta ;-)
