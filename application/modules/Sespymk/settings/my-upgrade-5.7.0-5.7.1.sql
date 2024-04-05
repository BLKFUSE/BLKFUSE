CREATE TABLE `engine4_sespymk_ignores` (
  `ignore_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `owner_id` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`ignore_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;