UPDATE `engine4_core_menuitems` SET `order` = '9' WHERE `engine4_core_menuitems`.`name` = 'sesmusic_main_lyricsbrowse';

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("sesmusic_main_uploadsong", "sesmusic", "Upload Song", "Sesmusic_Plugin_Menus", '{"route":"sesmusic_general","action":"create","upload":"song"}', "sesmusic_main", "", 8);

ALTER TABLE `engine4_sesmusic_albums` ADD `upload_param` VARCHAR(32) NOT NULL DEFAULT "album";
ALTER TABLE `engine4_sesmusic_albumsongs` ADD `upload_param` VARCHAR(32) NOT NULL DEFAULT "album";

INSERT IGNORE INTO `engine4_authorization_permissions`
SELECT
level_id as `level_id`,
"sesmusic_album" as `type`,
"uploadsong" as `name`,
0 as `value`,
NULL as `params`
FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");

INSERT IGNORE INTO `engine4_authorization_permissions`
SELECT
level_id as `level_id`,
"sesmusic_album" as `type`,
"uploadsong" as `name`,
0 as `value`,
NULL as `params`
FROM `engine4_authorization_levels` WHERE `type` IN("user");

INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`) VALUES
("sesmusic_song_new", "sesmusic", '{item:$subject} upload a new song {item:$object}:', "1", "5", "1", "3", "1", 1);