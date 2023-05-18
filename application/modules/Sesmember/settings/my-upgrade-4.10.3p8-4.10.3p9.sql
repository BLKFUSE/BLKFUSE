 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: my-upgrade-4.10.3p8-4.10.3p9.sql 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

CREATE TABLE IF NOT EXISTS `engine4_sesmember_userinfos` (
	`userinfo_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`user_id` int(11) unsigned NOT NULL,
	`follow_count` INT(11) NOT NULL DEFAULT "0",
	`location` VARCHAR(512) NOT NULL,
	`rating` float NOT NULL DEFAULT "0",
	`user_verified` TINYINT(1) NOT NULL DEFAULT "0",
	`cool_count` INT( 11 ) NOT NULL DEFAULT "0",
	`funny_count` INT( 11 ) NOT NULL DEFAULT "0",
	`useful_count` INT( 11 ) NOT NULL DEFAULT "0",
	`featured` TINYINT( 1 ) NOT NULL DEFAULT "0",
	`sponsored` TINYINT( 1 ) NOT NULL DEFAULT "0",
	`vip` TINYINT( 1 ) NOT NULL DEFAULT "0",
	`offtheday` tinyint(1)	NOT NULL DEFAULT "0",
	`starttime` DATE DEFAULT NULL,
	`endtime` DATE DEFAULT NULL,
	`adminpicks` TINYINT(1) NOT NULL DEFAULT "0",
	`order` INT(11) NOT NULL DEFAULT "0",
	PRIMARY KEY (`userinfo_id`),
	UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

INSERT IGNORE INTO `engine4_sesmember_userinfos`(`user_id`, `follow_count`, `location`, `rating`, `user_verified`, `cool_count`, `funny_count`, `useful_count`, `featured`, `sponsored`, `vip`, `offtheday`, `starttime`, `endtime`, `adminpicks`, `order`) select `user_id`, `follow_count`, `location`, `rating`, `user_verified`, `cool_count`, `funny_count`, `useful_count`, `featured`, `sponsored`, `vip`, `offtheday`, `starttime`, `endtime`, `adminpicks`, `order` from `engine4_users`;

ALTER TABLE `engine4_users` DROP `follow_count`;
ALTER TABLE `engine4_users` DROP `location`;
ALTER TABLE `engine4_users` DROP `rating`;
ALTER TABLE `engine4_users` DROP `user_verified`;
ALTER TABLE `engine4_users` DROP `cool_count`;
ALTER TABLE `engine4_users` DROP `funny_count`;
ALTER TABLE `engine4_users` DROP `useful_count`;
ALTER TABLE `engine4_users` DROP `featured`;
ALTER TABLE `engine4_users` DROP `sponsored`;
ALTER TABLE `engine4_users` DROP `vip`;
ALTER TABLE `engine4_users` DROP `offtheday`;
ALTER TABLE `engine4_users` DROP `starttime`;
ALTER TABLE `engine4_users` DROP `endtime`;
ALTER TABLE `engine4_users` DROP `adminpicks`;
ALTER TABLE `engine4_users` DROP `order`;
