CREATE TABLE IF NOT EXISTS `engine4_tickvideo_blocks` (
  `block_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `blocked_user_id` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`block_id`),
  UNIQUE KEY `unique` (`user_id`,`blocked_user_id`),
  KEY `REVERSE` (`blocked_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;