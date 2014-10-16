CREATE TABLE `characters` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `character_name` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `character_meta` (
  `character_id` int(11) NOT NULL,
  `key_type` int(11) NOT NULL,
  `meta_key` int(10) unsigned NOT NULL,
  `meta_value` longtext NOT NULL,
  UNIQUE KEY `user_id` (`character_id`,`key_type`,`meta_key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `game_meta` (
  `key_type` int(10) unsigned NOT NULL,
  `meta_key` int(10) unsigned NOT NULL,
  `meta_value` longtext NOT NULL,
  UNIQUE KEY `meta_key` (`key_type`,`meta_key`),
  KEY `key_type` (`key_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `logs` (
  `log_type` int(10) unsigned NOT NULL,
  `char_id` int(10) unsigned NOT NULL,
  `timestamp` int(10) unsigned NOT NULL,
  `meta_value` longtext NOT NULL,
  KEY `log_type` (`log_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
