DELETE FROM `engine4_core_menuitems` WHERE `name` = 'sesmusic_admin_main_categories' AND `module` = 'sesmusic';
DELETE FROM `engine4_core_menuitems` WHERE `name` = 'sesmusic_admin_main_subcategories' AND `module` = 'sesmusic';
ALTER TABLE `engine4_sesmusic_categories` 
ADD `slug` varchar(255) NOT NULL,
ADD `title` varchar(255) null,
ADD `description` text null,
ADD `color` varchar(255) null ,
ADD `thumbnail` int(11) NOT NULL DEFAULT 0,
ADD `colored_icon` int(11) NOT NULL DEFAULT 0,
ADD `order` int(11) NOT NULL DEFAULT 0,
ADD `profile_type` int(11) null,
ADD `member_levels` varchar(255) null;
update `engine4_core_menuitems` SET `menu` = 'sesmusic_admin_main_songcategories',`label` = 'Categories & Mapping' WHERE `name` = 'sesmusic_admin_main_subsongcategories' AND `module` = 'sesmusic';
INSERT IGNORE INTO `engine4_core_menuitems`( `name`, `module`, `label`, `params`, `menu`, `enabled`, `custom`, `order`) VALUES ('sesmusic_admin_main_songcategories','sesmusic','Songs Categories & Profile Fields','{"route":"admin_default","module":"sesmusic","controller":"song-categories","action":"index"}','sesmusic_admin_main','1','0','7');
INSERT IGNORE INTO `engine4_core_menuitems`( `name`, `module`, `label`, `params`, `menu`, `enabled`, `custom`, `order`) VALUES ('sesmusic_admin_main_albumcategories','sesmusic','Albums Categories & Profile Fields','{"route":"admin_default","module":"sesmusic","controller":"categories","action":"index"}','sesmusic_admin_main','1','0','8');
INSERT IGNORE INTO `engine4_core_menuitems`( `name`, `module`, `label`, `params`, `menu`, `enabled`, `custom`, `order`) VALUES ('sesmusic_admin_main_subalbumcategories','sesmusic','Categories & Mapping','{"route":"admin_default","module":"sesmusic","controller":"categories","action":"index"}','sesmusic_admin_main_albumcategories','1','0','8');
INSERT IGNORE INTO `engine4_core_menuitems`( `name`, `module`, `label`, `params`, `menu`, `enabled`, `custom`, `order`) VALUES ('sesmusic_admin_main_albumsubfields','sesmusic','Form Questions','{"route":"admin_default","module":"sesmusic","controller":"fields"}','sesmusic_admin_main_albumcategories','1','0','8');
INSERT IGNORE INTO `engine4_core_menuitems`( `name`, `module`, `label`, `params`, `menu`, `enabled`, `custom`, `order`) VALUES ('sesmusic_admin_main_songsubfields','sesmusic','Form Questions','{"route":"admin_default","module":"sesmusic","controller":"song-fields"}','sesmusic_admin_main_songcategories','1','0','8');

