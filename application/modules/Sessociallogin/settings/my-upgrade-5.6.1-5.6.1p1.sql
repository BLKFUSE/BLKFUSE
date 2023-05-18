INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("sessociallogin_admin_main_telegram", "sessociallogin", "Telegram", "", '{"route":"admin_default","module":"sessociallogin","controller":"settings","action":"telegram"}', "sessociallogin_admin_main", "", 4);

CREATE TABLE IF NOT EXISTS `engine4_user_telegram` (
`telegram_id` int(11) NOT NULL auto_increment,
`user_id` INT(11) NOT NULL,
`telegram_uid` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
`access_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT "",
PRIMARY KEY (`telegram_id`),
UNIQUE KEY `telegram_uid` (`telegram_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;