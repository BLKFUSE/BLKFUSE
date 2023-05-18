ALTER TABLE `engine4_sescommunityads_packages` ADD `interests` TINYINT(1) NOT NULL DEFAULT "0";
ALTER TABLE `engine4_sescommunityads_packages` ADD `banner` TINYINT(1) NOT NULL DEFAULT "0";
ALTER TABLE `engine4_sescommunityads_ads` ADD `banner_type` TINYINT(1) NOT NULL DEFAULT "1";
ALTER TABLE `engine4_sescommunityads_ads` ADD `html_code` TEXT NOT NULL;
ALTER TABLE `engine4_sescommunityads_ads` ADD `banner_id` INT(11) NOT NULL;
ALTER TABLE `engine4_sescommunityads_ads` ADD `revselocation` VARCHAR(255) NULL;
ALTER TABLE `engine4_sescommunityads_ads` ADD `revselocation_type` VARCHAR(10) NULL;
ALTER TABLE `engine4_sescommunityads_ads` ADD `revselocation_distance` INT(11) NOT NULL DEFAULT "0";
ALTER TABLE `engine4_sescommunityads_targetads` ADD `interest_enable` VARCHAR(255) NULL;
ALTER TABLE `engine4_sescommunityads_packages` ADD `rentpackage` TINYINT(1) NOT NULL DEFAULT "0";
ALTER TABLE `engine4_sescommunityads_ads` ADD `widgetid` INT(11) NOT NULL;

DROP TABLE IF EXISTS `engine4_sescommunityads_locations`;
CREATE TABLE IF NOT EXISTS `engine4_sescommunityads_locations` (
`location_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`resource_id` INT( 11 ) NOT NULL ,
`lat` DECIMAL( 10, 8 ) NULL ,
`lng` DECIMAL( 11, 8 ) NULL ,
`resource_type` VARCHAR( 65 ) NOT NULL DEFAULT "sescommunityads",
`venue` VARCHAR(255) NULL,
`address` TEXT NULL,
`address2` TEXT NULL,
`city` VARCHAR(255) NULL,
`state` VARCHAR(255) NULL,
`zip` VARCHAR(255) NULL,
`country` VARCHAR(255) NULL,
`modified_date` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY `uniqueKey` (`resource_id`,`resource_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
