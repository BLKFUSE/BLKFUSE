INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES
("eticktokclone", "SNS - TikTok Clone", "SNS - TikTok Clone", "6.4.0", 1, "extra");


INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES ("eticktokclone_main_tiktokclone", "eticktokclone", "TikTak Videos", "", '{"route":"eticktokclone_default", "action":"explore"}', "core_main", "", 999);

INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`, `default`) VALUES ("eticktokclone_follow", "eticktokclone", '{item:$subject} follow you.', "0", "", "1");

INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`) VALUES ("eticktokclone_follow", "eticktokclone", '{item:$subject} follow {item:$object}.', 1, 5, 1, 1, 1, 1);

CREATE TABLE IF NOT EXISTS `engine4_eticktokclone_blocks` (
	`block_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`user_id` int(11) UNSIGNED NOT NULL,
	`blocked_user_id` int(11) UNSIGNED NOT NULL,
	PRIMARY KEY (`block_id`),
	UNIQUE KEY `unique` (`user_id`,`blocked_user_id`),
	KEY `REVERSE` (`blocked_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;

INSERT IGNORE INTO `engine4_core_mailtemplates` (`type`, `module`, `vars`) VALUES
("eticktokclone_follow", "eticktokclone", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]");

CREATE TABLE IF NOT EXISTS `engine4_eticktokclone_follows` (
	`follow_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`user_id` int(11) NOT NULL,
	`resource_id` int(11) NOT NULL,
	`creation_date` datetime NOT NULL,
	`resource_approved` TINYINT(1) NOT NULL DEFAULT "0",
	`user_approved` TINYINT(1) NOT NULL DEFAULT "0",
	PRIMARY KEY (`follow_id`),
	UNIQUE KEY `uniqueKey` (`user_id`,`resource_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1 ;
