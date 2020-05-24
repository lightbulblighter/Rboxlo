SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `api_keys`;
CREATE TABLE `api_keys` (
  `id` int NOT NULL AUTO_INCREMENT,
  `version` text NOT NULL,
  `usage` text NOT NULL,
  `key` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


DROP TABLE IF EXISTS `client_versions`;
CREATE TABLE `client_versions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `year` text NOT NULL,
  `textual_version` text NOT NULL,
  `player_hash` text NOT NULL,
  `latest` tinyint NOT NULL,
  `released` tinyint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


DROP TABLE IF EXISTS `email_verification_keys`;
CREATE TABLE `email_verification_keys` (
  `id` int NOT NULL AUTO_INCREMENT,
  `key` text NOT NULL,
  `uid` int NOT NULL,
  `generated` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


DROP TABLE IF EXISTS `email_verification_tokens`;
CREATE TABLE `email_verification_tokens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `token` text NOT NULL,
  `uid` int NOT NULL,
  `generated` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


SET NAMES utf8mb4;

DROP TABLE IF EXISTS `friends`;
CREATE TABLE `friends` (
  `id` int NOT NULL,
  `sender_uid` int NOT NULL,
  `recipient_uid` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS `game_counted_statistics`;
CREATE TABLE `game_counted_statistics` (
  `id` int NOT NULL AUTO_INCREMENT,
  `version` text NOT NULL,
  `name` text NOT NULL,
  `count` bigint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS `game_tokens`;
CREATE TABLE `game_tokens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `token` text NOT NULL,
  `generated` int NOT NULL,
  `user_id` int NOT NULL,
  `game_id` int NOT NULL,
  `place_id` int NOT NULL,
  `ip` text NOT NULL,
  `port` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


DROP TABLE IF EXISTS `games`;
CREATE TABLE `games` (
  `id` int NOT NULL AUTO_INCREMENT,
  `full_id` text NOT NULL,
  `name` text NOT NULL,
  `creator` int NOT NULL,
  `created` int NOT NULL,
  `last_updated` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


DROP TABLE IF EXISTS `invite_keys`;
CREATE TABLE `invite_keys` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uses` int NOT NULL DEFAULT '0',
  `max_uses` int NOT NULL DEFAULT '1',
  `key` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int NOT NULL AUTO_INCREMENT,
  `color` tinytext NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


DROP TABLE IF EXISTS `places`;
CREATE TABLE `places` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `creator` int NOT NULL,
  `created` int NOT NULL,
  `last_updated` int NOT NULL,
  `description` text NOT NULL,
  `chat_style` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `email` text NOT NULL,
  `register_ip` text NOT NULL,
  `last_ip` text NOT NULL,
  `money` bigint NOT NULL DEFAULT '100',
  `joindate` int NOT NULL,
  `avatar` text NOT NULL,
  `email_verified` tinyint NOT NULL DEFAULT '0',
  `preferences` text NOT NULL,
  `last_reward` int NOT NULL,
  `rank` tinyint NOT NULL DEFAULT '0',
  `ssc` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;