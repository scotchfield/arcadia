CREATE TABLE `achievements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `achieve_title` varchar(100) NOT NULL,
  `achieve_text` longtext NOT NULL,
  `achieve_type` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `attacks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attack_name` text NOT NULL,
  `attack_description` text NOT NULL,
  `attack_meta` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `characters` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `character_name` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `character_achievements` (
  `character_id` int(10) unsigned NOT NULL,
  `achievement_id` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  KEY `character_id` (`character_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `character_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `character_id` int(10) unsigned NOT NULL,
  `item_id` int(11) NOT NULL,
  `charitem_meta` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`character_id`,`item_id`),
  KEY `user_id_2` (`character_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `character_meta` (
  `character_id` int(11) NOT NULL,
  `key_type` int(11) NOT NULL,
  `meta_key` int(10) unsigned NOT NULL,
  `meta_value` longtext NOT NULL,
  UNIQUE KEY `user_id` (`character_id`,`key_type`,`meta_key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `character_quests` (
  `character_id` int(10) unsigned NOT NULL,
  `quest_id` int(10) unsigned NOT NULL,
  `completed` int(10) unsigned NOT NULL,
  `quest_meta` longtext NOT NULL,
  UNIQUE KEY `character_id_3` (`character_id`,`quest_id`,`completed`),
  KEY `character_id` (`character_id`),
  KEY `character_id_2` (`character_id`,`completed`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `game_meta` (
  `key_type` int(10) unsigned NOT NULL,
  `meta_key` int(10) unsigned NOT NULL,
  `meta_value` longtext NOT NULL,
  UNIQUE KEY `meta_key` (`key_type`,`meta_key`),
  KEY `key_type` (`key_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `description` text NOT NULL,
  `weight` int(10) unsigned NOT NULL,
  `item_meta` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `mail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `character_id_to` int(10) unsigned NOT NULL,
  `character_id_from` int(10) unsigned NOT NULL,
  `character_name_from` tinytext NOT NULL,
  `subject` tinytext NOT NULL,
  `text` text NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `item_meta` longtext NOT NULL,
  `status` int(10) unsigned NOT NULL,
  `created` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `npcs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `npc_name` varchar(100) NOT NULL,
  `npc_description` longtext NOT NULL,
  `npc_defeated` longtext NOT NULL,
  `npc_state` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `quests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `start_text` longtext NOT NULL,
  `end_text` longtext NOT NULL,
  `npc_id` int(10) unsigned NOT NULL,
  `quest_prereq` text NOT NULL,
  `quest_acceptmeta` longtext NOT NULL,
  `quest_progress` longtext NOT NULL,
  `quest_complete` text NOT NULL,
  `repeatable` tinyint(4) NOT NULL,
  `quest_meta` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(64) NOT NULL,
  `user_pass` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `registered` datetime NOT NULL,
  `activation` varchar(60) NOT NULL,
  `status` int(11) NOT NULL,
  `max_characters` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `zones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zone_tag` varchar(10) NOT NULL,
  `zone_title` text NOT NULL,
  `zone_description` text NOT NULL,
  `zone_type` varchar(20) NOT NULL,
  `zone_meta` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `zone_items` (
  `zone_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `state_meta` longtext NOT NULL,
  UNIQUE KEY `zone_id` (`zone_id`,`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `zone_transitions` (
  `zone_source` int(11) NOT NULL,
  `zone_destination` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
