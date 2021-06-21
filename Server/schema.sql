CREATE DATABASE IF NOT EXISTS `rboxlo`;
USE `rboxlo`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `password_hash` text NOT NULL,
  `email_ciphertext` text NOT NULL,
  `email_blind_index` text NOT NULL,
  `created_timestamp` int(11) NOT NULL,
  `last_stipend_timestamp` int(11) NOT NULL,
  `last_ping` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `preferences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `avatar` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `sign_in_history` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `register_ip_blind_index` text NOT NULL,
  `2fa_secret` text NOT NULL,
  `email_verified` int(11) NOT NULL DEFAULT 0,
  `is_banned` int(11) NOT NULL DEFAULT 0,
  `current_ban_article` int(11) NOT NULL DEFAULT 0,
  `money` int(11) NOT NULL DEFAULT 25,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attempts` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `threshold` int(11) NOT NULL,
  `created_timestamp` int(11) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT 1,
  `ip` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `invite_keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uses` int(11) NOT NULL,
  `max_uses` int(11) NOT NULL,
  `key` text NOT NULL,
  `creator_id` int(11) NOT NULL,
  `created_timestamp` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `long_term_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `selector` text NOT NULL,
  `validator_hash` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_timestamp` int(11) NOT NULL,
  `expires_timestamp` int(11) NOT NULL,
  `ip` text NOT NULL,
  `user_agent` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `join_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` text NOT NULL,
  `created_timestamp` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `place_id` int(11) NOT NULL,
  `attributes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`attributes`)),
  PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` text NOT NULL,
  `created_timestamp` int(11) NOT NULL,
  `last_ping_timestamp` int(11) NOT NULL,
  `players` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`players`)),
  `instance` text NOT NULL,
  `port` int(11) NOT NULL,
  `place_id` int(11) NOT NULL,
  `ip` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` text NOT NULL,
  `start_place_id` int(11) NOT NULL,
  `created_timestamp` int(11) NOT NULL,
  `last_updated_timestamp` int(11) NOT NULL,
  `name` text NOT NULL,
  `application` text NOT NULL,
  `privileges` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`privileges`)),
  `privacy` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` text NOT NULL,
  `created_timestamp` int(11) NOT NULL,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `places` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `game_uuid` text NOT NULL,
  `uuid` text NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `genre` int(11) NOT NULL,
  `max_players` int(11) NOT NULL,
  `copying` int(11) NOT NULL,
  `allowed_gears` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`allowed_gears`)),
  `chat_style` int(11) NOT NULL,
  `is_start_place` int(11) NOT NULL,
  `created_timestamp` int(11) NOT NULL,
  `last_updated` int(11) NOT NULL,
  `private_servers_enabled` int(11) NOT NULL DEFAULT 0,
  `private_servers_fee` int(11) NOT NULL DEFAULT 0,
  `visits` int(11) NOT NULL DEFAULT 0,
  `upvotes` int(11) NOT NULL DEFAULT 0,
  `downvotes` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `place_versions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uploader_user_id` int(11) NOT NULL,
  `version` int(11) NOT NULL,
  `sha256` text NOT NULL,
  `place_id` int(11) NOT NULL,
  `created_timestamp` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `servers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creator_id` int(11) NOT NULL,
  `created_timestamp` int(11) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `players` int(11) NOT NULL,
  `port` int(11) NOT NULL,
  `ip` text NOT NULL,
  `application` text NOT NULL,
  `private_key` text NOT NULL,
  `is_private` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;