CREATE TABLE IF NOT EXISTS `engine4_sesmusic_albumsong_fields_maps` (
  `field_id`  int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL,
  `order` smallint(6) NOT NULL,
   PRIMARY KEY (`field_id`,`option_id`,`child_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
CREATE TABLE IF NOT EXISTS `engine4_sesmusic_albumsong_fields_options` (
  `option_id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` int(11) NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order` smallint(6) NOT NULL DEFAULT '999',
     PRIMARY KEY (`option_id`),
   KEY `field_id` (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
CREATE TABLE IF NOT EXISTS `engine4_sesmusic_albumsong_fields_meta` (
  `field_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `label` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `alias` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `display` tinyint(1) UNSIGNED NOT NULL,
  `publish` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `search` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `show` tinyint(1) UNSIGNED DEFAULT '0',
  `order` smallint(3) UNSIGNED NOT NULL DEFAULT '999',
  `config` text COLLATE utf8_unicode_ci NOT NULL,
  `validators` text COLLATE utf8_unicode_ci,
  `filters` text COLLATE utf8_unicode_ci,
  `style` text COLLATE utf8_unicode_ci,
  `error` text COLLATE utf8_unicode_ci,
   PRIMARY KEY (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
CREATE TABLE IF NOT EXISTS `engine4_sesmusic_albumsong_fields_search` (
  `item_id` int(11) NOT NULL,
  `profile_type` enum('3','4','5') COLLATE utf8_unicode_ci DEFAULT NULL,
    PRIMARY KEY (`item_id`),
   KEY `profile_type` (`profile_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
CREATE TABLE IF NOT EXISTS `engine4_sesmusic_albumsong_fields_values` (
  `item_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `index` smallint(3) NOT NULL DEFAULT '0',
  `value` text COLLATE utf8_unicode_ci NOT NULL,
    PRIMARY KEY (`item_id`,`field_id`,`index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT IGNORE INTO `engine4_sesmusic_albumsong_fields_meta` (`field_id`, `type`, `label`, `description`, `alias`, `required`, `display`, `publish`, `search`, `show`, `order`, `config`, `validators`, `filters`, `style`, `error`) VALUES
(1, 'profile_type', 'Profile Type', '', 'profile_type', 1, 0, 0, 2, 0, 999, '', NULL, NULL, NULL, NULL);
INSERT IGNORE INTO `engine4_sesmusic_albumsong_fields_search` (`item_id`, `profile_type`) VALUES
(1, NULL);
INSERT IGNORE INTO `engine4_sesmusic_albumsong_fields_options` (`option_id`, `field_id`, `label`, `order`) VALUES
(1, 1, 'Rock Band', 0);
INSERT IGNORE INTO `engine4_sesmusic_albumsong_fields_maps` (`field_id`, `option_id`, `child_id`, `order`) VALUES
(0, 0, 1, 1);

CREATE TABLE IF NOT EXISTS `engine4_sesmusic_album_fields_options` (
  `option_id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` int(11) NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order` smallint(6) NOT NULL DEFAULT '999',
     PRIMARY KEY (`option_id`),
   KEY `field_id` (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
CREATE TABLE IF NOT EXISTS `engine4_sesmusic_album_fields_maps` (
  `field_id`  int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL,
  `order` smallint(6) NOT NULL,
     PRIMARY KEY (`field_id`,`option_id`,`child_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `engine4_sesmusic_album_fields_meta` (
  `field_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `label` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `alias` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `display` tinyint(1) UNSIGNED NOT NULL,
  `publish` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `search` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `show` tinyint(1) UNSIGNED DEFAULT '0',
  `order` smallint(3) UNSIGNED NOT NULL DEFAULT '999',
  `config` text COLLATE utf8_unicode_ci NOT NULL,
  `validators` text COLLATE utf8_unicode_ci,
  `filters` text COLLATE utf8_unicode_ci,
  `style` text COLLATE utf8_unicode_ci,
  `error` text COLLATE utf8_unicode_ci,
   PRIMARY KEY (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
CREATE TABLE IF NOT EXISTS `engine4_sesmusic_album_fields_search` (
  `item_id` int(11) NOT NULL,
  `profile_type` enum('3','4','5') COLLATE utf8_unicode_ci DEFAULT NULL,
    PRIMARY KEY (`item_id`),
   KEY `profile_type` (`profile_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
CREATE TABLE IF NOT EXISTS `engine4_sesmusic_album_fields_values` (
  `item_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `index` smallint(3) NOT NULL DEFAULT '0',
  `value` text COLLATE utf8_unicode_ci NOT NULL,
    PRIMARY KEY (`item_id`,`field_id`,`index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
INSERT IGNORE INTO `engine4_sesmusic_album_fields_meta` (`field_id`, `type`, `label`, `description`, `alias`, `required`, `display`, `publish`, `search`, `show`, `order`, `config`, `validators`, `filters`, `style`, `error`) VALUES
(1, 'profile_type', 'Profile Type', '', 'profile_type', 1, 0, 0, 2, 0, 999, '', NULL, NULL, NULL, NULL);
INSERT IGNORE INTO `engine4_sesmusic_album_fields_search` (`item_id`, `profile_type`) VALUES
(1, NULL);
INSERT IGNORE INTO `engine4_sesmusic_album_fields_options` (`option_id`, `field_id`, `label`, `order`) VALUES
(1, 1, 'Rock Band', 0);
ALTER TABLE `engine4_sesmusic_albums` ADD `new` TINYINT(1) NOT NULL DEFAULT '0' AFTER `hot`;
ALTER TABLE `engine4_sesmusic_artists`  ADD `sponsored` TINYINT(1) NOT NULL DEFAULT '0'  AFTER `offtheday`,  ADD `featured` TINYINT(1) NOT NULL DEFAULT '0'  AFTER `sponsored`;
ALTER TABLE `engine4_sesmusic_albumsongs` ADD `youtube_video` TEXT NOT NULL AFTER `song_url`;
UPDATE `engine4_core_menuitems` SET `params` = '{"route":"sesmusic_general_welcome","action":"welcome"}' WHERE `engine4_core_menuitems`.`name` = 'core_main_sesmusic' AND `module` = 'sesmusic';



